<?php require_once('Connections/test.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "admin_level";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  $encodedpass=md5(strrev(md5($password)));
  mysql_select_db($database_test, $test);
  	
  $LoginRS__query=sprintf("SELECT login, password, admin_level FROM users WHERE login=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($encodedpass, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $test) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'admin_level');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
	$today = date("Y-m-d H:i:s");  	
	$LoginLog = sprintf("INSERT INTO admin_panel_login_logs (Admin, Time) VALUES ('$loginUsername','$today')");
	mysql_select_db($database_test, $test);
	$Result1 = mysql_query($LoginLog, $test) or die(mysql_error());
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin Control Panel</title>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/login.css"/>

</head>

<body>
<div class="center"><a href="../index.php"><!--<img src="imgs/dead.jpg" width="350" height="208" />--></a></div>

<div class="headform">

Taviana Nr.2 Admin Panel</div>
<div id="forma">
  <form action="<?php echo $loginFormAction; ?>" method="POST" enctype="application/x-www-form-urlencoded" name="getin">
    Login:   <input name="username" type="text" /><br />
<br />

    Pass:   <input name="password" type="password" /><br />
<br />

  <input name="submitme" type="submit" value="Log In!" />
  </form>
</div>
</body>
</html>