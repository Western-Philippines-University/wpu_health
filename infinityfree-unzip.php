<?php

/**
 * Extracts wpu-infinityfree.zip in htdocs, then deletes the zip and this script.
 */

declare(strict_types=1);

$root = __DIR__;
$zipPath = $root.DIRECTORY_SEPARATOR.'wpu-infinityfree.zip';
$report = [];
$ok = false;

try {
    if (! class_exists('ZipArchive')) {
        throw new RuntimeException('PHP zip extension is not enabled on this host.');
    }
    if (! is_file($zipPath)) {
        throw new RuntimeException('wpu-infinityfree.zip was not found in htdocs.');
    }

    $zip = new ZipArchive();
    $opened = $zip->open($zipPath);
    if ($opened !== true) {
        throw new RuntimeException('Could not open zip (code '.$opened.').');
    }

    if (! $zip->extractTo($root)) {
        $zip->close();
        throw new RuntimeException('Zip extract failed. Check free disk space and try again.');
    }
    $count = $zip->numFiles;
    $zip->close();
    $report[] = "Extracted {$count} files.";

    @unlink($zipPath);
    $report[] = 'Removed wpu-infinityfree.zip.';
    $ok = true;
} catch (Throwable $e) {
    $report[] = $e->getMessage();
}

if ($ok) {
    @unlink(__FILE__);
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Extract deploy zip</title>
    <style>
        body { font-family: system-ui, sans-serif; padding: 2rem; background: #f4f6f8; }
        main { max-width: 40rem; margin: 0 auto; background: #fff; padding: 1.5rem; border-radius: 12px; }
        .ok { color: #166534; }
        .err { color: #991b1b; }
    </style>
</head>
<body>
<main>
    <h1 class="<?= $ok ? 'ok' : 'err' ?>"><?= $ok ? 'Extract complete' : 'Extract failed' ?></h1>
    <pre><?= htmlspecialchars(implode("\n", $report), ENT_QUOTES, 'UTF-8') ?></pre>
    <?php if ($ok): ?>
        <p><a href="/infinityfree-install.php">Continue to installer</a></p>
    <?php endif; ?>
</main>
</body>
</html>
