<?php
namespace registro\loginjano;

if (!isset($GLOBALS["autorizado"])) {
    include ("../index.php");
    exit();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/auth/Sesion.class.php");
include_once ("core/builder/InspectorHTML.class.php");
include_once ("core/builder/Mensaje.class.php");
include_once ("core/crypto/Encriptador.class.php");

// Esta clase contiene la logica de negocio del bloque y extiende a la clase funcion general la cual encapsula los
// metodos mas utilizados en la aplicacion
// Para evitar redefiniciones de clases el nombre de la clase del archivo funcion debe corresponder al nombre del bloque
// en camel case precedido por la palabra Funcion

class Funcion {
    
    var $sql;
    var $funcion;
    var $lenguaje;
    var $ruta;
    var $miConfigurador;
    var $error;
    var $miRecursoDB;
    var $crypto;
    
    function procesarFormulario() {
        include_once ($this->ruta . "funcion/formProcessor.php");
        return $resultado;
    }
    function procesarIngresoApp() {
        include_once ($this->ruta . "funcion/formProcessorApp.php");
        return $resultado;
    }    
    function procesarAjax() {
        include_once ($this->ruta . "funcion/procesarAjax.php");
    }
    
    function finSesion() {
        include_once ($this->ruta . "funcion/finSesion.php");
    }
        
    function enviarMensaje() {
        include_once ($this->ruta . "funcion/enviarMensaje.php");
    }
    
    function cambiarClave() {
        include_once ($this->ruta . "funcion/cambiarClave.php");
    }
    
    function registroUsuario() {
        include_once ($this->ruta . "funcion/registroUsuario.php");
    }    
    function action() {
        $resultado = true;
        
        // Aquí se coloca el código que procesará los diferentes formularios que pertenecen al bloque
        // aunque el código fuente puede ir directamente en este script, para facilitar el mantenimiento
        // se recomienda que aqui solo sea el punto de entrada para incluir otros scripts que estarán
        // en la carpeta funcion
        // Importante: Es adecuado que sea una variable llamada opcion o action la que guie el procesamiento:
        
        if (isset ( $_REQUEST ['procesarAjax'] )) {
            $this->procesarAjax ();
        } else {

            if (isset($_REQUEST['opcion'])) {
                switch ($_REQUEST ['opcion']) {

                    case "finSesion" :
                        $this->finSesion();
                        break;
                    case "validarLogin":
                        $resultado = $this->procesarFormulario();
                        break;
                    case "loginapp":
                        $resultado = $this->procesarIngresoApp();
                        break;                    
                    case "enviarMensaje":
                        $this->enviarMensaje();
                        break;
                    case 'cambiarClave':
                        $this->cambiarClave();
                        break;
                    case 'registrarUsuario':
                        $this->registroUsuario();
                        break;
                    
                }
            }
        }
        
        return $resultado;
    }
    
    function __construct() {
        
        $this->miConfigurador = \Configurador::singleton();
        
        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
        
        $this->miMensaje = \Mensaje::singleton();
        
        $this->miSesion= \Sesion::singleton();
        
        $conexion = "aplicativo";
        $this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        
        if (! $this->miRecursoDB) {
            
            $this->miConfigurador->fabricaConexiones->setRecursoDB($conexion,"tabla");
            $this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        }
    }
    
    public function setRuta($unaRuta) {
        $this->ruta = $unaRuta;
    }
    
    function setSql($a) {
        $this->sql = $a;
    }
    
    function setFuncion($funcion) {
        $this->funcion = $funcion;
    }
    
    public function setLenguaje($lenguaje) {
        $this->lenguaje = $lenguaje;
    }
    
    public function setFormulario($formulario) {
        $this->formulario = $formulario;
    }

}

?>
