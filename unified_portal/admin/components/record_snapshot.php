<?php
require_once __DIR__ . '/../../includes/wpu_security.php';
wpu_bootstrap_admin_api();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/patient_record_quick_view_lib.php';
$pdo = getDBConnection();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$module = isset($_GET['module']) ? strtolower(trim((string) $_GET['module'])) : '';
$revisionId = isset($_GET['revision_id']) ? (int) $_GET['revision_id'] : 0;

if ($id <= 0 || ($module !== 'dental' && $module !== 'health')) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        'SELECT pr.*, pt.type_name, d.name AS department, ct.case_name
         FROM patient_records pr
         LEFT JOIN patient_types pt ON pr.patient_type_id = pt.id
         LEFT JOIN departments d ON pr.department_id = d.id
         LEFT JOIN case_types ct ON pr.case_type_id = ct.id
         WHERE pr.id = ? AND pr.module_type = ?'
    );
    $stmt->execute([$id, $module]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $row) {
        echo json_encode(['success' => false, 'message' => 'Record not found']);
        exit;
    }

    if ($revisionId > 0) {
        $rev = patient_record_edit_history_get($pdo, $revisionId, $id);
        if ($rev === null) {
            echo json_encode(['success' => false, 'message' => 'Version not found']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'record' => $rev['payload'],
            'quick_view_is_prior' => true,
            'is_revision' => true,
            'edited_by' => $rev['edited_by'],
            'edited_at' => $rev['created_at'],
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'record' => patient_record_quick_view_payload_from_row($row),
        'quick_view_is_prior' => false,
        'is_revision' => false,
    ]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
