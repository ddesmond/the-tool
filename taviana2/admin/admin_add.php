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


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$encodedpass=md5(strrev(md5($_POST['password'])));
  $insertSQL = sprintf("INSERT INTO users (login, password, admin_level) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['login'], "text"),
                       GetSQLValueString($encodedpass, "text"),
                       GetSQLValueString($_POST['admin_level'], "int"));

  mysql_select_db($database_test, $test);
  $Result1 = mysql_query($insertSQL, $test) or die(mysql_error());

  $insertGoTo = "admins.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php require_once('../restrictions/restrictmeSA.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Admins</title>
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




<div class="cheat_content"> Add Admin



<br />
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Login:</td>
      <td><input type="text" name="login" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Password:</td>
      <td><input type="password" name="password" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Admin level:</td>
      <td><select name="admin_level">
        <option value="3" <?php if (!(strcmp(3, ""))) {echo "SELECTED";} ?>>SuperAdmin</option>
        <option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>>Game Admin</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<br />

    
    
</div>
<div class="foot"></div>
</div>
</body>
</html>