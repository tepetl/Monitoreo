<?PHP

date_default_timezone_set('UTC');
include_once 'clases/ConectionDB.class.php';



$alineas = file($argv[1]);

$anno = ($argv[2] != "") ? $argv[2] : date("Y");

$conn = new ConectionDB('localhost', 5432, 'MONITOREO', 'popoca', 'b0b054');


$lim_inf = 40000;
$lim_sup = 40400;


foreach ($alineas as $pos => $linea) {


    $ares = checaCadena($linea);

    if ($ares[0]) {



        $aux = array_values(array_filter(explode(" ", $linea)));

        if (count($aux) === 4) {


            $id = generaID($aux);
            $fh = generaFechaHora($aux);
            $lec = generaRegistro($aux);


            if ($lec > $lim_inf && $lec < $lim_sup) {
                //echo $lec."\n"; 
                insertaQuery($id, $fh, $aux[1] - 1, $lec);
            } else {
                //imprime error por registro fuera de rango
            }
        } else {
            //imprime error por numero distinto de campos
        }
    } else {
        //imprime error por caracteres no leeibles
    }
}

/**
 * 
 * @global type $anno
 * @param type $renglon
 * @return string
 */
function generaID($renglon) {
    global $anno;


    $aux = (trim($renglon[1]) - 1);

    $aux.= date("Ymd", mktime(0, 0, 0, 1, $aux, $anno));

    $haux = explode(":", $renglon[0]);
    $aux.=$haux[0] . $haux[1];




    return $aux;
}

function generaFechaHora($renglon) {
    global $anno;
    $aux = date("Y-m-d ", mktime(0, 0, 0, 1, (trim($renglon[1]) - 1), $anno));

    return $aux . $renglon[0];
}

/**
 * 
 * @param type $renglon
 * @return type
 */
function generaRegistro($renglon) {

    $lectura = trim($renglon[3]);

    $lectura = floatval($lectura);

    if ($lectura < 10000)
        $lectura*=100.00;

    if ($lectura < 100000)
        $lectura*=10.00;


    $lectura = floatval($lectura) / 10.0;

    return $lectura;
}

/**
 * 
 * @param type $id
 * @param type $fh
 * @param type $dia
 * @param type $registro
 */
function insertaQuery($id, $fh, $dia, $registro) {
    global $conn;
    $query = "INSERT INTO estacion_tla (id, fecha_hora, dia, registro ) VALUES (" . $id . ",'" . $fh . "'," . $dia . "," . $registro . " );";

    // echo $query."\n";
    $conn->ejecutaQuery($query);
}

function checaCadena($linea) {
    $num = strlen($linea);

    $vb = false;
    for ($i = 0; $i < $num; $i++) {
        if (ctype_print($linea[$i]) && (ctype_digit($linea[$i]) || $linea[$i] === ":" || $linea[$i] === " " || $linea[$i] === "\n")) {
            $vb = true;
        } else {
            $vb = false;
        }
    }


    return array($vb, $linea);
}
?>

