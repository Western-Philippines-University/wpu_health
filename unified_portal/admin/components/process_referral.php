<?php
/**
 * Referral Processing Script
 * Handles creation and updates of medical referrals
 */

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/connect.php';

// Clear any previous output
ob_clean();

// Check database connection
if (!$conn || $conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please check your database configuration.'
    ]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Collect and sanitize input data
        $hospital_clinic     = trim($_POST['referral-hospital'] ?? '');
        $referral_date       = trim($_POST['referral-date'] ?? '');
        $patient_name        = trim($_POST['referral-name'] ?? '');
        $patient_age         = isset($_POST['referral-age']) ? (int)$_POST['referral-age'] : 0;
        $patient_sex         = trim($_POST['referral-sex'] ?? '');
        
        // Process patient type checkboxes
        $patient_types = $_POST['referral-type'] ?? [];
        if (!is_array($patient_types)) {
            $patient_types = [$patient_types];
        }
        $patient_type_occupation = in_array('OCCUPATION', $patient_types) ? 1 : 0;
        $patient_type_faculty    = in_array('FACULTY', $patient_types) ? 1 : 0;
        $patient_type_staff      = in_array('STAFF', $patient_types) ? 1 : 0;
        $patient_type_student    = in_array('STUDENT', $patient_types) ? 1 : 0;
        
        $patient_address     = trim($_POST['referral-address'] ?? '');
        $case_summary        = trim($_POST['referral-case'] ?? '');
        $reason_for_referral = trim($_POST['referral-reason'] ?? '');
        
        // Optional fields for send back section
        $send_back_agency    = trim($_POST['referral-send-back'] ?? '');
        $return_date         = trim($_POST['referral-send-back-date'] ?? '');
        $return_patient_name = trim($_POST['referral-send-back-name'] ?? '');
        $return_patient_age  = isset($_POST['referral-send-back-age']) && !empty($_POST['referral-send-back-age']) ? (int)$_POST['referral-send-back-age'] : null;
        $return_patient_sex  = trim($_POST['referral-send-back-sex'] ?? '');
        $services_findings   = trim($_POST['referral-services'] ?? '');
        $signature_name      = trim($_POST['referral-signature'] ?? '');
        $designation         = trim($_POST['referral-designation'] ?? '');
        
        // Validate required fields
        if (empty($hospital_clinic) || empty($referral_date) || empty($patient_name) || 
            $patient_age <= 0 || empty($patient_sex) || empty($patient_address) || 
            empty($case_summary) || empty($reason_for_referral)) {
            throw new Exception('All required fields must be filled out.');
        }

        // Validate age range
        if ($patient_age < 1 || $patient_age > 150) {
            throw new Exception('Please enter a valid age between 1 and 150.');
        }

        // Validate date format
        $date_obj = DateTime::createFromFormat('Y-m-d', $referral_date);
        if (!$date_obj || $date_obj->format('Y-m-d') !== $referral_date) {
            throw new Exception('Invalid referral date format.');
        }
        
        // Handle NULL values for optional fields
        $return_date         = empty($return_date) ? NULL : $return_date;
        $return_patient_name = empty($return_patient_name) ? NULL : $return_patient_name;
        $return_patient_age  = empty($return_patient_age) ? NULL : $return_patient_age;
        $return_patient_sex  = empty($return_patient_sex) ? NULL : $return_patient_sex;
        $send_back_agency    = empty($send_back_agency) ? NULL : $send_back_agency;
        $services_findings   = empty($services_findings) ? NULL : $services_findings;
        $signature_name      = empty($signature_name) ? NULL : $signature_name;
        $designation         = empty($designation) ? NULL : $designation;
        
        // Prepare SQL statement
        $sql = "INSERT INTO referrals (
            hospital_clinic, referral_date, patient_name, patient_age, patient_sex,
            patient_type_occupation, patient_type_faculty, patient_type_staff, patient_type_student,
            patient_address, case_summary, reason_for_referral, send_back_agency, return_date,
            return_patient_name, return_patient_age, return_patient_sex, services_findings,
            signature_name, designation
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }
        
        // Bind parameters with correct types (s = string, i = integer)
        $stmt->bind_param(
            "sssisiiiissssssissss",
            $hospital_clinic, $referral_date, $patient_name, $patient_age, $patient_sex,
            $patient_type_occupation, $patient_type_faculty, $patient_type_staff, $patient_type_student,
            $patient_address, $case_summary, $reason_for_referral, $send_back_agency, $return_date,
            $return_patient_name, $return_patient_age, $return_patient_sex, $services_findings,
            $signature_name, $designation
        );
        
        // Execute and respond
        if ($stmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'Referral saved successfully!',
                'id'      => $conn->insert_id
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Error saving referral: ' . $stmt->error
            ];
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    // Send JSON response
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    // Invalid request method
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. POST required.'
    ]);
    exit;
}
?>