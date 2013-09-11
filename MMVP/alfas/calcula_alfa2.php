<?php

/**
 * @file calcula_alfa2.php
 * 
 *  @author AAFR <alffore@gmail.com>
 * 
 */
date_default_timezone_set('UTC');
include_once '../clases/ConectionDB.class.php';



$d = (isset($argv[1]) && $argv[1] > 0) ? $argv[1] : 1;



$conn = new ConectionDB('localhost', 5432, 'MONITOREO', 'popoca', 'b0b054');


// determina el ultimo tiempo UTC comparable entre TEO y TLA
$query = "SELECT EXTRACT(epoch FROM MAX(teo.fecha_hora)) as epoc_max, MAX(teo.fecha_hora) as fh_max,
    MAX(tla.id) as id_max FROM estacion_teo teo,estacion_tla tla WHERE  teo.id=tla.id;";
$conn->ejecutaQuery($query);

$fh_max = $conn->obtenCampo("fh_max");
$epoc_max = $conn->obtenCampo("epoc_max");



$epoc1 = $epoc_max;
$epoc0 = $epoc1 - 86400;


while ($d > 0) {

    //obtenemos lecturas
    $query = "
    SELECT AVG(teo_r) as teo_r, AVG(tla_r) as tla_r FROM (
    SELECT teo.registro as teo_r,tla.registro as tla_r,teo.id,teo.fecha_hora,
    EXTRACT(epoch FROM teo.fecha_hora) as fhepoc,'' as dp,'' as alfa, '' as r FROM estacion_teo teo,estacion_tla tla 
    WHERE EXTRACT(epoch FROM teo.fecha_hora) <=$epoc1 AND EXTRACT(epoch FROM teo.fecha_hora) >$epoc0 AND teo.id=tla.id 
     ) as s";

    $epoc1 = $epoc0;
    $epoc0 = $epoc1 - 86400;


    $conn->ejecutaQuery($query);



    if ($conn->obtenNumRes() > 0) {


        while ($ren = $conn->obtenArrayA()) {
            if ($ren['teo_r'] > 0) {

                $alfa = $ren['tla_r'] / $ren['teo_r'];

                $v = strftime('%Y-%m-%d %H:00:00', $epoc0);

                echo $alfa . "," . $ren['teo_r'] . "," . $ren['tla_r'] . "," . $epoc0 . "," . $v . "\n";
            }
        }
        
    }




    $d--;
}
?>
