<?php
namespace gestionConcursante\gestionHoja\funcion;
use gestionConcursante\gestionHoja\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorExperiencia {

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
        
        
        $parametronivel=array('tipo_nivel'=> 'Experiencia',
                              'nombre'=> 'Profesional'  
                             );
                                    
        $cadena_sql = $this->miSql->getCadenaSql("consultarNivel",$parametronivel);
        $resultadoNivel = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");                                    

        $arregloDatos = array('id_usuario'=>$_REQUEST['id_usuario'],
                              'consecutivo_experiencia'=>$_REQUEST['consecutivo_experiencia'],
                              'consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                              'codigo_nivel_experiencia'=>$resultadoNivel[0]['codigo_nivel'],
                              'pais_experiencia'=>$_REQUEST['pais_experiencia'],
                              'codigo_institucion'=>$_REQUEST['codigo_institucion'],
                              'nombre_institucion'=>$_REQUEST['nombre_institucion_experiencia'],
                              'direccion_institucion'=> (isset($_REQUEST['direccion_institucion'])?$_REQUEST['direccion_institucion']:''),
                              'correo_institucion'=>$_REQUEST['correo_institucion'],
                              'telefono_institucion'=>$_REQUEST['telefono_institucion'],
                              'codigo_nivel_institucion'=>$_REQUEST['nivel_institucion'],
                              'cargo'=>$_REQUEST['cargo'],
                              'descripcion_cargo'=>$_REQUEST['descripcion_cargo'],
                              'actual'=>$_REQUEST['cargo_actual'],
                              'fecha_inicio'=>$_REQUEST['fecha_inicio'],
                              'fecha_fin'=>$_REQUEST['fecha_fin'],
                              'nombre'=>$_REQUEST['nombre'],
                              'apellido'=>$_REQUEST['apellido'],
             );
        
        
        if($arregloDatos['consecutivo_experiencia']==0)
             { $cadenaSql = $this->miSql->getCadenaSql ( 'registroExperiencia',$arregloDatos );
               $resultadoExperiencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroExperienciaProfesional" );
               $_REQUEST['consecutivo_experiencia']=$resultadoExperiencia;
             }
        else {  $cadenaSql = $this->miSql->getCadenaSql ( 'actualizarExperiencia',$arregloDatos );
               $resultadoExperiencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarExperienciaProfesional" );
        }
        
        if($resultadoExperiencia)
            {   $datosSoporte=array('consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                    'consecutivo_dato'=>$_REQUEST['consecutivo_experiencia'],
                    'id_usuario'=>$_REQUEST['id_usuario']);
                $this->miArchivo->procesarArchivo($datosSoporte);
                redireccion::redireccionar('actualizoProfesional',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorExperiencia($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);

$resultado = $miRegistrador->procesarFormulario();
?>