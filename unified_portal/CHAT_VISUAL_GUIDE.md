# 🎨 Admin Chat System - Visual Guide

## Design Overview

### 🔵 Chat Bubble (Closed State)
```
┌─────────────────────────────┐
│                             │
│                             │
│                             │
│                        ┌──┐ │
│                        │💬│ │ ← Blue bubble with unread badge
│                        │ 3│ │ ← Red badge shows total unread
│                        └──┘ │
└─────────────────────────────┘
```

### 📋 Contact List View
```
┌──────────────────────────────┐
│  Messages              ✕     │ ← Header with close button
├──────────────────────────────┤
│  ┌─┐                         │
│  │H│  Health Admin        [2]│ ← Green avatar, unread count
│  └─┘  Click to chat          │
├──────────────────────────────┤
│  ┌─┐                         │
│  │D│  Dental Admin        [1]│ ← Orange avatar, unread count
│  └─┘  Click to chat          │
└──────────────────────────────┘
      ↑
  Click to open 1-on-1 chat
```

### 💬 Individual Chat Window
```
┌──────────────────────────────┐
│ ← Health Admin               │ ← Header (blue background)
├──────────────────────────────┤
│                              │
│  ┌─────────────────┐         │
│  │ Hi! How are you?│ 2h      │ ← Received (white bubble)
│  └─────────────────┘         │
│                              │
│         ┌──────────────────┐ │
│         │ Good! How's the │ │ ← Sent (blue bubble)
│      1h │ clinic today?   │ │
│         └──────────────────┘ │
│                              │
│  ┌──────────────────┐        │
│  │ Busy but good!  │ Just now│
│  └──────────────────┘        │
│                              │
├──────────────────────────────┤
│ [Type a message...]      [→]│ ← Input area
└──────────────────────────────┘
```

## User Flow

### 1️⃣ Click Bubble → See Contacts
```
     [Bubble]
        ↓
   ┌─────────┐
   │Messages │
   ├─────────┤
   │Health   │ ← Choose who to chat with
   │Dental   │
   └─────────┘
```

### 2️⃣ Select Contact → Private Chat
```
   Click Health Admin
        ↓
   ┌─────────────┐
   │← Health     │
   ├─────────────┤
   │             │ ← 1-on-1 conversation
   │  Messages   │
   │    here     │
   ├─────────────┤
   │ Type...  [→]│
   └─────────────┘
```

### 3️⃣ Back Button → Return to Contacts
```
   Click ← button
        ↓
   ┌─────────┐
   │Messages │
   ├─────────┤
   │Health   │ ← Switch to another contact
   │Dental   │
   └─────────┘
```

## Color Scheme

### 🎨 Avatar Colors
- **Admin:** Blue (#4F46E5)
- **Health:** Green (#10B981)
- **Dental:** Orange (#F59E0B)

### 💬 Message Colors
- **Sent messages:** Blue background (#4F46E5), white text
- **Received messages:** White background, dark text (#111827)
- **Unread badge:** Red (#EF4444)

## Size Specifications

### 📐 Dimensions
- **Chat Bubble:** 56px × 56px (circle)
- **Contact List:** 280px width
- **Chat Window:** 320px width × 450px height
- **Mobile:** Full width (responsive)

### 🔤 Typography
- **Header:** 15px, Semi-bold
- **Contact Name:** 14px, Medium
- **Messages:** 14px, Regular
- **Timestamps:** 10px, Regular

## Unread Counter Logic

### Main Bubble Badge
```
Total unread = Sum of all contacts
Example: Health(2) + Dental(1) = [3] on bubble
```

### Contact List Badges
```
Each contact shows their own unread count
┌──────────────────────┐
│ Health Admin     [2] │ ← 2 unread from Health
│ Dental Admin     [1] │ ← 1 unread from Dental
└──────────────────────┘
```

### Badge Behavior
- ✅ Shows when messages unread
- ❌ Hides when all messages read
- 🔄 Updates every 2 seconds
- 📱 Shows "99+" if count > 99

## Example Scenarios

### Scenario 1: Admin checking messages
```
1. Admin sees [3] on bubble → Has 3 unread total
2. Opens → Sees Health(2) + Dental(1) in list
3. Clicks Health → Opens chat with Health Admin
4. Reads messages → Badge becomes [1] (only Dental left)
```

### Scenario 2: Health sending to Admin
```
1. Health clicks bubble → Opens contact list
2. Sees Admin and Dental
3. Clicks Admin → Opens private chat
4. Types "Need your help" → Sends
5. Admin sees bubble change from [0] to [1]
```

### Scenario 3: Multiple conversations
```
Admin chatting with Health:
├─ Sees Health messages
├─ Can send to Health
├─ Click back to switch
└─ Choose Dental for different conversation
```

## Mobile Responsive

### Desktop (> 480px)
- Fixed size: 320px × 450px
- Bottom-right corner

### Mobile (≤ 480px)
- Full width: calc(100vw - 40px)
- Full height: calc(100vh - 120px)
- Still bottom-right positioned

## Key Interactions

### Hover Effects
- Bubble: Scale 1.0 → 1.05
- Contacts: Background gray on hover
- Send button: Color darkens

### Click Effects
- Send button: Scale 1.0 → 0.95 (active)
- Smooth transitions (0.15s-0.2s)

### Animations
- Window open: Slide up from bottom
- New messages: Fade in from bottom
- Badge appear: Fade in

## Accessibility

- ✅ Clear contrast ratios
- ✅ Keyboard accessible (Enter to send)
- ✅ Screen reader friendly labels
- ✅ Touch-friendly button sizes (min 36px)

---

**Minimalist** · **Intuitive** · **Fast**
