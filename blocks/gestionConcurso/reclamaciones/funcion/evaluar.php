<?php
namespace gestionConcurso\reclamaciones\funcion;
use gestionConcurso\reclamaciones\funcion\redireccion;
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

        if($_REQUEST['validacion']=='SI'){
          //inactivar registro de la validación
          $parametro=array(
            'validacion'=>$_REQUEST['evaluar_respuesta']
          );
          $cadena_sql = $this->miSql->getCadenaSql("inactivarValidacion", $parametro);
          $resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "actualiza", $parametro, "inactivarValidacion");
        }

        $parametro=array(
          'reclamacion'=>$_REQUEST['reclamacion'],
          'respuesta'=>$_REQUEST['validacion'],
          'observacion'=>$_REQUEST['observaciones'],
          'fecha'=>$fecha,
          'evaluar_respuesta'=>$_REQUEST['evaluar_respuesta'],
          'evaluador'=>$_REQUEST['usuario']
        );

        $cadena_sql = $this->miSql->getCadenaSql("registroEvaluacionReclamacion", $parametro);
        $resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "registra", $parametro, "registroEvaluacionReclamacion");

        if($resultado){
            redireccion::redireccionar('evaluoReclamacion',$parametro);  exit();
        }
        else{
            redireccion::redireccionar('noEvaluoReclamacion',$parametro);  exit();
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
