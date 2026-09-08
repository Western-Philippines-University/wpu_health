<?php

declare(strict_types=1);

$staging = $argv[1] ?? '';
$zipPath = $argv[2] ?? '';

if ($staging === '' || $zipPath === '' || ! is_dir($staging)) {
    fwrite(STDERR, "Usage: zip-infinityfree.php <staging-dir> <zip-path>\n");
    exit(1);
}

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    fwrite(STDERR, "Could not create zip: {$zipPath}\n");
    exit(1);
}

$root = realpath($staging);
if ($root === false) {
    fwrite(STDERR, "Invalid staging directory\n");
    exit(1);
}

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

$count = 0;
foreach ($files as $file) {
    if (! $file->isFile()) {
        continue;
    }
    $full = $file->getRealPath();
    if ($full === false) {
        continue;
    }
    $relative = substr($full, strlen($root) + 1);
    $zip->addFile($full, str_replace('\\', '/', $relative));
    $count++;
}

$zip->close();
echo "Wrote {$count} files to {$zipPath}\n";
