<?php

namespace Database\Seeders;

use App\Models\MedicalCertificate;
use App\Models\PatientRecord;
use App\Models\Referral;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Seed five representative records for demo and testing.
     */
    public function run(): void
    {
        MedicalCertificate::query()->create([
            'name' => 'Juan Miguel Reyes',
            'age' => 21,
            'gender' => 'Male',
            'civil_status' => 'Single',
            'address' => 'Brgy. San Pedro, Puerto Princesa City, Palawan',
            'examination_date' => '2026-03-01',
            'reason' => 'Pre-employment medical examination for internship',
            'findings_fit' => true,
            'findings_impression' => false,
            'impression_text' => null,
            'advice' => 'Fit to work. Maintain adequate rest and hydration.',
            'receipt_no' => 'OR-2026-001',
            'date_issued' => '2026-03-01',
            'mc_no' => 'MC-2026-001',
        ]);

        MedicalCertificate::query()->create([
            'name' => 'Maria Clara Santos',
            'age' => 34,
            'gender' => 'Female',
            'civil_status' => 'Married',
            'address' => 'Brgy. Sta. Monica, Puerto Princesa City, Palawan',
            'examination_date' => '2026-03-05',
            'reason' => 'Annual physical examination',
            'findings_fit' => false,
            'findings_impression' => true,
            'impression_text' => 'Mild hypertension noted. Blood pressure 145/92 mmHg.',
            'advice' => 'Follow up with physician in 2 weeks. Reduce salt intake and monitor BP daily.',
            'receipt_no' => 'OR-2026-002',
            'date_issued' => '2026-03-05',
            'mc_no' => 'MC-2026-002',
        ]);

        Referral::query()->create([
            'hospital_clinic' => 'Ospital ng Palawan',
            'referral_date' => '2026-03-10',
            'patient_name' => 'Carlo Mendoza',
            'patient_age' => 19,
            'patient_sex' => 'MALE',
            'patient_type_student' => true,
            'patient_address' => 'Brgy. Tagumpay, Puerto Princesa City, Palawan',
            'case_summary' => 'Persistent cough and low-grade fever for 5 days. Chest clear on auscultation.',
            'reason_for_referral' => 'For chest X-ray and further pulmonary evaluation.',
            'signature_name' => 'Dr. Ana Patricia Cruz',
            'designation' => 'University Physician',
        ]);

        Referral::query()->create([
            'hospital_clinic' => 'Palawan Dental Center',
            'referral_date' => '2026-03-12',
            'patient_name' => 'Liza Mae Torres',
            'patient_age' => 22,
            'patient_sex' => 'FEMALE',
            'patient_type_student' => true,
            'patient_address' => 'Brgy. San Manuel, Puerto Princesa City, Palawan',
            'case_summary' => 'Severe tooth pain on lower right molar. Visible caries on tooth #46.',
            'reason_for_referral' => 'For possible extraction or root canal treatment.',
            'signature_name' => 'Dr. Ana Patricia Cruz',
            'designation' => 'University Physician',
        ]);

        PatientRecord::query()->create([
            'module_type' => 'health',
            'patient_type_id' => '1',
            'student_id' => '2022-CS-0142',
            'full_name' => 'Angela Rose Villanueva',
            'gender' => 'Female',
            'age' => 20,
            'marital_status' => 'Single',
            'religion' => 'Roman Catholic',
            'is_minor' => 'No',
            'phone_number' => '09171234567',
            'address' => 'Brgy. San Miguel, Puerto Princesa City, Palawan',
            'department_id' => '1',
            'visit_date' => '2026-03-08',
            'case_type_id' => '3',
            'diagnosis' => 'Acute upper respiratory tract infection',
            'treatment' => 'Paracetamol 500mg q6h PRN, increased fluid intake, rest for 3 days',
            'subjective' => 'Sore throat and runny nose for 2 days',
            'objectives' => 'Temp 37.8°C, pharyngeal erythema, no lung crackles',
            'diagnostics' => 'Clinical diagnosis',
            'assessment' => 'Mild URTI, likely viral',
            'plan' => 'Symptomatic treatment, return if fever persists beyond 3 days',
            'doctor' => 'Dr. Ana Patricia Cruz',
        ]);
    }
}
