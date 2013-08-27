<?php

/**
 * @file difpon.php
 * 
 * @author John Doe <john.doe@example.com>
 */
date_default_timezone_set('UTC');
include_once 'clases/ConectionDB.class.php';

$h = (isset($argv[1]) && $argv[1] > 0) ? $argv[1] : 24;

$conn = new ConectionDB('localhost', 5432, 'MONITOREO', 'popoca', 'b0b054');


// determina el ultimo tiempo UTC comparable entre TEO y TLA
$query = "SELECT EXTRACT( epoch FROM MAX(teo.fecha_hora)) as epoc_max, MAX(teo.fecha_hora) as fh_max,MAX(tla.id) as id_max FROM estacion_teo teo,estacion_tla tla WHERE  teo.id=tla.id;";
$conn->ejecutaQuery($query);

$fh_max = $conn->obtenCampo("fh_max");
$id_max = $conn->obtenCampo("id_max");
$epoc_max = $conn->obtenCampo("epoc_max");



//obtenemos alfa para 24 horas
$query = "SELECT (tla_a/teo_a) as alfa FROM ";

$query.="(SELECT AVG(teo.registro) as teo_a FROM estacion_teo teo WHERE teo.fecha_hora<='" . $fh_max . "' 
    AND teo.fecha_hora>= timestamp '" . $fh_max . "' - interval '" . $h . " hours') as teo";

$query.=",(SELECT AVG(tla.registro) as tla_a FROM estacion_tla tla WHERE tla.fecha_hora<='" . $fh_max . "' 
    AND tla.fecha_hora>= timestamp '" . $fh_max . "' - interval '" . $h . " hours')as tla";

$conn->ejecutaQuery($query);

$alfa = $conn->obtenCampo("alfa");



//determinamos el coeficiente de correlacion para las misma 24 horas
$query = "SELECT corr(tla.registro,teo.registro) as r FROM estacion_tla as tla, estacion_teo as teo 
    WHERE teo.fecha_hora<='" . $fh_max . "' AND teo.fecha_hora>= timestamp '" . $fh_max . "' - interval '" . $h . " hours' AND teo.id=tla.id";
$conn->ejecutaQuery($query);

$r = $conn->obtenCampo("r");



$epoc1 = $epoc_max;

for ($h2 = 1; $h2 <= $h; $h2++) {
//echo "\n\n";

    $epoc0 = $epoc_max - $h2 * (3600);



//obtenemos lecturas
    $query = "
    SELECT AVG(teo_r) as teo_r, AVG(tla_r) as tla_r,'' as teo_id,'' as fecha_hora, '' as fhepoc,'' as dp,'' as alfa, '' as r  FROM (
    SELECT teo.registro as teo_r,tla.registro as tla_r,teo.id,teo.fecha_hora,
    EXTRACT( epoch FROM teo.fecha_hora) as fhepoc,'' as dp,'' as alfa, '' as r FROM estacion_teo teo,estacion_tla tla 
    WHERE EXTRACT( epoch FROM teo.fecha_hora) <=$epoc1 AND EXTRACT( epoch FROM teo.fecha_hora) >$epoc0 AND teo.id=tla.id 
     ) as s
        
";




    $conn->ejecutaQuery($query);

    if ($conn->obtenNumRes() > 0) {

        while ($ren = $conn->obtenArrayA()) {
            $aux = array();
            foreach ($ren as $c => $v) {

                switch ($c) {
                    case 'dp':
                        $v = $ren['tla_r'] - $alfa * $ren['teo_r'];
                        break;
                    case 'alfa':
                        $v = $alfa;
                        break;
                    case 'fecha_hora':
                        $v = strftime('%Y-%m-%d %H:00:00', $epoc0);
                        break;
                    case 'fhepoc':
                        $v=$epoc0;
                        break;
                    case 'r':
                        $v = $r;
                        break;
                }

                $aux[count($aux)] = $v;
            }

            if($aux[0]!=""){
            echo implode(",", $aux) . "\n";
            }
        }
    }
    $epoc1 = $epoc0;
}//loop por hora $h2
?>
