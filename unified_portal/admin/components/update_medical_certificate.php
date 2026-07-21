<?php
require_once __DIR__.'/../../config/connect.php';

error_reporting(0);

function wpu_update_certificate_json_send(array $payload): void
{
    if (! headers_sent()) {
        header('Content-Type: application/json; charset=UTF-8');
    }
    echo json_encode($payload);
    if (! defined('WPU_LARAVEL_BRIDGE') || ! WPU_LARAVEL_BRIDGE) {
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        wpu_update_certificate_json_send(['success' => false, 'message' => 'Invalid certificate ID']);

        return;
    }

    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $civil_status = $_POST['civil_status'] ?? '';
    $address = $_POST['address'] ?? '';
    $date = $_POST['date'] ?? '';
    $reason = $_POST['reason'] ?? '';
    $impression_text = $_POST['impression_text'] ?? '';
    $advice = $_POST['advice'] ?? '';
    $date_issued = $_POST['date_issued'] ?? '';
    $mc_no = $_POST['mc_no'] ?? '';
    $receipt_no = $_POST['receipt_no'] ?? '';
    
    // Handle medical findings properly
    $findings_fit = 0;
    $findings_impression = 0;
    
    if (isset($_POST['findings'])) {
        if (is_array($_POST['findings'])) {
            $findings_fit = in_array('fit', $_POST['findings']) ? 1 : 0;
            $findings_impression = in_array('impression', $_POST['findings']) ? 1 : 0;
        }
    }

    try {
        $sql = "UPDATE medical_certificates 
                SET name=?, age=?, gender=?, civil_status=?, address=?, 
                    examination_date=?, reason=?, impression_text=?, advice=?, 
                    date_issued=?, mc_no=?, receipt_no=?,
                    findings_fit=?, findings_impression=?, updated_at=NOW()
                WHERE id=?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $types = 'si'.str_repeat('s', 10).'iii';
        $stmt->bind_param(
            $types,
            $name,
            $age,
            $gender,
            $civil_status,
            $address,
            $date,
            $reason,
            $impression_text,
            $advice,
            $date_issued,
            $mc_no,
            $receipt_no,
            $findings_fit,
            $findings_impression,
            $id
        );

        if ($stmt->execute()) {
            wpu_update_certificate_json_send(['success' => true, 'message' => 'Medical certificate updated successfully!']);
        } else {
            throw new Exception('Failed to update certificate: '.$stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        wpu_update_certificate_json_send(['success' => false, 'message' => 'Error: '.$e->getMessage()]);
    }

    $conn->close();

    return;
}

wpu_update_certificate_json_send(['success' => false, 'message' => 'Invalid request method']);

return;