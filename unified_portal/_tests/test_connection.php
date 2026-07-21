<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>";

try {
    require_once 'config/connect.php';
    
    if ($conn && !$conn->connect_error) {
        echo "<p style='color: green;'>✓ Database connection successful!</p>";
        echo "<p>Server: " . $conn->server_info . "</p>";
        echo "<p>Database: wpu_medical</p>";
        
        $result = $conn->query("SHOW TABLES");
        if ($result) {
            echo "<h3>Available Tables:</h3>";
            echo "<ul>";
            while ($row = $result->fetch_array()) {
                echo "<li>" . $row[0] . "</li>";
            }
            echo "</ul>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Database connection failed!</p>";
        if ($conn) {
            echo "<p>Error: " . $conn->connect_error . "</p>";
        } else {
            echo "<p>Error: Connection object is null</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception occurred: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>PHP Info:</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>MySQL Extension: " . (extension_loaded('mysqli') ? 'Loaded' : 'Not Loaded') . "</p>";
?>