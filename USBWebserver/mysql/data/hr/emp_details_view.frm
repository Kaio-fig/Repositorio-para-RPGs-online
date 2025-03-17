TYPE=VIEW
query=select `e`.`employee_id` AS `employee_id`,`e`.`job_id` AS `job_id`,`e`.`manager_id` AS `manager_id`,`e`.`department_id` AS `department_id`,`d`.`location_id` AS `location_id`,`l`.`country_id` AS `country_id`,`e`.`first_name` AS `first_name`,`e`.`last_name` AS `last_name`,`e`.`salary` AS `salary`,`e`.`commission_pct` AS `commission_pct`,`d`.`department_name` AS `department_name`,`j`.`job_title` AS `job_title`,`l`.`city` AS `city`,`l`.`state_province` AS `state_province`,`c`.`country_name` AS `country_name`,`r`.`region_name` AS `region_name` from `hr`.`employees` `e` join `hr`.`departments` `d` join `hr`.`jobs` `j` join `hr`.`locations` `l` join `hr`.`countries` `c` join `hr`.`regions` `r` where ((`e`.`department_id` = `d`.`department_id`) and (`d`.`location_id` = `l`.`location_id`) and (`l`.`country_id` = `c`.`country_id`) and (`c`.`region_id` = `r`.`region_id`) and (`j`.`job_id` = `e`.`job_id`))
md5=593f066064c5c40800dac4ba15fe906e
updatable=1
algorithm=0
definer_user=root
definer_host=localhost
suid=2
with_check_option=0
timestamp=2020-06-30 12:14:52
create-version=1
source=SELECT e.employee_id,\n	e.job_id,\n	e.manager_id,\n	e.department_id,\n	d.location_id,\n	l.country_id,\n	e.first_name,\n	e.last_name,\n	e.salary,\n	e.commission_pct,\n	d.department_name,\n	j.job_title,\n	l.city,\n	l.state_province,\n	c.country_name,\n	r.region_name\nFROM employees e,\n	departments d,\n	jobs j,\n	locations l,\n	countries c,\n	regions r\nWHERE e.department_id = d.department_id\n	AND d.location_id = l.location_id\n	AND l.country_id = c.country_id\n	AND c.region_id = r.region_id\n	AND j.job_id = e.job_id
client_cs_name=utf8
connection_cl_name=utf8_general_ci
view_body_utf8=select `e`.`employee_id` AS `employee_id`,`e`.`job_id` AS `job_id`,`e`.`manager_id` AS `manager_id`,`e`.`department_id` AS `department_id`,`d`.`location_id` AS `location_id`,`l`.`country_id` AS `country_id`,`e`.`first_name` AS `first_name`,`e`.`last_name` AS `last_name`,`e`.`salary` AS `salary`,`e`.`commission_pct` AS `commission_pct`,`d`.`department_name` AS `department_name`,`j`.`job_title` AS `job_title`,`l`.`city` AS `city`,`l`.`state_province` AS `state_province`,`c`.`country_name` AS `country_name`,`r`.`region_name` AS `region_name` from `hr`.`employees` `e` join `hr`.`departments` `d` join `hr`.`jobs` `j` join `hr`.`locations` `l` join `hr`.`countries` `c` join `hr`.`regions` `r` where ((`e`.`department_id` = `d`.`department_id`) and (`d`.`location_id` = `l`.`location_id`) and (`l`.`country_id` = `c`.`country_id`) and (`c`.`region_id` = `r`.`region_id`) and (`j`.`job_id` = `e`.`job_id`))
