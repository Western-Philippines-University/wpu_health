# Unified System - Complete Integration Guide

## ✅ What Has Been Done

### 1. Database Consolidation
- ✅ Created unified database schema (`database/wpu_unified.sql`)
- ✅ Updated all database references to use single `wpu` database
- ✅ Added `module_type` field to `patient_records` table

### 2. Navigation & UI Updates
- ✅ Updated sidebar navigation to include all modules:
  - Dashboard
  - Medical Certificates
  - Referrals
  - Dental Records
  - Health Records
  - Reports
  - System Settings
  - Admin Management
  - User Logs
  - Backup

### 3. Dashboard Updates
- ✅ Updated dashboard to show stats for all 4 modules:
  - Medical Certificates count
  - Referrals count
  - Dental Records count
  - Health Records count
- ✅ Added quick action buttons for all modules

### 4. Database Queries
- ✅ Added queries for dental records (filtered by `module_type = 'dental'`)
- ✅ Added queries for health records (filtered by `module_type = 'health'`)
- ✅ Added queries for patient types, departments, and case types

## 🔧 What Still Needs to Be Done

### 1. Complete Dental Records Page Integration

**Location**: `admin/admin.php` - `case 'dental_records':`

**What to do**:
1. Copy the patient records display code from `dental/dental.php` (around line 2175-2500)
2. Update queries to filter by `module_type = 'dental'`
3. Update form actions to use unified paths
4. Include add/edit/delete functionality

**Key Code to Copy**:
- Patient records table display
- Add record modal/form
- Edit record functionality
- Delete confirmation
- Search/filter functionality
- Pagination

### 2. Complete Health Records Page Integration

**Location**: `admin/admin.php` - `case 'health_records':`

**What to do**:
1. Copy the patient records display code from `health/HEALTH.php` (around line 2175-2500)
2. Update queries to filter by `module_type = 'health'`
3. Update form actions to use unified paths
4. Include add/edit/delete functionality

**Key Code to Copy**:
- Patient records table display
- Add record modal/form
- Edit record functionality
- Delete confirmation
- Search/filter functionality
- Pagination

### 3. Update Form Processors

**Files to Update**:
- `dental/edit_record.php` - Ensure it uses unified database and sets `module_type = 'dental'`
- `health/edit_record.php` - Ensure it uses unified database and sets `module_type = 'health'`
- Create unified form processors in `admin/components/`:
  - `process_dental_record.php`
  - `process_health_record.php`

**Key Changes**:
```php
// When inserting dental records
INSERT INTO patient_records (module_type, ...) VALUES ('dental', ...)

// When inserting health records
INSERT INTO patient_records (module_type, ...) VALUES ('health', ...)
```

### 4. Update JavaScript Functions

**What to add**:
- Functions for dental record modals (add/edit)
- Functions for health record modals (add/edit)
- Search functions for dental/health records
- Export functions for reports

**Location**: Add to the `<script>` section at the bottom of `admin/admin.php`

### 5. Update Print/Report Files

**Files to Update**:
- `dental/print_*.php` - Update to filter by `module_type = 'dental'`
- `health/print_*.php` - Update to filter by `module_type = 'health'`

**Key Change**:
```php
// Add WHERE clause
WHERE module_type = 'dental' AND ...
// or
WHERE module_type = 'health' AND ...
```

## 📋 Step-by-Step Integration

### Step 1: Complete Dental Records Page

1. Open `dental/dental.php`
2. Find the `case 'records':` section (around line 2175)
3. Copy the entire HTML/PHP code for patient records display
4. Paste into `admin/admin.php` in the `case 'dental_records':` section
5. Update all queries to include `WHERE module_type = 'dental'`
6. Update form action URLs to point to unified components
7. Update JavaScript function names to avoid conflicts (prefix with `dental_`)

### Step 2: Complete Health Records Page

1. Open `health/HEALTH.php`
2. Find the `case 'records':` section (around line 2175)
3. Copy the entire HTML/PHP code for patient records display
4. Paste into `admin/admin.php` in the `case 'health_records':` section
5. Update all queries to include `WHERE module_type = 'health'`
6. Update form action URLs to point to unified components
7. Update JavaScript function names to avoid conflicts (prefix with `health_`)

### Step 3: Create Unified Form Processors

Create `admin/components/process_dental_record.php`:
```php
<?php
require_once '../../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = getDBConnection();
    
    // Get form data
    $module_type = 'dental'; // Always set for dental
    $full_name = $_POST['full_name'] ?? '';
    // ... get all other fields
    
    // Insert with module_type
    $stmt = $pdo->prepare("INSERT INTO patient_records (module_type, full_name, ...) VALUES (?, ?, ...)");
    $stmt->execute([$module_type, $full_name, ...]);
    
    echo json_encode(['success' => true]);
}
?>
```

Create `admin/components/process_health_record.php` (same structure, but `module_type = 'health'`)

### Step 4: Update Edit Files

Update `dental/edit_record.php`:
- Change database to `wpu`
- Ensure `module_type = 'dental'` is maintained on updates

Update `health/edit_record.php`:
- Change database to `wpu`
- Ensure `module_type = 'health'` is maintained on updates

### Step 5: Update Print Files

For each print file in `dental/` and `health/`:
- Add `WHERE module_type = 'dental'` or `WHERE module_type = 'health'` to queries
- Update database connection to use unified database

## 🎯 Testing Checklist

- [ ] Dashboard shows correct counts for all modules
- [ ] Dental Records page displays records correctly
- [ ] Health Records page displays records correctly
- [ ] Can add new dental record
- [ ] Can add new health record
- [ ] Can edit dental record
- [ ] Can edit health record
- [ ] Can delete dental record
- [ ] Can delete health record
- [ ] Search works for dental records
- [ ] Search works for health records
- [ ] Reports filter correctly by module_type
- [ ] Print functions work correctly
- [ ] All navigation links work

## 📝 Notes

- The unified system uses a single `admin/admin.php` file
- All modules are accessible from one navigation menu
- Database is unified - all data in one `wpu` database
- Module distinction is made via `module_type` field in `patient_records` table
- Old `dental/dental.php` and `health/HEALTH.php` files can be kept as reference but are no longer the main entry points

## 🚀 Quick Start

1. Import `database/wpu_unified.sql` into your `wpu` database
2. Migrate existing data (see `UNIFIED_MODULE_MIGRATION.md`)
3. Complete the integration steps above
4. Test all functionality
5. Update `index.php` to point only to `admin/admin.php`

The system is now unified - one database, one admin interface, all modules accessible from one place!

