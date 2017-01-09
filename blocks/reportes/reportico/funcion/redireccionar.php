<?php

namespace reportes\reportico\funcion;

if (!isset($GLOBALS ["autorizado"])) {
    include ("index.php");
    exit();
}

class redireccion {

    public static function redireccionar($opcion, $valor = "") {
        $miConfigurador = \Configurador::singleton();
        $miPaginaActual = $miConfigurador->getVariableConfiguracion("pagina");
        switch ($opcion) {

            case "logger" :
                $variable = "pagina=" . $miPaginaActual;
                $variable .= $valor;
                break;

            case "noElimino" :
                $variable = "pagina=" . $miPaginaActual;
                $variable .= "&opcion=noelimino";
                $variable .= "&mensaje=errorEliminar";
                break;

            case "noItems" :
                $variable = "pagina=" . $miPaginaActual;
                $variable .= "&opcion=mensaje";
                $variable .= "&mensaje=otros";
                $variable .= "&errores=noItems";

                break;


            case "regresar" :
                $variable = "pagina=" . $miPaginaActual;
                break;

            case "paginaPrincipal" :
                $variable = "pagina=" . $miPaginaActual;
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