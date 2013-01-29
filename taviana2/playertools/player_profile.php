<?php require_once('../Connections/test.php'); ?>
<?php require_once('../restrictions/restrictme.php'); ?>
<?php
// thanks to mariogaljanic //
function createArrFromString($string, $type='Inventory'){
    //echo "<h1>we are in:$type</h1>";
    
    if(isset($arr)){
        unset ($arr);
    }
    $arr = array();
    //substring first left and first right bracket
    $string = substr($string, 1, -1);  
    

    $stringArr = str_split($string); 
    
if($type=='Inventory'){
    //echo "We are using Inventory";
    
    $bracketLevel =0;
    $charCounter =0;
    $stringWithLevels = "";
    foreach($stringArr as $char){
    
        if($char == '[' && $bracketLevel == 0){
            $stringArr[$charCounter] = 'BOL1['; //beginning of level 1
            $bracketLevel++;
        }else if($char == ']' && $bracketLevel == 1){
            $stringArr[$charCounter] = ']EOL1';
            $bracketLevel--;
        }else if($char == ',' && $bracketLevel == 2){
            $stringArr[$charCounter] = '~~';
            
        }else if($char == ']' && $bracketLevel == 2){
            $stringArr[$charCounter] = ']EOL2';
            $bracketLevel--;
        }else if($char == '[' && $bracketLevel == 1){
            $stringArr[$charCounter] = 'BOL2[';
            $bracketLevel++;
        }
        
        
        //echo '<span title="'.$bracketLevel.'">'.$stringArr[$charCounter].'</span>';
       
        $stringWithLevels .= $stringArr[$charCounter];
        $charCounter++; //at the end of string, increase counter!
    }
    
    //echo '<hr>'.$stringWithLevels.'<hr>';
    
    $outerARR = explode("]EOL1,BOL1[", $stringWithLevels);
    $arr = array();
    foreach ($outerARR as $arrvalue){
        $innerarr = explode(',', $arrvalue);
        for($i=0; $i<count($innerarr); $i++){
            if(substr($innerarr[$i], 0,5) == 'BOL2['){
                //string replace
                $innerarr[$i] = explode("~~", substr($innerarr[$i], 5, -5));
            }
        }
        
        $innerarr = str_replace ("BOL1[", "", $innerarr);
        $innerarr = str_replace ("BOL2[", "", $innerarr);
        $innerarr = str_replace ("]EOL1", "", $innerarr);
        $innerarr = str_replace ("]EOL2", "", $innerarr);    
        $arr[] = $innerarr;
        
        
    }
    //echo "<pre>".print_r($arr,true)."</pre>";

}//end of type = Inventory

if($type == 'Backpack'){
//echo "we are using Backpack";

//we have removed initial [] brackets!, no we have to recreate an array!
    $bracketLevel =0;
    $charCounter =0;
    $stringWithLevels = "";
    foreach($stringArr as $char){
    
        if($char == '[' && $bracketLevel == 0){
            $stringArr[$charCounter] = 'BOL1['; //beginning of level 1
            $bracketLevel++;
        }else if($char == ']' && $bracketLevel == 1){
            $stringArr[$charCounter] = ']EOL1';
            $bracketLevel--;
        }else if($char == ',' && $bracketLevel == 2){
            $stringArr[$charCounter] = '~~';
            
        }else if($char == ']' && $bracketLevel == 2){
            $stringArr[$charCounter] = ']EOL2';
            $bracketLevel--;
        }else if($char == '[' && $bracketLevel == 1){
            $stringArr[$charCounter] = 'BOL2[';
            $bracketLevel++;
        }
        
       
        $stringWithLevels .= $stringArr[$charCounter];
        $charCounter++; //at the end of string, increase counter!
    }
    
    $outerARR = explode("BOL1[", $stringWithLevels);
    
      
      $outerARR[0] = str_replace('"','',$outerARR[0]);
      $outerARR[0] = str_replace(',','',$outerARR[0]);
      
      $arr['BackpackType'] = $outerARR[0];
      $primaryWeapon = explode("]EOL2,BOL2[", $outerARR[1]);    //BOL2["FN_FAL" ------  1]EOL2]EOL1,
      $weapon = substr($primaryWeapon[0], 6, -1);
      $quantity = substr($primaryWeapon[1], 0,1);
      
      $arr['PrimaryWeapon'] = array($weapon => $quantity);//$outerARR[1];      
      $itemsInBackpack = explode("]EOL2,BOL2[", $outerARR[2]);      
      
      $iib = explode("~~", substr(str_replace('"','',$itemsInBackpack[0]), 5));

      $iibq = explode("~~",str_replace(']EOL2]EOL1','',$itemsInBackpack[1]));//items in backpack quantity

      $arr['ItemsInBacpack'] = array_combine($iib, $iibq); 
}

//

return $arr;
}

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



