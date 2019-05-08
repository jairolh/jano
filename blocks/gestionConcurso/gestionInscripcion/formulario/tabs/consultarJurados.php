<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use gestionConcurso\gestionInscripcion\funcion\redireccion;

class consultarJurado {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	var $miSesion;
  var $rutaSoporte;

	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();

		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );

		$this->lenguaje = $lenguaje;

		$this->miFormulario = $formulario;

		$this->miSql = $sql;

		$this->miSesion = \Sesion::singleton();

	}

	function miForm() {

		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		$this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "raizSoportes" );
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

								$parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso']);
								$cadena_sql = $this->miSql->getCadenaSql("consultarInscritoConcurso", $parametro);
								$resultadoListaInscrito = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre']."Jurados";
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


			$esteCampo = "marcoListaJurados";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{

				$cadena_sql = $this->miSql->getCadenaSql("consultarJurados");
				$resultadoJurado= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

					if($resultadoJurado){

								 //-----------------Inicio de Conjunto de Controles----------------------------------------
										 $esteCampo = "marcoConsultaInscrito";
										 $atributos["estilo"] = "jqueryui";
										 $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
										 //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
										 unset($atributos);
										 $tab=1;

										 // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
										 $esteCampo = 'seleccionJurado';
										 $atributos ['columnas'] = 1;
										 $atributos ['nombre'] = $esteCampo;
										 $atributos ['id'] = $esteCampo;
										 $atributos ['evento'] = '';
										 $atributos ['deshabilitado'] = false;
										 $atributos ["etiquetaObligatorio"] = true;
										 $atributos ['tab'] = $tab;
										 $atributos ['tamanno'] = 1;
										 $atributos ['estilo'] = 'jqueryui';
										 $atributos ['validar'] = 'required';
										 $atributos ['limitar'] = true;
										 $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
										 $atributos ['anchoEtiqueta'] = 170;
										 $atributos ['anchoCaja'] = 60;
										 if (isset ( $_REQUEST [$esteCampo] ))
										 {$atributos ['seleccion'] = $_REQUEST [$esteCampo];}
										 else {	$atributos ['seleccion'] = -1;}
										 $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarJurados" );
										 $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
										 $atributos ['matrizItems'] = $matrizItems;
										 // Utilizar lo siguiente cuando no se pase un arreglo:
										 // $atributos['baseDatos']='ponerAquiElNombreDeLaConexión';
										 // $atributos ['cadena_sql']='ponerLaCadenaSqlAEjecutar';
										 $tab ++;
										 $atributos = array_merge ( $atributos, $atributosGlobales );
										 echo $this->miFormulario->campoCuadroLista ( $atributos );
										 unset ( $atributos );
										 // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------

										 // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
										 $esteCampo = 'tipoJurado';
										 $atributos ['columnas'] = 1;
										 $atributos ['nombre'] = $esteCampo;
										 $atributos ['id'] = $esteCampo;
										 $atributos ['evento'] = '';
										 $atributos ['deshabilitado'] = true;
										 $atributos ["etiquetaObligatorio"] = true;
										 $atributos ['tab'] = $tab;
										 $atributos ['tamanno'] = 1;
										 $atributos ['estilo'] = 'jqueryui';
										 $atributos ['validar'] = 'required';
										 $atributos ['limitar'] = true;
										 $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
										 $atributos ['anchoEtiqueta'] = 170;
										 $atributos ['anchoCaja'] = 60;
										 if (isset ( $_REQUEST [$esteCampo] ))
										 {$atributos ['seleccion'] = $_REQUEST [$esteCampo];}
										 else {	$atributos ['seleccion'] = -1;}
										 $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTiposJurado" );
										 $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
										 $atributos ['matrizItems'] = $matrizItems;
										 // Utilizar lo siguiente cuando no se pase un arreglo:
										 // $atributos['baseDatos']='ponerAquiElNombreDeLaConexión';
										 // $atributos ['cadena_sql']='ponerLaCadenaSqlAEjecutar';
										 $tab ++;
										 $atributos = array_merge ( $atributos, $atributosGlobales );
										 echo $this->miFormulario->campoCuadroLista ( $atributos );
										 unset ( $atributos );
										 // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------

                                                                                // ------------------Division para los botones-------------------------
                                                                               $atributos ["id"] = "botones";
                                                                               $atributos ["estilo"] = "marcoBotones";
                                                                               echo $this->miFormulario->division ( "inicio", $atributos );
                                                                               unset ( $atributos );
                                                                               {
                                                                                       // -----------------CONTROL: Botón ----------------------------------------------------------------
                                                                                       $esteCampo = 'botonAsignarAspirantes';
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
                                                                                       $atributos ['nombreFormulario'] = $esteBloque['nombre']."Jurados";
                                                                                       $tab ++;

                                                                                       // Aplica atributos globales al control
                                                                                       $atributos = array_merge ( $atributos, $atributosGlobales );
                                                                                       echo $this->miFormulario->campoBoton ( $atributos );
                                                                                       // -----------------FIN CONTROL: Botón -----------------------------------------------------------
                                                                               }
                                                                               echo $this->miFormulario->division ( 'fin' );


										 $esteCampo = "marcoListaAspirantes";
										 $atributos ['id'] = $esteCampo;
										 $atributos ["estilo"] = "jqueryui";
										 $atributos ['tipoEtiqueta'] = 'inicio';
										 $atributos ["leyenda"] = "<b>".$this->lenguaje->getCadena ( $esteCampo )."</b>";
										 echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );

										/* $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarAspirantesValidados", $parametro);
										 $aspirantes = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );*/
										 //var_dump($aspirantes);

										 echo "<table id='tablaConsultaAspirantesAsignados' class='display' width='100%'></table>";

										 echo $this->miFormulario->marcoAgrupacion ( 'fin' );



								}else{
										$tab=1;
										//---------------Inicio Formulario (<form>)--------------------------------
										$atributos["id"]="divNoEncontroConcurso";
										$atributos["estilo"]="marcoBotones";
										//$atributos["estiloEnLinea"]="display:none";
										echo $this->miFormulario->division("inicio",$atributos);

										//-------------Control Boton-----------------------
										$esteCampo = "noEncontroJurados";
										$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
										$atributos["etiqueta"] = "";
										$atributos["estilo"] = "centrar";
										$atributos["tipo"] = 'error';
										$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
										echo $this->miFormulario->cuadroMensaje($atributos);
										unset($atributos);
										//------------------Fin Division para los botones-------------------------
										echo $this->miFormulario->division("fin");
										//-------------Control cuadroTexto con campos ocultos-----------------------
								}

				//echo $this->miFormulario->marcoAgrupacion ( 'fin' );



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
			//$valorCodificado = "action=" . $esteBloque ["nombre"];
			$valorCodificado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=agregarAspirantesJurado";
			$valorCodificado .= "&usuario=" . $this->miSesion->getSesionUsuarioId();
			$valorCodificado .= "&consecutivo_concurso=" . $_REQUEST['consecutivo_concurso'];
			$valorCodificado .= "&nombre_concurso=".$_REQUEST['nombre_concurso'];


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
			//var_dump($valorCodificado);

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

$miSeleccionador = new consultarJurado ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
