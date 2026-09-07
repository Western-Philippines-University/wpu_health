<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (! isset($_SESSION['admin_username']) && function_exists('auth') && auth()->guard('admin')->check()) {
    $_SESSION['admin_username'] = auth()->guard('admin')->user()->username;
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

if (! isset($_SESSION['admin_username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/patient_record_quick_view_lib.php';
$pdo = getDBConnection();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$module = isset($_GET['module']) ? strtolower(trim((string) $_GET['module'])) : '';

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

    [$payload, $isPrior] = patient_record_quick_view_resolve_for_modal($row);

    echo json_encode([
        'success' => true,
        'record' => $payload,
        'quick_view_is_prior' => $isPrior,
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