$colname_qchar = "-1";
if (isset($_GET['cuid'])) {
  $colname_qchar = $_GET['cuid'];
}
mysql_select_db($database_test, $test);
$query_qchar = sprintf("SELECT * FROM survivor WHERE id = %s", GetSQLValueString($colname_qchar, "int"));
$qchar = mysql_query($query_qchar, $test) or die(mysql_error());
$row_qchar = mysql_fetch_assoc($qchar);
$totalRows_qchar = mysql_num_rows($qchar);

//get inventory

//echo '<div id="TroubleShooter" title=\''.$row_qchar['inventory']. ' ----------------------------- ' .$row_qchar['backpack'] . '\'> trouble?</div>';
//echo $row_qchar['inventory'];

$minventory = createArrFromString($row_qchar['inventory'], 'Inventory'); //all variables created by Mario will have prefix m to avoid collision with aki!

//echo '<hr>' .'<pre>'.print_r($minventory,true).'</pre>';

$inv = $row_qchar['inventory'];
$arr = $inv;
$invalids = array('[[', '[', ']', ']]', '"', '', ',,,,', '.', ',,', ',,,','[[],[]]', '[]');
$tempo = "";
$replace_invalids = str_replace($invalids, $tempo, $arr);
$str = $replace_invalids;
$str = explode(',', $replace_invalids);
if(isset($mFinalInv)){
    unset($mFinalInv);
}
$mFinalInv = array();
foreach($minventory as $minv){
        $tempArr = array();
    foreach($minv as $mi){
        if(is_array($mi)){
            $mi = implode("=>", $mi);
        }
        $mi = str_replace('"','',$mi);
        $tempArr[] =  $mi;
    }

   // echo "<pre>".    print_r($tempArr,true)."</pre><hr>";
    
    $mFinalInv = array_merge($mFinalInv,$tempArr);
}


//echo '<hr>' .'<pre>'.print_r($mFinalInv,true).'</pre>';
//echo '<hr>' .'<pre>'.print_r(array_count_values($mFinalInv),true).'</pre>';


/*
 * now added by mario:
 */

$str = array_count_values($mFinalInv);

//echo '<pre>'.print_r($str,true).'</pre>'; 
//echo '<pre>'.print_r($str, true).'<pre><hr>';


// $mbackpack = createArrFromString($row_qchar['backpack'], $type='Backpack');

// echo '<pre>'.print_r($mbackpack, true).'<pre><hr>';

/*
 * Get primary and backpack data!
 */
// $primaryWeapon = $mbackpack['PrimaryWeapon'];
// $backpack = $mbackpack['BackpackType'];
// foreach($primaryWeapon as $key => $value){
//    $primaryWeapon = $key;
// }
 

