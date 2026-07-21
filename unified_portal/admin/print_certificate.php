<?php
require_once '../config/connect.php';

// Get ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch certificate data
$query = "SELECT * FROM medical_certificates WHERE id = $id";
$result = $conn->query($query);
$cert = $result->fetch_assoc();

// Get the stored certificate code
$code_query = "SELECT certificate_code FROM certificate_codes LIMIT 1";
$code_result = $conn->query($code_query);
$certificate_code = "WPU-QSF-GASS-HSO-01 Rev.00 (09.20.24)"; // Default

if ($code_result && $code_result->num_rows > 0) {
    $code_data = $code_result->fetch_assoc();
    $certificate_code = $code_data['certificate_code'];
}

$staff_query = "SELECT * FROM staff_signatures LIMIT 1";
$staff_result = $conn->query($staff_query);
if ($staff_result && $staff_result->num_rows > 0) {
    $staff = $staff_result->fetch_assoc();
} else {
    // Default values
    $staff = [
        'name' => 'MICAELLA T. BAGALANON-LABUTOY, MD, OHP',
        'position' => 'University Physician',
        'license_no' => 'License. No. 0148115'
    ];
}

// Process medical findings for display
$findingsFit = $cert['findings_fit'] ?? 0;
$findingsImpression = $cert['findings_impression'] ?? 0;
$impressionText = $cert['impression_text'] ?? '';
$advice = $cert['advice'] ?? '';

// Build findings display text
$findingsText = '';
if ($findingsFit == 1 && $findingsImpression == 1) {
    $findingsText = "(✔) physically and mentally fit.<br>(✔) with the impression of <u>" . htmlspecialchars($impressionText) . "</u>";
} elseif ($findingsFit == 1) {
    $findingsText = "(✔) physically and mentally fit.";
} elseif ($findingsImpression == 1) {
    $findingsText = "(✔) with the impression of <u>" . htmlspecialchars($impressionText) . "</u>";
} else {
    $findingsText = "No medical findings recorded.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Medical Certificate</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 40px 60px;
            line-height: 1.8;
            font-size: 16px;
            text-align: justify;
        }
        .header {
            text-align: center;
            position: relative;
            margin-bottom: 20px;
        }
        .header img {
            width: 90px;
            position: absolute;
            left: 0;
            top: 0;
        }
        .header h3 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 2px 0;
            font-size: 14px;
        }
        .office {
            font-weight: bold;
            margin-top: 10px;
            font-size: 15px;
        }
        h2 {
            text-decoration: underline;
            text-align: center;
            margin: 30px 0;
        }
        .content p {
            margin: 15px 0;
        }
        .signature {
            margin-top: 80px;
            text-align: right;
        }
        .signature-line {
            width: 250px;
            border-top: 1px solid #000;
            margin: 0 0 5px auto;
        }
        .signature-name {
            font-weight: bold;
        }
        .footer-text {
            margin-top: 80px;
            text-align: right;
            font-size: 12px;
        }
        
        /* Hide URL and date when printing */
        @media print {
            @page {
                margin: 0;
            }
            body {
                margin: 1.6cm;
            }
            /* Hide browser-specific print headers/footers */
            .header:after, .footer:before {
                display: none !important;
            }
        }
        
        /* Additional CSS to remove browser headers/footers */
        @page {
            size: auto;
            margin: 0;
        }
        
        /* Ensure checkboxes are properly aligned */
        .findings-list {
            margin: 10px 0;
            padding-left: 20px;
        }
        .findings-item {
            margin-bottom: 5px;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <img src="../assets/images/logo.png" alt="University Logo">
        <h3>Republic of the Philippines</h3>
        <h3><strong>WESTERN PHILIPPINES UNIVERSITY</strong></h3>
        <p>A Strong Partner for Sustainable Development</p>
        <div class="office">HEALTH SERVICES OFFICE</div>
    </div>

    <h2>MEDICAL CERTIFICATE</h2>

    <div class="content">
        <p>TO WHOM IT MAY CONCERN:</p>

        <p>
            This is to certify that <u><?php echo htmlspecialchars($cert['name']); ?></u>, 
            <u><?php echo htmlspecialchars($cert['age']); ?></u> years old, 
            <u><?php echo htmlspecialchars($cert['gender']); ?></u>, 
            <u><?php echo htmlspecialchars($cert['civil_status']); ?></u>, 
            a resident of <u><?php echo htmlspecialchars($cert['address']); ?></u>, 
            was examined on <u><?php echo date("F d, Y", strtotime($cert['examination_date'])); ?></u>.
        </p>

        <?php if (!empty($cert['reason'])): ?>
        <p>
            Due to: <u><?php echo htmlspecialchars($cert['reason']); ?></u>
        </p>
        <?php endif; ?>

        <p>
            And found:
        </p>

        <div class="findings-list">
            <?php if ($findingsFit == 1): ?>
            <div class="findings-item">(✔) physically and mentally fit.</div>
            <?php endif; ?>
            
            <?php if ($findingsImpression == 1): ?>
            <div class="findings-item">(✔) with the impression of <u><?php echo htmlspecialchars($impressionText); ?></u></div>
            <?php endif; ?>
            
            <?php if ($findingsFit == 0 && $findingsImpression == 0): ?>
            <div class="findings-item">No medical findings recorded.</div>
            <?php endif; ?>
        </div>

        <?php if (!empty($advice)): ?>
        <p>
            And was advised to <u><?php echo htmlspecialchars($advice); ?></u>.
        </p>
        <?php endif; ?>

        <p>
            This certificate is issued upon request for medical purposes only.
        </p>

        <p>
            <?php if (!empty($cert['receipt_no'])): ?>
            Official Receipt No.: <u><?php echo htmlspecialchars($cert['receipt_no']); ?></u><br>
            <?php endif; ?>
            
            Date Issued: <u><?php echo date("F d, Y", strtotime($cert['date_issued'])); ?></u><br>
            
            <?php if (!empty($cert['mc_no'])): ?>
            MC No.: <u><?php echo htmlspecialchars($cert['mc_no']); ?></u>
            <?php endif; ?>
        </p>
    </div>

    <div class="signature">
        <div class="signature-line"></div>
        <div class="signature-name"><?php echo htmlspecialchars($staff['name']); ?></div>
        <div><?php echo htmlspecialchars($staff['position']); ?></div>
        <div><?php echo htmlspecialchars($staff['license_no']); ?></div>
    </div>

    <div class="footer-text">
        <?php echo htmlspecialchars($certificate_code); ?>
    </div>

</body>
</html>