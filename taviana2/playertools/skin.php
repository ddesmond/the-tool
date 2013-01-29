<?php require_once('../Connections/test.php'); ?>
<?php require_once('../restrictions/restrictme.php'); ?>
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
	$unique_id = $_POST['unique_id'];
	$admin = $_SESSION['MM_Username'];
	$new_model = $_POST['model'];
	$today = date("Y-m-d H:i:s"); 
	
	mysql_select_db($database_test, $test);
	$query_pl_in = sprintf("SELECT survivor.id, survivor.unique_id, survivor.model AS model, profile.name AS pname FROM survivor JOIN profile ON survivor.unique_id = profile.unique_id WHERE survivor.unique_id ='$unique_id' AND survivor.is_dead=0");
	$pl_in = mysql_query($query_pl_in, $test) or die(mysql_error());
	$row_pl_in = mysql_fetch_assoc($pl_in);
	$totalRows_pl_in = mysql_num_rows($pl_in);
	
	$old_model = $row_pl_in['model'];
	$player_name = $row_pl_in['pname'];
		
	$SkinLog = sprintf("INSERT INTO admin_panel_skinchange_logs (Admin, PlayerID, PlayerName, OldSkin, NewSkin, Time) VALUES ('$admin','$unique_id','$player_name','$old_model','$new_model','$today')");
	mysql_select_db($database_test, $test);
	$Result1 = mysql_query($SkinLog, $test) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE survivor SET unique_id=%s, model=%s WHERE id=%s",
                       GetSQLValueString($_POST['unique_id'], "text"),
                       GetSQLValueString($_POST['model'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_test, $test);
  $Result1 = mysql_query($updateSQL, $test) or die(mysql_error());
  
  
  $updateGoTo = "../index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));

}

$colname_pl_in = "-1";
if (isset($_GET['uid'])) {
  $colname_pl_in = $_GET['uid'];
}
mysql_select_db($database_test, $test);
$query_pl_in = sprintf("SELECT id, unique_id, is_dead, model FROM survivor WHERE unique_id = %s AND is_dead=0", GetSQLValueString($colname_pl_in, "text"));
$pl_in = mysql_query($query_pl_in, $test) or die(mysql_error());
$row_pl_in = mysql_fetch_assoc($pl_in);
$totalRows_pl_in = mysql_num_rows($pl_in);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Skin changer</title>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
<link rel="stylesheet" type="text/css" href="../css/reset.css"/>
<link rel="stylesheet" type="text/css" href="../css/admin.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
</head>

<body>
<div id="bodycontent">

<div class="head"></div>
<?php include('../include/menu.php'); ?>


<div class="cheat_content">

Player ID : <?php echo $row_pl_in['unique_id']; ?><br />
<br />

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="left">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Skin:</td>
      <td><select name="model">
        <option value="Survivor2_DZ" <?php if (!(strcmp("Survivor2_DZ", htmlentities($row_pl_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Standard</option>
        <option value="Rocket_DZ" <?php if (!(strcmp("Rocket_DZ", htmlentities($row_pl_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Rocket</option>
        <option value="Soldier1_DZ" <?php if (!(strcmp("Soldier1_DZ", htmlentities($row_pl_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Soldier</option>
        <option value="SurvivorW2_DZ" <?php if (!(strcmp("SurvivorW2_DZ", htmlentities($row_pl_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Soldier 2</option>
        <option value="Bandit1_DZ" <?php if (!(strcmp("Bandit1_DZ", htmlentities($row_pl_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Bandit</option>
        <option value="Camo1_DZ" <?php if (!(strcmp("Camo1_DZ", htmlentities($row_pl_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Camo clothing</option>
        <option value="Sniper1_DZ" <?php if (!(strcmp("Sniper1_DZ", htmlentities($row_pl_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Ghilie</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update skin" /></td>
    </tr>
  </table>
  <input type="hidden" name="unique_id" value="<?php echo htmlentities($row_pl_in['unique_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_pl_in['id']; ?>" />
</form>
<p>&nbsp;</p>
</div>
<div class="foot"></div>
</div>
</body>
</html>
<?php
mysql_free_result($pl_in);
?>
