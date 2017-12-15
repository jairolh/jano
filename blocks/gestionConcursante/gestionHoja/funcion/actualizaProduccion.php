<?php
namespace gestionConcursante\gestionHoja\funcion;
use gestionConcursante\gestionHoja\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorProduccion {

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
                              'consecutivo_produccion'=>$_REQUEST['consecutivo_produccion'],
                              'consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                              'pais_produccion'=>$_REQUEST['pais_produccion'],
                              'departamento_produccion'=>$_REQUEST['departamento_produccion'],
                              'ciudad_produccion'=>$_REQUEST['ciudad_produccion'],
                              'codigo_tipo_produccion'=>$_REQUEST['codigo_tipo_produccion'],
                              'nombre_tipo_produccion'=>$_REQUEST['nombre_tipo_produccion'],
                              'titulo_produccion'=>$_REQUEST['titulo_produccion'],
                              'nombre_autor'=>$_REQUEST['nombre_autor'],
                              'nombre_producto_incluye'=>$_REQUEST['nombre_producto_incluye'],
                              'nombre_editorial'=>$_REQUEST['nombre_editorial'],
                              'volumen'=>$_REQUEST['volumen'],
                              'pagina_producto'=>$_REQUEST['pagina_producto'],
                              'codigo_isbn'=>$_REQUEST['codigo_isbn'],
                              'codigo_issn'=>$_REQUEST['codigo_issn'],
                              'indexado'=>$_REQUEST['indexado'],
                              'descripcion_produccion'=>$_REQUEST['descripcion_produccion'],
                              'fecha_produccion'=>$_REQUEST['fecha_produccion'],
                              'direccion_produccion'=>$_REQUEST['direccion_produccion'],
                              'nombre'=>$_REQUEST['nombre'],
                              'apellido'=>$_REQUEST['apellido'],
             );
        
        if($arregloDatos['consecutivo_produccion']==0)
             { $cadenaSql = $this->miSql->getCadenaSql ( 'registroProduccion',$arregloDatos );
               $resultadoProduccion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroExperienciaProduccion" );
               $_REQUEST['consecutivo_produccion']=$resultadoProduccion;
             }
        else { $cadenaSql = $this->miSql->getCadenaSql ( 'actualizarProduccion',$arregloDatos );
               $resultadoProduccion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarExperienciaProduccion" );
        }
        
        if($resultadoProduccion)
            {   $datosSoporte=array('consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                    'consecutivo_dato'=>$_REQUEST['consecutivo_produccion'],
                    'id_usuario'=>$_REQUEST['id_usuario']);
                $this->miArchivo->procesarArchivo($datosSoporte);
                
                redireccion::redireccionar('actualizoProduccion',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorProduccion($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);

$resultado = $miRegistrador->procesarFormulario();
?>