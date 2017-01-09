<?php
namespace bloquesNovedad\bloqueHojadeVida\bloqueConsultar\formulario;

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


class Formulario {

	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;

	function __construct($lenguaje, $formulario, $sql) {

		$this->miConfigurador = \Configurador::singleton ();

		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );

		$this->lenguaje = $lenguaje;

		$this->miFormulario = $formulario;

		$this->miSql = $sql;

	}

	function formulario() {

		/**
		 * IMPORTANTE: Este formulario está utilizando jquery.
		 * Por tanto en el archivo ready.php se delaran algunas funciones js
		 * que lo complementan.
		 */

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
		$_REQUEST['tiempo']=time();

		$conexion = 'estructura';
		$primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

		//var_dump($primerRecursoDB);
		//exit;

		// -------------------------------------------------------------------------------------------------

		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;

		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = '';

		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';

		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		$atributos ['titulo'] = false;
		//$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );

		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = false;
		$tab = 1;
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------

		// ---------------- INICIO: Lista Variables Control--------------------------------------------------------
		
		//Establecimiento Limite de Campos y Referencias Dinamicas **************************************************
		//***********************************************************************************************************
		$cantidad_referenciasLimite = 8;
		$cantidad_referencias_infoLimite = 20;
		$cantidad_idiomasLimite = 7;
		$cantidad_experienciaLimite = 10;
		$cantidad_referencias_perLimite = 20;
		
		//Para cambiar revisar el archivo ajax.php para ajustar los limites de los campos y las funciones AJAX
		//***********************************************************************************************************
		//***********************************************************************************************************
		
		// ---------------- FIN: Lista Variables Control--------------------------------------------------------
		
		
		
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		
		//---------------------------------------------------------------------------------------------------
		

		//************************************************************************************************************
		//************************************************************************************************************
			
		$cadenaSql1 = $this->miSql->getCadenaSql("buscarInfoIdent", $_REQUEST['funcionarioDocumentoBusqueda']);
		$matrizInfoExpe = $primerRecursoDB->ejecutarAcceso($cadenaSql1, "busqueda");
			
		//--var_dump($matrizInfoExpe[0][0]);//id datos de expedicion
		//--var_dump($matrizInfoExpe[0][1]);//id ubicacion
		//var_dump($matrizInfoExpe[0][2]);
		//var_dump($matrizInfoExpe[0][3]);
			
		$cadenaSql2 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizInfoExpe[0][1]);
		$matrizUbicacion = $primerRecursoDB->ejecutarAcceso($cadenaSql2, "busqueda");
			
		//--var_dump($matrizUbicacion[0][0]);//id pais
		//--var_dump($matrizUbicacion[0][1]);//id departamento
		//--var_dump($matrizUbicacion[0][2]);//id ciudad
		
		$cadenaSql3 = $this->miSql->getCadenaSql("consultarFuncionario", $_REQUEST['funcionarioDocumentoBusqueda']);
		$matrizFuncionario = $primerRecursoDB->ejecutarAcceso($cadenaSql3, "busqueda");
			
		//var_dump($matrizFuncionario[0][0]); //id funcionario
		//var_dump($matrizFuncionario[0][1]); //id datos de expedicion
		//var_dump($matrizFuncionario[0][2]); //id informacion personal
		//var_dump($matrizFuncionario[0][3]); //id datos residencia
		//var_dump($matrizFuncionario[0][4]); //id datos formacion funcionario
		//var_dump($matrizFuncionario[0][5]); //id publicacion
		
		
		$cadenaSql4 = $this->miSql->getCadenaSql("consultarInformacionPersonalBasica", $matrizFuncionario[0][2]);
		$matrizInfoPersonal = $primerRecursoDB->ejecutarAcceso($cadenaSql4, "busqueda");
		
		//var_dump($matrizInfoPersonal[0][1]);
		
		$cadenaSql5 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizInfoPersonal[0][1]);
		$matrizUbicacionInfoPer = $primerRecursoDB->ejecutarAcceso($cadenaSql5, "busqueda");
		
		//var_dump($matrizUbicacionInfoPer);
		
		$cadenaSql6 = $this->miSql->getCadenaSql("consultarDatosResidenciaCont", $matrizFuncionario[0][3]);
		$matrizInfoResidencia = $primerRecursoDB->ejecutarAcceso($cadenaSql6, "busqueda");
		
		$cadenaSql7 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizInfoResidencia[0][1]);
		$matrizUbicacionInfoRes = $primerRecursoDB->ejecutarAcceso($cadenaSql7, "busqueda");
		
		//var_dump($matrizInfoResidencia);
		
		$cadenaSql8 = $this->miSql->getCadenaSql("consultarFormacionAcademicaFuncionario", $matrizFuncionario[0][4]);
		$matrizFormacion = $primerRecursoDB->ejecutarAcceso($cadenaSql8, "busqueda");
		
		//var_dump($matrizFormacion[0][0]);//id formacion basica
		//var_dump($matrizFormacion[0][1]);//id formacion media
		
		$cadenaSql9 = $this->miSql->getCadenaSql("consultarFormacionBasica", $matrizFormacion[0][0]);
		$matrizFormacionBasica = $primerRecursoDB->ejecutarAcceso($cadenaSql9, "busqueda");
		
		$cadenaSql11 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizFormacionBasica[0][1]);
		$matrizUbicacionBasica = $primerRecursoDB->ejecutarAcceso($cadenaSql11, "busqueda");
		
		//var_dump($matrizFormacionBasica);
		//var_dump($matrizUbicacionBasica);
		//-----------------------------------------------------------------------------------------------
		
		$cadenaSql10 = $this->miSql->getCadenaSql("consultarFormacionMedia", $matrizFormacion[0][1]);
		$matrizFormacionMedia = $primerRecursoDB->ejecutarAcceso($cadenaSql10, "busqueda");
		
		$cadenaSql12 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizFormacionMedia[0][1]);
		$matrizUbicacionMedia = $primerRecursoDB->ejecutarAcceso($cadenaSql12, "busqueda");
		
		
		
		
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
		
		
		$cadenaSql13 = $this->miSql->getCadenaSql("consultarCantidadFormacionSuperior", $matrizFuncionario[0][4]);
		$matrizCantFormacionSuperior = $primerRecursoDB->ejecutarAcceso($cadenaSql13, "busqueda");
		if($matrizCantFormacionSuperior == null){
			$cantidad_referencias = 0;
		}else{
			$cantidad_referencias = count($matrizCantFormacionSuperior);
		}
		
		//var_dump($matrizCantFormacionSuperior); //Obtengo los id x cada formacion superior
		
		$cadenaSql14 = $this->miSql->getCadenaSql("consultarCantidadFormacionInformal", $matrizFuncionario[0][4]);
		$matrizCantFormacionInformal = $primerRecursoDB->ejecutarAcceso($cadenaSql14, "busqueda");
		if($matrizCantFormacionInformal == null){
			$cantidad_referencias_info = 0;
		}else{
			$cantidad_referencias_info = count($matrizCantFormacionInformal);
		}
		
		//var_dump(count($matrizCantFormacionInformal)); //Obtengo los id x cada formacion informal
		
		$cadenaSql15 = $this->miSql->getCadenaSql("consultarCantidadFormacionIdiomas", $matrizFuncionario[0][0]);
		$matrizCantFormacionIdioma = $primerRecursoDB->ejecutarAcceso($cadenaSql15, "busqueda");
		if($matrizCantFormacionIdioma == null){
			$cantidad_idiomas = 0;
		}else{
			$cantidad_idiomas = count($matrizCantFormacionIdioma);
		}
		
		//var_dump(count($matrizCantFormacionIdioma)); //Obtengo los id x cada formacion idioma
		//var_dump(array_reverse($matrizCantFormacionIdioma)); //array_reverse ordenar id
		
		$cadenaSql16 = $this->miSql->getCadenaSql("consultarCantidadExperiencia", $matrizFuncionario[0][0]);
		$matrizCantExperiencia = $primerRecursoDB->ejecutarAcceso($cadenaSql16, "busqueda");
		if($matrizCantExperiencia == null){
			$cantidad_experiencia = 0;
		}else{
			$cantidad_experiencia = count($matrizCantExperiencia);
		}
		
		//var_dump(count($matrizCantExperiencia)); //Obtengo los id x cada experiencia laboral
		//var_dump($matrizCantExperiencia); //array_reverse ordenar id
		
		$cadenaSql17 = $this->miSql->getCadenaSql("consultarCantidadReferencia", $matrizFuncionario[0][0]);
		$matrizCantReferencia = $primerRecursoDB->ejecutarAcceso($cadenaSql17, "busqueda");
		if($matrizCantReferencia == null){
			$cantidad_referencias_per = 0;
		}else{
			$cantidad_referencias_per = count($matrizCantReferencia);
		}
		
		//var_dump(count($matrizCantReferencia)); //Obtengo los id x cada experiencia laboral
		//var_dump(array_reverse($matrizCantReferencia)); //array_reverse ordenar id
		

		//--
		//************************************************************************************************************
		//************************************************************************************************************
		
		$cadenaSql18 = $this->miSql->getCadenaSql("consultarReferenciasPersonales", $matrizFuncionario[0][0]);
		$matrizReferencia = $primerRecursoDB->ejecutarAcceso($cadenaSql18, "busqueda");
		
		$cadenaSql19 = $this->miSql->getCadenaSql("consultarExperienciaLaboral", $matrizFuncionario[0][0]);
		$matrizExperiencia = $primerRecursoDB->ejecutarAcceso($cadenaSql19, "busqueda");
		
		//var_dump($matrizExperiencia);
		
		/*$count = 0;
		while($count < $cantidad_experiencia){
			 
			$cadenaSql20 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizExperiencia[$count][4]);
			$matrizUbicacionExpe = $primerRecursoDB->ejecutarAcceso($cadenaSql20, "busqueda");
			$count++;
	
		}*/
		
		$cadenaSql21 = $this->miSql->getCadenaSql("consultarFormacionIdiomas", $matrizFuncionario[0][0]);
		$matrizIdiomas = $primerRecursoDB->ejecutarAcceso($cadenaSql21, "busqueda");
		
		$cadenaSql22 = $this->miSql->getCadenaSql("consultarFormacionInformal", $matrizFuncionario[0][4]);
		$matrizInformal = $primerRecursoDB->ejecutarAcceso($cadenaSql22, "busqueda");
		
		$cadenaSql23 = $this->miSql->getCadenaSql("consultarFormacionSuperior", $matrizFuncionario[0][4]);
		$matrizSuperior = $primerRecursoDB->ejecutarAcceso($cadenaSql23, "busqueda");
		
		//var_dump($matrizSuperior);
		
		$cadenaSql24 = $this->miSql->getCadenaSql("consultarFormacionInvestigacion", $matrizFuncionario[0][5]);
		$matrizPublicacion = $primerRecursoDB->ejecutarAcceso($cadenaSql24, "busqueda");
		
		//var_dump($matrizSuperior);
		
		
		//************************************************************************************************************
		
		
		//---------------------------------------------------------------------------------------------------
		
		$esteCampo = "AgrupacionGeneral";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = "<center>MODIFICAR HOJA DE VIDA</center>";
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		{

		// ---------------- SECCION: Controles del Formulario -----------------------------------------------

			
			$esteCampo = "novedadesIdentificacion";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
				$atributos ["id"] = "botonDatos";
				$atributos ["estilo"] = "botonDatos";
				echo $this->miFormulario->division ( "inicio", $atributos );
				{
					echo "<button id=\"mostrarb1\" name=\"mas1\" ALIGN=RIGHT class=\"\">
	        			<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueFuncionario/css/images/show.png\" width=\"20\" height=\"20\">
	        		  </button>";
					echo "<button id=\"ocultarb1\" ALIGN=RIGHT name=\"menos1\" class=\"\">
	        			<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueFuncionario/css/images/hide.png\" width=\"20\" height=\"20\">
	        		  </button>";
				}
				echo $this->miFormulario->division ( "fin" );
				 
				$atributos ["id"] = "contentDatos1";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->agrupacion ( "inicio", $atributos );
				{
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioIdentificacion';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					 
					$cadenaSql = $this->miSql->getCadenaSql("buscarTipoDoc", $_REQUEST['funcionarioDocumentoBusqueda']);
					$matrizDoc = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
					 
					$atributos['seleccion'] = $matrizDoc[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = true;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['ajax_function'] = "";
					$atributos ['ajax_control'] = $esteCampo;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = '';
					 
					//var_dump($this->miSql->getCadenaSql("buscarRegistro"));
					 
					$matrizItems=array(
							array(1,'Cédula de Ciudadanía'),
							array(2,'Tarjeta de Identidad'),
							array(3,'Cédula de extranjería'),
							array(4,'Pasaporte')
					);
			
					$atributos['matrizItems'] = $matrizItems;
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
			
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioDocumento';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['estiloEtiqueta'] = 'labelTamano';
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, minSize[5], custom[onlyNumberSp]';
					 
					$atributos ['valor'] = $_REQUEST['funcionarioDocumentoBusqueda'];
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 15;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					unset($atributos);
					$esteCampo = 'funcionarioSoporteIden';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'hidden';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = '';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoExpe[0][3];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioFechaExpDocFunMod';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, custom[date]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoExpe[0][2];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 10;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'lugarExp';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
			
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioPais';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlM1 = $this->miSql->getCadenaSql ( "consultarPais", $matrizUbicacion[0][0] );
					$matrizM1 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlM1, "busqueda" );
					
					$atributos['seleccion'] = $matrizM1[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
			
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
					$atributos['matrizItems'] = $matrizItems;
			
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
			
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select ----------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioDepartamento';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlM1 = $this->miSql->getCadenaSql ( "consultarDepartamento",  $matrizUbicacion[0][1] );
					$matrizM1 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlM1, "busqueda" );
					
					$atributos['seleccion'] = $matrizM1[0][0];				
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamentoAjax", $matrizUbicacion[0][0] );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioCiudad';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlM1 = $this->miSql->getCadenaSql ( "consultarCiudad",  $matrizUbicacion[0][2] );
					$matrizM1 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlM1, "busqueda" );
					
					$atributos['seleccion'] = $matrizM1[0][0];	
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudadAjax", $matrizUbicacion[0][1] );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'nombresCampos';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
					 
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioPrimerApellido';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, minSize[1], custom[onlyLetterSp]';
					 
					$cadenaSql = $this->miSql->getCadenaSql("buscarPrimerApellido", $_REQUEST['funcionarioDocumentoBusqueda']);
					$matrizDoc = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
					 
					$atributos ['valor'] = $matrizDoc[0][0];
					 
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioSegundoApellido';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, minSize[1], custom[onlyLetterSp]';
					 
					$cadenaSql = $this->miSql->getCadenaSql("buscarSegundoApellido", $_REQUEST['funcionarioDocumentoBusqueda']);
					$matrizDoc = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
			
					$atributos ['valor'] = $matrizDoc[0][0];
					 
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioPrimerNombre';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, minSize[1], custom[onlyLetterSp]';
					 
					$cadenaSql = $this->miSql->getCadenaSql("buscarPrimerNombre", $_REQUEST['funcionarioDocumentoBusqueda']);
					$matrizDoc = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
			
					$atributos ['valor'] = $matrizDoc[0][0];
					 
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] =true;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioSegundoNombre';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[onlyLetterSp]';
					 
					$cadenaSql = $this->miSql->getCadenaSql("buscarSegundoNombre", $_REQUEST['funcionarioDocumentoBusqueda']);
					$matrizDoc = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
			
					$atributos ['valor'] = $matrizDoc[0][0];
					 
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioOtrosNombres';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
				}
				echo $this->miFormulario->agrupacion ( "fin" );
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
			
			$esteCampo = "novedadesDatosPersonales";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
				 
				$atributos ["id"] = "botonDatos";
				$atributos ["estilo"] = "botonDatos";
				echo $this->miFormulario->division ( "inicio", $atributos );
				{
					echo "<button id=\"mostrarb2\" name=\"mas1\" ALIGN=RIGHT class=\"\">
	        			<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueFuncionario/css/images/show.png\" width=\"20\" height=\"20\">
	        		  </button>";
					echo "<button id=\"ocultarb2\" ALIGN=RIGHT name=\"menos1\" class=\"\">
	        			<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueFuncionario/css/images/hide.png\" width=\"20\" height=\"20\">
	        		  </button>";
				}
				echo $this->miFormulario->division ( "fin" );
			
				$atributos ["id"] = "contentDatos2";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->agrupacion ( "inicio", $atributos );
				{
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					
		//*************************************************************************************************
		//*************************************************************************************************
					
					
					
					
					
					$esteCampo = 'novedadesDatosNacimiento';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
			
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioFechaNacimiento';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoPersonal[0][0];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 10;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioPaisNacimiento';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlM2 = $this->miSql->getCadenaSql ( "consultarPais",  $matrizUbicacionInfoPer[0][0] );
					$matrizM2 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlM2, "busqueda" );
						
					$atributos['seleccion'] = $matrizM2[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select ----------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioDepartamentoNacimiento';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlM2 = $this->miSql->getCadenaSql ( "consultarDepartamento",  $matrizUbicacionInfoPer[0][1] );
					$matrizM2 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlM2, "busqueda" );
					
					$atributos['seleccion'] = $matrizM2[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamentoAjax", $matrizUbicacionInfoPer[0][0] );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioCiudadNacimiento';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlM2 = $this->miSql->getCadenaSql ( "consultarCiudad",  $matrizUbicacionInfoPer[0][2] );
					$matrizM2 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlM2, "busqueda" );
						
					$atributos['seleccion'] = $matrizM2[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudadAjax", $matrizUbicacionInfoPer[0][1] );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'novedadesDatosGeneroEtc';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
			
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioGenero';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					$atributos['seleccion'] = '';
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					
					if(isset($matrizInfoPersonal[0][2])){
					
						if($matrizInfoPersonal[0][2] == 'Masculino'){
							$atributos['seleccion'] = 1;
						}else if($matrizInfoPersonal[0][2] == 'Femenino'){
							$atributos['seleccion'] = 2;
						}
					}
					 
					$matrizItems=array(
							array(1,'Masculino'),
							array(2,'Femenino')
				    
					);
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioEstadoCivil';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					$atributos['seleccion'] = '';
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					
					if(isset($matrizInfoPersonal[0][3])){
							
						if($matrizInfoPersonal[0][3] == 'Soltero'){
							$atributos['seleccion'] = 1;
						}else if($matrizInfoPersonal[0][3] == 'Casado'){
							$atributos['seleccion'] = 2;
						}else if($matrizInfoPersonal[0][3] == 'Union Libre'){
							$atributos['seleccion'] = 3;
						}else if($matrizInfoPersonal[0][3] == 'Viudo'){
							$atributos['seleccion'] = 4;
						}else if($matrizInfoPersonal[0][3] == 'Divorciado'){
							$atributos['seleccion'] = 5;
						}
					}
					
					$matrizItems=array(
							array(1,'Soltero'),
							array(2,'Casado'),
							array(3,'Unión Libre'),
							array(4,'Viudo'),
							array(5,'Divorciado')
				    
					);
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioEdad';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, minSize[2]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoPersonal[0][4];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 3;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioTipoSangre';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					$atributos['seleccion'] = -1;
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = ' ';
					
					if(isset($matrizInfoPersonal[0][5])){
							
						if($matrizInfoPersonal[0][5] == 'A'){
							$atributos['seleccion'] = 1;
						}else if($matrizInfoPersonal[0][5] == 'B'){
							$atributos['seleccion'] = 2;
						}else if($matrizInfoPersonal[0][5] == 'O'){
							$atributos['seleccion'] = 3;
						}else if($matrizInfoPersonal[0][5] == 'AB'){
							$atributos['seleccion'] = 4;
						}
					}
					
					$matrizItems=array(
							array(1,'A'),
							array(2,'B'),
							array(3,'O'),
							array(4,'AB')
					);
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioSangreRH';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					$atributos['seleccion'] = -1;
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = ' ';
					
					if(isset($matrizInfoPersonal[0][6])){
							
						if($matrizInfoPersonal[0][6] == 'Positivo'){
							$atributos['seleccion'] = 1;
						}else if($matrizInfoPersonal[0][6] == 'Negativo'){
							$atributos['seleccion'] = 2;
						}
					}
					
					$matrizItems=array(
							array(1,'Positivo'),
							array(2,'Negativo')
					);
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioTipoLibreta';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					$atributos['seleccion'] = -1;
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = true;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = ' ';
					
					if(isset($matrizInfoPersonal[0][7])){
							
						if($matrizInfoPersonal[0][7] == 'Primera'){
							$atributos['seleccion'] = 1;
						}else if($matrizInfoPersonal[0][7] == 'Segunda'){
							$atributos['seleccion'] = 2;
						}
					}
					
					$matrizItems=array(
							array(1,'Primera'),
							array(2,'Segunda')
				    
					);
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioNumeroLibreta';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[onlyNumberSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoPersonal[0][8];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 20;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioDistritoLibreta';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[onlyNumberSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoPersonal[0][9];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 2;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					unset($atributos);
					$esteCampo = 'funcionarioSoporteLibreta';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'hidden';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = '';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoPersonal[0][10];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'novedadesDatosCaracterizacion';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioGrupoEtnico';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['anchoEtiqueta'] = 300;
					$atributos['tab'] = $tab;
					$atributos['seleccion'] = -1;
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = ' ';
					
					if(isset($matrizInfoPersonal[0][11])){
							
						if($matrizInfoPersonal[0][11] == 'Afrodescendiente'){
							$atributos['seleccion'] = 1;
						}else if($matrizInfoPersonal[0][11] == 'Indigenas'){
							$atributos['seleccion'] = 2;
						}else if($matrizInfoPersonal[0][11] == 'Raizales'){
							$atributos['seleccion'] = 3;
						}else if($matrizInfoPersonal[0][11] == 'Rom'){
							$atributos['seleccion'] = 4;
						}
					}
					 
					$matrizItems=array(
							array(1,'Afrodescendientes'),
							array(2,'Indígenas'),
							array(3,'Raizales'),
							array(4,'Rom')
				    
					);
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioGrupoLGBT';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					$atributos['seleccion'] = -1;
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = ' ';
					
					if(isset($matrizInfoPersonal[0][12])){
							
						if($matrizInfoPersonal[0][12] == 't'){
							$atributos['seleccion'] = 1;
						}else if($matrizInfoPersonal[0][12] == 'f'){
							$atributos['seleccion'] = 2;
						}
					}
					
					$matrizItems=array(
							array(1,'Si'),
							array(2,'No')
					);
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioCabezaFamilia';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					$atributos['seleccion'] = -1;
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = ' ';
					
					if(isset($matrizInfoPersonal[0][13])){
							
						if($matrizInfoPersonal[0][13] == 't'){
							$atributos['seleccion'] = 1;
						}else if($matrizInfoPersonal[0][13] == 'f'){
							$atributos['seleccion'] = 2;
						}
					}
					 
					$matrizItems=array(
							array(1,'Si'),
							array(2,'No')
					);
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioPersonasCargo';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					$atributos['seleccion'] = -1;
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					
					if(isset($matrizInfoPersonal[0][14])){
							
						if($matrizInfoPersonal[0][14] == 't'){
							$atributos['seleccion'] = 1;
						}else if($matrizInfoPersonal[0][14] == 'f'){
							$atributos['seleccion'] = 2;
						}
					}
					
					$matrizItems=array(
							array(1,'Si'),
							array(2,'No')
					);
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					unset($atributos);
					$esteCampo = 'funcionarioSoporteCaracterizacion';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'hidden';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = '';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoPersonal[0][15];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
				}
				echo $this->miFormulario->agrupacion ( "fin" );
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
			$esteCampo = "novedadesDatosCiudadania";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
				 
				$atributos ["id"] = "botonDatos";
				$atributos ["estilo"] = "botonDatos";
				echo $this->miFormulario->division ( "inicio", $atributos );
				{
					echo "<button id=\"mostrarb3\" name=\"mas1\" ALIGN=RIGHT class=\"\">
	        			<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueFuncionario/css/images/show.png\" width=\"20\" height=\"20\">
	        		  </button>";
					echo "<button id=\"ocultarb3\" ALIGN=RIGHT name=\"menos1\" class=\"\">
	        			<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueFuncionario/css/images/hide.png\" width=\"20\" height=\"20\">
	        		  </button>";
				}
				echo $this->miFormulario->division ( "fin" );
			
				$atributos ["id"] = "contentDatos3";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->agrupacion ( "inicio", $atributos );
				{
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'novedadesDatosResidencia';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
			
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoNacionalidad';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, minSize[3], custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][0];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 20;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioContactoPais';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlM3 = $this->miSql->getCadenaSql ( "consultarPais", $matrizUbicacionInfoRes[0][0] );
					$matrizM3 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlM3, "busqueda" );
					
					$atributos['seleccion'] = $matrizM3[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
			
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
			
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais");
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
					$atributos['matrizItems'] = $matrizItems;
			
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
			
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select ----------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioContactoDepartamento';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSql = $this->miSql->getCadenaSql ( "consultarDepartamento", $matrizUbicacionInfoRes[0][1] );
					$matrizValor = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
					
					$atributos['seleccion'] = $matrizValor[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamentoAjax", $matrizUbicacionInfoRes[0][0] );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioContactoCiudad';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSql = $this->miSql->getCadenaSql ( "consultarCiudad", $matrizUbicacionInfoRes[0][2] );
					$matrizValor = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
					
					$atributos['seleccion'] = $matrizValor[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudadAjax", $matrizUbicacionInfoRes[0][1] );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoLocalidad';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, minSize[3], custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][2];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 20;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoBarrio';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, minSize[3], custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][3];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 20;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoDireccion';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, minSize[10]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][4];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 50;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioContactoEstrato';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					$atributos['seleccion'] = -1;
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['ajax_function'] = "";
					$atributos ['ajax_control'] = $esteCampo;
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] =false;
					$atributos ['validar'] = ' ';
					/*
					 $atributos['cadena_sql'] = $this->miSql->getCadenaSql("buscarRegistro");
					 $matrizItems = $primerRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");*/
					
					if(isset($matrizInfoResidencia[0][5])){
							
						if($matrizInfoResidencia[0][5] == 'Uno'){
							$atributos['seleccion'] = 1;
						}else if($matrizInfoResidencia[0][5] == 'Dos'){
							$atributos['seleccion'] = 2;
						}else if($matrizInfoResidencia[0][5] == 'Tres'){
							$atributos['seleccion'] = 3;
						}else if($matrizInfoResidencia[0][5] == 'Cuatro'){
							$atributos['seleccion'] = 4;
						}else if($matrizInfoResidencia[0][5] == 'Cinco'){
							$atributos['seleccion'] = 5;
						}else if($matrizInfoResidencia[0][5] == 'Seis'){
							$atributos['seleccion'] = 6;
						}
					}
					
					$matrizItems=array(
							array(1,'Uno'),
							array(2,'Dos'),
							array(3,'Tres'),
							array(4,'Cuatro'),
							array(5,'Cinco'),
							array(6,'Seis')
				    
					);
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					unset($atributos);
					$esteCampo = 'funcionarioSoporteEstrato';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'hidden';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = '';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][6];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					unset($atributos);
					$esteCampo = 'funcionarioSoporteResidencia';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'hidden';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = '';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][7];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoTelFijo';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, minSize[7], custom[phone]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][8];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 7;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoTelMovil';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required,  minSize[10], custom[phone]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][9];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 10;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoEmail';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required,  minSize[8], custom[email]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][10];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'novedadesDatosOrganizacion';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoOrganiTelOficina';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['anchoEtiqueta'] = 280;
					
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[phone]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][11];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 10;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoOrganiEmail';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['anchoEtiqueta'] = 280;
					
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[email]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][12];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoOrganiDireccion';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['anchoEtiqueta'] = 280;
					
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = ' ';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][13];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 50;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioContactoOrganiCargo';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['anchoEtiqueta'] = 280;
					
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizInfoResidencia[0][14];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 20;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
				}
				echo $this->miFormulario->agrupacion ( "fin" );
				 
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			//*************************************************************************************************************
			//*************************************************************************************************************
			$esteCampo = "novedadesDatosFormacionAcademica";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
				 
				$atributos ["id"] = "botonDatos";
				$atributos ["estilo"] = "botonDatos";
				echo $this->miFormulario->division ( "inicio", $atributos );
				{
					echo "<button id=\"mostrarb4\" name=\"mas1\" ALIGN=RIGHT class=\"\">
	        			<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueFuncionario/css/images/show.png\" width=\"20\" height=\"20\">
	        		  </button>";
					echo "<button id=\"ocultarb4\" ALIGN=RIGHT name=\"menos1\" class=\"\">
	        			<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueFuncionario/css/images/hide.png\" width=\"20\" height=\"20\">
	        		  </button>";
				}
				echo $this->miFormulario->division ( "fin" );
			
				$atributos ["id"] = "contentDatos4";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->agrupacion ( "inicio", $atributos );
				{
			
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'novedadesDatosFormacionBasica';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionBasicaModalidad';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizFormacionBasica[0][0];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 20;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionBasicaPais';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlF1 = $this->miSql->getCadenaSql ( "consultarPais", $matrizUbicacionBasica[0][0] );
					$matrizF1 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlF1, "busqueda" );
					
					$atributos['seleccion'] = $matrizF1[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
			
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
			
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
					$atributos['matrizItems'] = $matrizItems;
			
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
			
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select ----------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionBasicaDepartamento';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlF1 = $this->miSql->getCadenaSql ( "consultarDepartamento", $matrizUbicacionBasica[0][1] );
					$matrizF1 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlF1, "busqueda" );
					
					$atributos['seleccion'] = $matrizF1[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamentoAjax", $matrizUbicacionBasica[0][0] );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionBasicaCiudad';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlF1 = $this->miSql->getCadenaSql ( "consultarCiudad", $matrizUbicacionBasica[0][2] );
					$matrizF1 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlF1, "busqueda" );
						
					$atributos['seleccion'] = $matrizF1[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudadAjax", $matrizUbicacionBasica[0][1] );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionBasicaColegio';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizFormacionBasica[0][2];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 90;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionBasicaTitul';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizFormacionBasica[0][3];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 50;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioFechaFormacionBasica';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizFormacionBasica[0][4];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 10;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					unset($atributos);
					$esteCampo = 'funcionarioSoporteFormacionBasica';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'hidden';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = '';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizFormacionBasica[0][5];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'novedadesDatosFormacionMedia';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionMediaModalidad';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizFormacionMedia[0][0];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 20;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionMediaPais';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlF2 = $this->miSql->getCadenaSql ( "consultarPais", $matrizUbicacionMedia[0][0] );
					$matrizF2 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlF2, "busqueda" );
						
					$atributos['seleccion'] = $matrizF2[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
			
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
			
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
					$atributos['matrizItems'] = $matrizItems;
			
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
			
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select ----------------------------------------------------
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionMediaDepartamento';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlF2 = $this->miSql->getCadenaSql ( "consultarDepartamento", $matrizUbicacionMedia[0][1] );
					$matrizF2 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlF2, "busqueda" );
					
					$atributos['seleccion'] = $matrizF2[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamentoAjax", $matrizUbicacionMedia[0][0] );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					 
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionMediaCiudad';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$cadenaSqlF2 = $this->miSql->getCadenaSql ( "consultarCiudad", $matrizUbicacionMedia[0][2] );
					$matrizF2 = $primerRecursoDB->ejecutarAcceso ( $cadenaSqlF2, "busqueda" );
					
					$atributos['seleccion'] = $matrizF2[0][0];
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudadAjax", $matrizUbicacionMedia[0][1] );
					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					 
					$atributos['matrizItems'] = $matrizItems;
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionMediaColegio';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizFormacionMedia[0][2];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 90;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioFormacionMediaTitul';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required, custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizFormacionMedia[0][3];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 50;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioFechaFormacionMedia';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizFormacionMedia[0][4];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 10;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					unset($atributos);
					$esteCampo = 'funcionarioSoporteFormacionMedia';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'hidden';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					 
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = '';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizFormacionMedia[0][5];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
			
					$esteCampo = 'novedadesDatosFormacionSuperior';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
			
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
			
					// --------------------------------------------------------------------------------------------------
					 
					//***************************************************************************************************************
					//***************************************************************************************************************
					 
					//$cantidad_referencias = 3;//---------------------------------------------
					 
					for($i = 0; $i < $cantidad_referenciasLimite; $i++){
			
						 
						$esteCampo = "novedadesDatosCantidadEduacionSuperior_";
						$baseCampo = "novedadesDatosCantidadEduacionSuperior";
						$atributos ['id'] = $esteCampo.$i;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$numero_estudio = $i+1;
						$atributos ["leyenda"] = $this->lenguaje->getCadena ( $baseCampo ).$numero_estudio;
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						{
							if($i < $cantidad_referencias){
								unset($atributos);
								$atributos ["id"] = "botonDatos";
								$atributos ["estilo"] = "botonDatos";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									echo "<button id=\"btDelete_".$i."\" ALIGN=RIGHT onclick=\"seleccionDeleteSup('$i')\">
											<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueConsultar/css/images/deleteReg.png\" width=\"40\" height=\"40\">
	        		  					 </button>";
								}
								echo $this->miFormulario->division ( "fin" );
								
								// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
								$esteCampo = 'funcionarioFormacionSuperiorEliminar_'.$i;
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'hidden';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = false;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = '';
									
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = '';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = false;
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
								
							}else{
								// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
								$esteCampo = 'funcionarioFormacionSuperiorNuevo_'.$i;
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'hidden';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = false;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = '';
									
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = 'custom[onlyLetterSp]';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = true;
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							}

							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorModalidad_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorModalidad';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';

							
							if(isset($matrizSuperior[$i][1])){
									
								if($matrizSuperior[$i][1] == 'Tecnica'){
									$atributos['seleccion'] = 1;
								}else if($matrizSuperior[$i][1] == 'Tecnologica'){
									$atributos['seleccion'] = 2;
								}else if($matrizSuperior[$i][1] == 'Tecnologica Especializada'){
									$atributos['seleccion'] = 3;
								}else if($matrizSuperior[$i][1] == 'Universitaria'){
									$atributos['seleccion'] = 4;
								}else if($matrizSuperior[$i][1] == 'Especializacion'){
									$atributos['seleccion'] = 5;
								}else if($matrizSuperior[$i][1] == 'Maestria'){
									$atributos['seleccion'] = 6;
								}else if($matrizSuperior[$i][1] == 'Doctorado'){
									$atributos['seleccion'] = 7;
								}
							}
							 
							$matrizItems=array(
									array(1,'Técnica'),
									array(2,'Tecnológica'),
									array(3,'Tecnológica Especializada'),
									array(4,'Universitaria'),
									array(5,'Especialización'),
									array(6,'Maestría'),
									array(7,'Doctorado')
				      
							);
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							$caracteSelect = $atributos;
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorSemestres_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorSemestres';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, custom[onlyNumberSp]';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizSuperior[$i][2] )){
								$atributos ['valor'] = $matrizSuperior[$i][2];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 20;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							 
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorGraduado_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorGraduado';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							$atributos['tab'] = $tab;
						    $atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = '';
							
							if(isset($matrizSuperior[$i][3])){
									
								if($matrizSuperior[$i][3] == 't'){
									$atributos['seleccion'] = 1;
								}else if($matrizSuperior[$i][3] == 'f'){
									$atributos['seleccion'] = 2;
								}
							}
							
							$matrizItems=array(
									array(1,'Si'),
									array(2,'No')
				      
							);
							
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorPais_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorPais';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							$atributos['tab'] = $tab;
							
							if (isset ( $matrizSuperior[$i][4] )){
								$cadenaSql30 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizSuperior[$i][4]);
								$matrizUbicacionSuperior = $primerRecursoDB->ejecutarAcceso($cadenaSql30, "busqueda");
								$cadenaSql = $this->miSql->getCadenaSql ( "consultarPais", $matrizUbicacionSuperior[0][0] );
								$matrizSelect = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
									
								$atributos['seleccion'] = $matrizSelect[0][0];
							} else{
								$atributos['seleccion'] = -1;
							}
							
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							
							$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais");
							$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							
			
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
							 
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorDepartamento_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorDepartamento';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							$atributos['tab'] = $tab;
							
							if (isset ( $matrizSuperior[$i][4] )){
								$cadenaSql30 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizSuperior[$i][4]);
								$matrizUbicacionSuperior = $primerRecursoDB->ejecutarAcceso($cadenaSql30, "busqueda");
								$cadenaSql = $this->miSql->getCadenaSql ( "consultarDepartamento", $matrizUbicacionSuperior[0][1] );
								$matrizSelect = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
							
								$atributos['seleccion'] = $matrizSelect[0][0];
								$atributos['deshabilitado'] = false;
								
								$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamentoAjax", $matrizUbicacionSuperior[0][0]);
								$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							} else{
								$atributos['seleccion'] = -1;
								$atributos['deshabilitado'] = true;
								
								$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
	        					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							}

							$atributos['evento'] = ' ';
							
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
							 
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorCiudad_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorCiudad';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							$atributos['tab'] = $tab;
							
							if (isset ( $matrizSuperior[$i][4] )){
								$cadenaSql30 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizSuperior[$i][4]);
								$matrizUbicacionSuperior = $primerRecursoDB->ejecutarAcceso($cadenaSql30, "busqueda");
								$cadenaSql = $this->miSql->getCadenaSql ( "consultarCiudad", $matrizUbicacionSuperior[0][2] );
								$matrizSelect = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
							
								$atributos['seleccion'] = $matrizSelect[0][0];
								$atributos['deshabilitado'] = false;
							
								$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudadAjax", $matrizUbicacionSuperior[0][1]);
								$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
								
							} else{
								$atributos['seleccion'] = -1;
								$atributos['deshabilitado'] = true;
							
								$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
	        					$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							}
							
							
							$atributos['evento'] = ' ';
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
							 
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorResolucionConvali_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorResolucionConvali';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizSuperior[$i][5] )){
								$atributos ['valor'] = $matrizSuperior[$i][5];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 20;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFechaConvalidaSuperior_'.$i;
							$baseCampo = 'funcionarioFechaConvalidaSuperior';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[date]';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizSuperior[$i][6] )){
								$atributos ['valor'] = $matrizSuperior[$i][6];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorEntidadConvali_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorEntidadConvali';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizSuperior[$i][7] )){
								$atributos ['valor'] = $matrizSuperior[$i][7];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 20;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorUniversidad_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorUniversidad';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, custom[onlyLetterSp]';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizSuperior[$i][8] )){
								$atributos ['valor'] = $matrizSuperior[$i][8];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 80;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorTituloObtenido_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorTituloObtenido';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos ['anchoEtiqueta'] = 300;
							
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, custom[onlyLetterSp]';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizSuperior[$i][9] )){
								$atributos ['valor'] = $matrizSuperior[$i][9];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFechaTituloSuperior_'.$i;
							$baseCampo = 'funcionarioFechaTituloSuperior';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[date]';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizSuperior[$i][10] )){
								$atributos ['valor'] = $matrizSuperior[$i][10];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionSuperiorNumeroTarjeta_'.$i;
							$baseCampo = 'funcionarioFormacionSuperiorNumeroTarjeta';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizSuperior[$i][11] )){
								$atributos ['valor'] = $matrizSuperior[$i][11];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFechaTarjetaSuperior_'.$i;
							$baseCampo = 'funcionarioFechaTarjetaSuperior';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['anchoEtiqueta'] = 300;
							
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[date]';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizSuperior[$i][12] )){
								$atributos ['valor'] = $matrizSuperior[$i][12];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							unset($atributos);
							$esteCampo = 'funcionarioSoporteFormacionSuperior_'.$i;
							$baseCampo = 'funcionarioSoporteFormacionSuperior';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'hidden';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							 
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizSuperior[$i][13] )){
								$atributos ['valor'] = $matrizSuperior[$i][13];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
						}
						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
					}
					unset($atributos);
					$atributos ["id"] = "mainSuperior";
					$atributos ["estilo"] = "botonDinamico";
					echo $this->miFormulario->agrupacion ( "inicio", $atributos );
					{
						echo "<input type=\"button\" id=\"btAdd\" value=\"Agregar\" class=\"btn btn-success\"/> &nbsp;";
						echo "<input type=\"button\" id=\"btRemove\" value=\"Eliminar\" class=\"btn btn-danger\" /> &nbsp;";
					}
					echo $this->miFormulario->agrupacion ( "fin" );
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'novedadesDatosFormacionInformal';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
			
					//**************************************************************************************************************
					//**************************************************************************************************************
			
			
					//$cantidad_referencias_info = 4;//---------------------------------------------
			
					for($i = 0; $i < $cantidad_referencias_infoLimite; $i++){
						 
						 
						$esteCampo = "novedadesDatosCantidadEduacionInformal_";
						$baseCampo = "novedadesDatosCantidadEduacionInformal";
						$atributos ['id'] = $esteCampo.$i;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$numero_estudio = $i+1;
						$atributos ["leyenda"] = $this->lenguaje->getCadena ( $baseCampo ).$numero_estudio;
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						{
							
							if($i < $cantidad_referencias_info){
								unset($atributos);
								$atributos ["id"] = "botonDatos";
								$atributos ["estilo"] = "botonDatos";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									echo "<button id=\"btDeleteInf_".$i."\" ALIGN=RIGHT onclick=\"seleccionDeleteInf('$i')\">
											<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueConsultar/css/images/deleteReg.png\" width=\"40\" height=\"40\">
	        		  					 </button>";
								}
								echo $this->miFormulario->division ( "fin" );
								
								// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
								$esteCampo = 'funcionarioFormacionInformalEliminar_'.$i;
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'hidden';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = false;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = '';
									
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = '';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = false;
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
								
							}else{
								// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
								$esteCampo = 'funcionarioFormacionInformalNuevo_'.$i;
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'hidden';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = false;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = '';
									
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = 'custom[onlyLetterSp]';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = true;
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							}
							 
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionInformalCurso_'.$i;
							$baseCampo = 'funcionarioFormacionInformalCurso';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[onlyLetterSp]';
							
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizInformal[$i][1] )){
								$atributos ['valor'] = $matrizInformal[$i][1];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 100;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
			
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							 
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionInformalCursoLugar_'.$i;
							$baseCampo = 'funcionarioFormacionInformalCursoLugar';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							 
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[onlyLetterSp]';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizInformal[$i][2] )){
								$atributos ['valor'] = $matrizInformal[$i][2];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							 
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionInformalCursoIntensidad_'.$i;
							$baseCampo = 'funcionarioFormacionInformalCursoIntensidad';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[onlyNumberSp]';
			
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizInformal[$i][3] )){
								$atributos ['valor'] = $matrizInformal[$i][3];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
			
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							 
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFechaInformal_'.$i;
							$baseCampo = 'funcionarioFechaInformal';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[date]';
			
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizInformal[$i][4] )){
								$atributos ['valor'] = $matrizInformal[$i][4];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
			
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							 
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							unset($atributos);
							$esteCampo = 'funcionarioSoporteFormacionInformal_'.$i;
							$baseCampo = 'funcionarioSoporteFormacionInformal';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'hidden';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							//s$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
			
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizInformal[$i][5] )){
								$atributos ['valor'] = $matrizInformal[$i][5];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
			
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							 
							 
							 
						}
						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
					}
					unset($atributos);
					$atributos ["id"] = "mainInformal";
					$atributos ["estilo"] = "botonDinamico";
					echo $this->miFormulario->agrupacion ( "inicio", $atributos );
					{
						echo "<input type=\"button\" id=\"btAddIn\" value=\"Agregar\" class=\"btn btn-success\"/> &nbsp;";
						echo "<input type=\"button\" id=\"btRemoveIn\" value=\"Eliminar\" class=\"btn btn-danger\" /> &nbsp;";
					}
					echo $this->miFormulario->agrupacion ( "fin" );
					
