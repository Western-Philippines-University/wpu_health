<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>WPU Health Services Office</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="icon" type="image/x-icon" href="../assets/images/logo.png">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
            integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>

    <body>

        <?php include '../components/adming_header.php'; ?>

        <div class="main-content">
            <div class="button-wrapper">
                <button class="btn" id="medicalCertBtn">Medical Certificate</button>
                <button class="btn" id="referralBtn">Two Way Referral</button>
            </div>
        </div>

        <div id="medicalCertModal" class="modal">
            <div class="modal-content">
                <span class="close"><i class="fa-solid fa-xmark"></i></span>
                <h2>Medical Certificate Form</h2>
                <form id="certificateForm" method="post" action="components/process_medical_certificate.php">
                    <div class="form-section-header">Personal Information</div>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter full name">
                    </div>

                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" placeholder="Enter age">
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="civil_status">Civil Status</label>
                        <select id="civil_status" name="civil_status">
                            <option value="">Select Civil Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Complete Address</label>
                        <textarea id="address" name="address" placeholder="Enter complete address" rows="3"></textarea>
                    </div>

                    <div class="form-section-header">Medical Examination</div>
                    <div class="form-group">
                        <label for="date">Date of Examination</label>
                        <input type="date" id="date" name="date">
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason for Examination</label>
                        <input type="text" id="reason" name="reason" placeholder="Enter reason for examination">
                    </div>

                    <div class="form-section-header">Medical Findings</div>
                    <div class="form-group full-width">
                        <label>Assessment Results</label>
                        <div class="checkbox-container">
    <input type="checkbox" id="fit" name="findings[]" value="fit">
    <label for="fit">Physically and mentally fit</label>
</div>
<div class="impression-group">
    <div class="checkbox-container">
        <input type="checkbox" id="impression" name="findings[]" value="impression">
        <label for="impression">With the impression of:</label>
    </div>
    <textarea id="impression_text" name="impression_text"
        placeholder="Enter medical impression details" rows="2" disabled></textarea>
</div>

                    </div>

                    <div class="form-group full-width">
                        <label for="advice">Medical Advice</label>
                        <textarea id="advice" name="advice" placeholder="Enter medical advice and recommendations"
                            rows="3"></textarea>
                    </div>

                    <div class="form-section-header">Administrative Details</div>
                    <div class="form-group" style="display: none;">
    <label for="receipt_no">Official Receipt No.</label>
    <input type="text" id="receipt_no" name="receipt_no" readonly>
</div>
                    <div class="form-group">
    <label for="date_issued">Date Issued</label>
    <input type="date" id="date_issued" name="date_issued" value="<?php echo date('Y-m-d'); ?>" readonly>
