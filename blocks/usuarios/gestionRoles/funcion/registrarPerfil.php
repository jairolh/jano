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
        
        $hoy = date("Y-m-d");   
        
        if(isset($_REQUEST['perfil']) && $_REQUEST['perfil']>=0 )
            {   
                $arregloDatos = array('subsistema'=>$_REQUEST['subsistema'],
                                       'rol_id'=>$_REQUEST['perfil'] );
                
                $log=array('accion'=>"REGISTRO",
                        'id_registro'=>$_REQUEST['subsistema']."|".$_REQUEST['perfil'],
                        'tipo_registro'=>"GESTION ROLES",
                        'nombre_registro'=>"subsistema=>".$_REQUEST['subsistema'].
                                           "|Rol=>".$_REQUEST['perfil'],
                       ); 

            }
        else{
                $this->cadena_sql = $this->miSql->getCadenaSql("idPerfil",'');
                $idPerfil = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
                $arregloDatos = array('subsistema'=>$_REQUEST['subsistema'],
                                      'rol_id'=>($idPerfil[0]['rol_id']+1),
                                      'rol_nombre'  =>$_REQUEST['nombre'],
                                      'rol_alias'  =>$_REQUEST['alias'],
                                      'rol_descripcion'  =>$_REQUEST['descripcion'],
                                      'rol_estado'  =>1,
                                      'rol_fechaIni'  =>$hoy  );
                                  
               $this->cadena_sql = $this->miSql->getCadenaSql("insertarRol", $arregloDatos);
               $resultadoRol = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");
               $log=array('accion'=>"REGISTRO",
                        'id_registro'=>$arregloDatos['subsistema']."|".$arregloDatos['rol_id'],
                        'tipo_registro'=>"GESTION ROLES",
                        'nombre_registro'=>"subsistema=>".$arregloDatos['subsistema'].
                                           "|rol_id=>".$arregloDatos['rol_id'].
                                           "|rol_nombre=>".$arregloDatos['rol_nombre'].
                                           "|rol_alias=>".$arregloDatos['rol_alias'].
                                           "|rol_descripcion=>".$arregloDatos['rol_descripcion'].
                                           "|rol_estado=>".$arregloDatos['rol_estado'].
                                           "|rol_fechaIni=>".$arregloDatos['rol_fechaIni'],
                       ); 
            }
            
        if(isset($_REQUEST['perfil']) || $resultadoRol)
            {   $this->cadena_sql = $this->miSql->getCadenaSql("insertarRolSubsistema", $arregloDatos);
                $resultadoRolSub = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");

                if($resultadoRolSub)
                    {    
                        $cadena_sql = $this->miSql->getCadenaSql("consultaPerfilesSistema", $arregloDatos);
                        $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                        $log['descripcion']="Registro de nuevo Rol ".$resultadoPerfil[0]['rol_alias']." al Subsistema ".$resultadoPerfil[0]['etiketa'];

                        $this->miLogger->log_usuario($log);
                        $arregloDatos['perfilUs']=$resultadoPerfil[0]['rol_alias'];
                        $arregloDatos['perfilSub']=$resultadoPerfil[0]['etiketa'];
                        redireccion::redireccionar('insertoPerfil',$arregloDatos);  exit();
                    }
                else
                    {
                       redireccion::redireccionar('noInsertoPerfil',$arregloDatos);  exit();
                    }
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