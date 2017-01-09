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
        
        $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"; 
        $num = '1234567890';
        $caracter = '=_#$-';
        $numerodeletras=5; 
        $pass = "";
        $keycar=$keyNum= "";
        for($i=0;$i<$numerodeletras;$i++)
            {$pass .= substr($caracteres,rand(0,strlen($caracteres)),1); }

        $maxCar = strlen($caracter)-1;
        $maxNum = strlen($num)-1;

        for($j=0;$j < 1;$j++)
               { $keycar .= $caracter{mt_rand(0,$maxCar)};}       
        for($k=0;$k < 2;$k++)
               { $keyNum .= $num{mt_rand(0,$maxNum)};}       
        $pass=$pass.$keycar.$keyNum;       
        $password = $this->miConfigurador->fabricaConexiones->crypto->codificarClave ( $pass );
        $hoy = date("Y-m-d");   
	$arregloDatos = array(
                              'id_usuario'=>$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion'],
                              'nombres'=>$_REQUEST['nombres'],
                              'apellidos'=>$_REQUEST['apellidos'],
                              'correo'=>$_REQUEST['correo'],
                              'telefono'=>$_REQUEST['telefono'],
                              'subsistema'=>$_REQUEST['subsistema'],
                              'perfil'=>$_REQUEST['perfil'],
                              'password'=>$password,
                              'pass'=>$pass,
                              'fechaIni'  =>$hoy,
                              'fechaFin'  =>$_REQUEST['fechaFin'],  
                              'identificacion'=>$_REQUEST['identificacion'],
                              'tipo_identificacion'=>$_REQUEST['tipo_identificacion'],  );

        $this->cadena_sql = $this->miSql->getCadenaSql("consultarUsuarios", $arregloDatos);
	$resultadoUsuario = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
        if(!$resultadoUsuario)
	{
            $this->cadena_sql = $this->miSql->getCadenaSql("insertarUsuario", $arregloDatos);
            $resultadoEstado = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");
            if($resultadoEstado)
            {	$this->cadena_sql = $this->miSql->getCadenaSql("insertarPerfilUsuario", $arregloDatos);
                $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");
                
                $parametro['id_usuario']=$arregloDatos['id_usuario'];
                $cadena_sql = $this->miSql->getCadenaSql("consultarPerfilUsuario", $parametro);
                $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                
                $log=array('accion'=>"REGISTRO",
                            'id_registro'=>$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion'],
                            'tipo_registro'=>"GESTION USUARIO",
                            'nombre_registro'=>"id_usuario=>".$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion'].
                                               "|identificacion=>".$_REQUEST['identificacion'].
                                               "|tipo_identificacion=>".$_REQUEST['tipo_identificacion'].
                                               "|nombres=>".$_REQUEST['nombres'].
                                               "|apellidos=>".$_REQUEST['apellidos'].
                                               "|correo=>".$_REQUEST['correo'].
                                               "|telefono=>".$_REQUEST['telefono'].
                                               "|subsistema=>".$_REQUEST['subsistema'].
                                               "|perfil=>".$_REQUEST['perfil'].
                                               "|fechaIni=>".$hoy.
                                               "|fechaFin=>".$_REQUEST['fechaFin'],
                            'descripcion'=>"Registro de nuevo Usuario ".$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion']." con perfil ".$resultadoPerfil[0]['rol_alias'],
                           ); 
                $this->miLogger->log_usuario($log);
                $arregloDatos['perfilUs']=$resultadoPerfil[0]['rol_alias'];
                redireccion::redireccionar('inserto',$arregloDatos);  exit();
            }else
            {
                    redireccion::redireccionar('noInserto',$arregloDatos);  exit();
            }

        }else
            {       
                    redireccion::redireccionar('existe',$arregloDatos);  exit();
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