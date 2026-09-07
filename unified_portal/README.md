# WPU Medical System

**Version 2.0** - A comprehensive health services management platform for WPU

## 🏥 System Overview

The WPU Medical System is a complete health services management solution with three integrated modules:

1. **Certificate Services** - Medical certificates and referrals management
2. **Dental Services** - Dental records and treatment tracking  
3. **Health Services** - General health records and patient management

## ✨ Key Features

- ✅ **Medical Certificate Management** - Create, view, edit, and print medical certificates
- ✅ **Referral System** - Generate and track patient referrals
- ✅ **Search & Filter** - Quick search across all records
- ✅ **Auto-lock Security** - Automatic session locking for security
- ✅ **User Activity Logs** - Track all system activities
- ✅ **Print & Export** - Generate PDF certificates and reports
- ✅ **Responsive Design** - Works on desktop, tablet, and mobile
- ✅ **Multi-Module Support** - Separate dental and health modules

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/XAMPP web server
- Modern web browser

### Installation (5 Minutes)

1. **Extract files to web server**
   ```
   C:\xampp\htdocs\wpu_medical-master
   ```

2. **Create databases**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create three databases: `wpu`, `wpu_dental`, `wpu_clinic`

3. **Import SQL files**
   - Import `database/wpu.sql` into `wpu` database
   - Import `database/wpu_dental.sql` into `wpu_dental` database
   - Import `database/wpu_clinic.sql` into `wpu_clinic` database

4. **Access the system**
   ```
   http://localhost/wpu_medical-master/
   ```

5. **Login**
   - Username: `admin`
   - Password: `admin123`
   - ⚠️ Change password after first login!

📖 **For detailed setup instructions, see [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)**

## 📁 System Structure

```
wpu_medical-master/
├── index.php                 # Main landing page
├── admin/                    # Certificate Services Module
│   ├── admin.php            # Main admin panel
│   ├── components/          # Form processors
│   └── js/                  # JavaScript files
├── dental/                   # Dental Services Module
│   └── dental.php           # Dental admin panel
├── health/                   # Health Services Module
│   └── HEALTH.php           # Health admin panel
├── config/                   # Configuration files
│   ├── connect.php          # Legacy database config
│   └── database.php         # Unified database config (NEW)
├── includes/                 # Shared utilities
│   ├── functions.php        # Legacy functions
│   └── helpers.php          # Unified helpers (NEW)
├── database/                 # SQL schema files
├── assets/                   # CSS, images, resources
│   ├── css/
│   └── images/
└── components/               # Shared components
```

## 🎯 Module Access

### Certificate Services Admin
- **URL**: `http://localhost/wpu_medical-master/admin/admin.php`
- **Features**: Medical certificates, referrals, receipt management

### Dental Services
- **URL**: `http://localhost/wpu_medical-master/dental/dental.php`
- **Features**: Dental records, treatment tracking, reports

### Health Services  
- **URL**: `http://localhost/wpu_medical-master/health/HEALTH.php`
- **Features**: Health records, patient history, data export

## 📚 User Guide

### Creating a Medical Certificate
1. Click **"New Certificate"** button
2. Fill in patient information (name, age, gender, etc.)
3. Select examination date
4. Choose findings (Fit to Work / Impression)
5. Add advice and notes
6. Click **"Save"**
7. Use **"Print"** button to generate PDF

### Creating a Referral
1. Click **"New Referral"** button
2. Enter hospital/clinic name
3. Fill in patient details
4. Add case summary and reason for referral
5. Click **"Save"**
6. Print when needed

### Searching Records
- Use the search box to find patients by name
- Records are paginated (10 per page)
- Click action buttons: View 👁️ / Edit ✏️ / Delete 🗑️ / Print 🖨️

## 🔒 Security Features

- ✅ **Auto-lock** - Automatic session locking after inactivity
- ✅ **Password Hashing** - Secure password storage with bcrypt
- ✅ **CSRF Protection** - Form token validation
- ✅ **SQL Injection Prevention** - Prepared statements
- ✅ **Input Sanitization** - All inputs filtered
- ✅ **Session Management** - Secure session handling
- ✅ **Activity Logging** - Track all user actions

## ⚙️ Configuration

All system settings are in `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Change if you have MySQL password

// Database names
define('DB_MAIN', 'wpu');
define('DB_DENTAL', 'wpu_dental');
define('DB_HEALTH', 'wpu_clinic');
```

## 🐛 Troubleshooting

### Database Connection Error
```
✗ Check MySQL is running
✗ Verify credentials in config/database.php
✗ Ensure databases exist
```

### Can't Login
```
✗ Try default credentials: admin / admin123
✗ Reset password in database if needed
✗ Clear browser cookies
```

### White Screen
```
✗ Enable error display: ini_set('display_errors', 1);
✗ Check PHP error log
✗ Verify file permissions
```

📖 **See [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) for more solutions**

## 🔧 Maintenance

### Daily
- Monitor system performance
- Check error logs

### Weekly  
- Backup databases via phpMyAdmin
- Review user activity logs

### Monthly
- Archive old records
- Update system if needed

### Backup Commands
```sql
-- Export via phpMyAdmin or command line
mysqldump -u root wpu > backup_wpu.sql
mysqldump -u root wpu_dental > backup_dental.sql
mysqldump -u root wpu_clinic > backup_clinic.sql
```

## 📈 What's New in Version 2.0

✨ **November 4, 2025 Update**

- ✅ **Unified Configuration** - Single config file for all modules
- ✅ **Consolidated Helper Functions** - No more duplicate code
- ✅ **Cleaner File Structure** - Removed 13+ duplicate SQL files
- ✅ **Better Error Handling** - Improved user feedback
- ✅ **Enhanced Security** - Stronger validation and sanitization
- ✅ **Comprehensive Documentation** - Installation guide and cleanup log
- ✅ **Archived Test Files** - Removed development files from production
- ✅ **Improved UI/UX** - Better alerts and loading states
- ✅ **Code Refactoring** - 3000+ lines of duplicate code consolidated

## 🎨 Browser Support

| Browser | Version | Status |
|---------|---------|--------|
| Chrome  | 90+     | ✅ Fully Supported |
| Firefox | 88+     | ✅ Fully Supported |
| Edge    | 90+     | ✅ Fully Supported |
| Safari  | 14+     | ✅ Fully Supported |
| Opera   | 76+     | ✅ Fully Supported |

## 📱 Mobile Support

- ✅ iOS 14+ (iPhone, iPad)
- ✅ Android 10+
- ✅ Responsive design adapts to all screen sizes

## 📞 Support

For technical assistance:
1. Check [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)
2. Review [CLEANUP_LOG.md](CLEANUP_LOG.md)
3. Check system activity logs
4. Contact your IT administrator

## 📝 License

© 2025 WPU Health Services. For internal institutional use only.

---

**Quick Links:**
- 📖 [Installation Guide](INSTALLATION_GUIDE.md)
- 📝 [Cleanup Log](CLEANUP_LOG.md)
- 🏥 [Main System](http://localhost/wpu_medical-master/)
- 👨‍⚕️ [Certificate Admin](http://localhost/wpu_medical-master/admin/admin.php)
- 🦷 [Dental Module](http://localhost/wpu_medical-master/dental/dental.php)
- 🏥 [Health Module](http://localhost/wpu_medical-master/health/HEALTH.php)
