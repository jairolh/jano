<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use gestionConcursante\concursosInscritos\funcion\redireccion;

class registrarForm {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	var $miSesion;

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
			$variable.= "&opcion=consultaEvaluacion";
			$variable.= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
			$variable.= "&consecutivo_inscrito=".$_REQUEST['consecutivo_inscrito'];
			$variable.= "&consecutivo_perfil=".$_REQUEST['consecutivo_perfil'];

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

                        //detalle de la reclamación
                        $parametro=array(
                                'reclamacion'=>$_REQUEST['reclamacion']
                        );
                        $cadena_sql = $this->miSql->getCadenaSql("respuestaReclamacion", $parametro);
                        $respuestaReclamacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

			$esteCampo = "marcoConsultaReclamacion";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
				echo "<div class='cell-border'><table id='tablaConsultaInscripcion' class='table table-striped table-bordered'>";
				echo "<thead>
                                                <tr align='center'>
                                                    <th>N° Reclamación</th>
                                                    <th>Fecha Respuesta</th>
                                                    <th>¿Aplica reclamación?</th>
                                                    <th>Observaciones</th>
                                                </tr>
						</thead>
						<tbody>";
                                                /*				<th>Evaluador</th>*/
						if($respuestaReclamacion){
							$mostrarHtml = "<tr align='center'>
											<td align='left'>".$respuestaReclamacion[0]['id_reclamacion']."</td>
											<td align='left'>".$respuestaReclamacion[0]['fecha_registro']."</td>
											<td align='left'>".$respuestaReclamacion[0]['respuesta']."</td>
											<td align='left'>".$respuestaReclamacion[0]['observacion']."</td>";
											//<td align='left'>".$respuestaReclamacion[0]['evaluador']."</td>";

						 $mostrarHtml .= "</tr>";
						}

					 echo $mostrarHtml;
					 unset($mostrarHtml);
					 echo "</tbody>";
					 echo "</table></div>";

			}


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

			$valorCodificado = "action=" . $esteBloque ["nombre"];
			$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=guardarValidacion";
												//$valorCodificado .= "&consecutivo_inscrito=".$_REQUEST['consecutivo_inscrito'];

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
