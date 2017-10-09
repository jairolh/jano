<?php

namespace gestionConcurso\reclamacionesEvaluaciones;

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
				case "consultarDetalleRta":
					include_once($this->ruta."formulario/respuestaReclamacion.php");
					break;

				case "consutarReclamaciones" :
					include_once($this->ruta."formulario/consultarReclamaciones.php");
					break;

				case "consultaEvaluacion" :
					include_once($this->ruta."formulario/tabs/verEvaluacion.php");
					break;

				case "evaluar" :
					include_once($this->ruta."formulario/nuevaEvaluacion.php");
					break;

				case "validacion" :
					include_once($this->ruta."formulario/detalleEjecucion.php");
					break;

				case "mensaje" :
					include_once ($this->ruta . "/formulario/mensaje.php");
					break;

				case "listar":
					include_once ($this->ruta . "/formulario/consultarReclamaciones.php");
					break;

				case "detalle":
					include_once($this->ruta."formulario/detalleEjecucion.php");
					break;

				case "evaluar":
					include_once($this->ruta."formulario/evaluarAspirante.php");
					break;

				case "consultarEvaluacion":
					include_once($this->ruta."formulario/consultarEvaluacion.php");
					break;

				case "listar":
					include_once ($this->ruta . "/formulario/consultarConcursoEjecuta.php");
					break;


        		}
		} else {
			$_REQUEST ['opcion'] = "listar";
			include_once ($this->ruta . "/formulario/consultarConcursoEjecuta.php");
		}
	}
}
?>
