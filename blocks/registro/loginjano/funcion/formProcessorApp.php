<?php

namespace registro\loginjano;

use registro\loginjano\funcion\Redireccionador;

// Se incluye la clase para log de usuarios
include_once ("core/log/logger.class.php");
include_once ('Redireccionador.php');

// var_dump($_REQUEST);exit;
class FormProcessorApp {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;
    var $conexion;
    var $miSesion;
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
        $conexion = "estructura";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $arregloLogin = array();

        if (!$esteRecursoDB) {
            // Este se considera un error fatal
            exit();
        }

        /**
         *
         * @todo En entornos de producción la clave debe codificarse utilizando un objeto de la clase Codificador
         */
       
        //echo $variable ['clave'] = $this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST ["token"]);
        // Verificar que el tiempo registrado en los controles no sea superior al tiempo actual + el tiempo de expiración

        if ($_REQUEST ['tiempo'] <= time() + $this->miConfigurador->getVariableConfiguracion('expiracion')) {

            // Verificar que el usuario esté registrado en el sistema
            if (isset ( $_REQUEST ['accesoApp'] ) && $_REQUEST ['accesoApp'] != '' && isset ( $_REQUEST ['accesoTipo'] ) && $_REQUEST ['accesoTipo'] != 'anonimo') {

                if (isset($_REQUEST['token']) && trim($this->miConfigurador->getVariableConfiguracion ( "tokenCurriculum" ))==$this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['token'])) {
                    //verifica si usuario existe, si no lo registra
                    $this->registroUsuario();  
                    $variable ['usuario'] = $_REQUEST ["usuario"];
                    //verifica sesiones antiguas y las borra
                    $parametro=array('usuario'=>$variable ['usuario'],'dato'=>'idUsuario');
                    $sesionActiva = $this->miSesion->sesionActiva($parametro);

                    if($sesionActiva)
                        {
                          foreach($sesionActiva as $key => $value)
                              {$this->miSesion->borrarSesionActiva($sesionActiva[$key]['sesionid']);}
                        }

                    // 1. Crear una sesión de trabajo
                    $estaSesion = $this->miSesion->crearSesion($variable ['usuario']);
                     $arregloLogin = array(
                        'autenticacionExitosaApp'.$_REQUEST ["accesoApp"],
                        $variable ['usuario'] ,
                        $_SERVER ['REMOTE_ADDR'],
                        $_SERVER ['HTTP_USER_AGENT']
                    );


                    $argumento = json_encode($arregloLogin);
                    $arreglo = array(
                        $variable ['usuario'] ,
                        $argumento
                    );

                    if ($estaSesion) {
                        $log = array('accion' => "INGRESO",
                            'id_registro' => $variable ['usuario'] . "|" . $estaSesion,
                            'tipo_registro' => "LOGIN",
                            'nombre_registro' => $arreglo[1],
                            'descripcion' => "Ingreso al sistemas del usuario " . $variable ['usuario'] . " con la sesion " . $estaSesion,
                        );
                        //            var_dump($log);
                        $_COOKIE["aplicativo"] = $estaSesion;
                        $this->miLogger->log_usuario($log);
                        //Si estado dif Activo redirecciona a pagina decambio contraseña
                        Redireccionador::redireccionar('loginApp',$_REQUEST);
                        
                    }
                    // Redirigir a la página principal del usuario, en el arreglo $registro se encuentran los datos de la sesion:
                    // $this->funcion->redireccionar("indexUsuario", $registro[0]);
                    return true;
                } else {

                    // Registrar el error por clave no válida
                    $arregloLogin = array(
                        'accesoNoValido',
                        $variable ['usuario'],
                        $_SERVER ['REMOTE_ADDR'],
                        $_SERVER ['HTTP_USER_AGENT']
                    );
                }
            } else {

                // Registrar el error por usuario no valido
                $arregloLogin = array(
                    'usuarioNoValido',
                    $variable ['usuario'],
                    $_SERVER ['REMOTE_ADDR'],
                    $_SERVER ['HTTP_USER_AGENT']
                );
            }
        } else {

            // Registrar evento por tiempo de expiración en controles
            $arregloLogin = array(
                'formularioExpirado',
                $variable ['usuario'],
                $_SERVER ['REMOTE_ADDR'],
                $_SERVER ['HTTP_USER_AGENT']
            );
        }

        $argumento = json_encode($arregloLogin);
        $arreglo = array(
            $registro [0] ["id_usuario"],
            $argumento
        );

        Redireccionador::redireccionar('paginaPrincipal', $arregloLogin [0]);

        $cadena_sql = $this->miSql->getCadenaSql("registrarEvento", $arreglo);
        $registroAcceso = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    }


    function registroUsuario() {
        $conexion="estructura";
	$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $password = $this->miConfigurador->fabricaConexiones->crypto->codificarClave ($_REQUEST['nombre']);
        $hoy = date("Y-m-d");   
        $fechafin = strtotime ( '+10 year' , strtotime ( $hoy ) ) ;
        $fechafin = date ( 'Y-m-j' ,  $fechafin );
        $this->cadena_sql = $this->miSql->getCadenaSql("consultaPerfilesSistema", 'Concursante');
	$resultadoRol = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
        $arregloDatos = array(
                              'id_usuario'=> strtolower($_REQUEST['tipoidentificacion']).$_REQUEST['identificacion'],
                              'nombres'=>$_REQUEST['nombre'],
                              'apellidos'=>$_REQUEST['apellido'],
                              'correo'=>$_REQUEST['correo'],
                              'telefono'=>'',
                              'subsistema'=>$resultadoRol[0]['id_subsistema'],
                              'perfil'=>$resultadoRol[0]['rol_id'],
                              'perfilAlias'=>$resultadoRol[0]['rol_alias'],
                              'password'=>$password,
                              'fechaIni'  =>$hoy,
                              'fechaFin'  => $fechafin,  
                              'identificacion'=>$_REQUEST['identificacion'],
                              'tipo_identificacion'=>$_REQUEST['tipoidentificacion'],  );
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
            }
            $_REQUEST['usuario']=$arregloDatos['id_usuario'];
        }
        else{$_REQUEST['usuario']=$resultadoUsuario[0]['id_usuario'];}
 
    }    

    
    
}

$miProcesador = new FormProcessorApp($this->lenguaje, $this->sql);

$resultado = $miProcesador->procesarFormulario();



