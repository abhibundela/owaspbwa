INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_job_spec', 'jobspec_id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_job_vacancy', 'vacancy_id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_job_application', 'application_id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_job_application_events', 'id');
UPDATE `hs_hr_unique_id` SET `last_id` = 8 WHERE `table_name` = 'hs_hr_module' AND `field_name` = 'mod_id';

INSERT INTO `hs_hr_module` VALUES ('MOD008', 'Recruitment', 'OrangeHRM', 'info@orangehrm.com', 'VER001', 'Recruitment');
INSERT INTO `hs_hr_rights` ( `userg_id` , `mod_id` , `addition` , `editing` , `deletion` , `viewing` ) VALUES  ('USG001', 'MOD008', '1', '1', '1', '1');