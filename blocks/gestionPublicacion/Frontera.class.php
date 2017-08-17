<?php

namespace gestionPublicacion;

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
	var $formulario;
	var $miConfigurador;
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
		$this->formulario = $formulario;
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
		include_once ("core/builder/FormularioHtml.class.php");
		
		$this->ruta = $this->miConfigurador->getVariableConfiguracion ( "rutaBloque" );
		$this->miFormulario = new \FormularioHtml ();
                //var_dump($_REQUEST);//exit;
		if (isset ( $_REQUEST ['opcion'] )) {
			
			switch ($_REQUEST ['opcion']) {
                                case "hojaVida" :
					include_once ($this->ruta . "/formulario/hojaVida.php");
					break;
                                case "faseProcesado" :
					include_once ($this->ruta . "/formulario/faseProcesado.php");
					break;                                    
                                case "faseParcial" :
                                        include_once ($this->ruta . "/formulario/listaParcial.php");
				     break;                                    
                                case "faseFinal" :
                                        include_once ($this->ruta . "/formulario/listaFinal.php");
				     break;                                    
				case "mensaje" :
					include_once ($this->ruta . "/formulario/mensaje.php");
					break;
				
				                                  
        		}
		} else {
			$_REQUEST ['opcion'] = "mostrar";
			include_once ($this->ruta . "/formulario/consultarUsuarios.php");
		}
	}
}
?>