//get backpack
$inv2 = $row_qchar['backpack'];
$arr2 =$inv2;
$invalids2 = array('[[', '[', ']', ']]', '"','.', ',,', ',,' ,'[[],[]]','[13]','[8]','[29]', '15Rnd_9x19_M9SD', '100Rnd_762x51_M240','10Rnd_127x99_m107','10x_303','15Rnd_9x19_M9','15Rnd_9x19_M9SD','17Rnd_9x19_glock17','1Rnd_HE_M203','1Rnd_Smoke_M203','200Rnd_556x45_M249','20Rnd_762x51_DMR','30Rnd_545x39_AK','30Rnd_556x45_Stanag','30Rnd_556x45_StanagSD','30Rnd_762x39_AK47','30Rnd_9x19_MP5','30Rnd_9x19_MP5SD','5Rnd_762x51_M24','5x_22_LR_17_HMR','6Rnd_45ACP','7Rnd_45ACP_1911','8Rnd_9x18_Makarov','8Rnd_B_Beneli_74Slug','8Rnd_B_Beneli_Pellets','Binocular','Binocular_Vector','DZ_Patrol_Pack_EP1','FlareGreen_M203','FlareWhite_M203','FoodCanBakedBeans','FoodCanFrankBeans','FoodCanPasta','FoodCanSardines','ItemAntibiotic','ItemBandage','ItemBloodbag','ItemCompass','ItemEpinephrine','ItemEtool','ItemFlashlight','ItemFlashlightRed','ItemGPS','ItemHeatPack','ItemKnife','ItemMap','ItemMatchbox','ItemMorphine','ItemPainkiller','ItemSandbag','ItemSodaCoke','ItemSodaEmpty','ItemSodaMdew','ItemSodaPepsi','ItemTankTrap','ItemToolbox','ItemWatch','ItemWaterbottle','ItemWaterbottleUnfilled','NVGoggles','PipeBomb','SmokeShell','SmokeShellGreen','SmokeShellRed','TrashJackDaniels','TrashTinCan','WeaponHolder','_ItemJerrycan','WeaponHolder_ItemTent','WeaponHolder_PartEngine','WeaponHolder_PartFueltank','WeaponHolder_PartGeneric','WeaponHolder_PartGlass','WeaponHolder_PartVRotor','WeaponHolder_PartWheel','equip_antibiotics_CA.paa','equip_bbtin_ca.paa','equip_carglass_CA.paa','equip_engine_ca.paa','equip_fbtin_ca.paa','equip_fueltank_ca.paa','equip_genericparts_CA.paa','equip_jerrycan_CA.paa','equip_pastatin_ca.paa','equip_sardinestin_ca.paa','equip_tincan_ca.paa','equip_vrotor_ca.paa','equip_wheel_ca.paa', 'FoodSteakCooked', 'FoodSteakRaw', '30Rnd_9x19_UZI_SD');
$tempo2 = "";
$replace_invalids2 = str_replace($invalids2, $tempo2, $arr2);
$str2 = $replace_invalids2;
$str2 = explode(',', $replace_invalids2);



//echo '<hr>' .'<pre>'.print_r($row_qchar['backpack'],true).'</pre>';
//echo '<hr>' .'<pre>'.print_r(array_count_values($mFinalInv),true).'</pre>';




//get backpack no guns or 1st
$invclean = $row_qchar['backpack'];
$arrclean =$invclean;
$invalidsclean = array('[[', '[', ']', ']]', '"','.', ' ', ',,', ',,,', '[[],[]]', '[]','[13]','[8]','[29]', 'AKS_74_U', 'AKS_74_kobra', 'AK_47_M', 'AK_74', 'BAF_AS50_scoped', 'BAF_L85A2_RIS_CWS', 'Colt1911', 'FN_FAL', 'FN_FAL_ANPVS4', 'LeeEnfield', 'M1014', 'M136', 'M14_EP1', 'M16A2', 'M16A2GL', 'M24', 'M240_DZ', 'M249_DZ', 'M4A1', 'M4A1_AIM_SD_camo', 'M4A1_Aim', 'M4A1_HWS_GL_camo', 'M4A3_CCO_EP1', 'M9', 'M9SD', 'MP5A5', 'MP5SD', 'Makarov', 'Mk_48_DZ', 'SVD_CAMO', 'UZI_EP1', 'UZI_SD_EP1', 'glock17_EP1', 'huntingrifle', 'revolver_EP1', 'bizon_silenced');
$tempoclean = "";
$replace_invalidsclean = str_replace($invalidsclean, $tempoclean, $arrclean);

$strclean = $replace_invalidsclean;
$strclean = explode(',', $replace_invalidsclean);

//printr_ strclean AKA inventory backpack!


//get guns only
$invcleang = $row_qchar['inventory'];
$arrcleang =$invcleang;
$invalidscleang = array('[[', '[', ']', ']]', '"', '.', ' ', ',,', ',,,',',[[],[]],', '[]','[13]','[8]','[29]' );
$tempocleang = "";
$replace_invalidscleang = str_replace($invalidscleang, $tempocleang, $arrcleang);

$strcleang = $replace_invalidscleang;
$strcleang = explode(',', $replace_invalidscleang);
$strcleang=preg_replace('/[\s]+/',' ',$strcleang);




/////////////////////////////// END PHP //////////////////////

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Character Overview</title>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
<link rel="stylesheet" type="text/css" href="../css/reset.css"/>
<link rel="stylesheet" type="text/css" href="../css/admin.css"/>
</head>

