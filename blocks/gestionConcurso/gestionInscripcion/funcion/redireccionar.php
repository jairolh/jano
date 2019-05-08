<?php

namespace gestionConcurso\gestionInscripcion\funcion;

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

                        case "juradoAsignado":
                            $variable="pagina=".$miPaginaActual;
                            $variable.="&opcion=mensaje";
                            $variable.="&mensaje=juradoAsignado";
                            $variable.="&nombre_concurso=".$valor['nombre_concurso'];
                            $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                            $variable.="&tab=".$valor['tab'];
                            break;

                        case "noAsignoJurado":
                            $variable="pagina=".$miPaginaActual;
                            $variable.="&opcion=mensaje";
                            $variable.="&mensaje=noAsignoJurado";
                            $variable.="&nombre_concurso=".$valor['nombre_concurso'];
                            $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                            $variable.="&tab=".$valor['tab'];
                            break;

                        case "Cerro":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=Cerro";
                                $variable.="&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                                $variable.="&nombre_concurso=".$_REQUEST['nombre_concurso'];
                                break;

                        case "noCerro":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=noCerro";
                                $variable.="&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                                $variable.="&nombre_concurso=".$_REQUEST['nombre_concurso'];
                                break;

                        case "CerroFase":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=CerroFase";
                                $variable.="&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                                $variable.="&nombre_concurso=".$_REQUEST['nombre_concurso'];
                                $variable.="&nombre=".$_REQUEST['nombre'];
                                break;

                        case "noCerroFase":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=noCerroFAse";
                                $variable.="&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                                $variable.="&nombre_concurso=".$_REQUEST['nombre_concurso'];
                                $variable.="&nombre=".$_REQUEST['nombre'];
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
