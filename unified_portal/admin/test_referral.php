<?php
header('Content-Type: text/plain');
require_once '../config/connect.php';

echo "Testing database connection and table structure...\n\n";

// Test connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "✓ Database connection successful\n";

// Test table structure
$result = $conn->query("SHOW COLUMNS FROM referrals");
if ($result) {
    echo "✓ Table 'referrals' exists\n";
    echo "Columns in referrals table:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- " . $row['Field'] . "\n";
    }
} else {
    echo "✗ Table 'referrals' does not exist or cannot be accessed\n";
}

$conn->close();
?>