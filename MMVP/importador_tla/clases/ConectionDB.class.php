<?php


/**
 * Conexión a la base de datos en postgres
 *
 * @author eduardo
 */
class ConectionDB {
    protected $conexion;
    public $res;
    private $serverName;
    private $dbPort;
    private $dbName;
    private $userName;
    private $dbuserPasswd;

    /**
     *
     * @param type $serverName
     * @param type $dbPort
     * @param type $dbName
     * @param type $userName
     * @param type $dbuserPasswd 
     */
    function __construct($serverName, $dbPort, $dbName, $userName, $dbuserPasswd) {
        $this->serverName = $serverName;
        $this->dbPort = $dbPort;
        $this->dbName = $dbName;
        $this->userName = $userName;
        $this->dbuserPasswd = $dbuserPasswd;

        $this->abreConexion();
    }

    /**
     * 
     */
    protected function abreConexion() {
    
        $this->conexion = pg_connect("host=" . $this->serverName . " port=" . $this->dbPort . " dbname=" . $this->dbName . " user=" . $this->userName . " password=" . $this->dbuserPasswd);
    }

    /**
     * @brief Metodo que ejecuta un query arbitrario
     * @param $query Query para la ejecucion
     */
    public function ejecutaQuery($query) {
        $this->res = pg_query($this->conexion, $query);
        if ($this->res == false) {
            echo "<br>" . $query . "</p>";
            echo "<br><p><h3>Error: </h3>" . pg_last_error($this->conexion);
        }
    }

    /**
     * @brief Metodo que obtiene el resultado del query
     */
    public function obtenRes() {
        return $this->res;
    }

    public function obtenCampo($campo) {
        return pg_fetch_result($this->res, 0, $campo);
    }

    public function freeRes() {
        pg_free_result($this->res);
    }

    /**
     * @brief Metodo que obtiene la cantidad de resultados
     */
    public function obtenNumRes() {
        return pg_num_rows($this->res);
    }

    /**
     * @brief Metodo que recuperalos resultados y los pone e un arry relacional
     */
    public function obtenArray() {
        return pg_fetch_array($this->res);
    }

    /**
     * @brief Metodo que recuperalos resultados y los pone e un arry relacional
     */
    public function obtenArrayA() {
        //return pg_fetch_array($this->res,PGSQL_ASSOC);	
        return pg_fetch_assoc($this->res);
    }

    /**
     * @brief Metodo que recuperalos resultados y los pone e un arry relacional
     */
    public function obtenRow() {
        return pg_fetch_row($this->res);
    }

    /**
     * @brief Metodo que pone ejecuta y regresa el resultado
     */
    public function ponExQuery($query) {
        $this->ejecutaQuery($query);
        return $this->obtenRes();
    }

    /**
     * @brief Metodo que obtieen el nombre del campo si se proporciona su posicion
     */
    public function obtenNomCampo($ncampo) {
        return pg_field_name($this->res, $ncampo);
    }

    function __destruct() {
        pg_close($this->conexion);
    }

}

//class

?>
