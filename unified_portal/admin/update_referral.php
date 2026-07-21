<?php
require_once __DIR__ . '/../includes/wpu_security.php';
wpu_bootstrap_admin_api();
require_once '../config/connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Accept multiple possible keys for ID
    $id = (int)($_POST['id'] ?? $_POST['referral_id'] ?? $_POST['referral-id'] ?? 0);
    if (!$id) throw new Exception("Missing referral ID.");

    // Get all form data with proper null handling
    $hospital_clinic = trim($_POST['referral-hospital'] ?? '');
    $referral_date = trim($_POST['referral-date'] ?? '');
    $patient_name = trim($_POST['referral-name'] ?? '');
    $patient_age = (int)($_POST['referral-age'] ?? 0);
    $patient_sex = trim($_POST['referral-sex'] ?? '');
    $patient_address = trim($_POST['referral-address'] ?? '');
    $case_summary = trim($_POST['referral-case'] ?? '');
    $reason_for_referral = trim($_POST['referral-reason'] ?? '');
    $send_back_agency = trim($_POST['referral-send-back'] ?? '');
    $return_date = trim($_POST['referral-send-back-date'] ?? '');
    $return_patient_name = trim($_POST['referral-send-back-name'] ?? '');
    $return_patient_age = ($_POST['referral-send-back-age'] !== '') ? (int)$_POST['referral-send-back-age'] : null;
    $return_patient_sex = trim($_POST['referral-send-back-sex'] ?? '');
    $services_findings = trim($_POST['referral-services'] ?? '');
    $signature_name = trim($_POST['referral-signature'] ?? '');
    $designation = trim($_POST['referral-designation'] ?? '');
    
    // Handle the additional send_back fields from your database
    $send_back_patient_name = trim($_POST['send-back-patient-name'] ?? '');
    $send_back_patient_age = trim($_POST['send-back-patient-age'] ?? '');
    $send_back_patient_sex = trim($_POST['send-back-patient-sex'] ?? '');

    // Convert empty strings to NULL for optional fields
    $hospital_clinic = $hospital_clinic === '' ? null : $hospital_clinic;
    $referral_date = $referral_date === '' ? null : $referral_date;
    $patient_name = $patient_name === '' ? null : $patient_name;
    $patient_sex = $patient_sex === '' ? null : $patient_sex;
    $patient_address = $patient_address === '' ? null : $patient_address;
    $case_summary = $case_summary === '' ? null : $case_summary;
    $reason_for_referral = $reason_for_referral === '' ? null : $reason_for_referral;
    $send_back_agency = $send_back_agency === '' ? null : $send_back_agency;
    $return_date = $return_date === '' ? null : $return_date;
    $return_patient_name = $return_patient_name === '' ? null : $return_patient_name;
    $return_patient_sex = $return_patient_sex === '' ? null : $return_patient_sex;
    $services_findings = $services_findings === '' ? null : $services_findings;
    $signature_name = $signature_name === '' ? null : $signature_name;
    $designation = $designation === '' ? null : $designation;
    $send_back_patient_name = $send_back_patient_name === '' ? null : $send_back_patient_name;
    $send_back_patient_age = $send_back_patient_age === '' ? null : $send_back_patient_age;
    $send_back_patient_sex = $send_back_patient_sex === '' ? null : $send_back_patient_sex;

    // Handle patient types
    $patient_type_student = 0;
    $patient_type_faculty = 0;
    $patient_type_staff = 0;
    $patient_type_occupation = 0;

    $patient_types = $_POST['referral-type'] ?? [];
    foreach ($patient_types as $type) {
        switch (strtoupper(trim($type))) {
            case 'STUDENT': $patient_type_student = 1; break;
            case 'FACULTY': $patient_type_faculty = 1; break;
            case 'STAFF': $patient_type_staff = 1; break;
            case 'OCCUPATION': $patient_type_occupation = 1; break;
        }
    }

    // Debug logging
    error_log("Updating referral ID: $id");
    error_log("Patient Name: $patient_name");
    error_log("Hospital: $hospital_clinic");
    error_log("Patient Age: $patient_age");
    error_log("Patient Sex: $patient_sex");

    // Fixed query with ALL database fields
    $query = "UPDATE referrals SET 
        hospital_clinic = ?, 
        referral_date = ?, 
        patient_name = ?, 
        patient_age = ?, 
        patient_sex = ?,
        patient_address = ?, 
        patient_type_student = ?, 
        patient_type_faculty = ?, 
        patient_type_staff = ?,
        patient_type_occupation = ?, 
        case_summary = ?, 
        reason_for_referral = ?, 
        send_back_agency = ?,
        return_date = ?, 
        return_patient_name = ?, 
        return_patient_age = ?, 
        return_patient_sex = ?,
        services_findings = ?, 
        signature_name = ?, 
        designation = ?,
        send_back_patient_name = ?,
        send_back_patient_age = ?,
        send_back_patient_sex = ?,
        updated_at = NOW()
        WHERE id = ?";

    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Bind parameters - note the additional send_back fields
    $stmt->bind_param(
        "sssissiiissssssssssssssi",
        $hospital_clinic,
        $referral_date,
        $patient_name,
        $patient_age,
        $patient_sex,
        $patient_address,
        $patient_type_student,
        $patient_type_faculty,
        $patient_type_staff,
        $patient_type_occupation,
        $case_summary,
        $reason_for_referral,
        $send_back_agency,
        $return_date,
        $return_patient_name,
        $return_patient_age,
        $return_patient_sex,
        $services_findings,
        $signature_name,
        $designation,
        $send_back_patient_name,
        $send_back_patient_age,
        $send_back_patient_sex,
        $id
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Referral updated successfully!']);
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("Error updating referral: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>