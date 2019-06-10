<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use gestionConcursante\gestionHoja\funcion\redireccion;

class registrarForm {
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
               // var_dump($resultadoUsuarios);
                //-----BUSCA LOS TIPOS DE SOPORTES PARA EL FORMUALRIO, SEGÚN LOS RELACIONADO EN LA TABLA
                $parametroTipoSop = array('dato_relaciona'=>'datosBasicos',);
                $cadenaSalud_sql = $this->miSql->getCadenaSql("buscarTipoSoporte", $parametroTipoSop);
                $resultadoTiposop = $esteRecursoDB->ejecutarAcceso($cadenaSalud_sql, "busqueda");
                // ---------------- SECCION: Enlace para soporte -----------------------------------------------
                $variableSoporte = "pagina=gestionarSoportes"; //pendiente la pagina para modificar parametro                                                        
                $variableSoporte.= "&action=gestionarSoportes";
                $variableSoporte.= "&bloque=" . $esteBloque["id_bloque"];
                $variableSoporte.= "&bloqueGrupo=";
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
                $estefomulario= 'datosBasicos';
		$atributos ['id'] = $estefomulario;
		$atributos ['nombre'] = $estefomulario;
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
			$esteCampo = "marcoBasicos";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{    
                            // ---------------- CONTROL AGRUPACION: Cuadro Agrupacion --------------------------------------------------------
                                        $atributos ["id"] = "cuadroSoportesBasicos";
                                        $atributos ["estiloEnLinea"] = "display:block";
                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                        echo $this->miFormulario->division ( "inicio", $atributos );
                                        unset ( $atributos );
                                        { 
                                                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                $esteCampo = "cuadroSoportesBasicos";
                                                $atributos ['id'] = $esteCampo;
                                                $atributos ['leyenda'] = $this->lenguaje->getCadena ( $esteCampo );
                                                $atributos ['estilo'] = 'jqueryui';
                                                echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
                                                {
                                                // --------------- INICIO CONTROLES : CARGA SOPORTES SEGUN LOS RELACIONADOS --------------------------------------------------
                                               foreach ($resultadoTiposop as $tipokey => $value) 
                                                   {

                                                   //valida si existen soportes para el tipo
                                                   if(isset($resultadoUsuarios[0]['consecutivo']) && $resultadoUsuarios[0]['consecutivo']>0)
                                                       {  
                                                           $parametroSop = array('consecutivo_persona'=>trim($resultadoUsuarios[0]['consecutivo']),
                                                                'tipo_dato'=>$resultadoTiposop[$tipokey]['dato_relaciona'],
                                                                'nombre_soporte'=>$resultadoTiposop[$tipokey]['nombre'],
                                                                'consecutivo_dato'=>$resultadoUsuarios[0]['consecutivo']
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
                                                                $archivo = "formato ".$resultadoTiposop[$tipokey]['extencion_permitida']." y máximo ".number_format(($resultadoTiposop[$tipokey]['tamanno_permitido']/1024),2,",",".")." Mb";//$this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                                $atributos ['etiqueta'] = "<p align='left'>".$resultadoTiposop[$tipokey]['alias']." </p>";// $this->lenguaje->getCadena ( $esteCampo );
                                                                $atributos ['etiqueta'].= "<p> <font face='Verdana, Arial, Helvetica, sans-serif' size='1.2' color='#FF0000' style='text-align:left' >".ucfirst($archivo)."</font></p>  ";  
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
                                            echo $this->miFormulario->agrupacion ( 'fin' );
                                            unset ( $atributos );
				}
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );
				// ---------------- CONTROL: Fin Cuadro Agrupacion --------------------------------------------------------
                                // ---------------- CONTROL AGRUPACION: Cuadro Agrupacion --------------------------------------------------------
				$atributos ["id"] = "cuadroIdentificacion";
				$atributos ["estiloEnLinea"] = "display:block";
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					
					$esteCampo = "cuadroIdentificacion";
					$atributos ['id'] = $esteCampo;
					$atributos ['leyenda'] = $this->lenguaje->getCadena ( $esteCampo );
                                        $atributos ['estilo'] = 'jqueryui';
					echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
					{                                            
                                                    
                                        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                            $esteCampo = 'nombres';
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
                                            $atributos ['validar']="required, minSize[2]";
                                            $atributos ['valor'] = $resultadoUsuarios[0]['nombre'];
                                            $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                            //tooltip - $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
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
                                            $esteCampo = 'apellidos';
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
                                            $atributos ['validar']="required, minSize[2]";
                                            $atributos ['valor'] =  $resultadoUsuarios[0]['apellido'];
                                            $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                            //tooltip -     $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
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
                                            // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                            $esteCampo = 'sexo';
                                            $atributos ['nombre'] = $esteCampo;
                                            $atributos ['id'] = $esteCampo;
                                            $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                            $atributos ["etiquetaObligatorio"] = true;
                                            $atributos ['tab'] = $tab ++;
                                            //$atributos ['seleccion'] = - 1;
                                            $atributos ['anchoEtiqueta'] = 170;
                                            $atributos ['evento'] = '';
                                            if (isset ( $resultadoUsuarios[0][$esteCampo] ))
                                                 {  $atributos ['seleccion'] = $resultadoUsuarios[0][$esteCampo];}
                                            else {	$atributos ['seleccion'] = - 1;}
                                            $atributos ['deshabilitado'] = false;
                                            $atributos ['columnas'] = 1;
                                            $atributos ['tamanno'] = 1;
                                            $atributos ['estilo'] = "jqueryui";
                                            $atributos ['validar'] = "required";
                                            $atributos ['limitar'] = true;
                                            $atributos ['anchoCaja'] = 60;
                                            $atributos ['evento'] = '';
                                            //$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
                                            //$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                            $matrizItems=array( array('F','Femenino'), 
                                                                array('M','Masculino')                                               
                                                              );
                                            $atributos ['matrizItems'] = $matrizItems;
                                            // Aplica atributos globales al control
                                            $atributos = array_merge ( $atributos, $atributosGlobales );
                                            echo $this->miFormulario->campoCuadroLista ( $atributos );
                                            unset ( $atributos );
                                            // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                            
                                            // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                            $esteCampo = 'tipo_identificacion';
                                            $atributos ['nombre'] = $esteCampo;
                                            $atributos ['id'] = $esteCampo;
                                            $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                            $atributos ["etiquetaObligatorio"] = false;
                                            $atributos ['tab'] = $tab ++;
                                            $atributos ['seleccion'] = - 1;
                                            $atributos ['anchoEtiqueta'] = 170;
                                            $atributos ['evento'] = '';
                                            if (isset ( $resultadoUsuarios[0][$esteCampo] ))
                                                {$atributos ['seleccion'] = $resultadoUsuarios[0][$esteCampo];}
                                            else {	$atributos ['seleccion'] = - 1;}
                                            $atributos ['deshabilitado'] = true;
                                            $atributos ['columnas'] = 1;
                                            $atributos ['tamanno'] = 1;
                                            $atributos ['ajax_function'] = "";
                                            $atributos ['ajax_control'] = $esteCampo;
                                            $atributos ['estilo'] = "jqueryui";
                                            $atributos ['validar'] = "required";
                                            $atributos ['limitar'] = true;
                                            $atributos ['anchoCaja'] = 60;
                                            $atributos ['miEvento'] = '';
                                            $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "tipoIdentificacion" );
                                            $matrizItems = array (array (0,' '));
                                            $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                            $atributos ['matrizItems'] = $matrizItems;
                                            // Aplica atributos globales al control
                                            $atributos = array_merge ( $atributos, $atributosGlobales );
                                            echo $this->miFormulario->campoCuadroLista ( $atributos );
                                            unset ( $atributos );
                                            // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                            $esteCampo = 'identificacion';
                                            $atributos ['id'] = $esteCampo;
                                            $atributos ['nombre'] = $esteCampo;
                                            $atributos ['tipo'] = 'text';
                                            $atributos ['estilo'] = 'jqueryui';
                                            $atributos ['marco'] = true;
                                            $atributos ['estiloMarco'] = '';
                                            $atributos ["etiquetaObligatorio"] = false;
                                            $atributos ['columnas'] = 1;
                                            $atributos ['dobleLinea'] =false;
                                            $atributos ['tabIndex'] = $tab;
                                            $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                            $atributos ['validar']="required, minSize[5], custom[integer]";
                                            $atributos ['valor'] = $resultadoUsuarios[0]['identificacion'];
                                            $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                            //tooltip - $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                            $atributos ['deshabilitado'] = 'readonly';  //true;
                                            $atributos ['tamanno'] = 20;
                                            $atributos ['anchoCaja'] = 60;
                                            $atributos ['maximoTamanno'] = '';
                                            $atributos ['anchoEtiqueta'] = 170;
                                            $tab ++;
                                            // Aplica atributos globales al control
                                            $atributos = array_merge ( $atributos, $atributosGlobales );
                                            echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                            unset ( $atributos );
                                            
                                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------         
                                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                $esteCampo = 'fecha_identificacion';
                                                $atributos ['id'] = $esteCampo;
                                                $atributos ['nombre'] = $esteCampo;
                                                $atributos ['tipo'] = 'fecha';
                                                $atributos ['estilo'] = 'jqueryui';
                                                $atributos ['marco'] = true;
                                                $atributos ['estiloMarco'] = '';
                                                $atributos ["etiquetaObligatorio"] = true;
                                                $atributos ['columnas'] = 1;
                                                $atributos ['dobleLinea'] = 0;
                                                $atributos ['tabIndex'] = $tab;
                                                $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                                $atributos ['validar']="required";
                                                $atributos ['valor'] =  $resultadoUsuarios[0]['fecha_identificacion'];
                                                $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                //tooltip - $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                $atributos ['deshabilitado'] = true;
                                                $atributos ['tamanno'] = 60;
                                                $atributos ['maximoTamanno'] = '';
                                                $atributos ['anchoEtiqueta'] = 170;
                                                $atributos ['anchoCaja'] = 60;
                                                $tab ++;
                                                // Aplica atributos globales al control
                                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                                echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                                unset ( $atributos );
                                                // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                                                // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                                $esteCampo = 'lugar_identificacion';
                                                $atributos ['nombre'] = $esteCampo;
                                                $atributos ['id'] = $esteCampo;
                                                $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                                $atributos ["etiquetaObligatorio"] = true;
                                                $atributos ['tab'] = $tab ++;
                                                //$atributos ['seleccion'] = - 1;
                                                $atributos ['anchoEtiqueta'] = 170;
                                                $atributos ['evento'] = '';
                                                if (isset ( $resultadoUsuarios[0]['lugar_identificacion'] ))
                                                     {  $atributos ['seleccion'] = $resultadoUsuarios[0]['lugar_identificacion'];
                                                        $atributos ['deshabilitado'] = false;
                                                     }
                                                else {	$atributos ['seleccion'] = -1;
                                                        $atributos ['deshabilitado'] = false;
                                                    }
                                                $atributos ['columnas'] = 1;
                                                $atributos ['tamanno'] = 1;
                                                $atributos ['estilo'] = "jqueryui";
                                                $atributos ['validar'] = "required";
                                                $atributos ['limitar'] = true;
                                                $atributos ['anchoCaja'] = 60;
                                                $atributos ['evento'] = '';
                                                $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
                                                $matrizItems = array (array (0,' '));
                                                $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                                $atributos ['matrizItems'] = $matrizItems;
                                                // Aplica atributos globales al control
                                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                                echo $this->miFormulario->campoCuadroLista ( $atributos );
                                                unset ( $atributos );
                                                // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------                                
                                
                                
                                            }
                                            echo $this->miFormulario->agrupacion ( 'fin' );
                                            unset ( $atributos );
				}
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );
				// ---------------- CONTROL: Fin Cuadro Agrupacion --------------------------------------------------------
                                // ---------------- CONTROL AGRUPACION: Cuadro Agrupacion --------------------------------------------------------
				$atributos ["id"] = "cuadro_nacimiento";
				$atributos ["estiloEnLinea"] = "display:block";
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					
					$esteCampo = "cuadroNacimiento";
					$atributos ['id'] = $esteCampo;
					$atributos ['leyenda'] = $this->lenguaje->getCadena ( $esteCampo );
                                        $atributos ['estilo'] = 'jqueryui';
					echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
					{
                                             // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                $esteCampo = 'fecha_nacimiento';
                                                $atributos ['id'] = $esteCampo;
                                                $atributos ['nombre'] = $esteCampo;
                                                $atributos ['tipo'] = 'fecha';
                                                $atributos ['estilo'] = 'jqueryui';
                                                $atributos ['marco'] = true;
                                                $atributos ['estiloMarco'] = '';
                                                $atributos ["etiquetaObligatorio"] = true;
                                                $atributos ['columnas'] = 1;
                                                $atributos ['dobleLinea'] = 0;
                                                $atributos ['tabIndex'] = $tab;
                                                $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                                $atributos ['validar']="required";
                                                $atributos ['valor'] =  $resultadoUsuarios[0]['fecha_nacimiento'];
                                                $atributos ['textoFondo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                //tooltip - $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                $atributos ['deshabilitado'] = true;
                                                $atributos ['tamanno'] = 60;
                                                $atributos ['maximoTamanno'] = '';
                                                $atributos ['anchoEtiqueta'] = 170;
                                                $atributos ['anchoCaja'] = 60;
                                                $tab ++;
                                                // Aplica atributos globales al control
                                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                                echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                                unset ( $atributos );
                                                // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                                                // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                                $esteCampo = 'pais';
                                                $atributos ['nombre'] = $esteCampo;
                                                $atributos ['id'] = $esteCampo;
                                                $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                                $atributos ["etiquetaObligatorio"] = true;
                                                $atributos ['tab'] = $tab ++;
                                                $atributos ['anchoEtiqueta'] = 170;
                                                $atributos ['evento'] = ' ';
                                                if (isset ($resultadoUsuarios[0]['pais_nacimiento']))
                                                     {  $atributos ['seleccion'] = $resultadoUsuarios[0]['pais_nacimiento'];}
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
                                                // $atributos['miniRegistro']=;
                                                // $atributos ['baseDatos'] = "inventarios";
                                                // $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
                                                // Aplica atributos globales al control
                                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                                echo $this->miFormulario->campoCuadroLista ( $atributos );
                                                unset ( $atributos );
                                                // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                                // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                                $esteCampo = 'departamento';
                                                $atributos ['nombre'] = $esteCampo;
                                                $atributos ['id'] = $esteCampo;
                                                $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                                $atributos ["etiquetaObligatorio"] = true;
                                                $atributos ['tab'] = $tab ++;
                                                //$atributos ['seleccion'] = - 1;
                                                $atributos ['anchoEtiqueta'] = 170;
                                                $atributos ['evento'] = '';
                                                if (isset($resultadoUsuarios[0]['departamento_nacimiento']))
                                                     {  $atributos ['seleccion'] = $resultadoUsuarios[0]['departamento_nacimiento'];
                                                        $parametro['pais']= $resultadoUsuarios[0]['pais_nacimiento'];
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
                                                
                                                $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento",$parametro);
                                                $matrizItems = array (array (0,' '));
                                                $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                                $atributos ['matrizItems'] = $matrizItems;
                                                // Aplica atributos globales al control
                                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                                echo $this->miFormulario->campoCuadroLista ( $atributos );
                                                unset ( $atributos );
                                                // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------                                
                                                // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
                                                $esteCampo = 'ciudad';
                                                $atributos ['nombre'] = $esteCampo;
                                                $atributos ['id'] = $esteCampo;
                                                $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                                $atributos ["etiquetaObligatorio"] = true;
                                                $atributos ['tab'] = $tab ++;
                                                //$atributos ['seleccion'] = - 1;
                                                $atributos ['anchoEtiqueta'] = 170;
                                                $atributos ['evento'] = '';
                                                if (isset ( $resultadoUsuarios[0]['lugar_nacimiento'] ))
                                                     {  $atributos ['seleccion'] = $resultadoUsuarios[0]['lugar_nacimiento'];
                                                        $parametro['departamento']= $resultadoUsuarios[0]['departamento_nacimiento'];
                                                        $atributos ['deshabilitado'] = false;
                                                     }
                                                else {	$atributos ['seleccion'] = - 1;
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
                                                $esteCampo = 'codigo_idioma_nativo';
                                                $atributos ['nombre'] = $esteCampo;
                                                $atributos ['id'] = $esteCampo;
                                                $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                                                $atributos ["etiquetaObligatorio"] = true;
                                                $atributos ['tab'] = $tab ++;
                                                $atributos ['anchoEtiqueta'] = 170;
                                                $atributos ['evento'] = ' ';
                                                if (isset ( $resultadoUsuarios[0]['codigo_idioma_nativo'] ))
                                                     {  $atributos ['seleccion'] = $resultadoUsuarios[0]['codigo_idioma_nativo'];}
                                                else {  $atributos ['seleccion'] = -1;}
                                                $atributos ['deshabilitado'] = false;
                                                $atributos ['columnas'] = 1;
                                                $atributos ['tamanno'] = 1;
                                                $atributos ['estilo'] = "jqueryui";
                                                $atributos ['validar'] = "required";
                                                $atributos ['limitar'] = true;
                                                $atributos ['anchoCaja'] = 60;
                                                $atributos ['evento'] = '';
                                                $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarIdioma" );
                                                $matrizItems = array (array (0,' '));
                                                $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                                $atributos ['matrizItems'] = $matrizItems;
                                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                                echo $this->miFormulario->campoCuadroLista ( $atributos );
                                                unset ( $atributos );
                                                // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
                                                
					}
					
					echo $this->miFormulario->agrupacion ( 'fin' );
					unset ( $atributos );
				}
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );
				// ---------------- CONTROL: Fin Cuadro Agrupacion --------------------------------------------------------
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{       //verifica si el usuario ya realizó el consentimiento informado
                                        if(isset( $resultadoUsuarios[0]['autorizacion']) && $resultadoUsuarios[0]['autorizacion']==TRUE )
                                            {    
                                                // -----------------CONTROL: Botón ----------------------------------------------------------------
                                                $esteCampo = 'botonBasicos';
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
                                                $tab ++;

                                                // Aplica atributos globales al control
                                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                                echo $this->miFormulario->campoBoton ( $atributos );
                                            }
                                        else
                                            {
                				// -----------------CONTROL: Botón ----------------------------------------------------------------
                                                $esteCampo = 'botonBasicos';
                                                $this->esteBoton =  $esteCampo;
                                                $atributos ["id"] = $esteCampo;
                                                $atributos ["tabIndex"] = $tab;
                                                $atributos ["tipo"] = '';
                                                // submit: no se coloca si se desea un tipo button genérico
                                                $atributos ["modal"] = 'myModal';
                                                $atributos ['submit'] = false;
                                                $atributos ["estiloMarco"] = '';
                                                $atributos ["estiloBoton"] = 'jqueryui';
                                                //$atributos ["estiloBoton"] = 'btn btn-link';
                                                $atributos ['estiloEnLinea'] = 'padding: 2px; margin-right:15px';
                                                // verificar: true para verificar el formulario antes de pasarlo al servidor.
                                                $atributos ["verificar"] = '';
                                                $atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
                                                $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
                                                $tab ++;

                                                // Aplica atributos globales al control
                                                $atributos = array_merge ( $atributos, $atributosGlobales );
                                                echo $this->miFormulario->campoBoton ( $atributos );
                                                unset ( $atributos );
                                                // -----------------FIN CONTROL: Botón -----------------------------------------------------------
                                                include ("dialog.php");
                                            }
                                                
				    // -----------------FIN CONTROL: Botón -----------------------------------------------------------
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

                                    $valorCodificado = "action=" . $esteBloque ["nombre"];
                                    $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                    $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
                                    $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                                    $valorCodificado .= "&opcion=guardarDatosBasicos";
                                    $valorCodificado .= "&id_usuario=".$usuario;
                                    $valorCodificado .= "&consecutivo=".$resultadoUsuarios[0]['consecutivo'];
                                    $valorCodificado .= "&tipo_identificacion=".$resultadoUsuarios[0]['tipo_identificacion'];
                                    $valorCodificado .= "&autorizacion=".$resultadoUsuarios[0]['autorizacion'];
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
                                    $atributos ['estilo'] = 'jqueryui';
                                    $atributos ["obligatorio"] = false;
                                    $atributos ['marco'] = true;
                                    $atributos ["etiqueta"] = "";
                                    $atributos ["valor"] = $valorCodificado;
                                    echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                    unset ( $atributos );

				}
				echo $this->miFormulario->division ( 'fin' );
				// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
                       	}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
		}
                $atributos ['tipoEtiqueta'] = 'fin';
                echo $this->miFormulario->formulario ( $atributos );
                return true;
	}
      
}

$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
