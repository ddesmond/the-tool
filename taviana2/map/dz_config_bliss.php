<?php
// Written by Killzone_Kid
// http://killzonekid.com

//server details
$server_ip = ''; //change as nescessary
$server_port = ''; //change as nescessary

//if you run multiple servers make a separate ToolZ install for each one
//in each separate install indicate server instance
//$server_instance = '1'; for example
//if you dont run multiple servers, leave it blank
$server_instance = 1; //change as nescessary

//server map
//currently 5 values accepted
//$server_map = 'Chernarus';
//$server_map = 'Lingor';
//$server_map = 'Panthera';
//$server_map = 'Namalsk';
//$server_map = 'Celle';
//$server_map = 'Taviana';
$server_map = 'Taviana'; //change as nescessary

//time offset minutes between server/database time and time displayed
//if your server is set 1 hour behind from your local time $server_time_offset = -60;
//if your server is set 1 hour ahead from your local time $server_time_offset = 60;
$server_time_offset = 0;

//limit access to database to not more than once every 30 seconds
$update_interval = 10;

//Right clicked boxes hide for 10 seconds
$box_hide_delay = 10;

//do not display players on map if their last update was more than 5 minutes ago (probably logged off)
$last_update_cutoff_min = 5;

//db connect details
$DB_hostname = ''; //change as nescessary
$DB_username = ''; //change as nescessary
$DB_password = ''; //change as nescessary
$DB_database = ''; //change as nescessary
$DB_max_query_players_results = 50;

?>