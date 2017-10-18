<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
use gestionConcursante\gestionHoja\funcion\redireccion;

class formacionForm {
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
                if(isset($_REQUEST['consecutivo_formacion']))
                    {  $parametro['consecutivo_formacion']=$_REQUEST['consecutivo_formacion'];
                       $cadena_sql = $this->miSql->getCadenaSql("consultarFormacion", $parametro);
                       $resultadoFormacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                        $parametroSop = array('consecutivo'=>$resultadoUsuarios[0]['consecutivo'],
                                             'tipo_dato'=>'datosFormacion',
                                             'nombre_soporte'=>'soporteDiploma',
                                             'consecutivo_dato'=>$_REQUEST['consecutivo_formacion']);
                        $cadenaSopDip_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                        $resultadoSopDip = $esteRecursoDB->ejecutarAcceso($cadenaSopDip_sql, "busqueda");
                        $parametroSop['nombre_soporte']='soporteTprofesional';
                        $cadenaSopTP_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                        $resultadoSopTP = $esteRecursoDB->ejecutarAcceso($cadenaSopTP_sql, "busqueda");
                    }
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
                $estefomulario= 'datosFormacion';
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
			$esteCampo = "marcoFormacion";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
                        if(!isset($_REQUEST['consecutivo_formacion']))
                            { $atributos ["estiloEnLinea"] = "display:none;"; }
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{   $atributos ['id'] = 'datos';
                            $atributos ["estilo"] = "jqueryui";
                            $atributos ['tipoEtiqueta'] = 'inicio';
                            $atributos ["leyenda"] = '';
                            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
                            unset ( $atributos );
                            {	      
                               // ---------------- CONTROL AGRUPACION: Cuadro Agrupacion --------------------------------------------------------
				$atributos ["id"] = "cuadro_formacion";
				$atributos ["estiloEnLinea"] = "display:block";
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'modalidad';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = '';
                                    if (isset ( $resultadoFormacion[0]['codigo_modalidad'] ))
                                         {  $atributos ['seleccion'] = $resultadoFormacion[0]['codigo_modalidad'];}
                                    else {  $atributos ['seleccion'] =  1;}
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 60;
                                    $atributos ['evento'] = '';
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarModalidad" );
                                    $matrizItems = array (array (0,' '));
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------                                
                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'nivel_formacion';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = '';
                                    if (isset ( $resultadoFormacion[0]['codigo_nivel'] ))
                                         {  $atributos ['seleccion'] = $resultadoFormacion[0]['codigo_nivel'];}
                                    else {  $atributos ['seleccion'] = -1; }
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 60;
                                    $atributos ['evento'] = '';
                                    $parametronivel=array('tipo_nivel'=> 'Formacion');
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarNivel",$parametronivel );
                                    $matrizItems = array (array (0,' '));
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'pais_formacion';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = ' ';
                                    if (isset ( $resultadoFormacion[0]['pais_formacion'] ))
                                         {  $atributos ['seleccion'] = $resultadoFormacion[0]['pais_formacion'];}
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
                                    $esteCampo = 'codigo_institucion';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = ' ';
                                    if (isset ( $resultadoFormacion[0]['codigo_institucion'] ))
                                         {  $atributos ['seleccion'] = $resultadoFormacion[0]['codigo_institucion'];}
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
                                    $esteCampo = 'nombre_institucion';
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
                                    if (isset ( $resultadoFormacion[0]['nombre_institucion'] )) {
                                            $atributos ['valor'] = $resultadoFormacion[0]['nombre_institucion'];
                                            $atributos ['deshabilitado'] = true;
                                    } else {
                                            $atributos ['valor'] = '';
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
                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'consecutivo_programa';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = ' ';
                                    if (isset ( $resultadoFormacion[0]['codigo_programa'] ))
                                         {  $atributos ['seleccion'] = $resultadoFormacion[0]['codigo_programa'];
                                            $atributos ['deshabilitado'] = false;
                                         }
                                    else {  $atributos ['seleccion'] = 1;
                                            $atributos ['deshabilitado'] = true;//false;
                                         }
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 400;
                                    $atributos ['evento'] = '';
                                     if (isset ( $resultadoFormacion[0]['codigo_institucion'] ))
                                         {  $parametro['codigo_ies']= $resultadoFormacion[0]['codigo_institucion'];}
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarPrograma",$parametro );
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    // Aplica atributos globales al control
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'nombre_programa';
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
                                    if (isset ( $resultadoFormacion[0]['nombre_programa'] )) {
                                            $atributos ['valor'] = $resultadoFormacion[0]['nombre_programa']; }
                                    else {  $atributos ['valor'] = '';}
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    if (isset ( $resultadoFormacion[0]['codigo_programa'] ) &&  $resultadoFormacion[0]['codigo_programa']<>0)
                                         {  $atributos ['deshabilitado'] = true;}
                                    else {  $atributos ['deshabilitado'] = false;}
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                      
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'cursos_aprobados';
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
                                    $atributos ['validar']="required,minSize[1],custom[number]";
                                    if (isset ( $resultadoFormacion[0]['cursos_aprobados'] )) 
                                         {  $atributos ['valor'] = $resultadoFormacion[0]['cursos_aprobados'];} 
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
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'promedio';
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['tipo'] = 'text';
                                    $atributos ['estilo'] = 'jqueryui';
                                    $atributos ['marco'] = true;
                                    $atributos ['estiloMarco'] = '';
                                    $atributos ["etiquetaObligatorio"] = false;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['dobleLinea'] = 0;
                                    $atributos ['tabIndex'] = $tab;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ['validar']="minSize[1],maxSize[4],custom[number],min[0],max[5]";
                                    if (isset ( $resultadoFormacion[0]['promedio'] )) 
                                         {  $atributos ['valor'] = $resultadoFormacion[0]['promedio'];} 
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
                                    $esteCampo = 'graduado';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = ' ';
                                    if (isset ( $resultadoFormacion[0]['graduado'] ))
                                         {  $atributos ['seleccion'] = $resultadoFormacion[0]['graduado'];}
                                    else {	$atributos ['seleccion'] = -1;}
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 60;
                                    $atributos ['evento'] = '';
                                    $matrizItems = array (array ('N','NO'),
                                                          array ('S','SI'));
                                    $atributos ['matrizItems'] = $matrizItems;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------                                    
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'fecha_grado';
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['tipo'] = 'texto';
                                    $atributos ['estilo'] = 'jqueryui';
                                    $atributos ['marco'] = true;
                                    $atributos ['estiloMarco'] = '';
                                    $atributos ["etiquetaObligatorio"] = false;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['dobleLinea'] = 0;
                                    $atributos ['tabIndex'] = $tab;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ['validar']="custom[date]";
                                    if (isset ( $resultadoFormacion[0]['fecha_grado'] )) 
                                        {   $atributos ['valor'] = $resultadoFormacion[0]['fecha_grado'];}
                                    else {  $atributos ['valor'] = '';}
                                    $atributos ['titulo'] =  '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = true;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de division --------------------------------------------------------
                                        $atributos ["id"]="diploma";
                                        $atributos ["estiloEnLinea"] = "border-width: 0";//display:block";
                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                        echo $this->miFormulario->division ( "inicio", $atributos );
                                        unset ( $atributos );
                                                {
                                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                    $esteCampo = 'soporteDiploma';
                                                    $atributos ['id'] = $esteCampo;
                                                    $atributos ['nombre'] = $esteCampo;
                                                    $atributos ['tipo'] = 'file';
                                                    $atributos ['estilo'] = 'jqueryui';
                                                    $atributos ['marco'] = true;
                                                    $atributos ['dobleLinea'] = false;
                                                    $atributos ['tabIndex'] = $tab;
                                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                                    $atributos ['etiquetaObligatorio'] = false;
                                                    $atributos ['tamanno'] = 1024;
                                                    $atributos ['evento'] = 'accept="pdf"';
                                                    if(isset($resultadoSopDip[0]['archivo']))
                                                        {  $atributos ['columnas'] = 2;
                                                           $atributos ['validar'] = ''; 
                                                        }
                                                    else{  $atributos ['columnas'] = 1;
                                                           $atributos ['validar'] = 'required,minSize[1]'; 
                                                        }
                                                    if (isset ( $_REQUEST [$esteCampo] )) 
                                                         { $atributos ['valor'] = $_REQUEST [$esteCampo];}
                                                    else {  $atributos ['valor'] = '';}
                                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                    $atributos ['deshabilitado'] = FALSE;
                                                    $atributos ['anchoCaja'] = 60;
                                                    $atributos ['maximoTamanno'] = '';
                                                    $atributos ['anchoEtiqueta'] = 170;
                                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                  if(isset($resultadoSopDip[0]['archivo']))
                                                        {
                                                           // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                          $esteCampo = 'archivoDiploma';
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:soporte("ruta_diploma");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['marco'] = true;
                                                          $atributos ['columnas'] = 2;
                                                          $atributos ['enlaceTexto'] = $resultadoSopDip[0]['alias'];
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
                                                          $esteCampo = 'ruta_diploma';
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
                                                          $atributos ['valor'] = $this->rutaSoporte.$resultadoSopDip[0]['ubicacion']."/".$resultadoSopDip[0]['archivo'];
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
                                        // ---------------- CONTROL: Cuadro de division --------------------------------------------------------
                                        $atributos ["id"]="Tprofesional";
                                        $atributos ["estiloEnLinea"] = "border-width: 0";//display:block";
                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                        echo $this->miFormulario->division ( "inicio", $atributos );
                                        unset ( $atributos );
                                                {
                                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                    $esteCampo = 'soporteTprofesional';
                                                    $atributos ['id'] = $esteCampo;
                                                    $atributos ['nombre'] = $esteCampo;
                                                    $atributos ['tipo'] = 'file';
                                                    $atributos ['estilo'] = 'jqueryui';
                                                    $atributos ['marco'] = true;
                                                    if(isset($resultadoSopTP[0]['archivo']))
                                                        {  $atributos ['columnas'] = 2;}
                                                    else{  $atributos ['columnas'] = 1;}
                                                    $atributos ['dobleLinea'] = false;
                                                    $atributos ['tabIndex'] = $tab;
                                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                                    $atributos ['obligatorio'] = false;
                                                    $atributos ['etiquetaObligatorio'] = false;
                                                    $atributos ['validar'] = '';
                                                    $atributos ['tamanno'] = 1024;
                                                    $atributos ['evento'] = 'accept="pdf"';
                                                    if (isset ( $_REQUEST [$esteCampo] )) 
                                                         {    $atributos ['valor'] = $_REQUEST [$esteCampo];} 
                                                    else {  $atributos ['valor'] = '';}
                                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                    $atributos ['deshabilitado'] = FALSE;
                                                    $atributos ['anchoCaja'] = 60;
                                                    $atributos ['maximoTamanno'] = '';
                                                    $atributos ['anchoEtiqueta'] = 170;
                                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                                   // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                  if(isset($resultadoSopTP[0]['archivo']))
                                                        {//echo $this->campoSeguro('rutaTprofesional');
                                                           // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                          $esteCampo = 'archivoTprofesional';
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:soporte("ruta_tprofesional");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['marco'] = true;
                                                          $atributos ['columnas'] = 2;
                                                          $atributos ['enlaceTexto'] = $resultadoSopTP[0]['alias'];
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
                                                          $esteCampo = 'ruta_tprofesional';
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
                                                          $atributos ['valor'] = $this->rutaSoporte.$resultadoSopTP[0]['ubicacion']."/".$resultadoSopTP[0]['archivo'];
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
                                        // --------------- FIN CONTROL : Cuadro de Soporte tarjeta profesional --------------------------------------------------                                    
				}
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );
				// ---------------- CONTROL: Fin Cuadro Agrupacion --------------------------------------------------------
                            }
                            echo $this->miFormulario->marcoAgrupacion ( 'fin' );
                            // -----------------FIN CONTROL: Botón -----------------------------------------------------------
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonFormacion';
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
                                    $formacion=isset($resultadoFormacion[0]['consecutivo_formacion'])?$resultadoFormacion[0]['consecutivo_formacion']:0;    
                                    $valorCodificado = "action=" . $esteBloque ["nombre"];
                                    $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                    $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
                                    $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                                    $valorCodificado .= "&opcion=guardarDatosFormacion";
                                    $valorCodificado .= "&id_usuario=".$usuario;
                                    $valorCodificado .= "&consecutivo_formacion=".$formacion;
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
			// -----------------FIN CONTROL: Agupacion general -----------------------------------------------------------
		}
                // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
                // Se debe declarar el mismo atributo de marco con que se inició el formulario.
                $atributos ['tipoEtiqueta'] = 'fin';
                echo $this->miFormulario->formulario ( $atributos );
                return true;
	}
}
$miSeleccionador = new formacionForm ( $this->lenguaje, $this->miFormulario, $this->sql );
$miSeleccionador->miForm ();
?>
