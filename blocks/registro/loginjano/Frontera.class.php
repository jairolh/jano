<?php

namespace registro\loginjano;

if (! isset ( $GLOBALS ["autorizado"] )) {
    include ("../index.php");
    exit ();
}

include_once ("core/manager/Configurador.class.php");

class Frontera {
    
    var $ruta;
    var $sql;
    var $funcion;
    var $lenguaje;
    var $miFormulario;
    
    var 

    $miConfigurador;
    
    function __construct() {
        
        $this->miConfigurador = \Configurador::singleton ();
    
    }
    
    public function setRuta($unaRuta) {
        $this->ruta = $unaRuta;
    }
    
    public function setLenguaje($lenguaje) {
        $this->lenguaje = $lenguaje;
    }
    
    public function setFormulario($formulario) {
        $this->miFormulario = $formulario;
    }
    
    function frontera() {
        $this->html ();
    }
    
    function setSql($a) {
        $this->sql = $a;
    
    }
    
    function setFuncion($funcion) {
        $this->funcion = $funcion;
    
    }
    
    function html() {
        $this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");
        
        $this->miFormulario = new \FormularioHtml ();
		
        if (isset ( $_REQUEST ['opcion'] )) {
            switch ($_REQUEST ['opcion']) {

                case "recuperarClave" :
                    include_once ($this->ruta . "/formulario/recuperarClaveForm.php");
                    break;
                
                case "cambiarClave" :
                    include_once ($this->ruta . "/formulario/cambiarClaveForm.php");
                    break;
                
                case "mensaje" :
                    include_once ($this->ruta . "/formulario/mensaje.php");
                    break;
                
                case 'paginaPrincipal':
                    include_once ($this->ruta . "/formulario/formLogin.php");
                    break;
            }
        } else {
            $_REQUEST ['opcion'] = "login";
            include_once ($this->ruta . "/formulario/formLogin.php");
        }
        
    }

}
?>
