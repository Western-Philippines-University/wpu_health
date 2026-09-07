<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing Medical Certificate Processing</h1>";

echo "<h2>Testing Database Connection</h2>";
require_once 'config/connect.php';

if ($conn && !$conn->connect_error) {
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    echo "<p>Server: " . $conn->server_info . "</p>";
    echo "<p>Database: " . $dbname . "</p>";
} else {
    echo "<p style='color: red;'>✗ Database connection failed</p>";
    if ($conn) {
        echo "<p>Error: " . $conn->connect_error . "</p>";
    }
}

if ($conn && !$conn->connect_error) {
    echo "<h2>Testing Database Tables</h2>";
    
    $result = $conn->query("SHOW TABLES LIKE 'medical_certificates'");
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✓ medical_certificates table exists</p>";
        
        $result = $conn->query("DESCRIBE medical_certificates");
        if ($result) {
            echo "<h3>Table Structure:</h3>";
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . $row['Default'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: red;'>✗ medical_certificates table does not exist</p>";
    }
}

echo "<h2>Testing Form Submission</h2>";
echo "<form method='post' action='components/process_medical_certificate.php'>";
echo "<p><label>Name: <input type='text' name='name' value='Test User' required></label></p>";
echo "<p><label>Age: <input type='number' name='age' value='25' required></label></p>";
echo "<p><label>Gender: <select name='gender' required><option value='Male'>Male</option><option value='Female'>Female</option></select></label></p>";
echo "<p><label>Civil Status: <select name='civil_status' required><option value='Single'>Single</option><option value='Married'>Married</option></select></label></p>";
echo "<p><label>Address: <textarea name='address' required>Test Address</textarea></label></p>";
echo "<p><label>Date: <input type='date' name='date' value='2024-01-15' required></label></p>";
echo "<p><label>Reason: <input type='text' name='reason' value='Test reason' required></label></p>";
echo "<p><label>Findings: <input type='checkbox' name='findings[]' value='fit'> Fit</label></p>";
echo "<p><label>Advice: <textarea name='advice'>Test advice</textarea></label></p>";
echo "<p><label>Receipt No: <input type='text' name='receipt_no' value='R001' readonly></label></p>";
echo "<p><label>Date Issued: <input type='date' name='date_issued' value='2024-01-15' readonly></label></p>";
echo "<p><label>MC No: <input type='text' name='mc_no' value='MC001'></label></p>";
echo "<p><input type='submit' value='Test Submit'></p>";
echo "</form>";

echo "<h2>Testing Direct PHP Processing</h2>";
echo "<p><a href='components/process_medical_certificate.php' target='_blank'>Test direct access to process_medical_certificate.php</a></p>";

echo "<h2>Environment Information</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";
?>