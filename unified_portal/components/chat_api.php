<?php
require_once __DIR__ . '/../includes/wpu_security.php';
wpu_bootstrap_admin_api();
/**
 * Admin Chat API - Private Messaging System
 * Handles 1-on-1 chat between admin, health, and dental administrators
 */

header('Content-Type: application/json');

require_once __DIR__.'/../config/database.php';

try {
    $pdo = getDBConnection();
} catch (Throwable) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Get action
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

switch ($action) {
    case 'send':
        sendMessage($pdo);
        break;
    
    case 'get':
        getMessages($pdo);
        break;
    
    case 'mark_read':
        markMessagesAsRead($pdo);
        break;
    
    case 'unread_counts':
        getUnreadCounts($pdo);
        break;
    
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}

/**
 * Send a new message
 */
function sendMessage($pdo) {
    if (!isset($_POST['sender']) || !isset($_POST['receiver']) || !isset($_POST['message'])) {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
        return;
    }
    
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $message = trim($_POST['message']);
    
    // Validate
    if (!in_array($sender, ['admin', 'health', 'dental']) || !in_array($receiver, ['admin', 'health', 'dental'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid sender or receiver']);
        return;
    }
    
    if (empty($message)) {
        echo json_encode(['success' => false, 'error' => 'Message cannot be empty']);
        return;
    }
    
    if (strlen($message) > 500) {
        echo json_encode(['success' => false, 'error' => 'Message too long']);
        return;
    }
    
    try {
        createTableIfNotExists($pdo);
        
        $stmt = $pdo->prepare("INSERT INTO admin_chat (sender, receiver, message) VALUES (?, ?, ?)");
        $stmt->execute([$sender, $receiver, $message]);
        
        echo json_encode([
            'success' => true,
            'message_id' => $pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Failed to send message']);
    }
}

/**
 * Get messages between two users
 */
function getMessages($pdo) {
    if (!isset($_GET['user']) || !isset($_GET['contact'])) {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
        return;
    }
    
    try {
        createTableIfNotExists($pdo);
        
        $user = $_GET['user'];
        $contact = $_GET['contact'];
        $last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;
        
        if ($last_id == 0) {
            // Initial load - get all messages
            $stmt = $pdo->prepare("
                SELECT id, sender, receiver, message, timestamp 
                FROM admin_chat 
                WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)
                ORDER BY timestamp ASC, id ASC
                LIMIT 100
            ");
            $stmt->execute([$user, $contact, $contact, $user]);
            $allMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get last ID
            $stmt = $pdo->prepare("
                SELECT MAX(id) as last_id 
                FROM admin_chat 
                WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)
            ");
            $stmt->execute([$user, $contact, $contact, $user]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $max_id = $row['last_id'] ? $row['last_id'] : 0;
            
            echo json_encode([
                'success' => true,
                'all_messages' => $allMessages,
                'last_id' => $max_id
            ]);
        } else {
            // Poll for new messages
            $stmt = $pdo->prepare("
                SELECT id, sender, receiver, message, timestamp 
                FROM admin_chat 
                WHERE id > ? AND ((sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?))
                ORDER BY timestamp ASC, id ASC
                LIMIT 50
            ");
            $stmt->execute([$last_id, $user, $contact, $contact, $user]);
            $newMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get last ID
            $stmt = $pdo->prepare("
                SELECT MAX(id) as last_id 
                FROM admin_chat 
                WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)
            ");
            $stmt->execute([$user, $contact, $contact, $user]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $max_id = $row['last_id'] ? $row['last_id'] : 0;
            
            echo json_encode([
                'success' => true,
                'messages' => $newMessages,
                'last_id' => $max_id
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Failed to fetch messages']);
    }
}

/**
 * Mark messages as read
 */
function markMessagesAsRead($pdo) {
    if (!isset($_POST['user']) || !isset($_POST['contact'])) {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
        return;
    }
    
    $user = $_POST['user'];
    $contact = $_POST['contact'];
    
    if (!in_array($user, ['admin', 'health', 'dental'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid user']);
        return;
    }
    
    try {
        // Mark messages from contact to user as read
        $stmt = $pdo->prepare("
            UPDATE admin_chat 
            SET is_read = 1 
            WHERE sender = ? AND receiver = ? AND is_read = 0
        ");
        $stmt->execute([$contact, $user]);
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Failed to mark as read']);
    }
}

/**
 * Get unread counts for all contacts
 */
function getUnreadCounts($pdo) {
    if (!isset($_GET['user'])) {
        echo json_encode(['success' => false, 'error' => 'Missing user parameter']);
        return;
    }
    
    $user = $_GET['user'];
    
    if (!in_array($user, ['admin', 'health', 'dental'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid user']);
        return;
    }
    
    try {
        createTableIfNotExists($pdo);
        
        // Get unread count per sender
        $stmt = $pdo->prepare("
            SELECT sender, COUNT(*) as count 
            FROM admin_chat 
            WHERE receiver = ? AND is_read = 0 
            GROUP BY sender
        ");
        $stmt->execute([$user]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $counts = [];
        foreach ($rows as $row) {
            $counts[$row['sender']] = intval($row['count']);
        }
        
        echo json_encode([
            'success' => true,
            'counts' => $counts
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Failed to get unread counts']);
    }
}

/**
 * Create table if it doesn't exist
 */
function createTableIfNotExists($pdo) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `admin_chat` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `sender` varchar(50) NOT NULL COMMENT 'admin, health, or dental',
            `receiver` varchar(50) NOT NULL COMMENT 'admin, health, or dental',
            `message` text NOT NULL,
            `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `is_read` tinyint(1) DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `idx_timestamp` (`timestamp`),
            KEY `idx_sender` (`sender`),
            KEY `idx_receiver` (`receiver`),
            KEY `idx_conversation` (`sender`, `receiver`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}
