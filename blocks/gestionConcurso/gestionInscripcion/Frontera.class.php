<?php

namespace gestionConcurso\gestionInscripcion;

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

				case "agregarAspirantesJurado" :
					include_once ($this->ruta . "/formulario/tabs/datosAspirantes.php");
					break;

				case "mensaje" :
					include_once ($this->ruta . "/formulario/mensaje.php");
					break;
				 case "listar":
					include_once ($this->ruta . "/formulario/consultarConcursoEjecuta.php");
					break;
                                case "detalle":
					include_once($this->ruta."formulario/detalleEjecucion.php");
					break;
                                case "cerrarFase":
                                        switch($_REQUEST ['fase'])
                                            {
                                               case 'soporte':
                                                  include_once($this->ruta."formulario/cerrarSoportesConcurso.php");
                                                break;
                                                default:
                                                  include_once($this->ruta."formulario/cerrarFase.php");
                                                break;

                                            }
                                        break;

                                    /******/
				case "nuevo":
					include_once($this->ruta."formulario/nuevo.php");
					break;

                                case "editar":
					include_once($this->ruta."formulario/nuevo.php");
					break;

                                case "borrar":
					include_once($this->ruta."formulario/borrar.php");
					break;
                                case "inhabilitar":
					include_once($this->ruta."formulario/cambiaEstadoConcurso.php");
					break;

                                case "habilitar":
					include_once($this->ruta."formulario/cambiaEstadoConcurso.php");
					break;
                                case "inhabilitarCriterio":
					include_once($this->ruta."formulario/cambiaEstadoCriterio.php");
					break;
                                case "habilitarCriterio":
					include_once($this->ruta."formulario/cambiaEstadoCriterio.php");
					break;
                                case "inhabilitarCalendario":
					include_once($this->ruta."formulario/cambiaEstadoCalendario.php");
					break;
                                case "habilitarCalendario":
					include_once($this->ruta."formulario/cambiaEstadoCalendario.php");
					break;
                                case "inhabilitarPerfil":
					include_once($this->ruta."formulario/cambiaEstadoPerfil.php");
					break;
                                case "habilitarPerfil":
					include_once($this->ruta."formulario/cambiaEstadoPerfil.php");
					break;
        		}
		} else {
			$_REQUEST ['opcion'] = "listar";
			include_once ($this->ruta . "/formulario/consultarConcursoEjecuta.php");
		}
	}
}
?>
