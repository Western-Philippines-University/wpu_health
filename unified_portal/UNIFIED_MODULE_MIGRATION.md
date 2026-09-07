# Unified Module Migration Guide

## Overview
The WPU Medical System has been consolidated from 3 separate modules (Certificate Services, Dental Services, Health Services) into 1 unified module with a single unified database.

## Changes Made

### 1. Database Consolidation
- **Before**: 3 separate databases (`wpu`, `wpu_dental`, `wpu_clinic`)
- **After**: 1 unified database (`wpu`)

### 2. Database Schema Updates
- Created unified schema file: `database/wpu_unified.sql`
- Added `module_type` field to `patient_records` table to distinguish between:
  - `dental` - Dental records
  - `health` - Health/clinic records  
  - `certificate` - Certificate-related records (if needed)

### 3. Configuration Updates
- Updated `config/database.php` to use single database
- Updated `config/connect.php` to use unified database
- All modules now use the same database connection

### 4. Code Updates
- Updated `admin/admin.php` to use unified database
- Updated `dental/dental.php` and all dental module files
- Updated `health/HEALTH.php` and all health module files
- Updated `index.php` to point to unified admin panel

## Migration Steps

### Step 1: Backup Existing Databases
```sql
-- Backup all three databases before migration
mysqldump -u root wpu > backup_wpu.sql
mysqldump -u root wpu_dental > backup_wpu_dental.sql
mysqldump -u root wpu_clinic > backup_wpu_clinic.sql
```

### Step 2: Import Unified Schema
1. Open phpMyAdmin or MySQL command line
2. Create/select the `wpu` database
3. Import `database/wpu_unified.sql`

### Step 3: Migrate Existing Data
You need to migrate existing data from `wpu_dental` and `wpu_clinic` databases:

```sql
-- Migrate dental patient records
INSERT INTO wpu.patient_records (module_type, patient_type_id, student_id, full_name, gender, age, marital_status, religion, is_minor, guardian_name, phone_number, address, department_id, visit_date, case_type_id, diagnosis, treatment, subjective, objectives, diagnostics, assessment, plan, doctor, created_at)
SELECT 'dental', patient_type_id, student_id, full_name, gender, age, marital_status, religion, is_minor, guardian_name, phone_number, address, department_id, visit_date, case_type_id, diagnosis, treatment, subjective, objectives, diagnostics, assessment, plan, doctor, created_at
FROM wpu_dental.patient_records;

-- Migrate health patient records
INSERT INTO wpu.patient_records (module_type, patient_type_id, student_id, full_name, gender, age, marital_status, religion, is_minor, guardian_name, phone_number, address, department_id, visit_date, case_type_id, diagnosis, treatment, subjective, objectives, diagnostics, assessment, plan, doctor, created_at)
SELECT 'health', patient_type_id, student_id, full_name, gender, age, marital_status, religion, is_minor, guardian_name, phone_number, address, department_id, visit_date, case_type_id, diagnosis, treatment, subjective, objectives, diagnostics, assessment, plan, doctor, created_at
FROM wpu_clinic.patient_records;

-- Merge admins (deduplicate by username)
INSERT INTO wpu.admins (username, password, created_at)
SELECT DISTINCT username, password, created_at
FROM wpu_dental.admins
WHERE username NOT IN (SELECT username FROM wpu.admins);

INSERT INTO wpu.admins (username, password, created_at)
SELECT DISTINCT username, password, created_at
FROM wpu_clinic.admins
WHERE username NOT IN (SELECT username FROM wpu.admins);

-- Merge case_types (deduplicate by case_name)
INSERT INTO wpu.case_types (case_name, created_at)
SELECT DISTINCT case_name, created_at
FROM wpu_dental.case_types
WHERE case_name NOT IN (SELECT case_name FROM wpu.case_types);

-- Merge departments (deduplicate by name)
INSERT INTO wpu.departments (name, created_at)
SELECT DISTINCT name, created_at
FROM wpu_dental.departments
WHERE name NOT IN (SELECT name FROM wpu.departments);

INSERT INTO wpu.departments (name, created_at)
SELECT DISTINCT name, created_at
FROM wpu_clinic.departments
WHERE name NOT IN (SELECT name FROM wpu.departments);

-- Merge patient_types (deduplicate by type_name)
INSERT INTO wpu.patient_types (type_name, color_code, created_at)
SELECT DISTINCT type_name, color_code, created_at
FROM wpu_dental.patient_types
WHERE type_name NOT IN (SELECT type_name FROM wpu.patient_types);

INSERT INTO wpu.patient_types (type_name, color_code, created_at)
SELECT DISTINCT type_name, color_code, created_at
FROM wpu_clinic.patient_types
WHERE type_name NOT IN (SELECT type_name FROM wpu.patient_types);

-- Merge user_logs
INSERT INTO wpu.user_logs (username, activity_type, login_time, logout_time, duration, status, created_at)
SELECT username, activity_type, login_time, logout_time, duration, status, created_at
FROM wpu_dental.user_logs;

INSERT INTO wpu.user_logs (username, activity_type, login_time, logout_time, duration, status, created_at)
SELECT username, activity_type, login_time, logout_time, duration, status, created_at
FROM wpu_clinic.user_logs;

-- Merge system_settings (deduplicate by setting_key)
INSERT INTO wpu.system_settings (setting_key, setting_value, created_at)
SELECT DISTINCT setting_key, setting_value, created_at
FROM wpu_dental.system_settings
WHERE setting_key NOT IN (SELECT setting_key FROM wpu.system_settings);

INSERT INTO wpu.system_settings (setting_key, setting_value, created_at)
SELECT DISTINCT setting_key, setting_value, created_at
FROM wpu_clinic.system_settings
WHERE setting_key NOT IN (SELECT setting_key FROM wpu.system_settings);
```

