<?php
namespace gestionConcurso\gestionJurado\funcion;
use gestionConcurso\gestionJurado\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorCriterioTipoJurado{

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

        $arregloDatos = array('tipo_jurado'=>$_REQUEST['tipo_jurado'],
                              'criterio_evaluacion'=>$_REQUEST['criterio_evaluacion']
        );

        $cadenaSql = $this->miSql->getCadenaSql ( 'registrarCriterioTipoJurado',$arregloDatos );
        $resultadoCriterio = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroCriterioTipoJurado" );

        if($resultadoCriterio){
         	redireccion::redireccionar('registroCriterioTipoJurado',$arregloDatos);  exit();
        }else{
        	redireccion::redireccionar('noRegistroCriterioTipoJurado',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorCriterioTipoJurado($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);
$resultado = $miRegistrador->procesarFormulario();
?>
