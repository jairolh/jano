<?php
namespace gestionConcursante\gestionHoja\funcion;
use gestionConcursante\gestionHoja\funcion\redireccion;
include_once ('redireccionar.php');


if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class borrarRegistro {

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
        if (isset($_REQUEST['botonCancelar2']) && $_REQUEST['botonCancelar2']=='true') 
            {redireccion::redireccionar ( 'devolver',$_REQUEST['tipo'] );
             exit ();
            }  
            
        $conexion="estructura";
	$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        
        $arregloDatos = array('id_usuario'=>$_REQUEST['usuario'],
                              'consecutivo'=>$_REQUEST['consecutivo'],
                              'persona'=>$_REQUEST['persona'],
                              'tipo'=>$_REQUEST['tipo'],
            );
        
        switch ($_REQUEST['accion'])
            {   
                case 'borrarFormacion':
                    $arregloDatos['dato']=' Formación Académica '; 
                    $cadenaSql = $this->miSql->getCadenaSql ( 'borrarFormacion',$arregloDatos );
                break;
                case 'borrarProfesional':
                    $arregloDatos['dato']=' Experiencia Profesional '; 
                    $cadenaSql = $this->miSql->getCadenaSql ( 'borrarProfesional',$arregloDatos );
                break;
                case 'borrarDocencia':
                    $arregloDatos['dato']=' Experiencia Docente '; 
                    $cadenaSql = $this->miSql->getCadenaSql ( 'borrarDocencia',$arregloDatos );
                break;
                case 'borrarActividad':
                    $arregloDatos['dato']=' Actividad Académica '; 
                    $cadenaSql = $this->miSql->getCadenaSql ( 'borrarActividad',$arregloDatos );
                break;
                case 'borrarInvestigacion':
                    $arregloDatos['dato']=' Experiencia en Investigación '; 
                    $cadenaSql = $this->miSql->getCadenaSql ( 'borrarInvestigacion',$arregloDatos );
                break;
                case 'borrarProduccion':
                    $arregloDatos['dato']=' Producción Académica '; 
                    $cadenaSql = $this->miSql->getCadenaSql ( 'borrarProduccion',$arregloDatos );
                break;
                case 'borrarIdiomas':
                    $arregloDatos['dato']=' Conocimiento en Idioma '; 
                    $cadenaSql = $this->miSql->getCadenaSql ( 'borrarIdiomas',$arregloDatos );
                break;
            
            }
        $resultadoBorrar = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "eliminar", $arregloDatos, $_REQUEST['accion'] );
        if($resultadoBorrar)
            {  
                redireccion::redireccionar('borro',$arregloDatos);  exit();
            }else
            {
                redireccion::redireccionar('noBorro',$arregloDatos);  exit();
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

$miRegistrador = new borrarRegistro($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);

$resultado = $miRegistrador->procesarFormulario();
?>