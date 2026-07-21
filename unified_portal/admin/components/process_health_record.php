<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (! isset($_SESSION['admin_username']) && function_exists('auth') && auth()->guard('admin')->check()) {
    $_SESSION['admin_username'] = auth()->guard('admin')->user()->username;
}

// Check if user is logged in
if (! isset($_SESSION['admin_username'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Database connection - Using unified database
require_once '../../config/database.php';
$pdo = getDBConnection();

// Handle POST request - Save health record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_record') {
    $patient_type_id = $_POST['patient_type'] ?? '';
    $student_id = $_POST['student_id'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $age = $_POST['age'] ?? '';
    $marital_status = $_POST['marital_status'] ?? '';
    $religion = $_POST['religion'] ?? '';
    $is_minor = $_POST['is_minor'] ?? 'No';
    $guardian_name = ($is_minor == 'Yes') ? ($_POST['guardian_name'] ?? '') : '';
    $phone_number = $_POST['phone_number'] ?? '';
    $address = $_POST['address'] ?? '';
    $department_id = $_POST['department'] ?? '';
    $visit_date = $_POST['visit_date'] ?? '';
    $case_type_id = $_POST['case_type'] ?? '';
    $diagnosis = $_POST['diagnosis'] ?? '';
    $treatment = $_POST['treatment'] ?? '';
    $subjective = $_POST['subjective'] ?? '';
    $objectives = $_POST['objectives'] ?? '';
    $diagnostics = $_POST['diagnostics'] ?? '';
    $assessment = $_POST['assessment'] ?? '';
    $plan = $_POST['plan'] ?? '';
    $doctor = $_POST['doctor'] ?? '';
    
    $module_type = 'health'; // Always set for health records

    $response = ['success' => false, 'message' => ''];

    // Validate required fields
    if ($patient_type_id && $student_id && $full_name && $gender && $age && $marital_status && $department_id && $visit_date && $case_type_id && $diagnosis && $treatment && $doctor) {
        try {
            $stmt = $pdo->prepare("INSERT INTO patient_records 
                (module_type, patient_type_id, student_id, full_name, gender, age, marital_status, religion, 
                 is_minor, guardian_name, phone_number, address, department_id, visit_date, 
                 case_type_id, diagnosis, treatment, subjective, objectives, diagnostics, 
                 assessment, plan, doctor) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([$module_type, $patient_type_id, $student_id, $full_name, $gender, $age, $marital_status,
                           $religion, $is_minor, $guardian_name, $phone_number, $address, $department_id,
                           $visit_date, $case_type_id, $diagnosis, $treatment, $subjective, $objectives,
                           $diagnostics, $assessment, $plan, $doctor]);
            
            $response['success'] = true;
            $response['message'] = "Health record saved successfully!";
            require_once __DIR__.'/../../includes/wpu_report_lib.php';
            wpu_report_invalidate_visit($module_type, $visit_date);
            
        } catch (PDOException $e) {
            $response['message'] = "Database error: " . $e->getMessage();
        }
    } else {
        $missing_fields = [];
        if (!$patient_type_id) $missing_fields[] = "Patient Type";
        if (!$student_id) $missing_fields[] = "ID Number";
        if (!$full_name) $missing_fields[] = "Full Name";
        if (!$gender) $missing_fields[] = "Gender";
        if (!$age) $missing_fields[] = "Age";
        if (!$marital_status) $missing_fields[] = "Marital Status";
        if (!$department_id) $missing_fields[] = "Department";
        if (!$visit_date) $missing_fields[] = "Visit Date";
        if (!$case_type_id) $missing_fields[] = "Case Type";
        if (!$diagnosis) $missing_fields[] = "Diagnosis";
        if (!$treatment) $missing_fields[] = "Treatment";
        if (!$doctor) $missing_fields[] = "Doctor";
        
        $response['message'] = "Please fill in all required fields. Missing: " . implode(', ', $missing_fields);
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Invalid request
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Invalid request']);
exit;
?>

