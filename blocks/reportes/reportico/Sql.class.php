<?php

namespace reportes\reportico;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

// Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
// en camel case precedida por la palabra sql
class Sql extends \Sql {

    var $miConfigurador;

    function __construct() {
        $this->miConfigurador = \Configurador::singleton();
    }

    function getCadenaSql($tipo, $variable = "") {

        /**
         * 1.
         * Revisar las variables para evitar SQL Injection
         */
        $prefijo = $this->miConfigurador->getVariableConfiguracion("prefijo");
        $idSesion = $this->miConfigurador->getVariableConfiguracion("id_sesion");

        switch ($tipo) {

            /**
             * Clausulas específicas
             */
                case "consultarUsuarios":

                        $cadenaSql = "SELECT DISTINCT ";
                        $cadenaSql .= " usu.id_usuario, ";
                        $cadenaSql .= "usu.nombre, ";
                        $cadenaSql .= "usu.apellido ";
                        $cadenaSql .= "FROM ".$prefijo."usuario usu ";
                        $cadenaSql .= " WHERE ";
                        $cadenaSql .= " usu.id_usuario='".$variable['id_usuario']."'"; 
                       
                break;
        }

        return $cadenaSql;
    }

}

?>
