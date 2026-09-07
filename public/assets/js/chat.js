// Admin Chat JavaScript - Individual Chat System
(function() {
    'use strict';

    // Configuration
    const POLL_INTERVAL = 2000;
    const MAX_POLL_INTERVAL = 30000;
    const API_BASE = window.location.pathname.includes('/admin/') 
        ? '../components/chat_api.php' 
        : 'components/chat_api.php';
    const JSON_HEADERS = { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };
    
    // Determine current user - unified system uses admin
    let currentUser = 'admin';
    let consecutiveErrors = 0;

    // Available contacts
    const contacts = {
        'admin': [
            { id: 'health', name: 'Health Admin', avatar: 'H', class: 'health' },
            { id: 'dental', name: 'Dental Admin', avatar: 'D', class: 'dental' }
        ],
        'health': [
            { id: 'admin', name: 'Admin', avatar: 'A', class: 'admin' },
            { id: 'dental', name: 'Dental Admin', avatar: 'D', class: 'dental' }
        ],
        'dental': [
            { id: 'admin', name: 'Admin', avatar: 'A', class: 'admin' },
            { id: 'health', name: 'Health Admin', avatar: 'H', class: 'health' }
        ]
    };

    // DOM Elements
    const chatBubbleBtn = document.getElementById('chatBubbleBtn');
    const chatContacts = document.getElementById('chatContacts');
    const chatWindow = document.getElementById('chatWindow');
    const chatCloseContacts = document.getElementById('chatCloseContacts');
    const chatBackBtn = document.getElementById('chatBackBtn');
    const chatContactsList = document.getElementById('chatContactsList');
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const chatSendBtn = document.getElementById('chatSendBtn');
    const chatUnreadBadge = document.getElementById('chatUnreadBadge');
    const chatHeaderName = document.getElementById('chatHeaderName');

    // State
    let currentView = 'none';
    let activeContact = null;
    let lastMessageIds = {};
    let unreadCounts = {};
    let pollInterval = null;

    // Initialize
    function init() {
        renderContacts();
        attachEventListeners();
        loadUnreadCounts();
        startPolling();
    }

    // Render contacts
    function renderContacts() {
        const contactsHtml = contacts[currentUser].map(contact => `
            <div class="chat-contact-item" data-contact="${contact.id}">
                <div class="chat-contact-avatar ${contact.class}">${contact.avatar}</div>
                <div class="chat-contact-info">
                    <div class="chat-contact-name">${contact.name}</div>
                    <div class="chat-contact-status">Click to chat</div>
                </div>
                <span class="chat-contact-badge" data-badge="${contact.id}" style="display: none;">0</span>
            </div>
        `).join('');
        
        chatContactsList.innerHTML = contactsHtml;
        
        document.querySelectorAll('.chat-contact-item').forEach(item => {
            item.addEventListener('click', function() {
                openChatWith(this.dataset.contact);
            });
        });
    }

    // Event listeners
    function attachEventListeners() {
        chatBubbleBtn.addEventListener('click', toggleContacts);
        chatCloseContacts.addEventListener('click', closeAll);
        chatBackBtn.addEventListener('click', backToContacts);
        chatSendBtn.addEventListener('click', sendMessage);
        
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });

        // Close chat when clicking outside the chat UI (same logic as modals)
        document.addEventListener('click', function(e) {
            try {
                const container = document.querySelector('.chat-bubble-container');
                if (!container) return;
                const clickInside = container.contains(e.target);
                if (!clickInside && currentView !== 'none') {
                    closeAll();
                }
            } catch (err) {
                // Fail-safe: do nothing on error
                console.error('Chat outside-click handler error:', err);
            }
        });
    }

    // Toggle contacts
    function toggleContacts() {
        if (currentView === 'none') {
            showContacts();
        } else {
            closeAll();
        }
    }

    // Show contacts
    function showContacts() {
        currentView = 'contacts';
        chatContacts.classList.add('show');
        chatWindow.classList.remove('show');
        loadUnreadCounts();
    }

    // Close all
    function closeAll() {
        currentView = 'none';
        chatContacts.classList.remove('show');
        chatWindow.classList.remove('show');
        activeContact = null;
    }

    // Back to contacts
    function backToContacts() {
        showContacts();
    }

    // Open chat with contact
    function openChatWith(contactId) {
        activeContact = contactId;
        currentView = 'chat';
        
        const contact = contacts[currentUser].find(c => c.id === contactId);
        chatHeaderName.textContent = contact.name;
        
        chatContacts.classList.remove('show');
        chatWindow.classList.add('show');
        
        chatMessages.innerHTML = '<div class="chat-empty-state">Loading...</div>';
        // Reset lastMessageId to ensure all messages are loaded fresh
        lastMessageIds[contactId] = 0;
        loadMessages(contactId, true);
        markMessagesAsRead(contactId);
        chatInput.focus();
    }

    function parseJsonResponse(response) {
        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }
        return response.json();
    }

    // Send message
    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message || !activeContact) return;

        chatInput.disabled = true;
        chatSendBtn.disabled = true;

        fetch(API_BASE, {
            method: 'POST',
            headers: Object.assign({ 'Content-Type': 'application/x-www-form-urlencoded' }, JSON_HEADERS),
            body: new URLSearchParams({
                action: 'send',
                sender: currentUser,
                receiver: activeContact,
                message: message
            }),
            redirect: 'error'
        })
        .then(parseJsonResponse)
        .then(data => {
            if (data.success) {
                chatInput.value = '';
                loadMessages(activeContact, false);
            } else {
                alert('Failed: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send');
        })
        .finally(() => {
            chatInput.disabled = false;
            chatSendBtn.disabled = false;
            chatInput.focus();
        });
    }

    // Load messages
    function loadMessages(contactId, isInitial = false) {
        const lastId = lastMessageIds[contactId] || 0;
        
        fetch(API_BASE + '?' + new URLSearchParams({
            action: 'get',
            user: currentUser,
            contact: contactId,
            last_id: lastId
        }), { headers: JSON_HEADERS, redirect: 'error' })
        .then(parseJsonResponse)
        .then(data => {
            if (data.success) {
                consecutiveErrors = 0;
                if (isInitial && data.all_messages) {
                    renderAllMessages(data.all_messages);
                } else if (data.messages && data.messages.length > 0) {
                    renderNewMessages(data.messages);
                }
                
                if (data.last_id) {
                    lastMessageIds[contactId] = data.last_id;
                }
            }
        })
        .catch(error => {
            consecutiveErrors++;
            if (consecutiveErrors <= 2) console.error('Chat load error:', error);
        });
    }

    // Render all messages
    function renderAllMessages(messages) {
        if (messages.length === 0) {
            chatMessages.innerHTML = '<div class="chat-empty-state">No messages yet</div>';
            return;
        }
        
        chatMessages.innerHTML = '';
        messages.forEach(msg => appendMessage(msg));
        scrollToBottom();
    }

    // Render new messages
    function renderNewMessages(messages) {
        const emptyState = chatMessages.querySelector('.chat-empty-state');
        if (emptyState) chatMessages.innerHTML = '';
        
        messages.forEach(msg => appendMessage(msg));
        scrollToBottom();
    }

    // Append message
    function appendMessage(msg) {
        const isOwn = msg.sender === currentUser;
        const div = document.createElement('div');
        div.className = 'chat-message ' + (isOwn ? 'sent' : 'received');
        
        const bubble = document.createElement('div');
        bubble.className = 'chat-message-bubble';
        bubble.textContent = msg.message;
        
        const time = document.createElement('div');
        time.className = 'chat-message-time';
        time.textContent = formatTime(msg.timestamp);
        
        bubble.appendChild(time);
        div.appendChild(bubble);
        chatMessages.appendChild(div);
    }

    // Format time
    function formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return Math.floor(diff / 60000) + 'm';
        if (diff < 86400000) return Math.floor(diff / 3600000) + 'h';
        
        return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    // Scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Mark as read
    function markMessagesAsRead(contactId) {
        fetch(API_BASE, {
            method: 'POST',
            headers: Object.assign({ 'Content-Type': 'application/x-www-form-urlencoded' }, JSON_HEADERS),
            body: new URLSearchParams({
                action: 'mark_read',
                user: currentUser,
                contact: contactId
            }),
            redirect: 'error'
        })
        .then(parseJsonResponse)
        .then(data => {
            if (data.success) {
                unreadCounts[contactId] = 0;
                updateUnreadBadges();
            }
        })
        .catch(error => {
            if (consecutiveErrors <= 2) console.error('Chat mark-read error:', error);
        });
    }

    // Load unread counts
    function loadUnreadCounts() {
        fetch(API_BASE + '?' + new URLSearchParams({
            action: 'unread_counts',
            user: currentUser
        }), { headers: JSON_HEADERS, redirect: 'error' })
        .then(parseJsonResponse)
        .then(data => {
            if (data.success && data.counts) {
                consecutiveErrors = 0;
                unreadCounts = data.counts;
                updateUnreadBadges();
            }
        })
        .catch(error => {
            consecutiveErrors++;
            if (consecutiveErrors <= 2) console.error('Chat unread-counts error:', error);
        });
    }

    // Update badges
    function updateUnreadBadges() {
        let totalUnread = 0;
        
        contacts[currentUser].forEach(contact => {
            const count = unreadCounts[contact.id] || 0;
            const badge = document.querySelector(`[data-badge="${contact.id}"]`);
            
            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
            
            totalUnread += count;
        });
        
        if (totalUnread > 0) {
            chatUnreadBadge.textContent = totalUnread > 99 ? '99+' : totalUnread;
            chatUnreadBadge.classList.add('show');
        } else {
            chatUnreadBadge.classList.remove('show');
        }
    }

    // Poll with adaptive interval
    function poll() {
        if (consecutiveErrors >= 5) {
            stopPolling();
            return;
        }
        loadUnreadCounts();
        if (activeContact && currentView === 'chat') {
            loadMessages(activeContact, false);
        }
    }

    // Start polling
    function startPolling() {
        stopPolling();
        var interval = Math.min(POLL_INTERVAL * Math.pow(2, consecutiveErrors), MAX_POLL_INTERVAL);
        pollInterval = setInterval(function () {
            poll();
            if (consecutiveErrors > 0) {
                startPolling();
            }
        }, interval);
    }

    // Stop polling
    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    // Init
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.addEventListener('beforeunload', stopPolling);

})();
