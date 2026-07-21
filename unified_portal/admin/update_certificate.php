<?php
require_once __DIR__ . '/../includes/wpu_security.php';
wpu_bootstrap_admin_api();
require_once '../config/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid certificate ID']);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$age = isset($_POST['age']) ? trim($_POST['age']) : '';
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
$examination_date = isset($_POST['examination_date']) ? trim($_POST['examination_date']) : '';
$mc_no = isset($_POST['mc_no']) ? trim($_POST['mc_no']) : '';
$diagnosis = isset($_POST['diagnosis']) ? trim($_POST['diagnosis']) : '';
$treatment = isset($_POST['treatment']) ? trim($_POST['treatment']) : '';
$remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

$query = "UPDATE medical_certificates SET 
            name = ?, 
            age = ?, 
            gender = ?, 
            examination_date = ?, 
            mc_no = ?, 
            reason = ?, 
            impression_text = ?, 
            advice = ?, 
            updated_at = NOW() 
          WHERE id = ?";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ssssssssi", 
    $name, 
    $age, 
    $gender, 
    $examination_date, 
    $mc_no, 
    $diagnosis,   
    $treatment,   
    $remarks,     
    $id
);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Certificate updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No changes made to the certificate']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update certificate: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>