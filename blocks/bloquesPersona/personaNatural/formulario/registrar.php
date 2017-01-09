<?php

namespace bloquesPersona\personaNatural\formulario;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
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
		$_REQUEST ['tiempo'] = time ();
		$tiempo = $_REQUEST ['tiempo'];
		
		$conexion = 'estructura';
		$primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		// var_dump($primerRecursoDB);
		// exit;
		
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
		$atributos ['titulo'] = false; // $this->lenguaje->getCadena ( $esteCampo );
		                               
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = true;
		$tab = 1;
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		
		// ---------------- SECCION: Controles del Formulario -----------------------------------------------
		
		// --------------------------------------------------------------------------------------------------
		
		$esteCampo = "marcoDatosBasicos";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = "Registro nueva persona";
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		// --------------------------------------------------------------------------------------------------
		
		$esteCampo = "novedadesIdentificacion";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		unset ( $atributos );
		{
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalIdentificacion';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['ajax_function'] = "";
			$atributos ['ajax_control'] = $esteCampo;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			// $atributos['cadena_sql'] = $this->miSql->getCadenaSql("buscarRegistro");
			// $matrizItems = $primerRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
			
			// var_dump($this->miSql->getCadenaSql("buscarRegistro"));
			
			$matrizItems = array (
					array (
							1,
							'Cédula de Ciudadanía' 
					),
					array (
							2,
							'Tarjeta de Identidad' 
					),
					array (
							3,
							'Cédula de extranjería' 
					),
					array (
							4,
							'Pasaporte' 
					),
					array (
							5,
							'Nit' 
					),
					array (
							6,
							'Registro Civil' 
					),
					array (
							7,
							'NUIP' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalDocumento';
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
			$atributos ['validar'] = 'required, minSize[1]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
			// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
			
			$esteCampo = 'nombresCampos';
			$atributos ['texto'] = ' ';
			$atributos ['estilo'] = 'text-success';
			$atributos ['etiqueta'] = "<h4>" . $this->lenguaje->getCadena ( $esteCampo ) . "</h4>";
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoTexto ( $atributos );
			
			// --------------------------------------------------------------------------------------------------
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalPrimerNombre';
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
			$atributos ['validar'] = 'required, minSize[1]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 80;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalSegundoNombre';
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
			// $atributos ['validar'] = 'required, minSize[1]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 80;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalPrimerApellido';
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
			$atributos ['validar'] = 'required, minSize[1]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 80;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalSegundoApellido';
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
			$atributos ['validar'] = 'required, minSize[1]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 80;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			unset ( $atributos );
		}
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		$esteCampo = "novedadesDatosPersonales";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		{
			// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
			
			$esteCampo = 'datosTributarios';
			$atributos ['texto'] = ' ';
			$atributos ['estilo'] = 'text-success';
			$atributos ['etiqueta'] = "<h4>" . $this->lenguaje->getCadena ( $esteCampo ) . "</h4>";
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoTexto ( $atributos );
			
			// --------------------------------------------------------------------------------------------------
			
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalContribuyente';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$matrizItems = array (
					array (
							1,
							'Si es gran Contribuyente' 
					),
					array (
							2,
							'No es gran Contribuyente' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalAutorretenedor1';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$matrizItems = array (
					array (
							1,
							'Autorretenedor' 
					),
					array (
							2,
							'No Autorretendedor' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			
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
			$esteCampo = 'personaNaturalRegimen1';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$matrizItems = array (
					array (
							1,
							'Común' 
					),
					array (
							2,
							'Simplificado' 
					),
					array (
							3,
							'No Aplica' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			
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
			
			$esteCampo = 'otrosDatos';
			$atributos ['texto'] = ' ';
			$atributos ['estilo'] = 'text-success';
			$atributos ['etiqueta'] = "<h4>" . $this->lenguaje->getCadena ( $esteCampo ) . "</h4>";
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoTexto ( $atributos );
			
			// --------------------------------------------------------------------------------------------------
			
			// // ---------------- CONTROL: Select --------------------------------------------------------
			// $esteCampo = 'personaNaturalProcedencia';
			// $atributos ['nombre'] = $esteCampo;
			// $atributos ['id'] = $esteCampo;
			// $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			// $atributos ['tab'] = $tab;
			// $atributos ['seleccion'] = - 1;
			// $atributos ['evento'] = ' ';
			// $atributos ['deshabilitado'] = false;
			// $atributos ['limitar'] = 50;
			// $atributos ['tamanno'] = 1;
			// $atributos ['columnas'] = 1;
			
			// $atributos ['obligatorio'] = true;
			// $atributos ['etiquetaObligatorio'] = true;
			// $atributos ['validar'] = 'required';
			
			// $matrizItems = array (
			// array (
			// 1,
			// 'Nacional'
			// ),
			// array (
			// 2,
			// 'Extranjero'
			// )
			// )
			// ;
			// $atributos ['matrizItems'] = $matrizItems;
			
			// if (isset ( $_REQUEST [$esteCampo] )) {
			// $atributos ['valor'] = $_REQUEST [$esteCampo];
			// } else {
			// $atributos ['valor'] = '';
			// }
			// $tab ++;
			
			// // Aplica atributos globales al control
			// $atributos = array_merge ( $atributos, $atributosGlobales );
			// echo $this->miFormulario->campoCuadroLista ( $atributos );
			// // --------------- FIN CONTROL : Select --------------------------------------------------
			unset ( $atributos );
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalPais';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
			$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalDepartamento';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = true;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
			$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalCiudad';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = true;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
			$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
			$atributos ['matrizItems'] = $matrizItems;
			
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
			
			// --------------- FIN CONTROL : Select --------------------------------------------------
			
			// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
			
			$esteCampo = 'infoComercial';
			$atributos ['texto'] = ' ';
			$atributos ['estilo'] = 'text-success';
			$atributos ['etiqueta'] = "<h4>" . $this->lenguaje->getCadena ( $esteCampo ) . "</h4>";
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoTexto ( $atributos );
			
			// --------------------------------------------------------------------------------------------------
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			// $esteCampo = 'personaNaturalConsecutivo';
			// $atributos ['id'] = $esteCampo;
			// $atributos ['nombre'] = $esteCampo;
			// $atributos ['tipo'] = 'text';
			// $atributos ['estilo'] = 'jqueryui';
			// $atributos ['marco'] = true;
			// $atributos ['columnas'] = 1;
			// $atributos ['dobleLinea'] = false;
			// $atributos ['tabIndex'] = $tab;
			// $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			
			// $atributos ['obligatorio'] = false;
			// $atributos ['etiquetaObligatorio'] = false;
			
			// if (isset ( $_REQUEST [$esteCampo] )) {
			// $atributos ['valor'] = $_REQUEST [$esteCampo];
			// } else {
			// $atributos ['valor'] = '';
			// }
			// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			// $atributos ['deshabilitado'] = true;
			// $atributos ['tamanno'] = 4;
			// $atributos ['maximoTamanno'] = '';
			// $tab ++;
			
			// // Aplica atributos globales al control
			// $atributos = array_merge ( $atributos, $atributosGlobales );
			// echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalBanco';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$matrizItems = array (
					array (
							1,
							'Banco de Bogota' 
					),
					array (
							2,
							'Banco Popular' 
					),
					array (
							3,
							'Bancolombia S.A.' 
					),
					array (
							4,
							'Citibank Colombia' 
					),
					array (
							5,
							'Banco GNB Colombia S.A.' 
					),
					array (
							6,
							'BBVA Colombia' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalTipoCuenta';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$matrizItems = array (
					array (
							1,
							'Ahorro' 
					),
					array (
							2,
							'Corriente' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalNumeroCuenta';
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
			$atributos ['validar'] = 'required, minSize[1], maxSize[29]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
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
			unset ( $atributos );
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalTipoPago';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$matrizItems = array (
					array (
							1,
							'Transferencia' 
					),
					array (
							2,
							'SAP' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalEconomicoEstado';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$matrizItems = array (
					array (
							1,
							'Activo' 
					),
					array (
							2,
							'Inactivo' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// --------------- FIN CONTROL : Select --------------------------------------------------
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'fechaCreacionConsulta1';
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
				$atributos ['valor'] = '';
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
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalCreo';
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
			$atributos ['validar'] = 'required, minSize[1],maxSize[49]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 80;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
		}
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		$esteCampo = "infoContactos";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		{
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			// $esteCampo = 'personaNaturalContactosConsecutivo';
			// $atributos ['id'] = $esteCampo;
			// $atributos ['nombre'] = $esteCampo;
			// $atributos ['tipo'] = 'text';
			// $atributos ['estilo'] = 'jqueryui';
			// $atributos ['marco'] = true;
			// $atributos ['columnas'] = 1;
			// $atributos ['dobleLinea'] = false;
			// $atributos ['tabIndex'] = $tab;
			// $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			
			// $atributos ['obligatorio'] = false;
			// $atributos ['etiquetaObligatorio'] = false;
			
			// if (isset ( $_REQUEST [$esteCampo] )) {
			// $atributos ['valor'] = $_REQUEST [$esteCampo];
			// } else {
			// $atributos ['valor'] = '';
			// }
			// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			// $atributos ['deshabilitado'] = true;
			// $atributos ['tamanno'] = 4;
			// $atributos ['maximoTamanno'] = '';
			// $tab ++;
			
			// // Aplica atributos globales al control
			// $atributos = array_merge ( $atributos, $atributosGlobales );
			// echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalContactoTipo';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$matrizItems = array (
					array (
							1,
							'Dirección' 
					),
					array (
							2,
							'Email' 
					),
					array (
							3,
							'Teléfono Fijo' 
					),
					array (
							4,
							'Teléfono móvil' 
					),
					array (
							5,
							'Fax' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalContactosDescrip';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['estilo'] = '';
			$atributos ['marco'] = false;
			$atributos ['columnas'] = 50;
			$atributos ['filas'] = 3;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			
			$atributos ['obligatorio'] = false;
			$atributos ['etiquetaObligatorio'] = false;
			$atributos ['validar'] = '';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoTextArea ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			unset ( $atributos );
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalContactosPais';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
			$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalContactosDepartamento';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = true;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
			$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalContactosCiudad';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = true;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
			$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalContactosIndicativo';
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
			$atributos ['validar'] = 'required, minSize[1]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = false;
			$atributos ['tamanno'] = 5;
			$atributos ['maximoTamanno'] = '';
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
			unset ( $atributos );
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'personaNaturalContactosEstado';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = 2;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$matrizItems = array (
					array (
							1,
							'Activa' 
					),
					array (
							2,
							'Inactiva' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			
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
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalContactosObserv';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['estilo'] = '';
			$atributos ['marco'] = false;
			$atributos ['columnas'] = 50;
			$atributos ['filas'] = 3;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			
			$atributos ['obligatorio'] = false;
			$atributos ['etiquetaObligatorio'] = false;
			$atributos ['validar'] = '';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoTextArea ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'fechaCreacionConsulta';
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
				$atributos ['valor'] = '';
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
			$esteCampo = 'personaNaturalContactosUsuarioCreo';
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
			$atributos ['validar'] = 'required, minSize[1], maxSize[49]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
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
			
			$esteCampo = 'infoEconomica';
			$atributos ['texto'] = ' ';
			$atributos ['estilo'] = 'text-success';
			$atributos ['etiqueta'] = "<h4>" . $this->lenguaje->getCadena ( $esteCampo ) . "</h4>";
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoTexto ( $atributos );
			
			// --------------------------------------------------------------------------------------------------
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			// $esteCampo = 'personaNaturalEconomicoConsecutivo';
			// $atributos ['id'] = $esteCampo;
			// $atributos ['nombre'] = $esteCampo;
			// $atributos ['tipo'] = 'text';
			// $atributos ['estilo'] = 'jqueryui';
			// $atributos ['marco'] = true;
			// $atributos ['columnas'] = 1;
			// $atributos ['dobleLinea'] = false;
			// $atributos ['tabIndex'] = $tab;
			// $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			
			// $atributos ['obligatorio'] = true;
			// $atributos ['etiquetaObligatorio'] = true;
			// // $atributos ['validar'] = 'required, minSize[1]';
			
			// if (isset ( $_REQUEST [$esteCampo] )) {
			// $atributos ['valor'] = $_REQUEST [$esteCampo];
			// } else {
			// $atributos ['valor'] = '';
			// }
			// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			// $atributos ['deshabilitado'] = false;
			// $atributos ['tamanno'] = 4;
			// $atributos ['maximoTamanno'] = '';
			// $tab ++;
			
			// // Aplica atributos globales al control
			// $atributos = array_merge ( $atributos, $atributosGlobales );
			// echo $this->miFormulario->campoCuadroTexto ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalEconomicoCodigo';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCodigo" );
			$matrizItems1 = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
			$atributos ['matrizItems'] = $matrizItems1;
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalEconomicoDescrip';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['estilo'] = '';
			$atributos ['marco'] = false;
			$atributos ['columnas'] = 50;
			$atributos ['filas'] = 3;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			
			$atributos ['obligatorio'] = false;
			$atributos ['etiquetaObligatorio'] = false;
			$atributos ['validar'] = '';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoTextArea ( $atributos );
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'fechaEconomicoInicio';
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
				$atributos ['valor'] = '';
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
			$esteCampo = 'fechaEconomicoFin';
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
				$atributos ['valor'] = '';
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
			$esteCampo = 'personaNaturalEconomicoEstado1';
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['tab'] = $tab;
			$atributos ['seleccion'] = - 1;
			$atributos ['evento'] = ' ';
			$atributos ['deshabilitado'] = false;
			$atributos ['limitar'] = 50;
			$atributos ['tamanno'] = 1;
			$atributos ['columnas'] = 1;
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			$matrizItems = array (
					array (
							1,
							'Activa' 
					),
					array (
							2,
							'Inactiva' 
					) 
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos ['ajax_function'] = "";
			$atributos ['ajax_control'] = $esteCampo;
			
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
			
			unset ( $atributos );
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'fechaEconomicoCreacion';
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
				$atributos ['valor'] = '';
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
			$esteCampo = 'personaNaturalEconomicoUsuarioCreo';
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
			$atributos ['validar'] = 'required, minSize[1]';
			
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
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
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		$esteCampo = "infoSoporte";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		{
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalSoporteIden';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'file';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['dobleLinea'] = false;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required, minSize[1]';
			
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
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'personaNaturalSoporteRUT';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'file';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['dobleLinea'] = false;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required, minSize[1]';
			
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
			
			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
		}
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botones";
		$atributos ["estilo"] = "marcoBotones";
		$atributos ["titulo"] = "Enviar Información";
		echo $this->miFormulario->division ( "inicio", $atributos );
		
		// -----------------CONTROL: Botón ----------------------------------------------------------------
		
		// -----------------FIN CONTROL: Botón -----------------------------------------------------------
		
		// ------------------Fin Division para los botones-------------------------
		
		// -----------------CONTROL: Botón ----------------------------------------------------------------
		$esteCampo = 'enviarRegistro';
		$atributos ["id"] = $esteCampo;
		$atributos ["tabIndex"] = $tab;
		$atributos ["tipo"] = 'boton';
		// submit: no se coloca si se desea un tipo button genérico
		$atributos ['submit'] = true;
		$atributos ["estiloMarco"] = '';
		$atributos ["estiloBoton"] = 'jqueryui';
		// verificar: true para verificar el formulario antes de pasarlo al servidor.
		$atributos ["verificar"] = true;
		$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
		$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['nombreFormulario'] = $esteBloque ['nombre'];
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoBoton ( $atributos );
		
		// -----------------FIN CONTROL: Botón -----------------------------------------------------------
		
		// ------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division ( "fin" );
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
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
		
		$valorCodificado = "actionBloque=" . $esteBloque ["nombre"]; // Ir pagina Funcionalidad
		$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' ); // Frontera mostrar formulario
		$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
		$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
		$valorCodificado .= "&opcion=mostrar";
		/**
		 * SARA permite que los nombres de los campos sean dinámicos.
		 * Para ello utiliza la hora en que es creado el formulario para
		 * codificar el nombre de cada campo.
		 */
		$valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
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