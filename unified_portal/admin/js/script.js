function showAlert(type, title, message, callback) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: title,
            text: message,
            confirmButtonText: type === 'success' ? 'Continue' : 'OK'
        }).then(callback);
    } else {
        alert(`${title}: ${message}`);
        if (callback) callback();
    }
}

const medicalCertBtn = document.getElementById('medicalCertBtn');
const referralBtn = document.getElementById('referralBtn');
const medicalCertModal = document.getElementById('medicalCertModal');
const referralModal = document.getElementById('referralModal');
const certificateModal = document.getElementById('certificateModal');
const referralDisplayModal = document.getElementById('referralDisplayModal');

const closeButtons = document.querySelectorAll('.close');
const certificateClose = document.querySelector('.certificate-close');
const referralClose = document.querySelector('.referral-close');

const certificateForm = document.getElementById('certificateForm');
const referralForm = document.getElementById('referralForm');

const printBtn = document.getElementById('printBtn');
const downloadBtn = document.getElementById('downloadBtn');
const newCertBtn = document.getElementById('newCertBtn');
const printRefBtn = document.getElementById('printRefBtn');
const downloadRefBtn = document.getElementById('downloadRefBtn');
const newRefBtn = document.getElementById('newRefBtn');

const impressionCheckbox = document.getElementById('impression');
const impressionText = document.getElementById('impression_text');
const receiptNo = document.getElementById('receipt_no');
const dateIssued = document.getElementById('date_issued');

document.addEventListener('DOMContentLoaded', function() {
    generateReceiptNumber();
    setCurrentDate();
    
    setupFormSubmissions();
    
    setupModalInteractions();
    
    setupActionButtons();
    
    setupImpressionLogic();
});

function generateReceiptNumber() {
    const timestamp = Date.now();
    const random = Math.floor(Math.random() * 1000);
    receiptNo.value = `R${timestamp}${random}`;
}

function setCurrentDate() {
    const today = new Date().toISOString().split('T')[0];
    dateIssued.value = today;
}

function setupFormSubmissions() {
    certificateForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitMedicalCertificate();
    });
    
    referralForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitReferral();
    });
}

function submitMedicalCertificate() {
    const formData = new FormData(certificateForm);
    
    // Simple relative path - adjust based on your actual file structure
    const url = 'components/process_medical_certificate.php';
    
    console.log('Submitting to:', url); // Debug log
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug log
        console.log('Response headers:', response.headers.get('content-type')); // Debug log
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get the response as text first to see what we're actually receiving
        return response.text().then(text => {
            console.log('Response text:', text); // Debug log
            
            // Try to parse as JSON
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                console.error('Raw response:', text);
                throw new Error('Server returned invalid JSON. Response: ' + text.substring(0, 200));
            }
        });
    })
    .then(data => {
        console.log('Parsed data:', data); // Debug log
        
        if (data.status === 'success') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    confirmButtonText: 'Continue'
                }).then(() => {
                    displayMedicalCertificate();
                });
            } else {
                alert('Success: ' + data.message);
                displayMedicalCertificate();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
            } else {
                alert('Error: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'An error occurred while submitting the form.',
                confirmButtonText: 'OK'
            });
        } else {
            alert('Error: ' + (error.message || 'An error occurred while submitting the form.'));
        }
    });
}

function submitReferral() {
    const formData = new FormData(referralForm);
    
    // Simple relative path - adjust based on your actual file structure
    const url = 'components/process_referral.php';
    
    console.log('Submitting to:', url); // Debug log
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug log
        console.log('Response headers:', response.headers.get('content-type')); // Debug log
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get the response as text first to see what we're actually receiving
        return response.text().then(text => {
            console.log('Response text:', text); // Debug log
            
            // Try to parse as JSON
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                console.error('Raw response:', text);
                throw new Error('Server returned invalid JSON. Response: ' + text.substring(0, 200));
            }
        });
    })
    .then(data => {
        console.log('Parsed data:', data); // Debug log
        
        if (data.status === 'success') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    confirmButtonText: 'Continue'
                }).then(() => {
                    displayReferral();
                });
            } else {
                alert('Success: ' + data.message);
                displayReferral();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
            } else {
                alert('Error: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'An error occurred while submitting the form.',
                confirmButtonText: 'OK'
            });
        } else {
            alert('Error: ' + (error.message || 'An error occurred while submitting the form.'));
        }
    });
}

