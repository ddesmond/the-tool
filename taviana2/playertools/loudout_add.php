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
  $updateSQL = sprintf("UPDATE survivor SET inventory=%s, model=%s WHERE id=%s",
                       GetSQLValueString($_POST['inventory'], "text"),
                       GetSQLValueString($_POST['model'], "text"),
                       GetSQLValueString($_POST['id'], "text"));

  mysql_select_db($database_test, $test);
  $Result1 = mysql_query($updateSQL, $test) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_q_p_in = "-1";
if (isset($_GET['uid'])) {
  $colname_q_p_in = $_GET['uid'];
}
mysql_select_db($database_test, $test);
$query_q_p_in = sprintf("SELECT id, unique_id, inventory, is_dead, model FROM survivor WHERE id = %s", GetSQLValueString($colname_q_p_in, "int"));
$q_p_in = mysql_query($query_q_p_in, $test) or die(mysql_error());
$row_q_p_in = mysql_fetch_assoc($q_p_in);
$totalRows_q_p_in = mysql_num_rows($q_p_in);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Loudout test</title>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

</head>

<body>
<div id="bodycontent">

<div class="head"></div>
<?php include('include/menu.php'); ?>




<div class="cheat_content">

PLAYERS LIST add loudout select dropdown<br />
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Inventory:</td>
      <td><select name="inventory">
        <option value="1" <?php if (!(strcmp(1, htmlentities($row_q_p_in['inventory'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Loadout 1</option>
        <option value="2" <?php if (!(strcmp(2, htmlentities($row_q_p_in['inventory'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Loadout 2</option>
        <option value="3" <?php if (!(strcmp(3, htmlentities($row_q_p_in['inventory'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Loadout 3</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Model:</td>
      <td><select name="model">
        <option value="Survivor2_DZ" <?php if (!(strcmp("Survivor2_DZ", htmlentities($row_q_p_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Standard skin</option>
        <option value="Camo1_DZ" <?php if (!(strcmp("Camo1_DZ", htmlentities($row_q_p_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Camo</option>
        <option value="Sniper1_DZ" <?php if (!(strcmp("Sniper1_DZ", htmlentities($row_q_p_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Ghillie</option>
        <option value="Rocket_DZ" <?php if (!(strcmp("Rocket_DZ", htmlentities($row_q_p_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Rocket skin</option>
        <option value="Soldier1_DZ" <?php if (!(strcmp("Soldier1_DZ", htmlentities($row_q_p_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Soldier</option>
        <option value="SurvivorW2_DZ" <?php if (!(strcmp("SurvivorW2_DZ", htmlentities($row_q_p_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Survivor 2</option>
        <option value="Bandit1_DZ" <?php if (!(strcmp("Bandit1_DZ", htmlentities($row_q_p_in['model'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Banditos</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update loadout" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_q_p_in['id']; ?>" />
</form>

</div>
<div class="foot"></div>
</div>
</body>
</html>
<?php
mysql_free_result($q_p_in);


?>
