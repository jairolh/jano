<?php

namespace usuarios\gestionRoles\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class redireccion {
	public static function redireccionar($opcion, $valor = "") {
		$miConfigurador = \Configurador::singleton ();
		//$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );
                $miPaginaActual="gestionRoles";
		
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
                                
                        case "inhabilitar":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=inhabilito";
                                $variable.="&rol_alias=".$valor['rol_alias'];
                                $variable.="&subsistema=".$valor['subsistema'];

                                break;

                        case "noinhabilitar":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=noInhabilito";
                                $variable.="&rol_alias=".$valor['rol_alias'];
                                $variable.="&subsistema=".$valor['subsistema'];
                                break;        

                        case "habilitar":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=habilito";
                                $variable.="&rol_alias=".$valor['rol_alias'];
                                $variable.="&subsistema=".$valor['subsistema'];

                                break;

                        case "nohabilitar":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=nohabilito";
                                $variable.="&rol_alias=".$valor['rol_alias'];
                                $variable.="&subsistema=".$valor['subsistema'];
                                break;   
                        case "insertoSub":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=confirmaSub";
                                $variable.="&nombreSub=".$valor['nombre'];
                                $variable.="&idSub=".$valor['id_subsistema'];
                                break;
                            
                        case "noInsertoSub":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=errorSub";
                                $variable.="&nombreSub=".$valor['nombre'];
                                break;

                                
                                
                        case "insertoPerfil":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=confirmaPerfil";
                                $variable.="&perfilUs=".$valor['perfilUs'];
                                $variable.="&perfilSub=".$valor['perfilSub'];
                                break;
                            
                        case "noInsertoPerfil":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=errorPerfil";
                                break;

                        case "editoRol":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=confirmaEditaRol";
                                $variable.="&rol_alias=".$valor['rol_alias'];
                                $variable.="&subsistema=".$valor['etiqueta'];
                                break;

                        case "noEditoRol":
                                $variable="pagina=".$miPaginaActual;
                                $variable.="&opcion=mensaje";
                                $variable.="&mensaje=errorEditaRol";
                                $variable.="&rol_alias=".$valor['rol_alias'];
                                $variable.="&subsistema=".$valor['etiqueta'];
                                $variable.="&rol_id=".$valor['rol_id'];
                                $variable.="&id_subsistema=".$valor['id_subsistema'];
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