function setupModalInteractions() {
    medicalCertBtn.addEventListener('click', () => {
        medicalCertModal.style.display = 'block';
    });
    
    referralBtn.addEventListener('click', () => {
        referralModal.style.display = 'block';
    });
    
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            medicalCertModal.style.display = 'none';
            referralModal.style.display = 'none';
        });
    });
    
    certificateClose.addEventListener('click', () => {
        certificateModal.style.display = 'none';
    });
    
    referralClose.addEventListener('click', () => {
        referralDisplayModal.style.display = 'none';
    });
    
    window.addEventListener('click', (e) => {
        // Close any modal when clicking outside its content
        document.querySelectorAll('.modal').forEach(modal => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
}

function setupActionButtons() {
    if (printBtn) printBtn.addEventListener('click', () => printCertificate());
    if (downloadBtn) downloadBtn.addEventListener('click', () => downloadCertificate());
    if (newCertBtn) newCertBtn.addEventListener('click', () => createNewCertificate());
    
    if (printRefBtn) printRefBtn.addEventListener('click', () => printReferral());
    if (downloadRefBtn) downloadRefBtn.addEventListener('click', () => downloadReferral());
    if (newRefBtn) newRefBtn.addEventListener('click', () => createNewReferral());
}

function setupImpressionLogic() {
    if (impressionCheckbox && impressionText) {
        impressionCheckbox.addEventListener('change', function() {
            impressionText.disabled = !this.checked;
            if (!this.checked) {
                impressionText.value = '';
            }
        });
    }
}

function displayMedicalCertificate() {
    const name = document.getElementById('name').value;
    const age = document.getElementById('age').value;
    const gender = document.getElementById('gender').value;
    const civilStatus = document.getElementById('civil_status').value;
    const address = document.getElementById('address').value;
    const date = document.getElementById('date').value;
    const reason = document.getElementById('reason').value;
    const fit = document.getElementById('fit').checked;
    const impression = document.getElementById('impression').checked;
    const impressionTextValue = document.getElementById('impression_text').value;
    const advice = document.getElementById('advice').value;
    const receiptNoValue = document.getElementById('receipt_no').value;
    const dateIssuedValue = document.getElementById('date_issued').value;
    const mcNo = document.getElementById('mc_no').value;
    
    document.getElementById('cert-name').textContent = name;
    document.getElementById('cert-age').textContent = age;
    document.getElementById('cert-gender').textContent = gender;
    document.getElementById('cert-civil-status').textContent = civilStatus;
    document.getElementById('cert-address').textContent = address;
    document.getElementById('cert-date').textContent = date;
    document.getElementById('cert-reason').textContent = reason;
    document.getElementById('cert-advice').textContent = advice;
    document.getElementById('cert-receipt-no').textContent = receiptNoValue;
    document.getElementById('cert-date-issued').textContent = dateIssuedValue;
    document.getElementById('cert-mc-no').textContent = mcNo;
    
    if (fit) {
        document.getElementById('cert-fit').innerHTML = '<span class="checkbox checked"></span> physically and mentally fit.';
    } else {
        document.getElementById('cert-fit').innerHTML = '<span class="checkbox"></span> physically and mentally fit.';
    }
    
    if (impression) {
        document.getElementById('cert-impression').innerHTML = '<span class="checkbox checked"></span> with the impression of <span class="underline">' + impressionTextValue + '</span>.';
    } else {
        document.getElementById('cert-impression').innerHTML = '<span class="checkbox"></span> with the impression of <span class="underline"></span>.';
    }
    
    medicalCertModal.style.display = 'none';
    certificateModal.style.display = 'block';
}

function displayReferral() {
    const hospital = document.getElementById('referral-hospital').value;
    const refDate = document.getElementById('referral-date').value;
    const refName = document.getElementById('referral-name').value;
    const refAge = document.getElementById('referral-age').value;
    const refSex = document.querySelector('input[name="referral-sex"]:checked')?.value;
    const refAddress = document.getElementById('referral-address').value;
    const refCase = document.getElementById('referral-case').value;
    const refReason = document.getElementById('referral-reason').value;
    const refSendBack = document.getElementById('referral-send-back').value;
    const refSendBackDate = document.getElementById('referral-send-back-date').value;
    const refSendBackName = document.getElementById('referral-send-back-name').value;
    const refSendBackAge = document.getElementById('referral-send-back-age').value;
    const refSendBackSex = document.getElementById('referral-send-back-sex').value;
    const refServices = document.getElementById('referral-services').value;
    const refSignature = document.getElementById('referral-signature').value;
    const refDesignation = document.getElementById('referral-designation').value;
    
    document.getElementById('ref-hospital').textContent = hospital;
    document.getElementById('ref-date').textContent = refDate;
    document.getElementById('ref-name').textContent = refName;
    document.getElementById('ref-age').textContent = refAge;
    document.getElementById('ref-address').textContent = refAddress;
    document.getElementById('ref-case').textContent = refCase;
    document.getElementById('ref-reason').textContent = refReason;
    document.getElementById('ref-send-back').textContent = refSendBack;
    document.getElementById('ref-send-back-date').textContent = refSendBackDate;
    document.getElementById('ref-send-back-name').textContent = refSendBackName;
    document.getElementById('ref-send-back-age').textContent = refSendBackAge;
    document.getElementById('ref-send-back-sex').textContent = refSendBackSex;
    document.getElementById('ref-services').textContent = refServices;
    document.getElementById('ref-signature').textContent = refSignature;
    document.getElementById('ref-designation').textContent = refDesignation;
    
    if (refSex === 'MALE') {
        document.getElementById('ref-sex-male').innerHTML = 'MALE (✓)';
        document.getElementById('ref-sex-female').innerHTML = 'FEMALE ( )';
    } else if (refSex === 'FEMALE') {
        document.getElementById('ref-sex-male').innerHTML = 'MALE ( )';
        document.getElementById('ref-sex-female').innerHTML = 'FEMALE (✓)';
    }
    
    // Reset all patient type checkboxes to unchecked first
    document.getElementById('ref-occupation').innerHTML = 'OCCUPATION ( )';
    document.getElementById('ref-faculty').innerHTML = 'FACULTY ( )';
    document.getElementById('ref-staff').innerHTML = 'STAFF ( )';
    document.getElementById('ref-student').innerHTML = 'STUDENT ( )';
    
    // Then mark the checked ones
    const patientTypes = document.querySelectorAll('input[name="referral-type"]:checked');
    patientTypes.forEach(type => {
        if (type.value === 'OCCUPATION') {
            document.getElementById('ref-occupation').innerHTML = 'OCCUPATION (✓)';
        }
        if (type.value === 'FACULTY') {
            document.getElementById('ref-faculty').innerHTML = 'FACULTY (✓)';
        }
        if (type.value === 'STAFF') {
            document.getElementById('ref-staff').innerHTML = 'STAFF (✓)';
        }
        if (type.value === 'STUDENT') {
            document.getElementById('ref-student').innerHTML = 'STUDENT (✓)';
        }
    });
    
    referralModal.style.display = 'none';
    referralDisplayModal.style.display = 'block';
}

function printCertificate() {
    window.print();
}

function downloadCertificate() {
    const element = document.getElementById('certificateContent');
    const opt = {
        margin: 1,
        filename: 'medical_certificate.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    
    html2pdf().set(opt).from(element).save();
}

function createNewCertificate() {
    certificateForm.reset();
    generateReceiptNumber();
    setCurrentDate();
    certificateModal.style.display = 'none';
    medicalCertModal.style.display = 'block';
}

function printReferral() {
    window.print();
}

function downloadReferral() {
    const element = document.getElementById('referralContent');
    const opt = {
        margin: 1,
        filename: 'referral_form.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    
    html2pdf().set(opt).from(element).save();
}

function createNewReferral() {
    referralForm.reset();
    referralDisplayModal.style.display = 'none';
    referralModal.style.display = 'block';
}