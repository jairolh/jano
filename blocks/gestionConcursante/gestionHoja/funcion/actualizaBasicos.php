<?php
namespace gestionConcursante\gestionHoja\funcion;
use gestionConcursante\gestionHoja\funcion\redireccion;
include_once ('redireccionar.php');
include_once ('cargarArchivo.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorBasicos {

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
                              'consecutivo'=>$_REQUEST['consecutivo'],
                              'tipo_identificacion'=>$_REQUEST['tipo_identificacion'],
                              'identificacion'=>$_REQUEST['identificacion'],
                              'nombre'=>$_REQUEST['nombres'],
                              'apellido'=>$_REQUEST['apellidos'],
                              'fecha_nacimiento'=>$_REQUEST['fecha_nacimiento'],
                              'pais_nacimiento'=>$_REQUEST['pais'],
                              'departamento_nacimiento'=>$_REQUEST['departamento'],
                              'lugar_nacimiento'=>$_REQUEST['ciudad'],
                              'sexo'=>$_REQUEST['sexo']
            );
        $cadenaSql = $this->miSql->getCadenaSql ( 'actualizarBasicos',$arregloDatos );
        $resultadoBasicos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarBasicos" );
        
        if($resultadoBasicos)
            {   $_REQUEST['consecutivo_dato']=$_REQUEST['consecutivo'];
                $this->miArchivo->procesarArchivo('datosBasicos');
                redireccion::redireccionar('actualizoBasicos',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorBasicos($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>