<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Performance indexes for high-concurrency HIS workloads.
     * Safe to run multiple times — skips indexes that already exist.
     */
    public function up(): void
    {
        $this->addIndexIfMissing('medical_certificates', 'idx_mc_name', ['name']);
        $this->addIndexIfMissing('medical_certificates', 'idx_mc_created_at', ['created_at']);
        $this->addIndexIfMissing('medical_certificates', 'idx_mc_receipt_no', ['receipt_no']);

        $this->addIndexIfMissing('referrals', 'idx_ref_patient_name', ['patient_name']);
        $this->addIndexIfMissing('referrals', 'idx_ref_created_at', ['created_at']);

        $this->addIndexIfMissing('patient_records', 'idx_pr_module_created', ['module_type', 'created_at']);
        $this->addIndexIfMissing('patient_records', 'idx_pr_full_name', ['full_name']);
        $this->addIndexIfMissing('patient_records', 'idx_pr_module_name', ['module_type', 'full_name']);
        $this->addIndexIfMissing('patient_records', 'idx_pr_visit_date', ['visit_date']);

        $this->addIndexIfMissing('activity_logs', 'idx_activity_created_at', ['created_at']);
        $this->addIndexIfMissing('user_logs', 'idx_user_logs_login_time', ['login_time']);

        $this->addIndexIfMissing('system_settings', 'idx_system_settings_key', ['setting_key']);

        if (Schema::hasTable('patient_files') && Schema::hasColumn('patient_files', 'created_at')) {
            $this->addIndexIfMissing('patient_files', 'idx_pf_created_at', ['created_at']);
        }
    }

    public function down(): void
    {
        $indexes = [
            'medical_certificates' => ['idx_mc_name', 'idx_mc_created_at', 'idx_mc_receipt_no'],
            'referrals' => ['idx_ref_patient_name', 'idx_ref_created_at'],
            'patient_records' => ['idx_pr_module_created', 'idx_pr_full_name', 'idx_pr_module_name', 'idx_pr_visit_date'],
            'activity_logs' => ['idx_activity_created_at'],
            'user_logs' => ['idx_user_logs_login_time'],
            'system_settings' => ['idx_system_settings_key'],
            'patient_files' => ['idx_pf_created_at'],
        ];

        foreach ($indexes as $table => $names) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            foreach ($names as $indexName) {
                if ($this->indexExists($table, $indexName)) {
                    Schema::table($table, function (Blueprint $blueprint) use ($indexName) {
                        $blueprint->dropIndex($indexName);
                    });
                }
            }
        }
    }

    private function addIndexIfMissing(string $table, string $indexName, array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return;
            }
        }

        if ($this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($indexName, $columns) {
            $blueprint->index($columns, $indexName);
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection()->getDriverName();
        if ($connection !== 'mysql') {
            return false;
        }

        $database = Schema::getConnection()->getDatabaseName();
        $result = DB::select(
            'SELECT COUNT(*) AS c FROM information_schema.statistics
             WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$database, $table, $indexName]
        );

        return isset($result[0]) && (int) $result[0]->c > 0;
    }
};
