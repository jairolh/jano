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
    var $miArchivo;

    function __construct($lenguaje, $sql, $funcion, $miLogger,$miArchivo) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
        $this->miLogger= $miLogger;
        $this->miArchivo = $miArchivo;
    }

    function procesarFormulario() {
        $conexion="estructura";
		$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $arregloDatos = array('tipo_jurado'=>$_REQUEST['tipo_jurado'],
                              'criterio_evaluacion'=>$_REQUEST['criterio_evaluacion']
        );

        /*if($arregloDatos['consecutivo_evaluar']==0)
             {  $cadenaSql = $this->miSql->getCadenaSql ( 'registroCriterioConcurso',$arregloDatos );
                $resultadoConcurso = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroCriterioConcurso" );
                $_REQUEST['consecutivo_evaluar']=$resultadoConcurso;
             }
        else {  $cadenaSql = $this->miSql->getCadenaSql ( 'actualizaCriterioConcurso',$arregloDatos );
                $resultadoConcurso = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarCriterioConcurso" );
             }*/


        $cadenaSql = $this->miSql->getCadenaSql ( 'registrarCriterioTipoJurado',$arregloDatos );
        $resultadoCriterio = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroCriterioTipoJurado" );

        //var_dump($resultadoCriterio);
        //exit;


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

$miRegistrador = new RegistradorCriterioTipoJurado($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);
$resultado = $miRegistrador->procesarFormulario();
?>
