<?php
namespace gestionConcurso\evaluacion\funcion;
use gestionConcurso\evaluacion\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorEvaluacion {

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

        $fecha = date("Y-m-d H:i:s");

      /*  $arregloDatos = array('grupo'=>$_REQUEST['grupo'],
                              'inscrito'=>$_REQUEST['inscrito'],
                              'criterio'=>$_REQUEST['criterio'],
                              'puntaje'=>$_REQUEST['puntaje'],
                              'observacion'=>$_REQUEST['observacion'],
                              'fecha'=> $fecha
            );*/

            $arregloDatos = array('grupo'=>1,
                                  'inscrito'=>4,
                                  'criterio'=>2,
                                  'puntaje'=>15,
                                  'observacion'=>"Observacion",
                                  'fecha'=> $fecha
                );

            $cadenaSql = $this->miSql->getCadenaSql ( 'registroEvaluacion',$arregloDatos );
            $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroEvaluacion" );

            if($resultado){
                redireccion::redireccionar('registroEvaluacion',$arregloDatos);  exit();
            }
            else{
                redireccion::redireccionar('noregistroEvaluacion',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorEvaluacion($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);
$resultado = $miRegistrador->procesarFormulario();
?>
