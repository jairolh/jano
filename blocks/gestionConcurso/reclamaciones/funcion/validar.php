<?php
namespace gestionConcurso\evaluacionConcurso\funcion;
use gestionConcurso\reclamaciones\funcion\redireccion;
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

        $arregloDatos = array('consecutivo_inscrito'=>$_REQUEST['consecutivo_inscrito'],
                              'validacion'=>$_REQUEST['validacion'],
                              'observaciones'=>$_REQUEST['observaciones'],
                              'fecha'=> $fecha,
                              'consecutivo_concurso'=> $_REQUEST['consecutivo_concurso'],
                              'consecutivo_perfil'=> $_REQUEST['consecutivo_perfil'],
                              'reclamacion'=> $_REQUEST['reclamacion'],
                              'version_validacion'=>'2',

            );

            $cadenaSql = $this->miSql->getCadenaSql ( 'registroValidacion',$arregloDatos );
            $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroValidacion" );

            if($resultado){
                redireccion::redireccionar('validoRequisito',$arregloDatos);  exit();
            }
            else{
                redireccion::redireccionar('noValidoRequisito',$arregloDatos);  exit();
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
