<?php
require_once __DIR__ . '/../includes/wpu_security.php';
wpu_bootstrap_admin_api();
require_once '../config/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID parameter is required']);
    exit;
}

$id = (int)$_GET['id'];

try {
    // Check if referral exists
    $check_query = "SELECT id FROM referrals WHERE id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Referral not found']);
        exit;
    }
    
    // Delete the referral
    $delete_query = "DELETE FROM referrals WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $id);
    
    if ($delete_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Referral deleted successfully']);
    } else {
        throw new Exception('Failed to delete referral');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>