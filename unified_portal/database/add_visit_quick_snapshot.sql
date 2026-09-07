-- Run once on existing databases (phpMyAdmin or mysql CLI).
ALTER TABLE `patient_records`
  ADD COLUMN `visit_quick_snapshot` mediumtext DEFAULT NULL
  COMMENT 'JSON: visit fields before last edit (Quick View)'
  AFTER `doctor`;
