<?php

/**
 * @file modelo_dia
 * 
 * @author AAFR <alffore@gmail.com>
 */
$lat = 19.05826;    // North
$long = -98.6352;    // East
$offset = 0;    // difference between GMT and local time in hours

$zenith = 90 + (50 / 60);



include_once '../clases/ConectionDB.class.php';

$conn = new ConectionDB('localhost', 5432, 'MONITOREO', 'popoca', 'b0b054');

date_default_timezone_set('UTC');

$dia_modelo = date("Y-m-d") . " 23:59:00 UTC";

$d_modelo=date("d");

$mt_modelo = strtotime($dia_modelo);
$sunrise = date_sunrise($mt_modelo, SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith, $offset);

// determina el ultimo tiempo UTC comparable entre TEO y TLA
$query = "SELECT EXTRACT( epoch FROM MAX(teo.fecha_hora)) as epoc_max, MAX(teo.fecha_hora) as fh_max,MAX(tla.id) as id_max FROM estacion_teo teo,estacion_tla tla WHERE  teo.id=tla.id;";
$conn->ejecutaQuery($query);

$fh_max = $conn->obtenCampo("fh_max");
$id_max = $conn->obtenCampo("id_max");
$epoc_max = $conn->obtenCampo("epoc_max");


// determina el primer tiempo UTC comparable entre TEO y TLA
$query = "SELECT EXTRACT( epoch FROM MIN(teo.fecha_hora)) as epoc_min,MIN(teo.fecha_hora) as fh_min,MIN(tla.id) as id_min FROM estacion_teo teo,estacion_tla tla WHERE  teo.id=tla.id;";
$conn->ejecutaQuery($query);

$fh_min = $conn->obtenCampo("fh_min");
$id_min = $conn->obtenCampo("id_min");
$epoc_min = $conn->obtenCampo("epoc_min");



//obtenemos alfa para 24 historico
$query = "SELECT (tla_a/teo_a) as alfa FROM ";

$query.="(SELECT AVG(teo.registro) as teo_a FROM estacion_teo teo) as teo";

$query.=",(SELECT AVG(tla.registro) as tla_a FROM estacion_tla tla)as tla";

$conn->ejecutaQuery($query);

$alfa = $conn->obtenCampo("alfa");



//determinamos el coeficiente de correlación historico
$query = "SELECT corr(tla.registro,teo.registro) as r FROM estacion_tla as tla, estacion_teo as teo 
    WHERE teo.fecha_hora<='" . $fh_max . "'  AND teo.id=tla.id";
$conn->ejecutaQuery($query);

$r = $conn->obtenCampo("r");



//obtenemos lecturas
$query = "SELECT teo.registro as teo_r,tla.registro as tla_r,teo.id,teo.fecha_hora,
    EXTRACT( epoch FROM teo.fecha_hora) as fhepoc,'' as dp,'' as alfa, '' as r,EXTRACT( epoch FROM teo.fecha_hora) as fhepoc2 FROM estacion_teo teo,estacion_tla tla 
    WHERE teo.id=tla.id ORDER BY teo.id ";

$conn->ejecutaQuery($query);

while ($ren = $conn->obtenArrayA()) {

    $ren['dp'] = $ren['tla_r'] - $alfa * $ren['teo_r'];
    $ren['alfa'] = $alfa;
    $ren['r'] = $r;



    $aux= procesaRenglon($ren);



    echo implode(",", $aux) . "\n";
}

/**
 * 
 * @global type $sunrise
 * @global real $lat
 * @global type $long
 * @global type $zenith
 * @global int $offset
 * @global type $d_modelo
 * @param type $ren
 * @return type
 */
function procesaRenglon($ren) {
    global $sunrise,$lat,$long,$zenith,$offset,$d_modelo;
    
    $s=date_sunrise($ren['fhepoc'], SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith, $offset);
    
    $ds=$sunrise-$s;
    
    $ren['fhepoc']+=$ds;
    
    // checamos que estemos en el mismo dia y si no ciclamos
    $d_cal=date("d",$ren['fhepoc']);
    
    $segunda_corr=($d_modelo-$d_cal)*24*3600; 
    
    $ren['fhepoc']+=$segunda_corr;
    
    
    $ren['fecha_hora']=date("Y-m-d h:i:",$ren['fhepoc'])."00";
    
    return array_values($ren);
    
}

?>
