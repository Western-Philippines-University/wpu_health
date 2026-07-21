<?php
/**
 * Page-aware data loader — loads only the queries each admin page needs.
 * Preserves all existing variable names used by admin.php templates.
 */

require_once __DIR__.'/wpu_reference_data.php';

$enabled = 0;
$timeout = 300000;
$certificate_code = '';
$referral_code = '';
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'certificates';
if (! in_array($active_tab, ['certificates', 'referrals'], true)) {
    $active_tab = 'certificates';
}
$records_tab = isset($_GET['records_tab']) ? $_GET['records_tab'] : 'dental';
if (! in_array($records_tab, ['dental', 'health'], true)) {
    $records_tab = 'dental';
}
$cert_page = 1;
$ref_page = 1;
$cert_search = '';
$ref_search = '';
$search = '';
$cert_total = 0;
$ref_total = 0;
$cert_total_pages = 0;
$ref_total_pages = 0;
$dental_page = 1;
$dental_search = '';
$search_records = '';
$dental_total = 0;
$dental_total_pages = 0;
$health_page = 1;
$health_search = '';
$health_total = 0;
$health_total_pages = 0;
$certificates_result = null;
$referrals_result = null;
$dental_result = null;
$health_result = null;
$patient_types_result = null;
$departments_result = null;
$case_types_result = null;
$wpu_patient_types = [];
$wpu_departments = [];
$wpu_case_types = [];
$wpu_current_staff = null;

if (! $logged_in) {
    return;
}

require_once __DIR__.'/../config/connect.php';

if (! ($conn instanceof mysqli)) {
    return;
}

$autoLock = wpu_get_auto_lock_settings($pdo);
$enabled = $autoLock['enabled'];
$timeout = $autoLock['timeout'];

$codes = wpu_get_certificate_codes($pdo);
$certificate_code = $codes['certificate_code'];
$referral_code = $codes['referral_code'];

$wpu_patient_types = wpu_get_patient_types($pdo);
$wpu_departments = wpu_get_departments($pdo);
$wpu_case_types = wpu_get_case_types($pdo);

if ($page === 'settings') {
    $wpu_current_staff = wpu_get_staff_signature($pdo);
}

$certPages = ['dashboard', 'certificates', 'referrals', 'certificates_referrals'];
$recordPages = ['dashboard', 'dental_records', 'health_records', 'health_dental_records', 'view_record'];
$needsCertData = in_array($page, $certPages, true);
$needsRecordData = in_array($page, $recordPages, true);

if ($needsCertData) {
    $cert_page = max(1, isset($_GET['cert_page']) ? (int) $_GET['cert_page'] : 1);
    $ref_page = max(1, isset($_GET['ref_page']) ? (int) $_GET['ref_page'] : 1);
    $cert_search = isset($_GET['cert_search']) ? $conn->real_escape_string($_GET['cert_search']) : '';
    $ref_search = isset($_GET['ref_search']) ? $conn->real_escape_string($_GET['ref_search']) : '';
    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    if ($active_tab === 'certificates' && $cert_search === '' && $search !== '') {
        $cert_search = $search;
    }
    if ($active_tab === 'referrals' && $ref_search === '' && $search !== '') {
        $ref_search = $search;
    }

    if ($page === 'dashboard') {
        $certCountResult = $conn->query('SELECT COUNT(*) AS total FROM medical_certificates');
        $cert_total = $certCountResult ? (int) $certCountResult->fetch_assoc()['total'] : 0;

        $refCountResult = $conn->query('SELECT COUNT(*) AS total FROM referrals');
        $ref_total = $refCountResult ? (int) $refCountResult->fetch_assoc()['total'] : 0;

        $certificates_result = $conn->query(
            'SELECT id, name, mc_no, receipt_no, created_at, examination_date
             FROM medical_certificates
             ORDER BY created_at DESC
             LIMIT 5'
        );
        $referrals_result = $conn->query(
            'SELECT id, patient_name, hospital_clinic, created_at
             FROM referrals
             ORDER BY created_at DESC
             LIMIT 5'
        );
    } else {
        $cert_count_query = "SELECT COUNT(*) AS total FROM medical_certificates WHERE name LIKE '%{$cert_search}%'";
        $cert_count_result = $conn->query($cert_count_query);
        $cert_total = $cert_count_result ? (int) $cert_count_result->fetch_assoc()['total'] : 0;
        $cert_total_pages = $cert_total > 0 ? (int) ceil($cert_total / $cert_ref_items_per_page) : 0;
        if ($cert_total_pages > 0) {
            $cert_page = min($cert_page, $cert_total_pages);
        }
        $cert_offset = ($cert_page - 1) * $cert_ref_items_per_page;

        $certificates_query = "SELECT * FROM medical_certificates
                               WHERE name LIKE '%{$cert_search}%'
                               ORDER BY created_at DESC
                               LIMIT {$cert_ref_items_per_page} OFFSET {$cert_offset}";
        $certificates_result = $conn->query($certificates_query);

        $ref_count_query = "SELECT COUNT(*) AS total FROM referrals WHERE patient_name LIKE '%{$ref_search}%'";
        $ref_count_result = $conn->query($ref_count_query);
        $ref_total = $ref_count_result ? (int) $ref_count_result->fetch_assoc()['total'] : 0;
        $ref_total_pages = $ref_total > 0 ? (int) ceil($ref_total / $cert_ref_items_per_page) : 0;
        if ($ref_total_pages > 0) {
            $ref_page = min($ref_page, $ref_total_pages);
        }
        $ref_offset = ($ref_page - 1) * $cert_ref_items_per_page;

        $referrals_query = "SELECT * FROM referrals
                            WHERE patient_name LIKE '%{$ref_search}%'
                            ORDER BY created_at DESC
                            LIMIT {$cert_ref_items_per_page} OFFSET {$ref_offset}";
        $referrals_result = $conn->query($referrals_query);
    }
}

