<?php

namespace gestionConcurso\gestionInscripcion\funcion;

use gestionConcurso\gestionInscripcion\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class CambiarEstadoPerfil {

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
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $arregloDatos = array('consecutivo_perfil'=>$_REQUEST['consecutivo_perfil'],
                              'consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                              'nombre'=>$_REQUEST['nombre'],
                              'estado'=>$_REQUEST['estadoPerfil']
            );
        $this->cadena_sql = $this->miSql->getCadenaSql("actualizaEstadoPerfil", $arregloDatos);
        $resultadoEstado = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "actualiza", $arregloDatos, "actualizaEstadoPerfilConcurso" );
	if($resultadoEstado)
            {redireccion::redireccionar($_REQUEST['opcion'],$arregloDatos);
            }
        else
            {redireccion::redireccionar('no'.$_REQUEST['opcion'],$arregloDatos);
             exit();
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

$miRegistrador = new CambiarEstadoPerfil($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>