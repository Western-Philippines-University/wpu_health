<?php
require_once __DIR__ . '/../includes/wpu_security.php';
wpu_bootstrap_admin_api();
require_once '../config/connect.php';

header('Content-Type: application/json');

try {
    // Check if table exists, if not create it
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
        
        // Insert default data
        $insert_default = "INSERT INTO staff_signatures (name, position, license_no) VALUES 
                          ('MICAELLA T. BAGALANON-LABUTOY, MD, OHP', 'University Physician', 'License. No. 0148115')";
        $conn->query($insert_default);
    }
    
    // Get staff signature data
    $query = "SELECT * FROM staff_signatures LIMIT 1";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        echo json_encode(['success' => true, 'staff' => $staff]);
    } else {
        // Return default values if no data found
        echo json_encode(['success' => true, 'staff' => [
            'name' => 'MICAELLA T. BAGALANON-LABUTOY, MD, OHP',
            'position' => 'University Physician',
            'license_no' => 'License. No. 0148115'
        ]]);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>