<?php

/**
 * One-time migration: add visit_quick_snapshot to patient_records.
 * Run: php run_add_visit_quick_snapshot.php
 * From this directory, or: php unified_portal/database/run_add_visit_quick_snapshot.php
 */

declare(strict_types=1);

$base = dirname(__DIR__);
require_once $base . '/config/database.php';

$pdo = getDBConnection();

$check = $pdo->prepare(
    'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
);
$check->execute(['patient_records', 'visit_quick_snapshot']);
$exists = (int) ($check->fetchColumn() ?: 0) > 0;

if ($exists) {
    echo "Column visit_quick_snapshot already exists. Nothing to do.\n";
    exit(0);
}

$pdo->exec(
    "ALTER TABLE `patient_records`
     ADD COLUMN `visit_quick_snapshot` mediumtext DEFAULT NULL
     COMMENT 'JSON: visit fields before last edit (Quick View)'
     AFTER `doctor`"
);

echo "Added column visit_quick_snapshot to patient_records.\n";
