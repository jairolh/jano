<?php

namespace gestionConcurso\caracterizaConcurso\funcion;

use gestionConcurso\caracterizaConcurso\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorPerfil {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;
    var $miLogger;

    function __construct($lenguaje, $sql, $funcion, $miLogger) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
        $this->miLogger= $miLogger;
    }

    function procesarFormulario() {

        $conexion="estructura";
		$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        
		$datos = array('nombre'=> $_REQUEST['nombreCriterio'],
				'factor'=> $_REQUEST['seleccionFactor']
		);
		
		$cadena_sql = $this->miSql->getCadenaSql("registrarCriterio", $datos);
		$resultadoCriterio = $esteRecursoDB->ejecutarAcceso($cadena_sql, "registra");
		
		if($resultadoCriterio){
			redireccion::redireccionar('insertoCriterio',$datos);  exit();
		}else {
			redireccion::redireccionar('noInsertoCriterio',$datos);  exit();
		}
  
    }

    function resetForm() {
        foreach ($_REQUEST as $clave => $valor) {

            if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
                unset($_REQUEST [$clave]);
            }
        }
    }

}

$miRegistrador = new RegistradorPerfil($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>