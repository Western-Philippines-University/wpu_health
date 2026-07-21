<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class WpuMaintenanceCommand extends Command
{
    protected $signature = 'wpu:maintenance {--optimize-db : Run OPTIMIZE TABLE on core HIS tables}';

    protected $description = 'Daily HIS maintenance: prune sessions, clear stale cache, optional DB optimize';

    public function handle(): int
    {
        $this->info('Running WPU HIS maintenance...');

        Artisan::call('session:gc');
        $this->line('  Session garbage collection complete.');

        $this->pruneExpiredSessions();

        if ($this->option('optimize-db')) {
            $this->optimizeCoreTables();
        }

        $this->cleanupLegacyFileCache();

        $this->info('Maintenance complete.');

        return self::SUCCESS;
    }

    private function pruneExpiredSessions(): void
    {
        if (! $this->tableExists('sessions')) {
            return;
        }

        $deleted = DB::table('sessions')
            ->where('last_activity', '<', now()->subMinutes((int) config('session.lifetime', 120))->timestamp)
            ->delete();

        $this->line("  Pruned {$deleted} expired database sessions.");
    }

    private function optimizeCoreTables(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            $this->warn('  DB optimize skipped — not MySQL.');

            return;
        }

        $tables = [
            'medical_certificates',
            'referrals',
            'patient_records',
            'patient_files',
            'activity_logs',
            'user_logs',
            'sessions',
        ];

        foreach ($tables as $table) {
            if (! $this->tableExists($table)) {
                continue;
            }
            DB::statement("OPTIMIZE TABLE `{$table}`");
            $this->line("  Optimized table: {$table}");
        }
    }

    private function cleanupLegacyFileCache(): void
    {
        $dir = storage_path('framework/wpu_cache');
        if (! is_dir($dir)) {
            return;
        }

        $now = time();
        $removed = 0;
        foreach (glob($dir.DIRECTORY_SEPARATOR.'*.cache') ?: [] as $file) {
            $payload = @file_get_contents($file);
            if ($payload === false) {
                continue;
            }
            $data = @unserialize($payload);
            if (is_array($data) && isset($data['expires']) && $data['expires'] !== 0 && $data['expires'] < $now) {
                if (@unlink($file)) {
                    $removed++;
                }
            }
        }

        $this->line("  Removed {$removed} expired legacy cache files.");
    }

    private function tableExists(string $table): bool
    {
        try {
            return DB::getSchemaBuilder()->hasTable($table);
        } catch (\Throwable) {
            return false;
        }
    }
}
