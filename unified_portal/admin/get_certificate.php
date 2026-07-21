<?php
session_start();
require_once '../config/connect.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'No certificate ID provided']);
    exit;
}

$id = intval($_GET['id']);

// Query to get certificate with ALL fields
$query = "SELECT * FROM medical_certificates WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Certificate not found']);
    exit;
}

$certificate = $result->fetch_assoc();

echo json_encode([
    'success' => true,
    'certificate' => $certificate,
]);

$stmt->close();
$conn->close();
?>