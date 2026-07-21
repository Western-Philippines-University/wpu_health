<?php
/**
 * Cached reference data for forms and modals.
 */

require_once __DIR__.'/wpu_cache.php';

if (! function_exists('wpu_get_patient_types')) {
    function wpu_get_patient_types(PDO $pdo): array
    {
        return wpu_cache_remember('ref:patient_types', 900, function () use ($pdo) {
            $stmt = $pdo->query('SELECT id, type_name, color_code FROM patient_types ORDER BY id');

            return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        });
    }
}

if (! function_exists('wpu_get_departments')) {
    function wpu_get_departments(PDO $pdo): array
    {
        return wpu_cache_remember('ref:departments', 900, function () use ($pdo) {
            $stmt = $pdo->query('SELECT id, name FROM departments ORDER BY name');

            return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        });
    }
}

if (! function_exists('wpu_get_case_types')) {
    function wpu_get_case_types(PDO $pdo): array
    {
        return wpu_cache_remember('ref:case_types', 900, function () use ($pdo) {
            $stmt = $pdo->query('SELECT id, case_name FROM case_types ORDER BY case_name');

            return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        });
    }
}

if (! function_exists('wpu_get_certificate_codes')) {
    /**
     * @return array{certificate_code: string, referral_code: string}
     */
    function wpu_get_certificate_codes(PDO $pdo): array
    {
        return wpu_cache_remember('ref:certificate_codes', 600, function () use ($pdo) {
            $stmt = $pdo->query('SELECT certificate_code, referral_code FROM certificate_codes LIMIT 1');
            $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;

            return [
                'certificate_code' => is_array($row) ? (string) ($row['certificate_code'] ?? '') : '',
                'referral_code' => is_array($row) ? (string) ($row['referral_code'] ?? '') : '',
            ];
        });
    }
}

if (! function_exists('wpu_get_auto_lock_settings')) {
    /**
     * @return array{enabled: int, timeout: int}
     */
    function wpu_get_auto_lock_settings(PDO $pdo): array
    {
        return wpu_cache_remember('settings:auto_lock', 120, function () use ($pdo) {
            $enabledStmt = $pdo->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'auto_lock_enabled' LIMIT 1");
            $enabledStmt->execute();
            $enabled = (int) $enabledStmt->fetchColumn();

            $timeoutStmt = $pdo->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'auto_lock_timeout' LIMIT 1");
            $timeoutStmt->execute();
            $timeout = (int) $timeoutStmt->fetchColumn();
            if ($timeout <= 0) {
                $timeout = 300000;
            }

            return ['enabled' => $enabled, 'timeout' => $timeout];
        });
    }
}

if (! function_exists('wpu_get_staff_signature')) {
    /**
     * @return array{name: string, position: string, license_no: string, signature?: string|null}
     */
    function wpu_get_staff_signature(PDO $pdo): array
    {
        $defaults = [
            'name' => 'MICAELLA T. BAGALANON-LABUTOY, MD, OHP',
            'position' => 'University Physician',
            'license_no' => 'License. No. 0148115',
        ];

        return wpu_cache_remember('ref:staff_signature', 600, function () use ($pdo, $defaults) {
            try {
                $stmt = $pdo->query('SELECT * FROM staff_signatures LIMIT 1');
                $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;

                return is_array($row) ? array_merge($defaults, $row) : $defaults;
            } catch (PDOException) {
                return $defaults;
            }
        });
    }
}

if (! function_exists('wpu_render_select_options')) {
    function wpu_render_select_options(array $rows, string $valueKey, string $labelKey, string $selected = ''): void
    {
        foreach ($rows as $row) {
            $value = (string) ($row[$valueKey] ?? '');
            $label = (string) ($row[$labelKey] ?? '');
            $isSelected = $selected !== '' && $value === $selected ? ' selected' : '';
            echo '<option value="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'"'.$isSelected.'>'
                .htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</option>';
        }
    }
}
