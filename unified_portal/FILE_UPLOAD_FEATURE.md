# File Upload and View Feature

## Overview
This feature allows you to upload files to patient records and view them directly in the browser without downloading.

## Features Added

### 1. File Upload
- Upload files directly to patient records
- Supported file types:
  - Images: JPG, JPEG, PNG, GIF
  - Documents: PDF, TXT, DOC, DOCX
- Maximum file size: 5MB
- Files are stored in the database for security

### 2. File Viewing (No Download Required)
- **Images**: Opens in a new window for viewing
- **PDF Files**: Opens in a new window with browser's PDF viewer
- **Text Files**: Displays content in a modal popup
- **Other Files**: Download option available

### 3. File Management
- View list of all uploaded files for each patient record
- See file name, type, size, and upload date
- Delete files when no longer needed

## How to Use

### Setup (First Time Only)
1. Open phpMyAdmin or MySQL console
2. Select your `wpu_clinic` database
3. Run the SQL script: `database/patient_files_table.sql`
4. This creates the `patient_files` table

### Uploading Files
1. Navigate to a patient record (click "View" on any patient)
2. Scroll to the "File Attachments" section
3. Click "Choose File" and select your file
4. Click "📤 Upload File"
5. Wait for confirmation message
6. File will appear in the list below

### Viewing Files
1. In the File Attachments section, find your file
2. Click the "👁️ View" button
3. **For Images/PDFs**: Opens in new window for viewing
4. **For Text Files**: Content displays in a popup modal
5. Close the window/modal when done

### Deleting Files
1. Click the "🗑️" button next to the file
2. Confirm deletion
3. File is permanently removed

## Technical Details

### Database Structure
```sql
patient_files table:
- id: Primary key
- patient_record_id: Links to patient_records table
- file_name: Original filename
- file_type: MIME type (e.g., image/jpeg)
- file_size: Size in bytes
- file_content: Binary file data (LONGBLOB)
- uploaded_by: Username who uploaded
- uploaded_at: Timestamp
```

### Security Features
- Files stored in database (not on filesystem)
- Only logged-in users can upload/view files
- File type validation
- File size limits (5MB max)
- SQL injection protection via prepared statements

### Browser Compatibility
- Works in all modern browsers (Chrome, Firefox, Edge, Safari)
- PDF viewing requires browser's built-in PDF viewer
- Image viewing works in all browsers

## Troubleshooting

### "Please allow popups to view the file"
- Your browser blocked the popup window
- Click "Allow" when prompted, or adjust browser settings

### "File too large. Maximum size: 5MB"
- Compress or resize your file before uploading
- For images, use image compression tools

### "Invalid file type"
- Only specific file types are allowed
- Check the supported formats list above

### Files not appearing after upload
- Make sure the database table was created properly
- Check browser console for JavaScript errors
- Refresh the page

## File Locations in Code

### PHP Handlers
- **File Upload**: Lines ~450-485 in HEALTH.php
- **File View**: Lines ~487-500 in HEALTH.php  
- **File Delete**: Lines ~502-520 in HEALTH.php

### JavaScript Functions
- **uploadFile()**: Handles file upload with FormData
- **viewFile()**: Opens files based on type
- **deleteFile()**: Removes files from database
- **showFileContentModal()**: Displays text content

### HTML Section
- Located in the "view" page section
- After "Clinical Notes" section
- Before "Action Buttons"

## Future Enhancements (Possible)
- Multiple file upload at once
- File download option
- File preview thumbnails
- Drag and drop upload
- Progress bar for large files
- File categorization/tagging
- Search files by name
- File version history

## Support
If you encounter issues:
1. Check browser console for errors (F12)
2. Verify database table exists
3. Check PHP error logs
4. Ensure file size is under 5MB
5. Verify file type is supported
