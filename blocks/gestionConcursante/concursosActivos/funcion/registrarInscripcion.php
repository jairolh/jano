<?php

namespace gestionConcursante\concursosActivos\funcion;

use gestionConcursante\concursosActivos\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorInscripcion {

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

      $miSesion = \Sesion::singleton();
      $usuario=$miSesion->idUsuario();

      $tipo=substr($usuario,0,2);
      $id=substr($usuario,2);

      $persona = array('tipo_identificacion'=> $tipo,
          'identificacion'=> $id
  		);

      //buscar el consecutivo de la persona
      $cadena_sql = $this->miSql->getCadenaSql("consultaConsecutivo", $persona);
      $resultadoPersona = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

      $fecha=$parametro['fecha_actual'] = date("d") . "/" . date("m") . "/" . date("Y");

      $datos = array('consecutivo_persona'=> $resultadoPersona[0][0],
          'perfil'=> $_REQUEST['perfil'],
          'nombre_perfil'=> $_REQUEST['nombre_perfil'],
  				'fecha'=> $fecha
  		);

      $cadena_sql = $this->miSql->getCadenaSql("registrarInscripcion", $datos);
  		$resultadoInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "registra", $datos, "registroInscripcion");

  		if($resultadoInscripcion){
  			redireccion::redireccionar('insertoInscripcion',$datos);  exit();
  		}else {
  			redireccion::redireccionar('noInsertoInscripcion',$datos);  exit();
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

$miRegistrador = new RegistradorInscripcion($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>
