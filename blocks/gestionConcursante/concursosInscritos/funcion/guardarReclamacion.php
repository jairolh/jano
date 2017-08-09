<?php
namespace gestionConcursante\concursosInscritos\funcion;
use gestionConcursante\concursosInscritos\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorValidacion {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;
    var $miLogger;

    function __construct($lenguaje, $sql, $funcion, $miLogger) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
        $this->miLogger= $miLogger;
    }

    function procesarFormulario() {
        $conexion="estructura";
	      $esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $fecha = date("Y-m-d H:i:s");

        $arregloDatos = array(
                              'observaciones'=>$_REQUEST['observaciones'],
                              'fecha'=> $fecha,
                              'consecutivo_calendario'=>$_REQUEST['consecutivo_calendario']
        );

        $cadenaSql = $this->miSql->getCadenaSql ( 'registroReclamacion',$arregloDatos );
        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroReclamacion" );

        if($resultado){
            redireccion::redireccionar('registroReclamacion',$arregloDatos);  exit();
        }
        else{
            redireccion::redireccionar('noRegistroReclamacion',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorValidacion($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);
$resultado = $miRegistrador->procesarFormulario();
?>
