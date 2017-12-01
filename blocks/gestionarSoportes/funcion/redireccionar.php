<?php

namespace gestionarSoportes\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class redireccion {
	public static function redireccionar($opcion, $valor = "") {
		$miConfigurador = \Configurador::singleton ();
		$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );
		
		switch ($opcion) {
			
			case "inserto" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=confirma";
				$variable .= "&usuario=" . $_REQUEST ['usuario'];
                                $variable .= "&codEmpleado=" . $_REQUEST['codEmpleado'];
				break;
			
			case "noInserto" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=error";
				$variable .= "&usuario=" . $_REQUEST ['usuario'];
				break;
			
			case "elimino" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=elimina";
				$variable .= "&usuario=" . $_REQUEST ['usuario'];
				break;
			
			case "noElimino" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=noelimina";
				$variable .= "&usuario=" . $_REQUEST ['usuario'];
				break;
			
			case "crear" :
				$variable = "pagina=crearNoticia";
				$variable .= "&usuario=" . $_REQUEST ['usuario'];
				break;
			
			case "continuar" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&usuario=" . $_REQUEST ['usuario'];
				break;
			
			case "devolver" :
				$variable = "pagina=" . $miPaginaActual;
                                $variable .= "&opcion=buscarNovedades";
				$variable .= "&usuario=" . $_REQUEST ['usuario'];
                                $variable .= "&TipoBusqueda=cod";
                                $variable .= "&documento=" . $_REQUEST['codEmpleado'];
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