<?php

namespace registro\loginjano;

use registro\loginjano\funcion\Redireccionador;

// Se incluye la clase para log de usuarios
include_once ("core/log/logger.class.php");
include_once ('Redireccionador.php');

// var_dump($_REQUEST);exit;
class FormProcessor {

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
        $variable ['usuario'] = $_REQUEST ["usuario"];
        $variable ['clave'] = $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST ["clave"]);
        // Verificar que el tiempo registrado en los controles no sea superior al tiempo actual + el tiempo de expiración


        if ($_REQUEST ['tiempo'] <= time() + $this->miConfigurador->getVariableConfiguracion('expiracion')) {

            // Verificar que el usuario esté registrado en el sistema
            $cadena_sql = $this->miSql->getCadenaSql("buscarUsuario", $variable);
            $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            if ($registro) {

                if ($registro [0] ['clave'] == $variable ["clave"]) {

                    // 1. Crear una sesión de trabajo
                    $estaSesion = $this->miSesion->crearSesion($registro [0] ["id_usuario"]);

                    $arregloLogin = array(
                        'autenticacionExitosa',
                        $registro [0] ["id_usuario"],
                        $_SERVER ['REMOTE_ADDR'],
                        $_SERVER ['HTTP_USER_AGENT']
                    );


                    $argumento = json_encode($arregloLogin);
                    $arreglo = array(
                        $registro [0] ["id_usuario"],
                        $argumento
                    );

                    // var_dump ( $arreglo );
                    //$cadena_sql = $this->miSql->getCadenaSql("registrarEvento", $arreglo);
                    //$registroAcceso = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

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
                        if ($registro [0] ['estado'] == 2) {
                            Redireccionador::redireccionar('claves', $registro);
                        } else {
                            Redireccionador::redireccionar('index', $registro [0]);
                        }
                    }
                    // Redirigir a la página principal del usuario, en el arreglo $registro se encuentran los datos de la sesion:
                    // $this->funcion->redireccionar("indexUsuario", $registro[0]);
                    return true;
                } else {

                    // Registrar el error por clave no válida
                    $arregloLogin = array(
                        'claveNoValida',
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

}

$miProcesador = new FormProcessor($this->lenguaje, $this->sql);

$resultado = $miProcesador->procesarFormulario();



