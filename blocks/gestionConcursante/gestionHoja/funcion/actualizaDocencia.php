<?php
namespace gestionConcursante\gestionHoja\funcion;
use gestionConcursante\gestionHoja\funcion\redireccion;
include_once ('redireccionar.php');
include_once ('cargarArchivo.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorDocencia {

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
                              'consecutivo_docencia'=>$_REQUEST['consecutivo_docencia'],
                              'consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                              'pais_docencia'=>$_REQUEST['pais_docencia'],
                              'nivel_institucion_docencia'=>$_REQUEST['nivel_institucion_docencia'],
                              'codigo_institucion_docencia'=>$_REQUEST['codigo_institucion_docencia'],
                              'nombre_institucion_docencia'=>$_REQUEST['nombre_institucion_docencia'],
                              'direccion_institucion_docencia'=>isset($_REQUEST['direccion_institucion_docencia'])?$_REQUEST['direccion_institucion_docencia']:'',
                              'correo_institucion_docencia'=>$_REQUEST['correo_institucion_docencia'],
                              'telefono_institucion_docencia'=>$_REQUEST['telefono_institucion_docencia'],
                              'codigo_nivel_docencia'=>$_REQUEST['codigo_nivel_docencia'],
                              'codigo_vinculacion'=>$_REQUEST['codigo_vinculacion'],
                              'nombre_vinculacion'=>$_REQUEST['nombre_vinculacion'],
                              'descripcion_docencia'=>$_REQUEST['descripcion_docencia'],
                              'docencia_actual'=>$_REQUEST['docencia_actual'],
                              'fecha_inicio_docencia'=>$_REQUEST['fecha_inicio_docencia'],
                              'fecha_fin_docencia'=>$_REQUEST['fecha_fin_docencia'],
                              'nombre'=>$_REQUEST['nombre'],
                              'apellido'=>$_REQUEST['apellido'],
             );
        
        if($arregloDatos['consecutivo_docencia']==0)
             { $cadenaSql = $this->miSql->getCadenaSql ( 'registroDocencia',$arregloDatos );
               $resultadoDocencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroExperienciaDocencia" );
             }
        else { $cadenaSql = $this->miSql->getCadenaSql ( 'actualizarDocencia',$arregloDatos );
               $resultadoDocencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarExperienciaDocencia" );
        }
        

        if($resultadoDocencia)
            {   $_REQUEST['consecutivo']=$_REQUEST['consecutivo_persona'];
                $_REQUEST['consecutivo_dato']=$_REQUEST['consecutivo_docencia'];
                $this->miArchivo->procesarArchivo('datosDocencia');
                redireccion::redireccionar('actualizoDocencia',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorDocencia($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>