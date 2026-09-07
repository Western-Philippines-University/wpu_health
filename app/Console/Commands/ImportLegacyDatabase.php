<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;
use PDOException;

class ImportLegacyDatabase extends Command
{
    protected $signature = 'db:import-legacy
                            {--path= : Path to SQL file relative to the project root}';

    protected $description = 'Create the configured database if needed and import unified SQL (default: database/sql/wpu_unified.sql). Run `php artisan migrate` afterward for Laravel framework tables.';

    public function handle(): int
    {
        $relative = $this->option('path') ?: 'database/sql/wpu_unified.sql';
        $fullPath = base_path($relative);

        if (! is_file($fullPath)) {
            $this->error("SQL file not found: {$fullPath}");

            return self::FAILURE;
        }

        $connection = config('database.connections.mysql');
        $host = $connection['host'];
        $port = (int) ($connection['port'] ?? 3306);
        $database = $connection['database'];
        $username = $connection['username'];
        $password = (string) ($connection['password'] ?? '');

        try {
            $this->ensureDatabaseExists($host, $port, $database, $username, $password);
        } catch (PDOException $e) {
            $this->error('Could not ensure database exists: '.$e->getMessage());

            return self::FAILURE;
        }

        $sql = file_get_contents($fullPath);
        if ($sql === false) {
            $this->error("Could not read: {$fullPath}");

            return self::FAILURE;
        }

        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $username, $password, [
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            $this->error('Database connection failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info("Importing into `{$database}` from {$relative}...");

        try {
            $pdo->exec($sql);
        } catch (PDOException $e) {
            $this->error('Import failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info('Legacy import finished. Next: php artisan migrate');

        return self::SUCCESS;
    }

    private function ensureDatabaseExists(
        string $host,
        int $port,
        string $database,
        string $username,
        string $password,
    ): void {
        $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');
        $quotedDb = str_replace('`', '``', $database);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$quotedDb}` CHARACTER SET {$charset} COLLATE {$collation}");
    }
}
