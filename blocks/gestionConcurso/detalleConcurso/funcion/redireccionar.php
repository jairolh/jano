<?php

namespace gestionConcurso\detalleConcurso\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class redireccion {
	public static function redireccionar($opcion, $valor = "") {
		$miConfigurador = \Configurador::singleton ();
		//$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );
                $miPaginaActual="detalleConcurso";
		
		switch ($opcion) {
                        
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
                                //$variable.="&id_usuario=".$valor['id_usuario'];
                                $variable.="&nombre=".$valor['nombre'];
                                break;     
                            
                        case "actualizoCriterioConcurso":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=actualizoCriterioConcurso";
                                $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                break;          
                        case "actualizoCalendarioConcurso":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=actualizoCalendarioConcurso";
                                $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                break;  
                        case "actualizoPerfilConcurso":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=actualizoPerfilConcurso";
                                $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
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

                        case "habilitarConcurso":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=habilitoConcurso";
                                $variable.="&nombre=".$valor;

                                break;

                        case "nohabilitarConcurso":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=nohabilitoConcurso";
                                if($valor!=""){
                                        $variable.="&nombre=".$valor;
                                }
                                break;   
                        case "inhabilitarCriterio":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=inhabilitoCriterio";
                                $variable.="&nombre=".$valor['nombre'];
                                $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];

                                break;

                        case "noinhabilitarCriterio":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=noInhabilitoCriterio";
                                if($valor!=""){
                                    $variable.="&nombre=".$valor['nombre'];
                                    $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                }
                                break;        

                        case "habilitarCriterio":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=habilitoCriterio";
                                $variable.="&nombre=".$valor['nombre'];
                                $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];

                                break;

                        case "nohabilitarCriterio":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=nohabilitoCriterio";
                                if($valor!=""){
                                    $variable.="&nombre=".$valor['nombre'];
                                    $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                }
                                break;                                   
                        case "inhabilitarCalendario":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=inhabilitoCalendario";
                                $variable.="&nombre=".$valor['nombre'];
                                $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                break;

                        case "noinhabilitarCalendario":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=noInhabilitoCalendario";
                                if($valor!=""){
                                    $variable.="&nombre=".$valor['nombre'];
                                    $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                }
                                break;        

                        case "habilitarCalendario":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=habilitoCalendario";
                                $variable.="&nombre=".$valor['nombre'];
                                $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                break;

                        case "nohabilitarCalendario":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=nohabilitoCalendario";
                                if($valor!=""){
                                    $variable.="&nombre=".$valor['nombre'];
                                    $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                }
                                break;                                   
                                
                        case "inhabilitarPerfil":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=inhabilitoPerfil";
                                $variable.="&nombre=".$valor['nombre'];
                                $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                break;

                        case "noinhabilitarPerfil":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=noInhabilitoPerfil";
                                if($valor!=""){
                                    $variable.="&nombre=".$valor['nombre'];
                                    $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                }
                                break;        

                        case "habilitarPerfil":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=habilitoPerfil";
                                $variable.="&nombre=".$valor['nombre'];
                                $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
                                break;

                        case "nohabilitarPerfil":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=nohabilitoPerfil";
                                if($valor!=""){
                                    $variable.="&nombre=".$valor['nombre'];
                                    $variable.="&consecutivo_concurso=".$valor['consecutivo_concurso'];
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