<?php
/**
 * @file calcula_alfa.php
 * 
 * @author John Doe <john.doe@example.com>
 */


date_default_timezone_set('UTC');
include_once '../clases/ConectionDB.class.php';


$conn = new ConectionDB('localhost', 5432, 'MONITOREO', 'popoca', 'b0b054');


$query="SELECT teo.registro as teo_r,tla.registro as tla_r,teo.id,teo.fecha_hora,
    EXTRACT(epoch FROM teo.fecha_hora) as fhepoc FROM estacion_teo teo,estacion_tla tla 
    WHERE  teo.id=tla.id ORDER BY teo.id";

$conn->ejecutaQuery($query);

$sum_tla=0;
$sum_teo=0;
while($ren=$conn->obtenArrayA()){
    
    $sum_teo+=$ren['teo_r'];
    $sum_tla+=$ren['tla_r'];
    
    $alfa=$sum_tla/$sum_teo;
    
    
    echo $alfa.",".$sum_teo.",".$sum_tla.",".$ren['fhepoc'].",".$ren['fecha_hora']."\n";
    
}



?>
