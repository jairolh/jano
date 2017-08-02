<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
use gestionConcursante\gestionHoja\funcion\redireccion;

class calendarioForm {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
        var $rutaSoporte;   
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
                $directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
                $rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/";
                $this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "host" ) .$this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		
		// ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------
		/**
		 * Atributos que deben ser aplicados a todos los controles de este formulario.
		 * Se utiliza un arreglo
		 * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
		 *
		 * Si se utiliza esta técnica es necesario realizar un mezcla entre este arreglo y el específico en cada control:
		 * $atributos= array_merge($atributos,$atributosGlobales);
		 */
		$atributosGlobales ['campoSeguro'] = 'true';
		$_REQUEST ['tiempo'] = time ();
		$tiempo = $_REQUEST ['tiempo'];
		// lineas para conectar base de d atos-------------------------------------------------------------------------------------------------
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
                $seccion ['tiempo'] = $tiempo;
                $hoy=date("Y-m-d");
                $miSesion = \Sesion::singleton();
                $usuario=$miSesion->idUsuario();
                //identifca el usuario
		$parametro['id_usuario']=$usuario;
                $cadena_sql = $this->miSql->getCadenaSql("consultarBasicos", $parametro);
                $resultadoUsuarios = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                if(isset($_REQUEST['consecutivo_calendario']))
                    {  $parametro=array('consecutivo_calendario'=>$_REQUEST['consecutivo_calendario'],
                                        'consecutivo_concurso'=>$_REQUEST['consecutivo_concurso']);
                       $cadena_sql = $this->miSql->getCadenaSql("consultarCalendarioConcurso", $parametro);
                       $resultadoCalendario = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                       
                       $parametroSop = array('consecutivo'=>0,
                                             'tipo_dato'=>'autorizacionFecha',
                                             'nombre_soporte'=>'soporteAutorizacion',
                                             'consecutivo_dato'=>$_REQUEST['consecutivo_calendario']);
                        $cadenaSopAut_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                        $resultadoSopAut = $esteRecursoDB->ejecutarAcceso($cadenaSopAut_sql, "busqueda");
                    }
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
                $estefomulario= 'datosCalendario';
		$atributos ['id'] = $estefomulario;
		$atributos ['nombre'] =$estefomulario;
		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = 'multipart/form-data';
		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';
		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		$atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo );
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = false;
		$tab = 1;
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		{
			// ---------------- SECCION: Controles del Formulario -----------------------------------------------
			$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
                        $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
                        $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
			$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
			$variable = "pagina=" . $miPaginaActual;
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = "marcoCalendario";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
                        if(!isset($_REQUEST['consecutivo_calendario']))
                            { $atributos ["estiloEnLinea"] = "display:none;"; }
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{	      
                               // ---------------- CONTROL AGRUPACION: Cuadro Agrupacion --------------------------------------------------------
				$atributos ["id"] = "cuadro_calendario";
				$atributos ["estiloEnLinea"] = "display:block";
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'consecutivo_actividad';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = ' ';
                                    if (isset($resultadoCalendario[0]['consecutivo_actividad']))
                                         {  $atributos ['seleccion'] = $resultadoCalendario[0]['consecutivo_actividad'];
                                            $actividad=$resultadoCalendario[0]['consecutivo_actividad'];
                                         }
                                    else {  $atributos ['seleccion'] = -1;$actividad='';}
                                    if (isset($resultadoCalendario[0]['obligatoria']) && $resultadoCalendario[0]['obligatoria']=='S' )
                                         {$atributos ['deshabilitado'] = true;}
                                    else { $atributos ['deshabilitado'] = false;}
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 60;
                                    $atributos ['evento'] = '';
                                    $parametroAct=array('consecutivo_actividad'=>$actividad,
                                                     'consecutivo_concurso'=>$_REQUEST['consecutivo_concurso']);
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultaActividadCalendario",$parametroAct );
                                    $matrizItems = array (array (0,' '));
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'descripcion';
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['tipo'] = 'text';
                                    $atributos ['estilo'] = 'jqueryui';
                                    $atributos ['marco'] = true;
                                    $atributos ['estiloMarco'] = '';
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['columnas'] = 74;
                                    $atributos ['filas'] = 4;
                                    $atributos ['dobleLinea'] = 0;
                                    $atributos ['tabIndex'] = $tab;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ['validar'] = 'required, minSize[1], maxSize[255]';
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    if (isset ($resultadoCalendario[0]['descripcion']  )) 
                                        { $atributos ['valor'] =  $resultadoCalendario[0]['descripcion'] ;}
                                    else{ $atributos ['valor'] = ''; }
                                    $tab ++;

                                    // Aplica atributos globales al control
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoTextArea ( $atributos );
                                    unset ( $atributos );                                                   
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'fecha_inicio_calendario';
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['tipo'] = 'texto';
                                    $atributos ['estilo'] = 'jqueryui';
                                    $atributos ['marco'] = true;
                                    $atributos ['estiloMarco'] = '';
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['dobleLinea'] = 0;
                                    $atributos ['tabIndex'] = $tab;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ['validar']="required";
                                    if (isset ($resultadoCalendario[0]['fecha_inicio']  )) 
                                        { $atributos ['valor'] =  $resultadoCalendario[0]['fecha_inicio'] ;}
                                    else{ $atributos ['valor'] = ''; }
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = true;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    // Aplica atributos globales al control
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                               
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'fecha_fin_calendario';
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['tipo'] = 'texto';
                                    $atributos ['estilo'] = 'jqueryui';
                                    $atributos ['marco'] = true;
                                    $atributos ['estiloMarco'] = '';
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['dobleLinea'] = 0;
                                    $atributos ['tabIndex'] = $tab;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ['validar']="required";
                                    if (isset ($resultadoCalendario[0]['fecha_fin']  )) 
                                        { $atributos ['valor'] =  $resultadoCalendario[0]['fecha_fin'] ;}
                                    else{ $atributos ['valor'] = ''; }
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = true;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    // Aplica atributos globales al control
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'porc_aprueba_fase';
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['tipo'] = 'text';
                                    $atributos ['estilo'] = 'jqueryui';
                                    $atributos ['marco'] = true;
                                    $atributos ['estiloMarco'] = '';
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['dobleLinea'] = 0;
                                    $atributos ['tabIndex'] = $tab;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ['validar']="required,custom[number],minSize[1],min[0],max[100]";
                                    if (isset ( $resultadoCalendario[0]['porcentaje_aprueba'] )) 
                                         {  $atributos ['valor'] = $resultadoCalendario[0]['porcentaje_aprueba'];} 
                                    else {  $atributos ['valor'] = '0';}
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    if (isset($resultadoCalendario[0]['obligatoria']) && $resultadoCalendario[0]['obligatoria']=='S' )
                                         {$atributos ['deshabilitado'] = true;}
                                    else { $atributos ['deshabilitado'] = false;}
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                   
                                }
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );
				// ---------------- CONTROL: Fin Cuadro Agrupacion --------------------------------------------------------
                                
                                // ---------------- CONTROL: Cuadro de division Soportes --------------------------------------------------------
                                $atributos ["id"]="autorizacion";
                                $atributos ["estiloEnLinea"] = "border-width: 0";//display:block";
                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                echo $this->miFormulario->division ( "inicio", $atributos );
                                unset ( $atributos );
                                        {
                                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                            $esteCampo = 'soporteAutorizacion';
                                            $atributos ['id'] = $esteCampo;
                                            $atributos ['nombre'] = $esteCampo;
                                            $atributos ['tipo'] = 'file';
                                            $atributos ['estilo'] = 'jqueryui';
                                            $atributos ['marco'] = true;
                                            $atributos ['dobleLinea'] = false;
                                            $atributos ['tabIndex'] = $tab;
                                            $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                            $atributos ['etiquetaObligatorio'] = true;
                                            $atributos ['tamanno'] = 1024;
                                            $atributos ['evento'] = 'accept="pdf"';
                                            $atributos ['columnas'] = 1;
                                            $atributos ['validar'] = 'required,minSize[1]'; 
                                            if (isset ( $_REQUEST [$esteCampo] )) 
                                                 { $atributos ['valor'] = $_REQUEST [$esteCampo];}
                                            else {  $atributos ['valor'] = '';}
                                            $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                            $atributos ['deshabilitado'] = FALSE;
                                            $atributos ['anchoCaja'] = 60;
                                            $atributos ['maximoTamanno'] = '';
                                            $atributos ['anchoEtiqueta'] = 170;
                                            $atributos = array_merge ( $atributos, $atributosGlobales );
                                            if($hoy>$_REQUEST['inicio_concurso'])
                                                {echo $this->miFormulario->campoCuadroTexto ( $atributos );}
                                            // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                          if(is_array($resultadoSopAut))
                                                {
                                              foreach ($resultadoSopAut as $key => $value) 
                                                    {
                                                   // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                        $esteCampo = 'archivoAutorizacion'.$key;
                                                        $atributos ['id'] = $esteCampo;
                                                        $atributos ['enlace'] = 'javascript:soporte("ruta_autorizacion'.$key.'");';
                                                        $atributos ['tabIndex'] = 0;
                                                        $atributos ['marco'] = true;
                                                        $atributos ['columnas'] = 1;
                                                        $atributos ['enlaceTexto'] = $resultadoSopAut[$key]['alias'];
                                                        $atributos ['estilo'] = 'textoGrande textoGris ';
                                                        $atributos ['enlaceImagen'] = $rutaBloque."/images/pdfImage.png";
                                                        $atributos ['posicionImagen'] ="atras";//"adelante";
                                                        $atributos ['ancho'] = '25px';
                                                        $atributos ['alto'] = '25px';
                                                        $atributos ['redirLugar'] = false;
                                                        $atributos ['valor'] = '';
                                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                                        echo $this->miFormulario->enlace( $atributos );
                                                        unset ( $atributos );
                                                       // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                        $esteCampo = 'ruta_autorizacion'.$key;
                                                        $atributos ['id'] = $esteCampo;
                                                        $atributos ['nombre'] = $esteCampo;
                                                        $atributos ['tipo'] = 'hidden';
                                                        $atributos ['estilo'] = 'jqueryui';
                                                        $atributos ['marco'] = true;
                                                        $atributos ['columnas'] = 1;
                                                        $atributos ['dobleLinea'] = false;
                                                        $atributos ['tabIndex'] = $tab;
                                                        $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                        $atributos ['obligatorio'] = false;
                                                        $atributos ['etiquetaObligatorio'] = false;
                                                        $atributos ['validar'] = '';
                                                        $atributos ['valor'] = $this->rutaSoporte.$resultadoSopAut[$key]['ubicacion']."/".$resultadoSopAut[$key]['archivo'];
                                                        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                        $atributos ['deshabilitado'] = FALSE;
                                                        $atributos ['tamanno'] = 30;
                                                        $atributos ['anchoCaja'] = 60;
                                                        $atributos ['maximoTamanno'] = '';
                                                        $atributos ['anchoEtiqueta'] = 170;
                                                        //$atributos = array_merge ( $atributos, $atributosGlobales );
                                                        echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                                        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                    }
                                                }  
                                    }
                                echo $this->miFormulario->division( 'fin' );
                                unset ( $atributos );
                        // --------------- FIN CONTROL : Cuadro de Soporte Diploma --------------------------------------------------
                                
                                
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonCalendario';
					$atributos ["id"] = $esteCampo;
					$atributos ["tabIndex"] = $tab;
					$atributos ["tipo"] = 'boton';
					// submit: no se coloca si se desea un tipo button genérico
					$atributos ['submit'] = true;
					$atributos ["estiloMarco"] = '';
					$atributos ["estiloBoton"] = 'jqueryui';
                                        //$atributos ['columnas'] = 2;
					// verificar: true para verificar el formulario antes de pasarlo al servidor.
					$atributos ["verificar"] = '';
					$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
					$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['nombreFormulario'] = $estefomulario;//$esteBloque ['nombre'];
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
                                        
                                        if($hoy<$_REQUEST['cierre_concurso'])
                                            {echo $this->miFormulario->campoBoton ( $atributos );}
                                        unset($atributos);    
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------
                                        //-------------Control Boton-----------------------
                                        $esteCampo = "botonCancelar";
                                        $atributos["verificar"]="true";
                                        $atributos["tipo"]="boton";
                                        $atributos["id"]="botonCancelar";
                                        $atributos["tipoSubmit"] = "";
                                        //$atributos ['columnas'] = 2;
                                        $atributos["tabIndex"]=$tab++;
                                        $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                        //echo $this->miFormulario->campoBoton($atributos);
                                        unset($atributos);
                                        //-------------Fin Control Boton---------------------- 
                                        
                                        // -----------------CONTROL: Botón ----------------------------------------------------------------
                                        $pestanna='#tabCalendario';
                                        $variable= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                        $variable.= "&opcion=detalle";
                                        $variable.= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
                                        
                                        $esteCampo = 'botonCancelar';
                                        $atributos ['id'] = $esteCampo;
                                        $atributos["tipo"]="boton";
                                        $atributos ['enlace'] = $variable.$pestanna;
                                        $atributos ['tabIndex'] = 1;
                                        //$atributos ['columnas'] = 2;
                                        $atributos ['estilo'] = 'jqueryui';
                                        $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                                        //$atributos ['ancho'] = '10%';
                                        //$atributos ['alto'] = '10%';
                                        $atributos ['redirLugar'] = true;
                                        echo $this->miFormulario->enlace ( $atributos );
                                        unset($atributos);

                                        // -----------------FIN CONTROL: Botón -----------------------------------------------------------
                                        
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
                                    $calendario=isset($resultadoCalendario[0]['consecutivo_calendario'])?$resultadoCalendario[0]['consecutivo_calendario']:0;    
                                    $valorCodificado = "action=" . $esteBloque ["nombre"];
                                    $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                    $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
                                    $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                                    $valorCodificado .= "&opcion=guardarCalendarioConcurso";
                                    $valorCodificado .= "&id_usuario=".$usuario;
                                    $valorCodificado .= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                                    $valorCodificado .= "&consecutivo_calendario=".$calendario;
                                    
                                    if (isset($resultadoCalendario[0]['obligatoria']) && $resultadoCalendario[0]['obligatoria']=='S' )
                                         { $valorCodificado .= "&consecutivo_actividad=".$resultadoCalendario[0]['consecutivo_actividad'];
                                           $valorCodificado .= "&porc_aprueba_fase=".$resultadoCalendario[0]['porcentaje_aprueba'];
                                         }

                                    
                                    /**
                                     * SARA permite que los nombres de los campos sean dinámicos.
                                     * Para ello utiliza la hora en que es creado el formulario para
                                     * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
                                     * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
                                     * (b) asociando el tiempo en que se está creando el formulario
                                     */
                                    $valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
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
				}
				echo $this->miFormulario->division ( 'fin' );
				// ---------------- FIN SECCION: Botones del Formulario -------------------------------------------
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
		}
                // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
                // Se debe declarar el mismo atributo de marco con que se inició el formulario.
                $atributos ['tipoEtiqueta'] = 'fin';
                echo $this->miFormulario->formulario ( $atributos );
                return true;
	}
}
$miSeleccionador = new calendarioForm ( $this->lenguaje, $this->miFormulario, $this->sql );
$miSeleccionador->miForm ();
?>
