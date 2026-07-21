<?php
header('Content-Type: application/json');
require_once '../config/connect.php';

// Collect form data
$patient_id = $_POST['patient_id'] ?? null;
$diagnosis = $_POST['diagnosis'] ?? '';
$recommendation = $_POST['recommendation'] ?? '';
$doctor_name = $_POST['doctor_name'] ?? '';
$remarks = $_POST['remarks'] ?? '';

if (!$patient_id || !$diagnosis) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO medical_certificates (patient_id, diagnosis, recommendation, doctor_name, remarks, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issss", $patient_id, $diagnosis, $recommendation, $doctor_name, $remarks);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database insert failed']);
    }

    $stmt->close();
    $conn->close();
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;
