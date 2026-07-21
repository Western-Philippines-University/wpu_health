# 💬 Admin Chat System - Quick Start Guide

## 🎯 Overview
A minimalist **1-on-1 chat system** where administrators can choose who to chat with privately. Features unread message counters and a clean, compact design.

## 🚀 Installation

### Automatic Setup (Recommended)
1. Navigate to: `http://localhost/wpu_medical-master/setup_admin_chat.php`
2. The script will create the database table automatically
3. Done! Start chatting

### Manual Setup
1. Open phpMyAdmin → Select `wpu` database
2. Go to SQL tab
3. Copy content from `database/admin_chat.sql`
4. Execute

## 📍 Access Points

Chat available on all admin pages:
- **Admin:** `admin/admin.php`
- **Health:** `health/HEALTH.php`
- **Dental:** `dental/dental.php`

## 🎯 How to Use

1. **Click the blue bubble** at bottom-right corner
2. **Select a contact** from the list (Health Admin or Dental Admin, etc.)
3. **Chat 1-on-1** with that person
4. **Unread counter** shows on bubble when closed
5. **Click back arrow** to return to contact list

## ✨ Key Features

✅ **Individual Chats** - Choose who to chat with, no group chat  
✅ **Unread Counters** - Badge on bubble & per contact  
✅ **Minimalist Design** - Compact, clean interface (320px width)  
✅ **Real-time Updates** - Auto-refresh every 2 seconds  
✅ **Mobile Responsive** - Works on all screen sizes  
✅ **Message History** - All messages saved per conversation  
✅ **Timestamps** - Shows "Just now", "5m", "2h", etc.

## 🎨 Design

- **Compact Size:** 320x450px chat window
- **Colors:** Clean blue (#4F46E5) with colored avatars
- **Avatars:** Admin (blue), Health (green), Dental (orange)
- **Minimalist:** Simple, no clutter

## 📋 Files Created

```
components/
  ├── admin_chat.php         UI component (minimalist design)
  └── chat_api.php           Backend API (private messaging)

assets/js/
  └── chat.js                JavaScript (contact list + chat)

database/
  └── admin_chat.sql         Database structure (sender/receiver)

setup_admin_chat.php         Auto-setup script
```

## 🔧 Troubleshooting

**Bubble not showing?**
→ Clear cache (Ctrl+Shift+Delete), refresh page

**Can't send messages?**
→ Run `setup_admin_chat.php` to create database table

**Messages not updating?**
→ Check browser console (F12) for JavaScript errors

**Wrong contact list?**
→ Check URL path (admin/, health/, dental/)

## 💡 Tips

- **Unread counts** update every 2 seconds
- **Click back arrow** to switch contacts
- **Messages are private** between two users only
- **Press Enter** to send messages quickly

## 🎨 Customization

Edit `components/admin_chat.php` to customize:
- **Main color:** Change `#4F46E5` (blue)
- **Size:** Modify `width: 320px` and `height: 450px`
- **Position:** Edit `bottom: 20px` and `right: 20px`
- **Avatar colors:** Modify `.health` and `.dental` classes

## � Architecture

**Contact List View:**
- Shows all possible contacts (2 contacts per user)
- Displays unread count per contact
- Click to open individual chat

**Chat View:**
- Private messages between two users
- Back button returns to contact list
- Real-time message updates

**Database:**
- Each message has `sender` and `receiver`
- Unread tracking per conversation
- Efficient querying with indexes

---

**Minimalist Design** | **Individual Chats** | **Real-time Updates**  
Last updated: November 4, 2025
