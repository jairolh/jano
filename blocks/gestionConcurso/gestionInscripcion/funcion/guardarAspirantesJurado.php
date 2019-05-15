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
        $porciones=array();
        foreach ( $_REQUEST as $key => $values )
            { 
             if(substr($key,0,9) == 'seleccion' && $key!='seleccionJurado')
                    {array_push($porciones, $values); }
            }
        //arreglo de js
        $items=$_REQUEST['aspirantes'];

        //obtener arreglo dividiendo por comas
        //$porciones = explode(",", $items);

        $fecha = date("Y-m-d H:i:s");
        $rol=explode('-',$_REQUEST['seleccionJurado']);  
        
        foreach ( $porciones as $key => $values ) {
          $arregloDatos = array('usuario'=>$rol[1],
                            'rol'=>$rol[0],
                            'inscrito'=>$values,
                            'jurado_tipo'=>$_REQUEST['tipoJurado'],
                            'fecha'=>$fecha,
                            'nombre_concurso'=>$_REQUEST['nombre_concurso'],
                            'consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                            'tab'=>$_REQUEST['tab']
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
