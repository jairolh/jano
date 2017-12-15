<?php
namespace gestionConcursante\gestionHoja\funcion;
use gestionConcursante\gestionHoja\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorActividad {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;
    var $miLogger;
    var $miArchivo;

    function __construct($lenguaje, $sql, $funcion, $miLogger, $miArchivo) {
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
        
        $arregloDatos = array('id_usuario'=>$_REQUEST['id_usuario'],
                              'consecutivo_actividad'=>$_REQUEST['consecutivo_actividad'],
                              'consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                              'pais_actividad'=>$_REQUEST['pais_actividad'],
                              'codigo_nivel_institucion'=>$_REQUEST['nivel_institucion_actividad'],
                              'codigo_institucion_actividad'=>$_REQUEST['codigo_institucion_actividad'],
                              'nombre_institucion_actividad'=>$_REQUEST['nombre_institucion_actividad'],
                              'correo_institucion_actividad'=>$_REQUEST['correo_institucion_actividad'],
                              'telefono_institucion_actividad'=>$_REQUEST['telefono_institucion_actividad'],
                              'codigo_tipo_actividad'=>$_REQUEST['codigo_tipo_actividad'],
                              'nombre_tipo_actividad'=>(isset($_REQUEST['nombre_tipo_actividad'])?$_REQUEST['nombre_tipo_actividad']:''),
                              'nombre_actividad'=>$_REQUEST['nombre_actividad'],
                              'descripcion_actividad'=>$_REQUEST['descripcion_actividad'],
                              'jefe_actividad'=>$_REQUEST['jefe_actividad'],
                              'fecha_inicio_actividad'=>$_REQUEST['fecha_inicio_actividad'],
                              'fecha_fin_actividad'=>$_REQUEST['fecha_fin_actividad'],
                              'nombre'=>$_REQUEST['nombre'],
                              'apellido'=>$_REQUEST['apellido'],
             );
        if($arregloDatos['consecutivo_actividad']==0)
             { $cadenaSql = $this->miSql->getCadenaSql ( 'registroActividad',$arregloDatos );
               $resultadoActividad = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroActividadAcademica" );
               $_REQUEST['consecutivo_actividad']=$resultadoActividad;
             }
        else { $cadenaSql = $this->miSql->getCadenaSql ( 'actualizarActividad',$arregloDatos );
               $resultadoActividad = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarActividadAcademica" );
        }
        
        if($resultadoActividad)
            {   $datosSoporte=array('consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                    'consecutivo_dato'=>$_REQUEST['consecutivo_actividad'],
                    'id_usuario'=>$_REQUEST['id_usuario']);
                $this->miArchivo->procesarArchivo($datosSoporte);         
                redireccion::redireccionar('actualizoActividad',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorActividad($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);
$resultado = $miRegistrador->procesarFormulario();
?>