//*******************************************************************************************************					
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
			
					$esteCampo = 'novedadesDatosFormacionIdiomas';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
			
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
			
					// --------------------------------------------------------------------------------------------------
					 
					 
					 
					 
					//$cantidad_idiomas = 3;//---------------------------------------------
					 
					for($i = 0; $i < $cantidad_idiomasLimite; $i++){
						 
						 
						$esteCampo = "novedadesDatosCantidadEduacionIdiomas_";
						$baseCampo = "novedadesDatosCantidadEduacionIdiomas";
						$atributos ['id'] = $esteCampo.$i;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$numero_estudio = $i+1;
						$atributos ["leyenda"] = $this->lenguaje->getCadena ( $baseCampo ).$numero_estudio;
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						{
							 
							if($i < $cantidad_idiomas){
								unset($atributos);
								$atributos ["id"] = "botonDatos";
								$atributos ["estilo"] = "botonDatos";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									echo "<button id=\"btDeleteIdi_".$i."\" ALIGN=RIGHT onclick=\"seleccionDeleteIdi('$i')\">
											<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueConsultar/css/images/deleteReg.png\" width=\"40\" height=\"40\">
	        		  					 </button>";
								}
								echo $this->miFormulario->division ( "fin" );
								
								// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
								$esteCampo = 'funcionarioFormacionIdiomasEliminar_'.$i;
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'hidden';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = false;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = '';
									
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = '';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = false;
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
								
							}else{
								// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
								$esteCampo = 'funcionarioFormacionIdiomasNuevo_'.$i;
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'hidden';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = false;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = '';
									
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = 'custom[onlyLetterSp]';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = true;
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							}
							
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionIdioma_'.$i;
							$baseCampo = 'funcionarioFormacionIdioma';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							 
							
							if(isset($matrizIdiomas[$i][1])){
									
								if($matrizIdiomas[$i][1] == 'Ingles'){
									$atributos['seleccion'] = 1;
								}else if($matrizIdiomas[$i][1] == 'Frances'){
									$atributos['seleccion'] = 2;
								}else if($matrizIdiomas[$i][1] == 'Aleman'){
									$atributos['seleccion'] = 3;
								}else if($matrizIdiomas[$i][1] == 'Portugues'){
									$atributos['seleccion'] = 4;
								}else if($matrizIdiomas[$i][1] == 'Italiano'){
									$atributos['seleccion'] = 5;
								}else if($matrizIdiomas[$i][1] == 'Mandarin'){
									$atributos['seleccion'] = 6;
								}else if($matrizIdiomas[$i][1] == 'Otro'){
									$atributos['seleccion'] = 7;
								}
							}
							
							$matrizItems=array(
									array(1,'Inglés'),
									array(2,'Francés'),
									array(3,'Alemán'),
									array(4,'Portugués'),
									array(5,'Italiano'),
									array(6,'Mandarín'),
									array(7,'Otro')
									 
							);
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionIdiomaUniversidad_'.$i;
							$baseCampo = 'funcionarioFormacionIdiomaUniversidad';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							 
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, custom[onlyLetterSp]';
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizIdiomas[$i][2] )){
								$atributos ['valor'] = $matrizIdiomas[$i][2];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 50;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionIdiomaNivel_'.$i;
							$baseCampo = 'funcionarioFormacionIdiomaNivel';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							 
							if(isset($matrizIdiomas[$i][3])){
									
								if($matrizIdiomas[$i][3] == '(A1) B\E1sico'){
									$atributos['seleccion'] = 1;
								}else if($matrizIdiomas[$i][3] == '(A2) Elemental'){
									$atributos['seleccion'] = 2;
								}else if($matrizIdiomas[$i][3] == '(B1) Pre-Intermedio'){
									$atributos['seleccion'] = 3;
								}else if($matrizIdiomas[$i][3] == '(B2) Intermedio Alto'){
									$atributos['seleccion'] = 4;
								}else if($matrizIdiomas[$i][3] == '(C1) Avanzado'){
									$atributos['seleccion'] = 5;
								}else if($matrizIdiomas[$i][3] == '(C2) Superior'){
									$atributos['seleccion'] = 6;
								}
							}
							
							$matrizItems=array(
									array(1,'(A1) Básico'),
									array(2,'(A2) Elemental'),
									array(3,'(B1) Pre-Intermedio'),
									array(4,'(B2) Intermedio Alto'),
									array(5,'(C1) Avanzado'),
									array(6,'(C2) Superior')
									 
							);
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionIdiomaNivelHabla_'.$i;
							$baseCampo = 'funcionarioFormacionIdiomaNivelHabla';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
							 
							if(isset($matrizIdiomas[$i][4])){
									
								if($matrizIdiomas[$i][4] == 'Aceptable'){
									$atributos['seleccion'] = 1;
								}else if($matrizIdiomas[$i][4] == 'Bueno'){
									$atributos['seleccion'] = 2;
								}else if($matrizIdiomas[$i][4] == 'Excelente'){
									$atributos['seleccion'] = 3;
								}
							}
							
							$matrizItems=array(
									array(1,'Aceptable'),
									array(2,'Bueno'),
									array(3,'Excelente')
									 
							);
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionIdiomaNivelLee_'.$i;
							$baseCampo = 'funcionarioFormacionIdiomaNivelLee';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
							
							if(isset($matrizIdiomas[$i][5])){
									
								if($matrizIdiomas[$i][5] == 'Aceptable'){
									$atributos['seleccion'] = 1;
								}else if($matrizIdiomas[$i][5] == 'Bueno'){
									$atributos['seleccion'] = 2;
								}else if($matrizIdiomas[$i][5] == 'Excelente'){
									$atributos['seleccion'] = 3;
								}
							}
							
							$matrizItems=array(
									array(1,'Aceptable'),
									array(2,'Bueno'),
									array(3,'Excelente')
									 
							);
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionIdiomaNivelEscribe_'.$i;
							$baseCampo = 'funcionarioFormacionIdiomaNivelEscribe';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
							 
							if(isset($matrizIdiomas[$i][6])){
									
								if($matrizIdiomas[$i][6] == 'Aceptable'){
									$atributos['seleccion'] = 1;
								}else if($matrizIdiomas[$i][6] == 'Bueno'){
									$atributos['seleccion'] = 2;
								}else if($matrizIdiomas[$i][6] == 'Excelente'){
									$atributos['seleccion'] = 3;
								}
							}
							
							$matrizItems=array(
									array(1,'Aceptable'),
									array(2,'Bueno'),
									array(3,'Excelente')
									 
							);
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioFormacionIdiomaNivelEscucha_'.$i;
							$baseCampo = 'funcionarioFormacionIdiomaNivelEscucha';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							 
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
							
							if(isset($matrizIdiomas[$i][7])){
									
								if($matrizIdiomas[$i][7] == 'Aceptable'){
									$atributos['seleccion'] = 1;
								}else if($matrizIdiomas[$i][7] == 'Bueno'){
									$atributos['seleccion'] = 2;
								}else if($matrizIdiomas[$i][7] == 'Excelente'){
									$atributos['seleccion'] = 3;
								}
							}
							
							$matrizItems=array(
									array(1,'Aceptable'),
									array(2,'Bueno'),
									array(3,'Excelente')
									 
							);
							$atributos['matrizItems'] = $matrizItems;
							 
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							 
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							unset($atributos);
							$esteCampo = 'funcionarioSoporteIdioma_'.$i;
							$baseCampo = 'funcionarioSoporteIdioma';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'hidden';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
			
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizIdiomas[$i][8] )){
								$atributos ['valor'] = $matrizIdiomas[$i][8];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
			
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioIdiomaObservacion_'.$i;
							$baseCampo = 'funcionarioIdiomaObservacion';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['estilo'] = '';
							$atributos ['marco'] = false;
							$atributos ['columnas'] = 50;
							$atributos ['filas'] = 3;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
			
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizIdiomas[$i][9] )){
								$atributos ['valor'] = $matrizIdiomas[$i][9];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$tab ++;
							$atributos ['deshabilitado'] = false;
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoTextArea ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
						}
						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
						 
					}
					unset($atributos);
					$atributos ["id"] = "mainIdioma";
					$atributos ["estilo"] = "botonDinamico";
					echo $this->miFormulario->agrupacion ( "inicio", $atributos );
					{
						echo "<input type=\"button\" id=\"btAddId\" value=\"Agregar\" class=\"btn btn-success\"/> &nbsp;";
						echo "<input type=\"button\" id=\"btRemoveId\" value=\"Eliminar\" class=\"btn btn-danger\" /> &nbsp;";
					}
					echo $this->miFormulario->agrupacion ( "fin" );
					 
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'novedadesDatosPublicaciones';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioPublicacionesTematica';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['estilo'] = '';
					$atributos ['marco'] = false;
					$atributos ['columnas'] = 50;
					$atributos ['filas'] = 3;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['anchoEtiqueta'] = 300;
					
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = '';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizPublicacion[0][0];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$tab ++;
					$atributos ['deshabilitado'] = false;
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTextArea ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioPublicacionesTipo';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['anchoEtiqueta'] = 300;
					
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[onlyLetterSp]';
			
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizPublicacion[0][1];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 50;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
			
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioPublicacionesLogros';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = false;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['anchoEtiqueta'] = 300;
					
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = 'custom[onlyLetterSp]';
					 
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizPublicacion[0][2];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 50;
					$atributos ['maximoTamanno'] = '';
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
					 
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'funcionarioPublicacionesReferencias';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['estilo'] = '';
					$atributos ['marco'] = false;
					$atributos ['columnas'] = 50;
					$atributos ['filas'] = 3;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['anchoEtiqueta'] = 300;
					
					$atributos ['obligatorio'] = false;
					$atributos ['etiquetaObligatorio'] = false;
					$atributos ['validar'] = '';
			
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = $matrizPublicacion[0][3];
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$tab ++;
					$atributos ['deshabilitado'] = false;
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTextArea ( $atributos );
					// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
					 
					 
					//echo "<a href=\"#\" id=\"mascampos\">Más campos</a>"; ----Mirar para Campos dinamicos
				}
				echo $this->miFormulario->agrupacion ( "fin" );
				 
				 
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			 
			//***************************************************************************************************************
			//***************************************************************************************************************
			
			$esteCampo = "novedadesDatosExperiencia";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
				 
				$atributos ["id"] = "botonDatos";
				$atributos ["estilo"] = "botonDatos";
				echo $this->miFormulario->division ( "inicio", $atributos );
				{
					echo "<button id=\"mostrarb5\" name=\"mas1\" ALIGN=RIGHT class=\"\">
	        			<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueFuncionario/css/images/show.png\" width=\"20\" height=\"20\">
	        		  </button>";
					echo "<button id=\"ocultarb5\" ALIGN=RIGHT name=\"menos1\" class=\"\">
	        			<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueFuncionario/css/images/hide.png\" width=\"20\" height=\"20\">
	        		  </button>";
				}
				echo $this->miFormulario->division ( "fin" );
			
				$atributos ["id"] = "contentDatos5";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->agrupacion ( "inicio", $atributos );
				{
			
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'novedadesDatosExperienciaLaboral';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
					 
					//$cantidad_experiencia = 3;//---------------------------------------------
					 
					for($i = 0; $i < $cantidad_experienciaLimite; $i++){
			
			
						$esteCampo = "novedadesDatosCantidadExperiencia_";
						$baseCampo = "novedadesDatosCantidadExperiencia";
						$atributos ['id'] = $esteCampo.$i;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$numero_estudio = $i+1;
						$atributos ["leyenda"] = $this->lenguaje->getCadena ( $baseCampo ).$numero_estudio;
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						{
							
							if($i < $cantidad_experiencia){
								unset($atributos);
								$atributos ["id"] = "botonDatos";
								$atributos ["estilo"] = "botonDatos";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									echo "<button id=\"btDeleteExp_".$i."\" ALIGN=RIGHT onclick=\"seleccionDeleteExp('$i')\">
											<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueConsultar/css/images/deleteReg.png\" width=\"40\" height=\"40\">
	        		  					 </button>";
								}
								echo $this->miFormulario->division ( "fin" );
								
								// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
								$esteCampo = 'funcionarioExperienciaEliminar_'.$i;
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'hidden';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = false;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = '';
									
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = '';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = false;
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
								
							}else{
								// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
								$esteCampo = 'funcionarioExperienciaNuevo_'.$i;
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'hidden';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = false;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = '';
									
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = 'custom[onlyLetterSp]';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = true;
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							}
				    
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaEmpresa_'.$i;
							$baseCampo = 'funcionarioExperienciaEmpresa';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, custom[onlyLetterSp]';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizExperiencia[$i][1] )){
								$atributos ['valor'] = $matrizExperiencia[$i][1];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 100;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaEmpresaNIT_'.$i;
							$baseCampo = 'funcionarioExperienciaEmpresaNIT';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[onlyNumberSp]';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizExperiencia[$i][2] )){
								$atributos ['valor'] = $matrizExperiencia[$i][2];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 15;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaTipo_'.$i;
							$baseCampo = 'funcionarioExperienciaTipo';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
				    
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
				    		
							
							if(isset($matrizExperiencia[$i][3])){
									
								if($matrizExperiencia[$i][3] == 'Publica'){
									$atributos['seleccion'] = 1;
								}else if($matrizExperiencia[$i][3] == 'Privada'){
									$atributos['seleccion'] = 2;
								}
							}
							
							$matrizItems=array(
									array(1,'Pública'),
									array(2,'Privada')
										
							);
							$atributos['matrizItems'] = $matrizItems;
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
				    
							// Aplica atributos globales al control select
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaPais_'.$i;
							$baseCampo = 'funcionarioExperienciaPais';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							
							if (isset ( $matrizExperiencia[$i][4] )){
								$cadenaSql30 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizExperiencia[$i][4]);
								$matrizUbicacionExpe = $primerRecursoDB->ejecutarAcceso($cadenaSql30, "busqueda");
								$cadenaSql = $this->miSql->getCadenaSql ( "consultarPais", $matrizUbicacionExpe[0][0] );
								$matrizSelect = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
									
								$atributos['seleccion'] = $matrizSelect[0][0];
							} else{
								$atributos['seleccion'] = -1;
							}

							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
				    
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							
							$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais");
							$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							
							$atributos['matrizItems'] = $matrizItems;
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
				    
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaDepartamento_'.$i;
							$baseCampo = 'funcionarioExperienciaDepartamento';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							
							if (isset ( $matrizExperiencia[$i][4] )){
								$cadenaSql30 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizExperiencia[$i][4]);
								$matrizUbicacionExpe = $primerRecursoDB->ejecutarAcceso($cadenaSql30, "busqueda");
								$cadenaSql = $this->miSql->getCadenaSql ( "consultarDepartamento", $matrizUbicacionExpe[0][1] );
								$matrizSelect = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
									
								$atributos['seleccion'] = $matrizSelect[0][0];
								$atributos['deshabilitado'] = false;
							
								$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamentoAjax", $matrizUbicacionExpe[0][0]);
								$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							} else{
								$atributos['seleccion'] = -1;
								$atributos['deshabilitado'] = true;
							
								$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
								$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							}
							
							$atributos['evento'] = ' ';
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
				    
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							
							$atributos['matrizItems'] = $matrizItems;
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
				    
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaCiudad_'.$i;
							$baseCampo = 'funcionarioExperienciaCiudad';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							
							if (isset ( $matrizExperiencia[$i][4] )){
								$cadenaSql30 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizExperiencia[$i][4]);
								$matrizUbicacionExpe = $primerRecursoDB->ejecutarAcceso($cadenaSql30, "busqueda");
								$cadenaSql = $this->miSql->getCadenaSql ( "consultarCiudad", $matrizUbicacionExpe[0][2] );
								$matrizSelect = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
									
								$atributos['seleccion'] = $matrizSelect[0][0];
								$atributos['deshabilitado'] = false;
									
								$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudadAjax", $matrizUbicacionExpe[0][1]);
								$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							} else{
								$atributos['seleccion'] = -1;
								$atributos['deshabilitado'] = true;
									
								$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
								$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							}
						
							$atributos['evento'] = ' ';
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
				    
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';

							
							$atributos['matrizItems'] = $matrizItems;
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaEmpresaCorreo_'.$i;
							$baseCampo = 'funcionarioExperienciaEmpresaCorreo';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, custom[email]';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizExperiencia[$i][5] )){
								$atributos ['valor'] = $matrizExperiencia[$i][5];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 50;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaEmpresaTelefono_'.$i;
							$baseCampo = 'funcionarioExperienciaEmpresaTelefono';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, custom[phone]';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizExperiencia[$i][6] )){
								$atributos ['valor'] = $matrizExperiencia[$i][6];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFechaEntradaExperiencia_'.$i;
							$baseCampo = 'funcionarioFechaEntradaExperiencia';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, custom[date]';
			
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizExperiencia[$i][7] )){
								$atributos ['valor'] = $matrizExperiencia[$i][7];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
			
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioFechaSalidaExperiencia_'.$i;
							$baseCampo = 'funcionarioFechaSalidaExperiencia';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, custom[date]';
			
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizExperiencia[$i][8] )){
								$atributos ['valor'] = $matrizExperiencia[$i][8];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
			
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaEmpresaDependencia_'.$i;
							$baseCampo = 'funcionarioExperienciaEmpresaDependencia';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = ' ';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizExperiencia[$i][9] )){
								$atributos ['valor'] = $matrizExperiencia[$i][9];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 20;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaEmpresaCargo_'.$i;
							$baseCampo = 'funcionarioExperienciaEmpresaCargo';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, custom[onlyLetterSp]';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizExperiencia[$i][10] )){
								$atributos ['valor'] = $matrizExperiencia[$i][10];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioExperienciaEmpresaHoras_'.$i;
							$baseCampo = 'funcionarioExperienciaEmpresaHoras';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[onlyNumberSp]';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizExperiencia[$i][11] )){
								$atributos ['valor'] = $matrizExperiencia[$i][11];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							unset($atributos);
							$esteCampo = 'funcionarioSoporteExperiencia_'.$i;
							$baseCampo = 'funcionarioSoporteExperiencia';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'hidden';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
			
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizExperiencia[$i][12] )){
								$atributos ['valor'] = $matrizExperiencia[$i][12];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 150;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
			
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
			
						}
						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
					}
					unset($atributos);
					$atributos ["id"] = "mainExperiencia";
					$atributos ["estilo"] = "botonDinamico";
					echo $this->miFormulario->agrupacion ( "inicio", $atributos );
					{
						echo "<input type=\"button\" id=\"btAddEx\" value=\"Agregar\" class=\"btn btn-success\"/> &nbsp;";
						echo "<input type=\"button\" id=\"btRemoveEx\" value=\"Eliminar\" class=\"btn btn-danger\" /> &nbsp;";
					}
					echo $this->miFormulario->agrupacion ( "fin" );
			
