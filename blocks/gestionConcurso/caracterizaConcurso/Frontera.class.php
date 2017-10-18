<?php

namespace gestionConcurso\caracterizaConcurso;

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
		
		if (isset ( $_REQUEST ['opcion'] )) {
			
			switch ($_REQUEST ['opcion']) {
				
				case "mensaje" :
					include_once ($this->ruta . "/formulario/mensaje.php");
					break;
				
				case "nuevo":
					include_once($this->ruta."formulario/factores.php");
					break;
                                    
				case "nuevoCriterio":
					include_once($this->ruta."formulario/factores.php");
					break;
				
				case "nuevoFactor":
					include_once($this->ruta."formulario/factores.php");
					break;    
				case "nuevoRol":
					include_once($this->ruta."formulario/factores.php");
					break;    					
				case "editar":
					include_once($this->ruta."formulario/factores.php");
					break;   
				
					
				case "inhabilitar":
					include_once($this->ruta."formulario/cambiaEstadoFactor.php");
					break;    
				
				case "habilitar":
					include_once($this->ruta."formulario/cambiaEstadoFactor.php");
					break;  
					
				case "inhabilitarModalidad":
					include_once($this->ruta."formulario/cambiaEstadoModalidad.php");
					break;
				
				case "habilitarModalidad":
					include_once($this->ruta."formulario/cambiaEstadoModalidad.php");
					break;
					
				case "inhabilitarActividad":
					include_once($this->ruta."formulario/cambiaEstadoActividad.php");
					break;
				
				case "habilitarActividad":
					include_once($this->ruta."formulario/cambiaEstadoActividad.php");
					break;
				case "inhabilitarCevaluacion":
				case "habilitarCevaluacion":
					include_once($this->ruta."formulario/cambiaEstadoCevaluacion.php");
					break;  
					
                                    
				case "gestionCriterio":
					include_once($this->ruta."formulario/consultarFactores.php");
					break;
					
				case "gestionModalidad":
					include_once($this->ruta."formulario/consultarModalidades.php");
					break;
					
				case "gestionActividades":
					include_once($this->ruta."formulario/consultarActividades.php");
					break;
				case "rolesCriterio":
					include_once($this->ruta."formulario/consultarRolesCriterio.php");
					break;	
				case "nuevaModalidad":
					include_once($this->ruta."formulario/modalidades.php");
					break;
					
				case "editarModalidad":
					include_once($this->ruta."formulario/modalidades.php");
					break;
					
				case "editarActividad":
					include_once($this->ruta."formulario/actividades.php");
					break;
					
				case "nuevaActividad":
					include_once($this->ruta."formulario/actividades.php");
					break;
							
                                                                         
        		}
		} else {
			$_REQUEST ['opcion'] = "mostrar";
			include_once ($this->ruta . "/formulario/consultarFactores.php");
		}
	}
}
?>
