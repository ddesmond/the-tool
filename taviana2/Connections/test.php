<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_test = "";
$database_test = "";
$username_test = "";
$password_test = "";
$test = mysql_pconnect($hostname_test, $username_test, $password_test) or trigger_error(mysql_error(),E_USER_ERROR); 
?>