# System Logs Directory

This directory contains system error and activity logs.

## Log Files

- `error.log` - PHP errors and warnings
- `access.log` - Page access logs (if enabled)
- `debug.log` - Debug information (development only)

## Security Notice

⚠️ **Important:**
- This folder should NOT be publicly accessible
- Contains sensitive system information
- Review logs regularly for security issues
- Rotate logs monthly to prevent large file sizes

## .htaccess Protection

An .htaccess file blocks web access to these logs.

## Log Rotation

Recommended schedule:
- **Daily:** Check for critical errors
- **Weekly:** Review unusual activity
- **Monthly:** Archive and compress old logs
- **Quarterly:** Delete archived logs older than 1 year

## Manual Log Rotation (Windows)

```powershell
# Rename current log with date
Move-Item error.log error_2025-11-04.log
# Create new empty log
New-Item error.log
```

## Manual Log Rotation (Linux)

```bash
# Rename and compress
mv error.log error_$(date +%Y-%m-%d).log
gzip error_*.log
# Create new log
touch error.log
chmod 644 error.log
```

---
**Last Updated:** November 4, 2025
