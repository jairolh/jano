<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use gestionConcursante\concursosInscritos\funcion\redireccion;
class consultaForm {
        var $ruta;
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
                $this->ruta = $this->miConfigurador->getVariableConfiguracion ( "rutaBloque" );
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

		// lineas para conectar base de datos-------------------------------------------------------------------------------------------------
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		$seccion ['tiempo'] = $tiempo;

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
			$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
			$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

			$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );

			$variable = "pagina=" . $miPaginaActual;
			// $variable .= "&opcion=detalleConcurso";
			// $variable .= "&id_concurso=".$_REQUEST['id_concurso'];
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );

			$cadena_sql = $this->miSql->getCadenaSql ( "consultarValidacion", $_REQUEST ['consecutivo_inscrito'] );
			$resultadoValidacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

			$cadena_sql = $this->miSql->getCadenaSql ( "consultarEvaluacionILUD", $_REQUEST ['consecutivo_inscrito'] );
			$resultadoEvaluacionILUD = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

			$cadena_sql = $this->miSql->getCadenaSql ( "consultarEvaluacionCompetencias", $_REQUEST ['consecutivo_inscrito'] );
			$resultadoEvaluaciones = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			// var_dump($resultadoEvaluaciones);

			$cadena_sql = $this->miSql->getCadenaSql ( "consultarEvaluacionHoja", $_REQUEST ['consecutivo_inscrito'] );
			$resultadoEvaluacionesHoja = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

			$cadena_sql = $this->miSql->getCadenaSql ( "consultarEvaluacionFinal", $_REQUEST ['consecutivo_inscrito'] );
			$resultadoEvaluacionFinal = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'botonRegresar';
			$atributos ['id'] = $esteCampo;
			$atributos ['enlace'] = $variable;
			$atributos ['tabIndex'] = $tab;
			$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['estilo'] = 'textoPequenno textoGris';
			$atributos ['enlaceImagen'] = $rutaBloque . "/images/player_rew.png";
			$atributos ['posicionImagen'] = "atras"; // "adelante";
			$atributos ['ancho'] = '30px';
			$atributos ['alto'] = '30px';
			$atributos ['redirLugar'] = true;
			$tab ++;
			echo $this->miFormulario->enlace ( $atributos );
			unset ( $atributos );

                        include ($this->ruta . "formulario/tabs/perfil.php");
                        
			$esteCampo = "marcoCriterio";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = "<b>Evaluaciones</b>";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
                            include ($this->ruta . "formulario/tabs/evaluacionPerfil.php");
                            include ($this->ruta . "formulario/tabs/evaluacionFases.php");

			}

			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
			// ------------------Fin Division para los botones-------------------------
			echo $this->miFormulario->division ( "fin" );

			$atributos ['marco'] = true;
			$atributos ['tipoEtiqueta'] = 'fin';
			echo $this->miFormulario->formulario ( $atributos );

			return true;
		}
	}
}

$miSeleccionador = new consultaForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
