<?php
// Written by Killzone_Kid
// http://killzonekid.com

$cache_file_vehicles_bliss = 'dz_db_cache_vehicles_bliss';

$now = time();

if (!file_exists($cache_file_vehicles_bliss)){

	$already_old = $now - $update_interval - 10;
	touch($cache_file_vehicles_bliss, $already_old);
}

// if cache is older than set interval
if (($now-filemtime($cache_file_vehicles_bliss)) > $update_interval){	

	touch($cache_file_vehicles_bliss);

//start db query
$filter_server_instance = ($server_instance != '')?"WHERE instance_vehicle.instance_id = '$server_instance'\n":"";		
$query = <<<END

SELECT instance_vehicle.id, instance_vehicle.world_vehicle_id, instance_vehicle.worldspace, instance_vehicle.parts AS parts, instance_vehicle.damage, instance_vehicle.fuel,  instance_vehicle.inventory, instance_vehicle.last_updated, vehicle.class_name 
FROM instance_vehicle JOIN world_vehicle ON instance_vehicle.world_vehicle_id = world_vehicle.id
JOIN vehicle ON world_vehicle.vehicle_id = vehicle.id
$filter_server_instance
ORDER BY instance_vehicle.last_updated DESC

END;

	if (!$link = mysql_connect($DB_hostname, $DB_username, $DB_password)){

		mySqlError();
			
	} else {

		mysql_select_db($DB_database, $link);
			 
		if (!$result = mysql_query($query)){
			
			mySqlError();
			 
		} else {
		
			$DB_return_str = "t_up = $now;\n";
			$all_UIDs = array();
			
			while($row = mysql_fetch_array($result)){
				
				$uid = $row['id'];
				$otype = $row['class_name'];
				$lastupdate = $row['last_updated'];
				$timestamp = strtotime($lastupdate);
				$actual_time = $timestamp - ($server_time_offset*60);
				$lastupdate = date("Y-m-d H:i:s",$actual_time);
				$fuel = $row['fuel'];
				$pos = preg_replace('/\\|/',',', $row['worldspace']);
				$damage = preg_replace('/\\|/',',', $row['damage']);
				$health = preg_replace('/\\|/',',', $row['parts']);
				$inventory  = preg_replace('/\\|/',',', $row['inventory']);
				
				if (!in_array($uid, $all_UIDs)) {
				
					$DB_return_str .= "dbData['$uid'] = ['$otype','$pos','$damage','$lastupdate','$timestamp','$fuel','$health','$inventory'];\n";
					array_push($all_UIDs, $uid);
				}
			}
				
			//$DB_return_str .= "document.getElementById('vehicles').value += ' (".count($all_UIDs).")';\n";
			$DB_return_str .= "server_query = '';\n";
			$DB_return_str .= "readyToUpdateIn($update_interval-(t_now - t_up));\n";
		}
			
		mysql_close($link);
			
		file_put_contents ($cache_file_vehicles_bliss, $DB_return_str);
	}
}

function mySqlError(){

	echo "</script>\n<br><span style=\"color:#ffff00;font-weight:bold;\">MySQL ERROR: ".mysql_error()."\n</span><script>";
}

include $cache_file_vehicles_bliss;
?>