<?php require_once('Connections/test.php'); ?>
<?php require_once('restrictions/restrictmeIndex.php'); ?>

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
$query_pl_names = "SELECT * FROM survivor,profile WHERE survivor.unique_id = profile.unique_id AND survivor.is_dead = 0";
$pl_names = mysql_query($query_pl_names, $test) or die(mysql_error());
$row_pl_names = mysql_fetch_assoc($pl_names);
$totalRows_pl_names = mysql_num_rows($pl_names);

//$wht = $row_pl_names['is_whitelisted'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Home</title>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="js/picnet.table.filter.min.js"></script>
<script type="text/javascript">

		$(document).ready(function() {
			// Initialise Plugin
		var options = {
				additionalFilterTriggers: [$('#onlyyes'), $('#onlyno'), $('#quickfind')],
				clearFiltersControls: [$('#cleanfilters')],
			       matchingRow: function(state, tr, textTokens) {
                  if (!state || !state.id) {
                    return true;
                  }
                  var child = tr.children('td:eq(2)');
                  if (!child) return true;
                  var val = child.text();
                  switch (state.id) {
                  case 'onlyyes':
                    return state.value !== true || val === 'yes';
                  case 'onlyno':
                    return state.value !== true || val === 'no';
                  default:
                    return true;
                  }
                }
            };

			$('#playerlist').tableFilter(options);
		});

	</script>
    <?php if ($_SESSION['MM_UserGroup'] == 1 ) { // Show if recordset empty ?>
<?php include('include/js_popup.php'); ?>

  <?php } // Show if recordset empty ?>
</head>

<body>
<?php if ($_SESSION['MM_UserGroup'] == 1 ) { // Show if user is GA ?>
<div id="boxes">
    <div style="display: none;" id="dialog" class="window">
      <center><h2>WARNING<br /> Any editing on players should only be done AFTER the player has been in the lobby for 1 minute! Otherwise there is a chance of corrupting the player profile!<br>If you are not sure about what you are doing, are going to do, please ask someone who knows for help!
  <br /></h2></center><br />
      <br />
      <a href="#" class="close">Close it</a>
    </div>
    <!-- Mask to cover the whole screen -->
    <div style="width: 1478px; height: 602px; display: none; opacity: 0.8;" id="mask"></div>
  </div>
  <?php } // show if user is GA ?>
<div id="bodycontent">

<div class="head"></div>
<?php include('include/mainmenu.php'); ?>




<div class="cheat_content">


  <center><h2>WARNING<br /> Any editing on players should only be done AFTER the player has been in the lobby for 1 minute! Otherwise there is a chance of corrupting the player profile!<br>If you are not sure about what you are doing, are going to do, please ask someone who knows for help!
  <br /></h2></center><br />
  Total players alive: <?php echo $totalRows_pl_names ?><br />
  
  

<div class="controls">
  <h5>Quick Find:
  <input type="text" id="quickfind"/>
    <a id="cleanfilters" href="#">Clear Filters</a><br />
  <br />
    Only Show Whitelisted: <input type="checkbox" id="onlyyes"/>     
    <br />
    Only Show Non-Whitelisted: <input type="checkbox" id="onlyno"/>    
	
  </h5>
</div>
  <br />
<table id="playerlist" width="950" border="0">
<thead>
		<tr>
        <th width="200">Player UID</th>
        <th width="220">Player Name</th>
        <th width="140">Whitelisted</th>
        <th width="120">Skin Change</th>
        </tr>        
	</thead>
<?php do { ?>
  <tr>
    <td><a href="playertools/character_list.php?uid=<?php echo $row_pl_names['unique_id']; ?>"><?php echo $row_pl_names['unique_id']; ?></a></td>
    <td><?php echo $row_pl_names['name']; ?></td>
	<td><?php echo "Under construction"; ?></td>
	<!--<td><a class="<?php $wht = $row_pl_names['is_whitelisted']; if ($wht=="0")  {  echo "no";  }elseif ($wht=="1")  {  echo "yes";  } ?>" href="whitelistme.php?pid=<?php echo $row_pl_names['unique_id']; ?>"><?php $wht = $row_pl_names['is_whitelisted']; if ($wht=="0")  {  echo "no";  }elseif ($wht=="1")  {  echo "yes";  } ?></a></td>-->
	<td><a class="skin" href="playertools/skin.php?uid=<?php echo $row_pl_names['unique_id']; ?>">Skin change</a></td>
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
