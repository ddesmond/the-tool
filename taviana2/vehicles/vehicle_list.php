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
//Create double table update statement.
//change fields to match DB
  $updateSQL = sprintf("UPDATE instance_vehicle SET worldspace=%s, inventory=%s, parts=%s, fuel=%s, damage=%s, instance_id=%s, last_updated=%s, created=%s WHERE id=%s",
                       GetSQLValueString($_POST['worldspace'], "text"),
                       GetSQLValueString($_POST['inventory'], "text"),
                       GetSQLValueString($_POST['parts'], "text"),
                       GetSQLValueString($_POST['fuel'], "double"),
                       GetSQLValueString($_POST['damage'], "double"),
                       GetSQLValueString($_POST['instance'], "int"),
                       GetSQLValueString($_POST['last_updated'], "date"),
                       GetSQLValueString($_POST['created'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_test, $test);
  $Result1 = mysql_query($updateSQL, $test) or die(mysql_error());

  
  
  $updateGoTo = "vehicles.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rs_veh_in = "-1";
if (isset($_GET['id'])) {
  $colname_rs_veh_in = $_GET['id'];
}
mysql_select_db($database_test, $test);
$query_rs_veh_in = sprintf("SELECT instance_vehicle.id, instance_vehicle.world_vehicle_id, instance_vehicle.instance_id, instance_vehicle.worldspace, instance_vehicle.parts AS parts, instance_vehicle.damage, instance_vehicle.created, instance_vehicle.fuel, instance_vehicle.inventory as inventory, instance_vehicle.last_updated, vehicle.class_name 
FROM instance_vehicle JOIN world_vehicle ON instance_vehicle.world_vehicle_id = world_vehicle.id
JOIN vehicle ON world_vehicle.vehicle_id = vehicle.id WHERE instance_vehicle.id = %s", GetSQLValueString($colname_rs_veh_in, "int"));
$rs_veh_in = mysql_query($query_rs_veh_in, $test) or die(mysql_error());
$row_rs_veh_in = mysql_fetch_assoc($rs_veh_in);
$totalRows_rs_veh_in = mysql_num_rows($rs_veh_in);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Vehicle Editor</title>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
<link rel="stylesheet" type="text/css" href="../css/reset.css"/>
<link rel="stylesheet" type="text/css" href="../css/admin.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="js/picnet.table.filter.min.js"></script>
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




<div class="main_content">
<b>Vehicle Details</b><br /><br />
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Pos:</td>
      <td><input type="text" name="worldspace" value="<?php echo htmlentities($row_rs_veh_in['worldspace'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Inventory:</td>
      <td><textarea name="inventory" cols="50" rows="8"><?php echo htmlentities($row_rs_veh_in['inventory'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Health:</td>
      <td><textarea cols="50" rows="8" name="parts" size="32" /><?php echo $row_rs_veh_in['parts']; ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fuel:</td>
      <td><input type="text" name="fuel" value="<?php echo htmlentities($row_rs_veh_in['fuel'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Damage:</td>
      <td><input type="text" name="damage" value="<?php echo htmlentities($row_rs_veh_in['damage'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Name:</td>
      <td><input type="text" readOnly="true" name="otype" value="<?php echo htmlentities($row_rs_veh_in['class_name'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Instance:</td>
      <td><input type="text" name="instance" readOnly="true" value="<?php echo htmlentities($row_rs_veh_in['instance_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Lastupdate:</td>
      <td><input type="text" name="last_updated" readOnly="true" value="<?php echo htmlentities($row_rs_veh_in['last_updated'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Created:</td>
      <td><input type="text" name="created" readOnly="true" value="<?php echo htmlentities($row_rs_veh_in['created'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update vehicle" /></td>
    </tr>
  </table>
  <input type="hidden" name="id" value="<?php echo $row_rs_veh_in['id']; ?>" />
  <input type="hidden" name="uid" value="<?php echo htmlentities($row_rs_veh_in['uid'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_rs_veh_in['id']; ?>" />
</form>
<p>&nbsp;</p>
</div>
<div class="foot"></div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_veh_in);

?>
