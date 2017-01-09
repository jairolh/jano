<?php

namespace usuarios\gestionUsuarios\funcion;

use usuarios\gestionUsuarios\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorUsuarios {

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
        
        $arregloDatos = array(
                              'id_usuario'=>$_REQUEST['id_usuario'],
                              'nombres'=>$_REQUEST['nombres'],
                              'apellidos'=>$_REQUEST['apellidos'],
                              'correo'=>$_REQUEST['correo'],
                              'telefono'=>$_REQUEST['telefono'],  );

        $this->cadena_sql = $this->miSql->getCadenaSql("actualizarUsuario", $arregloDatos);
        $resultadoUsuario = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");
        if($resultadoUsuario)
        {
            $log=array('accion'=>"ACTUALIZAR",
                        'id_registro'=>$_REQUEST['id_usuario'],
                        'tipo_registro'=>"GESTION USUARIO",
                        'nombre_registro'=>"id_usuario=>".$_REQUEST['id_usuario'].
                                           "|nombres=>".$_REQUEST['nombres'].
                                           "|apellidos=>".$_REQUEST['apellidos'].
                                           "|correo=>".$_REQUEST['correo'].
                                           "|telefono=>".$_REQUEST['telefono'],
                        'descripcion'=>"Actualización de datos basicos del Usuario ".$_REQUEST['id_usuario'],
                       ); 
            $this->miLogger->log_usuario($log);
            redireccion::redireccionar('actualizo',$arregloDatos);  exit();
        }else
        {
                redireccion::redireccionar('noactualizo',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorUsuarios($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>