<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing Form Submission Processing</h2>";

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

echo "<p>Simulated POST data:</p>";
echo "<pre>" . print_r($_POST, true) . "</pre>";

echo "<hr>";
echo "<p>Now testing the actual processing...</p>";

try {
    ob_start();
    include 'components/process_medical_certificate.php';
    $output = ob_get_clean();
    
    echo "<p style='color: green;'>✓ Processing completed</p>";
    echo "<p>Output:</p>";
    echo "<pre>" . htmlspecialchars($output) . "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception occurred: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
} catch (Error $e) {
    echo "<p style='color: red;'>✗ Fatal Error: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}
?>