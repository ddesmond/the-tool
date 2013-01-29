<?php
// *** Logout the current user.
$logoutGoTo = "index.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
$_SESSION['MM_Username_Private'] = NULL;
$_SESSION['MM_Username_Private'] = NULL;
unset($_SESSION['MM_Username_Private']);
unset($_SESSION['MM_Username_Private']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
