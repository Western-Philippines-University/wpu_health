<?php
require '../config/connect.php';

// Get the stored referral code
$code_query = "SELECT referral_code FROM certificate_codes LIMIT 1";
$code_result = $conn->query($code_query);

if ($code_result && $code_result->num_rows > 0) {
    $code_data = $code_result->fetch_assoc();
    $referral_code = $code_data['referral_code'];
}

// Get referral id
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM referrals WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$ref = $result->fetch_assoc();
if (!$ref) { die("Referral not found."); }

function mark($v) { return $v ? '✔' : ''; }

$staff_query = "SELECT * FROM staff_signatures LIMIT 1";
$staff_result = $conn->query($staff_query);
if ($staff_result && $staff_result->num_rows > 0) {
    $staff = $staff_result->fetch_assoc();
} else {
    // Default values
    $staff = [
        'name' => 'MICAELLA T. BAGALANON-LABUTOY, MD, OHP',
        'position' => 'Medical Officer IV',
        'license_no' => 'License. No. 0148115'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Two Way Referral Form</title>
<style>
  @page { 
    size: A4; 
    margin: 40px 50px; 
  }

  body {
    font-family: "Times New Roman", serif;
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
    padding: 0;
  }

  h3, h4, p { margin: 0; }
  .center { text-align: center; }
  .section { margin-top: 15px; }
  .underline { border-bottom: 1px solid #000; display: inline-block; min-width: 150px; }
  img {
    width: 90px;
    position: absolute;
    left: 0;
    top: 0;
  }

  /* --- PRINT STYLES --- */
  @media print {
  @page {
    margin: 40px 50px;
  }

  body {
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
    margin: 0;
  }

  /* Hide browser headers/footers in most modern browsers */
  @page {
    @top-left { content: none; }
    @top-right { content: none; }
    @bottom-left { content: none; }
    @bottom-right { content: none; }
  }
}

</style>
</head>
<body onload="window.print()">

<!-- Header -->
<div class="center">
  <img src="../assets/images/logo.png" alt="University Logo">
  <h3>Republic of the Philippines</h3>
  <h3><strong>WESTERN PHILIPPINES UNIVERSITY</strong></h3>
  <p>A Strong Partner for Sustainable Development</p>
  <p><strong>HEALTH SERVICES OFFICE</strong></p>
  <h4><strong>TWO WAY REFERRAL FORM</strong></h4>
</div>

<div class="section">
  To: <span class="underline"><?php echo htmlspecialchars($ref['hospital_clinic']); ?></span>
  <span style="float:right;">Date: <span class="underline"><?php echo date('F d, Y', strtotime($ref['referral_date'])); ?></span></span>
</div>

<div class="section">
  Respectfully Referring Patient
</div>

<div class="section">
  Name: <span class="underline"><?php echo htmlspecialchars($ref['patient_name']); ?></span>
  Age: <span class="underline"><?php echo htmlspecialchars($ref['patient_age']); ?></span> years old
</div>

<div class="section">
  Sex:
  Male (<?php echo $ref['patient_sex']=='MALE'?'✔':''; ?>)
  Female (<?php echo $ref['patient_sex']=='FEMALE'?'✔':''; ?>)
  &nbsp;&nbsp;Occupation (<?php echo $ref['patient_type_occupation']?'✔':''; ?>)
  Faculty (<?php echo $ref['patient_type_faculty']?'✔':''; ?>)
  Staff (<?php echo $ref['patient_type_staff']?'✔':''; ?>)
  Student (<?php echo $ref['patient_type_student']?'✔':''; ?>)
</div>

<div class="section">
  Address: <span class="underline"><?php echo htmlspecialchars($ref['patient_address']); ?></span>
</div>

<div class="section">
  CASE SUMMARY:<br>
  <div class="underline" style="width:100%;"><?php echo nl2br(htmlspecialchars($ref['case_summary'])); ?></div>
</div>

<div class="section">
  REASON FOR REFERRAL / SERVICES REQUESTED:<br>
  <div class="underline" style="width:100%;"><?php echo nl2br(htmlspecialchars($ref['reason_for_referral'])); ?></div>
</div>

<div class="section" style="margin-top:30px; text-align: right; clear: both;">
    <strong><?php echo $staff['name']; ?></strong><br>
    <?php echo $staff['position']; ?><br>
    <?php echo $staff['license_no']; ?>
</div>

<hr style="margin:30px 0;">

<!-- Return Section -->
<div class="section">
  <strong>(Cut here and Send back to)</strong><br><br>

  Send Back to Referring Agency:
  <span class="underline"><?php echo htmlspecialchars($ref['send_back_agency']); ?></span><br><br>

  Date:
  <span class="underline"><?php echo $ref['return_date'] ? date('F d, Y', strtotime($ref['return_date'])) : ''; ?></span><br><br>

  Name of Patient:
  <span class="underline"><?php echo htmlspecialchars($ref['return_patient_name']); ?></span>
  Age:
  <span class="underline"><?php echo htmlspecialchars($ref['return_patient_age']); ?></span>
  Sex:
  <span class="underline"><?php echo htmlspecialchars($ref['return_patient_sex']); ?></span><br><br>

  SERVICES DONE / FINDINGS / RECOMMENDATIONS:<br>
  <div class="underline" style="width:100%;"><?php echo nl2br(htmlspecialchars($ref['services_findings'])); ?></div><br><br>

  Name and Signature:
  <span class="underline"><?php echo htmlspecialchars($ref['signature_name']); ?></span><br><br>
  Designation:
  <span class="underline"><?php echo htmlspecialchars($ref['designation']); ?></span>
</div>

<div class="center" style="margin-top:30px;font-size:12px; text-align: right;">
  <?php echo $referral_code; ?>
</div>

</body>
</html>
