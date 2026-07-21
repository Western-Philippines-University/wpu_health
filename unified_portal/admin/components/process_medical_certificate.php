<?php
/**
 * Medical Certificate Processing Script
 * Handles creation and updates of medical certificates
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__.'/../../config/connect.php';

/**
 * Output JSON once. Under Laravel's UnifiedPortalController (WPU_LARAVEL_BRIDGE), the script must
 * return from the include — do not fall through to a second echo (that breaks response.json()).
 */
function wpu_certificate_json_send(array $payload): void
{
    if (! headers_sent()) {
        header('Content-Type: application/json; charset=UTF-8');
    }
    echo json_encode($payload);
    if (! defined('WPU_LARAVEL_BRIDGE') || ! WPU_LARAVEL_BRIDGE) {
        exit;
    }
}

// Check database connection
if (! $conn || $conn->connect_error) {
    wpu_certificate_json_send([
        'success' => false,
        'message' => 'Database connection failed: '.($conn ? $conn->connect_error : 'No connection object'),
    ]);

    return;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wpu_certificate_json_send([
        'success' => false,
        'message' => 'Invalid request method. POST required.',
    ]);

    return;
}

try {
    // Collect and sanitize input data
    $name = trim($_POST['name'] ?? '');
    $age = isset($_POST['age']) ? (int) $_POST['age'] : 0;
    $gender = trim($_POST['gender'] ?? '');
    $civil_status = trim($_POST['civil_status'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $examination_date = trim($_POST['date'] ?? '');
    $reason = trim($_POST['reason'] ?? '');

    // Process findings checkboxes (form may send findings[] or findings)
    $findings = $_POST['findings'] ?? [];
    if (! is_array($findings)) {
        $findings = [$findings];
    }
    $findings_fit = in_array('fit', $findings, true) ? 1 : 0;
    $findings_impression = in_array('impression', $findings, true) ? 1 : 0;

    // Optional fields
    $impression_text = trim($_POST['impression_text'] ?? '');
    $advice = trim($_POST['advice'] ?? '');
    $receipt_no = trim($_POST['receipt_no'] ?? '');
    $date_issued = trim($_POST['date_issued'] ?? '');
    $mc_no = trim($_POST['mc_no'] ?? '');

    // Validate required fields
    if (empty($name) || $age <= 0 || empty($gender) || empty($civil_status)
        || empty($address) || empty($examination_date) || empty($reason)) {
        throw new Exception('All required fields must be filled out.');
    }

    // Validate age range
    if ($age < 1 || $age > 150) {
        throw new Exception('Please enter a valid age between 1 and 150.');
    }

    // Validate date format
    $date_obj = DateTime::createFromFormat('Y-m-d', $examination_date);
    if (! $date_obj || $date_obj->format('Y-m-d') !== $examination_date) {
        throw new Exception('Invalid examination date format.');
    }

    // Prepare SQL statement
    $sql = 'INSERT INTO medical_certificates (
                name, age, gender, civil_status, address,
                examination_date, reason,
                findings_fit, findings_impression,
                impression_text, advice,
                receipt_no, date_issued, mc_no
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

    $stmt = $conn->prepare($sql);
    if (! $stmt) {
        throw new Exception('Failed to prepare statement: '.$conn->error);
    }

    // 14 params: s i s s s s s i i s s s s s
    $stmt->bind_param(
        'sisssssiisssss',
        $name,
        $age,
        $gender,
        $civil_status,
        $address,
        $examination_date,
        $reason,
        $findings_fit,
        $findings_impression,
        $impression_text,
        $advice,
        $receipt_no,
        $date_issued,
        $mc_no
    );

    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Medical certificate saved successfully!',
            'id' => $conn->insert_id,
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Error saving medical certificate: '.$stmt->error,
        ];
    }
    $stmt->close();
} catch (Throwable $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
    ];
}

wpu_certificate_json_send($response);

return;
