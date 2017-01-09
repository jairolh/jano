<?php

namespace bloquesNovedad\vinculacionPersonaNatural\funcion;
                        
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class Redireccionador {
	public static function redireccionar($opcion, $valor = "") {
		
	    
            $miConfigurador = \Configurador::singleton ();
            $miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );
		
            
		switch ($opcion) {
			case "inserto" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=inserto";
				
				break;
			
			case "noInserto" :
                            $variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=noInserto";
                              
				break;
			
			
			case "verdetalle" :
				$variable = 'pagina='.$miPaginaActual;                                
				$variable .= "&opcion=verdetalle";
                                $variable .= '&variable=' . $valor;
                                break;	
                            
                            case "modificar" :
				$variable = 'pagina='.$miPaginaActual;                                
				$variable .= "&opcion=fmodificar";
                                $variable .= '&variable=' . $valor;
                                break;	
			case "form" :
                          
				$variable = 'pagina='.$miPaginaActual;                                
				$variable .= "&opcion=form";
                                
                                break;	
                            case "inactivar" :
                                
				$variable = 'pagina='.$miPaginaActual;                                
				$variable .= "&opcion=inactivar";
                                $variable .= '&variable=' . $valor;
                            break;    
                        
                         case "vincular" :
                                
				$variable = 'pagina='.$miPaginaActual;                                
				$variable .= "&opcion=vincular";
                                $variable .= '&variable=' . $valor;
                               
                            break; 
                         case "opcion1" :
                                
				$variable = 'pagina='.$miPaginaActual;                                
				$variable .= "&opcion=opcion1";
                                $variable .= '&variable=' . $valor;
                               
                            break; 
                        case "opcion2" :
                                
				$variable = 'pagina='.$miPaginaActual;                                
				$variable .= "&opcion=opcion2";
                                $variable .= '&variable=' . $valor;
                               
                            break; 
                        case "opcion3" :
                                
				$variable = 'pagina='.$miPaginaActual;                                
				$variable .= "&opcion=opcion3";
                                $variable .= '&variable=' . $valor;
                               
                            break; 
			
		}
		foreach ( $_REQUEST as $clave => $valor ) {
			unset ( $_REQUEST [$clave] );
		}
		$url = $miConfigurador->configuracion ["host"] . $miConfigurador->configuracion ["site"] . "/index.php?";
		
		$enlace = $miConfigurador->getVariableConfiguracion ( "enlace" );
		$variable = $miConfigurador->fabricaConexiones->crypto->codificar ( $variable );
		$_REQUEST [$enlace] = $enlace . '=' . $variable;
		$redireccion = $url . $_REQUEST [$enlace];
		
		echo "<script>location.replace('" . $redireccion . "')</script>";
		
		$_REQUEST [$enlace] = $variable;
		$_REQUEST ["recargar"] = true;
		
		return true;
	}
}
?>