<?php

namespace gestionConcursante\concursosInscritos\funcion;

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

	Case "registroReclamacion":
                $variable="pagina=".$miPaginaActual;
                $variable.="&opcion=mensaje";
                $variable.="&mensaje=confirmaReclamacion";
                $variable.="&consecutivo_inscrito=".$_REQUEST['consecutivo_inscrito'];
                $variable.="&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                $variable.= "&consecutivo_perfil=".$_REQUEST['consecutivo_perfil'];
                break;

    	case "noRegistroReclamacion":
    		$variable="pagina=".$miPaginaActual;
    		$variable.="&opcion=mensaje";
    		$variable.="&mensaje=errorReclamacion";
                $variable.="&consecutivo_inscrito=".$_REQUEST['consecutivo_inscrito'];
                $variable.="&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                $variable.= "&consecutivo_perfil=".$_REQUEST['consecutivo_perfil'];
    		break;

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
