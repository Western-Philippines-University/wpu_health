<?php

/**
 * One-time migration: create patient_record_edit_history if missing.
 * Run: php run_add_patient_record_edit_history.php
 * From this directory, or: php unified_portal/database/run_add_patient_record_edit_history.php
 */

declare(strict_types=1);

$base = dirname(__DIR__);
require_once $base . '/config/database.php';

$pdo = getDBConnection();

$check = $pdo->prepare(
    'SELECT COUNT(*) AS c FROM information_schema.TABLES
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
);
$check->execute(['patient_record_edit_history']);
$exists = (int) ($check->fetchColumn() ?: 0) > 0;

if ($exists) {
    echo "Table patient_record_edit_history already exists. Nothing to do.\n";
    exit(0);
}

$sqlPath = __DIR__.DIRECTORY_SEPARATOR.'add_patient_record_edit_history.sql';
$sql = is_readable($sqlPath) ? file_get_contents($sqlPath) : false;
if ($sql === false || $sql === '') {
    fwrite(STDERR, "Could not read add_patient_record_edit_history.sql\n");
    exit(1);
}

$pdo->exec($sql);
echo "Created table patient_record_edit_history.\n";
