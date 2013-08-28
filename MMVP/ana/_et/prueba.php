<?php

/**
 * @file prueba
 * 
 * @author AAFR
 */
date_default_timezone_set('UTC');

// De Bilt, The Netherlands, weather station #06260
$lat = 52.10;    // North
$long = 5.18;    // East
$offset = 1;    // difference between GMT and local time in hours

$zenith=90+50/60;
echo "<br><p>Sunrise: ".date_sunrise(time(), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset);
echo "<br>Sunset: ".date_sunset(time(), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset);

$zenith=96;
echo "<br><p>Civilian Twilight start: ".date_sunrise(time(), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset);
echo "<br>Civilian Twilight end: ".date_sunset(time(), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset);

$zenith=102;
echo "<br><p>Nautical Twilight start: ".date_sunrise(time(), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset);
echo "<br>Nautical Twilight end: ".date_sunset(time(), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset);

$zenith=108;
echo "<br><p>Astronomical Twilight start: ".date_sunrise(time(), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset);
echo "<br>Astronomical Twilight end: ".date_sunset(time(), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset);

?> 