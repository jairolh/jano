<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
use gestionConcursante\gestionHoja\funcion\redireccion;

class actividadForm {
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
                $miSesion = \Sesion::singleton();
                $usuario=$miSesion->idUsuario();
                //identifca el usuario
		$parametro['id_usuario']=$usuario;
                $cadena_sql = $this->miSql->getCadenaSql("consultarBasicos", $parametro);
                $resultadoUsuarios = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                if(isset($_REQUEST['consecutivo_actividad']))
                    {  $parametro['consecutivo_actividad']=$_REQUEST['consecutivo_actividad'];
                       $cadena_sql = $this->miSql->getCadenaSql("consultarActividad", $parametro);
                       $resultadoActividad = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                       $parametroExp = array('consecutivo'=>$resultadoUsuarios[0]['consecutivo'],
                                             'tipo_dato'=>'datosActividad',
                                             'nombre_soporte'=>'soporteActividad',
                                             'consecutivo_dato'=>$_REQUEST['consecutivo_actividad']);
                        $cadenaSopDoc_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroExp);
                        $resultadoSopDoc = $esteRecursoDB->ejecutarAcceso($cadenaSopDoc_sql, "busqueda");

                    }
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
                $estefomulario= 'datosActividad';
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
			$esteCampo = "marcoActividad";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
                        if(!isset($_REQUEST['consecutivo_actividad']))
                            { $atributos ["estiloEnLinea"] = "display:none;"; }
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{	      
                               // ---------------- CONTROL AGRUPACION: Cuadro Agrupacion --------------------------------------------------------
				$atributos ["id"] = "cuadro_actividad";
				$atributos ["estiloEnLinea"] = "display:block";
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'pais_actividad';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = ' ';
                                    if (isset ( $resultadoActividad[0]['pais_actividad'] ))
                                         {  $atributos ['seleccion'] = $resultadoActividad[0]['pais_actividad'];}
                                    else {	$atributos ['seleccion'] = 112;}
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 60;
                                    $atributos ['evento'] = '';
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
                                    $matrizItems = array (array (0,' '));
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'nivel_institucion_actividad';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = '';
                                    if (isset($resultadoActividad[0]['codigo_nivel_institucion']))
                                         {  $atributos ['seleccion'] = $resultadoActividad[0]['codigo_nivel_institucion'];}
                                    else {  $atributos ['seleccion'] = -1; }
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 60;
                                    $atributos ['evento'] = '';
                                    $parametronivel=array('tipo_nivel'=> 'Institucion');
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarNivel",$parametronivel );
                                    $matrizItems = array (array (0,' '));
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------

                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'codigo_institucion_actividad';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = ' ';
                                    if (isset ( $resultadoActividad[0]['codigo_institucion'] ))
                                         {  $atributos ['seleccion'] = $resultadoActividad[0]['codigo_institucion'];}
                                    else {	$atributos ['seleccion'] = 0;}
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 400;
                                    $atributos ['evento'] = '';
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarInstitucion" );
                                    $matrizItems = array (array (0,' '));
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'nombre_institucion_actividad';
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
                                    $atributos ['validar']="required,minSize[1]";
                                    if (isset ( $resultadoActividad[0]['nombre_institucion'] )) 
                                         {   $atributos ['valor'] = $resultadoActividad[0]['nombre_institucion']; 
                                             $atributos ['deshabilitado'] = true;                                         
                                         }
                                    else {   $atributos ['valor'] = '';
                                            $atributos ['deshabilitado'] = false;                                    
                                         }
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------  
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'correo_institucion_actividad';
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
                                    $atributos ['validar']="required, custom[email]";
                                    if (isset ( $resultadoActividad[0]['correo_institucion'] )) {
                                            $atributos ['valor'] = $resultadoActividad[0]['correo_institucion'];
                                    } else {
                                            $atributos ['valor'] = '';
                                    }
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = false;
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
                                    $esteCampo = 'telefono_institucion_actividad';
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
                                    $atributos ['validar']="required,minSize[7],custom[phone]";
                                    if (isset ( $resultadoActividad[0]['telefono_institucion'] )) 
                                         {  $atributos ['valor'] = $resultadoActividad[0]['telefono_institucion'];} 
                                    else {  $atributos ['valor'] = '';}
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------   
                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'codigo_tipo_actividad';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = '';
                                    if (isset($resultadoActividad[0]['codigo_tipo_actividad']))
                                         {  $atributos ['seleccion'] = $resultadoActividad[0]['codigo_tipo_actividad'];}
                                    else {  $atributos ['seleccion'] = 0; }
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 60;
                                    $atributos ['evento'] = '';
                                    $parametronivel=array('tipo_nivel'=> 'ActividadAcademica',
                                                          'add_otro'=> 'SI'  
                                                         );
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarNivel",$parametronivel );
                                    $matrizItems = array (array (0,' '));
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'nombre_tipo_actividad';
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
                                    $atributos ['validar']="required,minSize[1]";
                                    if (isset ( $resultadoActividad[0]['nombre_tipo_actividad'] )) 
                                         {   $atributos ['valor'] = $resultadoActividad[0]['nombre_tipo_actividad']; 
                                            $atributos ['deshabilitado'] = true;                                         
                                         }
                                    else {   $atributos ['valor'] = '';
                                            $atributos ['deshabilitado'] = false;                                    
                                         }
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                      
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'nombre_actividad';
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
                                    $atributos ['validar']="required,minSize[1]";
                                    if (isset ( $resultadoActividad[0]['nombre_actividad'] )) 
                                         {   $atributos ['valor'] = $resultadoActividad[0]['nombre_actividad']; 
                                         }
                                    else {   $atributos ['valor'] = '';
                                         }
                                    $atributos ['deshabilitado'] = false;                                         
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 190;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                      

                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'descripcion_actividad';
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
                                    $atributos ['validar'] = 'required, minSize[10], maxSize[2000]';
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    if (isset ( $resultadoActividad[0]['descripcion'] )) {
                                            $atributos ['valor'] = $resultadoActividad[0]['descripcion'];
                                    } else {
                                            $atributos ['valor'] = '';
                                    }
                                    $tab ++;

                                    // Aplica atributos globales al control
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoTextArea ( $atributos );
                                    unset ( $atributos );                                                   
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------                                                                      
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'jefe_actividad';
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
                                    $atributos ['validar']="required,minSize[1]";
                                    if (isset ( $resultadoActividad[0]['jefe_actividad'] )) 
                                         {   $atributos ['valor'] = $resultadoActividad[0]['jefe_actividad']; }
                                    else {   $atributos ['valor'] = ''; }
                                    $atributos ['deshabilitado'] = false;                                         
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                    
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'fecha_inicio_actividad';
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
                                    $atributos ['validar']="required, custom[date]";
                                    if (isset ( $resultadoActividad[0]['fecha_inicio'] )) 
                                        {   $atributos ['valor'] = $resultadoActividad[0]['fecha_inicio'];}
                                    else {  $atributos ['valor'] = '';}
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'fecha_fin_actividad';
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
                                    $atributos ['validar']="required,custom[date]";
                                    if (isset ( $resultadoActividad[0]['fecha_fin'] )) 
                                        {   $atributos ['valor'] = $resultadoActividad[0]['fecha_fin'];}
                                    else {  $atributos ['valor'] = '';}
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = true;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de division --------------------------------------------------------
                                        $atributos ["id"]="Actividad";
                                        $atributos ["estiloEnLinea"] = "border-width: 0";//display:block";
                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                        echo $this->miFormulario->division ( "inicio", $atributos );
                                        unset ( $atributos );
                                                {
                                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                    $esteCampo = 'soporteActividad';
                                                    $atributos ['id'] = $esteCampo;
                                                    $atributos ['nombre'] = $esteCampo;
                                                    $atributos ['tipo'] = 'file';
                                                    $atributos ['estilo'] = 'jqueryui';
                                                    $atributos ['marco'] = true;
                                                    if(isset($resultadoSopDoc[0]['archivo']))
                                                        {  $atributos ['columnas'] = 2;}
                                                    else{  $atributos ['columnas'] = 1;}
                                                    $atributos ['dobleLinea'] = false;
                                                    $atributos ['tabIndex'] = $tab;
                                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                                    $atributos ['obligatorio'] = false;
                                                    $atributos ['etiquetaObligatorio'] = false;
                                                    $atributos ['validar'] = 'minSize[1]';
                                                    if (isset ( $_REQUEST [$esteCampo] )) 
                                                         { $atributos ['valor'] = $_REQUEST [$esteCampo];}
                                                    else {  $atributos ['valor'] = '';}
                                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                    $atributos ['deshabilitado'] = FALSE;
                                                    $atributos ['tamanno'] = 30;
                                                    $atributos ['anchoCaja'] = 60;
                                                    $atributos ['maximoTamanno'] = '';
                                                    $atributos ['anchoEtiqueta'] = 170;
                                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                  if(isset($resultadoSopDoc[0]['archivo']))
                                                        {
                                                           // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                          $esteCampo = 'archivoActividad';
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:soporte("ruta_actividad");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['marco'] = true;
                                                          $atributos ['columnas'] = 2;
                                                          $atributos ['enlaceTexto'] = $resultadoSopDoc[0]['alias'];
                                                          $atributos ['estilo'] = 'textoGrande textoGris ';
                                                          $atributos ['enlaceImagen'] = $rutaBloque."/images/pdfImage.png";
                                                          $atributos ['posicionImagen'] ="atras";//"adelante";
                                                          $atributos ['ancho'] = '50px';
                                                          $atributos ['alto'] = '50px';
                                                          $atributos ['redirLugar'] = false;
                                                          $atributos ['valor'] = '';
                                                          $atributos = array_merge ( $atributos, $atributosGlobales );
                                                          echo $this->miFormulario->enlace( $atributos );
                                                          unset ( $atributos );
                                                         // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                          $esteCampo = 'ruta_actividad';
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
                                                          $atributos ['valor'] = $this->rutaSoporte.$resultadoSopDoc[0]['ubicacion']."/".$resultadoSopDoc[0]['archivo'];
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
                                        echo $this->miFormulario->division( 'fin' );
                                        unset ( $atributos );
                                        // --------------- FIN CONTROL : Cuadro de Soporte Diploma --------------------------------------------------
                                }
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );
				// ---------------- CONTROL: Fin Cuadro Agrupacion --------------------------------------------------------
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonActividad';
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
					$atributos ['nombreFormulario'] = $estefomulario;//$esteBloque ['nombre'];
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoBoton ( $atributos );
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------
                                        //-------------Control Boton-----------------------
                                        $esteCampo = "botonCancelar";
                                        $atributos["verificar"]="true";
                                        $atributos["tipo"]="boton";
                                        $atributos["id"]="botonCancelar";
                                        $atributos["tipoSubmit"] = "";
                                        $atributos["tabIndex"]=$tab++;
                                        $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                        echo $this->miFormulario->campoBoton($atributos);
                                        //-------------Fin Control Boton---------------------- 
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
                                    $actividad=isset($resultadoActividad[0]['consecutivo_actividad'])?$resultadoActividad[0]['consecutivo_actividad']:0;    
                                    $valorCodificado = "action=" . $esteBloque ["nombre"];
                                    $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                    $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
                                    $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                                    $valorCodificado .= "&opcion=guardarDatosActividad";
                                    $valorCodificado .= "&id_usuario=".$usuario;
                                    $valorCodificado .= "&consecutivo_actividad=".$actividad;
                                    $valorCodificado .= "&consecutivo_persona=".$resultadoUsuarios[0]['consecutivo'];
                                    $valorCodificado .= "&nombre=".$resultadoUsuarios[0]['nombre'];
                                    $valorCodificado .= "&apellido=".$resultadoUsuarios[0]['apellido'];
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
$miSeleccionador = new actividadForm ( $this->lenguaje, $this->miFormulario, $this->sql );
$miSeleccionador->miForm ();
?>
