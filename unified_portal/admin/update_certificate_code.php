<?php
require_once __DIR__ . '/../includes/wpu_security.php';
wpu_bootstrap_admin_api();
require_once '../config/connect.php';
require_once '../includes/wpu_cache.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $certificate_code = isset($_POST['certificate_code']) ? trim($_POST['certificate_code']) : '';
    $referral_code = isset($_POST['referral_code']) ? trim($_POST['referral_code']) : '';
    
    // Validate input
    if (empty($certificate_code) || empty($referral_code)) {
        echo json_encode(['success' => false, 'message' => 'Both certificate code and referral code are required']);
        exit;
    }
    
    try {
        // Create the table if it doesn't exist
        $create_table = "CREATE TABLE IF NOT EXISTS certificate_codes (
            id INT PRIMARY KEY AUTO_INCREMENT,
            certificate_code VARCHAR(255) NOT NULL DEFAULT 'WPU-QSF-GASS-HSO-01 Rev.00 (09.20.24)',
            referral_code VARCHAR(255) NOT NULL DEFAULT 'WPU-QSF-GASS-HSO-12 Rev.00 (09.20.24)',
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        if (!$conn->query($create_table)) {
            throw new Exception("Failed to create table: " . $conn->error);
        }
        
        // Check if there's already an entry
        $check_query = "SELECT id FROM certificate_codes LIMIT 1";
        $check_result = $conn->query($check_query);
        
        if ($check_result && $check_result->num_rows > 0) {
            // Update existing entry
            $update_query = "UPDATE certificate_codes SET certificate_code = ?, referral_code = ? WHERE id = 1";
            $stmt = $conn->prepare($update_query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ss", $certificate_code, $referral_code);
        } else {
            // Insert new entry
            $insert_query = "INSERT INTO certificate_codes (certificate_code, referral_code) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ss", $certificate_code, $referral_code);
        }
        
        if ($stmt->execute()) {
            wpu_cache_forget('ref:certificate_codes');
            echo json_encode(['success' => true, 'message' => 'Certificate codes updated successfully']);
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
