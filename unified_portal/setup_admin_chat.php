<?php
/**
 * Admin Chat System - Database Setup Script
 * Run this file once to create the necessary database table
 */

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'health_records';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Admin Chat Setup</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #dc3545;
            margin: 20px 0;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #17a2b8;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .btn:hover {
            opacity: 0.9;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class='container'>";

echo "<h1>🏥 Admin Chat System Setup</h1>";

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='success'>✓ Successfully connected to database: <code>$db_name</code></div>";
    
    // Check if table already exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'admin_chat'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "<div class='info'>ℹ Table <code>admin_chat</code> already exists. Checking structure...</div>";
        
        // Get current table structure
        $stmt = $pdo->query("DESCRIBE admin_chat");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Current Table Structure:</h3>";
        echo "<pre>";
        foreach ($columns as $column) {
            echo "- {$column['Field']} ({$column['Type']})\n";
        }
        echo "</pre>";
        
        // Get row count
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin_chat");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $messageCount = $row['count'];
        
        echo "<div class='success'>✓ Table is ready to use! Current messages: <strong>$messageCount</strong></div>";
        
    } else {
        echo "<div class='info'>ℹ Creating table <code>admin_chat</code>...</div>";
        
        // Create table
        $sql = "CREATE TABLE IF NOT EXISTS `admin_chat` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $pdo->exec($sql);
        
        echo "<div class='success'>✓ Table <code>admin_chat</code> created successfully!</div>";
        
        // Insert welcome messages
        $stmt = $pdo->prepare("INSERT INTO admin_chat (sender, receiver, message) VALUES ('admin', 'health', ?)");
        $stmt->execute(['Welcome! You can now chat privately with Health Admin.']);
        
        $stmt = $pdo->prepare("INSERT INTO admin_chat (sender, receiver, message) VALUES ('admin', 'dental', ?)");
        $stmt->execute(['Welcome! You can now chat privately with Dental Admin.']);
        
        echo "<div class='success'>✓ Welcome messages inserted!</div>";
    }
    
    // Show next steps
    echo "<h3>✅ Setup Complete!</h3>";
    echo "<div class='info'>";
    echo "<strong>Next Steps:</strong><br>";
    echo "1. Navigate to the unified admin panel:<br>";
    echo "&nbsp;&nbsp;&nbsp;• <a href='../admin/admin.php' target='_blank'>Unified Admin Panel</a><br>";
    echo "2. Look for the purple chat bubble in the bottom-right corner<br>";
    echo "3. Click to open and start chatting!<br>";
    echo "</div>";
    
    // Show file locations
    echo "<h3>📁 Files Created:</h3>";
    echo "<pre>";
    echo "components/\n";
    echo "  ├── admin_chat.php      (Chat UI component)\n";
    echo "  └── chat_api.php        (Backend API)\n\n";
    echo "assets/js/\n";
    echo "  └── chat.js             (Chat functionality)\n\n";
    echo "database/\n";
    echo "  └── admin_chat.sql      (Database structure)\n";
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<strong>✗ Database Error:</strong><br>";
    echo htmlspecialchars($e->getMessage());
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<strong>Troubleshooting:</strong><br>";
    echo "1. Make sure XAMPP/MySQL is running<br>";
    echo "2. Verify database name is '<code>health_records</code>'<br>";
    echo "3. Check database credentials in this file<br>";
    echo "4. Ensure you have proper database permissions<br>";
    echo "</div>";
}

echo "</div></body></html>";
?>
