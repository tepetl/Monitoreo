<?php

/* 
 * @file importa
 * 
 * @author EGL <eduardoglez@gmail.com>
 */
date_default_timezone_set('UTC');
include_once '../clases/ConectionDB.class.php';
include_once 'clases/Importa_CHX.class.php';



$anno = (isset($argv[2]) && $argv[2] != "") ? $argv[2] : date("Y");

$conn = new ConectionDB('localhost', 5432, 'MONITOREO', 'popoca', 'b0b054');


$lim_inf = 40500;
$lim_sup = 40700;



$nimp = new Importa_CHX($conn, $anno, $argv[1]);
