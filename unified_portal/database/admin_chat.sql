-- Admin Chat System Database Table
-- This table stores private chat messages between admin, health, and dental administrators

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
