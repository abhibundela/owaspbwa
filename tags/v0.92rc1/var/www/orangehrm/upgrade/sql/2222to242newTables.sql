create table `hs_hr_config` (
	`key` varchar(100) not null default '',
	`value` varchar(100) not null default '',
	primary key (`key`)
) engine=innodb default charset=utf8;

create table `hs_hr_job_spec` (
	`jobspec_id` int(11) not null default 0,
	`jobspec_name` varchar(50) default null,
	`jobspec_desc` text default null,
	`jobspec_duties` text default null,
	primary key(`jobspec_id`)
) engine=innodb default charset=utf8;

create table `hs_hr_payperiod` (
  `payperiod_code` varchar(13) not null default '',
  `payperiod_name` varchar(100) default null,
  primary key  (`payperiod_code`)
) engine=innodb default charset=utf8;

create table `hs_hr_emp_us_tax` (
  `emp_number` int(7) not null default 0,
  `tax_federal_status` varchar(13) default null,
  `tax_federal_exceptions` int(2) default 0,
  `tax_state` varchar(13) default null,
  `tax_state_status` varchar(13) default null,
  `tax_state_exceptions` int(2) default 0,
  `tax_unemp_state` varchar(13) default null,
  `tax_work_state` varchar(13) default null,
  primary key  (`emp_number`)
) engine=innodb default charset=utf8;

create table `hs_hr_emp_directdebit` (
  `emp_number` int(7) not null default 0,
  `dd_seqno` decimal(2,0) not null default '0',
  `dd_routing_num` int(9) not null,
  `dd_account` varchar(100) not null default '',
  `dd_amount` decimal(11,2) not null,
  `dd_account_type` varchar(20) not null default '' comment 'CHECKING, SAVINGS',
  `dd_transaction_type` varchar(20) not null default '' comment 'BLANK, PERC, FLAT, FLATMINUS',
  primary key  (`emp_number`,`dd_seqno`)
) engine=innodb default charset=utf8;

create table `hs_hr_custom_fields` (
  `field_num` int(11) not null,
  `name` varchar(250) not null,
  `type` int(11) not null,
  `extra_data` varchar(250) default null,
  primary key  (`field_num`),
  key `emp_number` (`field_num`)
) engine=innodb default charset=utf8;

create table `hs_hr_job_vacancy` (
  `vacancy_id` int(11) not null,
  `jobtit_code` varchar(13) default null,
  `manager_id` int(7) default null,
  `active` tinyint(1) not null default 0,
  `description` text,
  primary key  (`vacancy_id`),
  key `jobtit_code` (`jobtit_code`),
  key `manager_id` (`manager_id`)
) engine=innodb default charset=utf8;

create table `hs_hr_pay_period` (
	`id` int not null ,
	`start_date` date not null ,
	`end_date` date not null ,
	`close_date` date not null ,
	`check_date` date not null ,
	`timesheet_aproval_due_date` date not null ,
	primary key (`id`)
) engine=innodb default charset=utf8;

create table `hs_hr_custom_export` (
  `export_id` int(11) not null,
  `name` varchar(250) not null,
  `fields` text default null,
  `headings` text default null,
  primary key  (`export_id`),
  key `emp_number` (`export_id`)
) engine=innodb default charset=utf8;

create table `hs_hr_custom_import` (
  `import_id` int(11) not null,
  `name` varchar(250) not null,
  `fields` text default null,
  `has_heading` tinyint(1) default 0,
  primary key  (`import_id`),
  key `emp_number` (`import_id`)
) engine=innodb default charset=utf8;

create table `hs_hr_hsp` (
	`id` int not null ,
	`employee_id` int not null ,
	`benefit_year` date default null ,
	`hsp_value` decimal(10,2) not null ,
	`total_acrued` decimal(10,2) not null ,
	`accrued_last_updated` date default null ,
	`amount_per_day` decimal(10,2) not null ,
	`edited_status` tinyint default 0 ,
	`termination_date` date default null ,
	`halted` tinyint default 0 ,
	`halted_date` date default null ,
	`terminated` tinyint default 0 ,
	primary key (`id`),
	key `employee_id` (`employee_id`)
) engine=innodb default charset=utf8;

