<?php
require_once __DIR__ . '/../includes/wpu_security.php';
wpu_bootstrap_admin_page();

// Database connection
require_once '../config/database.php';
require_once '../includes/wpu_report_lib.php';
$pdo = getDBConnection();

// Check for case_date
if (!isset($_GET['case_date']) || empty($_GET['case_date'])) {
    die("No visit date provided.");
}

$case_date = $_GET['case_date'];
$module = wpu_report_module_valid($_GET['module'] ?? 'dental');

$records = wpu_report_daily_records($pdo, $module, $case_date);
$total = count($records);
$stats = wpu_report_compute_stats($records);
$gender_stats = $stats['gender_stats'];
$department_stats = $stats['department_stats'];
$age_stats = $stats['age_stats'];

// Sort departments by count
arsort($department_stats);

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
    <title>Daily Medical Report - <?php echo $clinic_name; ?></title>
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
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .clinic-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .report-date {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .report-info {
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
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
            font-size: 9px;
        }
        .summary-section {
            margin: 15px 0;
            padding: 12px;
            background: #f8f8f8;
            border: 1px solid #ddd;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin: 10px 0;
        }
        .stat-item {
            padding: 8px;
            background: white;
            border: 1px solid #ccc;
            text-align: center;
        }
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #000;
        }
        .stat-label {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 15px 0 8px 0;
            padding-bottom: 4px;
            border-bottom: 1px solid #000;
        }
        .department-breakdown {
            margin: 10px 0;
        }
        .dept-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dotted #ddd;
        }
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #000;
            font-size: 9px;
            text-align: center;
            color: #666;
        }
        .text-center {
            text-align: center;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #999;
            padding: 20px;
            border: 1px solid #ddd;
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
        <div class="report-title">Daily Patient Services Report</div>
        <div class="report-date"><?php echo date('l, F d, Y', strtotime($case_date)); ?></div>
        <div class="report-info">
            Report Generated: <?php echo date('M d, Y H:i'); ?> | 
            Day of Week: <?php echo date('l', strtotime($case_date)); ?>
        </div>
    </div>

    <!-- EXECUTIVE SUMMARY -->
    <div class="section-title">DAILY SUMMARY</div>
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-value"><?php echo $total; ?></div>
            <div class="stat-label">TOTAL PATIENTS</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $gender_stats['Male']; ?></div>
            <div class="stat-label">MALE</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $gender_stats['Female']; ?></div>
            <div class="stat-label">FEMALE</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">
                <?php 
                $unique_depts = count($department_stats);
                echo $unique_depts;
                ?>
            </div>
            <div class="stat-label">DEPARTMENTS</div>
        </div>
    </div>

    <!-- AGE DISTRIBUTION -->
    <div class="section-title">AGE GROUP DISTRIBUTION</div>
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-value"><?php echo $age_stats['children']; ?></div>
            <div class="stat-label">CHILDREN<br>(&lt; 13)</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $age_stats['youth']; ?></div>
            <div class="stat-label">YOUTH<br>(13-24)</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $age_stats['adults']; ?></div>
            <div class="stat-label">ADULTS<br>(25-59)</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $age_stats['seniors']; ?></div>
            <div class="stat-label">SENIORS<br>(60+)</div>
        </div>
    </div>

    <!-- DEPARTMENT BREAKDOWN -->
    <div class="section-title">DEPARTMENT BREAKDOWN</div>
    <div class="department-breakdown">
        <?php foreach ($department_stats as $dept => $count): ?>
            <div class="dept-item">
                <span><?php echo htmlspecialchars($dept); ?></span>
                <span><strong><?php echo $count; ?></strong> patients (<?php echo $total > 0 ? round(($count/$total)*100, 1) : 0; ?>%)</span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- DETAILED PATIENT RECORDS -->
    <div class="section-title">PATIENT CASE DETAILS</div>
    
    <?php if (!empty($records)): ?>
        <table>
            <thead>
                <tr>
                    <th width="4%">No.</th>
                    <th width="18%">Patient Name</th>
                    <th width="8%">Gender</th>
                    <th width="6%">Age</th>
                    <th width="18%">Department</th>
                    <th width="25%">Medical Diagnosis</th>
                    <th width="16%">Attending <?php echo $module === 'dental' ? 'Dentist' : 'Doctor'; ?></th>
                    <th width="5%">ID</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; ?>
                <?php foreach ($records as $r): ?>
                <tr>
                    <td class="text-center"><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($r['full_name']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($r['gender']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($r['age']); ?></td>
                    <td><?php echo htmlspecialchars($r['department_name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($r['diagnosis']); ?></td>
                    <td><?php echo htmlspecialchars($r['doctor']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($r['student_id'] ?? 'N/A'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">No patient records found for <?php echo date('F d, Y', strtotime($case_date)); ?></div>
    <?php endif; ?>

    <div class="footer">
        <?php echo $clinic_name; ?> Daily Report | <?php echo date('F d, Y', strtotime($case_date)); ?> | 
        Total Cases: <?php echo $total; ?> | Page 1 of 1
    </div>

    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 8px 15px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 11px;">
            Print Report
        </button>
    </div>
</body>
</html>