//*******************************************************************************************************					
			
					// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
					 
					$esteCampo = 'novedadesDatosReferenciaLaboral';
					$atributos['texto'] = ' ';
					$atributos['estilo'] = 'text-success';
					$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
					$tab ++;
					 
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTexto( $atributos );
					 
					// --------------------------------------------------------------------------------------------------
					 
					//$cantidad_referencias_per = 4;//---------------------------------------------
					 
					for($i = 0; $i < $cantidad_referencias_perLimite; $i++){
			
			
						$esteCampo = "novedadesDatosCantidadReferencia_";
						$baseCampo = "novedadesDatosCantidadReferencia";
						$atributos ['id'] = $esteCampo.$i;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$numero_estudio = $i+1;
						$atributos ["leyenda"] = $this->lenguaje->getCadena ( $baseCampo ).$numero_estudio;
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						{
							
							if($i < $cantidad_referencias_per){
								unset($atributos);
								$atributos ["id"] = "botonDatos";
								$atributos ["estilo"] = "botonDatos";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									echo "<button id=\"btDeleteRef_".$i."\" ALIGN=RIGHT onclick=\"seleccionDeleteRef('$i')\">
											<input type=image src=\"/jano/blocks/bloquesNovedad/bloqueHojadeVida/bloqueConsultar/css/images/deleteReg.png\" width=\"40\" height=\"40\">
	        		  					 </button>";
								}
								echo $this->miFormulario->division ( "fin" );
								
								// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
								$esteCampo = 'funcionarioReferenciasEliminar_'.$i;
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'hidden';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = false;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = '';
									
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = '';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = false;
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
												
							}else{
								// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
								$esteCampo = 'funcionarioReferenciasNuevo_'.$i;
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'hidden';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = false;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = '';
									
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = 'custom[onlyLetterSp]';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = true;
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
							}
			
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'funcionarioReferenciaTipo_'.$i;
							$baseCampo = 'funcionarioReferenciaTipo';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
				    
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = ' ';
							
							if(isset($matrizReferencia[$i][1])){
							
								if($matrizReferencia[$i][1] == 'Personal'){
									$atributos['seleccion'] = 1;
								}else if(($matrizReferencia[$i][1] == 'Profesional')){
									$atributos['seleccion'] = 2;
								}
							}
				    
							$matrizItems=array(
									array(1,'Personal'),
									array(2,'Profesional')
										
							);
							$atributos['matrizItems'] = $matrizItems;
							
							
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioReferenciaNombres_'.$i;
							$baseCampo = 'funcionarioReferenciaNombres';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[onlyLetterSp]';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizReferencia[$i][2] )){
								$atributos ['valor'] = $matrizReferencia[$i][2];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 50;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioReferenciaApellidos_'.$i;
							$baseCampo = 'funcionarioReferenciaApellidos';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[onlyLetterSp]';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizReferencia[$i][3] )){
								$atributos ['valor'] = $matrizReferencia[$i][3];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 50;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioReferenciaTelefono_'.$i;
							$baseCampo = 'funcionarioReferenciaTelefono';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[phone]';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizReferencia[$i][4] )){
								$atributos ['valor'] = $matrizReferencia[$i][4];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'funcionarioReferenciaRelacion_'.$i;
							$baseCampo = 'funcionarioReferenciaRelacion';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
				    
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = 'custom[onlyLetterSp]';
				    
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizReferencia[$i][5] )){
								$atributos ['valor'] = $matrizReferencia[$i][5];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 20;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
				    
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							unset($atributos);
							$esteCampo = 'funcionarioSoporteReferencia_'.$i;
							$baseCampo = 'funcionarioSoporteReferencia';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'hidden';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['dobleLinea'] = false;
							$atributos ['tabIndex'] = $tab;
							//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			
							$atributos ['obligatorio'] = false;
							$atributos ['etiquetaObligatorio'] = false;
							$atributos ['validar'] = '';
			
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else if (isset ( $matrizReferencia[$i][6] )){
								$atributos ['valor'] = $matrizReferencia[$i][6];
							} else{
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 150;
							$atributos ['maximoTamanno'] = '';
							$tab ++;
			
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
						}
						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
					}
					unset($atributos);
					$atributos ["id"] = "mainReferencias";
					$atributos ["estilo"] = "botonDinamico";
					echo $this->miFormulario->agrupacion ( "inicio", $atributos );
					{
						echo "<input type=\"button\" id=\"btAddRe\" value=\"Agregar\" class=\"btn btn-success\"/> &nbsp;";
						echo "<input type=\"button\" id=\"btRemoveRe\" value=\"Eliminar\" class=\"btn btn-danger\" /> &nbsp;";
					}
					echo $this->miFormulario->agrupacion ( "fin" );
			
				}
				echo $this->miFormulario->agrupacion ( "fin" );
			
				 
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
			
