<?php
/**
 * Common Helper Functions
 * Consolidated utility functions used across all WPU Medical System modules
 */

// Prevent direct access
if (!defined('WPU_SYSTEM_INIT')) {
    define('WPU_SYSTEM_INIT', true);
}

/**
 * Calculate duration between two timestamps
 * 
 * @param string $start Start timestamp
 * @param string $end End timestamp
 * @return string Formatted duration (e.g., "5m 30s")
 */
function calculateDuration($start, $end) {
    $start_time = strtotime($start);
    $end_time = strtotime($end);
    $diff = $end_time - $start_time;
    
    $hours = floor($diff / 3600);
    $minutes = floor(($diff % 3600) / 60);
    $seconds = $diff % 60;
    
    if ($hours > 0) {
        return "{$hours}h {$minutes}m {$seconds}s";
    }
    return "{$minutes}m {$seconds}s";
}

/**
 * Get luminance value of a hex color
 * 
 * @param string $hexColor Hex color code (e.g., "#FF5733")
 * @return float Luminance value (0-255)
 */
function getLuminance($hexColor) {
    $hex = str_replace('#', '', $hexColor);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return ($r * 299 + $g * 587 + $b * 114) / 1000;
}

/**
 * Generate a random color in hex format
 * 
 * @return string Hex color code (e.g., "#3A7BD5")
 */
function generateRandomColor() {
    $hue = rand(0, 359);
    $saturation = rand(70, 100);
    $lightness = rand(45, 65);
    
    $c = (1 - abs(2 * $lightness / 100 - 1)) * $saturation / 100;
    $x = $c * (1 - abs(fmod($hue / 60, 2) - 1));
    $m = $lightness / 100 - $c / 2;
    
    if ($hue < 60) { $r = $c; $g = $x; $b = 0; }
    elseif ($hue < 120) { $r = $x; $g = $c; $b = 0; }
    elseif ($hue < 180) { $r = 0; $g = $c; $b = $x; }
    elseif ($hue < 240) { $r = 0; $g = $x; $b = $c; }
    elseif ($hue < 300) { $r = $x; $g = 0; $b = $c; }
    else { $r = $c; $g = 0; $b = $x; }
    
    $r = dechex(round(($r + $m) * 255));
    $g = dechex(round(($g + $m) * 255));
    $b = dechex(round(($b + $m) * 255));
    
    return '#' . str_pad($r, 2, '0', STR_PAD_LEFT) . 
           str_pad($g, 2, '0', STR_PAD_LEFT) . 
           str_pad($b, 2, '0', STR_PAD_LEFT);
}

/**
 * Generate unique receipt number
 * 
 * @param PDO|mysqli $conn Database connection
 * @param string $prefix Receipt prefix (e.g., "WPU-MC-")
 * @param string $table Table name to check
 * @param string $column Column name for receipt number
 * @return string Generated receipt number
 */
function generateReceiptNumber($conn, $prefix = 'WPU-MC-', $table = 'medical_certificates', $column = 'receipt_no') {
    $year = date('Y');
    
    if ($conn instanceof PDO) {
        $stmt = $conn->prepare("SELECT $column FROM $table WHERE $column LIKE ? ORDER BY id DESC LIMIT 1");
        $likePattern = $prefix . $year . '%';
        $stmt->execute([$likePattern]);
        $lastReceipt = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // MySQLi
        $likePattern = $prefix . $year . '%';
        $stmt = $conn->prepare("SELECT $column FROM $table WHERE $column LIKE ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("s", $likePattern);
        $stmt->execute();
        $result = $stmt->get_result();
        $lastReceipt = $result->fetch_assoc();
        $stmt->close();
    }
    
    if ($lastReceipt && preg_match('/' . preg_quote($prefix) . $year . '-(\d+)/', $lastReceipt[$column], $matches)) {
        $nextNumber = intval($matches[1]) + 1;
    } else {
        $nextNumber = 1;
    }
    
    return $prefix . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
}

/**
 * Sanitize string input
 * 
 * @param string $input The input to sanitize
 * @return string Sanitized input
 */
function sanitize_input($input) {
    if (is_array($input)) {
        return array_map('sanitize_input', $input);
    }
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
    if (empty($date) || $date === '0000-00-00') return '';
    try {
        $d = new DateTime($date);
        return $d->format($format);
    } catch (Exception $e) {
        return '';
    }
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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['form_token']) && hash_equals($_SESSION['form_token'], $token);
}

/**
 * Generate CSRF token
 * 
 * @return string CSRF token
 */
function generate_csrf_token() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['form_token'])) {
        $_SESSION['form_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['form_token'];
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
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
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

/**
 * Log activity to database
 * 
 * @param PDO|mysqli $conn Database connection
 * @param string $username Username performing action
 * @param string $action Action description
 * @param string $details Additional details
 * @return bool Success status
 */
function log_activity($conn, $username, $action, $details = '') {
    try {
        if ($conn instanceof PDO) {
            $stmt = $conn->prepare("INSERT INTO activity_logs (username, `action`, details, created_at) VALUES (?, ?, ?, NOW())");
            return $stmt->execute([$username, $action, $details]);
        } else {
            // MySQLi
            $stmt = $conn->prepare("INSERT INTO activity_logs (username, `action`, details, created_at) VALUES (?, ?, ?, NOW())");
            if ($stmt) {
                $stmt->bind_param("sss", $username, $action, $details);
                $result = $stmt->execute();
                $stmt->close();
                return $result;
            }
        }
    } catch (Exception $e) {
        error_log("Failed to log activity: " . $e->getMessage());
        return false;
    }
    return false;
}

/**
 * Display formatted alert message
 * 
 * @param string $type Alert type (success, error, warning, info)
 * @param string $title Alert title
 * @param string $message Alert message
 * @return string HTML alert markup
 */
function display_alert($type, $title, $message) {
    $icons = [
        'success' => '✓',
        'error' => '✗',
        'warning' => '⚠',
        'info' => 'ℹ'
    ];
    
    $icon = $icons[$type] ?? 'ℹ';
    
    return <<<HTML
    <div class="alert alert-{$type}" id="alert-{$type}">
        <span class="alert-icon">{$icon}</span>
        <div class="alert-content">
            <div class="alert-title">{$title}</div>
            <div class="alert-message">{$message}</div>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            ×
        </button>
    </div>
HTML;
}

?>
