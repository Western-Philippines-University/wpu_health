# Quick Start Guide - File Upload Feature

## 🚀 Getting Started (3 Easy Steps)

### Step 1: Setup Database
1. Make sure XAMPP is running (MySQL should be green)
2. Open your browser and go to: `http://localhost/wpu_medical-master/health/setup_file_upload.php`
3. Click the "🚀 Setup File Upload Feature" button
4. Wait for success message
5. Done! ✅

### Step 2: Access the Feature
1. Go to the Health system: `http://localhost/wpu_medical-master/health/HEALTH.php`
2. Login with your admin credentials
3. Click "Patient Records" in the sidebar
4. Click the "👁️" (View) button on any patient record
5. Scroll down to see the **"File Attachments"** section

### Step 3: Upload Your First File
1. Click "Choose File" button
2. Select an image, PDF, or document (under 5MB)
3. Click "📤 Upload File" button
4. Wait for "File uploaded successfully!" message
5. Your file now appears in the list below

## 📖 How to View Files (No Download!)

### View Images or PDFs
- Click the "👁️ View" button next to your file
- It opens in a new browser window
- View, zoom, or navigate through pages (for PDFs)
- Close the window when done

### View Text Files
- Click the "👁️ View" button
- Content displays in a popup modal
- Read the content directly
- Click "Close" to exit

### Download Other Files
- For DOC/DOCX files, click "👁️ View"
- Your browser will prompt to download
- Save to your computer to open

## 🗑️ Delete Files
1. Click the "🗑️" button next to the file
2. Confirm "OK" in the popup
3. File is permanently deleted
4. Page refreshes automatically

## ✅ Supported File Types
- **Images**: JPG, JPEG, PNG, GIF
- **Documents**: PDF, TXT, DOC, DOCX
- **Max Size**: 5 MB per file

## 💡 Tips & Tricks

### Best Practices
- Use descriptive filenames (e.g., "XRay_ChestPA_2024-11-04.jpg")
- Keep files under 2MB for faster upload
- Compress images before uploading
- Upload only relevant medical documents

### For Images
- Resize large images before upload
- Use JPG format for photos
- Use PNG for scans/documents with text

### For PDFs
- Combine multiple pages into one PDF
- Use PDF compression tools if file is too large
- Modern browsers display PDFs perfectly

## ❓ Common Issues

### "Please allow popups to view the file"
**Solution**: Click "Allow" when your browser asks about popups, or:
- Chrome: Click the popup icon in address bar
- Firefox: Click "Preferences" → "Allow"
- Edge: Click "Options" → "Always allow"

### "File too large. Maximum size: 5MB"
**Solution**: 
- Compress the image online (tinypng.com, compressjpeg.com)
- For PDFs, use a PDF compressor
- Split large documents into smaller parts

### "Invalid file type"
**Solution**: 
- Only upload supported formats (see list above)
- Convert files to supported formats first
- Ask admin if you need other file types

### Upload seems stuck
**Solution**:
- Check internet connection
- File might be too large
- Refresh page and try again
- Clear browser cache

## 🔒 Security Features
- ✅ Files stored in database (not public folder)
- ✅ Only logged-in users can view/upload
- ✅ File type validation
- ✅ File size limits
- ✅ Automatic virus protection (by file type restriction)

## 📍 Where is Everything?

### Main System
- Health System: `health/HEALTH.php`
- Setup Page: `health/setup_file_upload.php`

### Database
- Table Name: `patient_files`
- Database: `wpu_clinic`

### Documentation
- Full Guide: `FILE_UPLOAD_FEATURE.md`
- SQL Script: `database/patient_files_table.sql`

## 🆘 Need Help?

### Check Setup
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select `wpu_clinic` database
3. Look for `patient_files` table
4. If missing, run setup again

### Test Upload
1. Go to any patient record
2. Try uploading a small image (< 1MB)
3. Check browser console (F12) for errors
4. Check if file appears in list

### Verify Database
```sql
-- Run this in phpMyAdmin SQL tab
SELECT * FROM patient_files;
```
Should show uploaded files (if any).

## 🎯 Use Cases

### Medical Records
- Upload X-ray images
- Store lab results (PDF)
- Attach prescription scans
- Keep medical certificates

### Administrative
- Insurance documents
- Consent forms
- Medical history forms
- ID photocopies

### Clinical Notes
- Treatment photos
- Progress charts
- Referral letters
- Diagnostic reports

## 📊 What's Next?

After mastering file uploads, you can:
1. Upload multiple files per patient
2. Organize files by category
3. Download files for backup
4. Print patient records with attachments

## 🌟 Pro Tips

### Organize Files
- Name files with dates: `2024-11-04_xray.jpg`
- Use consistent naming: `Type_Description_Date`
- Delete old/unnecessary files regularly

### Performance
- Upload during off-peak hours for large files
- Use compressed formats
- Clean up old files monthly

### Backup
- Download important files periodically
- Keep original files in separate backup
- Test file restoration regularly

---

**Ready to start?** Go to the [Setup Page](http://localhost/wpu_medical-master/health/setup_file_upload.php)!

**Questions?** Check the [Full Documentation](FILE_UPLOAD_FEATURE.md)
