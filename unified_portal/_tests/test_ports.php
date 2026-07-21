<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing MySQL Ports</h2>";

$ports = [3306, 3307, 3308, 3309];
$username = "root";
$password = "";

foreach ($ports as $port) {
    echo "<h3>Testing port $port</h3>";
    
    try {
        $conn = new mysqli("localhost:$port", $username, $password);
        
        if ($conn->connect_error) {
            echo "<p style='color: red;'>✗ Port $port: Connection failed - " . $conn->connect_error . "</p>";
        } else {
            echo "<p style='color: green;'>✓ Port $port: Connection successful!</p>";
            echo "<p>Server: " . $conn->server_info . "</p>";
            
            if ($conn->query("CREATE DATABASE IF NOT EXISTS wpu_medical")) {
                echo "<p style='color: green;'>✓ Database 'wpu_medical' created or exists</p>";
                
                if ($conn->select_db("wpu_medical")) {
                    echo "<p style='color: green;'>✓ Database selected successfully</p>";
                    
                    $result = $conn->query("SHOW TABLES");
                    if ($result) {
                        echo "<p>Tables found: " . $result->num_rows . "</p>";
                        while ($row = $result->fetch_array()) {
                            echo "<p>- " . $row[0] . "</p>";
                        }
                    }
                } else {
                    echo "<p style='color: red;'>✗ Failed to select database</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ Failed to create database</p>";
            }
            
            $conn->close();
            break;
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Port $port: Exception - " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";
echo "<h3>Recommendation:</h3>";
echo "<p>Update your <code>config/connect.php</code> file with the working port number.</p>";
?>