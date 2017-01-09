<?php

namespace \blocks\gui\bannerUsuario\funcion;

use component\GestorNotificaciones\Componente as componenteNotificador;

class MensajeroNotificador {
    
    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;
    var $conexion;
    var $miGestorNotificaciones;
    
    function __construct($lenguaje, $sql) {
        
        $this->miConfigurador = \Configurador::singleton ();
        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miGestorNotificaciones=new componenteNotificador();
    
    }
    
    function getNotificacionesPendientes() {    


        $notificacionesPendientes=$this->miGestorNotificaciones->consultarPendientes($idUsuario);
        
        return $notificacionesPendientes;
    }
    
}

$miMensajero = new MensajeroNotificador ( $this->lenguaje, $this->sql );

$resultado= $miMensajero->procesarFormulario ();

?>
