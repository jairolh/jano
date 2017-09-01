<?php

namespace registro\loginjano;

use registro\loginjano\funcion\Redireccionador;
include_once ("core/log/logger.class.php");
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
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        session_start();
        
        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        if((strtotime('today')-strtotime($_REQUEST['fecha']))<=0){
            if($_REQUEST['nuevaClave']===$_REQUEST['verificacionNuevaClave'] 
                    &&  strlen($_REQUEST['nuevaClave'])<=16 
                    && preg_match("/(?=.*[0-9])(?=.*[A-Z])(?=\S+$).{8,}/", $_REQUEST['nuevaClave'])){
            
                $usuario = trim($_REQUEST['usuario']);
                $clave = $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST['nuevaClave']);

                //--------------------COMPROBACION DE EXISTENCIA DEL USUARIO-----------------------------------
                $arregloDatos=array('usuario'=>$usuario,'clave'=>$clave);
                
                $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ("cambiarClave",$arregloDatos);
                //echo "Cadena: ".$atributos ['cadena_sql']."<br>";
                //echo "Clave: ".$this->miConfigurador->fabricaConexiones->crypto->decodificarClave($clave)."<br>";
                $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "actualiza", $arregloDatos, "cambiarClave" );
                if($matrizItems != null){
                    
                    Redireccionador::redireccionar('claveCambiada', array('nombre'=>$_REQUEST['nombreUsuario']));
                }else{
                    Redireccionador::redireccionar('claveNoCambiada', array('nombre'=>$_REQUEST['nombreUsuario']));
                }
            }else{
                Redireccionador::redireccionar('claveNoCoincide');
            }
        }else{
            Redireccionador::redireccionar('linkCaducado',array('fecha'=>$_REQUEST['fecha']));
        }
        
        session_destroy();
    }

}

$miProcesador = new FormProcessor($this->lenguaje, $this->sql);

$resultado = $miProcesador->procesarFormulario();



