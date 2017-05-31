<?php
require_once ("core/files/soporteSql.class.php");

class soporteBase {
    
    /**
     * Atributos de la sesión
     */
    var $sesionUsuarioId;
    var $miSql;
    var $prefijoTablas;
    
    function setConexion($conexion) {
        $this->miConexion = $conexion;
    }
    
    /**
     * @METHOD setIdusuario
     *
     * @return valor
     * @access public
     */
    function setIdUsuario($idUsuario) {
        $this->setSesionUsuarioId = $idUsuario;
    }
    // Fin del mètodo especificar_usuario
        
    function setPrefijoTablas($valor) {
        $this->prefijoTablas = $valor;
        $this->miSql->setPrefijoTablas ( $this->prefijoTablas );
    }
    
    function getSesionUsuarioId() {
        return $this->sesionUsuarioId;
    }

}

?>
