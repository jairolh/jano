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
		
		$atributosGlobales ['campoSeguro'] = 'true';
		
		$_REQUEST ['tiempo'] = time ();
		$tiempo = $_REQUEST ['tiempo'];
		
		// lineas para conectar base de d atos-------------------------------------------------------------------------------------------------
		$conexion = "inventarios";
		
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$seccion ['tiempo'] = $tiempo;
		
		// ___________________________________________________________________________________
		// -------------------------------------------------------------------------------------------------
		$conexion = "inventarios";
		
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "sicapital";
		
		$esteRecursoDBO = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'polizas' );
		
		$resultado_polizas = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$resultado_polizas = $resultado_polizas [0];
		
		$letras = array (
				'1',
				'A',
				'B',
				'C',
				'D' 
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultarOrdenServicios', $_REQUEST ['numero_orden'] );
		
		$orden = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		
		$datosOrden = array (
				'dependencia_solicitante' => $orden [0] ['dependencia_solicitante'],
				'rubro' => $orden [0] ['rubro'],
				'objeto_contrato' => $orden [0] ['objeto_contrato'],
				'polizaA' => $orden [0] ['poliza1'],
				'polizaB' => $orden [0] ['poliza2'],
				'polizaC' => $orden [0] ['poliza3'],
				'polizaD' => $orden [0] ['poliza4'],
				'duracion' => $orden [0] ['duracion_pago'],
				'fecha_inicio_pago' => $orden [0] ['fecha_inicio_pago'],
				'fecha_final_pago' => $orden [0] ['fecha_final_pago'],
				'forma_pago' => $orden [0] ['forma_pago'],
				'total_preliminar' => $orden [0] ['total_preliminar'],
				'iva' => $orden [0] ['iva'],
				'total' => $orden [0] ['total'],
				'nombreContratista' => $orden [0] ['id_contratista_encargado'],
				'asignacionOrdenador' => $orden [0] [17],
				'id_ordenador' => $orden [0] [17],
				'vigencia_contratista'=> $orden [0] ['vig_contratista']
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultarContratista', $orden [0] ['id_contratista'] );
		
		$contratistaC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$datosContratistaC = array (
				'nombre_razon_contratista' => $contratistaC [0] ['nombre_razon_social'],
				'identifcacion_contratista' => $contratistaC [0] ['identificacion'],
				'direccion_contratista' => $contratistaC [0] ['direccion'],
				'telefono_contratista' => $contratistaC [0] ['telefono'],
				'cargo_contratista' => $contratistaC [0] ['cargo'] 
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultarSupervisor', $orden [0] ['id_supervisor'] );
		
		$supervisor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'informacion_supervisor', $orden [0] ['id_supervisor'] );
		
		$supervisor_nom = $esteRecursoDBO->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$datosSupervisor = array (
				'nombre_supervisor' => $supervisor_nom [0] [0],
				'cargo_supervisor' => $supervisor [0] ['cargo'],
				'dependencia_supervisor' => $supervisor [0] ['dependencia'] 
		);
		
		
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'informacion_ordenador', $orden [0] [17] );
		
		$ordenador = $esteRecursoDBO->ejecutarAcceso ( $cadenaSql, "busqueda" );
		

		$datosOrdenador = array (
				'nombreOrdenador' => $ordenador [0] [0] 
		);
		// var_dump($datosOrdenador);
		$encargados = $datosOrdenador ;
		
		$_REQUEST = array_merge ( $_REQUEST, $datosOrden, $datosContratistaC, $datosSupervisor, $encargados );
		
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'informacionPresupuestal', $orden [0]['info_presupuestal'] );
		
		
		$info_presupuestal = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$arreglo=array(
				'vigencia_disponibilidad'=>$info_presupuestal[0][0],
				'diponibilidad'=>$info_presupuestal[0][1],
				'valor_disponibilidad'=>$info_presupuestal[0][2],
				'fecha_diponibilidad'=>$info_presupuestal[0][3],
				'valorLetras_disponibilidad'=>$info_presupuestal[0][4],
				'vigencia_registro'=>$info_presupuestal[0][5],
				'registro'=>$info_presupuestal[0][6],
				'valor_registro'=>$info_presupuestal[0][7],
				'fecha_registro'=>$info_presupuestal[0][8],
				'valorL_registro'=>$info_presupuestal[0][9],
		
		
		
		);
		$_REQUEST=array_merge($_REQUEST,$arreglo);
		
		
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
			
			$datos=unserialize($_REQUEST['informacionGeneral']);
			
			$esteCampo = "AgrupacionGeneral";
			$atributos ['id'] = $esteCampo;
			$atributos ['leyenda'] = "Información General Orden de Servicios";
			echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
			{
				
				// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
				$esteCampo = 'informacion_numero';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'textoSubtituloCursiva';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ['texto'] = $this->lenguaje->getCadena ( $esteCampo ) . "    " . $datos[1];
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 1;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['validar'] = '';
				// $atributos ['etiqueta'] =$this->lenguaje->getCadena ( $esteCampo."Nota" );
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = '';
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 10;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 10;
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoTexto ( $atributos );
				unset ( $atributos );
				
				// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
				$esteCampo = 'informacion_fecha';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'textoSubtituloCursiva';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ['texto'] = $this->lenguaje->getCadena ( $esteCampo ) . "    " . $datos[0];
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 1;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['validar'] = '';
				// $atributos ['etiqueta'] =$this->lenguaje->getCadena ( $esteCampo."Nota" );
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = '';
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 10;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 10;
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoTexto ( $atributos );
				unset ( $atributos );
				
				// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
				$esteCampo = 'informacion_contratista';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'textoSubtituloCursiva';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ['texto'] = $this->lenguaje->getCadena ( $esteCampo ) . "    " . $datos[2];
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 1;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['validar'] = '';
				// $atributos ['etiqueta'] =$this->lenguaje->getCadena ( $esteCampo."Nota" );
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = '';
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 10;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 10;
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoTexto ( $atributos );
				unset ( $atributos );
				
				// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
				$esteCampo = 'informacion_dependencia';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'textoSubtituloCursiva';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ['texto'] = $this->lenguaje->getCadena ( $esteCampo ) . $datos[3];
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 1;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['validar'] = '';
				// $atributos ['etiqueta'] =$this->lenguaje->getCadena ( $esteCampo."Nota" );
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = '';
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 10;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 10;
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoTexto ( $atributos );
				unset ( $atributos );
			}
			
			echo $this->miFormulario->agrupacion ( 'fin' );
			
			$esteCampo = "marcoDatosBasicos";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = "Modificar Orden de Servicios No. ".$datos[1];
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
				
				$esteCampo = "AgrupacionSolicitante";
				$atributos ['id'] = $esteCampo;
				$atributos ['leyenda'] = "Información del Solicitante";
				echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
				{
					
					$esteCampo = 'dependencia_solicitante';
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['anchoEtiqueta'] = 170;
					$atributos ['evento'] = '';
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['seleccion'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['seleccion'] = - 1;
					}
					$atributos ['deshabilitado'] = false;
					$atributos ['columnas'] = 1;
					$atributos ['tamanno'] = 1;
					$atributos ['ajax_function'] = "";
					$atributos ['ajax_control'] = $esteCampo;
					$atributos ['estilo'] = "jqueryui";
					$atributos ['validar'] = "required";
					$atributos ['limitar'] = 1;
					$atributos ['anchoCaja'] = 67;
					$atributos ['miEvento'] = '';
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "dependencia" );
					$matrizItems = array (
							array (
									0,
									' ' 
							) 
					);
					$matrizItems = $esteRecursoDBO->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					$atributos ['matrizItems'] = $matrizItems;
					// $atributos['miniRegistro']=;
					$atributos ['baseDatos'] = "inventarios";
					// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'rubro';
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['seleccion'] = - 1;
					$atributos ['anchoEtiqueta'] = 170;
					$atributos ['evento'] = '';
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['seleccion'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['seleccion'] = - 1;
					}
					$atributos ['deshabilitado'] = false;
					$atributos ['columnas'] = 1;
					$atributos ['tamanno'] = 1;
					$atributos ['ajax_function'] = "";
					$atributos ['ajax_control'] = $esteCampo;
					$atributos ['estilo'] = "jqueryui";
					$atributos ['validar'] = "required";
					$atributos ['limitar'] = 1;
					$atributos ['anchoCaja'] = 70;
					$atributos ['miEvento'] = '';
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "rubros" );
					$matrizItems = array (
							array (
									0,
									' ' 
							) 
					);
					$matrizItems = $esteRecursoDBO->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					$atributos ['matrizItems'] = $matrizItems;
					// $atributos['miniRegistro']=;
					$atributos ['baseDatos'] = "inventarios";
					// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
				}
				echo $this->miFormulario->agrupacion ( 'fin' );
				
				$esteCampo = "AgrupacionSupervisor";
				$atributos ['id'] = $esteCampo;
				$atributos ['leyenda'] = "Datos del Supervisor";
				echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
				{
					
					// ---------------- CONTROL: Cuadro Lista --------------------------------------------------------
					
					$esteCampo = 'dependencia_supervisor';
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['anchoEtiqueta'] = 170;
					$atributos ['evento'] = '';
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['seleccion'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['seleccion'] = - 1;
					}
					$atributos ['deshabilitado'] = false;
					$atributos ['columnas'] = 1;
					$atributos ['tamanno'] = 1;
					$atributos ['ajax_function'] = "";
					$atributos ['ajax_control'] = $esteCampo;
					$atributos ['estilo'] = "jqueryui";
					$atributos ['validar'] = "required";
					$atributos ['limitar'] = true;
					$atributos ['anchoCaja'] = 65;
					$atributos ['miEvento'] = '';
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "cargo_jefe" );
					$matrizItems = array (
							array (
									0,
									' ' 
							) 
					);
					$matrizItems = $esteRecursoDBO->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					$atributos ['matrizItems'] = $matrizItems;
					// $atributos['miniRegistro']=;
					$atributos ['baseDatos'] = "inventarios";
					// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'nombre_supervisor';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = false;
					$atributos ['columnas'] = 2;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = '';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 32;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 170;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'cargo_supervisor';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 2;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 33;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 150;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
				}
				echo $this->miFormulario->agrupacion ( 'fin' );
				
				$esteCampo = "AgrupacionContratista";
				$atributos ['id'] = $esteCampo;
				$atributos ['leyenda'] = "Información del Contratista";
				echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
				{
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'nombre_razon_contratista';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 2;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 28;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 220;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'identifcacion_contratista';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 2;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 33;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 160;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'direccion_contratista';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 2;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 28;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 220;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'telefono_contratista';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 2;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 33;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 160;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'cargo_contratista';
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
					$atributos ['validar'] = 'required, minSize[1]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 28;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 220;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
				}
				echo $this->miFormulario->agrupacion ( 'fin' );
				
				$esteCampo = "AgrupacionObjetoContrato";
				$atributos ['id'] = $esteCampo;
				$atributos ['leyenda'] = "Información del Contrato";
				echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
				{
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'objeto_contrato';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 105;
					$atributos ['filas'] = 15;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1]';
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 20;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 220;
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTextArea ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "AgrupacionPoliza";
					$atributos ['id'] = $esteCampo;
					$atributos ['leyenda'] = "Requerimiento de Póliza";
					echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
					{
						for($i = 1; $i <= 4; $i ++) {
							
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$nombre = 'poliza' . $letras [$i];
							$atributos ['id'] = $nombre;
							$atributos ['nombre'] = $nombre;
							$atributos ['estilo'] = 'campoCuadroSeleccionCorta';
							$atributos ['marco'] = true;
							$atributos ['estiloMarco'] = true;
							$atributos ["etiquetaObligatorio"] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = 1;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $resultado_polizas [$i];
							$atributos ['validar'] = '';
							$atributos ['valor'] = 'poliza' . $i;
							if (isset ( $_REQUEST [$nombre] )) {
								if ($_REQUEST [$nombre] != 'f') {
									$atributos ['seleccionado'] = $_REQUEST [$nombre];
								}
							}
							$atributos ['deshabilitado'] = false;
							$tab ++;
							
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroSeleccion ( $atributos );
							unset ( $atributos );
						}
					}
					echo $this->miFormulario->agrupacion ( 'fin' );
				}
				echo $this->miFormulario->agrupacion ( 'fin' );
				
				$esteCampo = "AgrupacionReferentePago";
				$atributos ['id'] = $esteCampo;
				$atributos ['leyenda'] = "Información Referente al Pago";
				echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
				{
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'fecha_inicio_pago';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'fecha';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 3;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'require,custom[date]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 8;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 160;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'fecha_final_pago';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'fecha';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 3;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'require,custom[date]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 8;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 160;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'duracion';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = false;
					$atributos ['columnas'] = 3;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1],maxSize[10],custom[onlyNumberSp]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 11;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 160;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					
					$atributos ["id"] = "numero_dias"; // No cambiar este nombre
					$atributos ["tipo"] = "hidden";
					$atributos ['estilo'] = '';
					$atributos ["obligatorio"] = false;
					$atributos ['marco'] = true;
					$atributos ["etiqueta"] = "";
					$atributos ["valor"] = '';
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'forma_pago';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 105;
					$atributos ['filas'] = 3;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1]';
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 20;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 220;
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTextArea ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'total_preliminar';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 3;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 18;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 160;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'iva';
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = false;
					$atributos ['tab'] = $tab ++;
					$atributos ['seleccion'] = 0;
					$atributos ['anchoEtiqueta'] = 200;
					$atributos ['evento'] = '';
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['deshabilitado'] = false;
					$atributos ['columnas'] = 3;
					$atributos ['tamanno'] = 1;
					$atributos ['ajax_function'] = "";
					$atributos ['ajax_control'] = $esteCampo;
					$atributos ['estilo'] = "jqueryui";
					$atributos ['validar'] = "required";
					$atributos ['limitar'] = 0;
					$atributos ['anchoCaja'] = 30;
					$atributos ['miEvento'] = '';
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "dependencia" );
					$matrizItems = array (
							array (
									0,
									'NO' 
							),
							
							array (
									1,
									'SI' 
							) 
					);
					// $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					$atributos ['matrizItems'] = $matrizItems;
					// $atributos['miniRegistro']=;
					$atributos ['baseDatos'] = "inventarios";
					// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'total_iva';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = false;
					$atributos ['columnas'] = 3;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = 0;
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 13;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 100;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'total';
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
					$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[number]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 25;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 160;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
				}
				
				echo $this->miFormulario->agrupacion ( 'fin' );
				$esteCampo = "AgrupacionDisponibilidad";
				$atributos ['id'] = $esteCampo;
				$atributos ['leyenda'] = "Información Respaldo Presupuestal";
				echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
				{
					$esteCampo = "AgrupacionCertificadoPresupuestal";
					$atributos ['id'] = $esteCampo;
					$atributos ['leyenda'] = "Certificado de Disponibilidad Presupuestal";
					echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
						
					{
						$esteCampo = "vigencia_disponibilidad";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 220;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = false;
						$atributos ['columnas'] = 2;
						$atributos ['tamanno'] = 1;
						$atributos ['ajax_function'] = "";
						$atributos ['ajax_control'] = $esteCampo;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = 1;
						$atributos ['anchoCaja'] = 27;
						$atributos ['miEvento'] = '';
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "vigencia_disponibilidad" );
						$matrizItems1 = array (
								array (
										'',
										'Seleccione ...'
								)
						);
						$matrizItems = $esteRecursoDBO->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
						$atributos ['matrizItems'] = array_merge($matrizItems1,$matrizItems);
						// $atributos['miniRegistro']=;
						$atributos ['baseDatos'] = "sicapital";
						// $atributos ['baseDatos'] = "inventarios";
				
						// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
				
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
				
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'diponibilidad';
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
				
						$atributos ['anchoEtiqueta'] = 180;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = false;
						$atributos ['columnas'] = 2;
						$atributos ['tamanno'] = 1;
						$atributos ['ajax_function'] = "";
						$atributos ['ajax_control'] = $esteCampo;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = 1;
						$atributos ['anchoCaja'] = 27;
						$atributos ['miEvento'] = '';
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscar_disponibilidad",$_REQUEST['vigencia_disponibilidad'] );
						$matrizItems = array (
								array (
										'',
										''
								)
						);
						$matrizItems = $esteRecursoDBO->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
						$atributos ['matrizItems'] = $matrizItems;
						// $atributos['miniRegistro']=;
						$atributos ['baseDatos'] = "sicapital";
						// $atributos ['baseDatos'] = "inventarios";
				
						// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
				
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
				
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'valor_disponibilidad';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'fecha';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = false;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = '';
				
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 27;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 220;
						$tab ++;
				
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
				
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
				
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'fecha_diponibilidad';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'fecha';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = false;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = '';
				
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 8;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 180;
						$tab ++;
				
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
				
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
				
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'valorLetras_disponibilidad';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 105;
						$atributos ['filas'] = 3;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						// $atributos ['validar'] = 'required, minSize[1]';
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 20;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 220;
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$tab ++;
				
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoTextArea ( $atributos );
						unset ( $atributos );
					}
						
					echo $this->miFormulario->agrupacion ( 'fin' );
						
					$esteCampo = "AgrupacionRegistroPresupuestal";
					$atributos ['id'] = $esteCampo;
					$atributos ['leyenda'] = "Certificado de Registro Presupuestal";
					echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
						
					{
				
						$esteCampo = 'vigencia_registro';
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 220;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = false;
						$atributos ['columnas'] = 2;
						$atributos ['tamanno'] = 1;
						$atributos ['ajax_function'] = "";
						$atributos ['ajax_control'] = $esteCampo;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = 1;
						$atributos ['anchoCaja'] = 27;
						$atributos ['miEvento'] = '';
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "vigencia_registro" );
						$matrizItems1 = array (
								array (
										'',
										'Seleccione ...'
								)
						);
						$matrizItems = $esteRecursoDBO->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
						$atributos ['matrizItems'] = array_merge($matrizItems1,$matrizItems);
						// $atributos['miniRegistro']=;
						$atributos ['baseDatos'] = "sicapital";
						// $atributos ['baseDatos'] = "inventarios";
				
						// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
				
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
				
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'registro';
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 180;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = false;
						$atributos ['columnas'] = 2;
						$atributos ['tamanno'] = 1;
						$atributos ['ajax_function'] = "";
						$atributos ['ajax_control'] = $esteCampo;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = 1;
						$atributos ['anchoCaja'] = 27;
						$atributos ['miEvento'] = '';
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscar_registro", $_REQUEST['vigencia_registro']);
						$matrizItems1 = array (
								array (
										'',
										'Seleccione ..'
								)
						);
						$matrizItems = $esteRecursoDBO->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
						$atributos ['matrizItems'] = $matrizItems;
						// $atributos['miniRegistro']=;
						$atributos ['baseDatos'] = "sicapital";
						// $atributos ['baseDatos'] = "inventarios";
				
						// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
				
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
				
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'valor_registro';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = '';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = false;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = '';
				
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 27;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 220;
						$atributos ['maximoTamanno'] = 10;
				
						$tab ++;
				
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
				
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
				
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'fecha_registro';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'fecha';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = false;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = '';
				
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 8;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 180;
						$tab ++;
				
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
				
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
				
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'valorL_registro';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = false;
						$atributos ['columnas'] = 105;
						$atributos ['filas'] = 3;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						// $atributos ['validar'] = 'required, minSize[1]';
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 20;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 220;
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$tab ++;
				
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoTextArea ( $atributos );
						unset ( $atributos );
					}
				}
				
				echo $this->miFormulario->agrupacion ( 'fin' );
			
				$esteCampo = "Encargados";
				$atributos ['id'] = $esteCampo;
				$atributos ['leyenda'] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
				{
					$esteCampo = "ordenadorGasto";
					$atributos ['id'] = $esteCampo;
					$atributos ['leyenda'] = $this->lenguaje->getCadena ( $esteCampo );
					echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
					{
						
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'asignacionOrdenador';
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 170;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = - 1;
						}
						$atributos ['deshabilitado'] = false;
						$atributos ['columnas'] = 2;
						$atributos ['tamanno'] = 1;
						$atributos ['ajax_function'] = "";
						$atributos ['ajax_control'] = $esteCampo;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = true;
						$atributos ['anchoCaja'] = 40;
						$atributos ['miEvento'] = '';
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "ordenador_gasto" );
						$matrizItems = array (
								array (
										0,
										' ' 
								) 
						);
						$matrizItems = $esteRecursoDBO->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
						$atributos ['matrizItems'] = $matrizItems;
						// $atributos['miniRegistro']=;
						$atributos ['baseDatos'] = "inventarios";
						// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'nombreOrdenador';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = false;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[2000]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = false;
						$atributos ['tamanno'] = 28;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 190;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
						
						$esteCampo = 'id_ordenador';
						$atributos ["id"] = $esteCampo; // No cambiar este nombre
						$atributos ["tipo"] = "hidden";
						$atributos ['estilo'] = '';
						$atributos ["obligatorio"] = false;
						$atributos ['marco'] = true;
						$atributos ["etiqueta"] = "";
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					}
					
					echo $this->miFormulario->agrupacion ( 'fin' );
					unset ( $atributos );
					
				
					
					$esteCampo = "contratista";
					$atributos ['id'] = $esteCampo;
					$atributos ['leyenda'] = $this->lenguaje->getCadena ( $esteCampo );
					echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
					{
						
						$esteCampo = "vigencia_contratista";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
							
						$atributos ['anchoEtiqueta'] = 295;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = false;
						$atributos ['columnas'] = 1;
						$atributos ['tamanno'] = 1;
						$atributos ['ajax_function'] = "";
						$atributos ['ajax_control'] = $esteCampo;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = 1;
						$atributos ['anchoCaja'] = 27;
						$atributos ['miEvento'] = '';
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "vigencia_contratista" );
						$matrizItems1 = array (
								array (
										'',
										'Seleccione ...'
								)
						);
						$matrizItems = $esteRecursoDBO->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
						$atributos ['matrizItems'] = array_merge($matrizItems1,$matrizItems);
						// $atributos['miniRegistro']=;
						$atributos ['baseDatos'] = "sicapital";
						// $atributos ['baseDatos'] = "inventarios";
							
						// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
							
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'nombreContratista';
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 295;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = - 1;
						}
						$atributos ['deshabilitado'] = false;
						$atributos ['columnas'] = 1;
						$atributos ['tamanno'] = 1;
						$atributos ['ajax_function'] = "";
						$atributos ['ajax_control'] = $esteCampo;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = true;
						$atributos ['anchoCaja'] = 50;
						$atributos ['miEvento'] = '';
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "constratistas" );
						$matrizItems = array (
								array (
										0,
										' ' 
								) 
						);
						$matrizItems = $esteRecursoDBO->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
						$atributos ['matrizItems'] = $matrizItems;
						// $atributos['miniRegistro']=;
						$atributos ['baseDatos'] = "inventarios";
						// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					}
					
					echo $this->miFormulario->agrupacion ( 'fin' );
					unset ( $atributos );
				}
				
				echo $this->miFormulario->agrupacion ( 'fin' );
				unset ( $atributos );
				
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				
				// -----------------CONTROL: Botón ----------------------------------------------------------------
				$esteCampo = 'botonAceptar';
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
				echo $this->miFormulario->campoBoton ( $atributos );
				// -----------------FIN CONTROL: Botón -----------------------------------------------------------
				
				echo $this->miFormulario->division ( 'fin' );
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
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
			
			$valorCodificado = "actionBloque=" . $esteBloque ["nombre"];
			$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=modificarOrden";
			$valorCodificado .= "&numero_orden=" . $_REQUEST ['numero_orden'];
			
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
$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
