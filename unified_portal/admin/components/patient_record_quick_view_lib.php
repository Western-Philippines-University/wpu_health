<?php

declare(strict_types=1);

/**
 * Shared helpers for Quick View: frozen payload matches buildHistorySnapshotHtml() in admin.php.
 */

function fetch_patient_record_for_quick_view(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT pr.*, pt.type_name, d.name AS department, ct.case_name
         FROM patient_records pr
         LEFT JOIN patient_types pt ON pr.patient_type_id = pt.id
         LEFT JOIN departments d ON pr.department_id = d.id
         LEFT JOIN case_types ct ON pr.case_type_id = ct.id
         WHERE pr.id = ?'
    );
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

/**
 * @return array<string, mixed>
 */
function patient_record_quick_view_payload_from_row(array $row): array
{
    return [
        'full_name' => $row['full_name'] ?? '',
        'student_id' => $row['student_id'] ?? '',
        'visit_date' => $row['visit_date'] ?? '',
        'type_name' => $row['type_name'] ?? '',
        'department' => $row['department'] ?? '',
        'doctor' => $row['doctor'] ?? '',
        'case_name' => $row['case_name'] ?? '',
        'diagnosis' => $row['diagnosis'] ?? '',
        'treatment' => $row['treatment'] ?? '',
        'subjective' => $row['subjective'] ?? '',
        'objectives' => $row['objectives'] ?? '',
        'diagnostics' => $row['diagnostics'] ?? '',
        'assessment' => $row['assessment'] ?? '',
        'plan' => $row['plan'] ?? '',
        'gender' => $row['gender'] ?? '',
        'age' => $row['age'] ?? '',
        'marital_status' => $row['marital_status'] ?? '',
        'religion' => $row['religion'] ?? '',
        'is_minor' => $row['is_minor'] ?? '',
        'guardian_name' => $row['guardian_name'] ?? '',
        'phone_number' => $row['phone_number'] ?? '',
        'address' => $row['address'] ?? '',
    ];
}

function patient_record_quick_view_encode_snapshot(array $row): string
{
    $json = json_encode(
        patient_record_quick_view_payload_from_row($row),
        JSON_UNESCAPED_UNICODE
    );

    return $json !== false ? $json : '{}';
}

/**
 * @return array{0: array<string, mixed>, 1: bool} [payload, is_prior_version]
 */
function patient_record_quick_view_resolve_for_modal(array $row): array
{
    $raw = $row['visit_quick_snapshot'] ?? null;
    unset($row['visit_quick_snapshot']);

    if ($raw !== null && $raw !== '') {
        $decoded = json_decode((string) $raw, true);
        if (is_array($decoded) && array_key_exists('full_name', $decoded)) {
            return [$decoded, true];
        }
    }

    return [patient_record_quick_view_payload_from_row($row), false];
}

function patient_record_edit_history_ensure_table(PDO $pdo): void
{
    static $ready = false;
    if ($ready) {
        return;
    }

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS `patient_record_edit_history` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `patient_record_id` int(11) NOT NULL,
            `edited_by` varchar(50) DEFAULT NULL,
            `snapshot` mediumtext NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `idx_record_created` (`patient_record_id`, `created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci'
    );
    $ready = true;
}

/**
 * Copy the single legacy visit_quick_snapshot into history when a record has no rows yet.
 *
 * @param  list<int>  $recordIds
 */
function patient_record_edit_history_backfill(PDO $pdo, array $recordIds): void
{
    patient_record_edit_history_ensure_table($pdo);

    $ids = array_values(array_unique(array_filter(array_map('intval', $recordIds), static fn (int $id): bool => $id > 0)));
    if ($ids === []) {
        return;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "INSERT INTO patient_record_edit_history (patient_record_id, edited_by, snapshot, created_at)
            SELECT pr.id, NULL, pr.visit_quick_snapshot, NOW()
            FROM patient_records pr
            WHERE pr.id IN ($placeholders)
              AND pr.visit_quick_snapshot IS NOT NULL
              AND pr.visit_quick_snapshot != ''
              AND NOT EXISTS (
                  SELECT 1 FROM patient_record_edit_history h WHERE h.patient_record_id = pr.id
              )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($ids);
}

function patient_record_edit_history_append(PDO $pdo, int $recordId, string $snapshotJson, string $editedBy): bool
{
    if ($recordId <= 0 || $snapshotJson === '') {
        return false;
    }

    patient_record_edit_history_ensure_table($pdo);

    $stmt = $pdo->prepare(
        'INSERT INTO patient_record_edit_history (patient_record_id, edited_by, snapshot, created_at)
         VALUES (?, ?, ?, NOW())'
    );

    return $stmt->execute([$recordId, $editedBy !== '' ? $editedBy : null, $snapshotJson]);
}

/**
 * @param  list<int>  $recordIds
 * @return array<int, list<array{id:int,patient_record_id:int,edited_by:?string,created_at:string}>>
 */
function patient_record_edit_history_for_records(PDO $pdo, array $recordIds): array
{
    patient_record_edit_history_ensure_table($pdo);
    patient_record_edit_history_backfill($pdo, $recordIds);

    $ids = array_values(array_unique(array_filter(array_map('intval', $recordIds), static fn (int $id): bool => $id > 0)));
    if ($ids === []) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare(
        "SELECT id, patient_record_id, edited_by, created_at
         FROM patient_record_edit_history
         WHERE patient_record_id IN ($placeholders)
         ORDER BY created_at DESC, id DESC"
    );
    $stmt->execute($ids);

    $grouped = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $rid = (int) $row['patient_record_id'];
        $grouped[$rid][] = [
            'id' => (int) $row['id'],
            'patient_record_id' => $rid,
            'edited_by' => $row['edited_by'] !== null && $row['edited_by'] !== '' ? (string) $row['edited_by'] : null,
            'created_at' => (string) $row['created_at'],
        ];
    }

    return $grouped;
}

/**
 * @return array{payload: array<string, mixed>, edited_by: ?string, created_at: string}|null
 */
function patient_record_edit_history_get(PDO $pdo, int $revisionId, int $recordId): ?array
{
    patient_record_edit_history_ensure_table($pdo);

    $stmt = $pdo->prepare(
        'SELECT snapshot, edited_by, created_at
         FROM patient_record_edit_history
         WHERE id = ? AND patient_record_id = ?
         LIMIT 1'
    );
    $stmt->execute([$revisionId, $recordId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (! $row) {
        return null;
    }

    $decoded = json_decode((string) $row['snapshot'], true);
    if (! is_array($decoded) || ! array_key_exists('full_name', $decoded)) {
        return null;
    }

    return [
        'payload' => $decoded,
        'edited_by' => $row['edited_by'] !== null && $row['edited_by'] !== '' ? (string) $row['edited_by'] : null,
        'created_at' => (string) $row['created_at'],
    ];
}
