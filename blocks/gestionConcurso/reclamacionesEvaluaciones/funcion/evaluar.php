<?php
namespace gestionConcurso\reclamacionesEvaluaciones\funcion;
use gestionConcurso\reclamacionesEvaluaciones\funcion\redireccion;
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

        $parametro = array (
                'reclamacion' => $_REQUEST['reclamacion'],
                'usuario' => $_REQUEST['usuario']
        );
        $cadena_sql = $this->miSql->getCadenaSql ( "consultarDetalleReclamacion2", $parametro );
        $resultadoDetalleReclamacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

        foreach ( $resultadoDetalleReclamacion as $key => $value ) {
          if($_REQUEST['validacion'.$key]=='SI'){
            //inactivar registro de la evaluación
            $parametro=array(
              'evaluacion'=>$resultadoDetalleReclamacion[$key]['evaluacion_id']
            );
            $cadena_sql = $this->miSql->getCadenaSql("inactivarValidacion", $parametro);
            $resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "actualiza", $parametro, "inactivarValidacion");

          }

          $parametro=array(
            'reclamacion'=>$_REQUEST['reclamacion'],
            'respuesta'=>$_REQUEST['validacion'.$key],
            'observacion'=>$_REQUEST['observaciones'.$key],
            'fecha'=>$fecha,
            'evaluar_respuesta'=>$resultadoDetalleReclamacion[$key]['evaluacion_id'],
            'evaluador'=>$_REQUEST['usuario']
          );

          $cadena_sql = $this->miSql->getCadenaSql("registroEvaluacionReclamacion", $parametro);
          $resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "registra", $parametro, "registroEvaluacionReclamacion");

            if($_REQUEST['validacion'.$key]=="SI"){
                $parametro=array(
                    'grupo'=>$resultadoDetalleReclamacion[$key]['id_grupo'],
                    'inscrito'=>$_REQUEST['consecutivo_inscrito'],
                    'id_evaluar'=>$resultadoDetalleReclamacion[$key]['id_evaluar'],
                    'puntaje'=>$_REQUEST['puntaje'.$key],
                    'observacion'=>"Evaluación asociada a la reclamación #".$_REQUEST['reclamacion'],
                    'fecha'=>$fecha,
                    'reclamacion'=>$_REQUEST['reclamacion'],
                    'consecutivo_concurso='.$_REQUEST['consecutivo_concurso']
                  );

                  $cadena_sql = $this->miSql->getCadenaSql("registroNuevaEvaluacion", $parametro);
                  $resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "registra", $parametro, "registroEvaluacionReclamacion");
            }


        }
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
