-- Create table for storing patient file attachments
-- Run this SQL to add file upload functionality to the system

CREATE TABLE IF NOT EXISTS `patient_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_record_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_content` longblob NOT NULL,
  `uploaded_by` varchar(100) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `patient_record_id` (`patient_record_id`),
  CONSTRAINT `patient_files_ibfk_1` FOREIGN KEY (`patient_record_id`) REFERENCES `patient_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
