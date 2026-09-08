-- Append-only edit history for patient records (one row per save).
-- Run in phpMyAdmin or: mysql -u ... your_db < add_patient_record_edit_history.sql

CREATE TABLE IF NOT EXISTS `patient_record_edit_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_record_id` int(11) NOT NULL,
  `edited_by` varchar(50) DEFAULT NULL,
  `snapshot` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_record_created` (`patient_record_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
