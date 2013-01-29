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

mysql_select_db($database_test, $test);
$query_vehs = "SELECT instance_vehicle.id, instance_vehicle.world_vehicle_id as vehicle_id, instance_vehicle.worldspace, instance_vehicle.parts AS parts, instance_vehicle.damage, instance_vehicle.fuel,  instance_vehicle.inventory, instance_vehicle.last_updated, vehicle.class_name 
FROM instance_vehicle JOIN world_vehicle ON instance_vehicle.world_vehicle_id = world_vehicle.id
JOIN vehicle ON world_vehicle.vehicle_id = vehicle.id WHERE instance_id = 1 ORDER BY id ASC";
$vehs = mysql_query($query_vehs, $test) or die(mysql_error());
$row_vehs = mysql_fetch_assoc($vehs);
$totalRows_vehs = mysql_num_rows($vehs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Vehicles List</title>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
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




<div class="main_content">
<b>Vehicle List</b><br />


  
<table id="playerlist" width="950" border="0">
<thead>
		<tr>
        <th>Vehicle ID</th>
        <th>Vehicle Name</th>
        <th>Vehicle Vehicle_ID</th>
        <th>Damage</th>
		<th>Fuel</th>
        </tr>        
	</thead>
<?php do { ?>
  <tr>
    <td><a href="vehicle_list.php?id=<?php echo $row_vehs['id']; ?>"><?php echo $row_vehs['id']; ?></a></td>
    <td><?php echo $row_vehs['class_name']; ?></td>
    <td><?php echo $row_vehs['vehicle_id']; ?></td>
    <td><?php echo $row_vehs['damage']; ?></td>
	<td><?php echo $row_vehs['fuel']; ?></td>
  </tr>
  <?php } while ($row_vehs = mysql_fetch_assoc($vehs)); ?>
</table>



</div>
<div class="foot"></div>
</div>
</body>
</html>
<?php
mysql_free_result($vehs);
?>
