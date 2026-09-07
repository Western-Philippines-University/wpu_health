<?php
/**
 * Legacy mysqli entry point — uses the same .env / DB_* settings as database.php.
 */

if (! defined('DB_CONFIG_INCLUDED')) {
    define('DB_CONFIG_INCLUDED', true);
}

require_once __DIR__.'/database.php';
