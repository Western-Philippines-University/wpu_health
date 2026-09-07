<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class WriteActivityLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public string $username,
        public string $activity,
        public ?string $details = null,
    ) {}

    public function handle(): void
    {
        if (! $this->tableExists('activity_logs')) {
            return;
        }

        DB::table('activity_logs')->insert([
            'username' => $this->username,
            'activity' => $this->activity,
            'details' => $this->details,
            'created_at' => now(),
        ]);
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
