<?php

/**
 * Description of Importa_CHX
 *
 * @author EGL <eduardoglez@gmail.com>, AAFR <alffore@gmail.com>
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


        $total_lin = count($alineas);



        $this->blecm = false;

        $bp = false;


        foreach ($alineas as $pos => $linea) {

            $ares = $this->checaCadena($linea);

            if (!$ares[0]) {
                $this->imprimeError("Caracteres no legibles", $pos);
            } else {

                $aux = array_values(array_filter(explode(" ", $ares[1])));

                if (count($aux) == 4) {

                    $fh = $this->procesaFecha($aux[0], $aux[1]);
                    $dia = $this->procesaDia($fh);
                    $lectura = $this->procesaLectura($aux[2]);
                    $id = $fh;

                    $this->insertaQuery($id, $fh, $dia, $lectura);
                }
            }
        }
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

            if (ctype_print($linea[$i]) && (ctype_digit($linea[$i]) || $linea[$i] === ":" || $linea[$i] === " " || $linea[$i] === "\n" || $linea[$i] === "-" || $linea[$i] === ".")) {
                $vb = true;
            } else {
                $vb = false;
            }
        }


        return array($vb, $linea);
    }

    /**
     * Metodo para procesar la fecha regrea los dos formatos necesarios de fecha hora
     * @param String $fecha
     * @param type $hora
     */
    protected function procesaFecha($fecha, $hora) {

        $aux = explode("-", $fecha);

        $dato = $aux[2] . "-" . $aux[1] . "-" . $aux[0] . " " . $hora;


        return strtotime($dato);
    }

    /**
     * 
     * @param type $fecha
     * @return type
     */
    protected function procesaDia($fecha_ts) {
        return 0 + strftime("%j", $fecha_ts);
    }

    /**
     * 
     * @param type $lectura
     * @return type
     */
    protected function procesaLectura($lectura) {
        $lectura = trim($lectura);
        if ($lectura[0] == 0) {
            $lectura[0] = " ";
        }

        return 0.00 + trim($lectura);
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
     * @param type $id
     * @param type $fh
     * @param type $dia
     * @param type $registro
     */
    private function insertaQuery($id, $fh, $dia, $lectura) {

        if ($this->checaEID($id)) {
            $query = "DELETE FROM estacion_chx WHERE id=" . $id;
            $this->conn->ejecutaQuery($query);
        }

        $query = "INSERT INTO estacion_chx (id, fecha_hora, dia, registro) VALUES (" . $id . ",'" . $fh . "'," . $dia . "," . $lectura . " );";

        $this->conn->ejecutaQuery($query);
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    private function checaEID($id) {

        $query = "SELECT COUNT(*) as cuenta FROM estacion_chx WHERE id=" . $id;

        $this->conn->ejecutaQuery($query);

        return($this->conn->obtenCampo("cuenta") > 0) ? TRUE : FALSE;
    }

}
