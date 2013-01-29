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
$query_pl_names = "SELECT * FROM survivor,profile WHERE survivor.unique_id = profile.unique_id AND survivor.is_dead=0";
$pl_names = mysql_query($query_pl_names, $test) or die(mysql_error());
$row_pl_names = mysql_fetch_assoc($pl_names);
$totalRows_pl_names = mysql_num_rows($pl_names);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Loadout test</title>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
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
<?php include('include/menu.php'); ?>




<div class="cheat_content">

PLAYERS LIST<br />


<div class="controls">
  Quick Find: <input type="text" id="quickfind"/>
  <a id="cleanfilters" href="#">Clear Filters</a></div>
  
<table id="playerlist" width="950" border="0">
<thead>
		<tr>
        <th>Player UID</th>
        <th>Player Name</th>
        </tr>        
	</thead>
<?php do { ?>
  <tr>
    <td><a href="loudout_add.php?uid=<?php echo $row_pl_names['id']; ?>"><?php echo $row_pl_names['id']; ?></a></td>
    <td><?php echo $row_pl_names['name']; ?></td>
  </tr>
  <?php } while ($row_pl_names = mysql_fetch_assoc($pl_names)); ?>
</table>



</div>
<div class="foot"></div>
</div>
</body>
</html>
<?php
mysql_free_result($pl_names);
?>
