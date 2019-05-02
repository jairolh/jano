<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use gestionConcurso\detalleConcurso\funcion\redireccion;

class registrarConcursoForm {
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
                 //identifica el tipo de rol para buscar el concurso a administrar
                $roles=  $miSesion->RolesSesion();
                $admin='';
                if($roles){
                    foreach ($roles as $key => $value) 
                        {   if($key>0){$admin.=",";}
                            $admin.= "'".$roles[$key]['nom_app']."'";  
                        }
                }
                
                if(isset($_REQUEST['consecutivo_concurso']))
                    {   $parametro['consecutivo_concurso']=$_REQUEST['consecutivo_concurso'];
                        $cadena_sql = $this->miSql->getCadenaSql("consultaConcurso", $parametro);
                        $resultadoConcurso = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    }
                else{$nuevoConcurso=1;}    
                //-----BUSCA LOS TIPOS DE SOPORTES PARA EL FORMUALRIO, SEGÚN LOS RELACIONADO EN LA TABLA
                 $parametroTipoSop = array('dato_relaciona'=>'datosConcurso',
                                           //'tipo_soporte'=>'soporteAcuerdo',
                                           );
                 $cadenaSalud_sql = $this->miSql->getCadenaSql("buscarTipoSoporte", $parametroTipoSop);
                 $resultadoTiposop = $esteRecursoDB->ejecutarAcceso($cadenaSalud_sql, "busqueda");
                 // ---------------- SECCION: Enlace para soporte -----------------------------------------------
                 $variableSoporte = "pagina=gestionarSoportes"; //pendiente la pagina para modificar parametro                                                        
                 $variableSoporte.= "&action=gestionarSoportes";
                 $variableSoporte.= "&bloque=" . $esteBloque["id_bloque"];
                 $variableSoporte.= "&bloqueGrupo=";
                 //----------------	
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
                        $esteCampo = 'botonRegresar';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['enlace'] = $variable;
                        $atributos ['tabIndex'] = 1;
                        $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                        $atributos ['estilo'] = 'textoPequenno textoGris';
                        $atributos ['enlaceImagen'] = $rutaBloque."/images/player_rew.png";
                        $atributos ['posicionImagen'] = "atras";//"adelante";
                        $atributos ['ancho'] = '30px';
                        $atributos ['alto'] = '30px';
                        $atributos ['redirLugar'] = true;
                        echo $this->miFormulario->enlace ( $atributos );
                        unset ( $atributos );
                        
