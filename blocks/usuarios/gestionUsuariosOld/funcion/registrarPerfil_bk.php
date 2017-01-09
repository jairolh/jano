<?php

namespace usuarios\gestionUsuarios\funcion;

use usuarios\gestionUsuarios\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorPerfil {

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
        $hoy = date("Y-m-d");   
        
	$arregloDatos = array(
                              'id_usuario'=>$_REQUEST['id_usuario'],
                              'subsistema'=>$_REQUEST['subsistema'],
                              'perfil'=>$_REQUEST['perfil'],
                              'fechaIni'  =>$hoy,
                              'fechaFin'  =>$_REQUEST['fechaFin'], );

        $this->cadena_sql = $this->miSql->getCadenaSql("insertarPerfilUsuario", $arregloDatos);
        $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");

        if($resultadoPerfil)
            {    
                $parametro['id_usuario']=$arregloDatos['id_usuario'];
                $parametro['rol_id']=$arregloDatos['perfil'];
                $cadena_sql = $this->miSql->getCadenaSql("consultarPerfilUsuario", $parametro);
                $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                $log=array('accion'=>"REGISTRO",
                            'id_registro'=>$_REQUEST['id_usuario']."|".$_REQUEST['subsistema']."|".$_REQUEST['perfil'],
                            'tipo_registro'=>"GESTION USUARIO",
                            'nombre_registro'=>"id_usuario=>".$_REQUEST['id_usuario'].
                                               "|subsistema=>".$_REQUEST['subsistema'].
                                               "|perfil=>".$_REQUEST['perfil'].
                                               "|fechaIni=>".$hoy.
                                               "|fechaFin=>".$_REQUEST['fechaFin'],
                            'descripcion'=>"Registro de nuevo perfil ".$resultadoPerfil[0]['rol_alias']." al Usuario ".$_REQUEST['id_usuario'],
                           ); 
                $this->miLogger->log_usuario($log);
                $arregloDatos['perfilUs']=$resultadoPerfil[0]['rol_alias'];
                redireccion::redireccionar('insertoPerfil',$arregloDatos);  exit();
            }
        else
            {
               redireccion::redireccionar('noInsertoPerfil',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorPerfil($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>