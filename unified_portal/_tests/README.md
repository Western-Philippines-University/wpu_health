# Test Files Information

This folder contains test and debugging files used during development.

⚠️ **These files are NOT for production use!**

## Files in this directory:

1. **comprehensive_test.php** - Full system integration test
2. **debug_medical_cert.php** - Certificate debugging script
3. **setup_database.php** - Database setup helper
4. **test_connection.php** - Database connection tester
5. **test_form_submission.php** - Form submission test
6. **test_medical_cert.php** - Medical certificate functionality test
7. **test_ports.php** - Server port availability test

## Important Notes:

- ❌ Do NOT run these files in production
- ❌ Do NOT expose this folder publicly
- ✅ Files are kept for development reference only
- ✅ Can be safely deleted if not needed

## Recommended Action:

For production deployment, either:
1. Delete this entire `_tests` folder, OR
2. Move it outside the web root, OR
3. Block access via .htaccess (see below)

## Blocking Access with .htaccess

Create `.htaccess` file in this folder with:

```apache
# Deny all access to test files
Order deny,allow
Deny from all
```

This prevents web access while keeping files for development.

---
**Last Updated:** November 4, 2025
