<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 600;

    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(
        public string $reportType,
        public array $filters,
        public int $requestedByAdminId,
        public string $outputFormat = 'csv',
    ) {}

    public function handle(): void
    {
        $filename = sprintf(
            'reports/%s_%s.%s',
            $this->reportType,
            now()->format('Ymd_His'),
            $this->outputFormat
        );

        Storage::disk('local')->put($filename, $this->buildPlaceholderContent());

        Log::info('Report generated', [
            'type' => $this->reportType,
            'path' => $filename,
            'admin_id' => $this->requestedByAdminId,
        ]);
    }

    private function buildPlaceholderContent(): string
    {
        return "report_type,generated_at\n{$this->reportType},".now()->toIso8601String()."\n";
    }
}
