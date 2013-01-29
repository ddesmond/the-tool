<div class="menu">
<ul>
<li><img src="../imgs/panels/user.png" width="16" height="16" /><a href="../index.php">List players</a></li>
<?php if ($_SESSION['MM_UserGroup'] == 3 ) { // Show if uer is SA?>
<li><img src="../imgs/panels/vehicles.png" width="16" height="16" /><a href="../vehicles/vehicles.php">Vehicles</a></li>
<li><img src="../imgs/panels/maps.png" width="16" height="16" /><a href="../map/index.php" target="_new">Map</a></li>
<li><img src="imgs/panels/maps.png" width="16" height="16" /><a href="teleport/index.php" target="_new">Teleporters</a></li>
<li><img src="../imgs/panels/tools.png" width="16" height="16" /><a href="../admin/admins.php">Admins</a></li>
<?php } // Show if user is SA ?>
<li><img src="../imgs/panels/search.png" width="16" height="16" /><a href="../search/search_cheats.php">Item Checker</a></li>
<!--<li><img src="imgs/panels/search.png" width="16" height="16" /><a href="add_buildings_to_players.php">Add Objects</a></li> REMOVED DUE TO NOT SAFE -->
<li><img src="../imgs/panels/logout.png" width="16" height="16" /><a href="../logout.php">Logout</a></li>

</ul>
<div class="usrlogd">Logged in as: <?php echo $_SESSION['MM_Username']; ?>   <img src="imgs/panels/lock.png" width="16" height="16" /></div>
</div>


<div id="servername">
  <div class="stick_text"><a href="../index.php">Taviana #2<br />    Server</div></a>
</div>
