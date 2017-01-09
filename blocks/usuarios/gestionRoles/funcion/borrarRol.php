<?php

namespace usuarios\gestionRoles\funcion;

use usuarios\gestionRoles\funcion\redireccion;

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

        $parametro=array('id_subsistema'=>$_REQUEST['id_subsistema'],
                         'rol_id'=>$_REQUEST['rol_id']);
       
        $cadena_sql = $this->miSql->getCadenaSql("consultarPerfilUsuario", $parametro);
        $resultadoLog = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //var_dump($resultadoLog);
        
        if($resultadoLog)
            {   $_REQUEST['nroUser']=count($resultadoLog);
                redireccion::redireccionar('existe',$_REQUEST);
                exit();
            } 
        else{
                $this->cadena_sql = $this->miSql->getCadenaSql("borrarRol", $parametro);
                $resultadoBorra = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");

                if($resultadoBorra)
                {	
                    $log=array('accion'=>"BORRAR",
                                'id_registro'=>$_REQUEST ["id_subsistema"]."|".$_REQUEST ["rol_id"],
                                'tipo_registro'=>"GESTION ROLES",
                                'nombre_registro'=>"id_subsistema=>".$_REQUEST ["id_subsistema"].
                                                   "|rol_id=>".$_REQUEST ["rol_id"],
                                'descripcion'=>"Borrar el Rol ".$_REQUEST ["rol_alias"]." del Subsistema ".$_REQUEST ["subsistema"],
                               ); 
                    $this->miLogger->log_usuario($log);
                    redireccion::redireccionar($_REQUEST['opcion'],$_REQUEST);
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