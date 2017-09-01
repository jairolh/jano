<?php

namespace usuarios\gestionUsuarios\funcion;

use usuarios\gestionUsuarios\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class BorrarUsuario {

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
// 		var_dump ( $_REQUEST );
        $conexion="estructura";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $parametro['id_usuario']=$_REQUEST['id_usuario'];
        
        $cadena_sql = $this->miSql->getCadenaSql("consultarLogUsuario", $parametro);
        $resultadoLog = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        if($resultadoLog)
            {
                redireccion::redireccionar('existeLog',$_REQUEST);
                exit();
            } 
        else{
                $cadena_sql = $this->miSql->getCadenaSql("consultarUsuarios", $parametro);
                $resultadoUsuarios = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                $this->cadena_sql = $this->miSql->getCadenaSql("borrarPerfil", $parametro);
                $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($this->cadena_sql,"eliminar",  $parametro, "borrarPerfilUsuario" );
                
                if($resultadoPerfil)
                        {
                         $this->cadena_sql = $this->miSql->getCadenaSql("borrarUsuario", $parametro);
                         $resultadoBorra = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "eliminar",  $parametro, "borrarUsuario" );

                        if($resultadoBorra)
                        {
                            redireccion::redireccionar($_REQUEST['opcion'],$_REQUEST);

                        }else
                        {
                            redireccion::redireccionar('no'.$_REQUEST['opcion'],$_REQUEST);
                            exit();
                        }
                    }    
                else
                    {
                        redireccion::redireccionar('no'.$_REQUEST['opcion'],$_REQUEST);
                        exit();
                    }
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

$miRegistrador = new BorrarUsuario($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>