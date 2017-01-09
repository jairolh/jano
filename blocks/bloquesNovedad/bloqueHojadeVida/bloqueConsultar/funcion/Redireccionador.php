<?php

namespace bloquesNovedad\bloqueHojadeVida\bloqueConsultar\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class Redireccionador {
	public static function redireccionar($opcion, $valor = "") {
		
	    //$miConfigurador = \Configurador::singleton ();
		$miConfigurador = \Configurador::singleton ();
		$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );
	    
	    switch ($opcion) {
	    	case "inserto" :
	    
	    		$variable = "pagina=" . $miPaginaActual;
	    		$variable .= "&opcion=mensaje";
	    		$variable .= "&mensaje=inserto";
	    		$variable .= "&primerNombre=" . $valor ['primerNombre'];
	    		$variable .= "&segundoNombre=" . $valor ['segundoNombre'];
	    		$variable .= "&primerApellido=" . $valor ['primerApellido'];
	    		$variable .= "&segundoApellido=" . $valor ['segundoApellido'];
	    		//var_dump("Si llego aqui");
	    	//	var_dump($variable);
	    		//exit;
	    		break;
	    			
	    	case "noInserto" :
	    		$variable = "pagina=" . $miPaginaActual;
	    		$variable .= "&opcion=mensaje";
	    		$variable .= "&mensaje=noInserto";
	    		$variable .= "&primerNombre=" . $valor ['primerNombre'];
	    		$variable .= "&segundoNombre=" . $valor ['segundoNombre'];
	    		$variable .= "&primerApellido=" . $valor ['primerApellido'];
	    		$variable .= "&segundoApellido=" . $valor ['segundoApellido'];
	    
	    		break;
	    		
			case "noInsertoVal" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=noInsertoVal";
				$variable .= "&primerNombre=" . $valor ['primerNombre'];
				$variable .= "&segundoNombre=" . $valor ['segundoNombre'];
				$variable .= "&primerApellido=" . $valor ['primerApellido'];
				$variable .= "&segundoApellido=" . $valor ['segundoApellido'];
				
				break;
	    			
	    	case "regresar" :
	    		$variable = "pagina=" . $miPaginaActual;
	    		break;
	    			
	    	case "paginaPrincipal" :
	    		$variable = "pagina=" . $miPaginaActual;
	    		break;
	    		
	    	case "consultarFun" :
				$variable = "pagina=" . $miPaginaActual;
	    		$variable .= "&opcion=consultar";
				break;
			
			case "modificarFun" :
				$variable = "pagina=" . $miPaginaActual;
	    		$variable .= "&opcion=modificar";
				break;
				
	    }
	    
	    /*
		switch ($opcion) {
			
			case "opcion1" :
				
				$variable = 'pagina=segundaPagina';
				$variable .= '&variable' . $valor;				
				break;
				
			default:
			    $variable='';
			
		}*/
	    
		foreach ( $_REQUEST as $clave => $valor ) {
			unset ( $_REQUEST [$clave] );
		}
		
		$url = $miConfigurador->configuracion ["host"] . $miConfigurador->configuracion ["site"] . "/index.php?";
		$enlace = $miConfigurador->configuracion ['enlace'];
		$variable = $miConfigurador->fabricaConexiones->crypto->codificar ( $variable );
		$_REQUEST [$enlace] = $enlace . '=' . $variable;
		$redireccion = $url . $_REQUEST [$enlace];
		
		echo "<script>location.replace('" . $redireccion . "')</script>";
		
		/*
		$url = $miConfigurador->configuracion ["host"] . $miConfigurador->configuracion ["site"] . "/index.php?";
		$enlace = $miConfigurador->configuracion ['enlace'];
		$variable = $miConfigurador->fabricaConexiones->crypto->codificar ( $variable );
		$_REQUEST [$enlace] = $enlace . '=' . $variable;
		$redireccion = $url . $_REQUEST [$enlace];
		
		echo "<script>location.replace('" . $redireccion . "')</script>";*/
		
		/*
		$enlace = $miConfigurador->getVariableConfiguracion ( "enlace" );
		$variable = $miConfigurador->fabricaConexiones->crypto->codificar ( $variable );
		
		$_REQUEST [$enlace] = $variable;
		$_REQUEST ["recargar"] = true;*/
		
		return true;
	}
}
?>