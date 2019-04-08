<?php

namespace gestionConcurso\gestionJurado\funcion;

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

			/*Mensajes actualización estado del Tipo de Jurado*/
			case "inhabilitarTipoJurado":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=inhabilitoTipoJurado";
				$variable.="&tipoJurado=".$valor['nombre_tipoJurado'];
				break;

			case "habilitarTipoJurado":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=habilitoTipoJurado";
				$variable.="&tipoJurado=".$valor['nombre_tipoJurado'];
				break;

			case "nohabilitarTipoJurado":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=nohabilitoTipoJurado";
				$variable.="&tipoJurado=".$valor['nombre_tipoJurado'];
				break;

			case "noinhabilitarTipoJurado":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=noinhabilitoTipoJurado";
				$variable.="&tipoJurado=".$valor['nombre_tipoJurado'];
				break;

			/*Mensajes actualización estado de Criterio del Tipo de Jurado*/
			case "inhabilitarCriterio":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=inhabilitoCriterioTipoJurado";
				$variable.="&nombre_criterio=".$valor['nombre_criterio'];
				break;

			case "habilitarCriterio":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=habilitarCriterioTipoJurado";
				$variable.="&nombre_criterio=".$valor['nombre_criterio'];
				break;

			case "nohabilitarCriterio":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=nohabilitoCriterioTipoJurado";
				$variable.="&nombre_criterio=".$valor['nombre_criterio'];
				break;

			case "noinhabilitarCriterio":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=noinhabilitoCriterioTipoJurado";
				$variable.="&nombre_criterio=".$valor['nombre_criterio'];
				break;

			/*Mensajes actualización estado de Criterio del Tipo de Jurado*/
			case "inhabilitarJurado":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=inhabilitoJurado";
				$variable.="&nombre_tipo_jurado=".$valor['nombre_tipo_jurado'];
				$variable.="&id_usuario=".$valor['id_usuario'];
				break;

			case "habilitarJurado":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=habilitarJurado";
				$variable.="&nombre_tipo_jurado=".$valor['nombre_tipo_jurado'];
				$variable.="&id_usuario=".$valor['id_usuario'];
				break;

			case "nohabilitarJurado":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=nohabilitoJurado";
				$variable.="&nombre_tipo_jurado=".$valor['nombre_tipo_jurado'];
				$variable.="&id_usuario=".$valor['id_usuario'];
				break;

			case "noinhabilitarJurado":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=noinhabilitoJurado";
				$variable.="&nombre_tipo_jurado=".$valor['nombre_tipo_jurado'];
				$variable.="&id_usuario=".$valor['id_usuario'];
				break;

			case "insertoTipoJurado":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=confirmaTipoJurado";
				$variable.="&nombreTipoJurado=".$valor['nombre'];
				break;

			case "noInsertoTipoJurado":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=errorTipoJurado";
				$variable.="&nombreTipoJurado=".$valor['nombre'];
                        break;

                        case "registroCriterioTipoJurado":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=confirmaCriterioTipoJurado";
                                $variable.="&tipo_jurado=".$valor['tipo_jurado'];
                                break;

                        case "noRegistroCriterioTipoJurado":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=errorCriterioTipoJurado";
                                $variable.="&tipo_jurado=".$valor['tipo_jurado'];
                                break;

                        case "insertoUsuarioTipoJurado":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=confirmaUsuarioTipoJurado";
                                $variable.="&usuario=".$valor['usuario_jurado'];
                                break;

                        case "noInsertoUsuarioTipoJurado":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=errorUsuarioTipoJurado";
                                $variable.="&usuario=".$valor['usuario_jurado'];
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
