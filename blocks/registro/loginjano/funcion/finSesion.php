<?php

namespace registro\loginjano;

use registro\loginjano\funcion\Redireccionador;

include_once ('Redireccionador.php');

// var_dump($_REQUEST);exit;
class FormProcessor {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;
    var $conexion;
    var $miLogger;

    function __construct($lenguaje, $sql) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miSesion = \Sesion::singleton();
        //Objeto de la clase Loger
        $this->miLogger = \logger::singleton();
    }

    function procesarFormulario() {

        /**
         *
         * @todo lógica de procesamiento
         */
        session_start();
        $arregloLogin = array('CierreSesion',$_REQUEST ["usuario"],$_SERVER ['REMOTE_ADDR'],$_SERVER ['HTTP_USER_AGENT']);
        $argumento = json_encode($arregloLogin);
        $arreglo = array($_REQUEST ["usuario"],$argumento);
        
        $sesionActiva = $_REQUEST['sesion'];
        $log=array('accion'=>"SALIDA",
                    'id_registro'=>$_REQUEST ["usuario"]."|".$sesionActiva,
                    'tipo_registro'=>"LOGOUT",
                    'nombre_registro'=>$arreglo[1],
                    'descripcion'=>"Salida al sistemas del usuario ".$_REQUEST ["usuario"]." con la sesion ".$sesionActiva,
                   ); 
        //            var_dump($log);
        //$_COOKIE["aplicativo"]=$estaSesion;
        $this->miLogger->log_usuario($log);
        $borrarSesion = $this->miSesion->borrarValorSesion('TODOS', $sesionActiva);
        $terminarSesion=$this->miSesion->terminarSesion($sesionActiva);
        session_destroy();
        Redireccionador::redireccionar('paginaPrincipal', false);
    }

}

$miProcesador = new FormProcessor($this->lenguaje, $this->sql);

$resultado = $miProcesador->procesarFormulario();



