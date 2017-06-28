<?php

namespace gestionConcursante\concursosActivos\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class redireccion {
	public static function redireccionar($opcion, $valor = "") {
		$miConfigurador = \Configurador::singleton ();
		$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );
       	//$miPaginaActual="caracterizaConcurso";


		switch ($opcion) {

      case "insertoInscripcion":
      	$variable="pagina=".$miPaginaActual;
      	$variable.="&opcion=mensaje";
      	$variable.="&mensaje=confirmaInscripcion";
				$variable.="&perfil=".$valor['perfil'];
				$variable.="&nombre_perfil=".$valor['nombre_perfil'];
      	break;

    	case "noInsertoInscripcion":
    		$variable="pagina=".$miPaginaActual;
    		$variable.="&opcion=mensaje";
    		$variable.="&mensaje=errorInscripcion";
				$variable.="&perfil=".$valor['perfil'];
				$variable.="&nombre_perfil=".$valor['nombre_perfil'];
    		break;

      case "paginaPrincipal" :
				$variable = "pagina=" . $miPaginaActual;
				break;
		}

		foreach ( $_REQUEST as $clave => $valor ) {
			unset ( $_REQUEST [$clave] );
		}

		$url = $miConfigurador->configuracion ["host"] . $miConfigurador->configuracion ["site"] . "/index.php?";
		$enlace = $miConfigurador->configuracion ['enlace'];
		$variable = $miConfigurador->fabricaConexiones->crypto->codificar ( $variable );
		$_REQUEST [$enlace] = $enlace . '=' . $variable;
		$redireccion = $url . $_REQUEST [$enlace];

		echo "<script>location.replace('" . $redireccion . "')</script>";
	}
}

?>
