<?php

/**
 * @file lector_simple
 * 
 * @author AAFR
 */

date_default_timezone_set('UTC');
include_once 'clases/ConectionDB.class.php';


$conn = new ConectionDB('localhost', 5432, 'MONITOREO', 'popoca', 'b0b054');


$query="SELECT EXTRACT( epoch FROM fecha_hora) as fh, registro FROM estacion_teo ORDER BY fecha_hora ";


$conn->ejecutaQuery($query);


while($ren=$conn->obtenArrayA()){
    
    echo $ren['fh'].",".$ren['registro']."\n";
    
    
}


?>
