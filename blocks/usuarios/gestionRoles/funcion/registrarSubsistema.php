<?php

namespace usuarios\gestionRoles\funcion;

use usuarios\gestionRoles\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorSubsistema {

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
        
                $this->cadena_sql = $this->miSql->getCadenaSql("idSubsistema",'');
                $idSub = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
                $arregloDatos = array('id_subsistema'=>($idSub[0]['id_subsistema']+1),
                                      'nombre'  =>$_REQUEST['nombreSub'],
                                      'etiqueta'  =>$_REQUEST['etiqueta'],
                                      'descripcion'  =>$_REQUEST['descripcionSub'],
                                      'pagina'  =>$_REQUEST['paginaSub']);
               $this->cadena_sql = $this->miSql->getCadenaSql("insertarSubsistema", $arregloDatos);
               $resultadoSub = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");
               
        if($resultadoSub)
            {   
                $log=array('accion'=>"REGISTRO",
                        'id_registro'=>$arregloDatos['id_subsistema'],
                        'tipo_registro'=>"GESTION ROLES",
                        'nombre_registro'=>"id_subsistema=>".$arregloDatos['id_subsistema'].
                                           "|nombre=>".$arregloDatos['nombre'].
                                           "|etiqueta=>".$arregloDatos['etiqueta'].
                                           "|descripcion=>".$arregloDatos['descripcion'].
                                           "|id_pagina=>".$arregloDatos['pagina'],
                        'descripcion'=>"Registro de nuevo Subsistema al Subsistema ".$arregloDatos['nombre']
                          );
            
                        $this->miLogger->log_usuario($log);
                        redireccion::redireccionar('insertoSub',$arregloDatos);  exit();
                    
            }
        else
            {
               redireccion::redireccionar('noInsertoSub',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorSubsistema($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>