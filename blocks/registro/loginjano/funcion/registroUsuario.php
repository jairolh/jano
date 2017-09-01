<?php

namespace registro\loginjano;

use registro\loginjano\funcion\Redireccionador;
include_once ("core/log/logger.class.php");
include_once ('Redireccionador.php');


class FormProcessor {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;
    var $conexion;
    var $miLogger;

    function __construct($lenguaje, $sql) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miSesion = \Sesion::singleton();
        //Objeto de la clase Loger
        $this->miLogger = \logger::singleton();
    }

    function procesarFormulario() {

        /**
         *
         * @todo lógica de procesamiento
         */
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        session_start();

        $var=['identificacion' ,'tipo_identificacion','nombres','apellidos','correo','telefono','clave','verificacionNuevaClave'];
        foreach ($var as $key => $value) {
            if(isset($_REQUEST[$value]) && $_REQUEST[$value]=="" )
                { Redireccionador::redireccionar('campoNovalido',$_REQUEST);
                  break;  
                }
        }
        
        if(!$this->comprobar_email($_REQUEST['correo']))
            { Redireccionador::redireccionar('correoNovalido',$_REQUEST);}
        if(!$this->comprobar_clave($_REQUEST['clave'],$_REQUEST['verificacionNuevaClave']))
            { Redireccionador::redireccionar('claveNovalido',$_REQUEST);}
        
        $this->registroUsuario();    
        
        session_destroy();
    }

    function comprobar_email($email){
    $mail_correcto = 0;
    //compruebo unas cosas primeras
        if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
           if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
              //miro si tiene caracter .
              if (substr_count($email,".")>= 1){
                 //obtengo la terminacion del dominio
                 $term_dom = substr(strrchr ($email, '.'),1);
                 //compruebo que la terminación del dominio sea correcta
                 if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
                    //compruebo que lo de antes del dominio sea correcto
                    $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
                    $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
                    if ($caracter_ult != "@" && $caracter_ult != "."){
                       $mail_correcto = 1;
                    }
                 }
              }
           }
        }
        if ($mail_correcto)
           return 1;
        else
           return 0;
    }
    
    function comprobar_clave($clave1,$clave2){
    $clave_correcto = 0;
    //compruebo unas cosas primeras
         if($clave1===$clave2 &&  strlen($clave1)<=16 && preg_match("/(?=.*[0-9])(?=.*[A-Z])(?=\S+$).{8,}/", $clave1))
           return 1;
        else
           return 0;
    }    
    
    
    function registroUsuario() {

        $conexion="estructura";
	$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $password = $this->miConfigurador->fabricaConexiones->crypto->codificarClave ($_REQUEST['clave']);
        $hoy = date("Y-m-d");   
        $fechafin = strtotime ( '+10 year' , strtotime ( $hoy ) ) ;
        $fechafin = date ( 'Y-m-j' ,  $fechafin );
        $this->cadena_sql = $this->miSql->getCadenaSql("consultaPerfilesSistema", 'Concursante');
	$resultadoRol = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
        $arregloDatos = array(
                              'id_usuario'=> strtolower($_REQUEST['tipo_identificacion']).$_REQUEST['identificacion'],
                              'nombres'=>$_REQUEST['nombres'],
                              'apellidos'=>$_REQUEST['apellidos'],
                              'correo'=>$_REQUEST['correo'],
                              'telefono'=>$_REQUEST['telefono'],
                              'subsistema'=>$resultadoRol[0]['id_subsistema'],
                              'perfil'=>$resultadoRol[0]['rol_id'],
                              'perfilAlias'=>$resultadoRol[0]['rol_alias'],
                              'password'=>$password,
                              'fechaIni'  =>$hoy,
                              'fechaFin'  => $fechafin,  
                              'identificacion'=>$_REQUEST['identificacion'],
                              'tipo_identificacion'=>$_REQUEST['tipo_identificacion'],  );
        $this->cadena_sql = $this->miSql->getCadenaSql("buscarUsuario", $arregloDatos);
	$resultadoUsuario = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
        
        if(!$resultadoUsuario)
	{
            $this->cadena_sql = $this->miSql->getCadenaSql("insertarUsuario", $arregloDatos);
            $resultadoEstado = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $arregloDatos, "AutoRegistroUsuario" );
            if($resultadoEstado)
            {	$this->cadena_sql = $this->miSql->getCadenaSql("insertarPerfilUsuario", $arregloDatos);
                $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $arregloDatos, "AutoregistroPerfilUsuario" );
            
                $this->cadena_sql = $this->miSql->getCadenaSql("insertarConcursante", $arregloDatos);
                $resultadoConcursante = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $arregloDatos, "AutoregistroConcursante" );

                    Redireccionador::redireccionar('insertoUsuario',$arregloDatos);  exit();
            }else
            {
                    Redireccionador::redireccionar('noInserto',$arregloDatos);  exit();
            }

        }else
            {       
                    Redireccionador::redireccionar('existe',$_REQUEST);  exit();
            }
  
    }

    
}

$miProcesador = new FormProcessor($this->lenguaje, $this->sql);

$resultado = $miProcesador->procesarFormulario();



