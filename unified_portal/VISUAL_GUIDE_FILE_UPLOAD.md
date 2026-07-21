# Visual Guide - File Upload & View Feature

## 📸 Step-by-Step Screenshots Guide

### Part 1: Initial Setup

#### Step 1.1: Open Setup Page
```
URL: http://localhost/wpu_medical-master/health/setup_file_upload.php
```
**What you'll see:**
- Big blue "Setup File Upload Feature" button
- Step-by-step instructions
- Features list

#### Step 1.2: Click Setup Button
**Action:** Click the blue button
**Result:** Green success message appears:
```
✅ Success! The 'patient_files' table has been created.
```

---

### Part 2: Accessing Patient Records

#### Step 2.1: Login to Health System
```
URL: http://localhost/wpu_medical-master/health/HEALTH.php
```
**Enter:**
- Username: admin
- Password: (your password)

#### Step 2.2: Navigate to Patient Records
**Location:** Left sidebar
**Click:** "📋 Patient Records"
**You'll see:** List of all patients

#### Step 2.3: Open a Patient Record
**Action:** Click the "👁️" (eye) button on any patient
**Result:** Patient details page opens

---

### Part 3: File Upload Section

#### Step 3.1: Locate File Attachments
**Where:** Scroll down the patient record page
**Section Title:** "File Attachments"
**Position:** After "Clinical Notes", before "Action Buttons"

#### Step 3.2: Upload Form Layout
```
┌─────────────────────────────────────────────┐
│  File Attachments                           │
├─────────────────────────────────────────────┤
│  Upload File (Images, PDF, Text)            │
│  [Choose File] [No file chosen]             │
│  [📤 Upload File]                           │
└─────────────────────────────────────────────┘
```

---

### Part 4: Uploading Your First File

#### Step 4.1: Select a File
**Action:** Click "Choose File" button
**Result:** File browser opens
**Select:** Any supported file (JPG, PNG, PDF, TXT, etc.)
**Limit:** Under 5 MB

#### Step 4.2: Confirm Selection
**You'll see:** File name appears next to "Choose File"
**Example:** `[Choose File] chest_xray.jpg`

#### Step 4.3: Upload the File
**Action:** Click "📤 Upload File" button
**Loading:** Blue popup appears: "Uploading file..."
**Success:** Green popup appears: "File uploaded successfully!"
**Auto-refresh:** Page reloads automatically after 1.5 seconds

---

### Part 5: File List Display

#### Step 5.1: Uploaded Files Table
**After upload, you'll see:**
```
┌──────────────┬────────┬──────────┬─────────────┬───────────┐
│ File Name    │ Type   │ Size     │ Uploaded    │ Actions   │
├──────────────┼────────┼──────────┼─────────────┼───────────┤
│ xray.jpg     │ image/ │ 234.56KB │ Nov 04 2:30 │ 👁️ 🗑️    │
│              │ jpeg   │          │             │           │
└──────────────┴────────┴──────────┴─────────────┴───────────┘
```

#### Step 5.2: Understanding the Table
- **File Name:** Original filename
- **Type:** MIME type (image/jpeg, application/pdf, etc.)
- **Size:** File size in KB or MB
- **Uploaded:** Date and time of upload
- **Actions:** View (👁️) and Delete (🗑️) buttons

---

### Part 6: Viewing Files

#### Step 6.1: View an Image File
**Action:** Click "👁️" button on image file
**Result:** New browser window opens
**Display:** Image shown at full size
**Controls:** 
- Zoom in/out
- Download option (browser controls)
- Close window when done

#### Step 6.2: View a PDF File
**Action:** Click "👁️" button on PDF file
**Result:** New window with PDF viewer
**Display:** PDF with browser's built-in viewer
**Controls:**
- Navigate pages (← →)
- Zoom in/out
- Search text
- Download/Print
- Rotate pages

#### Step 6.3: View a Text File
**Action:** Click "👁️" button on TXT file
**Result:** Modal popup appears on same page
**Display:**
```
┌─────────────────────────────────────────┐
│  📄 medical_notes.txt              ✕   │
├─────────────────────────────────────────┤
│  ┌───────────────────────────────────┐ │
│  │ Patient complained of headache    │ │
│  │ Temperature: 37.5°C               │ │
│  │ Blood pressure: 120/80            │ │
│  │ Prescribed: Paracetamol 500mg     │ │
│  └───────────────────────────────────┘ │
│                                         │
│                          [Close]        │
└─────────────────────────────────────────┘
```
**Action:** Click "Close" to exit
**Result:** Modal disappears, back to patient record

#### Step 6.4: View Other Files (DOC, DOCX)
**Action:** Click "👁️" button on DOC/DOCX
**Result:** File downloads to your computer
**Message:** Blue popup: "This file type cannot be previewed. Download to view."
**Next:** Open with Microsoft Word or similar

---

### Part 7: Deleting Files

#### Step 7.1: Delete a File
**Action:** Click "🗑️" button next to file
**Confirmation:** Browser popup appears:
```
┌─────────────────────────────────────┐
│  Are you sure you want to delete   │
│  this file?                         │
│                                     │
│     [Cancel]     [OK]               │
└─────────────────────────────────────┘
```

#### Step 7.2: Confirm Deletion
**Action:** Click "OK"
**Loading:** Blue popup: "Deleting file..."
**Success:** Green popup: "File deleted successfully!"
**Auto-refresh:** Page reloads, file removed from list

