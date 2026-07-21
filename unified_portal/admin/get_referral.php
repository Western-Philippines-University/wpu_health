<?php
header('Content-Type: application/json');
require_once '../config/connect.php';

$id = $_GET['id'] ?? 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Error: Invalid referral ID.']);
    exit;
}

try {
    // Include ALL fields from your database
    $query = "SELECT 
                id, hospital_clinic, referral_date, patient_name, patient_age, patient_sex,
                patient_type_occupation, patient_type_faculty, patient_type_staff, patient_type_student,
                patient_address, case_summary, reason_for_referral, send_back_agency,
                return_date, return_patient_name, return_patient_age, return_patient_sex,
                services_findings, signature_name, designation, send_back_patient_name,
                send_back_patient_age, send_back_patient_sex, created_at, updated_at
              FROM referrals WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $id);
    
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $referral = $result->fetch_assoc();

    if ($referral) {
        echo json_encode(['success' => true, 'referral' => $referral]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Referral not found.']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error in get_referral.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>