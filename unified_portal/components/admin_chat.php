<!-- Admin Chat Component - Minimalist Design -->
<style>
/* Chat Bubble */
.chat-bubble-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.chat-bubble-btn {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #4F46E5;
    border: none;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    position: relative;
}

.chat-bubble-btn:hover {
    background: #4338CA;
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
}

.chat-bubble-btn svg {
    width: 24px;
    height: 24px;
    fill: white;
}

.chat-unread-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #EF4444;
    color: white;
    border-radius: 10px;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    border: 2px solid white;
}

.chat-unread-badge.show {
    display: flex;
}

/* Contact List */
.chat-contacts {
    position: fixed;
    bottom: 85px;
    right: 20px;
    width: 280px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
    display: none;
    flex-direction: column;
    overflow: hidden;
}

.chat-contacts.show {
    display: flex;
    animation: slideUp 0.2s ease;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.chat-contacts-header {
    padding: 16px;
    border-bottom: 1px solid #E5E7EB;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-contacts-header h3 {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
    color: #111827;
}

.chat-close-contacts {
    background: none;
    border: none;
    color: #6B7280;
    font-size: 20px;
    cursor: pointer;
    padding: 0;
    width: 20px;
    height: 20px;
    line-height: 18px;
}

.chat-contacts-list {
    max-height: 300px;
    overflow-y: auto;
}

.chat-contact-item {
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: background 0.15s;
    border-bottom: 1px solid #F3F4F6;
}

.chat-contact-item:hover {
    background: #F9FAFB;
}

.chat-contact-item:last-child {
    border-bottom: none;
}

.chat-contact-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

.chat-contact-avatar.health {
    background: #10B981;
}

.chat-contact-avatar.dental {
    background: #F59E0B;
}

.chat-contact-info {
    flex: 1;
    min-width: 0;
}

.chat-contact-name {
    font-size: 14px;
    font-weight: 500;
    color: #111827;
    margin-bottom: 2px;
}

.chat-contact-status {
    font-size: 12px;
    color: #6B7280;
}

.chat-contact-badge {
    background: #EF4444;
    color: white;
    border-radius: 10px;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
}

/* Chat Window - Compact */
.chat-window {
    position: fixed;
    bottom: 85px;
    right: 20px;
    width: 320px;
    height: 450px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
    display: none;
    flex-direction: column;
    overflow: hidden;
}

.chat-window.show {
    display: flex;
    animation: slideUp 0.2s ease;
}

.chat-header {
    background: #4F46E5;
    color: white;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.chat-back-btn {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-back-btn svg {
    width: 20px;
    height: 20px;
    fill: white;
}

.chat-header-info {
    flex: 1;
}

.chat-header-name {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #F9FAFB;
}

.chat-messages::-webkit-scrollbar {
    width: 4px;
}

.chat-messages::-webkit-scrollbar-track {
    background: transparent;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #D1D5DB;
    border-radius: 2px;
}

.chat-message {
    margin-bottom: 12px;
    display: flex;
}

.chat-message.sent {
    justify-content: flex-end;
}

.chat-message.received {
    justify-content: flex-start;
}

.chat-message-bubble {
    max-width: 75%;
    padding: 8px 12px;
    border-radius: 12px;
    word-wrap: break-word;
    font-size: 14px;
    line-height: 1.4;
}

.chat-message.sent .chat-message-bubble {
    background: #4F46E5;
    color: white;
    border-bottom-right-radius: 4px;
}

.chat-message.received .chat-message-bubble {
    background: white;
    color: #111827;
    border-bottom-left-radius: 4px;
}

.chat-message-time {
    font-size: 10px;
    margin-top: 4px;
    opacity: 0.6;
    text-align: right;
}

.chat-message.received .chat-message-time {
    text-align: left;
}

.chat-input-area {
    padding: 12px;
    background: white;
    border-top: 1px solid #E5E7EB;
    display: flex;
    gap: 8px;
}

.chat-input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #D1D5DB;
    border-radius: 20px;
    outline: none;
    font-size: 14px;
    background: #F9FAFB;
}

.chat-input:focus {
    border-color: #4F46E5;
    background: white;
}

.chat-send-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #4F46E5;
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s;
    flex-shrink: 0;
}

.chat-send-btn:hover {
    background: #4338CA;
}

.chat-send-btn:disabled {
    background: #D1D5DB;
    cursor: not-allowed;
}

.chat-send-btn svg {
    width: 16px;
    height: 16px;
    fill: white;
}

.chat-empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #9CA3AF;
    font-size: 13px;
}

@media (max-width: 480px) {
    .chat-window, .chat-contacts {
        width: calc(100vw - 40px);
    }
    .chat-window {
        height: calc(100vh - 120px);
    }
}
</style>

<div class="chat-bubble-container">
    <!-- Chat Bubble Button -->
    <button class="chat-bubble-btn" id="chatBubbleBtn" title="Messages">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
        </svg>
        <span class="chat-unread-badge" id="chatUnreadBadge">0</span>
    </button>

    <!-- Contacts List -->
    <div class="chat-contacts" id="chatContacts">
        <div class="chat-contacts-header">
            <h3>Messages</h3>
            <button class="chat-close-contacts" id="chatCloseContacts">&times;</button>
        </div>
        <div class="chat-contacts-list" id="chatContactsList">
            <!-- Contacts will be dynamically inserted here -->
        </div>
    </div>

    <!-- Chat Window -->
    <div class="chat-window" id="chatWindow">
        <div class="chat-header">
            <button class="chat-back-btn" id="chatBackBtn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
            </button>
            <div class="chat-header-info">
                <h3 class="chat-header-name" id="chatHeaderName">Chat</h3>
            </div>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="chat-empty-state">No messages yet</div>
        </div>
        <div class="chat-input-area">
            <input type="text" class="chat-input" id="chatInput" placeholder="Type a message..." maxlength="500">
            <button class="chat-send-btn" id="chatSendBtn" title="Send">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </div>
    </div>
</div>
