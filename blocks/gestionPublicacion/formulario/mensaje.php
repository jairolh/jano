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
		$conexion = "inventarios";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		// Limpia Items Tabla temporal
		
		// $cadenaSql = $this->miSql->getCadenaSql ( 'limpiar_tabla_items' );
		// $resultado_secuancia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "acceso" );
		
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
			// $atributos ["leyenda"] = "Regitrar Orden Compra";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			{
			if (isset ( $_REQUEST ['mensaje'] ))
                            {
				
                            switch ($_REQUEST ['mensaje'])
                                {   case "confirma":
                                        $tipo = 'success';
                                        $mensaje = "El usuario ".$_REQUEST['nombres']." ".$_REQUEST['apellidos']." ha sido creado exitosamente. Puede descargar el siguiente PDF con la información del usuario.";
                                        $boton = "continuar";
                                        $valorCodificado="pagina=gestionUsuarios";
                                        $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($valorCodificado,$directorio);

                                        $variableResumen = "pagina=gestionUsuarios"; //pendiente la pagina para modificar parametro                                                        
                                        $variableResumen.= "&action=".$esteBloque["nombre"];
                                        $variableResumen.= "&bloque=" . $esteBloque["id_bloque"];
                                        $variableResumen.= "&bloqueGrupo=" . $esteBloque["grupo"];
                                        $variableResumen.= "&opcion=resumen";
                                        $variableResumen.= "&identificacion=" . $_REQUEST['identificacion'];
                                        $variableResumen.= "&nombres=" .$_REQUEST['nombres'];
                                        $variableResumen.= "&apellidos=" .$_REQUEST['apellidos'];
                                        $variableResumen.= "&correo=" .$_REQUEST['correo'];
                                        $variableResumen.= "&telefono=" .$_REQUEST['telefono'];
                                        $variableResumen.= "&id_usuario=" .$_REQUEST['id_usuario'];
                                        $variableResumen.= "&perfilUs=" .$_REQUEST['perfilUs'];
                                        $variableResumen.= "&password=" .$_REQUEST['password'];
                                        $variableResumen = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableResumen, $directorio);

                                        //------------------Division para los botones-------------------------
                                        $atributos["id"]="botones";
                                        $atributos["estilo"]="marcoBotones";
                                        echo $this->miFormulario->division("inicio",$atributos);

                                        $enlace = "<a href='".$variableResumen."'>";
                                        $enlace.="<img src='".$rutaBloque."/images/pdfImage.png' width='35px'><br>Descargar Resumen ";
                                        $enlace.="</a><br><br>";       
                                        echo $enlace;
                                        //------------------Fin Division para los botones-------------------------
                                        echo $this->miFormulario->division("fin"); 
                              
                                        break;
                                    
                                    case "error":
                                        $tipo = 'error';
                                        $mensaje = "El usuario ".$_REQUEST['nombres']." ".$_REQUEST['apellidos']." no ha sido creado. Por favor intente mas tarde.";
                                        break;
                                    
                                    case "existe":
                                        $tipo = 'error';
                                        $mensaje = "Ya existe un  usuario con la identificacion ".$_REQUEST['identificacion']."!";
                                        $boton = "regresar";
                                        break;

                                    case "existeLog":
                                        $tipo = 'error';
                                        $mensaje = "No es posible Eliminar el usuario <b>".$_REQUEST['id_usuario']." - ".$_REQUEST['nombres']." ".$_REQUEST['apellidos']."</b>, ya que tiene registros asociados en el Sistema!!";
                                        $boton = "regresar";
                                        break;                                    

                                    case "borro":
                                        $tipo = 'success';
                                        $mensaje = "El el usuario <b>".$_REQUEST['id_usuario']." - ".$_REQUEST['nombres']." ".$_REQUEST['apellidos']."</b>, Se ha eliminado exitosamente.";
                                        $boton = "continuar";
                                        break;  
                                    
                                    case "noborro":
                                        $tipo = 'error';
                                        $mensaje = "No fue posible Eliminar el usuario <b>".$_REQUEST['id_usuario']." - ".$_REQUEST['nombres']." ".$_REQUEST['apellidos']."</b>,  Por favor intente mas tarde.";
                                        $boton = "regresar";
                                        break;                                    
                                    
                                    
                                    case "actualizo":
                                        $tipo = 'success';
                                        $mensaje = "Los datos del usuario ".$_REQUEST['id_usuario']." se actualizarón exitosamente.";
                                        $boton = "continuar";
                                        break;
                                    
                                    case "errorActualizo":
                                        $tipo = 'error';
                                        $mensaje = "Los datos del usuario ".$_REQUEST['id_usuario']." no se han actualizado. Por favor intente mas tarde.";
                                        $boton = "regresar";
                                        break;
                                    
                                    case "inhabilito":
                                        $tipo = 'success';
                                        $mensaje = "El usuario se inhabilito con exito.";
                                        $boton = "continuar";
                                        break;
                                    
                                    case "noInhabilito":
                                        $tipo = 'error';
                                        $mensaje = "El usuario no se pudo inhabilitar. Por favor intente mas tarde.";
                                        $boton = "regresar";
                                        break;
                                    
                                    case "habilito":
                                        $tipo = 'success';
                                        $mensaje = "El usuario se habilito con exito.";
                                        $boton = "continuar";
                                        break;
                                    
                                    case "nohabilito":
                                        $tipo = 'error';
                                        $mensaje = "El usuario no se pudo habilitar. Por favor intente mas tarde.";
                                        $boton = "regresar";
                                        break;

                                    case "confirmaPerfil":
                                        $tipo = 'success';
                                        $mensaje = "Se registro con exito el perfil <b>".$_REQUEST['perfilUs']."</b> al Usuario <b>".$_REQUEST['id_usuario']."</b>.";
                                        $boton = "continuar";
                                        $variable.="&opcion=perfil";
                                        $variable.="&id_usuario=".$_REQUEST['id_usuario'];
                                        break;
                                    
                                    case "errorPerfil":
                                        $tipo = 'error';
                                        $mensaje = "No fue posible registrar el nuevo perfil al Usuario ".$_REQUEST['id_usuario'].". Por favor intente mas tarde.";
                                        $boton = "regresar";
                                        $variable.="&opcion=perfil";
                                        $variable.="&id_usuario=".$_REQUEST['id_usuario'];
                                        break;
                                    
                                    case "confirmaEditaPerfil":
                                        $tipo = 'success';
                                        $mensaje = "Se actualizó con exito el perfil <b>".$_REQUEST['perfilUs']."</b> al Usuario <b>".$_REQUEST['id_usuario']."</b>.";
                                        $boton = "continuar";
                                        $variable.="&opcion=perfil";
                                        $variable.="&id_usuario=".$_REQUEST['id_usuario'];
                                        break;
                                    
                                    case "errorEditaPerfil":
                                        $tipo = 'error';
                                        $mensaje = "No fue posible actualizar el perfil al Usuario ".$_REQUEST['id_usuario'].". Por favor intente mas tarde.";
                                        $boton = "regresar";
                                        $variable.="&opcion=perfil";
                                        $variable.="&id_usuario=".$_REQUEST['id_usuario'];
                                        break;
                                    
                                    case "inhabilitoPerfil":
                                        $tipo = 'success';
                                        $mensaje = "El perfil del usuario se inhabilito con exito.";
                                        $boton = "continuar";
                                        $variable.="&opcion=perfil";
                                        $variable.="&id_usuario=".$_REQUEST['id_usuario'];
                                        break;
                                    
                                    case "noInhabilitoPerfil":
                                        $tipo = 'error';
                                        $mensaje = "El perfil del usuario no se pudo inhabilitar. Por favor intente mas tarde.";
                                        $boton = "regresar";
                                        $variable.="&opcion=perfil";
                                        $variable.="&id_usuario=".$_REQUEST['id_usuario'];
                                        break;
                                    
                                    case "habilitoPerfil":
                                        $tipo = 'success';
                                        $mensaje = "El perfil del usuario se habilito con exito.";
                                        $boton = "continuar";
                                        $variable.="&opcion=perfil";
                                        $variable.="&id_usuario=".$_REQUEST['id_usuario'];
                                        break;
                                    
                                    case "nohabilitoPerfil":
                                        $tipo = 'error';
                                        $mensaje = "El perfil del usuario no se pudo habilitar. Por favor intente mas tarde.";
                                        $boton = "regresar";
                                        $variable.="&opcion=perfil";
                                        $variable.="&id_usuario=".$_REQUEST['id_usuario'];
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
			
			// -----------------CONTROL: Botón ----------------------------------------------------------------
			/*
                        $esteCampo = 'botonContinuar';
			$atributos ["id"] = $esteCampo;
			$atributos ["tabIndex"] = $tab;
			$atributos ["tipo"] = 'boton';
			// submit: no se coloca si se desea un tipo button genérico
			$atributos ['submit'] = true;
			$atributos ["estiloMarco"] = '';
			$atributos ["estiloBoton"] = 'jqueryui';
			// verificar: true para verificar el formulario antes de pasarlo al servidor.
			$atributos ["verificar"] = '';
			$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
			$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['nombreFormulario'] = $esteBloque ['nombre'];
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			//echo $this->miFormulario->campoBoton ( $atributos );
                        */
                        
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