if ($needsRecordData) {
    $dental_page = max(1, isset($_GET['dental_page']) ? (int) $_GET['dental_page'] : 1);
    $dental_search = isset($_GET['dental_search']) ? $conn->real_escape_string($_GET['dental_search']) : '';
    $search_records = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    if ($records_tab === 'dental' && $dental_search === '' && $search_records !== '') {
        $dental_search = $search_records;
    }

    $health_page = max(1, isset($_GET['health_page']) ? (int) $_GET['health_page'] : 1);
    $health_search = isset($_GET['health_search']) ? $conn->real_escape_string($_GET['health_search']) : '';
    if ($records_tab === 'health' && $health_search === '' && $search_records !== '') {
        $health_search = $search_records;
    }

    $recordSelect = 'SELECT pr.*, pt.type_name, pt.color_code, d.name AS department_name, ct.case_name
                     FROM patient_records pr
                     LEFT JOIN patient_types pt ON pr.patient_type_id = pt.id
                     LEFT JOIN departments d ON pr.department_id = d.id
                     LEFT JOIN case_types ct ON pr.case_type_id = ct.id';

    if ($page === 'dashboard') {
        $dentalCountResult = $conn->query("SELECT COUNT(*) AS total FROM patient_records WHERE module_type = 'dental'");
        $dental_total = $dentalCountResult ? (int) $dentalCountResult->fetch_assoc()['total'] : 0;

        $healthCountResult = $conn->query("SELECT COUNT(*) AS total FROM patient_records WHERE module_type = 'health'");
        $health_total = $healthCountResult ? (int) $healthCountResult->fetch_assoc()['total'] : 0;

        $dental_result = $conn->query(
            $recordSelect." WHERE pr.module_type = 'dental' ORDER BY pr.created_at DESC LIMIT 3"
        );
        $health_result = $conn->query(
            $recordSelect." WHERE pr.module_type = 'health' ORDER BY pr.created_at DESC LIMIT 2"
        );
    } else {
        $dental_count_query = "SELECT COUNT(*) AS total FROM patient_records
                               WHERE module_type = 'dental' AND full_name LIKE '%{$dental_search}%'";
        $dental_count_result = $conn->query($dental_count_query);
        $dental_total = $dental_count_result ? (int) $dental_count_result->fetch_assoc()['total'] : 0;
        $dental_total_pages = $dental_total > 0 ? (int) ceil($dental_total / $health_dental_items_per_page) : 0;
        if ($dental_total_pages > 0) {
            $dental_page = min($dental_page, $dental_total_pages);
        }
        $dental_offset = ($dental_page - 1) * $health_dental_items_per_page;

        $dental_query = $recordSelect."
                        WHERE pr.module_type = 'dental' AND pr.full_name LIKE '%{$dental_search}%'
                        ORDER BY pr.created_at DESC
                        LIMIT {$health_dental_items_per_page} OFFSET {$dental_offset}";
        $dental_result = $conn->query($dental_query);

        $health_count_query = "SELECT COUNT(*) AS total FROM patient_records
                               WHERE module_type = 'health' AND full_name LIKE '%{$health_search}%'";
        $health_count_result = $conn->query($health_count_query);
        $health_total = $health_count_result ? (int) $health_count_result->fetch_assoc()['total'] : 0;
        $health_total_pages = $health_total > 0 ? (int) ceil($health_total / $health_dental_items_per_page) : 0;
        if ($health_total_pages > 0) {
            $health_page = min($health_page, $health_total_pages);
        }
        $health_offset = ($health_page - 1) * $health_dental_items_per_page;

        $health_query = $recordSelect."
                        WHERE pr.module_type = 'health' AND pr.full_name LIKE '%{$health_search}%'
                        ORDER BY pr.created_at DESC
                        LIMIT {$health_dental_items_per_page} OFFSET {$health_offset}";
        $health_result = $conn->query($health_query);
    }
}
