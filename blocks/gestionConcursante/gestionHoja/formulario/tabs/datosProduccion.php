<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
use gestionConcursante\gestionHoja\funcion\redireccion;

class produccionForm {
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
                $this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "raizSoportes" );                
	}
	function miForm() {
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
                $rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/";
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
                if(isset($_REQUEST['consecutivo_produccion']))
                    {  $parametro['consecutivo_produccion']=$_REQUEST['consecutivo_produccion'];
                       $cadena_sql = $this->miSql->getCadenaSql("consultarProduccion", $parametro);
                       $resultadoProduccion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    }
                //-----BUSCA LOS TIPOS DE SOPORTES PARA EL FORMUALRIO, SEGÚN LOS RELACIONADO EN LA TABLA
                $parametroTipoSop = array('dato_relaciona'=>'datosProduccion',);
                $cadenaSalud_sql = $this->miSql->getCadenaSql("buscarTipoSoporte", $parametroTipoSop);
                $resultadoTiposop = $esteRecursoDB->ejecutarAcceso($cadenaSalud_sql, "busqueda");
                // ---------------- SECCION: Enlace para soporte -----------------------------------------------
                $variableSoporte = "pagina=gestionarSoportes"; //pendiente la pagina para modificar parametro                                                        
                $variableSoporte.= "&action=gestionarSoportes";
                $variableSoporte.= "&bloque=" . $esteBloque["id_bloque"];
                $variableSoporte.= "&bloqueGrupo=";            
                //----

		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
                $estefomulario= 'datosProduccion';
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
			$esteCampo = "marcoProduccion";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
                        if(!isset($_REQUEST['consecutivo_produccion']))
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

                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'pais_produccion';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = ' ';
                                    if (isset ( $resultadoProduccion[0]['pais_produccion'] ))
                                         {  $atributos ['seleccion'] = $resultadoProduccion[0]['pais_produccion'];}
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
                                    $esteCampo = 'departamento_produccion';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    //$atributos ['seleccion'] = - 1;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = '';
                                    if (isset ( $resultadoProduccion[0]['departamento_produccion'] ))
                                         {  $atributos ['seleccion'] = $resultadoProduccion[0]['departamento_produccion'];
                                            $parametro['pais']=  $resultadoProduccion[0]['pais_produccion'];
                                         }
                                    else {	$atributos ['seleccion'] = - 1;}
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 60;
                                    $atributos ['evento'] = '';
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento",$parametro );
                                    $matrizItems = array (array (0,' '));
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    // Aplica atributos globales al control
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------                                
                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'ciudad_produccion';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    //$atributos ['seleccion'] = - 1;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = '';
                                    if (isset ( $resultadoProduccion[0]['ciudad_produccion'] ))
                                         {  $atributos ['seleccion'] =$resultadoProduccion[0]['ciudad_produccion'];
                                            $parametro['departamento']= $resultadoProduccion[0]['departamento_produccion'];
                                            $atributos ['deshabilitado'] = false;
                                         }
                                    else {  $atributos ['seleccion'] = - 1;
                                            $atributos ['deshabilitado'] = true;
                                        }
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 60;
                                    $atributos ['evento'] = '';
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad",$parametro );
                                    $matrizItems = array (array (0,' '));
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    // Aplica atributos globales al control
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------                                    
                                    // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                    $esteCampo = 'codigo_tipo_produccion';
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ["etiquetaObligatorio"] = true;
                                    $atributos ['tab'] = $tab ++;
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos ['evento'] = ' ';
                                    if (isset ( $resultadoProduccion[0]['codigo_tipo_produccion'] ))
                                         {  $atributos ['seleccion'] = $resultadoProduccion[0]['codigo_tipo_produccion'];}
                                    else {	$atributos ['seleccion'] = 0;}
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['columnas'] = 1;
                                    $atributos ['tamanno'] = 1;
                                    $atributos ['estilo'] = "jqueryui";
                                    $atributos ['validar'] = "required";
                                    $atributos ['limitar'] = true;
                                    $atributos ['anchoCaja'] = 400;
                                    $atributos ['evento'] = '';
                                    $parametronivel=array('tipo_nivel'=> 'Produccion',
                                                          'add_otro'=>'SI'  );
                                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarNivel",$parametronivel );
                                    $matrizItems = array (array (0,' '));
                                    $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                    $atributos ['matrizItems'] = $matrizItems;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroLista ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'nombre_tipo_produccion';
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
                                    $atributos ['validar']="required,minSize[1],maxSize[100]";
                                    if (isset ( $resultadoProduccion[0]['nombre_tipo_produccion'] )) 
                                         {   $atributos ['valor'] = $resultadoProduccion[0]['nombre_tipo_produccion']; 
                                             $atributos ['deshabilitado'] = false;                                         
                                         }
                                    else {   $atributos ['valor'] = '';
                                            $atributos ['deshabilitado'] = false;                                    
                                         }
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------  
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'titulo_produccion';
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
                                    $atributos ['validar']="required,minSize[1],maxSize[150]";
                                    if (isset ( $resultadoProduccion[0]['titulo_produccion'] )) 
                                         {   $atributos ['valor'] = $resultadoProduccion[0]['titulo_produccion']; 
                                         }
                                    else {   $atributos ['valor'] = '';
                                         }
                                    $atributos ['deshabilitado'] = false;                                                                             
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------  
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'nombre_autor';
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
                                    $atributos ['validar']="required,minSize[1],maxSize[100],custom[onlyLetterSp]";
                                    if (isset ( $resultadoProduccion[0]['nombre_autor'] )) 
                                         {  $atributos ['valor'] = $resultadoProduccion[0]['nombre_autor'];} 
                                    else {  $atributos ['valor'] = '';}
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------   
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'fecha_produccion';
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
                                    if (isset ( $resultadoProduccion[0]['fecha_produccion'] )) 
                                        {   $atributos ['valor'] = $resultadoProduccion[0]['fecha_produccion'];}
                                    else {  $atributos ['valor'] = '';}
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                    
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'nombre_producto_incluye';
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
                                    $atributos ['validar']="minSize[1],maxSize[150]";
                                    if (isset ( $resultadoProduccion[0]['nombre_producto_incluye'] )) 
                                         {   $atributos ['valor'] = $resultadoProduccion[0]['nombre_producto_incluye'];}
                                    else {   $atributos ['valor'] = ''; }
                                    $atributos ['deshabilitado'] = false;                                         
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                      
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'nombre_editorial';
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
                                    $atributos ['validar']="minSize[1],maxSize[100]";
                                    if (isset ( $resultadoProduccion[0]['nombre_editorial'] )) 
                                         {   $atributos ['valor'] = $resultadoProduccion[0]['nombre_editorial']; }
                                    else {   $atributos ['valor'] = ''; }
                                    $atributos ['deshabilitado'] = false;                                         
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------  
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'volumen';
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
                                    $atributos ['validar']="minSize[1],maxSize[50]";
                                    if (isset ( $resultadoProduccion[0]['volumen'] )) 
                                         {   $atributos ['valor'] = $resultadoProduccion[0]['volumen']; }
                                    else {   $atributos ['valor'] = ''; }
                                    $atributos ['deshabilitado'] = false;                                         
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'pagina_producto';
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
                                    $atributos ['validar']="minSize[1],maxSize[50]";
                                    if (isset ( $resultadoProduccion[0]['pagina'] )) 
                                         {   $atributos ['valor'] = $resultadoProduccion[0]['pagina']; }
                                    else {   $atributos ['valor'] = ''; }
                                    $atributos ['deshabilitado'] = false;                                         
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                                                           
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'codigo_isbn';
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
                                    $atributos ['validar']="minSize[1],maxSize[50]";
                                    if (isset ( $resultadoProduccion[0]['codigo_isbn'] )) 
                                         {   $atributos ['valor'] = $resultadoProduccion[0]['codigo_isbn']; }
                                    else {   $atributos ['valor'] = ''; }
                                    $atributos ['deshabilitado'] = false;                                         
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                                                           
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'codigo_issn';
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
                                    $atributos ['validar']="minSize[1],maxSize[50]";
                                    if (isset ( $resultadoProduccion[0]['codigo_issn'] )) 
                                         {   $atributos ['valor'] = $resultadoProduccion[0]['codigo_issn']; }
                                    else {   $atributos ['valor'] = ''; }
                                    $atributos ['deshabilitado'] = false;                                         
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'indexado';
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
                                    $atributos ['validar']="minSize[1],maxSize[255]";
                                    if (isset ( $resultadoProduccion[0]['indexado'] )) 
                                         {   $atributos ['valor'] = $resultadoProduccion[0]['indexado']; }
                                    else {   $atributos ['valor'] = ''; }
                                    $atributos ['deshabilitado'] = false;                                         
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                 
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'descripcion_produccion';
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['tipo'] = 'text';
                                    $atributos ['estilo'] = 'jqueryui';
                                    $atributos ['marco'] = true;
                                    $atributos ['estiloMarco'] = '';
                                    $atributos ["etiquetaObligatorio"] = false;
                                    $atributos ['columnas'] = 74;
                                    $atributos ['filas'] = 4;
                                    $atributos ['dobleLinea'] = 0;
                                    $atributos ['tabIndex'] = $tab;
                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ['validar'] = 'required, minSize[10], maxSize[2000]';
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = false;
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    if (isset ( $resultadoProduccion[0]['descripcion'] )) {
                                            $atributos ['valor'] = $resultadoProduccion[0]['descripcion'];
                                    } else {
                                            $atributos ['valor'] = '';
                                    }
                                    $tab ++;

                                    // Aplica atributos globales al control
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoTextArea ( $atributos );
                                    unset ( $atributos );                                                   
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------  

                            }
                            echo $this->miFormulario->agrupacion ( 'fin' );
                            unset ( $atributos );                                    
                            
                            // ---------------- CONTROL AGRUPACION: Cuadro Agrupacion --------------------------------------------------------
                            $esteCampo = "cuadroSoportesBasicos";
                            $atributos ['id'] = $esteCampo;
                            $atributos ['leyenda'] = $this->lenguaje->getCadena ( $esteCampo );
                            $atributos ['estilo'] = 'jqueryui';
                            echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
                            {
                                    
                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                    $esteCampo = 'direccion_produccion';
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
                                    $atributos ['validar']="custom[url]";
                                    if (isset ( $resultadoProduccion[0]['direccion_produccion'] )) 
                                         {   $atributos ['valor'] = str_replace('\\','', $resultadoProduccion[0]['direccion_produccion']);}
                                    else {   $atributos ['valor'] = ''; }
                                    $atributos ['deshabilitado'] = false;                                         
                                    $atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['tamanno'] = 60;
                                    $atributos ['maximoTamanno'] = '';
                                    $atributos ['anchoEtiqueta'] = 170;
                                    $tab ++;
                                    $atributos = array_merge ( $atributos, $atributosGlobales );
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );
                                    // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                       
                                    // ---------------- CONTROL: Cuadro de division --------------------------------------------------------
                                        $atributos ["id"]="Produccion";
                                        $atributos ["estiloEnLinea"] = "border-width: 0";//display:block";
                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                        echo $this->miFormulario->division ( "inicio", $atributos );
                                        unset ( $atributos );
                                                {
                                                // --------------- INICIO CONTROLES : CARGA SOPORTES SEGUN LOS RELACIONADOS --------------------------------------------------
                                                foreach ($resultadoTiposop as $tipokey => $value) 
                                                    {
                                                    //valida si existen soportes para el tipo
                                                    if(isset($_REQUEST['consecutivo_produccion']) && isset($resultadoUsuarios[0]['consecutivo']) && $resultadoUsuarios[0]['consecutivo']>0)
                                                        {  
                                                            $parametroSop = array('consecutivo_persona'=>trim($resultadoUsuarios[0]['consecutivo']),
                                                                 'tipo_dato'=>$resultadoTiposop[$tipokey]['dato_relaciona'],
                                                                 'nombre_soporte'=>$resultadoTiposop[$tipokey]['nombre'],
                                                                 'consecutivo_dato'=>$_REQUEST['consecutivo_produccion']);

                                                            $cadenaSop_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                                            $resultadoSoporte = $esteRecursoDB->ejecutarAcceso($cadenaSop_sql , "busqueda");
                                                       }
                                                    // ---------------- INICIO CONTROL: Cuadro de division Soporte --------------------------------------------------------
                                                     $atributos ["id"]=$resultadoTiposop[$tipokey]['tipo_soporte'];
                                                     $atributos ["estiloEnLinea"] = "border-width: 0";//display:block";
                                                     $atributos = array_merge ( $atributos, $atributosGlobales );
                                                     echo $this->miFormulario->division ( "inicio", $atributos );
                                                     unset ( $atributos );
                                                             {
                                                                // ---------------- CONTROL: Cuadro de Texto -----imprime caja para carga de archivo----------------------------
                                                                $esteCampo = $resultadoTiposop[$tipokey]['nombre'];
                                                                $atributos ['id'] = $esteCampo;
                                                                $atributos ['nombre'] = $esteCampo;
                                                                $atributos ['tipo'] = 'file';
                                                                $atributos ['estilo'] = 'jqueryui';
                                                                $atributos ['marco'] = true;
                                                                $atributos ['dobleLinea'] = false;
                                                                $atributos ['tabIndex'] = $tab;
                                                                $archivo = "formato ".$resultadoTiposop[$tipokey]['extencion_permitida']." y máximo ".number_format(($resultadoTiposop[$tipokey]['tamanno_permitido']/1024),2,",",".")." Mb";//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                                $atributos ['etiqueta'] = "<p align='left'>".$resultadoTiposop[$tipokey]['alias']." </p>";// $this->lenguaje->getCadena ( $esteCampo );
                                                                $atributos ['etiqueta'].= "<p> <font face='Verdana, Arial, Helvetica, sans-serif' size='1.2' color='#FF0000'   style='text-align:left' >".ucfirst($archivo)."</font></p>  ";  
                                                                $atributos ['titulo'] = "Para actualizar, adjuntar archivo en ".$archivo;//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                                 if(isset($resultadoTiposop[$tipokey]['validacion']) && strstr($resultadoTiposop[$tipokey]['validacion'], 'required'))
                                                                     {  $atributos ['etiquetaObligatorio'] = true;
                                                                     }
                                                                 else{  $atributos ['etiquetaObligatorio'] = false;
                                                                     }
                                                                 $atributos ['tamanno'] = $resultadoTiposop[$tipokey]['tamanno_permitido'];
                                                                 $atributos ['evento'] = 'accept="'.$resultadoTiposop[$tipokey]['extencion_permitida'].'"';
                                                                 //si existe soporte van 2 columnas
                                                                 if(isset($resultadoSoporte[0]['archivo']))
                                                                     {  $atributos ['columnas'] = 2;
                                                                        $atributos ['validar'] = ''; 
                                                                     }
                                                                 else{  $atributos ['columnas'] = 1;
                                                                        $atributos ['validar'] = $resultadoTiposop[$tipokey]['validacion']; 
                                                                     }
                                                                 if (isset ( $_REQUEST [$esteCampo] )) 
                                                                      { $atributos ['valor'] = $_REQUEST [$esteCampo];}
                                                                 else {  $atributos ['valor'] = '';}
                                                                 //$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                                 $atributos ['deshabilitado'] = FALSE;
                                                                 $atributos ['anchoCaja'] = 60;
                                                                 $atributos ['maximoTamanno'] = '';
                                                                 $atributos ['anchoEtiqueta'] = 200;
                                                                 $atributos = array_merge ( $atributos, $atributosGlobales );
                                                                 echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                                                 // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                               if(isset($resultadoSoporte[0]['archivo']))
                                                                     {  //verifica si el soporte es imagen para mostrarlo
                                                                        $arrayFile = explode(",",strtolower( $resultadoTiposop[$tipokey]['extencion_permitida']));
                                                                        if(isset($resultadoSoporte[0]['archivo']) && 
                                                                            (in_array(strtolower("png"), $arrayFile) || 
                                                                             in_array(strtolower("jpg"), $arrayFile) ||
                                                                             in_array(strtolower("jpeg"), $arrayFile) ||
                                                                             in_array(strtolower("bmp"), $arrayFile)))
                                                                               { //Se codifica la imagen
                                                                                  $rutaImagen= "file://".$this->rutaSoporte.$resultadoSoporte[0]['ubicacion']."/".$resultadoSoporte[0]['archivo'];
                                                                                  $imagen = file_get_contents ( $rutaImagen );
                                                                                  $imagenEncriptada = base64_encode ( $imagen );
                                                                                  $url_foto_perfil= "data:image;base64," . $imagenEncriptada;

                                                                                   // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                                                  $esteCampo = 'archivoFoto';
                                                                                  $atributos ['id'] = $esteCampo;
                                                                                  $atributos['imagen']= $url_foto_perfil;
                                                                                  $atributos['estilo']='campoImagen anchoColumna2';
                                                                                  $atributos['etiqueta']='fotografia';
                                                                                  $atributos['borde']='';
                                                                                  $atributos ['ancho'] = '100px';
                                                                                  $atributos ['alto'] = '120px';
                                                                                  $atributos = array_merge ( $atributos, $atributosGlobales );
                                                                                  echo $this->miFormulario->campoImagen( $atributos );
                                                                                  unset ( $atributos );
                                                                                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------  
                                                                              }
                                                                        else {      
                                                                                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                                                   $esteCampo = 'archivo'.$resultadoSoporte[0]['consecutivo_soporte'];
                                                                                   $atributos ['id'] = $esteCampo;
                                                                                   $atributos ['enlace'] = 'javascript:enlaceSop("ruta'.$resultadoSoporte[0]['consecutivo_soporte'].'");';
                                                                                   $atributos ['tabIndex'] = 0;
                                                                                   $atributos ['marco'] = true;
                                                                                   $atributos ['columnas'] = 2;
                                                                                   $atributos ['enlaceTexto'] = $resultadoSoporte[0]['alias'];
                                                                                   $atributos ['estilo'] = 'textoGrande textoGris ';
                                                                                   $atributos ['enlaceImagen'] = $rutaBloque."/images/pdfImage.png";
                                                                                   $atributos ['posicionImagen'] ="atras";//"adelante";
                                                                                   $atributos ['ancho'] = '35px';
                                                                                   $atributos ['alto'] = '35px';
                                                                                   $atributos ['redirLugar'] = false;
                                                                                   $atributos ['valor'] = '';
                                                                                   $atributos = array_merge ( $atributos, $atributosGlobales );
                                                                                   echo $this->miFormulario->enlace( $atributos );
                                                                                   unset ( $atributos );
                                                                                  // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                                                     //-------------Inicio preparar enlace soporte-------
                                                                                     $verSoporte = $variableSoporte;
                                                                                     $verSoporte .= "&opcion=verPdf";
                                                                                     $verSoporte .= "&raiz=".$this->rutaSoporte;
                                                                                     $verSoporte .= "&ruta=".$resultadoSoporte[0]['ubicacion'];
                                                                                     $verSoporte .= "&archivo=".$resultadoSoporte[0]['archivo'];
                                                                                     $verSoporte .= "&alias=".$resultadoSoporte[0]['alias'];
                                                                                     $verSoporte = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $verSoporte, $directorio );
                                                                                     //-------------Fin preparar enlace soporte-------
                                                                                   $esteCampo = 'ruta'.$resultadoSoporte[0]['consecutivo_soporte'];
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
                                                                                   $atributos ['valor'] = $verSoporte;
                                                                                   $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                                                   $atributos ['deshabilitado'] = FALSE;
                                                                                   $atributos ['tamanno'] = 30;
                                                                                   $atributos ['anchoCaja'] = 60;
                                                                                   $atributos ['maximoTamanno'] = '';
                                                                                   $atributos ['anchoEtiqueta'] = 120;
                                                                                   //$atributos = array_merge ( $atributos, $atributosGlobales );
                                                                                   echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                                                                   // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                                                }   
                                                                   }
                                                         }
                                                     echo $this->miFormulario->division( 'fin' );
                                                     unset ( $atributos );
                                                     // --------------- FIN CONTROL : Cuadro de Soporte foraech--------------------------------------------------
                                                     } 
                                                // --------------- FIN CONTROLES  : CARGA SOPORTES --------------------------------------------------
                                            }
                                        echo $this->miFormulario->division( 'fin' );
                                        unset ( $atributos );
                                        // --------------- FIN CONTROL : Cuadro de Soporte Diploma --------------------------------------------------

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
					$esteCampo = 'botonProduccion';
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
                                           //valida si registro consentimiento informado
                                            if($resultadoUsuarios[0]['autorizacion']==FALSE)
                                                {
                                                 // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                $MesteCampo= 'autorizacion';
                                                $Matributos ['id'] = $MesteCampo;
                                                $Matributos ['tipo'] = 'warning';
                                                $Matributos ['estilo'] = 'textoCentrar';
                                                $Matributos ['mensaje'] = $this->lenguaje->getCadena ( $MesteCampo );
                                                //$tab ++;
                                                // Aplica atributos globales al control
                                                $Matributos = array_merge ( $Matributos, $atributosGlobales );
                                                echo $this->miFormulario->cuadroMensaje ( $Matributos );
                                                unset ( $Matributos );
                                                $atributos ['deshabilitado'] = true;
                                                }
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
                                    $produccion=isset($resultadoProduccion[0]['consecutivo_produccion'])?$resultadoProduccion[0]['consecutivo_produccion']:0;    
                                    $valorCodificado = "action=" . $esteBloque ["nombre"];
                                    $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                    $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
                                    $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                                    $valorCodificado .= "&opcion=guardarDatosProduccion";
                                    $valorCodificado .= "&id_usuario=".$usuario;
                                    $valorCodificado .= "&consecutivo_produccion=".$produccion;
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
$miSeleccionador = new produccionForm ( $this->lenguaje, $this->miFormulario, $this->sql );
$miSeleccionador->miForm ();
?>
