<?php

/**
 * One-time migration: create activity_logs if missing.
 * Run: php unified_portal/database/run_add_activity_logs.php
 */

declare(strict_types=1);

$base = dirname(__DIR__);
require_once $base.'/config/database.php';

$pdo = getDBConnection();

$check = $pdo->prepare(
    'SELECT COUNT(*) FROM information_schema.TABLES
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
);
$check->execute(['activity_logs']);
$exists = (int) ($check->fetchColumn() ?: 0) > 0;

if ($exists) {
    echo "Table activity_logs already exists. Nothing to do.\n";
    exit(0);
}

$sqlPath = __DIR__.DIRECTORY_SEPARATOR.'add_activity_logs.sql';
$sql = file_get_contents($sqlPath);
if ($sql === false || trim($sql) === '') {
    fwrite(STDERR, "Could not read add_activity_logs.sql\n");
    exit(1);
}
$pdo->exec($sql);

echo "Created table activity_logs.\n";
