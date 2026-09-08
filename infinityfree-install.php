<?php

/**
 * One-time InfinityFree installer. Delete this file after a successful install.
 */

declare(strict_types=1);

$lockFile = __DIR__.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'.infinityfree-installed';

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function detectAppUrl(): string
{
    $https = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    return $scheme.'://'.$host;
}

function writeEnvFile(string $path, array $values): void
{
    $lines = [
        'APP_NAME="WPU Health Services"',
        'APP_ENV=production',
        'APP_KEY='.$values['APP_KEY'],
        'APP_DEBUG=false',
        'APP_URL='.$values['APP_URL'],
        'APP_TIMEZONE=Asia/Manila',
        '',
        'APP_LOCALE=en',
        'APP_FALLBACK_LOCALE=en',
        '',
        'LOG_CHANNEL=stack',
        'LOG_STACK=single',
        'LOG_LEVEL=error',
        '',
        'DB_CONNECTION=mysql',
        'DB_HOST='.$values['DB_HOST'],
        'DB_PORT=3306',
        'DB_DATABASE='.$values['DB_DATABASE'],
        'DB_USERNAME='.$values['DB_USERNAME'],
        'DB_PASSWORD="'.$values['DB_PASSWORD'].'"',
        '',
        'SESSION_DRIVER=file',
        'SESSION_LIFETIME=120',
        'SESSION_ENCRYPT=false',
        'SESSION_SECURE_COOKIE=true',
        'SESSION_PATH=/',
        'SESSION_DOMAIN=null',
        '',
        'BROADCAST_CONNECTION=log',
        'FILESYSTEM_DISK=local',
        'QUEUE_CONNECTION=sync',
        'CACHE_STORE=file',
        'MAIL_MAILER=log',
    ];

    if (file_put_contents($path, implode("\n", $lines)."\n") === false) {
        throw new RuntimeException('Could not write .env. Make sure htdocs is writable.');
    }
}

function ensureStorageDirs(string $root): void
{
    $dirs = [
        'storage/app',
        'storage/framework/cache/data',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs',
        'bootstrap/cache',
        'unified_portal/logs',
    ];
    foreach ($dirs as $dir) {
        $path = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $dir);
        if (! is_dir($path) && ! mkdir($path, 0775, true) && ! is_dir($path)) {
            throw new RuntimeException('Could not create '.$dir);
        }
        @chmod($path, 0775);
    }
}

