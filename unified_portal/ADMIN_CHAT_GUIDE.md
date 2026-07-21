# Admin Chat System - Setup Guide

## Overview
A real-time bubble chat feature has been added to enable communication between the three administrators:
- Admin (admin.php)
- Health Admin (HEALTH.php)
- Dental Admin (dental.php)

## Features
✅ Real-time messaging between all three administrators
✅ Bubble chat button fixed at bottom-right corner
✅ Unread message notifications
✅ Auto-refresh every 3 seconds
✅ Beautiful gradient UI design
✅ Responsive design for mobile devices
✅ Message history stored in database
✅ Timestamps showing when messages were sent

## Installation Steps

### 1. Create Database Table
Run the SQL script to create the chat table in your database:

```sql
-- Option 1: Using phpMyAdmin
-- Navigate to phpMyAdmin > wpu database > SQL tab
-- Copy and paste the contents of: database/admin_chat.sql

-- Option 2: Using command line
mysql -u root -p wpu < database/admin_chat.sql
```

The SQL creates a table called `admin_chat` in the `wpu` database.

### 2. Verify Files Created
The following files have been created:

```
database/
  └── admin_chat.sql           # Database table structure

components/
  ├── admin_chat.php           # Chat UI component
  └── chat_api.php             # Backend API

assets/
  └── js/
      └── chat.js              # Chat functionality
```

### 3. Integration Complete
The chat component has been automatically integrated into:
- `admin/admin.php`
- `health/HEALTH.php`
- `dental/dental.php`

## How to Use

### For End Users:
1. **Access Any Admin Page**: Navigate to admin.php, HEALTH.php, or dental.php
2. **Look for Chat Bubble**: You'll see a purple gradient bubble in the bottom-right corner
3. **Click to Open**: Click the bubble to open the chat window
4. **Send Messages**: Type your message and press Enter or click the send button
5. **View Messages**: All messages from all three admins will appear in the chat
6. **Unread Notifications**: Red badge shows number of unread messages

### Message Features:
- **Real-time Updates**: New messages appear automatically every 3 seconds
- **Sender Identification**: Each message shows who sent it (Admin/Health Admin/Dental Admin)
- **Timestamps**: Shows when each message was sent (e.g., "Just now", "5m ago", "2h ago")
- **Message History**: All messages are stored and can be viewed anytime
- **Character Limit**: Messages limited to 500 characters

## Testing

1. Open three different browser windows:
   - Window 1: http://localhost/wpu_medical-master/admin/admin.php
   - Window 2: http://localhost/wpu_medical-master/health/HEALTH.php
   - Window 3: http://localhost/wpu_medical-master/dental/dental.php

2. Click the chat bubble in any window
3. Send a message
4. Watch it appear in all other windows within 3 seconds

## Customization

### Change Chat Colors
Edit `components/admin_chat.php` and modify the gradient colors:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

### Change Polling Interval
Edit `assets/js/chat.js` and modify:
```javascript
const POLL_INTERVAL = 3000; // Change to desired milliseconds
```

### Change Message Character Limit
Edit both files:
- `components/admin_chat.php`: Change `maxlength="500"`
- `components/chat_api.php`: Change `strlen($message) > 500`

## Troubleshooting

### Chat bubble not appearing:
- Clear browser cache and refresh
- Check if files are properly uploaded
- Verify JavaScript console for errors

### Messages not sending:
- Check database connection in `chat_api.php`
- Verify `admin_chat` table exists in `wpu` database
- Check network tab in browser developer tools

### Messages not updating:
- Verify polling is working (check browser console)
- Ensure `chat_api.php` is accessible
- Check for JavaScript errors in console

## Security Notes

- The system uses basic sender identification based on the page URL
- For production use, consider adding proper authentication
- Messages are stored indefinitely - consider adding cleanup/archival
- Currently no message deletion feature - can be added if needed

## Support

For issues or questions:
1. Check browser console for JavaScript errors
2. Check PHP error logs
3. Verify database connection and table structure
4. Ensure all file paths are correct

## Future Enhancements (Optional)

Consider adding:
- Message deletion/editing
- File attachments
- Typing indicators
- Online/offline status
- Message reactions (like, emoji)
- Search functionality
- Message archiving
- Push notifications
- Sound alerts for new messages
