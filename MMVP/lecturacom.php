<?php

/**
 * @file lecturacom
 * 
 * @author AAFR
 */
date_default_timezone_set('UTC');
include_once 'clases/ConectionDB.class.php';


$conn = new ConectionDB('localhost', 5432, 'MONITOREO', 'popoca', 'b0b054');


$query="SELECT AVG(rtla),AVG(rteo),dia FROM (    
SELECT tla.id,tla.registro as rtla,teo.registro as rteo,tla.fecha_hora,tla.dia 
    FROM estacion_tla tla,estacion_teo teo WHERE teo.id=tla.id 
    ORDER BY fecha_hora) as s GROUP BY dia ORDER BY dia;";








?>
