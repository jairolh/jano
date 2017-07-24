<?php

namespace gestionConcurso\evaluacionConcurso\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class redireccion {
	public static function redireccionar($opcion, $valor = "") {
		$miConfigurador = \Configurador::singleton ();
		$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );
                //$miPaginaActual="detalleConcurso";

		switch ($opcion) {

                        case "validoRequisito":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=validoRequisito";

																$variable.="&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
																$variable.="&consecutivo_perfil=".$_REQUEST['consecutivo_perfil'];
																$variable.="&consecutivo_inscrito=".$_REQUEST['consecutivo_inscrito'];
                                break;

                        case "noValidoRequisito":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=noValidoRequisito";

																$variable.="&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
																$variable.="&consecutivo_perfil=".$_REQUEST['consecutivo_perfil'];
																$variable.="&consecutivo_inscrito=".$_REQUEST['consecutivo_inscrito'];
                                break;
                            /*******/

                        case "existe":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=existe";
                                $variable.="&rol_alias=".$valor['rol_alias'];
                                $variable.="&subsistema=".$valor['subsistema'];
                                $variable.="&nroUser=".$valor['nroUser'];
                                break;

                        case "borrar":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=borro";
                                $variable.="&rol_alias=".$valor['rol_alias'];
                                $variable.="&subsistema=".$valor['subsistema'];
                                break;

                        case "noborrar":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=noborro";
                                $variable.="&rol_alias=".$valor['rol_alias'];
                                $variable.="&subsistema=".$valor['subsistema'];
                                break;

                        case "actualizoConcurso":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=actualizoConcurso";
                                $variable.="&id_usuario=".$valor['id_usuario'];
                                $variable.="&nombre=".$valor['nombre'];
                                break;


                        case "noActualizo":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=errorActualizo";
                                if($valor!=""){
                                    $variable.="&id_usuario=".$valor['id_usuario'];
                                    $variable.="&nombre=".$valor['nombre'];
                                    }
                                break;
                        case "noActualizoDetalle":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=errorActualizoDetalle";
                                if($valor!=""){
                                    $variable.="&detalle=".$valor['detalle'];
                                    $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                    }
                                break;

                        case "inhabilitarConcurso":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=inhabilitoConcurso";
                                $variable.="&nombre=".$valor;

                                break;

                        case "noinhabilitarConcurso":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=noInhabilitoConcurso";
                                if($valor!=""){
                                        $variable.="&nombre=".$valor;
                                }
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

		// $enlace =$miConfigurador->getVariableConfiguracion("enlace");
		// $variable = $miConfigurador->fabricaConexiones->crypto->codificar($variable);
		// // echo $enlace;
		// // // echo $variable;
		// // exit;
		// $_REQUEST[$enlace] = $variable;
		// $_REQUEST["recargar"] = true;
		// return true;
	}
}

?>
