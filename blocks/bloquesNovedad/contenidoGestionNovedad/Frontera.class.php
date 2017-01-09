<?

namespace bloquesNovedad\contenidoGestionNovedad;

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
                $_REQUEST['botonRegistrarCargo']='false';
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
       

		
        if(isset($_REQUEST['opcion'])){
                          
                           switch ($_REQUEST ['opcion']) {
				
				case "siguienteUno" :
                                        include_once ($this->ruta . "/formulario/asociarNovedad.php");
					break;
                                case "asociacionUsuario" :
                                        include_once ($this->ruta . "/formulario/asociarNovedadUsuario.php");
					break;  
                                case "esporadica" :
                                        include_once ($this->ruta . "/formulario/formularioEsporadica.php");
					break;  
                                        
                                        
                                        
                                
                           }
                         
        		
		}else{
                    
			include_once ($this->ruta . "/formulario/form.php");
		}
	}
}
?>