</div>

                    <div class="form-group w-full">
                        <label for="mc_no">Medical Certificate No.</label>
                        <input type="text" id="mc_no" name="mc_no" placeholder="Enter MC number">
                    </div>

                    <button type="submit" class="submit-btn" name="submit">Generate Certificate</button>
                </form>
            </div>
        </div>

        <div id="referralModal" class="modal">
            <div class="modal-content">
                <span class="close"><i class="fa-solid fa-xmark"></i></span>
                <h2>Two Way Referral Form</h2>
                <form id="referralForm" method="post" action="components/process_referral.php">
                    <div class="form-section-header">Referral Header</div>
                    <div class="form-group">
                        <label for="referral-hospital">To: HOSPITAL/CLINIC OF CHOICE</label>
                        <input type="text" id="referral-hospital" name="referral-hospital"
                            placeholder="Enter hospital/clinic name">
                    </div>

                    <div class="form-group">
                        <label for="referral-date">Date:</label>
                        <input type="date" id="referral-date" name="referral-date">
                    </div>

                    <div class="form-section-header">Patient Information</div>
                    <div class="form-group">
                        <label for="referral-name">Patient Name:</label>
                        <input type="text" id="referral-name" name="referral-name"
                            placeholder="Enter patient's full name">
                    </div>

                    <div class="form-group">
                        <label for="referral-age">Age:</label>
                        <input type="number" id="referral-age" name="referral-age" placeholder="Enter age">
                    </div>

                    <div class="form-group">
                        <label>Sex:</label>
                        <div class="checkbox-group">
                            <div class="checkbox-option">
                                <input type="radio" id="referral-sex-male" name="referral-sex" value="MALE">
                                <label for="referral-sex-male">MALE</label>
                            </div>
                            <div class="checkbox-option">
                                <input type="radio" id="referral-sex-female" name="referral-sex" value="FEMALE">
                                <label for="referral-sex-female">FEMALE</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Patient Type:</label>
                        <div class="checkbox-group">
                            <div class="checkbox-option">
                                <input type="checkbox" id="referral-occupation" name="referral-type" value="OCCUPATION">
                                <label for="referral-occupation">OCCUPATION</label>
                            </div>
                            <div class="checkbox-option">
                                <input type="checkbox" id="referral-faculty" name="referral-type" value="FACULTY">
                                <label for="referral-faculty">FACULTY</label>
                            </div>
                            <div class="checkbox-option">
                                <input type="checkbox" id="referral-staff" name="referral-type" value="STAFF">
                                <label for="referral-staff">STAFF</label>
                            </div>
                            <div class="checkbox-option">
                                <input type="checkbox" id="referral-student" name="referral-type" value="STUDENT">
                                <label for="referral-student">STUDENT</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="referral-address">Complete Address:</label>
                        <textarea id="referral-address" name="referral-address" placeholder="Enter complete address"
                            rows="2"></textarea>
                    </div>

                    <div class="form-section-header">Medical Information</div>
                    <div class="form-group full-width">
                        <label for="referral-case">CASE SUMMARY:</label>
                        <textarea id="referral-case" name="referral-case"
                            placeholder="Enter case summary and medical history" rows="3"></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="referral-reason">REASON FOR REFERRAL/SERVICES REQUESTED:</label>
                        <textarea id="referral-reason" name="referral-reason"
                            placeholder="Enter reason for referral and services requested" rows="3"></textarea>
                    </div>

                    <div class="form-section-header">Return Information</div>
                    <div class="form-group">
                        <label for="referral-send-back">Send Back to Referring Agency:</label>
                        <input type="text" id="referral-send-back" name="referral-send-back"
                            placeholder="Enter referring agency name">
                    </div>

                    <div class="form-group">
                        <label for="referral-send-back-date">Return Date:</label>
                        <input type="date" id="referral-send-back-date" name="referral-send-back-date">
                    </div>

                    <div class="form-group">
                        <label for="referral-send-back-name">Patient Name (Return):</label>
                        <input type="text" id="referral-send-back-name" name="referral-send-back-name"
                            placeholder="Enter patient's name for return">
                    </div>

                    <div class="form-group">
                        <label for="referral-send-back-age">Age (Return):</label>
                        <input type="number" id="referral-send-back-age" name="referral-send-back-age"
                            placeholder="Enter age for return">
                    </div>

                    <div class="form-group full-width">
                        <label for="referral-send-back-sex">Sex (Return):</label>
                        <select id="referral-send-back-sex" name="referral-send-back-sex">
                            <option value="">Select Sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="referral-services">SERVICES DONE/FINDINGS/RECOMMENDATIONS:</label>
                        <textarea id="referral-services" name="referral-services"
                            placeholder="Enter services provided, findings, and recommendations" rows="3"></textarea>
                    </div>

                    <div class="form-section-header">Signature & Authorization</div>
                    <div class="form-group">
                        <label for="referral-signature">Name and Signature:</label>
                        <input type="text" id="referral-signature" name="referral-signature"
                            placeholder="Enter name and signature">
                    </div>

                    <div class="form-group">
                        <label for="referral-designation">Designation:</label>
                        <input type="text" id="referral-designation" name="referral-designation"
                            placeholder="Enter designation/title">
                    </div>

                    <button type="submit" class="submit-btn" name="submit">Generate Referral</button>
                </form>
            </div>
        </div>


        <div id="referralDisplayModal" class="modal referral-modal">
            <div class="modal-content referral-modal-content">
                <span class="close referral-close"><i class="fa-solid fa-xmark"></i></span>
                <div class="referral" id="referralContent">
                    <div class="certificate-header">
                        <div class="header-content">
                            <div class="header-left">
                                <img src="assets/images/logo.png" alt="WPU Logo" class="logo">
                                <h3>Republic of the Philippines<br>Western Philippines University</h3>
                            </div>
                            <div class="header-right">
                                <p>A STRONG PARTNER FOR SUSTAINABLE DEVELOPMENT</p>
                            </div>
                        </div>
                        <h4 class="health-office">HEALTH SERVICES OFFICE</h4>
                    </div>
                    <div class="referral-title">TWO WAY REFERRAL FORM</div>

                    <div class="referral-body">
                        <p>To: <span class="dotted-line" id="ref-hospital"></span> Date: <span class="dotted-line"
                                id="ref-date"></span></p>

                        <p>Respectfully Referring Patient</p>

                        <p>Name: <span class="dotted-line" id="ref-name"></span> Age: <span class="dotted-line"
                                id="ref-age"></span> years old</p>

                        <p>Sex:
                            <span id="ref-sex-male">MALE ( )</span>
                            <span id="ref-sex-female">FEMALE ( )</span>
                            <span id="ref-occupation">OCCUPATION ( )</span>
                            <span id="ref-faculty">FACULTY ( )</span>
                            <span id="ref-staff">STAFF ( )</span>
                            <span id="ref-student">STUDENT ( )</span>
                        </p>

                        <p>Address: <span class="dotted-line" id="ref-address"></span></p>

                        <div class="referral-section">
                            <div class="referral-section-title">CASE SUMMARY:</div>
                            <p id="ref-case"></p>
                        </div>

                        <div class="referral-section">
                            <div class="referral-section-title">REASON FOR REFERRAL/SERVICES REQUESTED:</div>
                            <p id="ref-reason"></p>
                        </div>

                        <div class="signature">
                            <div class="signature-line"></div>
                            <div class="signature-name">MICAELLA T. BAGALANON-LABUTOY, MD, OHP</div>
                            <div>MEDICAL OFFICER IV</div>
                            <div>License. No. 0148115</div>
                        </div>

                        <div class="cut-here">(Cut here and Send back to)</div>

                        <p>Send Back to Referring Agency: <span class="dotted-line" id="ref-send-back"></span></p>
                        <p>Date: <span class="dotted-line" id="ref-send-back-date"></span></p>
                        <p>Name of Patient: <span class="dotted-line" id="ref-send-back-name"></span></p>
                        <p>Age: <span class="dotted-line" id="ref-send-back-age"></span> Sex: <span class="dotted-line"
                                id="ref-send-back-sex"></span></p>

                        <div class="referral-section">
                            <div class="referral-section-title">SERVICES DONE/FINDINGS/RECOMMENDATIONS:</div>
                            <p id="ref-services"></p>
                        </div>

                        <p>Name and Signature: <span class="dotted-line" id="ref-signature"></span></p>
                        <p>Designature: <span class="dotted-line" id="ref-designation"></span></p>
                    </div>

                    <div class="referral-footer">
                        WPU-QSF-GASS-HSO-12 Rev.00 (09.20.24)
                    </div>
                </div>

                <div class="action-buttons">
                    <!-- <button class="action-btn" id="printRefBtn">Print Referral</button> -->
                    <button class="action-btn" id="downloadRefBtn">Download as PDF</button>
                    <button class="action-btn" id="newRefBtn">Create New Referral</button>
                </div>
            </div>
        </div>

        <div id="certificateModal" class="modal certificate-modal">
            <div class="modal-content certificate-modal-content">
                <span class="close certificate-close"><i class="fa-solid fa-xmark"></i></span>
                <div class="certificate" id="certificateContent">
                    <div class="certificate-header">
                        <div class="header-content">
                            <div class="header-left">
                                <img src="assets/images/logo.png" alt="WPU Logo" class="logo">
                                <h3>Republic of the Philippines<br>Western Philippines University</h3>
                            </div>
                            <div class="header-right">
                                <p>A STRONG PARTNER FOR SUSTAINABLE DEVELOPMENT</p>
                            </div>
                        </div>
                        <h4 class="health-office">HEALTH SERVICES OFFICE</h4>
                    </div>
                    <div class="certificate-title">MEDICAL CERTIFICATE</div>

                    <div class="certificate-body">
                        <p>TO WHOM IT MAY CONCERN:</p>

                        <p>This is to certify that <span class="underline" id="cert-name"></span>, <span
                                class="underline" id="cert-age"></span> years old,
                            <span class="underline" id="cert-gender"></span>, <span class="underline"
                                id="cert-civil-status"></span>.
                            A resident of <span class="underline" id="cert-address"></span> was examined on <span
                                class="underline" id="cert-date"></span>.
                        </p>

                        <p>Due to: <span class="underline" id="cert-reason"></span></p>

                        <p>And found:</p>

                        <p id="cert-fit"><span class="checkbox"></span> physically and mentally fit.</p>
                        <p id="cert-impression"><span class="checkbox"></span> with the impression of <span
                                class="underline" id="cert-impression-text"></span>.</p>

                        <p>And was advised to <span class="underline" id="cert-advice"></span>.</p>

                        <p>This certificate is issued upon request for medical purposes only.</p>

                        <p>Official Receipt No.: <span class="underline" id="cert-receipt-no"></span><br>
                            Date Issued: <span class="underline" id="cert-date-issued"></span><br>
                            MC No.: <span class="underline" id="cert-mc-no"></span></p>
                    </div>

                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-name">MICAELLA T. BAGALANON-LABUTOY, MD, OHP</div>
                        <div>University Physician</div>
                        <div>License. No. 0148115</div>
                    </div>

                    <div style="margin-top: 30px; text-align: center; font-size: 12px;">
                        WPU-QSF-GASS-HSO-01 Rev.00 (09.20.24)
                    </div>
                </div>

                <div class="action-buttons">
                    <!-- <button class="action-btn" id="printBtn">Print Certificate</button> -->
                    <button class="action-btn" id="downloadBtn">Download as PDF</button>
                    <button class="action-btn" id="newCertBtn">Create New Certificate</button>
                </div>
            </div>
        </div>

        <script>
document.addEventListener('DOMContentLoaded', function () {
    const impressionCheckbox = document.getElementById('impression');
    const impressionText = document.getElementById('impression_text');

    if (!impressionCheckbox || !impressionText) return; // safety

    // Set initial state when page loads
    impressionText.disabled = !impressionCheckbox.checked;

    // Toggle enable/disable when checkbox changes
    impressionCheckbox.addEventListener('change', function () {
        impressionText.disabled = !this.checked;
        if (!this.checked) {
            impressionText.value = ''; // optional: clear when unchecked
        }
    });
});
</script>

        <script src="js/script.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    </body>

</html>