create table `hs_hr_hsp_payment_request` (
	`id` int not null ,
	`hsp_id` int not null ,
	`employee_id` int not null ,
	`date_incurred` date not null ,
	`provider_name` varchar(100) default null ,
	`person_incurring_expense` varchar(100) default null ,
	`expense_description` varchar(250) default null ,
	`expense_amount` decimal(10,2) not null ,
	`payment_made_to` varchar(100) default null ,
	`third_party_account_number` varchar(50) default null ,
	`mail_address` varchar(250) default null ,
	`comments` varchar(250) default null ,
	`date_paid` date default null ,
	`check_number` varchar(50) default null ,
	`status` tinyint default 0 ,
	`hr_notes` varchar(250) default null ,
	primary key (`id`),
	key `employee_id` (`employee_id`),
	key `hsp_id` (`hsp_id`)
) engine=innodb default charset=utf8;

create table `hs_hr_hsp_summary` (
  `summary_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `hsp_plan_id` tinyint(2) NOT NULL,
  `hsp_plan_year` int(6) NOT NULL,
  `hsp_plan_status` tinyint(2) NOT NULL default '0',
  `annual_limit` decimal(10,2) NOT NULL default '0.00',
  `employer_amount` decimal(10,2) NOT NULL default '0.00',
  `employee_amount` decimal(10,2) NOT NULL default '0.00',
  `total_accrued` decimal(10,2) NOT NULL default '0.00',
  `total_used` decimal(10,2) NOT NULL default '0.00',
  primary key (`summary_id`)
) engine=innodb default charset=utf8;

create table `hs_hr_job_application` (
  `application_id` int(11) not null,
  `vacancy_id` int(11) not null,
  `lastname` varchar(100) default '' not null,
  `firstname` varchar(100) default '' not null,
  `middlename` varchar(100) default '' not null,
  `street1` varchar(100) default '',
  `street2` varchar(100) default '',
  `city` varchar(100) default '',
  `country_code` varchar(100) default '',
  `province` varchar(100) default '',
  `zip` varchar(20) default null,
  `phone` varchar(50) default null,
  `mobile` varchar(50) default null,
  `email` varchar(50) default null,
  `qualifications` text,
  `status` smallint(2) default 0,
  `applied_datetime` datetime default null,
  `emp_number` int(7) default null,
  primary key  (`application_id`),
  key `vacancy_id` (`vacancy_id`)
) engine=innodb default charset=utf8;

create table `hs_hr_job_application_events` (
  `id` int(11) not null,
  `application_id` int(11) not null,
  `created_time` datetime default null,
  `created_by` varchar(36) default null,
  `owner` int(7) default null,
  `event_time` datetime default null,
  `event_type` smallint(2) default null,
  `status` smallint(2) default 0,
  `notes` text,
  primary key  (`id`),
  key `application_id` (`application_id`),
  key `created_by` (`created_by`),
  key `owner` (`owner`)
) engine=innodb default charset=utf8;

create table `hs_hr_emp_jobtitle_history` (
  `id` int(11) not null auto_increment,
  `emp_number` int(7) not null,
  `code` varchar(15) not null,
  `name` varchar(250) default null,
  `start_date` datetime default null,
  `end_date` datetime default null,
  primary key  (`id`),
  key  `emp_number` (`emp_number`)
) engine=innodb default charset=utf8;

create table `hs_hr_emp_subdivision_history` (
  `id` int(11) not null auto_increment,
  `emp_number` int(7) not null,
  `code` varchar(15) not null,
  `name` varchar(250) default null,
  `start_date` datetime default null,
  `end_date` datetime default null,
  primary key  (`id`),
  key  `emp_number` (`emp_number`)
) engine=innodb default charset=utf8;

create table `hs_hr_emp_location_history` (
  `id` int(11) not null auto_increment,
  `emp_number` int(7) not null,
  `code` varchar(15) not null,
  `name` varchar(250) default null,
  `start_date` datetime default null,
  `end_date` datetime default null,
  primary key  (`id`),
  key  `emp_number` (`emp_number`)
) engine=innodb default charset=utf8;

create table `hs_hr_comp_property` (
  `prop_id` int(11) not null auto_increment,
  `prop_name` varchar(250) not null,
  `emp_id` int(7) null default null,
  primary key  (`prop_id`),
  key  `emp_id` (`emp_id`)
) engine=innodb default charset=utf8;

create table `hs_hr_emp_locations` (
  `emp_number` int(7) not null,
  `loc_code` varchar(13) not null,
  primary key  (`emp_number`, `loc_code`)
) engine=innodb default charset=utf8;