<?php

namespace usuarios\gestionRoles;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
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
	var $miInspectorHTML;
	var $error;
	var $miRecursoDB;
	var $crypto;
	var $miLogger; 
        // function verificarCampos() {
	// include_once ($this->ruta . "/funcion/verificarCampos.php");
	// if ($this->error == true) {
	// return false;
	// } else {
	// return true;
	// }
	// }
	function redireccionar($opcion, $valor = "") {
		include_once ($this->ruta . "/funcion/redireccionar.php");
	}
	function funcionEjemplo() {
		include_once ($this->ruta . "/funcion/funcionEjemplo.php");
	}
	function procesarAjax() {
		include_once ($this->ruta . "funcion/procesarAjax.php");
	}
	function registrar() {
		include_once ($this->ruta . "funcion/registrar.php");
	}
	function action() {
		
		// Evitar qu44444444rrrre se ingrese codigo HTML y PHP en los campos de texto
		// Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir = "";
		$_REQUEST = $this->miInspectorHTML->limpiarPHPHTML ( $_REQUEST );
		
		// Aquí se coloca el código que procesará los diferentes formularios que pertenecen al bloque
		// aunque el código fuente puede ir directamente en este script, para facilitar el mantenimiento
		// se recomienda que aqui solo sea el punto de entrada para incluir otros scripts que estarán
		// en la carpeta funcion
		
		// Importante: Es adecuado que sea una variable llamada opcion o action la que guie el procesamiento:
		if (isset ( $_REQUEST ['procesarAjax'] ))
                    {
                        $this->procesarAjax ();
                    } 
                elseif (isset ( $_REQUEST ["opcion"] ))
                    {
                    
                     switch ($_REQUEST ['opcion'])
                    {       case "guardarDatos":
                                    $this->guardarDatos();
                                break;

                            case "guardarDatosPerfil":
                                    $_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
                                    $this->guardarDatosPerfil();
                                break;

                            case "guardarDatosSub":
                                    $_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
                                    $this->guardarDatosSub();
                                break;

                            
                            case "editarDatos":
                                    $_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
                                    $this->editarDatos();
                                break;

                            case "borrar":
                                    $this->borrarDatos();
                                    break;    

                            case "inhabilitar":
                                    $_REQUEST["estado"]=0;
                                    $this->cambiarEstadoRol(); 
                                break;     

                            case "habilitar":
                                    $_REQUEST["estado"]=1;
                                    $this->cambiarEstadoRol(); 
                                break;       

                        }
                                       
// 			if ($validacion == false) {
// 				// Instanciar a la clase pagina con mensaje de correcion de datos
// 				echo "Datos Incorrectos";
// 			} else {
// 				// Validar las variables para evitar un tipo insercion de SQL
// 				$_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
				
// 				$this->funcionEjemplo ();
// 				$this->redireccionar ( "exito" );
// 			}
		}
	}
	function __construct() {
		$this->miConfigurador = \Configurador::singleton ();
		
		$this->miInspectorHTML = \InspectorHTML::singleton ();
		
		$this->ruta = $this->miConfigurador->getVariableConfiguracion ( "rutaBloque" );
		
		$this->miMensaje = \Mensaje::singleton ();
		
                $this->miLogger = \logger::singleton();
		
                $conexion = "aplicativo";
		$this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		if (! $this->miRecursoDB) {
			
			$this->miConfigurador->fabricaConexiones->setRecursoDB ( $conexion, "tabla" );
			$this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
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
        
        /*Funciones propias*/
        	
	function verificarCampos(){
		include_once($this->ruta."/funcion/verificarCampos.php");
		if($this->error==true){
			return false;
		}else{
			return true;
		}
	}
	
	function guardarDatos()
	{
		include_once($this->ruta."/funcion/registrarPerfil.php");
	}	
	
	function borrarDatos()
	{
		include_once($this->ruta."/funcion/borrarRol.php");
	}	
        
        function editarDatos()
	{
		include_once($this->ruta."/funcion/editarRol.php");
	}
        
        function guardarDatosSub()
	{
		include_once($this->ruta."/funcion/registrarSubsistema.php");
	}
        
        function guardarDatosPerfil()
	{
		include_once($this->ruta."/funcion/registrarPerfil.php");
	}
        
        function cambiarEstadoRol()
	{
		include_once($this->ruta."/funcion/cambiarEstadoRol.php");
	}
 

}
?>
