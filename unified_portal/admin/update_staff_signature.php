<?php
require_once '../config/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['staff_name']) ? trim($_POST['staff_name']) : '';
    $position = isset($_POST['staff_position']) ? trim($_POST['staff_position']) : '';
    $license_no = isset($_POST['staff_license']) ? trim($_POST['staff_license']) : '';
    
    if (empty($name) || empty($position) || empty($license_no)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    try {
        // Check if table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'staff_signatures'");
        if ($table_check->num_rows == 0) {
            // Create the table
            $create_table = "CREATE TABLE staff_signatures (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL DEFAULT 'MICAELLA T. BAGALANON-LABUTOY, MD, OHP',
                position VARCHAR(255) NOT NULL DEFAULT 'University Physician',
                license_no VARCHAR(100) NOT NULL DEFAULT 'License. No. 0148115',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            $conn->query($create_table);
        }
        
        // Check if there's already an entry
        $check_query = "SELECT id FROM staff_signatures LIMIT 1";
        $check_result = $conn->query($check_query);
        
        if ($check_result && $check_result->num_rows > 0) {
            // Update existing entry
            $update_query = "UPDATE staff_signatures SET name = ?, position = ?, license_no = ? WHERE id = 1";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sss", $name, $position, $license_no);
        } else {
            // Insert new entry
            $insert_query = "INSERT INTO staff_signatures (name, position, license_no) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sss", $name, $position, $license_no);
        }
        
        if ($stmt->execute()) {
            require_once __DIR__.'/../../includes/wpu_cache.php';
            wpu_cache_forget('ref:staff_signature');
            echo json_encode(['success' => true, 'message' => 'Staff signature updated successfully']);
        } else {
            throw new Exception("Failed to update staff signature: " . $stmt->error);
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