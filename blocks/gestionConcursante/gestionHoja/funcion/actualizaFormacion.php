<?php
namespace gestionConcursante\gestionHoja\funcion;
use gestionConcursante\gestionHoja\funcion\redireccion;
include_once ('redireccionar.php');
include_once ('cargarArchivo.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorFormacion {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;
    var $miLogger;
    var $miArchivo;

    function __construct($lenguaje, $sql, $funcion, $miLogger) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
        $this->miLogger= $miLogger;
        $this->miArchivo = new CargarArchivo($lenguaje, $sql, $funcion, $miLogger);
    }

    function procesarFormulario() {
        $conexion="estructura";
	$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $arregloDatos = array('id_usuario'=>$_REQUEST['id_usuario'],
                              'consecutivo_formacion'=>$_REQUEST['consecutivo_formacion'],
                              'consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                              'codigo_modalidad'=>$_REQUEST['modalidad'],
                              'codigo_nivel'=>$_REQUEST['nivel_formacion'],
                              'pais_formacion'=>$_REQUEST['pais_formacion'],
                              'codigo_institucion'=>$_REQUEST['codigo_institucion'],
                              'nombre_institucion'=>$_REQUEST['nombre_institucion'],
                              'codigo_programa'=>isset($_REQUEST['consecutivo_programa'])?$_REQUEST['consecutivo_programa']:0,
                              'nombre_programa'=>$_REQUEST['nombre_programa'],
                              'cursos_aprobados'=>$_REQUEST['cursos_aprobados'],
                              'graduado'=>$_REQUEST['graduado'],
                              'fecha_grado'=>$_REQUEST['fecha_grado'],
                              'promedio'=>$_REQUEST['promedio'],
                              'nombre'=>$_REQUEST['nombre'],
                              'apellido'=>$_REQUEST['apellido'],
            );
        if($arregloDatos['consecutivo_formacion']==0)
             {  $cadenaSql = $this->miSql->getCadenaSql ( 'registroFormacion',$arregloDatos );
                $resultadoFormacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroFormacion" );
                $_REQUEST['consecutivo_formacion']=$resultadoFormacion;
             }
        else {  $cadenaSql = $this->miSql->getCadenaSql ( 'actualizarFormacion',$arregloDatos );
                $resultadoFormacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarFormacion" );
        }
        
        
        if($resultadoFormacion)
            {   $_REQUEST['consecutivo']=$_REQUEST['consecutivo_persona'];
                $_REQUEST['consecutivo_dato']=$_REQUEST['consecutivo_formacion'];
                $this->miArchivo->procesarArchivo('datosFormacion');
                redireccion::redireccionar('actualizoFormacion',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorFormacion($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>