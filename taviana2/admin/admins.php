<?php require_once('../Connections/test.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_test, $test);
$query_rs_admins = "SELECT id, login, lastlogin FROM users ORDER BY login ASC";
$rs_admins = mysql_query($query_rs_admins, $test) or die(mysql_error());
$row_rs_admins = mysql_fetch_assoc($rs_admins);
$totalRows_rs_admins = mysql_num_rows($rs_admins);
?>
<?php require_once('../restrictions/restrictmeSA.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admins</title>
<link rel="stylesheet" type="text/css" href="../css/reset.css"/>
<link rel="stylesheet" type="text/css" href="../css/admin.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>


</head>

<body>
<div id="bodycontent">

<div class="head"></div>
<?php include('../include/menu.php'); ?>




<div class="cheat_content">

ADMIN LIST<br /><br />
Total Admins: <?php echo $totalRows_rs_admins ?><br />

<br />


    <table id="admins" width="930" border="0" cellspacing="10" cellpadding="10">
      <tr>
        <td>Name</td>
        <td>Delete</td>
      </tr>  
        
  <?php do { ?>
      <tr>
        <td><a href="edit_admin.php?id=<?php echo $row_rs_admins['id']; ?>"><?php echo $row_rs_admins['login']; ?></a></td>
        
<td><a href="admin_delete.php?id=<?php echo $row_rs_admins['id']; ?>">Delete admin from list</a></td>
        </tr>
    <?php } while ($row_rs_admins = mysql_fetch_assoc($rs_admins)); ?>
    </table>
    <div class="add_admin">
    <a href="admin_add.php">Add Administrator</a>
    </div>
    
</div>
<div class="foot"></div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_admins);
?>
