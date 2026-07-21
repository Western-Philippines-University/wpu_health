<?php
/**
 * Unified Database Configuration
 * Central database connection manager for all WPU Medical System modules
 */

// Prevent direct access
if (!defined('WPU_SYSTEM_INIT')) {
    define('WPU_SYSTEM_INIT', true);
}

/**
 * Load Laravel-style .env from project root so legacy scripts use the same DB credentials as the app.
 */
if (!function_exists('wpu_legacy_load_root_env')) {
    function wpu_legacy_load_root_env(): void
    {
        static $loaded = false;
        if ($loaded) {
            return;
        }
        $loaded = true;
        $envFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env';
        if (!is_readable($envFile)) {
            return;
        }
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if ($name === '' || getenv($name) !== false) {
                continue;
            }
            if (strlen($value) >= 2) {
                $q = $value[0];
                if (($q === '"' || $q === "'") && $value[strlen($value) - 1] === $q) {
                    $value = substr($value, 1, -1);
                    if ($q === '"') {
                        $value = stripcslashes($value);
                    }
                }
            }
            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
        }
    }
}
wpu_legacy_load_root_env();

// Environment Configuration - Only define if not already defined (prefer root .env / getenv)
if (!defined('DB_HOST')) {
    $h = getenv('DB_HOST');
    define('DB_HOST', ($h !== false && $h !== '') ? $h : 'localhost');
}
if (!defined('DB_USER')) {
    $u = getenv('DB_USERNAME');
    define('DB_USER', ($u !== false && $u !== '') ? $u : 'root');
}
if (!defined('DB_PASS')) {
    $p = getenv('DB_PASSWORD');
    define('DB_PASS', $p !== false ? $p : '');
}

// Unified Database Name - All modules now use single database
if (!defined('DB_NAME')) {
    $d = getenv('DB_DATABASE');
    define('DB_NAME', ($d !== false && $d !== '') ? $d : 'health_records');
}

// Charset
if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', 'utf8mb4');
}

// Timezone
date_default_timezone_set('Asia/Manila');

/**
 * Get database connection using PDO
 *
 * @return PDO Database connection
 */
function getDBConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    try {
        $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $persistent = getenv('DB_PERSISTENT');
        if ($persistent === 'true' || $persistent === '1') {
            $options[PDO::ATTR_PERSISTENT] = true;
        }

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        return $pdo;
    } catch (PDOException $e) {
        error_log('Database connection failed: '.$e->getMessage());
        if (ini_get('display_errors')) {
            exit('Database connection failed: '.$e->getMessage());
        }
        exit('Database connection failed. Please contact the administrator.');
    }
}

/**
 * Get MySQLi connection (for legacy code compatibility)
 *
 * @return mysqli|null Connected mysqli, or null on failure (PHP 8+ may throw otherwise)
 */
function getMySQLiConnection(): ?mysqli
{
    try {
        mysqli_report(MYSQLI_REPORT_OFF);
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        if ($conn->connect_error) {
            error_log('MySQLi connection failed: '.$conn->connect_error);

            return null;
        }

        $conn->set_charset(DB_CHARSET);

        return $conn;
    } catch (Throwable $e) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        error_log('MySQLi connection failed: '.$e->getMessage());

        return null;
    }
}

// Main MySQLi connection for legacy code (PDO is opened on demand via getDBConnection()).
if (! isset($conn) || ! ($conn instanceof mysqli)) {
    $conn = getMySQLiConnection();
}

// Legacy constants for backward compatibility (deprecated) - Only define if not already defined
if (!defined('DB_MAIN')) {
    define('DB_MAIN', DB_NAME);
}
if (!defined('DB_DENTAL')) {
    define('DB_DENTAL', DB_NAME);
}
if (!defined('DB_HEALTH')) {
    define('DB_HEALTH', DB_NAME);
}
