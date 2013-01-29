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

$maxRows_q_playerlist = 4;
$pageNum_q_playerlist = 0;
if (isset($_GET['pageNum_q_playerlist'])) {
  $pageNum_q_playerlist = $_GET['pageNum_q_playerlist'];
}
$startRow_q_playerlist = $pageNum_q_playerlist * $maxRows_q_playerlist;

$colname_q_playerlist = "-1";
if (isset($_GET['uid'])) {
  $colname_q_playerlist = $_GET['uid'];
}
mysql_select_db($database_test, $test);
$query_q_playerlist = sprintf("SELECT id, unique_id, is_dead FROM survivor WHERE unique_id = %s ORDER BY id DESC", GetSQLValueString($colname_q_playerlist, "text"));
$query_limit_q_playerlist = sprintf("%s LIMIT %d, %d", $query_q_playerlist, $startRow_q_playerlist, $maxRows_q_playerlist);
$q_playerlist = mysql_query($query_limit_q_playerlist, $test) or die(mysql_error());
$row_q_playerlist = mysql_fetch_assoc($q_playerlist);

if (isset($_GET['totalRows_q_playerlist'])) {
  $totalRows_q_playerlist = $_GET['totalRows_q_playerlist'];
} else {
  $all_q_playerlist = mysql_query($query_q_playerlist);
  $totalRows_q_playerlist = mysql_num_rows($all_q_playerlist);
}
$totalPages_q_playerlist = ceil($totalRows_q_playerlist/$maxRows_q_playerlist)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Character inspector</title>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
<link rel="stylesheet" type="text/css" href="../css/reset.css"/>
<link rel="stylesheet" type="text/css" href="../css/admin.css"/>
</head>

<body>
<div id="bodycontent">
<div class="head"></div>
<?php include('../include/menu.php'); ?>



<div class="main_content">


<?php do { ?>
  <div class="char_list">

<a href="player_profile.php?cuid=<?php echo $row_q_playerlist['id']; ?>">Check player inventory</a><br /><br />

PlayerUID: <?php echo $row_q_playerlist['unique_id']; ?><br />
Dead : <?php $wht = $row_q_playerlist['is_dead']; if ($wht=="0")  {  echo "No";  }elseif ($wht=="1")  {  echo "Yes";  } ?><br />

<?php if ($_SESSION['MM_UserGroup'] == 3 ) { // Show if uer is SA?><br />
<a href="edit_char.php?cuid=<?php echo $row_q_playerlist['id']; ?>">Edit Loadout</a><br />
<?php } // Show if user is SA ?>
<br />
<!--<a href="whitelistme.php?pid=<?php echo $row_q_playerlist['unique_id']; ?>">Whitelist player</a> UNCOMMENT FOR PUBLICS/WHITELIST-->
  <div class="char_back_img"></div>
</div> 
<?php } while ($row_q_playerlist = mysql_fetch_assoc($q_playerlist)); ?>



</div>
</div>
</body>
</html>
<?php
mysql_free_result($q_playerlist);
?>
