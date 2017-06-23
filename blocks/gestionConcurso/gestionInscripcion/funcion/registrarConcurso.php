<?php
namespace gestionConcurso\gestionInscripcion\funcion;
use gestionConcurso\gestionInscripcion\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorConcurso {

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
        $arregloDatos = array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                              'consecutivo_persona'=>!isset($_REQUEST['consecutivo_persona'])?0:$_REQUEST['consecutivo_persona'],
                              'codigo_tipo'=>$_REQUEST['tipo'],
                              'codigo_modalidad'=>$_REQUEST['modalidad'],
                              'nombre'=>$_REQUEST['nombre'],
                              'acuerdo'=>$_REQUEST['acuerdo'],
                              'descripcion'=>$_REQUEST['descripcion'],
                              'fecha_inicio_concurso'=>$_REQUEST['fecha_inicio_concurso'],
                              'fecha_fin_concurso'=>$_REQUEST['fecha_fin_concurso'],
                              'estado'=>$_REQUEST['estado']
            );
        
        if($arregloDatos['consecutivo_concurso']==0)
             {  $cadenaSql = $this->miSql->getCadenaSql ( 'registroConcurso',$arregloDatos );
                $resultadoConcurso = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroConcurso" );
                $_REQUEST['consecutivo_concurso']=$resultadoConcurso;
                
                $cadenaSql = $this->miSql->getCadenaSql ( 'consultaActividadObligatoria',$arregloDatos );
                $resultadoActividad = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
                foreach ($resultadoActividad as $key => $value) {
                    $datosCalendario=array('consecutivo_concurso'=> $resultadoConcurso,
                                           'consecutivo_actividad'=>$resultadoActividad[$key]['consecutivo_actividad'] ,
                                           'descripcion'=> 'Espacio tiempo para '.$resultadoActividad[$key]['descripcion'] ,
                                           'fecha_inicio'=> $_REQUEST['fecha_inicio_concurso'],
                                           'fecha_fin'=> $_REQUEST['fecha_fin_concurso'],
                                           'consecutivo_evaluar'=> 0,
                                        );
                    $cadenaSql = $this->miSql->getCadenaSql ('registroCalendarioConcurso',$datosCalendario);
                    $resultadoCalendario = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $datosCalendario, "registroCalendarioConcurso" );
                }
                
             }
        else {  $cadenaSql = $this->miSql->getCadenaSql ( 'actualizaConcurso',$arregloDatos );
                $resultadoConcurso = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarConcurso" );
        }
        
        if($resultadoConcurso)
            {   $_REQUEST['consecutivo']=0;$_REQUEST['consecutivo_persona'];
                $_REQUEST['consecutivo_dato']=$_REQUEST['consecutivo_concurso'];
                $this->miArchivo->procesarArchivo('datosConcurso');
                redireccion::redireccionar('actualizoConcurso',$arregloDatos);  exit();
            }else
            {
                redireccion::redireccionar('noActualizo',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorConcurso($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);
$resultado = $miRegistrador->procesarFormulario();
?>