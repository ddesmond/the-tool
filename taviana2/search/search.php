<?php require_once('../restrictions/restrictme.php'); ?>
<?php
///////////////////////////////////////////
// PLEASE NOTE!!!!!!!!!!!!!!!!!!!!!!!!!!!!
///////////////////////////////////////////
// TO ENABLE SEARCHING FUNCTIONS YOU MUST ENABLE FULL TEXT SEACRH IN YOUR DB ON INVENTORY AND BACKPACK.
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




  <div class="cheat_content">
<?php require_once('../Connections/test.php'); 

 mysql_select_db($database_test, $test);
  // Get the search variable from URL

  if(isset($_GET['q'])) 
	{
	$var = @$_GET['q'];
  $trimmed = trim($var); //trim whitespace from the stored variable
	}
	else
	{
  echo "<p>We dont seem to have a search parameter!</p>";
  exit;
  }
// rows to return
$limit=100; 

// check for an empty string and display a message.
if ($trimmed == "")
  {
  echo "<p>Please enter a search...</p>";
  exit;
  }

// check for a search parameter
if (!isset($var))
  {
  echo "<p>We dont seem to have a search parameter!</p>";
  exit;
  }

// $query = "select * FROM survivor where inventory like \"%$trimmed%\"  
//  order by inventory"; // EDIT HERE and specify your table and field names for the SQL query

$query ="SELECT survivor.id
     , survivor.unique_id
     , survivor.inventory
     , survivor.is_dead
     , profile.id
     , profile.unique_id
     , profile.name
FROM
  survivor
INNER JOIN profile
ON survivor.unique_id = profile.unique_id
WHERE
  survivor.inventory LIKE \"%$trimmed%\"
ORDER BY
  profile.name";


  try {

		$numresults=mysql_query($query) or die($query."<br/><br/>".mysql_error());
		$numrows=mysql_num_rows($numresults);
		
} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
}
  



// If we have no results, offer a google search as an alternative

if ($numrows == 0)
  {
  echo "<h4>Results:</h4><br><br>";
  echo "<p>Sorry, your search: &quot;" . $trimmed . "&quot; returned zero results</p>";

// google
 echo "<p><a href=\"http://www.google.com/search?q=" 
  . $trimmed . "\" target=\"_blank\" title=\"Look up 
  " . $trimmed . " on Google\">Click here</a> to try the 
  search on google</p>";
  }

// next determine if s has been passed to script, if not use 0
  if (empty($s)) {
  $s=0;
  }

// get results
  $query .= " limit $s,$limit";
  $result = mysql_query($query) or die("Couldn't execute query");

// display what the person searched for
echo "<p>You searched for: &quot;" . $var . "&quot;</p>";


$count = 1 + $s ;
// begin to show results set
echo "Items Found:  $numrows";


// now you can display the results returned
  while ($row= mysql_fetch_array($result)) {
  $title = $row["unique_id"];
$namers = $row["name"];
$whole_string = '<br /><br /><a href="../playertools/character_list.php?uid='.$title.'">'.$title.'</a> - '.$namers.'<br />';

echo $whole_string;


  echo "" ;
  $count++ ;
  }

$currPage = (($s/$limit) + 1);

//break before paging
  echo "<br /><br />";

  // next we need to do the links to other results
  if ($s>=1) { // bypass PREV link if s is 0
  $prevs=($s-$limit);
  print "&nbsp;<a href=\"$PHP_SELF?s=$prevs&q=$var\">&lt;&lt; 
  Prev 10</a>&nbsp&nbsp;";
  }

// calculate number of pages needing links
  $pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division

  if ($numrows%$limit) {
  // has remainder so add one page
  $pages++;
  }

// check to see if last page
  if (!((($s+$limit)/$limit)==$pages) && $pages!=1) {

  // not last page so give NEXT link
  $news=$s+$limit;

 // echo "&nbsp;<a href=\"'$PHP_SELF'?s=$news&q=$var\">Next 10 &gt;&gt;</a>";
  }

$a = $s + ($limit) ;
  if ($a > $numrows) { $a = $numrows ; }
  $b = $s + 1 ;
  echo "<br /><p>Showing results $b to $a of $numrows</p>";
  
?>
  
  </div>

</div>
</body>
</html>
