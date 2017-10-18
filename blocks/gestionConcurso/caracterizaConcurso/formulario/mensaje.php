<?php

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

class registrarForm {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	
	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();
		
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		
		$this->lenguaje = $lenguaje;
		
		$this->miFormulario = $formulario;
		
		$this->miSql = $sql;
	}
	
	function miForm() {
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		// ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------
		/**
		 * Atributos que deben ser aplicados a todos los controles de este formulario.
		 * Se utiliza un arreglo
		 * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
		 *
		 * Si se utiliza esta técnica es necesario realizar un mezcla entre este arreglo y el específico en cada control:
		 * $atributos= array_merge($atributos,$atributosGlobales);
		 */
		$_REQUEST ['tiempo'] = time ();
		
		$atributosGlobales ['campoSeguro'] = 'true';
		
		// -------------------------------------------------------------------------------------------------
		
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = 'multipart/form-data';
		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';
		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = true;
		$tab = 1;
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		{
			
			$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
				
			$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
                        
            $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
            $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
            $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
				
			$variable = "pagina=" . $miPaginaActual;

				
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'botonRegresar';
			$atributos ['id'] = $esteCampo;
			$atributos ['enlace'] = $variable;
			$atributos ['tabIndex'] = 1;
			//$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['estilo'] = 'textoPequenno textoGris';
			$atributos ['enlaceImagen'] = $rutaBloque."/images/atras.png";
			$atributos ['ancho'] = '30px';
			$atributos ['alto'] = '30px';
			$atributos ['redirLugar'] = true;
			//echo $this->miFormulario->enlace ( $atributos );
            unset ( $atributos );
			
			// ---------------- SECCION: Controles del Formulario -----------------------------------------------
			
			$esteCampo = "marcoDatosBasicos";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			{
			if (isset ( $_REQUEST ['mensaje'] )){
				
				switch ($_REQUEST ['mensaje']){   
					
					case "confirmaFactor":
						$tipo = 'success';
						$mensaje = "Se registro con exito el Factor de Evaluación <b>".$_REQUEST['nombreFactor']."</b>.";
						$boton = "continuar";
						$variable.="&opcion=nuevo";
						break;
					
					case "errorFactor":
						$tipo = 'error';
						$mensaje = "No fue posible registrar el nuevo Factor de evaluación <b>".$_REQUEST['nombreFactor']."</b>. Por favor intente más tarde.";
                        $boton = "regresar";
                        $variable.="&opcion=nuevo";
                        break;
					
                        case "confirmaCriterio":
                                $tipo = 'success';
                                $mensaje = "Se registro con éxito el Criterio de Evaluación <b>".$_REQUEST['nombreCriterio']."</b>.";
                                $boton = "continuar";
                        //$variable.="&opcion=nuevo";
                        break;
                  	
                  	case "errorCriterio":
                  		$tipo = 'error';
                  		$mensaje = "No fue posible registrar el nuevo Criterio de evaluación <b>".$_REQUEST['nombreCriterio']."</b>. Por favor intente más tarde.";
                        $boton = "regresar";
                        //$variable.="&opcion=nuevo";
                        break;
                        
					case "confirmaModalidad":
                        $tipo = 'success';
                       	$mensaje = "Se registro con éxito la Modalidad de Consurso <b>".$_REQUEST['nombreModalidad']."</b>.";
                       	$boton = "continuar";
                       	$variable.="&opcion=gestionModalidad";
                       	break;
                        	 
			case "errorModalidad":
                                $tipo = 'error';
                                $mensaje = "No fue posible registrar el la Modalidad de Consurso <b>".$_REQUEST['nombreModalidad']."</b>. Por favor intente más tarde.";
                                $boton = "regresar";
                                $variable.="&opcion=gestionModalidad";
                                break;
                        
                       	
			case "confirmaActividad":
                                $tipo = 'success';
                                $mensaje = "Se registro con éxito la Actividad <b>".$_REQUEST['nombreActividad']."</b>.";
                                $boton = "continuar";
                                $variable.="&opcion=gestionActividades";
                                break;
                   	
                   	case "errorActividad":
                                $tipo = 'error';
                                $mensaje = "No fue posible registrar la Actividad <b>".$_REQUEST['nombreActividad']."</b>. Por favor intente más tarde.";
                                $boton = "regresar";
                                $variable.="&opcion=gestionActividades";
                                break;
                 	
                 	case "confirmaEditaFactor":
                 		$tipo = 'success';
                 		$mensaje = "Se actualizó con exito el Factor <b>".$_REQUEST ["nombreFactor"]." </b>.";
                 		$boton = "continuar";
                 		break;
                 	
                 	case "errorEditaFactor":
                 		$tipo = 'error';
                 		$mensaje = "No fue posible actualizar el Factor <b>".$_REQUEST ["nombreFactor"]."</b>. Por favor intente más tarde.";
                        $boton = "regresar";
                        $variable.="&opcion=editar";
                        $variable.="&id_factor=".$_REQUEST ["id_factor"];
                        break;
                        
                   	case "confirmaEditaModalidad":
                      	$tipo = 'success';
                      	$mensaje = "Se actualizó con exito la Modalidad <b>".$_REQUEST ["nombreModalidad"]." </b>.";
                       	$boton = "continuar";
                       	$variable.="&opcion=gestionModalidad";
                       	break;
                        
                   	case "errorEditaModalidad":
                        $tipo = 'error';
                       	$mensaje = "No fue posible actualizar la Modalidad <b>".$_REQUEST ["nombreModalidad"]."</b>. Por favor intente más tarde.";
                       	$boton = "regresar";
                       	$variable.="&opcion=gestionModalidad";
                       	break;
                       	
                   	case "modalidadEnConsurso":
                       	$tipo = 'error';
                       	$mensaje = "La modallidad <b>".$_REQUEST ["nombreModalidad"]." </b> está asociada a un consurso, no se puede editar";
                       	$boton = "continuar";
                       	$variable.="&opcion=gestionModalidad";
                       	break;
                       	
                 	case "actividadEnConsurso":
                    	$tipo = 'error';
                       	$mensaje = "La actividad <b>".$_REQUEST ["nombreActividad"]." </b> está asociada a un consurso, no se puede editar";
                       	$boton = "continuar";
                      	$variable.="&opcion=gestionActividades";
                       	break;
                       	
                  	case "confirmaEditaActividad":
                      	$tipo = 'success';
                      	$mensaje = "Se actualizó con exito la Actividad <b>".$_REQUEST ["nombreActividad"]." </b>.";
                       	$boton = "continuar";
                       	$variable.="&opcion=gestionActividades";
                       	break;
                       	
                    case "errorEditaActividad":
                       	$tipo = 'error';
                       	$mensaje = "No fue posible actualizar la Actividad <b>".$_REQUEST ["nombreActividad"]."</b>. Por favor intente más tarde.";
                      	$boton = "regresar";
                      	$variable.="&opcion=gestionActividades";
                       	break;
                        
                   	case "inhabilito":
                   		$tipo = 'success';
                   		$mensaje = "El Criterio <b>".$_REQUEST ["criterio"]." </b> se inhabilitó con éxito.";
                   		$boton = "continuar";
                   		break;
                   	
                   	case "noinhabilito":
                   		$tipo = 'error';
                   		$mensaje = "El Criterio <b>".$_REQUEST ["criterio"]." </b> no se pudo inhabilitar. Por favor intente más tarde.";
                   		$boton = "regresar";
                   		break;
                   	
                   	case "habilito":
                   		$tipo = 'success';
                   		$mensaje = "El Criterio <b>".$_REQUEST ["criterio"]." </b> se habilitó con éxito.";
                   		$boton = "continuar";
                   		break;
                   		
                 	case "nohabilito":
                   		$tipo = 'error';
                   		$mensaje = "El Factor <b>".$_REQUEST ["factor"]." </b> no se pudo habilitar. Por favor intente más tarde.";
                   		$boton = "regresar";
                   		break;
                   		
                   	/*Mensaje para Habilitar o Deshabilitar las Modalidades*/
                   
                   	case "habilitoModalidad":
                   		$tipo = 'success';
                   		$mensaje = "La modalidad <b>".$_REQUEST ["modalidad"]." </b> se habilitó con éxito.";
                   		$boton = "continuar";
                   		$variable.="&opcion=gestionModalidad";
                   		break;
                   			
                   	case "inhabilitoModalidad":
                   		$tipo = 'success';
                   		$mensaje = "La modalidad <b>".$_REQUEST ["modalidad"]." </b> se inhabilitó con éxito.";
                   		$boton = "continuar";
                   		$variable.="&opcion=gestionModalidad";
                   		break;
                   		
                   	case "nohabilitoModalidad":
                   		$tipo = 'error';
                  		$mensaje = "La modalidad <b>".$_REQUEST ["modalidad"]." </b> no se pudo habilitar. Por favor intente más tarde.";
                   		$boton = "regresar";
                   		$variable.="&opcion=gestionModalidad";
                  		break;
                   		
                   	case "noinhabilitoModalidad":
                   		$tipo = 'error';
                   		$mensaje = "La modalidad <b>".$_REQUEST ["modalidad"]." </b> no se pudo inhabilitar. Por favor intente más tarde.";
                   		$boton = "regresar";
                   		$variable.="&opcion=gestionModalidad";
                  		break;
                  		
                   	/*Mensaje para Habilitar o Deshabilitar las Actividades*/
                  		
                   	case "habilitoActividad":
                   		$tipo = 'success';
                   		$mensaje = "La actividad <b>".$_REQUEST ["actividad"]." </b> se habilitó con éxito.";
                   		$boton = "continuar";
                   		$variable.="&opcion=gestionActividades";
                  		break;
                   		
                   	case "inhabilitoActividad":
                   		$tipo = 'success';
                   		$mensaje = "La actividad <b>".$_REQUEST ["actividad"]." </b> se inhabilitó con éxito.";
                   		$boton = "continuar";
                   		$variable.="&opcion=gestionActividades";
                   		break;
                   	
                   	case "nohabilitoActividad":
                   		$tipo = 'error';
                   		$mensaje = "La actividad <b>".$_REQUEST ["actividad"]." </b> no se pudo habilitar. Por favor intente más tarde.";
                   		$boton = "regresar";
                   		$variable.="&opcion=gestionActividades";
                   		break;
                   	
                   	case "noinhabilitoActividad":
                   		$tipo = 'error';
                   		$mensaje = "La actividad <b>".$_REQUEST ["actividad"]." </b> no se pudo inhabilitar. Por favor intente más tarde.";
                   		$boton = "regresar";
                   		$variable.="&opcion=gestionActividades";
                  		break;
                	/*Mensaje para Habilitar o Deshabilitar las Cevaluaciones*/
                  		
                   	case "habilitoCevaluacion":
                   		$tipo = 'success';
                   		$mensaje = "El rol <b>".$_REQUEST ['rol']."</b> del criterio <b>".$_REQUEST ['criterio']."</b> se habilitó con éxito.";
                   		$boton = "continuar";
                                $variable.= "&opcion=rolesCriterio";    
                                $variable.= "&id_criterio=" .$_REQUEST['id_criterio'];
                                $variable.= "&factor=" .$_REQUEST['factor'];
                                $variable.= "&criterio=" .$_REQUEST['criterio'];
                                                                
                  		break;
                   		
                   	case "inhabilitoCevaluacion":
                   		$tipo = 'success';
                   		$mensaje = "El rol <b>".$_REQUEST ['rol']."</b> del criterio <b>".$_REQUEST ['criterio']."</b> se inhabilitó con éxito.";
                   		$boton = "continuar";
                                $variable.= "&opcion=rolesCriterio";    
                                $variable.= "&id_criterio=" .$_REQUEST['id_criterio'];
                                $variable.= "&factor=" .$_REQUEST['factor'];
                                $variable.= "&criterio=" .$_REQUEST['criterio'];

                   		break;
                   	
                   	case "nohabilitoCevaluacion":
                   		$tipo = 'error';
                   		$mensaje = "El rol <b>".$_REQUEST ['rol']."</b> del criterio <b>".$_REQUEST ['criterio']."</b> no se pudo habilitar. Por favor intente más tarde.";
                   		$boton = "regresar";
                                $variable.= "&opcion=rolesCriterio";    
                                $variable.= "&id_criterio=" .$_REQUEST['id_criterio'];
                                $variable.= "&factor=" .$_REQUEST['factor'];
                                $variable.= "&criterio=" .$_REQUEST['criterio'];

                   		break;
                   	
                   	case "noinhabilitoCevaluacion":
                   		$tipo = 'error';
                   		$mensaje = "El rol <b>".$_REQUEST ['rol']."</b> del criterio <b>".$_REQUEST ['criterio']."</b> no se pudo inhabilitar. Por favor intente más tarde.";
                   		$boton = "regresar";
                                $variable.= "&opcion=rolesCriterio";    
                                $variable.= "&id_criterio=" .$_REQUEST['id_criterio'];
                                $variable.= "&factor=" .$_REQUEST['factor'];
                                $variable.= "&criterio=" .$_REQUEST['criterio'];

                  		break;                            
                            
            	}
            	
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'mensaje';
                $atributos ['id'] = $esteCampo;
                $atributos ['tipo'] = $tipo;
                $atributos ['estilo'] = 'textoCentrar';
                $atributos ['mensaje'] = $mensaje;
                $tab ++;
                // Aplica atributos globales al control
                $atributos = array_merge ( $atributos, $atributosGlobales );
                echo $this->miFormulario->cuadroMensaje ( $atributos );	
            }
    	}
			// ------------------Division para los botones-------------------------
			$atributos ["id"] = "botones";
			$atributos ["estilo"] = "marcoBotones";
			echo $this->miFormulario->division ( "inicio", $atributos );
			
			
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
                        
            $esteCampo = 'botonContinuar';
			$atributos ['id'] = $esteCampo;
			$atributos ['enlace'] = $variable;
			$atributos ['tabIndex'] = 1;
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
			//$atributos ['ancho'] = '10%';
			//$atributos ['alto'] = '10%';
			$atributos ['redirLugar'] = true;
			echo $this->miFormulario->enlace ( $atributos );
            unset($atributos);
                        
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
			
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
			// ---------------- SECCION: División ----------------------------------------------------------
			$esteCampo = 'division1';
			$atributos ['id'] = $esteCampo;
			$atributos ['estilo'] = 'general';
			echo $this->miFormulario->division ( "inicio", $atributos );
			
			// ---------------- FIN SECCION: División ----------------------------------------------------------
			echo $this->miFormulario->division ( 'fin' );
			
			// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
			
			// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
			// Se debe declarar el mismo atributo de marco con que se inició el formulario.
		}
		
		// -----------------FIN CONTROL: Botón -----------------------------------------------------------
		
		// ------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division ( "fin" );
		
		// ------------------- SECCION: Paso de variables ------------------------------------------------
		
		/**
		 * En algunas ocasiones es útil pasar variables entre las diferentes páginas.
		 * SARA permite realizar esto a través de tres
		 * mecanismos:
		 * (a). Registrando las variables como variables de sesión. Estarán disponibles durante toda la sesión de usuario. Requiere acceso a
		 * la base de datos.
		 * (b). Incluirlas de manera codificada como campos de los formularios. Para ello se utiliza un campo especial denominado
		 * formsara, cuyo valor será una cadena codificada que contiene las variables.
		 * (c) a través de campos ocultos en los formularios. (deprecated)
		 */
		
		// En este formulario se utiliza el mecanismo (b) para pasar las siguientes variables:
		
		// Paso 1: crear el listado de variables
		
		$valorCodificado = "actionBloque=" . $esteBloque ["nombre"];
		$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
		$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
		$valorCodificado .= "&opcion=paginaPrincipal";
		/**
		 * SARA permite que los nombres de los campos sean dinámicos.
		 * Para ello utiliza la hora en que es creado el formulario para
		 * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
		 * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
		 * (b) asociando el tiempo en que se está creando el formulario
		 */
		$valorCodificado .= "&tiempo=" . time ();
		// Paso 2: codificar la cadena resultante
		$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );
		
		$atributos ["id"] = "formSaraData"; // No cambiar este nombre
		$atributos ["tipo"] = "hidden";
		$atributos ['estilo'] = '';
		$atributos ["obligatorio"] = false;
		$atributos ['marco'] = true;
		$atributos ["etiqueta"] = "";
		$atributos ["valor"] = $valorCodificado;
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		
		$atributos ['marco'] = true;
		$atributos ['tipoEtiqueta'] = 'fin';
		echo $this->miFormulario->formulario ( $atributos );
	}
}
$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