$installed = is_file($lockFile);
$error = null;
$success = null;
$log = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! $installed) {
    try {
        $appUrl = trim((string) ($_POST['app_url'] ?? ''));
        $dbHost = trim((string) ($_POST['db_host'] ?? ''));
        $dbName = trim((string) ($_POST['db_database'] ?? ''));
        $dbUser = trim((string) ($_POST['db_username'] ?? ''));
        $dbPass = (string) ($_POST['db_password'] ?? '');
        $seedSample = isset($_POST['seed_sample']);

        if ($appUrl === '' || $dbHost === '' || $dbName === '' || $dbUser === '') {
            throw new RuntimeException('APP URL, database host, name, and username are required.');
        }
        if (strcasecmp($dbHost, 'localhost') === 0 || $dbHost === '127.0.0.1') {
            throw new RuntimeException('InfinityFree MySQL host is not localhost. Use the sqlXXX hostname from the control panel.');
        }

        ensureStorageDirs(__DIR__);

        $envPath = __DIR__.DIRECTORY_SEPARATOR.'.env';
        $appKey = 'base64:'.base64_encode(random_bytes(32));
        if (is_readable($envPath)) {
            $existing = file_get_contents($envPath);
            if (is_string($existing) && preg_match('/^APP_KEY=(.+)$/m', $existing, $match) && trim($match[1]) !== '') {
                $appKey = trim($match[1]);
            }
        }

        writeEnvFile($envPath, [
            'APP_KEY' => $appKey,
            'APP_URL' => rtrim($appUrl, '/'),
            'DB_HOST' => $dbHost,
            'DB_DATABASE' => $dbName,
            'DB_USERNAME' => $dbUser,
            'DB_PASSWORD' => str_replace(['\\', '"'], ['\\\\', '\\"'], $dbPass),
        ]);

        @set_time_limit(180);
        @ini_set('max_execution_time', '180');

        $dsn = 'mysql:host='.$dbHost.';dbname='.$dbName.';charset=utf8mb4';
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $log[] = 'Database connection succeeded.';

        $schemaFile = __DIR__.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.'full_schema.sql';
        if (is_readable($schemaFile)) {
            $sql = file_get_contents($schemaFile);
            if ($sql === false || $sql === '') {
                throw new RuntimeException('Could not read database/sql/full_schema.sql');
            }
            $pdo->exec($sql);
            $log[] = 'Imported database/sql/full_schema.sql';
        }

        require __DIR__.'/vendor/autoload.php';
        require __DIR__.'/bootstrap/hosting.php';
        $app = require __DIR__.'/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        $kernel->call('migrate', ['--force' => true]);
        $log[] = trim($kernel->output()) ?: 'Migrations finished.';

        $kernel->call('db:seed', ['--class' => 'AdminSeeder', '--force' => true]);
        $log[] = 'Admin user seeded (username: admin / password: password). Change this immediately.';

        if ($seedSample) {
            $kernel->call('db:seed', ['--class' => 'SampleDataSeeder', '--force' => true]);
            $log[] = 'Sample data seeded.';
        }

        if (file_put_contents($lockFile, date('c')) === false) {
            $log[] = 'Warning: could not write install lock file.';
        }

        @unlink(__FILE__);
        $success = 'Install complete. If this installer file is still here, delete infinityfree-install.php from htdocs now.';
        $installed = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$defaults = [
    'app_url' => detectAppUrl(),
    'db_host' => 'sqlXXX.infinityfree.com',
    'db_database' => '',
    'db_username' => '',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WPU Health Services — InfinityFree install</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f4f6f8; color: #1f2933; margin: 0; padding: 2rem; }
        main { max-width: 42rem; margin: 0 auto; background: #fff; padding: 1.5rem 1.75rem; border-radius: 12px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08); }
        label { display: block; font-weight: 600; margin: 0.85rem 0 0.35rem; }
        input[type=text], input[type=password] { width: 100%; box-sizing: border-box; padding: 0.6rem 0.7rem; border: 1px solid #cbd2d9; border-radius: 8px; }
        button { margin-top: 1.2rem; background: #0f766e; color: #fff; border: 0; padding: 0.7rem 1rem; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .error { background: #fee2e2; color: #991b1b; padding: 0.75rem 1rem; border-radius: 8px; }
        .ok { background: #dcfce7; color: #166534; padding: 0.75rem 1rem; border-radius: 8px; }
        .hint { color: #52606d; font-size: 0.92rem; }
        pre { background: #0f172a; color: #e2e8f0; padding: 0.8rem; border-radius: 8px; overflow: auto; }
    </style>
</head>
<body>
<main>
    <h1>InfinityFree installer</h1>
    <?php if ($installed && $success): ?>
        <p class="ok"><?= h($success) ?></p>
        <?php if ($log): ?><pre><?= h(implode("\n", $log)) ?></pre><?php endif; ?>
        <p><a href="/admin/login">Open admin login</a></p>
    <?php elseif ($installed): ?>
        <p class="ok">This site is already installed. Delete <code>infinityfree-install.php</code> if it is still in htdocs.</p>
        <p><a href="/admin/login">Open admin login</a></p>
    <?php else: ?>
        <p class="hint">Set PHP to 8.2+ in the InfinityFree control panel first. Database hostname must be the sqlXXX host, not localhost.</p>
        <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
        <form method="post">
            <label for="app_url">App URL</label>
            <input id="app_url" name="app_url" type="text" required value="<?= h($_POST['app_url'] ?? $defaults['app_url']) ?>">

            <label for="db_host">MySQL hostname</label>
            <input id="db_host" name="db_host" type="text" required value="<?= h($_POST['db_host'] ?? $defaults['db_host']) ?>">

            <label for="db_database">Database name</label>
            <input id="db_database" name="db_database" type="text" required value="<?= h($_POST['db_database'] ?? $defaults['db_database']) ?>">

            <label for="db_username">Database username</label>
            <input id="db_username" name="db_username" type="text" required value="<?= h($_POST['db_username'] ?? $defaults['db_username']) ?>">

            <label for="db_password">Database password</label>
            <input id="db_password" name="db_password" type="password" value="">

            <label><input type="checkbox" name="seed_sample" value="1"> Also load sample clinic data</label>

            <button type="submit">Install WPU Health Services</button>
        </form>
    <?php endif; ?>
</main>
</body>
</html>
