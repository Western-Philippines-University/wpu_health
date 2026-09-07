<?php

namespace App\Services;

use App\Models\MedicalCertificate;
use App\Models\PatientRecord;
use App\Models\Referral;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardStatsService
{
    private const CACHE_KEY = 'his:dashboard:stats';

    private const CACHE_TTL_SECONDS = 60;

    /**
     * @return array{medical_certificates: int, referrals: int, patient_records: int, dental_records: int, health_records: int}
     */
    public function getStats(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            if ($this->canUseSingleAggregateQuery()) {
                $row = DB::selectOne('
                    SELECT
                        (SELECT COUNT(*) FROM medical_certificates) AS medical_certificates,
                        (SELECT COUNT(*) FROM referrals) AS referrals,
                        (SELECT COUNT(*) FROM patient_records) AS patient_records,
                        (SELECT COUNT(*) FROM patient_records WHERE module_type = ?) AS dental_records,
                        (SELECT COUNT(*) FROM patient_records WHERE module_type = ?) AS health_records
                ', ['dental', 'health']);

                return [
                    'medical_certificates' => (int) ($row->medical_certificates ?? 0),
                    'referrals' => (int) ($row->referrals ?? 0),
                    'patient_records' => (int) ($row->patient_records ?? 0),
                    'dental_records' => (int) ($row->dental_records ?? 0),
                    'health_records' => (int) ($row->health_records ?? 0),
                ];
            }

            return [
                'medical_certificates' => MedicalCertificate::query()->count(),
                'referrals' => Referral::query()->count(),
                'patient_records' => PatientRecord::query()->count(),
                'dental_records' => PatientRecord::query()->where('module_type', 'dental')->count(),
                'health_records' => PatientRecord::query()->where('module_type', 'health')->count(),
            ];
        });
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function canUseSingleAggregateQuery(): bool
    {
        return DB::getDriverName() === 'mysql';
    }
}