//*************************************************************************************************************
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'funcionarioRegistrosSuperior';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'hidden';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['dobleLinea'] = false;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = '';
			
			$atributos ['obligatorio'] = false;
			$atributos ['etiquetaObligatorio'] = false;
			$atributos ['validar'] = 'custom[onlyLetterSp]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = $cantidad_referencias;
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 30;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			 
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'funcionarioRegistrosInformal';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'hidden';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['dobleLinea'] = false;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = '';
			 
			$atributos ['obligatorio'] = false;
			$atributos ['etiquetaObligatorio'] = false;
			$atributos ['validar'] = 'custom[onlyLetterSp]';
			 
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = $cantidad_referencias_info;
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 30;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			 
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			 
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'funcionarioRegistrosIdioma';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'hidden';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['dobleLinea'] = false;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = '';
			 
			$atributos ['obligatorio'] = false;
			$atributos ['etiquetaObligatorio'] = false;
			$atributos ['validar'] = 'custom[onlyLetterSp]';
			 
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = $cantidad_idiomas;
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 30;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			 
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			 
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'funcionarioRegistrosExperiencia';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'hidden';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['dobleLinea'] = false;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = '';
			 
			$atributos ['obligatorio'] = false;
			$atributos ['etiquetaObligatorio'] = false;
			$atributos ['validar'] = 'custom[onlyLetterSp]';
			 
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = $cantidad_experiencia;
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 30;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			 
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			 
			 
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'funcionarioRegistrosReferencia';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'hidden';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['dobleLinea'] = false;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = '';
			
			$atributos ['obligatorio'] = false;
			$atributos ['etiquetaObligatorio'] = false;
			$atributos ['validar'] = 'custom[onlyLetterSp]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = $cantidad_referencias_per;
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 30;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
//*********************************************************************************************************

		}
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		

		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botonesUsuario";
		$atributos ["estilo"] = "marcoBotones";
		$atributos ["titulo"] = "Entrar a Registro";
		echo $this->miFormulario->division ( "inicio", $atributos );

		// -----------------CONTROL: Botón ----------------------------------------------------------------
		$esteCampo = 'botonModificar';
		$atributos ["id"] = $esteCampo;
		$atributos ["tabIndex"] = $tab;
		$atributos ["tipo"] = 'boton';
		// submit: no se coloca si se desea un tipo button genérico
		$atributos ['submit'] = '';//true;
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

		$valorCodificado = "actionBloque=" . $esteBloque ["nombre"]; //Ir pagina Funcionalidad
		$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );//Frontera mostrar formulario
		$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
		$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
		$valorCodificado .= "&opcion=modificar"; //Opcion para Switch Case ------
		/**
		* SARA permite que los nombres de los campos sean dinámicos.
		* Para ello utiliza la hora en que es creado el formulario para
		* codificar el nombre de cada campo.
		*/
		$valorCodificado .= "&campoSeguro=" . $_REQUEST['tiempo'];
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

		// ----------------FIN SECCION: Paso de variables -------------------------------------------------

		// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------

		// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
		// Se debe declarar el mismo atributo de marco con que se inició el formulario.
		$atributos ['marco'] = true;
		$atributos ['tipoEtiqueta'] = 'fin';
		echo $this->miFormulario->formulario ( $atributos );

		return true;

	}

	function mensaje() {

		// Si existe algun tipo de error en el login aparece el siguiente mensaje
		$mensaje = $this->miConfigurador->getVariableConfiguracion ( 'mostrarMensaje' );
		$this->miConfigurador->setVariableConfiguracion ( 'mostrarMensaje', null );

		if ($mensaje) {

			$tipoMensaje = $this->miConfigurador->getVariableConfiguracion ( 'tipoMensaje' );

			if ($tipoMensaje == 'json') {

				$atributos ['mensaje'] = $mensaje;
				$atributos ['json'] = true;
			} else {
				$atributos ['mensaje'] = $this->lenguaje->getCadena ( $mensaje );
			}
			// -------------Control texto-----------------------
			$esteCampo = 'divMensaje';
			$atributos ['id'] = $esteCampo;
			$atributos ["tamanno"] = '';
			$atributos ["estilo"] = 'information';
			$atributos ["etiqueta"] = '';
			$atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
			echo $this->miFormulario->campoMensaje ( $atributos );
			unset ( $atributos );


		}

		return true;

	}
}

$miFormulario = new Formulario ( $this->lenguaje, $this->miFormulario, $this->sql );


$miFormulario->formulario ();
$miFormulario->mensaje ();

?>
