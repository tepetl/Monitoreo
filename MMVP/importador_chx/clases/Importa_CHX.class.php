<?php


/**
 * Description of Importa_CHX
 *
 * @author AAFR <alffore@gmail.com>
 */
class Importa_CHX {

    private $anno;
    private $conn;
    private $archivo;
    
    
    private $blecm;

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

        
        $total_lin=count($alineas);
        
//        echo $total_lin;
//        exit();
        
        $this->blecm=false;
        
      

        
        
        
        $bp = false;
        $baux = array();
        foreach ($alineas as $pos => $linea) {

            //echo $pos."::".$linea;

            $ares = $this->checaCadena($linea);


            // if ($ares[0]) {
            $aux = array_values(array_filter(explode(" ", $ares[1])));

         

            if (count($aux) === 10) {
         
                if ($bp === true) {
               
                    $this->procesaBloque($baux);
                    $baux = array();
                    
     
                    $this->blecm=false;
                } else {

                    $bp = true;
                }
            }

            if ($bp === true) {
                $baux[count($baux)] = $aux;
            }
//            } else {
//                //imprime error por caracteres no leeibles
//                $this->imprimeError("Carácteres no legibles", $pos);
//            }


        }
        
        $this->procesaBloque($baux);
        
    }

    /**
     * 
     * @param type $linea
     * @return type
     * 
     * @see http://stackoverflow.com/questions/1176904/php-how-to-remove-all-non-printable-characters-in-a-string
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
    
/**
 * 
 * @param type $baux
 * @return type
 */
    protected function procesaBloque($baux) {
        //print_r($baux);

        $adatos = array();

        $apre = $this->procesaHeader($baux[0]);

        //print_r($apre);
        
        $tam = count($baux);
       
        if($tam>31)return;

        for ($i = 1; $i < $tam; $i++) {
            $entrada = $baux[$i];

            $adatos[count($adatos)] = $this->generaLectura($apre, $entrada[3], 2 * $i - 2);

            $adatos[count($adatos)] = $this->generaLectura($apre, $entrada[7], 2 * $i - 1);
            
        }

        //print_r($adatos);
        
        if(!$this->blecm){        
        $tam2=count($adatos);
        //echo $tam2."\n";
        for ($i = 0; $i < $tam2; $i++) {
            $a=$adatos[$i];
        $this->insertaQuery($a[0], $a[1], $a[2], $a[3]);
        }
        }
    }
    
/**
 * 
 * @param type $aux
 * @return type
 */
    protected function procesaHeader($aux) {

        $dia = $aux[2];
        $preid = $dia;

        $preid.= date("Ymd", mktime(0, 0, 0, 1, $dia, $this->anno));

        $preid.=$aux[3];

        $prefh = date("Y-m-d", mktime(0, 0, 0, 1, $dia, $this->anno)) . " " . $aux[3] . ":";

        return array($dia, $preid, $prefh);
    }
    
/**
 * 
 * @param type $apre
 * @param type $lec
 * @param type $t
 * @return type
 */
    protected function generaLectura($apre, $lec, $t) {

        $id = $apre[1] . sprintf("%02d", $t);
        $fh = $apre[2] . sprintf("%02d", $t) . ":00";

        return array($id, $fh, $apre[0], $this->generaRegistro($lec));
    }

    /**
     * 
     * @param type $lectura
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

        if($lectura<$GLOBALS['lim_inf'] || $lectura>$GLOBALS['lim_sup'])$this->blecm=true;
        
        return $lectura;
    }

    /**
     * 
     * @param type $id
     * @param type $fh
     * @param type $dia
     * @param type $registro
     */
    private function insertaQuery($id, $fh, $dia, $lectura) {

        if($this->checaEID($id)){
            $query="DELETE FROM estacion_teo WHERE id=".$id;
            $this->conn->ejecutaQuery($query);
        }
        
        $query = "INSERT INTO estacion_teo (id, fecha_hora, dia, registro) VALUES (" . $id . ",'" . $fh . "'," . $dia . "," . $lectura . " );";
//echo $query."\n";
        $this->conn->ejecutaQuery($query);
    }

    
     /**
     * 
     * @param type $id
     * @return type
     */
    private function checaEID($id){
        
        $query="SELECT COUNT(*) as cuenta FROM estacion_teo WHERE id=".$id;
        
        $this->conn->ejecutaQuery($query);
        
        return($this->conn->obtenCampo("cuenta")>0)?TRUE:FALSE;
    }
}

    
    
}
