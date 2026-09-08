<?php
/**
 * System Initialization File
 * Bootstrap file for WPU Medical System
 * Include this at the top of every page
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define system root
if (!defined('SYSTEM_ROOT')) {
    define('SYSTEM_ROOT', dirname(__DIR__));
}

// Define that system is initialized
if (!defined('WPU_SYSTEM_INIT')) {
    define('WPU_SYSTEM_INIT', true);
}

// Error reporting — production-safe defaults
$wpuDebugRaw = $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? getenv('APP_DEBUG') ?: 'false';
$wpuDebug = filter_var($wpuDebugRaw, FILTER_VALIDATE_BOOLEAN);
error_reporting(E_ALL);
ini_set('display_errors', $wpuDebug ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', SYSTEM_ROOT . '/logs/error.log');

// Include configuration
require_once __DIR__ . '/database.php';

// Include helper functions
require_once dirname(__DIR__) . '/includes/helpers.php';
require_once dirname(__DIR__) . '/includes/wpu_security.php';

// Generate CSRF token if not exists
generate_csrf_token();

// Set timezone
date_default_timezone_set('Asia/Manila');

// Security headers
wpu_send_security_headers();

/**
 * Get current page name without extension
 * 
 * @return string Page name
 */
function getCurrentPage() {
    return basename($_SERVER['PHP_SELF'], '.php');
}

/**
 * Check if user has permission
 * (Placeholder for future role-based access control)
 * 
 * @param string $permission Permission to check
 * @return bool Has permission
 */
function hasPermission($permission) {
    // Currently all logged-in admins have all permissions
    return check_login(false);
}

/**
 * Redirect to page
 * 
 * @param string $url URL to redirect to
 * @param int $code HTTP status code
 */
function redirect($url, $code = 302) {
    header("Location: $url", true, $code);
    exit;
}

/**
 * Get base URL of the application
 * 
 * @return string Base URL
 */
function getBaseURL() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['SCRIPT_NAME']);
    return $protocol . '://' . $host . $path;
}

/**
 * Asset URL helper
 * 
 * @param string $asset Asset path relative to assets folder
 * @return string Full URL to asset
 */
function asset($asset) {
    return getBaseURL() . '/../assets/' . ltrim($asset, '/');
}

/**
 * Flash message helper
 * Set a message to display on next page load
 * 
 * @param string $type Message type (success, error, warning, info)
 * @param string $message Message text
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * 
 * @return array|null Flash message array or null
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Display flash message if exists
 * 
 * @return string HTML for flash message or empty string
 */
function displayFlashMessage() {
    $flash = getFlashMessage();
    if ($flash) {
        return display_alert($flash['type'], ucfirst($flash['type']), $flash['message']);
    }
    return '';
}

// Auto-display any flash messages
$flashMessage = getFlashMessage();
if ($flashMessage) {
    // Store to display in template
    $GLOBALS['flash_message_html'] = display_alert(
        $flashMessage['type'],
        ucfirst($flashMessage['type']),
        $flashMessage['message']
    );
}

?>
