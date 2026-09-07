<?php

/**
 * Append a row to activity_logs (admin audit trail). Fails quietly if the table is missing.
 */
function wpu_insert_activity_log(PDO $pdo, string $username, string $action, string $details = ''): bool
{
    if ($username === '') {
        return false;
    }
    try {
        $st = $pdo->prepare(
            'INSERT INTO activity_logs (username, `action`, details, created_at) VALUES (?, ?, ?, NOW())'
        );

        return $st->execute([$username, $action, $details]);
    } catch (PDOException $e) {
        error_log('wpu_insert_activity_log: '.$e->getMessage());

        return false;
    }
}
