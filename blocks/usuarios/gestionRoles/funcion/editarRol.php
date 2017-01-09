<?php

namespace usuarios\gestionRoles\funcion;

use usuarios\gestionRoles\funcion\redireccion;

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
        
	$arregloDatos = array('id_subsistema'=>$_REQUEST['id_subsistema'],
                              'nombreSub'=>$_REQUEST['nombreSub'],  
                              'etiqueta'=>$_REQUEST['etiqueta'],  
                              'pagina'=>$_REQUEST['paginaSub'],  
                              'descripcionSub'=>$_REQUEST['descripcionSub'],  
                              'rol_id'=>$_REQUEST['rol_id'],
                              'rol_nombre'  =>$_REQUEST['nombre'],
                              'rol_alias'  =>$_REQUEST['alias'],
                              'rol_descripcion'  =>$_REQUEST['descripcion'], );
        
        $this->cadena_sql = $this->miSql->getCadenaSql("EditarSubsistema", $arregloDatos);
        $resultadoSub = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");
        
        $this->cadena_sql = $this->miSql->getCadenaSql("EditarRol", $arregloDatos);
        $resultadoRol = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");
        
        if($resultadoSub && $resultadoRol)
            {    
                $log=array('accion'=>"ACTUALIZAR",
                            'id_registro'=>$_REQUEST['id_subsistema']."|".$_REQUEST['rol_id'],
                            'tipo_registro'=>"GESTION ROLES",
                            'nombre_registro'=>"id_subsistema=>".$_REQUEST['id_subsistema'].
                                               "|nombreSub=>".$_REQUEST['nombreSub'].
                                               "|etiqueta=>".$_REQUEST['etiqueta'].
                                               "|pagina=>".$_REQUEST['paginaSub'].
                                               "|descripcionSub=>".$_REQUEST['descripcionSub'].
                                               "|rol_id=>".$_REQUEST['rol_id'].
                                               "|rol_nombre=>".$_REQUEST['nombre'].
                                               "|rol_alias=>".$_REQUEST['alias'].
                                               "|rol_descripcion=>".$_REQUEST['descripcion'],
                            'descripcion'=>"Actualizar datos del Rol ".$arregloDatos['rol_alias']." y Subsistema ".$arregloDatos['etiqueta'],
                           ); 
                $this->miLogger->log_usuario($log);
                redireccion::redireccionar('editoRol',$arregloDatos);  exit();
            }
        else
            {
               redireccion::redireccionar('noEditoRol',$arregloDatos);  exit();
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