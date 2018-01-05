<?php

namespace registro\loginjano;

use registro\loginjano\funcion\Redireccionador;
include_once ("core/log/logger.class.php");
include_once ('Redireccionador.php');



// var_dump($_REQUEST);exit;
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
        
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        
        /**
         *
         * @todo lógica de procesamiento
         */
        
        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        
        $usuario = trim($_REQUEST['usuarioRecuperacion']);
        
        $enviarCorreo = true;
        //--------------------COMPROBACION DE EXISTENCIA DEL USUARIO-----------------------------------
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ("buscarUsuario",array('usuario'=>$usuario));
        //echo "Cadena: ".$atributos ['cadena_sql']."<br>";
        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
        
        if($matrizItems != NULL){
            if(count($matrizItems)==0){
                $enviarCorreo = false;
                Redireccionador::redireccionar('usuarioInexistente');
            }
        }else{
            $enviarCorreo = false;
            Redireccionador::redireccionar('usuarioInexistente');
        }
        //--------------------------------------------------------------------------------------------
        
        
        //------------------COMPROBACION DE USUARIO ACTIVO------------------------------------------
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ("buscarUsuarioActivo",array('usuario'=>$usuario));
        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
        //echo "Cadena: ".$atributos ['cadena_sql']."<br>";
        if($matrizItems != NULL){
            if(count($matrizItems)==0){
                $enviarCorreo = false;
                Redireccionador::redireccionar('usuarioInactivo',array('usuario'=>$usuario));
            }
        }else{
            $enviarCorreo = false;
            Redireccionador::redireccionar('usuarioInactivo',array('usuario'=>$usuario));
        }
        //--------------------------------------------------------------------------------------------
        
        
        
        //------------------COMPROBACION DE PERFILES DE USUARIO ACTIVOS------------------------------------------
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ("buscarPerfilActivoxUsuario",array('usuario'=>$usuario));
        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
        $perfiles = $matrizItems[0]['perfiles'];
        if($perfiles == 0){
            $enviarCorreo = false;
            Redireccionador::redireccionar('usuarioInactivo',array('usuario'=>$usuario));
        }
        //--------------------------------------------------------------------------------------------
        
        if($enviarCorreo){
            
            //------------------------------CADENA DE RECUPERACION----------------------------------------
            $variable = "pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
            $variable .= "&opcion=" . "cambiarClave";
            $variable .= "&bloque=" . $esteBloque ['nombre'];
            $variable .= "&bloqueGrupo=" . $esteBloque ["grupo"];
            $variable .= "&fecha=".date('Y-m-d');
            $variable .= "&usuario=" . $usuario ;
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url( $variable );
            $variable = $this->miConfigurador->configuracion ["host"].
            $this->miConfigurador->configuracion ["site"]."/index.php?data".$variable;
            //--------------------------------------------------------------------------------------------


            //---------------------------ENVÍO DE EMAIL AL USUARIO-----------------------------------------
            $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ("buscarCorreoUsuario",array('usuario'=>$usuario));
    //        echo "Cadena: ".$atributos ['cadena_sql']."<br>";
            $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
            $correoUsuario = $matrizItems[0]['correo'];

            $mensaje = $this->lenguaje->getCadena('mensajeMail');
            $mensaje = str_replace($this->lenguaje->getCadena('banderaUsuario'), $usuario, $mensaje);
            $mensaje = str_replace($this->lenguaje->getCadena('banderaEnlace'), $variable, $mensaje);
            $mensaje = str_replace("#host", $this->miConfigurador->configuracion ["host"], $mensaje);
            $mensaje = str_replace("#nombreAplicativo", $this->miConfigurador->configuracion ["nombreAplicativo"], $mensaje);
            
            //------------------------------------------------------------------------------------------------------
            
            ob_end_clean();
            $ruta=$this->miConfigurador->getVariableConfiguracion('raizDocumento');

            require_once($ruta.'/plugin/PHPMailer/PHPMailerAutoload.php');
            $mail = new \PHPMailer();
            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
            //nuevas lineas para envio por gmail
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->SMTPSecure = "tls"; // sets the prefix to the servier
            $server=explode(':',$this->miConfigurador->configuracion ["hostCorreo"]);
            $mail->Host = $server[0];  // Specify main and backup SMTP servers
            $mail->Port = $server[1]; // set the SMTP port for the GMAIL server
            $mail->Username = $this->miConfigurador->configuracion["cuentaCorreo"];                 // SMTP username
            $mail->Password = $this->miConfigurador->fabricaConexiones->crypto->decodificar($this->miConfigurador->configuracion["claveCorreo"]);     
            $mail->SMTPAuth = true;
            $mail->Timeout = 1200;
            $mail->Charset = "utf-8";
            // TCP port to connect to
            $mail->setFrom($this->miConfigurador->configuracion["cuentaCorreo"], 'Udistrital');
            $mail->addAddress($correoUsuario, '');     // Add a recipient
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Recuperar Clave';
            $mail->Body    = $mensaje;

            if(!$mail->send()) {
                Redireccionador::redireccionar('correoNoEnviado', array('correo'=>$correoUsuario));
            }
            //-------------------------------------------------------------------------------------------------
            
            //--------------------------------------------------------------------------------------------
            //require_once($ruta.'/core/auth/Sesion.class.php');
            $arregloLogin = array('SolicitudRecuperacionClave',$_REQUEST ["usuarioRecuperacion"],$_SERVER ['REMOTE_ADDR'],$_SERVER ['HTTP_USER_AGENT']);
            $argumento = json_encode($arregloLogin);
            $arreglo = array($_REQUEST ["usuarioRecuperacion"],$argumento);
            
            $sesionActiva = "".  date('Y-m-d H:i:s');
            $log=array('accion'=>"SOLICITUD",
                        'id_registro'=>$_REQUEST ["usuarioRecuperacion"]."|".$sesionActiva,
                        'tipo_registro'=>"RECUPERACIONCLAVE",
                        'nombre_registro'=>$arreglo[1],
                        'descripcion'=>"Solicitud de recuperacion de clave por ".$_REQUEST ["usuarioRecuperacion"]." a las ".$sesionActiva,
                       ); 
            $log['id_usuario']=$_REQUEST ["usuarioRecuperacion"];
            $log['fecha_log']=date("F j, Y, g:i:s a");         
            $log['host']=$this->miLogger->obtenerIP(); 
            $cadenaSql = $this->miSql->getCadenaSql("registroLogUsuario", $log);
            $resultado = @$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
            //---------------------------------------------------------------------------------------------------
            Redireccionador::redireccionar('correoEnviado', array('correo'=>$correoUsuario));
            
        }
        
            
    }

}

$miProcesador = new FormProcessor($this->lenguaje, $this->sql);

$resultado = $miProcesador->procesarFormulario();



