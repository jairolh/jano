<?php
namespace gestionConcursante\gestionHoja\funcion;
use gestionConcursante\gestionHoja\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorInvestigacion {

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
        $arregloDatos = array('id_usuario'=>$_REQUEST['id_usuario'],
                              'consecutivo_investigacion'=>$_REQUEST['consecutivo_investigacion'],
                              'consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                              'pais_investigacion'=>$_REQUEST['pais_investigacion'],
                              'nivel_institucion_investigacion'=>$_REQUEST['nivel_institucion_investigacion'],
                              'codigo_institucion_investigacion'=>$_REQUEST['codigo_institucion_investigacion'],
                              'nombre_institucion_investigacion'=>$_REQUEST['nombre_institucion_investigacion'],
                              'direccion_institucion_investigacion'=>isset($_REQUEST['direccion_institucion_investigacion'])?$_REQUEST['direccion_institucion_investigacion']:'',
                              'correo_institucion_investigacion'=>$_REQUEST['correo_institucion_investigacion'],
                              'telefono_institucion_investigacion'=>$_REQUEST['telefono_institucion_investigacion'],
                              'titulo_investigacion'=>$_REQUEST['titulo_investigacion'],
                              'jefe_investigacion'=>$_REQUEST['jefe_investigacion'],
                              'descripcion_investigacion'=>$_REQUEST['descripcion_investigacion'],
                              'investigacion_actual'=>$_REQUEST['investigacion_actual'],
                              'fecha_inicio_investigacion'=>$_REQUEST['fecha_inicio_investigacion'],
                              'fecha_fin_investigacion'=>$_REQUEST['fecha_fin_investigacion'],
                              'grupo_investigacion'=>$_REQUEST['grupo_investigacion'],
                              'categoria_grupo'=>$_REQUEST['categoria_grupo'],
                              'direccion_investigacion'=>$_REQUEST['direccion_investigacion'],
                              'nombre'=>$_REQUEST['nombre'],
                              'apellido'=>$_REQUEST['apellido'],
             );
        
        if($arregloDatos['consecutivo_investigacion']==0)
             { $cadenaSql = $this->miSql->getCadenaSql ( 'registroInvestigacion',$arregloDatos );
               $resultadoInvestigacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroExperienciaInvestigacion" );
               $_REQUEST['consecutivo_investigacion']=$resultadoInvestigacion;
             }
        else { $cadenaSql = $this->miSql->getCadenaSql ( 'actualizarInvestigacion',$arregloDatos );
               $resultadoInvestigacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarExperienciaInvestigacion" );
        }
        
        if($resultadoInvestigacion)
            {   
                $datosSoporte=array('consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                    'consecutivo_dato'=>$_REQUEST['consecutivo_investigacion'],
                    'id_usuario'=>$_REQUEST['id_usuario']);
                $this->miArchivo->procesarArchivo($datosSoporte);                
                redireccion::redireccionar('actualizoInvestigacion',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorInvestigacion($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);

$resultado = $miRegistrador->procesarFormulario();
?>