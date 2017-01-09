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
                $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ("cambiarClave",
                        array('usuario'=>$usuario,'clave'=>$clave));
                //echo "Cadena: ".$atributos ['cadena_sql']."<br>";
                //echo "Clave: ".$this->miConfigurador->fabricaConexiones->crypto->decodificarClave($clave)."<br>";
                $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "acceso" );

                if($matrizItems != null){
                    $arregloLogin = array('SolicitudRecuperacionClave',$usuario,$_SERVER ['REMOTE_ADDR'],$_SERVER ['HTTP_USER_AGENT']);
                    $argumento = json_encode($arregloLogin);
                    $arreglo = array($usuario,$argumento);

                    $sesionActiva = "".  date('Y-m-d H:i:s');
                    $log=array('accion'=>"CAMBIOCLAVE",
                                'id_registro'=>$usuario."|".$sesionActiva,
                                'tipo_registro'=>"RECUPERACIONCLAVE",
                                'nombre_registro'=>$arreglo[1],
                                'descripcion'=>"Recuperacion de clave por ".$usuario." a las ".$sesionActiva,
                               ); 

                    $log['id_usuario']=$usuario;
                    $log['fecha_log']=date("F j, Y, g:i:s a");         
                    $log['host']=$this->miLogger->obtenerIP(); 
                    $cadenaSql = $this->miSql->getCadenaSql("registroLogUsuario", $log);
                    $resultado = $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
                    
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



