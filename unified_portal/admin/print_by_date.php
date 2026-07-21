<?php
require_once __DIR__ . '/../includes/wpu_security.php';
wpu_bootstrap_admin_page();
if (! isset($_SESSION['admin_username']) && function_exists('auth') && auth()->guard('admin')->check()) {
    $_SESSION['admin_username'] = auth()->guard('admin')->user()->username;
}

// Check if user is logged in
if (! isset($_SESSION['admin_username'])) {
    header('Location: '.(function_exists('url') ? url('/admin/login') : 'admin.php'));
    exit;
}

// Database connection
require_once '../config/database.php';
require_once '../includes/wpu_report_lib.php';
$pdo = getDBConnection();

// Ensure visit_date is provided
if (!isset($_GET['visit_date']) || empty($_GET['visit_date'])) {
    die("No visit date provided.");
}

$visit_date = date('Y-m-d', strtotime($_GET['visit_date']));
$module = wpu_report_module_valid($_GET['module'] ?? 'dental');
$records = wpu_report_daily_records($pdo, $module, $visit_date);

// Module-specific titles
$clinic_names = [
    'dental' => 'WPU Dental Clinic',
    'health' => 'WPU Health Clinic'
];
$clinic_name = $clinic_names[$module] ?? 'WPU Clinic';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Records Report - <?php echo $clinic_name; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; color: #000; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .clinic-name { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .report-title { font-size: 16px; margin-bottom: 10px; }
        .report-date { font-size: 14px; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 11px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; }
        .no-records { text-align: center; font-style: italic; padding: 20px; }
        @media print { body { margin: 0.5in; } }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div class="clinic-name"><?php echo $clinic_name; ?></div>
        <div class="report-title">PATIENT RECORDS REPORT</div>
        <div class="report-date">Visit Date: <?= date('F d, Y', strtotime($visit_date)) ?></div>
        <div class="report-date">Report Generated: <?= date('F d, Y') ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Patient Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Department</th>
                <th>Diagnosis</th>
                <th>Attending <?php echo $module === 'dental' ? 'Dentist' : 'Doctor'; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($records)): ?>
                <?php $counter = 1; foreach ($records as $r): ?>
                <tr>
                    <td><?= $counter++ ?></td>
                    <td><?= htmlspecialchars($r['full_name']) ?></td>
                    <td><?= htmlspecialchars($r['gender']) ?></td>
                    <td><?= htmlspecialchars($r['age']) ?></td>
                    <td><?= htmlspecialchars($r['department_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($r['diagnosis']) ?></td>
                    <td><?= htmlspecialchars($r['doctor']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="no-records">No patient records found for the specified date.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <div>Total Records: <?= count($records) ?></div>
    </div>
</body>
</html>

