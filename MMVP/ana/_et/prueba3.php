<?php

/**
 * @file prueba2
 * 
 * @author AAFR
 */
date_default_timezone_set('UTC');
$lat = 19.05826;    // North
$long = -98.6352;    // East
$offset = 0;    // difference between GMT and local time in hours

$zenith=90 + (50/60);

$dia_modelo=date("Y-m-d")." 23:59:00 UTC";

$mt_modelo=strtotime($dia_modelo);

echo "\nSunrise: ".date_sunrise($mt_modelo, SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset)."\n";
echo "\nSunrise: ".date_sunrise($mt_modelo, SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith, $offset)."\n";




echo "\nSunrise 1: ".date_sunrise($mt_modelo, SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset)."\n";
echo "\nSunrise 2: ".date_sunrise($mt_modelo+2*24*3600, SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset)."\n";
echo "\nSunrise 3: ".date_sunrise($mt_modelo+3*24*3600, SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset)."\n";
echo "\nSunrise 4: ".date_sunrise($mt_modelo+4*24*3600, SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset)."\n";



for($i=1;$i<31;$i++){
    echo "\nSunrise : ".date_sunrise($mt_modelo+($i*24*3600), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset)."\n";
    
}
?>
