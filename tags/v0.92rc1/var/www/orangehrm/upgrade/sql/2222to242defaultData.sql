INSERT INTO `hs_hr_module` VALUES ('MOD007', 'Benefits', 'Gayanath', 'gayanath@orangehrm.com', 'VER001', 'Benefits Tracking'),
								  ('MOD008', 'Recruitment', 'OrangeHRM', 'info@orangehrm.com', 'VER001', 'Recruitment');
INSERT INTO `hs_hr_rights` ( `userg_id` , `mod_id` , `addition` , `editing` , `deletion` , `viewing` )
VALUES  ('USG001', 'MOD007', '1', '1', '1', '1'),
		('USG001', 'MOD008', '1', '1', '1', '1');

INSERT INTO `hs_hr_payperiod`(payperiod_code, payperiod_name) VALUES(1, 'Weekly');
INSERT INTO `hs_hr_payperiod`(payperiod_code, payperiod_name) VALUES(2, 'Bi Weekly');
INSERT INTO `hs_hr_payperiod`(payperiod_code, payperiod_name) VALUES(3, 'Semi Monthly');
INSERT INTO `hs_hr_payperiod`(payperiod_code, payperiod_name) VALUES(4, 'Monthly');
INSERT INTO `hs_hr_payperiod`(payperiod_code, payperiod_name) VALUES(5, 'Monthly on first pay of month.');

INSERT INTO `hs_hr_config`(`key`, `value`) VALUES('ldap_server', '');
INSERT INTO `hs_hr_config`(`key`, `value`) VALUES('ldap_domain_name', '');
INSERT INTO `hs_hr_config`(`key`, `value`) VALUES('ldap_port', '');
INSERT INTO `hs_hr_config`(`key`, `value`) VALUES('ldap_status', '');
INSERT INTO `hs_hr_config`(`key`, `value`) VALUES('hsp_current_plan', '0');
INSERT INTO `hs_hr_config`(`key`, `value`) VALUES('hsp_accrued_last_updated', '0000-00-00');
INSERT INTO `hs_hr_config`(`key`, `value`) VALUES('hsp_used_last_updated', '0000-00-00');
INSERT INTO `hs_hr_config`(`key`, `value`) VALUES('timesheet_period_set', 'Yes');

INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_custom_export', 'export_id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_custom_import', 'import_id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_pay_period', 'id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_hsp_summary', 'summary_id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_hsp_payment_request', 'id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_job_spec', 'jobspec_id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_job_vacancy', 'vacancy_id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_job_application', 'application_id');
INSERT INTO `hs_hr_unique_id`(last_id, table_name, field_name) VALUES(0, 'hs_hr_job_application_events', 'id');
UPDATE `hs_hr_unique_id` SET `last_id` = 8 WHERE `table_name` = 'hs_hr_module' AND `field_name` = 'mod_id';
