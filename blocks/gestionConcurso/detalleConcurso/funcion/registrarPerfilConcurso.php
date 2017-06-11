<?php
namespace gestionConcurso\detalleConcurso\funcion;
use gestionConcurso\detalleConcurso\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorPerfilConcurso {

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
        //var_dump($_REQUEST);
        $arregloDatos = array('consecutivo_perfil'=>$_REQUEST['consecutivo_perfil'],
                              'consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                              'nombre'=>$_REQUEST['nombrePerfil'],
                              'descripcion'=>$_REQUEST['descripcion'],
                              'requisitos'=>$_REQUEST['requisitos'],
                              'dependencia'=>$_REQUEST['dependencia'],
                              'area'=>$_REQUEST['area'],
                              'vacantes'=>$_REQUEST['vacantes'],
                              'estado'=>$_REQUEST['estado']
            );
        
        if($arregloDatos['consecutivo_perfil']==0)
             {  $cadenaSql = $this->miSql->getCadenaSql ( 'registroPerfilConcurso',$arregloDatos );
                $resultadoPerfil = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroPerfilConcurso" );
                $_REQUEST['consecutivo_perfil']=$resultadoPerfil;
             }
        else {  $cadenaSql = $this->miSql->getCadenaSql ( 'actualizaPerfilConcurso',$arregloDatos );
                $resultadoPerfil = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarPerfilConcurso" );
             }
        if($resultadoPerfil)
            {   //$_REQUEST['consecutivo']=0;$_REQUEST['consecutivo_persona'];
                //$_REQUEST['consecutivo_dato']=$_REQUEST['consecutivo_concurso'];
                //$this->miArchivo->procesarArchivo('datosConcurso');
                redireccion::redireccionar('actualizoPerfilConcurso',$arregloDatos);  exit();
            }else
            {   $arregloDatos['detalle']='perfil';
                redireccion::redireccionar('noActualizoDetalle',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorPerfilConcurso($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);
$resultado = $miRegistrador->procesarFormulario();
?>