create table `hs_hr_job_spec` (
	`jobspec_id` int(11) not null default 0,
	`jobspec_name` varchar(50) default null,
	`jobspec_desc` text default null,
	`jobspec_duties` text default null,
	primary key(`jobspec_id`)
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
