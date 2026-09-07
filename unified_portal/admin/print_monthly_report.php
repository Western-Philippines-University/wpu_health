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

$month = $_GET['month'] ?? '';
$year = $_GET['year'] ?? '';

if (!$month || !$year) {
    die("Please select both month and year.");
}

$module = wpu_report_module_valid($_GET['module'] ?? 'dental');

// Get month name
$month_names = [
    '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
    '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
    '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
];
$month_name = $month_names[$month] ?? 'Unknown';

// Calculate date range
$start_date = "$year-$month-01";
$end_date = date("Y-m-t", strtotime($start_date));

$records = wpu_report_monthly_records($pdo, $module, $start_date, $end_date);

// Calculate statistics
$total = count($records);
$female = 0;
$male = 0;
$age_less_13 = 0;
$age_13_24 = 0;
$age_25_plus = 0;

$student_count = 0;
$faculty_count = 0;
$staff_count = 0;

$dept_stats = [];
$case_stats = [];
$daily_stats = [];

foreach ($records as $record) {
    if ($record['gender'] == 'Female') $female++;
    elseif ($record['gender'] == 'Male') $male++;
    
    $age = (int)$record['age'];
    if ($age < 13) $age_less_13++;
    elseif ($age >= 13 && $age <= 24) $age_13_24++;
    else $age_25_plus++;
    
    $patient_type = $record['type_name'];
    if ($patient_type == 'Student') $student_count++;
    elseif ($patient_type == 'Faculty') $faculty_count++;
    elseif ($patient_type == 'Staff') $staff_count++;
    
    $dept = $record['department'] ?? 'N/A';
    if (!isset($dept_stats[$dept])) $dept_stats[$dept] = 0;
    $dept_stats[$dept]++;
    
    $case = $record['case_name'] ?? 'N/A';
    if (!isset($case_stats[$case])) $case_stats[$case] = 0;
    $case_stats[$case]++;
    
    $date = $record['visit_date'];
    if (!isset($daily_stats[$date])) $daily_stats[$date] = 0;
    $daily_stats[$date]++;
}

