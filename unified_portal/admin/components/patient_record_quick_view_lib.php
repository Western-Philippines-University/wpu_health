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
