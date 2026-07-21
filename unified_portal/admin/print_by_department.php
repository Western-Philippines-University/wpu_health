<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (! isset($_SESSION['admin_username']) && function_exists('auth') && auth()->guard('admin')->check()) {
    $_SESSION['admin_username'] = auth()->guard('admin')->user()->username;
}

// Check if user is logged in
if (! isset($_SESSION['admin_username'])) {
    header('Location: '.(function_exists('url') ? url('/admin/login') : 'admin.php'));
    exit;
}

// Get module type (dental or health)
$module = $_GET['module'] ?? 'dental';
if (!in_array($module, ['dental', 'health'])) {
    $module = 'dental';
}

// Database connection
require_once '../config/database.php';
$pdo = getDBConnection();

// Get department name from query string
if (!isset($_GET['department']) || empty($_GET['department'])) {
    die("No department selected.");
}

$param = $_GET['department'];

// Detect whether it's a numeric ID or a department name
if (is_numeric($param)) {
    $deptStmt = $pdo->prepare("SELECT id, name FROM departments WHERE id = :id");
    $deptStmt->execute(['id' => (int)$param]);
} else {
    $deptStmt = $pdo->prepare("SELECT id, name FROM departments WHERE name = :name");
    $deptStmt->execute(['name' => $param]);
}

$dept = $deptStmt->fetch(PDO::FETCH_ASSOC);

if (!$dept) {
    die("Invalid department selected.");
}

$department = $dept['name'];

// Fetch all patient records for this department, filtered by module_type
$sql = "SELECT pr.*, d.name AS department_name
        FROM patient_records pr
        LEFT JOIN departments d ON pr.department_id = d.id
        WHERE pr.department_id = :dept_id AND pr.module_type = :module_type
        ORDER BY pr.visit_date DESC, pr.full_name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['dept_id' => $dept['id'], 'module_type' => $module]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate statistics
$total_records = count($records);
$gender_stats = ['Male' => 0, 'Female' => 0];
$age_groups = ['<18' => 0, '18-25' => 0, '26-35' => 0, '36-50' => 0, '>50' => 0];

foreach ($records as $record) {
    // Gender statistics
    if (isset($gender_stats[$record['gender']])) {
        $gender_stats[$record['gender']]++;
    }
    
    // Age group statistics
    $age = (int)$record['age'];
    if ($age < 18) {
        $age_groups['<18']++;
    } elseif ($age >= 18 && $age <= 25) {
        $age_groups['18-25']++;
    } elseif ($age >= 26 && $age <= 35) {
        $age_groups['26-35']++;
    } elseif ($age >= 36 && $age <= 50) {
        $age_groups['36-50']++;
    } else {
        $age_groups['>50']++;
    }
}

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
    <title>Department Patient Report - <?php echo $clinic_name; ?></title>
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
        .department-name {
            font-size: 16px;
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
            margin: 15px 0;
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
            vertical-align: top;
        }
        .summary-section {
            margin: 15px 0;
            padding: 10px;
            background: #f8f8f8;
            border: 1px solid #ddd;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 10px 0;
        }
        .stat-item {
            padding: 8px;
            background: white;
            border: 1px solid #ccc;
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
        .text-right {
            text-align: right;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #999;
            padding: 20px;
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
        <div class="report-title">Department Patient Records Report</div>
        <div class="department-name"><?php echo htmlspecialchars($department); ?> Department</div>
        <div class="report-info">
            Report Generated: <?php echo date('F d, Y H:i'); ?> | 
            Total Records: <?php echo $total_records; ?>
        </div>
    </div>

    <!-- SUMMARY STATISTICS -->
    <div class="section-title">SUMMARY STATISTICS</div>
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-value"><?php echo $total_records; ?></div>
            <div class="stat-label">TOTAL PATIENTS</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $gender_stats['Male']; ?></div>
            <div class="stat-label">MALE PATIENTS</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $gender_stats['Female']; ?></div>
            <div class="stat-label">FEMALE PATIENTS</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">
                <?php 
                $latest_date = $total_records > 0 ? max(array_column($records, 'visit_date')) : 'N/A';
                echo $latest_date !== 'N/A' ? date('M d, Y', strtotime($latest_date)) : 'N/A';
                ?>
            </div>
            <div class="stat-label">MOST RECENT VISIT</div>
        </div>
    </div>

    <!-- AGE DISTRIBUTION -->
    <div class="section-title">AGE GROUP DISTRIBUTION</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Age Group</th>
                <th width="20%" class="text-center">Count</th>
                <th width="20%" class="text-center">Percentage</th>
                <th width="20%">Age Group</th>
                <th width="20%" class="text-center">Count</th>
                <th width="20%" class="text-center">Percentage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Under 18</td>
                <td class="text-center"><?php echo $age_groups['<18']; ?></td>
                <td class="text-center"><?php echo $total_records > 0 ? round(($age_groups['<18']/$total_records)*100, 1) : 0; ?>%</td>
                <td>26-35 Years</td>
                <td class="text-center"><?php echo $age_groups['26-35']; ?></td>
                <td class="text-center"><?php echo $total_records > 0 ? round(($age_groups['26-35']/$total_records)*100, 1) : 0; ?>%</td>
            </tr>
            <tr>
                <td>18-25 Years</td>
                <td class="text-center"><?php echo $age_groups['18-25']; ?></td>
                <td class="text-center"><?php echo $total_records > 0 ? round(($age_groups['18-25']/$total_records)*100, 1) : 0; ?>%</td>
                <td>36-50 Years</td>
                <td class="text-center"><?php echo $age_groups['36-50']; ?></td>
                <td class="text-center"><?php echo $total_records > 0 ? round(($age_groups['36-50']/$total_records)*100, 1) : 0; ?>%</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td>Over 50 Years</td>
                <td class="text-center"><?php echo $age_groups['>50']; ?></td>
                <td class="text-center"><?php echo $total_records > 0 ? round(($age_groups['>50']/$total_records)*100, 1) : 0; ?>%</td>
            </tr>
        </tbody>
    </table>

    <!-- DETAILED PATIENT RECORDS -->
    <div class="section-title">DETAILED PATIENT RECORDS</div>
    
    <?php if (!empty($records)): ?>
        <table>
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="15%">Patient Name</th>
                    <th width="8%">Gender</th>
                    <th width="6%">Age</th>
                    <th width="12%">Visit Date</th>
                    <th width="25%">Medical Diagnosis</th>
                    <th width="15%">Attending <?php echo $module === 'dental' ? 'Dentist' : 'Physician'; ?></th>
                    <th width="14%">Patient ID</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; ?>
                <?php foreach ($records as $r): ?>
                <tr>
                    <td class="text-center"><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($r['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($r['gender']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($r['age']); ?></td>
                    <td><?php echo date('m/d/Y', strtotime($r['visit_date'])); ?></td>
                    <td><?php echo htmlspecialchars($r['diagnosis']); ?></td>
                    <td><?php echo htmlspecialchars($r['doctor']); ?></td>
                    <td><?php echo htmlspecialchars($r['student_id'] ?? 'N/A'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">No patient records found for this department.</div>
    <?php endif; ?>

    <div class="footer">
        <?php echo $clinic_name; ?> Department Report | <?php echo htmlspecialchars($department); ?> Department | 
        Generated on: <?php echo date('F d, Y'); ?> | Page 1 of 1
    </div>

    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 8px 15px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 11px;">
            Print Report
        </button>
    </div>
</body>
</html>

