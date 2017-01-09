<?

namespace bloquesNovedad\bloqueHojadeVida\bloqueFuncionario;

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
		
		//echo "prueba2";


		//var_dump($_REQUEST['opcion']);

		
		// Como se tiene un solo formulario no es necesario un switch para cargarlo:
		$this->ruta = $this->miConfigurador->getVariableConfiguracion ( "rutaBloque" );
	    /*
        if(isset($_REQUEST['opcion'])){
			include_once ($this->ruta . "/formulario/registroFuncionario.php");
		}else{
			include_once ($this->ruta . "/formulario/registroBusqueda.php");
		}*/
		
		if (isset($_REQUEST['opcion'])) {
		
			switch ($_REQUEST['opcion']) {
		
				case "mensaje":
					//var_dump("Paso de Redireccionador");exit;
					include_once($this->ruta . "/formulario/mensaje.php");
					break;
		
				case "consultar":
					include_once($this->ruta . "/formulario/consultarFuncionario.php");
					break;
					
				case "registroConsulta":
					include_once($this->ruta . "/formulario/registroBusqueda.php");
					break;
		
				case "registrar":
					include_once($this->ruta . "/formulario/registroFuncionario.php");
					break;
					
				case "verDetalle" :
					include_once ($this->ruta . "/formulario/verDetalleFuncionario.php");
					break;
		
				case "modificar":
					include_once($this->ruta . "/formulario/modificarFuncionario.php");
					break;
					
			}
		} else {
			$_REQUEST['opcion'] = "buscarAprobado";
			include_once($this->ruta . "/formulario/registroBusqueda.php");
		}
	}
}
?>
