<?php

/**
 * @file importa
 * 
 * @author AAFR  <alffore@gmail.com>
 */
date_default_timezone_set('UTC');
include_once 'clases/ConectionDB.class.php';
include_once 'clases/Importa_TLA.class.php';



$anno = (isset($argv[2]) && $argv[2] != "") ? $argv[2] : date("Y");

$conn = new ConectionDB('localhost', 5432, 'MONITOREO', 'popoca', 'b0b054');


$lim_inf = 40190;
$lim_sup = 40410;



$nimp = new Importa_TLA($conn, $anno, $argv[1]);
?>
