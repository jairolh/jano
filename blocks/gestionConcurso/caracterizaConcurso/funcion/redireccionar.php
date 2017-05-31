<?php

namespace gestionConcurso\caracterizaConcurso\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class redireccion {
	public static function redireccionar($opcion, $valor = "") {
		$miConfigurador = \Configurador::singleton ();
		//$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );
       	$miPaginaActual="caracterizaConcurso";
		
		switch ($opcion) {
			
			/*Mensajes actualización estado del factor */
        	case "inhabilitar":
        		$variable="pagina=".$miPaginaActual;
        		$variable.="&opcion=mensaje";
        		$variable.="&mensaje=inhabilito";
        		$variable.="&factor=".$valor['nombre_factor'];
        		break; 
        	
        	case "habilitar":
        		$variable="pagina=".$miPaginaActual;
        		$variable.="&opcion=mensaje";
        		$variable.="&mensaje=habilito";
        		$variable.="&factor=".$valor['nombre_factor'];
				break;
				
			case "nohabilitar":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=nohabilito";
				$variable.="&factor=".$valor['nombre_factor'];
				break;
				
			case "noinhabilitar":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=noinhabilito";
				$variable.="&factor=".$valor['nombre_factor'];
				break;
					
			/*Mensajes actualización estado de la Modalidad*/
			case "inhabilitarModalidad":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=inhabilitoModalidad";
				$variable.="&modalidad=".$valor['nombre_modalidad'];
				break;
				
			case "habilitarModalidad":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=habilitoModalidad";
				$variable.="&modalidad=".$valor['nombre_modalidad'];
				break;
				
			case "nohabilitarModalidad":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=nohabilitoModalidad";
				$variable.="&modalidad=".$valor['nombre_modalidad'];
				break;
				
			case "noinhabilitarModalidad":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=noinhabilitoModalidad";
				$variable.="&modalidad=".$valor['nombre_modalidad'];
				break;
				
			/*Mensajes actualización estado de la Actividad*/
			case "inhabilitarActividad":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=inhabilitoActividad";
				$variable.="&actividad=".$valor['nombre_actividad'];
				break;
			
			case "habilitarActividad":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=habilitoActividad";
				$variable.="&actividad=".$valor['nombre_actividad'];
				break;
				
			case "noinhabilitarActividad":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=noinhabilitoActividad";
				$variable.="&actividad=".$valor['nombre_actividad'];
				break;
				
			case "nohabilitarActividad":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=nohabilitoActividad";
				$variable.="&actividad=".$valor['nombre_actividad'];
				break;
			/*Fin Mensajes actualización estado de la Actividad*/
			
			case "insertoFactor":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=confirmaFactor";
				$variable.="&nombreFactor=".$valor['nombre'];
				break;
			
			case "noInsertoFactor":
				$variable="pagina=".$miPaginaActual;
				$variable.="&opcion=mensaje";
				$variable.="&mensaje=errorFactor";
				$variable.="&nombreFactor=".$valor['nombre'];
                break;
            
            case "insertoCriterio":
            	$variable="pagina=".$miPaginaActual;
            	$variable.="&opcion=mensaje";
            	$variable.="&mensaje=confirmaCriterio";
            	$variable.="&nombreCriterio=".$valor['nombre'];
                break;
            
            case "noInsertoCriterio":
            	$variable="pagina=".$miPaginaActual;
            	$variable.="&opcion=mensaje";
            	$variable.="&mensaje=errorCriterio";
            	$variable.="&nombreCriterio=".$valor['nombre'];
            	break;
            	
            case "insertoModalidad":
            	$variable="pagina=".$miPaginaActual;
           		$variable.="&opcion=mensaje";
           		$variable.="&mensaje=confirmaModalidad";
           		$variable.="&nombreModalidad=".$valor['nombre'];
            	break;
            	
            case "noInsertoModalidad":
            	$variable="pagina=".$miPaginaActual;
            	$variable.="&opcion=mensaje";
           		$variable.="&mensaje=errorModalidad";
            	$variable.="&nombreModalidad=".$valor['nombre'];
            	break;
            	
            case "insertoActividad":
           		$variable="pagina=".$miPaginaActual;
           		$variable.="&opcion=mensaje";
            	$variable.="&mensaje=confirmaActividad";
           		$variable.="&nombreActividad=".$valor['nombre'];
           		break;
            		 
            case "noInsertoActividad":
           		$variable="pagina=".$miPaginaActual;
           		$variable.="&opcion=mensaje";
            	$variable.="&mensaje=errorActividad";
            	$variable.="&nombreActividad=".$valor['nombre'];
           		break;
            
            case "editoFactor":
            	$variable="pagina=".$miPaginaActual;
            	$variable.="&opcion=mensaje";
            	$variable.="&mensaje=confirmaEditaFactor";
            	$variable.="&id_factor=".$valor['id_factor'];
            	$variable.="&nombreFactor=".$valor['nombreFactor'];
            	break;
            	
            case "noEditoFactor":
            	$variable="pagina=".$miPaginaActual;
            	$variable.="&opcion=mensaje";
            	$variable.="&mensaje=errorEditaFactor";
            	$variable.="&id_factor=".$valor['id_factor'];
            	$variable.="&nombreFactor=".$valor['nombreFactor'];
            	break;
            	
	       	case "editoModalidad":
	          	$variable="pagina=".$miPaginaActual;
	           	$variable.="&opcion=mensaje";
	          	$variable.="&mensaje=confirmaEditaModalidad";
	         	$variable.="&id_modalidad=".$valor['id_modalidad'];
	          	$variable.="&nombreModalidad=".$valor['nombreModalidad'];
	           	break;
            		 
            case "noEditoModalidad":
            	$variable="pagina=".$miPaginaActual;
            	$variable.="&opcion=mensaje";
           		$variable.="&mensaje=errorEditaModalidad";
           		$variable.="&id_modalidad=".$valor['id_modalidad'];
            	$variable.="&nombreModalidad=".$valor['nombreModalidad'];
            	break;
            	
          	case "modalidadEnConsurso":
            	$variable="pagina=".$miPaginaActual;
            	$variable.="&opcion=mensaje";
            	$variable.="&mensaje=modalidadEnConsurso";
            	$variable.="&id_modalidad=".$valor['id_modalidad'];
            	$variable.="&nombreModalidad=".$valor['nombreModalidad'];
            	break;
            	
            case "editoActividad":
            	$variable="pagina=".$miPaginaActual;
            	$variable.="&opcion=mensaje";
           		$variable.="&mensaje=confirmaEditaActividad";
           		$variable.="&id_actividad=".$valor['id_actividad'];
            	$variable.="&nombreActividad=".$valor['nombreActividad'];
            	break;
            		 
            case "noEditoActividad":
           		$variable="pagina=".$miPaginaActual;
            	$variable.="&opcion=mensaje";
            	$variable.="&mensaje=errorEditaActividad";
           		$variable.="&id_actividad=".$valor['id_actividad'];
           		$variable.="&nombreActividad=".$valor['nombreActividad'];
           		break;
           		
          	case "actividadEnConsurso":
           		$variable="pagina=".$miPaginaActual;
           		$variable.="&opcion=mensaje";
           		$variable.="&mensaje=actividadEnConsurso";
          		$variable.="&id_actividad=".$valor['id_actividad'];
           		$variable.="&nombreActividad=".$valor['nombreActividad'];
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