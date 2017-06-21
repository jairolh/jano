<?php

namespace gestionConcursante\concursosInscritos;\funcion;

use gestionConcurso\gestionJurado\funcion\redireccion;

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

		$datos = array('nombre'=> $_REQUEST['nombreTipoJurado'],
				'descripcion'=> $_REQUEST['descripcionTipoJurado']
		);

		$cadena_sql = $this->miSql->getCadenaSql("registrarTipoJurado", $datos);
		$resultadoTipoJurado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "registra", $datos, "registroTipoJurado");


		if($resultadoTipoJurado){
			redireccion::redireccionar('insertoTipoJurado',$datos);  exit();
		}else {
			redireccion::redireccionar('noInsertoTipoJurado',$datos);  exit();
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