			$esteCampo = "marcoConcurso";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{	// ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
				$esteCampo = 'tipo';
				$atributos ['nombre'] = $esteCampo;
                                $atributos ['id'] = $esteCampo;
                                $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                $atributos ["etiquetaObligatorio"] = true;
                                $atributos ['tab'] = $tab ++;
                                $atributos ['anchoEtiqueta'] = 170;
                                $atributos ['evento'] = '';
                                if (isset ( $resultadoConcurso[0] ['codigo_nivel_concurso'] )) 
                                    {	$atributos ['seleccion'] = $resultadoConcurso[0] ['codigo_nivel_concurso'];}
                                else{	$atributos ['seleccion'] = -1;}
				$atributos ['columnas'] = 1;
                                $atributos ['tamanno'] = 1;
                                $atributos ['estilo'] = "jqueryui";
                                $atributos ['validar'] = "required";
                                $atributos ['limitar'] = true;
                                $atributos ['anchoCaja'] = 60;
                                $atributos ['evento'] = '';
                                $parametronivel=array('tipo_nivel'=> 'TipoConcurso','tipo_concurso'=>$admin);
                                $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarNivel",$parametronivel );
                                $matrizItems = array (array (0,' '));
                                $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                $atributos ['matrizItems'] = $matrizItems;
                                // Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
                                echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
				$esteCampo = 'modalidad';
				$atributos ['nombre'] = $esteCampo;
                                $atributos ['id'] = $esteCampo;
                                $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                $atributos ["etiquetaObligatorio"] = true;
                                $atributos ['tab'] = $tab ++;
                                $atributos ['anchoEtiqueta'] = 170;
                                $atributos ['evento'] = '';
                                if (isset ($resultadoConcurso[0] ['consecutivo_modalidad']  )) 
                                    {	$atributos ['seleccion'] = $resultadoConcurso[0]['consecutivo_modalidad'] ;
                                        $parametro['tipo_concurso']=$resultadoConcurso[0]['codigo_nivel_concurso'];
                                        $atributos ['deshabilitado'] = false;
                                    }
                                else{	$atributos ['seleccion'] = -1;
                                        $atributos ['deshabilitado'] = true;
                                        $parametro='';
                                    }
                                $atributos ['columnas'] = 1;
                                $atributos ['tamanno'] = 1;
                                $atributos ['estilo'] = "jqueryui";
                                $atributos ['validar'] = "required";
                                $atributos ['limitar'] = true;
                                $atributos ['anchoCaja'] = 60;
                                $atributos ['evento'] = '';
                                $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultaModalidad",$parametro );
                                $matrizItems = array (array (0,' '));
                                $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                $atributos ['matrizItems'] = $matrizItems;
                                // Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Lista -------------------------------------------------------- 
       				// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
				$esteCampo = 'nombre';
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
				$atributos ['validar']="required, minSize[5],maxSize[255]";
                                if (isset ($resultadoConcurso[0] ['nombre']  )) 
                                    { $atributos ['valor'] =  $resultadoConcurso[0]['nombre'] ;}
                                else{ $atributos ['valor'] = ''; }
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
				$esteCampo = 'acuerdo';
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
				$atributos ['validar']="required, minSize[5],maxSize[50]";
                                if (isset ($resultadoConcurso[0] ['acuerdo']  )) 
                                    { $atributos ['valor'] =  $resultadoConcurso[0]['acuerdo'] ;}
                                else{ $atributos ['valor'] = ''; }
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
                                $atributos ['validar'] = 'required, minSize[10], maxSize[3000]';
                                $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                $atributos ['deshabilitado'] = false;
                                $atributos ['tamanno'] = 60;
                                $atributos ['maximoTamanno'] = '';
                                $atributos ['anchoEtiqueta'] = 170;
                                if (isset ($resultadoConcurso[0]['descripcion']  )) 
                                    { $atributos ['valor'] =  $resultadoConcurso[0]['descripcion'] ;}
                                else{ $atributos ['valor'] = ''; }
                                $tab ++;

                                // Aplica atributos globales al control
                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                echo $this->miFormulario->campoTextArea ( $atributos );
                                unset ( $atributos );                                                   
                                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
				$esteCampo = 'fecha_inicio_concurso';
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
                                if (isset ($resultadoConcurso[0]['fecha_inicio']  )) 
                                    { $atributos ['valor'] =  $resultadoConcurso[0]['fecha_inicio'] ;}
                                else{ $atributos ['valor'] = ''; }
				$atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
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
				$esteCampo = 'fecha_fin_concurso';
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
                                if (isset ($resultadoConcurso[0]['fecha_fin']  )) 
                                    { $atributos ['valor'] =  $resultadoConcurso[0]['fecha_fin'] ;}
                                else{ $atributos ['valor'] = ''; }
				$atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
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
                                $esteCampo = 'maximo_puntos_conc';
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
                                $atributos ['validar']="required,custom[number],minSize[1],min[1]";
                                if (isset ( $resultadoConcurso[0]['puntos_aprueba'] )) 
                                     {  $atributos ['valor'] = $resultadoConcurso[0]['puntos_aprueba'];} 
                                else {  $atributos ['valor'] = '100';}
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
                                $esteCampo = 'porc_aprueba_conc';
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
                                if (isset ( $resultadoConcurso[0]['porcentaje_aprueba'] )) 
                                     {  $atributos ['valor'] = $resultadoConcurso[0]['porcentaje_aprueba'];} 
                                else {  $atributos ['valor'] = '70';}
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
                                $esteCampo = 'max_inscribe_aspirante';
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
                                $atributos ['validar']="required,custom[number],minSize[1],maxSize[2],min[1]";
                                if (isset ( $resultadoConcurso[0]['max_inscribe_aspirante'] )) 
                                     {  $atributos ['valor'] = $resultadoConcurso[0]['max_inscribe_aspirante'];} 
                                else {  $atributos ['valor'] = '1';}
                                $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                $atributos ['deshabilitado'] = false;
                                $atributos ['tamanno'] = 60;
                                $atributos ['maximoTamanno'] = '';
                                $atributos ['anchoEtiqueta'] = 170;
                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                unset ( $atributos );
                                // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------                                                              
                                // --------------- INICIO CONTROLES : CARGA SOPORTES SEGUN LOS RELACIONADOS --------------------------------------------------
                                foreach ($resultadoTiposop as $tipokey => $value) 
                                    {

                                    //valida si existen soportes para el tipo
                                    if(isset($_REQUEST['consecutivo_concurso']) && $_REQUEST['consecutivo_concurso']>0)
                                        {  
                                            $parametroSop = array('consecutivo_persona'=>0,
                                                 'tipo_dato'=>$resultadoTiposop[$tipokey]['dato_relaciona'],
                                                 'nombre_soporte'=>$resultadoTiposop[$tipokey]['nombre'],
                                                 'consecutivo_dato'=>$_REQUEST['consecutivo_concurso']
                                                );
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
                                                 $atributos ['etiqueta'] = $resultadoTiposop[$tipokey]['alias'].": ";// $this->lenguaje->getCadena ( $esteCampo );
                                                 if(isset($resultadoTiposop[$tipokey]['validacion']) && strstr($resultadoTiposop[$tipokey]['validacion'], 'required'))
                                                     {  $atributos ['etiquetaObligatorio'] = true;
                                                     }
                                                 else{  $atributos ['etiquetaObligatorio'] = false;
                                                     }
                                                 $atributos ['tamanno'] = $resultadoTiposop[$tipokey]['tamanno_permitido'];
                                                 $atributos ['evento'] = 'accept="'.$resultadoTiposop[$tipokey]['extencion_permitida'].'"';
                                                 //si existe soporte van 2 columnas
                                                 if(isset($resultadoSoporte[0]['archivo']) && $resultadoTiposop[$tipokey]['nombre']!='soporteAutorizacionConcurso')
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
                                                // echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                                 
                                                if($resultadoTiposop[$tipokey]['nombre']=='soporteAutorizacionConcurso'  && isset($resultadoConcurso[0]['fecha_inicio']) && strcmp($hoy,$resultadoConcurso[0]['fecha_inicio']) >= 0)
                                                     {echo $this->miFormulario->campoCuadroTexto ( $atributos );}
                                                elseif($resultadoTiposop[$tipokey]['nombre']!='soporteAutorizacionConcurso') 
                                                     {echo $this->miFormulario->campoCuadroTexto ( $atributos );}                                                            
                                                 // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                               if(isset($resultadoSoporte[0]['archivo']))
                                                     { 
                                                        foreach ($resultadoSoporte as $sop => $value) 
                                                            {    if($resultadoTiposop[$tipokey]['nombre']!='soporteAutorizacionConcurso')
                                                                     {$sop=0;}   
                                                                     // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                                    $esteCampo = 'archivo'.$resultadoSoporte[$sop]['consecutivo_soporte'];
                                                                    $atributos ['id'] = $esteCampo;
                                                                    $atributos ['enlace'] = 'javascript:enlaceSop("ruta'.$resultadoSoporte[$sop]['consecutivo_soporte'].'");';
                                                                    $atributos ['tabIndex'] = 0;
                                                                    $atributos ['marco'] = true;
                                                                    if($resultadoTiposop[$tipokey]['nombre']!='soporteAutorizacionConcurso')
                                                                         {  $atributos ['columnas'] = 2;

                                                                         }
                                                                     else{  $atributos ['columnas'] = 1;
                                                                         }
                                                                    $atributos ['enlaceTexto'] = $resultadoSoporte[$sop]['alias'];
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
                                                                      $verSoporte .= "&ruta=".$resultadoSoporte[$sop]['ubicacion'];
                                                                      $verSoporte .= "&archivo=".$resultadoSoporte[$sop]['archivo'];
                                                                      $verSoporte .= "&alias=".$resultadoSoporte[$sop]['alias'];
                                                                      $verSoporte = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $verSoporte, $directorio );
                                                                      //-------------Fin preparar enlace soporte-------
                                                                    $esteCampo = 'ruta'.$resultadoSoporte[$sop]['consecutivo_soporte'];
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
                                                                //valida si diferente a autorización termina el foreach    
                                                                 if($resultadoTiposop[$tipokey]['nombre']!='soporteAutorizacionConcurso')
                                                                     {break;}   
                                                        }       
                                                   }
                                         }
                                     echo $this->miFormulario->division( 'fin' );
                                     unset ( $atributos );
                                     // --------------- FIN CONTROL : Cuadro de Soportes--------------------------------------------------
                                     } 
                                // --------------- FIN CONTROLES : CARGA SOPORTES --------------------------------------------------
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );

        			// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonGuardar';
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
                                        //imprime boton si no ha llegado al fin de concurso
                                        
                                    if($nuevoConcurso || (isset($resultadoConcurso[0]['fecha_inicio']) && strcmp($hoy,$resultadoConcurso[0]['fecha_fin']) <= 0 ))
                                        {echo $this->miFormulario->campoBoton ( $atributos );}
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------
				}
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
			$concurso=isset($resultadoConcurso[0]['consecutivo_concurso'])?$resultadoConcurso[0]['consecutivo_concurso']:0;    
			$valorCodificado = "action=" . $esteBloque ["nombre"];
			$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=guardarConcurso";
                        $valorCodificado .= "&id_usuario=".$usuario;
                        $valorCodificado .= "&consecutivo_concurso=".$concurso;
			
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
			
			$atributos ['marco'] = true;
			$atributos ['tipoEtiqueta'] = 'fin';
			echo $this->miFormulario->formulario ( $atributos );
			
			return true;
		}
	}
}

$miSeleccionador = new registrarConcursoForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
