<?php

namespace registro\loginjano\funcion;

if (!isset($GLOBALS ["autorizado"])) {
    include ("index.php");
    exit();
}

class Redireccionador {

    public static function redireccionar($opcion, $valor = "") {
        $miConfigurador = \Configurador::singleton();

        $miPaginaActual = $miConfigurador->getVariableConfiguracion("pagina");

        switch ($opcion) {
            case "index" :
                //echo "Bienvenido, perfil jano, todo poderoso.";
                $variable = 'pagina=indexjano';
                $variable .= '&registro=' . $valor [0];
                break;

            case "claves" :
                // echo "Bienvenido, perfil Compras";
                $variable = 'pagina=cambiarClave';
                $variable .= '&usuario=' . $valor [0]['id_usuario'];
                break;
            
            case "claveCambiada" :
                $variable = 'pagina=' . $miPaginaActual;
                $variable .= "&opcion=mensaje";
                $variable .= "&mensaje=$opcion";
                $variable .= "&nombre=" . $valor['nombre'];
                break;
            
            case "claveNoCambiada" :
                $variable = 'pagina=' . $miPaginaActual;
                $variable .= "&opcion=mensaje";
                $variable .= "&mensaje=$opcion";
                $variable .= "&nombre=" . $valor['nombre'];
                break;
            
            case "correoEnviado" :
                $variable = 'pagina=' . $miPaginaActual;
                $variable .= "&opcion=mensaje";
                $variable .= "&mensaje=$opcion";
                $variable .= "&correo=" . $valor['correo'];
                break;
            
            case "correoNoEnviado" :
                $variable = 'pagina=' . $miPaginaActual;
                $variable .= "&opcion=mensaje";
                $variable .= "&mensaje=$opcion";
                $variable .= "&correo=" . $valor['correo'];
                break;
            
            case "usuarioInexistente" :
                $variable = 'pagina=' . $miPaginaActual;
                $variable .= "&opcion=mensaje";
                $variable .= "&mensaje=$opcion";
                break;
            
            case "usuarioInactivo" :
                $variable = 'pagina=' . $miPaginaActual;
                $variable .= "&opcion=mensaje";
                $variable .= "&mensaje=$opcion";
                $variable .= "&usuario=" . $valor['usuario'];
                break;
            
            case "linkCaducado" :
                $variable = 'pagina=' . $miPaginaActual;
                $variable .= "&opcion=mensaje";
                $variable .= "&mensaje=$opcion";
                $variable .= "&fecha=".$valor['fecha'];
                break;
            
            case "claveNoCoincide" :
                $variable = 'pagina=' . $miPaginaActual;
                $variable .= "&opcion=mensaje";
                $variable .= "&mensaje=$opcion";
                break;
            
//            case "recuperarClave":
//                $variable = 'pagina='.$miPaginaActual;
//                $variable .= '&opcion=' . $valor;
//                break;


              /** Otros casos */
            case "paginaPrincipal" :
                $variable = "pagina=" . $miPaginaActual;
                if (isset($valor) && $valor != '') {
                    $variable .= "&error=" . $valor;
                }
                break;

            default :
                $variable = 'pagina=' . $miPaginaActual;
                break;
        }
        foreach ($_REQUEST as $clave => $valor) {
            unset($_REQUEST [$clave]);
        }

        $url = $miConfigurador->configuracion ["host"] . $miConfigurador->configuracion ["site"] . "/index.php?";
        $enlace = $miConfigurador->configuracion ['enlace'];
        $variable = $miConfigurador->fabricaConexiones->crypto->codificar($variable);
        $_REQUEST [$enlace] = $enlace . '=' . $variable;
        $redireccion = $url . $_REQUEST [$enlace];

        echo "<script>location.replace('" . $redireccion . "')</script>";
    }

}

?>
