TYPE=VIEW
query=select `b`.`department_name` AS `DEPARTMENT_NAME`,avg(`a`.`salary`) AS `AVG(SALARY)` from `hr`.`employees` `a` join `hr`.`departments` `b` where (`a`.`department_id` = `b`.`department_id`) group by `b`.`department_name` order by `b`.`department_name`
md5=1c4dca9bc9045d49c8fcb4bb774e92b9
updatable=0
algorithm=0
definer_user=root
definer_host=localhost
suid=2
with_check_option=0
timestamp=2020-08-17 17:38:22
create-version=1
source=SELECT B.DEPARTMENT_NAME, AVG(SALARY)\nFROM EMPLOYEES A, DEPARTMENTS B\nWHERE A.DEPARTMENT_ID= B.DEPARTMENT_ID\nGROUP BY B.DEPARTMENT_NAME\nORDER BY B.DEPARTMENT_NAME
client_cs_name=utf8
connection_cl_name=utf8_general_ci
view_body_utf8=select `b`.`department_name` AS `DEPARTMENT_NAME`,avg(`a`.`salary`) AS `AVG(SALARY)` from `hr`.`employees` `a` join `hr`.`departments` `b` where (`a`.`department_id` = `b`.`department_id`) group by `b`.`department_name` order by `b`.`department_name`
