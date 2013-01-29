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

	$admin = $_SESSION['MM_Username'];
	$today = date("Y-m-d H:i:s"); 
	$sid = $_POST['id'];
	
	mysql_select_db($database_test, $test);
	$query_pl_in = sprintf("SELECT survivor.id, survivor.unique_id, survivor.worldspace as worldspace, survivor.inventory as inventory, survivor.backpack as backpack, survivor.model AS model, survivor.medical as medical, profile.name AS pname FROM survivor JOIN profile ON survivor.unique_id = profile.unique_id WHERE survivor.id ='$sid'");
	$pl_in = mysql_query($query_pl_in, $test) or die(mysql_error());
	$row_pl_in = mysql_fetch_assoc($pl_in);
	$totalRows_pl_in = mysql_num_rows($pl_in);
	
	$old_worldspace = $row_pl_in['worldspace'];
	$old_inventory = $row_pl_in['inventory'];
	$old_backpack = $row_pl_in['backpack'];
	$old_medical = $row_pl_in['medical'];
	$old_model = $row_pl_in['model'];
	$player_name = $row_pl_in['pname'];
	$unique_id = $row_pl_in['unique_id'];
	
	$new_worldspace = $_POST['worldspace'];
	$new_inventory = $_POST['inventory'];
	$new_backpack = $_POST['backpack'];
	$new_medical = $_POST['medical'];
	$new_model = $_POST['model'];
	
	mysql_select_db($database_test, $test);
	$SkinLog = sprintf("INSERT INTO admin_panel_loadout_logs (Time, Admin, PlayerID, PlayerName, OldPos, NewPos, OldInventory, NewInventory, OldBackpack, NewBackpack, OldMedical, NewMedical, OldModel, NewModel) VALUES ('$today','$admin','$unique_id','$player_name','$old_worldspace','$new_worldspace','$old_inventory','$new_inventory','$old_backpack','$new_backpack','$old_medical','$new_medical','$old_model','$new_model')");
	$Result1 = mysql_query($SkinLog, $test) or die(mysql_error());

  $updateSQL = sprintf("UPDATE survivor SET unique_id=%s, worldspace=%s, inventory=%s, backpack=%s, medical=%s, is_dead=%s, model=%s, `state`=%s, survivor_kills=%s, bandit_kills=%s, zombie_kills=%s, headshots=%s, last_ate=%s, last_drank=%s, survival_time=%s, last_updated=%s, start_time=%s WHERE id=%s",
                       GetSQLValueString($_POST['unique_id'], "text"),
                       GetSQLValueString($_POST['worldspace'], "text"),
                       GetSQLValueString($_POST['inventory'], "text"),
                       GetSQLValueString($_POST['backpack'], "text"),
                       GetSQLValueString($_POST['medical'], "text"),
                       GetSQLValueString($_POST['is_dead'], "int"),
                       GetSQLValueString($_POST['model'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['survivor_kills'], "int"),
                       GetSQLValueString($_POST['bandit_kills'], "int"),
                       GetSQLValueString($_POST['zombie_kills'], "int"),
                       GetSQLValueString($_POST['headshots'], "int"),
                       GetSQLValueString($_POST['last_ate'], "int"),
                       GetSQLValueString($_POST['last_drank'], "int"),
                       GetSQLValueString($_POST['survival_time'], "int"),
                       GetSQLValueString($_POST['last_updated'], "date"),
                       GetSQLValueString($_POST['start_time'], "date"),
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

$colname_char_edit_q = "-1";
if (isset($_GET['cuid'])) {
  $colname_char_edit_q = $_GET['cuid'];
}
mysql_select_db($database_test, $test);
$query_char_edit_q = sprintf("SELECT * FROM survivor WHERE id = %s", GetSQLValueString($colname_char_edit_q, "int"));
$char_edit_q = mysql_query($query_char_edit_q, $test) or die(mysql_error());
$row_char_edit_q = mysql_fetch_assoc($char_edit_q);
$totalRows_char_edit_q = mysql_num_rows($char_edit_q);$colname_char_edit_q = "-1";
if (isset($_GET['cuid'])) {
  $colname_char_edit_q = $_GET['cuid'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Character</title>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
<link rel="stylesheet" type="text/css" href="../css/reset.css"/>
<link rel="stylesheet" type="text/css" href="../css/admin.css"/>
</head>

<body>
<div id="bodycontent">

<div class="head"></div>
<?php include('../include/menu.php'); ?>


<div class="main_content">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Pos:</td>
        <td><input type="text" name="worldspace" value="<?php echo htmlentities($row_char_edit_q['worldspace'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top">Inventory:</td>
        <td><textarea name="inventory" cols="50" rows="5"><?php echo htmlentities($row_char_edit_q['inventory'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top">Backpack:</td>
        <td><textarea name="backpack" cols="50" rows="5"><?php echo htmlentities($row_char_edit_q['backpack'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Medical:</td>
        <td><input type="text" name="medical" value="<?php echo htmlentities($row_char_edit_q['medical'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Is_dead:</td>
        <td><input type="text" name="is_dead" value="<?php echo htmlentities($row_char_edit_q['is_dead'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Model:</td>
        <td><input type="text" name="model" value="<?php echo htmlentities($row_char_edit_q['model'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">State:</td>
        <td><input type="text" name="state" value="<?php echo htmlentities($row_char_edit_q['state'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Survivor_kills:</td>
        <td><input type="text" name="survivor_kills" value="<?php echo htmlentities($row_char_edit_q['survivor_kills'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Bandit_kills:</td>
        <td><input type="text" name="bandit_kills" value="<?php echo htmlentities($row_char_edit_q['bandit_kills'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Zombie_kills:</td>
        <td><input type="text" name="zombie_kills" value="<?php echo htmlentities($row_char_edit_q['zombie_kills'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Headshots:</td>
        <td><input type="text" name="headshots" value="<?php echo htmlentities($row_char_edit_q['headshots'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Last_ate:</td>
        <td><input type="text" name="last_ate" value="<?php echo htmlentities($row_char_edit_q['last_ate'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Last_drank:</td>
        <td><input type="text" name="last_drank" value="<?php echo htmlentities($row_char_edit_q['last_drank'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Survival_time:</td>
        <td><input type="text" name="survival_time" value="<?php echo htmlentities($row_char_edit_q['survival_time'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Last_update:</td>
        <td><input type="text" name="last_update" value="<?php echo htmlentities($row_char_edit_q['last_updated'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Start_time:</td>
        <td><input type="text" name="start_time" value="<?php echo htmlentities($row_char_edit_q['start_time'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Update Character" /></td>
      </tr>
    </table>
    <input type="hidden" name="id" value="<?php echo $row_char_edit_q['id']; ?>" />
    <input type="hidden" name="unique_id" value="<?php echo htmlentities($row_char_edit_q['unique_id'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="id" value="<?php echo $row_char_edit_q['id']; ?>" />
  </form>
  <p>&nbsp;</p>
</div>


</div>
</body>
</html>
<?php
mysql_free_result($char_edit_q);
?>
