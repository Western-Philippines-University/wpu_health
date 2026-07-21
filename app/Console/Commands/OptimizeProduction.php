<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeProduction extends Command
{
    protected $signature = 'wpu:optimize-production {--force : Run even when APP_ENV is not production}';

    protected $description = 'Warm Laravel caches for production deployment (routes, config, views, events)';

    public function handle(): int
    {
        if (! $this->option('force') && config('app.env') !== 'production') {
            $this->warn('Skipping production optimization — set APP_ENV=production or pass --force.');

            return self::SUCCESS;
        }

        $this->info('Optimizing for production...');

        $commands = [
            'config:cache',
            'route:cache',
            'view:cache',
            'event:cache',
        ];

        foreach ($commands as $command) {
            $this->call($command);
        }

        $this->info('Production caches warmed successfully.');

        return self::SUCCESS;
    }
}
