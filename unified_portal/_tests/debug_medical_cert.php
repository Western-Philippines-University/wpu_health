<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug Medical Certificate Processing</h2>";

try {
    echo "<p>Step 1: Including connect.php...</p>";
    require_once 'config/connect.php';
    echo "<p style='color: green;'>✓ connect.php included successfully</p>";
    
    if (!$conn) {
        echo "<p style='color: red;'>✗ Connection object is null</p>";
        exit;
    }
    
    if ($conn->connect_error) {
        echo "<p style='color: red;'>✗ Database connection failed: " . $conn->connect_error . "</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    $result = $conn->query("SHOW TABLES LIKE 'medical_certificates'");
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Table 'medical_certificates' exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Table 'medical_certificates' does not exist</p>";
        echo "<p>Creating table...</p>";
        
        $sql = "CREATE TABLE IF NOT EXISTS medical_certificates (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            age INT NOT NULL,
            gender ENUM('Male', 'Female', 'Other') NOT NULL,
            civil_status ENUM('Single', 'Married', 'Divorced', 'Widowed') NOT NULL,
            address TEXT NOT NULL,
            examination_date DATE NOT NULL,
            reason TEXT NOT NULL,
            findings_fit BOOLEAN DEFAULT FALSE,
            findings_impression BOOLEAN DEFAULT FALSE,
            impression_text TEXT,
            advice TEXT,
            receipt_no VARCHAR(100),
            date_issued DATE,
            mc_no VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>✓ Table created successfully</p>";
        } else {
            echo "<p style='color: red;'>✗ Error creating table: " . $conn->error . "</p>";
        }
    }
    
    echo "<p>Testing simple query...</p>";
    $test_result = $conn->query("SELECT 1 as test");
    if ($test_result) {
        echo "<p style='color: green;'>✓ Simple query successful</p>";
    } else {
        echo "<p style='color: red;'>✗ Simple query failed: " . $conn->error . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception occurred: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
    echo "<p>Trace: " . $e->getTraceAsString() . "</p>";
}

echo "<hr>";
echo "<h3>PHP Info:</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>MySQL Extension: " . (extension_loaded('mysqli') ? 'Loaded' : 'Not Loaded') . "</p>";
echo "<p>Error Reporting: " . error_reporting() . "</p>";
?>