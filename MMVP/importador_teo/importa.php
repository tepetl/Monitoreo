<?php


date_default_timezone_set('UTC');
include_once 'clases/ConectionDB.class.php';
include_once 'clases/Importa_TEO.class.php';


$anno = (isset($argv[2]) && $argv[2] != "") ? $argv[2] : date("Y");

$conn = new ConectionDB('localhost', 5432, 'MONITOREO', 'popoca', 'b0b054');

$lim_inf = 41070;
$lim_sup = 41270;

$nimp = new Importa_TEO($conn, $anno, $argv[1]);
?>
