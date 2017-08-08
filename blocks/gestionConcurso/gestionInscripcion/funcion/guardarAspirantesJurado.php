<?php
namespace gestionConcurso\gestionInscripcion\funcion;
use gestionConcurso\gestionInscripcion\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class AsignarAspirantes {

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

        //arreglo de js
        $items=$_REQUEST['aspirantes'];

        //obtener arreglo dividiendo por comas
        $porciones = explode(",", $items);

        $fecha = date("Y-m-d H:i:s");

        foreach ( $porciones as $key => $values ) {
          $arregloDatos = array('usuario'=>$_REQUEST['seleccionJurado'],
                            'inscrito'=>$values,
                            'jurado_tipo'=>$_REQUEST['tipoJurado'],
                            'fecha'=>$fecha,
                            'nombre_concurso'=>$_REQUEST['nombre_concurso'],
                            'consecutivo_concurso'=>$_REQUEST['consecutivo_concurso']
          );

          $cadenaSql = $this->miSql->getCadenaSql ('registroAspirantesJurado',$arregloDatos);
          $resultadoAsignacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroAspirantesJurado" );
        };

            if($resultadoAsignacion){
                redireccion::redireccionar('juradoAsignado',$arregloDatos);  exit();
            }else{
                redireccion::redireccionar('noAsignoJurado',$arregloDatos);  exit();
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

$miRegistrador = new AsignarAspirantes($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);
$resultado = $miRegistrador->procesarFormulario();
?>
