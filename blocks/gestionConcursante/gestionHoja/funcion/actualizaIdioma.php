<?php
namespace gestionConcursante\gestionHoja\funcion;
use gestionConcursante\gestionHoja\funcion\redireccion;
include_once ('redireccionar.php');
include_once ('cargarArchivo.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorIdioma {

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
                              'consecutivo_conocimiento'=>$_REQUEST['consecutivo_conocimiento'],
                              'consecutivo_persona'=>$_REQUEST['consecutivo_persona'],
                              'codigo_idioma'=>$_REQUEST['codigo_idioma'],
                              'porc_lee'=>$_REQUEST['porc_lee'],
                              'porc_escribe'=>$_REQUEST['porc_escribe'],
                              'porc_habla'=>$_REQUEST['porc_habla'],
                              'nombre'=>$_REQUEST['nombre'],
                              'apellido'=>$_REQUEST['apellido'],
            );

        if($arregloDatos['consecutivo_conocimiento']==0)
             {  $cadenaSql = $this->miSql->getCadenaSql ( 'registroIdioma',$arregloDatos );
                $resultadoIdioma = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registrarConocimientoIdioma" );
             }
        else {  $cadenaSql = $this->miSql->getCadenaSql ( 'actualizarIdioma',$arregloDatos );
                $resultadoIdioma = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarConocimientoIdioma" );
        }
        
        if($resultadoIdioma)
            {   redireccion::redireccionar('actualizoIdioma',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorIdioma($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>