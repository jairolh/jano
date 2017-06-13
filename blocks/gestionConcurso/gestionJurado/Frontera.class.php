<?php

namespace gestionConsurso\gestionJurado;

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

				case "nuevoTipo":
					include_once($this->ruta."formulario/registrarTipoJurado.php");
					break;

					case "inhabilitarJurado":
						include_once($this->ruta."formulario/cambiaEstadoJurado.php");
						break;

					case "habilitarJurado":
						include_once($this->ruta."formulario/cambiaEstadoJurado.php");
						break;

					case "inhabilitarCriterio":
						include_once($this->ruta."formulario/cambiaEstadoCriterio.php");
						break;

					case "habilitarCriterio":
						include_once($this->ruta."formulario/cambiaEstadoCriterio.php");
						break;

				case "inhabilitarTipoJurado":
					include_once($this->ruta."formulario/cambiaEstadoTipoJurado.php");
					break;

				case "habilitarTipoJurado":
					include_once($this->ruta."formulario/cambiaEstadoTipoJurado.php");
					break;

				case "editarActividad":
					include_once($this->ruta."formulario/actividades.php");
					break;

				case "nuevoTipoJurado":
					include_once($this->ruta."formulario/registrarTipoJurado.php");
					break;

				case "detalleCriterios":
					include_once($this->ruta."formulario/detalleCriterios.php");
					break;

				case "consultar":
					include_once($this->ruta."formulario/consultarTiposJurado.php");
					break;



        		}
		} else {
			$_REQUEST ['opcion'] = "mostrar";
			include_once ($this->ruta . "/formulario/consultarTiposJurado.php");
		}
	}
}
?>
