<?php
namespace gestionConcursante\gestionHoja\funcion;
use gestionConcursante\gestionHoja\funcion\redireccion;
include_once ('redireccionar.php');

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
                              'consecutivo'=>$_REQUEST['consecutivo'],
                              'tipo_identificacion'=>$_REQUEST['tipo_identificacion'],
                              'identificacion'=>$_REQUEST['identificacion'],
                              'nombre'=>$_REQUEST['nombres'],
                              'apellido'=>$_REQUEST['apellidos'],
                              'fecha_nacimiento'=>$_REQUEST['fecha_nacimiento'],
                              'pais_nacimiento'=>$_REQUEST['pais'],
                              'departamento_nacimiento'=>$_REQUEST['departamento'],
                              'lugar_nacimiento'=>$_REQUEST['ciudad'],
                              'sexo'=>$_REQUEST['sexo'],
                              'fecha_identificacion'=>$_REQUEST['fecha_identificacion'],
                              'lugar_identificacion'=>$_REQUEST['lugar_identificacion'],
                              'codigo_idioma_nativo'=>$_REQUEST['codigo_idioma_nativo'],
                              'autorizacion'=>$_REQUEST['autorizacion'],
            );
        
        //    var_dump($arregloDatos);exit;
        $cadenaSql = $this->miSql->getCadenaSql ( 'actualizarBasicos',$arregloDatos );
        $resultadoBasicos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarBasicos" );
        if($resultadoBasicos)
            {   
                $cadenaSqlUs = $this->miSql->getCadenaSql ( 'actualizarDatosUsuario',$arregloDatos );
                $resultadoUs = $esteRecursoDB->ejecutarAcceso ( $cadenaSqlUs, "actualiza", $arregloDatos, "actualizarDatosUsuario" );
                $datosSoporte=array('consecutivo_persona'=>$_REQUEST['consecutivo'],
                    'consecutivo_dato'=>$_REQUEST['consecutivo'],
                    'id_usuario'=>$_REQUEST['id_usuario']);
                $this->miArchivo->procesarArchivo($datosSoporte);
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

$miRegistrador = new RegistradorBasicos($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);

$resultado = $miRegistrador->procesarFormulario();
?>