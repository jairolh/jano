<?php

namespace gestionConcurso\gestionInscripcion;

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
        var $miArchivo;
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
                    {
                            case "cerrarSoporte":
                                    $_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
                                    $this->cerrarSoporte();
                                break;
                            case "cerrarFase":
                                    $_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
                                    switch ($_REQUEST ['fase'])
                                        {   case "requisito":
                                                  $this->cerrarRequisitos();
                                            break;
                                            case "evaluacion":
                                                  $this->cerrarEvaluacion();
                                            break;
                                            case "elegibles":
                                                  $this->cerrarElegibles();
                                            break;
                                        }
                                break;

                            /**********/
                            case "guardarConcurso":
                                    $_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
                                    $this->guardarConcurso();
                                break;
                            case "guardarCriterioConcurso":
                                    $_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
                                    $this->guardarCriterioConcurso();
                                break;
                            case "guardarCalendarioConcurso":
                                    $_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
                                    $this->guardarCalendarioConcurso();
                                break;
                            case "guardarPerfilConcurso":
                                    $_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
                                    $this->guardarPerfilConcurso();
                                break;
    			    case "guardarAspirantesJurado":
                                    $_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
                                    $this->guardarAspirantesJurado();
                                break;

                            case "borrar":
                                    $this->borrarDatos();
                                    break;

                            case "inhabilitar":
                                    $_REQUEST["estado"]=0;
                                    $this->cambiarEstado();
                                break;

                            case "habilitar":
                                    $_REQUEST["estado"]=1;
                                    $this->cambiarEstado();
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
                $this->miArchivo = \soporte::singleton();

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

	function guardarAspirantesJurado(){
		include_once($this->ruta."/funcion/guardarAspirantesJurado.php");
        }

	function cerrarSoporte()
	{
		include_once($this->ruta."/funcion/cerrarSoporteConcurso.php");
	}
	function cerrarRequisitos()
	{
            switch ($_REQUEST ['tipo_cierre'])
                {   case "parcial":
                        include_once($this->ruta."/funcion/cerrarRequisitosPerfilParcial.php");
                    break;
                    case "final":
                        include_once($this->ruta."/funcion/cerrarRequisitosPerfilFinal.php");
                    break;
                }
                
                
	}
	function cerrarEvaluacion()
	{
            switch ($_REQUEST ['tipo_cierre'])
                {   case "parcial":
                        include_once($this->ruta."/funcion/cerrarEvaluacionParcial.php");
                    break;
                    case "final":
                        include_once($this->ruta."/funcion/cerrarEvaluacionFinal.php");
                    break;
                }
                
	}
	function cerrarElegibles()
	{
            include_once($this->ruta."/funcion/cerrarElegibles.php");
	}        
        function guardarCalendarioConcurso()
	{
		include_once($this->ruta."/funcion/registrarCalendarioConcurso.php");
	}

	function borrarDatos()
	{
		include_once($this->ruta."/funcion/borrarRol.php");
	}

        function editarDatos()
	{
		include_once($this->ruta."/funcion/editarRol.php");
	}

        function cambiarEstado()
	{
		include_once($this->ruta."/funcion/cambiarEstado.php");
	}

}
?>
