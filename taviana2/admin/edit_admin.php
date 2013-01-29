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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE users SET login=%s, password=%s, salt=%s, lastlogin=%s, admin_level=%s WHERE id=%s",
                       GetSQLValueString($_POST['login'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['salt'], "text"),
                       GetSQLValueString($_POST['lastlogin'], "date"),
                       GetSQLValueString($_POST['admin_level'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_test, $test);
  $Result1 = mysql_query($updateSQL, $test) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_GET['id'])) {
  $colname_Recordset1 = $_GET['id'];
}
mysql_select_db($database_test, $test);
$query_Recordset1 = sprintf("SELECT * FROM users WHERE id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $test) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php require_once('../restrictions/restrictmeSA.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin edit</title>
<link rel="stylesheet" type="text/css" href="../css/reset.css"/>
<link rel="stylesheet" type="text/css" href="../css/admin.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="../js/picnet.table.filter.min.js"></script>
<script type="text/javascript">

		$(document).ready(function() {
			// Initialise Plugin
		var options = {
				additionalFilterTriggers: [$('#quickfind')],
				clearFiltersControls: [$('#cleanfilters')],
				};
			$('#playerlist').tableFilter(options);
		});

	</script>

</head>

<body>
<div id="bodycontent">

<div class="head"></div>
<?php include('../include/menu.php'); ?>




<div class="cheat_content">Edit Admin<br />
<br />
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Login:</td>
      <td><input type="text" name="login" value="<?php echo htmlentities($row_Recordset1['login'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Password:</td>
      <td><input type="password" name="password" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Admin_level:</td>
      <td><select name="admin_level">
        <option value="3" <?php if (!(strcmp(3, htmlentities($row_Recordset1['admin_level'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>SA</option>
        <option value="1" <?php if (!(strcmp(1, htmlentities($row_Recordset1['admin_level'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>GA</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="id" value="<?php echo $row_Recordset1['id']; ?>" />
  <input type="hidden" name="salt" value="<?php echo htmlentities($row_Recordset1['salt'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="lastlogin" value="<?php echo htmlentities($row_Recordset1['lastlogin'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_Recordset1['id']; ?>" />
</form>
<p>&nbsp;</p>
<br /><br />

    

</div>
<div class="foot"></div>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);


?>
