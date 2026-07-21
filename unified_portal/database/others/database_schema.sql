CREATE DATABASE IF NOT EXISTS wpu_medical;
USE wpu_medical;

CREATE TABLE IF NOT EXISTS medical_certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    civil_status ENUM('Single', 'Married', 'Divorced', 'Widowed') NOT NULL,
    address TEXT NOT NULL,
    examination_date DATE NOT NULL,
    reason TEXT NOT NULL,
    findings_fit BOOLEAN DEFAULT FALSE,
    findings_impression BOOLEAN DEFAULT FALSE,
    impression_text TEXT,
    advice TEXT,
    receipt_no VARCHAR(100),
    date_issued DATE,
    mc_no VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS referrals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_clinic VARCHAR(255) NOT NULL,
    referral_date DATE NOT NULL,
    patient_name VARCHAR(255) NOT NULL,
    patient_age INT NOT NULL,
    patient_sex ENUM('MALE', 'FEMALE') NOT NULL,
    patient_type_occupation BOOLEAN DEFAULT FALSE,
    patient_type_faculty BOOLEAN DEFAULT FALSE,
    patient_type_staff BOOLEAN DEFAULT FALSE,
    patient_type_student BOOLEAN DEFAULT FALSE,
    patient_address TEXT NOT NULL,
    case_summary TEXT NOT NULL,
    reason_for_referral TEXT NOT NULL,
    send_back_agency VARCHAR(255),
    return_date DATE,
    return_patient_name VARCHAR(255),
    return_patient_age INT,
    return_patient_sex ENUM('Male', 'Female'),
    services_findings TEXT,
    signature_name VARCHAR(255),
    designation VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
