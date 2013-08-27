<?php

/**
 * Description of Importa_TLA
 *
 * @author eduardo
 */
class Importa_TLA {

    private $anno;
    private $conn;
    private $archivo;

    /**
     * 
     * @param ConectionDB $conn
     * @param type $anno
     * @param type $archivo
     */
    function __construct($conn, $anno, $archivo) {
        $this->conn = $conn;
        $this->anno = $anno;
        $this->archivo = $archivo;
        $alineas = file($archivo);


        foreach ($alineas as $pos => $linea) {


            $ares = $this->checaCadena($linea);

            if ($ares[0]) {

                $aux = array_values(array_filter(explode(" ", $ares[1])));

                if (count($aux) === 4) {


                    $id = $this->generaID($aux);
                    $fh = $this->generaFechaHora($aux);
                    $lec = $this->generaRegistro($aux[3]);


                    if ($lec > $GLOBALS['lim_inf'] && $lec < $GLOBALS['lim_sup']) {
                        //echo $lec."\n"; 
                        $this->insertaQuery($id, $fh, $aux[1] - 1, $lec);
                    } else {
                        //imprime error por registro fuera de rango
                        $this->imprimeError("Lectura fuera de rango", $pos);
                    }
                } else {
                    //imprime error por numero distinto de campos
                    $this->imprimeError("Número distinto de campos", $pos);
                }
            } else {
                //imprime error por caracteres no legibles
                $this->imprimeError("Carácteres no legibles", $pos);
            }
        }
    }

    /**
     * 
     * @param type $renglon
     * @return string
     */
    private function generaID($renglon) {


        $aux = (trim($renglon[1]) - 1);

        $aux.= date("Ymd", mktime(0, 0, 0, 1, $aux, $this->anno));

        $haux = explode(":", $renglon[0]);
        $aux.=$haux[0] . $haux[1];


        return $aux;
    }

    /**
     * 
     * @param type $renglon
     * @return type
     */
    private function generaFechaHora($renglon) {

        $aux = date("Y-m-d ", mktime(0, 0, 0, 1, (trim($renglon[1]) - 1), $this->anno));
        $haux = explode(":", $renglon[0]);
        return $aux . $haux[0] . ":" . $haux[1] . ":00";
    }

    /**
     * 
     * @param type $renglon
     * @return type
     */
    protected function generaRegistro($lectura) {

        $lectura = trim($lectura);

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
    private function insertaQuery($id, $fh, $dia, $registro) {

        if($this->checaEID($id)){
            $query="DELETE FROM estacion_tla WHERE id=".$id;
            $this->conn->ejecutaQuery($query);
        }
        
        
        $query = "INSERT INTO estacion_tla (id, fecha_hora, dia, registro ) VALUES (" . $id . ",'" . $fh . "'," . $dia . "," . $registro . " );";

        $this->conn->ejecutaQuery($query);
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    private function checaEID($id){
        
        $query="SELECT COUNT(*) as cuenta FROM estacion_tla WHERE id=".$id;
        
        $this->conn->ejecutaQuery($query);
        
        return($this->conn->obtenCampo("cuenta")>0)?TRUE:FALSE;
    }
    
    
    
    /**
     * 
     * @param type $linea
     * @return type
     * 
     * http://stackoverflow.com/questions/1176904/php-how-to-remove-all-non-printable-characters-in-a-string
     */
    protected function checaCadena($linea) {

        $linea = preg_replace('/[^[:print:]]/', '', $linea);
        $num = strlen($linea);

        $vb = false;
        for ($i = 0; $i < $num; $i++) {

            $linea[$i] = str_replace(array('?'), ' ', $linea[$i]);

            if (ctype_print($linea[$i]) && (ctype_digit($linea[$i]) || $linea[$i] === ":" || $linea[$i] === " " || $linea[$i] === "\n")) {
                $vb = true;
            } else {
                $vb = false;
            }
        }


        return array($vb, $linea);
    }

    /**
     * 
     * @param type $arg
     * @param type $pos
     */
    protected function imprimeError($arg, $pos) {

        echo $this->archivo . "::" . $arg . " línea: " . $pos . "\n";
    }

}

//class
?>