<body>
<div id="bodycontent">

<div class="head"></div>
<?php include('../include/menu.php'); ?>

<div class="main_content">

<div id="char_table"><!-- char starts here -->

   
    
<div class="char_info">


Zombies killed:		<?php echo $row_qchar['zombie_kills']; ?><br />
Murders:		<?php echo $row_qchar['survivor_kills']; ?><br />
Bandits killed:	<?php echo $row_qchar['bandit_kills']; ?><br />
Time Played:	<?php echo $row_qchar['survival_time']; ?> min<br />
Last eat:<?php echo $row_qchar['last_ate']; ?><br />
Last drank: <?php echo $row_qchar['last_drank']; ?><br />
Outfit: <?php echo $row_qchar['model']; ?><br />
<br />
    </div> 
    

<div class="backpack">
Inventory:<br /><hr><br />


<div id="temp_inventory">
<?php foreach($str as $mitem => $quantity) { 
    unset($quantity);
    $mitem = explode("=>",$mitem);
    if(count($mitem)>1) {$quantity = $mitem[1];} else {$quantity = 1;}
    $mitem = $mitem[0];
    

?>
<div class="temp"><img name="temps" src="../characters/img/<?php echo $mitem; ?>.png" title="<?php echo $mitem." Quantity: ".$quantity; ?>" /> 
<?php
    
?>
</div>
    
<?php } ?>
</div>

</div>


</div><!-- char ends here -->

<div class="oldtable">

<table width="760" align="left" cellpadding="5">
  <tr>
    <td>id</td>
    <td><?php echo $row_qchar['id']; ?></td>
  </tr>
  <tr>
    <td>unique_id</td>
    <td><?php echo $row_qchar['unique_id']; ?></td>
  </tr>
    <tr>
    <td>Position:</td>
    <td><?php echo $row_qchar['worldspace']; ?><br /></td>
  </tr>
 <tr> 
    <td height="77">Inventory:</td>
    <td>	
<?php foreach($str as $mitem => $quantity) { 
    unset($quantity);
    $mitem = explode("=>",$mitem);
    if(count($mitem)>1) {$quantity = $mitem[1];} else {$quantity = 1;}
    $mitem = $mitem[0];
    

?>
<?php echo $mitem; ?><br/>
<?php
    
?>

    
<?php } ?>	
<br />
<br />

</td>
  </tr>
   <tr>
    <td height="46">Backpack:</td>
    <td><?php echo $row_qchar['backpack']; ?></td><br />
<br />

  </tr>
   <tr>
    <td>Medical</td>
    <td><?php echo $row_qchar['medical']; ?></td>
  </tr>
    <tr>
    <td>Dead</td>
    <td><?php echo $row_qchar['is_dead']; ?></td>
  </tr>
    <tr>
    <td>Skin</td>
    <td><?php echo $row_qchar['model']; ?></td>
  </tr>
    <tr>
    <td>State</td>
    <td><?php echo $row_qchar['state']; ?></td>
  </tr>
    <tr>
    <td>Survivor Kills</td>
    <td><?php echo $row_qchar['survivor_kills']; ?></td>
  </tr>
    <tr>
    <td>Bandit Kills</td>
    <td><?php echo $row_qchar['bandit_kills']; ?></td>
  </tr>
    <tr>
    <td>Zombie Kills</td>
    <td><?php echo $row_qchar['zombie_kills']; ?></td>
  </tr>
    <tr>
    <td>Headshots</td>
    <td><?php echo $row_qchar['headshots']; ?></td>
  </tr>
  <tr>
    <td>Last Ate</td>
    <td><?php echo $row_qchar['last_ate']; ?></td>
  </tr>
    <tr>
    <td>Last Drank</td>
    <td><?php echo $row_qchar['last_drank']; ?></td>
  </tr>
    <tr>
    <td>Survival Time</td>
    <td><?php echo $row_qchar['survival_time']; ?></td>
  </tr>
    <tr>
    <td>Last Updated</td>
    <td><?php echo $row_qchar['last_updated']; ?></td>
  </tr>
    <tr>
    <td>Start Time</td>
    <td><?php echo $row_qchar['start_time']; ?></td>
  </tr>
  
  
  
  
  
</table>


</div>
</div>
</body>
</html>
<?php
mysql_free_result($qchar);
?>
