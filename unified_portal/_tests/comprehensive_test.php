<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Comprehensive System Test</h1>";

echo "<h2>Test 1: Basic PHP Functionality</h2>";
echo "<p>PHP Version: " . phpversion() . " ✓</p>";
echo "<p>MySQL Extension: " . (extension_loaded('mysqli') ? 'Loaded ✓' : 'Not Loaded ✗') . "</p>";

echo "<h2>Test 2: Database Connection</h2>";
try {
    require_once 'config/connect.php';
    
    if ($conn && !$conn->connect_error) {
        echo "<p style='color: green;'>✓ Database connection successful!</p>";
        echo "<p>Server: " . $conn->server_info . "</p>";
        echo "<p>Database: wpu_medical</p>";
        
        $result = $conn->query("SELECT DATABASE() as current_db");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>Current Database: " . $row['current_db'] . "</p>";
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
    echo "<p style='color: red;'>✗ Exception: " . $e->getMessage() . "</p>";
}

echo "<h2>Test 3: Table Existence</h2>";
if ($conn && !$conn->connect_error) {
    $tables = ['medical_certificates', 'referrals'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✓ Table '$table' exists</p>";
        } else {
            echo "<p style='color: red;'>✗ Table '$table' does not exist</p>";
        }
    }
}

echo "<h2>Test 4: Form Processing Simulation</h2>";
if ($conn && !$conn->connect_error) {
    $_POST = [
        'name' => 'Test User',
        'age' => '25',
        'gender' => 'Male',
        'civil_status' => 'Single',
        'address' => 'Test Address',
        'date' => '2024-01-15',
        'reason' => 'Test Reason',
        'findings' => ['fit'],
        'impression_text' => 'Test Impression',
        'advice' => 'Test Advice',
        'receipt_no' => 'R123456789',
        'date_issued' => '2024-01-15',
        'mc_no' => 'MC001'
    ];
    
    echo "<p>Simulated form data:</p>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    try {
        ob_start();
        include 'components/process_medical_certificate.php';
        $output = ob_get_clean();
        
        echo "<p style='color: green;'>✓ Processing completed</p>";
        echo "<p>Output:</p>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Exception: " . $e->getMessage() . "</p>";
    } catch (Error $e) {
        echo "<p style='color: red;'>✗ Fatal Error: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>Test 5: Error Log Check</h2>";
$error_log_paths = [
    'C:/xampp/apache/logs/error.log',
    'C:/xampp/php/logs/php_error_log',
    'C:/xampp/mysql/data/mysql_error.log'
];

foreach ($error_log_paths as $log_path) {
    if (file_exists($log_path)) {
        echo "<p>Log file exists: $log_path</p>";
        $log_size = filesize($log_path);
        echo "<p>Log size: " . number_format($log_size) . " bytes</p>";
        
        if ($log_size > 0) {
            echo "<p>Last few lines:</p>";
            $lines = file($log_path);
            $last_lines = array_slice($lines, -5);
            echo "<pre>" . htmlspecialchars(implode('', $last_lines)) . "</pre>";
        }
    } else {
        echo "<p>Log file not found: $log_path</p>";
    }
}

echo "<hr>";
echo "<h2>Recommendations:</h2>";
echo "<ol>";
echo "<li>Run <code>setup_database.php</code> first to create the database and tables</li>";
echo "<li>Check XAMPP Control Panel to ensure MySQL and Apache are running</li>";
echo "<li>Verify the MySQL port in XAMPP (usually 3306, not 3307)</li>";
echo "<li>Check if the 'wpu_medical' database exists in phpMyAdmin</li>";
echo "</ol>";
?>