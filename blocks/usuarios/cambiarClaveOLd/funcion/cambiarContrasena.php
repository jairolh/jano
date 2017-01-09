<?php

namespace usuarios\cambiarClave\funcion;

use usuarios\cambiarClave\funcion\redireccion;

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

        if ($_REQUEST ["contrasena"] == $_REQUEST ["contrasenaConfirm"]) {

            $_REQUEST ["contrasena"]= $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST ['contrasena'] );
            $_REQUEST ["contrasenaActual"]= $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST ['contrasenaActual'] );

            $conexion = "estructura";
            $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

            $_REQUEST['usuario']=isset($_REQUEST['usuario'])?$_REQUEST['usuario']:$_REQUEST['id_usuario'];
            $parametro['id_usuario']=$_REQUEST['usuario'];
            $cadena_sql = $this->miSql->getCadenaSql("consultarUsuarios", $parametro);
            $resultadoUs = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            if($resultadoUs)
                {   if ($resultadoUs && $_REQUEST ['contrasenaActual']==$resultadoUs[0]['clave'])
                      { $parametro['contrasena']=$_REQUEST ["contrasena"];
                        $cadena_sql = $this->miSql->getCadenaSql ( "modificaClave", $parametro );
                        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "acceso" );
                        if ($resultado == true) {
                            $log=array('accion'=>"ACTUALIZAR",
                                        'id_registro'=>$resultadoUs[0]["id_usuario"],
                                        'tipo_registro'=>"CAMBIO CLAVE",
                                        'nombre_registro'=>"usuario=>".$parametro['id_usuario'].
                                                           "|claveActual=>".$resultadoUs[0]["clave"].
                                                           "|claveNueva=>".$parametro['contrasena'],
                                        'descripcion'=>"Cambio de contraseña del Usuario ".$parametro['id_usuario'],
                                       ); 
                            $this->miLogger->log_usuario($log);
                            redireccion::redireccionar('actualizo',$_REQUEST);  exit();
                            $mensaje = " <p><b>...Su contraseña ha sido modificada exitosamente...</b></p>";
                            $error = "exito";
                            }
                        else { 
                              redireccion::redireccionar('error',$_REQUEST);  exit();
                             }
                        } 
                    else{
                        redireccion::redireccionar('noCorrecta',$_REQUEST);  exit();
                        }
                }
            else
                {
                 redireccion::redireccionar('error',$_REQUEST);  exit();
                }
	
            } 
        else {  redireccion::redireccionar('noCoincide',$_REQUEST);  exit();
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