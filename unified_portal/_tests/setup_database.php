<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Setup</h2>";

try {
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    
    $conn = new mysqli($servername, $username, $password);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "<p style='color: green;'>✓ Connected to MySQL server</p>";
    
    $sql = "CREATE DATABASE IF NOT EXISTS wpu_medical";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>✓ Database 'wpu_medical' created or already exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Error creating database: " . $conn->error . "</p>";
    }
    
    $conn->select_db("wpu_medical");
    
    $sql = "CREATE TABLE IF NOT EXISTS medical_certificates (
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
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>✓ Table 'medical_certificates' created or already exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Error creating table medical_certificates: " . $conn->error . "</p>";
    }
    
    $sql = "CREATE TABLE IF NOT EXISTS referrals (
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
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>✓ Table 'referrals' created or already exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Error creating table referrals: " . $conn->error . "</p>";
    }
    
    echo "<p style='color: green;'>✓ Database setup completed!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception occurred: " . $e->getMessage() . "</p>";
}
?>