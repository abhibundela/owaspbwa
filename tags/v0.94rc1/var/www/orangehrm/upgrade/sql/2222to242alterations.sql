alter table `hs_hr_compstructtree` add `dept_id` varchar(32) null;
alter table `hs_hr_job_title` add `jobspec_id` int(11) default null;
alter table `hs_hr_employee` add `terminated_date` DATE null;
alter table `hs_hr_employee` add `termination_reason` varchar(256) default null;
alter table `hs_hr_employee` add `custom1` varchar(250) default null;
alter table `hs_hr_employee` add `custom2` varchar(250) default null;
alter table `hs_hr_employee` add `custom3` varchar(250) default null;
alter table `hs_hr_employee` add `custom4` varchar(250) default null;
alter table `hs_hr_employee` add `custom5` varchar(250) default null;
alter table `hs_hr_employee` add `custom6` varchar(250) default null;
alter table `hs_hr_employee` add `custom7` varchar(250) default null;
alter table `hs_hr_employee` add `custom8` varchar(250) default null;
alter table `hs_hr_employee` add `custom9` varchar(250) default null;
alter table `hs_hr_employee` add `custom10` varchar(250) default null;
alter table `hs_hr_emp_basicsalary` add `payperiod_code` varchar(13) default null;

alter table `hs_hr_job_title` add key sal_grd_code (`sal_grd_code`);
alter table `hs_hr_job_title` add key jobspec_id (`jobspec_id`);

alter table `hs_hr_geninfo` modify `geninfo_values` varchar(800) default null;
alter table `hs_hr_compstructtree` modify `lft` int(4) not null default '0';
alter table `hs_hr_compstructtree` modify `rgt` int(4) not null default '0';
alter table `hs_hr_emp_basicsalary` modify `ebsal_basic_salary` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT null;
alter table `hs_hr_employee` modify `emp_ssn_num` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '';
alter table `hs_pr_salary_grade` modify `sal_grd_name` varchar(60) default null unique;
alter table `hs_hr_empreport` modify `rep_name` varchar(60) unique default null;

alter table hs_hr_job_title
       add constraint foreign key (jobspec_id)
                             references hs_hr_job_spec(jobspec_id) on delete set null;

alter table hs_hr_emp_directdebit
       add constraint foreign key (emp_number)
                             references hs_hr_employee(emp_number) on delete cascade;

alter table hs_hr_emp_basicsalary
       add constraint foreign key (payperiod_code)
                             references hs_hr_payperiod(payperiod_code) on delete cascade;

alter table hs_hr_emp_us_tax
       add constraint foreign key (emp_number)
                             references hs_hr_employee(emp_number) on delete cascade;

alter table `hs_hr_hsp`
  add constraint foreign key (`employee_id`) references `hs_hr_employee` (`emp_number`) on delete cascade;

alter table `hs_hr_hsp_payment_request`
  add constraint foreign key (`employee_id`) references `hs_hr_employee` (`emp_number`) on delete cascade;

alter table `hs_hr_job_vacancy`
  add constraint foreign key (`manager_id`) references `hs_hr_employee` (`emp_number`) on delete set null,
  add constraint foreign key (jobtit_code) references hs_hr_job_title(jobtit_code) on delete set null;

alter table `hs_hr_job_application`
  add constraint foreign key (`vacancy_id`) references `hs_hr_job_vacancy` (`vacancy_id`) on delete cascade;

alter table `hs_hr_job_application_events`
  add constraint foreign key (`application_id`) references `hs_hr_job_application` (`application_id`) on delete cascade,
  add constraint foreign key (`created_by`) references `hs_hr_users` (`id`) on delete set null,
  add constraint foreign key (`owner`) references `hs_hr_employee` (`emp_number`) on delete set null;

alter table `hs_hr_emp_jobtitle_history`
    add constraint foreign key (`emp_number`)
        references hs_hr_employee(`emp_number`) on delete cascade;

alter table `hs_hr_emp_subdivision_history`
    add constraint foreign key (`emp_number`)
        references hs_hr_employee(`emp_number`) on delete cascade;

alter table `hs_hr_emp_location_history`
    add constraint foreign key (`emp_number`)
        references hs_hr_employee(`emp_number`) on delete cascade;

alter table `hs_hr_emp_locations`
    add constraint foreign key (`loc_code`)
        references hs_hr_location(`loc_code`) on delete cascade,
    add constraint foreign key (`emp_number`)
        references hs_hr_employee(`emp_number`) on delete cascade;