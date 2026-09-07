<?php
/**
 * Common Utility Functions
 * Shared functions used across the medical system
 */

// Prevent direct access
if (!defined('DB_CONFIG_INCLUDED')) {
    require_once __DIR__ . '/../config/connect.php';
}

/**
 * Sanitize string input
 * 
 * @param string $input The input to sanitize
 * @return string Sanitized input
 */
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 * 
 * @param string $email The email to validate
 * @return bool True if valid, false otherwise
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate date format (Y-m-d)
 * 
 * @param string $date The date to validate
 * @return bool True if valid, false otherwise
 */
function validate_date($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

/**
 * Format date for display
 * 
 * @param string $date The date to format
 * @param string $format The desired format (default: 'M d, Y')
 * @return string Formatted date
 */
function format_date($date, $format = 'M d, Y') {
    if (empty($date)) return '';
    $d = new DateTime($date);
    return $d->format($format);
}

/**
 * Calculate age from birthdate
 * 
 * @param string $birthdate The birthdate (Y-m-d format)
 * @return int Age in years
 */
function calculate_age($birthdate) {
    $today = new DateTime();
    $dob = new DateTime($birthdate);
    $age = $today->diff($dob);
    return $age->y;
}

/**
 * Generate random string for tokens
 * 
 * @param int $length Length of the string
 * @return string Random string
 */
function generate_random_string($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Log activity to database
 * 
 * @param mysqli $conn Database connection
 * @param string $username Username performing action
 * @param string $action Action description
 * @param string $details Additional details
 * @return bool Success status
 */
function log_activity($conn, $username, $action, $details = '') {
    $stmt = $conn->prepare("INSERT INTO activity_logs (username, `action`, details, created_at) VALUES (?, ?, ?, NOW())");
    if ($stmt) {
        $stmt->bind_param("sss", $username, $action, $details);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    return false;
}

/**
 * Send JSON response and exit
 * 
 * @param bool $success Success status
 * @param string $message Response message
 * @param array $data Additional data
 */
function send_json_response($success, $message, $data = []) {
    header('Content-Type: application/json');
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}

/**
 * Check if user is logged in
 * 
 * @param bool $redirect Whether to redirect if not logged in
 * @return bool Login status
 */
function check_login($redirect = true) {
    session_start();
    $logged_in = isset($_SESSION['admin_username']);
    
    if (!$logged_in && $redirect) {
        header('Location: ?page=login');
        exit;
    }
    
    return $logged_in;
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool True if valid, false otherwise
 */
function verify_csrf_token($token) {
    return isset($_SESSION['form_token']) && hash_equals($_SESSION['form_token'], $token);
}

/**
 * Generate receipt number
 * 
 * @param mysqli $conn Database connection
 * @param string $prefix Receipt prefix
 * @param string $table Table name to check
 * @param string $column Column name for receipt number
 * @return string Generated receipt number
 */
function generate_receipt_number($conn, $prefix = 'WPU-MC-', $table = 'medical_certificates', $column = 'receipt_no') {
    $year = date('Y');
    
    $stmt = $conn->prepare("SELECT $column FROM $table WHERE $column LIKE ? ORDER BY id DESC LIMIT 1");
    $likePattern = $prefix . $year . '%';
    $stmt->bind_param("s", $likePattern);
    $stmt->execute();
    $result = $stmt->get_result();
    $lastReceipt = $result->fetch_assoc();
    $stmt->close();
    
    if ($lastReceipt && preg_match('/' . preg_quote($prefix) . $year . '-(\d+)/', $lastReceipt[$column], $matches)) {
        $nextNumber = intval($matches[1]) + 1;
    } else {
        $nextNumber = 1;
    }
    
    return $prefix . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
}

/**
 * Validate age range
 * 
 * @param int $age Age to validate
 * @param int $min Minimum age
 * @param int $max Maximum age
 * @return bool True if valid, false otherwise
 */
function validate_age($age, $min = 1, $max = 150) {
    return is_numeric($age) && $age >= $min && $age <= $max;
}

/**
 * Get user IP address
 * 
 * @return string IP address
 */
function get_user_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Format bytes to human readable size
 * 
 * @param int $bytes Bytes to format
 * @param int $precision Decimal precision
 * @return string Formatted size
 */
function format_bytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Check if string contains only letters and spaces
 * 
 * @param string $str String to check
 * @return bool True if valid, false otherwise
 */
function is_alpha_space($str) {
    return preg_match('/^[a-zA-Z\s]+$/', $str);
}

/**
 * Truncate string with ellipsis
 * 
 * @param string $str String to truncate
 * @param int $length Maximum length
 * @param string $ellipsis Ellipsis string
 * @return string Truncated string
 */
function truncate_string($str, $length = 50, $ellipsis = '...') {
    if (strlen($str) <= $length) {
        return $str;
    }
    return substr($str, 0, $length - strlen($ellipsis)) . $ellipsis;
}
?>