### Step 4: Update Code Queries
All queries in `dental/` and `health/` modules that access `patient_records` should filter by `module_type`:

**For Dental Module:**
```php
// Add WHERE clause
WHERE module_type = 'dental'
```

**For Health Module:**
```php
// Add WHERE clause
WHERE module_type = 'health'
```

**Example:**
```php
// Before
$stmt = $pdo->prepare("SELECT * FROM patient_records WHERE ...");

// After
$stmt = $pdo->prepare("SELECT * FROM patient_records WHERE module_type = 'dental' AND ...");
```

### Step 5: Update Insert Queries
When inserting new records, always specify `module_type`:

**Dental Module:**
```php
INSERT INTO patient_records (module_type, ...) VALUES ('dental', ...)
```

**Health Module:**
```php
INSERT INTO patient_records (module_type, ...) VALUES ('health', ...)
```

## Important Notes

1. **Backup First**: Always backup your databases before migration
2. **Test Environment**: Test the migration in a development environment first
3. **Data Integrity**: Verify all data migrated correctly
4. **Query Updates**: Update all queries to filter by `module_type` where appropriate
5. **Old Databases**: You can keep `wpu_dental` and `wpu_clinic` databases as backups, but they are no longer used

## Verification Checklist

- [ ] All three databases backed up
- [ ] Unified schema imported successfully
- [ ] All patient records migrated with correct `module_type`
- [ ] All admins merged (no duplicates)
- [ ] All case_types merged (no duplicates)
- [ ] All departments merged (no duplicates)
- [ ] All patient_types merged (no duplicates)
- [ ] All user_logs merged
- [ ] All system_settings merged
- [ ] Dental module queries updated to filter by `module_type = 'dental'`
- [ ] Health module queries updated to filter by `module_type = 'health'`
- [ ] All insert queries include `module_type` field
- [ ] System tested and working correctly

## Rollback Plan

If something goes wrong, you can restore from backups:
```sql
-- Restore original databases
mysql -u root wpu < backup_wpu.sql
mysql -u root wpu_dental < backup_wpu_dental.sql
mysql -u root wpu_clinic < backup_wpu_clinic.sql
```

## Support

For issues or questions, refer to the main README.md or contact your system administrator.

