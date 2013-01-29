<?php require_once('../restrictions/restrictme.php'); ?>
<?php
///////////////////////////////////////////
// PLEASE NOTE!!!!!!!!!!!!!!!!!!!!!!!!!!!!
///////////////////////////////////////////
// TO ENABLE SEARCHING FUNCTIONS OYU MUST ENABLE FULL TEXT SEACRH IN YOUR DB ON INVENTORY AND BACKPACK.
///////////////////////////////////////////







?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search For Items</title>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
<link rel="stylesheet" type="text/css" href="../css/reset.css"/>
<link rel="stylesheet" type="text/css" href="../css/admin.css"/>
</head>

<body>
<div id="bodycontent">

<div class="head"></div>
<?php include('../include/menu.php'); ?>




  <div class="main_content">
  
  <div class="s_string">
  Search all players for any specific item<br /><br />
  <form name="form" action="search.php" method="get">
  <input type="text" name="q" />
  <input type="submit" name="Submit" value="Search" />
</form>
  
  </div>
  

  
  
  </div>

</div>
</body>
</html>