arsort($dept_stats);
arsort($case_stats);
ksort($daily_stats);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Medical Report - <?php echo $clinic_name; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 15mm;
            color: #000;
            font-size: 11px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .clinic-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .report-period {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .report-info {
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }
        td {
            border: 1px solid #000;
            padding: 5px 4px;
            font-size: 10px;
            vertical-align: top;
        }
        .summary-table {
            margin: 15px 0;
        }
        .summary-table th {
            background-color: #e0e0e0;
            text-align: center;
        }
        .summary-table td {
            text-align: center;
            font-weight: bold;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
            page-break-after: avoid;
        }
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 15px 0;
        }
        .stat-item {
            text-align: center;
            padding: 8px;
            background: #f8f8f8;
            border: 1px solid #ddd;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #000;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
        .page-break {
            page-break-before: always;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        @media print {
            body { margin: 15mm; }
            .no-print { display: none; }
        }
        @page {
            margin: 15mm;
            size: A4 portrait;
        }
    </style>
</head>
<body onload="window.print()">
    <!-- HEADER -->
    <div class="header">
        <div class="clinic-name"><?php echo $clinic_name; ?></div>
        <div class="report-title">Monthly Medical Services Report</div>
        <div class="report-period"><?php echo $month_name . ' ' . $year; ?></div>
        <div class="report-info">
            Reporting Period: <?php echo date('M d, Y', strtotime($start_date)) . ' - ' . date('M d, Y', strtotime($end_date)); ?> | 
            Generated: <?php echo date('M d, Y H:i'); ?>
        </div>
    </div>

    <!-- EXECUTIVE SUMMARY -->
    <div class="section-title">EXECUTIVE SUMMARY</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Total Patients</th>
                <th>Female</th>
                <th>Male</th>
                <th>Age &lt; 13</th>
                <th>Age 13-24</th>
                <th>Age ≥ 25</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $total; ?></td>
                <td><?php echo $female; ?></td>
                <td><?php echo $male; ?></td>
                <td><?php echo $age_less_13; ?></td>
                <td><?php echo $age_13_24; ?></td>
                <td><?php echo $age_25_plus; ?></td>
            </tr>
        </tbody>
    </table>

    <!-- PATIENT CATEGORIES -->
    <div class="section-title">PATIENT CATEGORIES</div>
    <div class="stat-grid">
        <div class="stat-item">
            <div class="stat-value"><?php echo $student_count; ?></div>
            <div class="stat-label">STUDENTS</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $faculty_count; ?></div>
            <div class="stat-label">FACULTY</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $staff_count; ?></div>
            <div class="stat-label">STAFF</div>
        </div>
    </div>

    <!-- DEPARTMENT ANALYSIS -->
    <div class="section-title">CASES BY DEPARTMENT</div>
    <table>
        <thead>
            <tr>
                <th width="60%">Department</th>
                <th width="20%" class="text-center">Cases</th>
                <th width="20%" class="text-center">Percentage</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dept_stats as $dept => $count): ?>
            <tr>
                <td><?php echo htmlspecialchars($dept); ?></td>
                <td class="text-center"><?php echo $count; ?></td>
                <td class="text-center"><?php echo $total > 0 ? round(($count/$total)*100, 1) : 0; ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- CASE TYPE ANALYSIS -->
    <div class="section-title">CASES BY MEDICAL TYPE</div>
    <table>
        <thead>
            <tr>
                <th width="60%">Case Type</th>
                <th width="20%" class="text-center">Cases</th>
                <th width="20%" class="text-center">Percentage</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($case_stats as $case => $count): ?>
            <tr>
                <td><?php echo htmlspecialchars($case); ?></td>
                <td class="text-center"><?php echo $count; ?></td>
                <td class="text-center"><?php echo $total > 0 ? round(($count/$total)*100, 1) : 0; ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- DAILY DISTRIBUTION -->
    <div class="section-title">DAILY CASE DISTRIBUTION</div>
    <table>
        <thead>
            <tr>
                <th width="30%">Date</th>
                <th width="40%">Day</th>
                <th width="30%" class="text-center">Cases</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daily_stats as $date => $count): ?>
            <tr>
                <td><?php echo date('M d, Y', strtotime($date)); ?></td>
                <td><?php echo date('l', strtotime($date)); ?></td>
                <td class="text-center"><?php echo $count; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- DETAILED RECORDS -->
    <div class="page-break"></div>
    <div class="section-title">DETAILED PATIENT RECORDS</div>
    
    <?php if (count($records) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th width="10%">Date</th>
                    <th width="15%">Patient Name</th>
                    <th width="10%">ID</th>
                    <th width="8%">Type</th>
                    <th width="6%">Gender</th>
                    <th width="5%">Age</th>
                    <th width="15%">Department</th>
                    <th width="12%">Case Type</th>
                    <th width="12%"><?php echo $module === 'dental' ? 'Dentist' : 'Physician'; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                <tr>
                    <td><?php echo date('m/d/Y', strtotime($record['visit_date'])); ?></td>
                    <td><?php echo htmlspecialchars($record['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($record['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($record['type_name']); ?></td>
                    <td><?php echo htmlspecialchars($record['gender']); ?></td>
                    <td><?php echo htmlspecialchars($record['age']); ?></td>
                    <td><?php echo htmlspecialchars($record['department']); ?></td>
                    <td><?php echo htmlspecialchars($record['case_name']); ?></td>
                    <td><?php echo htmlspecialchars($record['doctor']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">No patient records found for the specified period.</p>
    <?php endif; ?>

    <div class="footer">
        <?php echo $clinic_name; ?> Monthly Report | <?php echo $month_name . ' ' . $year; ?> | Page 1 of 1
    </div>

    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 8px 15px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer;">
            Print Report
        </button>
    </div>
</body>
</html>