#### Step 7.3: Cancel Deletion
**Action:** Click "Cancel" in confirmation
**Result:** Nothing happens, file remains

---

### Part 8: Multiple Files

#### Step 8.1: Upload Multiple Files
**Process:** Repeat upload steps for each file
**Result:** All files appear in the table
**Order:** Newest files appear at the top

#### Step 8.2: Managing Multiple Files
**Example table:**
```
┌─────────────────────┬──────────────┬──────┬──────────────┬────────┐
│ File Name           │ Type         │ Size │ Uploaded     │ Action │
├─────────────────────┼──────────────┼──────┼──────────────┼────────┤
│ prescription.pdf    │ application/ │ 45KB │ Nov 04 15:30 │ 👁️ 🗑️ │
│ xray_chest.jpg      │ image/jpeg   │ 234KB│ Nov 04 14:20 │ 👁️ 🗑️ │
│ lab_results.pdf     │ application/ │ 67KB │ Nov 04 13:10 │ 👁️ 🗑️ │
│ medical_history.txt │ text/plain   │ 2KB  │ Nov 04 12:00 │ 👁️ 🗑️ │
└─────────────────────┴──────────────┴──────┴──────────────┴────────┘
```

---

### Part 9: Common Scenarios

#### Scenario A: Viewing X-Ray Image
1. Click "👁️" on x-ray file
2. New window opens with image
3. Zoom in to see details
4. Right-click → "Save image as..." to download
5. Close window

#### Scenario B: Reading Lab Results (PDF)
1. Click "👁️" on PDF file
2. Browser PDF viewer opens
3. Read through pages using arrow keys
4. Use Ctrl+F to search for specific values
5. Print if needed (Ctrl+P)
6. Close window

#### Scenario C: Checking Notes (TXT)
1. Click "👁️" on text file
2. Modal popup shows content
3. Read the notes directly
4. Click "Close" button
5. Back to patient record instantly

#### Scenario D: Deleting Old Files
1. Review file list
2. Identify old/unnecessary files
3. Click "🗑️" on each file
4. Confirm deletion
5. Files removed from system

---

### Part 10: Troubleshooting Visual Guide

#### Issue A: Popup Blocked
**Visual indicator:**
```
🚫 Pop-up blocked
```
In browser address bar (right side)

**Solution:**
1. Click the popup icon
2. Select "Always allow pop-ups from this site"
3. Try viewing file again

#### Issue B: File Too Large
**Error message:**
```
┌─────────────────────────────────────┐
│  ❌ File too large. Maximum size:  │
│     5MB                             │
└─────────────────────────────────────┘
```

**Solution:**
1. Check file size (right-click → Properties)
2. Compress the file:
   - Images: Use tinypng.com
   - PDF: Use smallpdf.com
3. Try uploading again

#### Issue C: Wrong File Type
**Error message:**
```
┌─────────────────────────────────────────┐
│  ❌ Invalid file type. Allowed: JPG,   │
│     PNG, GIF, PDF, TXT, DOC, DOCX      │
└─────────────────────────────────────────┘
```

**Solution:**
1. Check file extension
2. Convert to supported format
3. Upload the converted file

---

### Part 11: Tips for Best Experience

#### Visual Organization Tips
**Good filename examples:**
```
✅ 2024-11-04_ChestXRay_PA.jpg
✅ LabResults_CBC_2024-11-04.pdf
✅ Prescription_Amoxicillin.pdf
✅ MedicalHistory_Summary.txt
```

**Bad filename examples:**
```
❌ IMG_1234.jpg
❌ Document1.pdf
❌ Untitled.txt
❌ New Document (2).docx
```

#### File Size Visual Guide
```
Excellent: 📗 0-500 KB    (Fast upload, instant view)
Good:      📘 500KB-2MB   (Quick upload, fast view)
Okay:      📙 2-5MB       (Slower upload, may lag)
Too Big:   📕 >5MB        (Upload fails)
```

#### Upload Speed Indicators
```
Small file (100KB):  ⚡ Instant (< 1 second)
Medium file (1MB):   🏃 Quick (1-3 seconds)
Large file (4MB):    🚶 Slow (5-10 seconds)
```

---

### Part 12: Keyboard Shortcuts

#### While Viewing Files
```
ESC key          → Close popup/window
Ctrl + P         → Print (in PDF viewer)
Ctrl + F         → Search (in PDF viewer)
Ctrl + W         → Close window
Alt + ←          → Go back
F11              → Fullscreen toggle
```

#### In Patient Record Page
```
Ctrl + F         → Search on page
F5               → Refresh page
Ctrl + Click     → Open link in new tab
Alt + ←          → Back to records list
```

---

## 🎯 Success Indicators

### You'll know it's working when you see:
✅ File appears in the table after upload
✅ File size is displayed correctly
✅ View button opens file correctly
✅ Delete removes file from list
✅ Green success messages appear
✅ Page refreshes automatically

### Visual confirmation checklist:
- [ ] Setup page shows success message
- [ ] File upload section is visible
- [ ] Choose file button works
- [ ] Upload button responds
- [ ] Files list displays correctly
- [ ] View opens files properly
- [ ] Delete removes files
- [ ] No error messages appear

---

**Need more help?** Check the [Quick Start Guide](QUICK_START_FILE_UPLOAD.md) or [Full Documentation](FILE_UPLOAD_FEATURE.md)
