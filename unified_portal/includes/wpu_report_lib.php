<?php
/**
 * Shared report data queries with short-lived cache for print endpoints.
 */

require_once __DIR__.'/wpu_cache.php';

if (! function_exists('wpu_report_module_valid')) {
    function wpu_report_module_valid(string $module): string
    {
        return in_array($module, ['dental', 'health'], true) ? $module : 'dental';
    }
}

if (! function_exists('wpu_report_daily_records')) {
    function wpu_report_daily_records(PDO $pdo, string $module, string $visitDate): array
    {
        $module = wpu_report_module_valid($module);
        $cacheKey = 'report:daily:'.$module.':'.$visitDate;

        return wpu_cache_remember($cacheKey, 120, function () use ($pdo, $module, $visitDate) {
            $sql = 'SELECT pr.*, d.name AS department_name
                    FROM patient_records pr
                    LEFT JOIN departments d ON pr.department_id = d.id
                    WHERE pr.visit_date = :visit_date AND pr.module_type = :module_type
                    ORDER BY pr.full_name ASC';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['visit_date' => $visitDate, 'module_type' => $module]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        });
    }
}

if (! function_exists('wpu_report_compute_stats')) {
    /**
     * @param  array<int, array<string, mixed>>  $records
     * @return array{
     *   gender_stats: array<string, int>,
     *   department_stats: array<string, int>,
     *   age_stats: array<string, int>
     * }
     */
    function wpu_report_compute_stats(array $records): array
    {
        $gender_stats = ['Male' => 0, 'Female' => 0];
        $department_stats = [];
        $age_stats = [
            'children' => 0,
            'youth' => 0,
            'adults' => 0,
            'seniors' => 0,
        ];

        foreach ($records as $record) {
            if (isset($gender_stats[$record['gender'] ?? ''])) {
                $gender_stats[$record['gender']]++;
            }

            $dept = $record['department_name'] ?? 'N/A';
            $department_stats[$dept] = ($department_stats[$dept] ?? 0) + 1;

            $age = (int) ($record['age'] ?? 0);
            if ($age < 13) {
                $age_stats['children']++;
            } elseif ($age <= 24) {
                $age_stats['youth']++;
            } elseif ($age <= 59) {
                $age_stats['adults']++;
            } else {
                $age_stats['seniors']++;
            }
        }

        return [
            'gender_stats' => $gender_stats,
            'department_stats' => $department_stats,
            'age_stats' => $age_stats,
        ];
    }
}

if (! function_exists('wpu_report_monthly_records')) {
    function wpu_report_monthly_records(PDO $pdo, string $module, string $startDate, string $endDate): array
    {
        $module = wpu_report_module_valid($module);
        $cacheKey = 'report:monthly:'.$module.':'.$startDate.':'.$endDate;

        return wpu_cache_remember($cacheKey, 300, function () use ($pdo, $module, $startDate, $endDate) {
            $sql = 'SELECT pr.*, pt.type_name, d.name AS department, ct.case_name
                    FROM patient_records pr
                    LEFT JOIN patient_types pt ON pr.patient_type_id = pt.id
                    LEFT JOIN departments d ON pr.department_id = d.id
                    LEFT JOIN case_types ct ON pr.case_type_id = ct.id
                    WHERE pr.visit_date BETWEEN :start_date AND :end_date AND pr.module_type = :module_type
                    ORDER BY pr.visit_date ASC, pr.full_name ASC';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'module_type' => $module,
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        });
    }
}

if (! function_exists('wpu_report_by_department')) {
    function wpu_report_by_department(PDO $pdo, string $module, int $departmentId): array
    {
        $module = wpu_report_module_valid($module);
        $cacheKey = 'report:dept:'.$module.':'.$departmentId;

        return wpu_cache_remember($cacheKey, 180, function () use ($pdo, $module, $departmentId) {
            $sql = 'SELECT pr.*, d.name AS department_name
                    FROM patient_records pr
                    LEFT JOIN departments d ON pr.department_id = d.id
                    WHERE pr.module_type = :module_type AND pr.department_id = :department_id
                    ORDER BY pr.visit_date DESC, pr.full_name ASC';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['module_type' => $module, 'department_id' => $departmentId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        });
    }
}

if (! function_exists('wpu_report_invalidate_visit')) {
    function wpu_report_invalidate_visit(string $module, string $visitDate): void
    {
        $module = wpu_report_module_valid($module);
        $visitDate = date('Y-m-d', strtotime($visitDate));
        wpu_cache_forget('report:daily:'.$module.':'.$visitDate);

        $start = date('Y-m-01', strtotime($visitDate));
        $end = date('Y-m-t', strtotime($visitDate));
        wpu_cache_forget('report:monthly:'.$module.':'.$start.':'.$end);
    }
}

if (! function_exists('wpu_report_flush_cache')) {
    function wpu_report_flush_cache(): void
    {
        // Legacy file cache — pattern-based flush for report keys would need glob;
        // full reference flush covers most admin-side invalidations.
        wpu_cache_flush_reference();
    }
}
