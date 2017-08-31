<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use gestionConcurso\reclamacionesEvaluaciones\funcion\redireccion;
class evaluarReclamacion {
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

		$this->miSesion = \Sesion::singleton ();
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

			$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
			$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

			$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );

			// buscar reclamaciones para el concurso
			$parametro = array (
					'consecutivo_concurso' => $_REQUEST ['consecutivo_concurso']
			);
			$cadena_sql = $this->miSql->getCadenaSql ( "consultarReclamaciones", $parametro );
			$resultadoReclamacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

			$esteCampo = "marcoEvaluacionReclamacion";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = "<b>" . $this->lenguaje->getCadena ( $esteCampo ) . "</b>";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
				if ($resultadoReclamacion) {
					// -----------------Inicio de Conjunto de Controles----------------------------------------
					$esteCampo = "marcoConsultaInscrito";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
					// echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
					unset ( $atributos );

					echo "<div class='cell-border'><table id='tablaConsultaInscrito' class='table table-striped table-bordered'>";
					echo "<thead>
													<tr align='center'>
															<th>N° Inscripción</th>
															<th>Identificación</th>
															<th>Aspirante</th>
															<th>Hoja de Vida</th>
													</tr>
													</thead>
													<tbody>";

					foreach ( $resultadoReclamacion as $key => $value ) {
						$parametro = array (
								'consecutivo_inscrito' => $resultadoReclamacion [$key] ['id_inscrito']
						);
						$cadena_sql = $this->miSql->getCadenaSql ( "consultarValidacion2", $parametro );
						$resultadoValidacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

						$mostrarHtml = "<tr align='center'>
																<td align='left'>" . $resultadoReclamacion [$key] ['id_inscrito'] . "</td>
																<td align='left'>" . $resultadoReclamacion [$key] ['identificacion'] . "</td>
																<td align='left'>" . $resultadoReclamacion [$key] ['nombre_inscrito'] . "</td>";
						$mostrarHtml .= "<td>";

						$variableVerHoja = "pagina=publicacion";
						$variableVerHoja .= "&opcion=hojaVida";
						$variableVerHoja .= "&usuario=" . $this->miSesion->getSesionUsuarioId ();
						$variableVerHoja .= "&id_usuario=" . $_REQUEST ['usuario'];
						$variableVerHoja .= "&campoSeguro=" . $_REQUEST ['tiempo'];
						$variableVerHoja .= "&tiempo=" . time ();
						$variableVerHoja .= "&consecutivo_inscrito=" . $resultadoReclamacion [$key] ['id_inscrito'];
						$variableVerHoja .= "&consecutivo_concurso=" . $_REQUEST ['consecutivo_concurso'];
						$variableVerHoja .= "&consecutivo_perfil=" . $resultadoReclamacion [$key] ['consecutivo_perfil'];
						$variableVerHoja = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableVerHoja, $directorio );

						// -------------Enlace-----------------------
						$esteCampo = "verHojaVida";
						$esteCampo = 'enlace_hoja';
						$atributos ['id'] = $esteCampo;
						$atributos ['enlace'] = 'javascript:enlace("ruta_enlace_hoja");';
						$atributos ['tabIndex'] = 0;
						$atributos ['columnas'] = 1;
						$atributos ['enlaceTexto'] = 'Ver Curriculum';
						$atributos ['estilo'] = 'clasico';
						$atributos ['enlaceImagen'] = $rutaBloque . "/images/xmag.png";
						$atributos ['posicionImagen'] = "atras"; // "adelante";
						$atributos ['ancho'] = '20px';
						$atributos ['alto'] = '20px';
						$atributos ['redirLugar'] = false;
						$atributos ['valor'] = '';
						$mostrarHtml .= $this->miFormulario->enlace ( $atributos );
						unset ( $atributos );
						// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
						$esteCampo = 'ruta_enlace_hoja';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'hidden';
						$atributos ['etiqueta'] = ""; // $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['obligatorio'] = false;
						$atributos ['valor'] = $variableVerHoja;
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = FALSE;
						$mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
						// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------

						$mostrarHtml .= "</td>";

						$mostrarHtml .= "</tr>";
					}
					echo $mostrarHtml;
					unset ( $mostrarHtml );

					echo "</tbody>";
					echo "</table></div>";

					echo "<div style ='width: 100%; padding-left: 12%; padding-right: 12%;' class='cell-border'><table id='tablaRequisitos' class='table table-striped table-bordered'>";
					echo "<tbody>";
					$mostrarHtml = "<tr>
			 								<th>Concurso</th>
			 								<td colspan='1'>" . $resultadoReclamacion [$key] ['concurso'] . "</td>
			 								<th>Perfil</th>
			 								<td colspan='1'>" . $resultadoReclamacion [$key] ['perfil'] . "</td>
			 								</tr>";

					echo $mostrarHtml;
					unset ( $mostrarHtml );
					unset ( $variable );
					echo "</tbody>";
					echo "</table></div>";

					echo "<div style ='width: 100%; padding-left: 12%; padding-right: 12%;' class='cell-border'><table id='tablaRequisitos' class='table table-striped table-bordered'>";
					echo "<tbody>";
					$mostrarHtml = "<tr align='center'>" . "<th colspan='2'>Reclamación #" . $resultadoReclamacion [0] ['id'] . "</th>
												</td>";
					$mostrarHtml .= "<tr align='center'>" . "<th colspan='1'>Fecha</th>
												<td colspan='3'>" . $resultadoReclamacion [0] ['fecha_registro'] . "</td>";

					$mostrarHtml .= "<tr align='center'>" . "<th colspan='1'>Observación</th>
												<td colspan='3'>" . $resultadoReclamacion [0] ['observacion'] . "</td>";
					echo $mostrarHtml;
					unset ( $mostrarHtml );
					unset ( $variable );
					echo "</tbody>";
					echo "</table></div>";

					echo "<div style ='width: 100%; padding-left: 12%; padding-right: 12%;' class='cell-border'><table id='tablaRequisitos' class='table table-striped table-bordered'>";
					echo "<tbody>";
					$mostrarHtml = "<tr>
					 								<th colspan='4'>Evaluación Reclamación</th>
					 								</tr>";

					$parametro = array (
							'reclamacion' => $resultadoReclamacion [0] ['id'],
							'usuario' => $this->miSesion->getSesionUsuarioId ()
					);
					$cadena_sql = $this->miSql->getCadenaSql ( "consultarDetalleReclamacion", $parametro );
					$resultadoDetalleReclamacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );


                    $cadena_sql = $this->miSql->getCadenaSql("consultaRespuestaReclamaciones", $parametro);
                    $resultadoRespuestaReclamaciones = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    //var_dump($resultadoRespuestaReclamaciones);

					$mostrarHtml .= "<tr align='center'>" . "<th colspan='1'>Criterio</th>
													<th colspan='1'>Calificación</th>
                                                    <th colspan='1'>¿Aplica Reclamación?</th>
													<th colspan='1'>Nueva Calificación</th><tr>";

					foreach ( $resultadoDetalleReclamacion as $key => $value ) {
						$mostrarHtml .= "<tr>
			 								<td colspan='1'>" . $resultadoDetalleReclamacion [$key] ['nombre_criterio'] . "</td>
                                            <td colspan='1'>" . $resultadoDetalleReclamacion [$key] ['puntaje_parcial'] . "</td>
                                            <td colspan='1'>" . $resultadoRespuestaReclamaciones [$key] ['respuesta'] . "</td>";

                        if($resultadoRespuestaReclamaciones [$key] ['respuesta']=='SI'){
                            $mostrarHtml .= "<td align='center' colspan='1'>";

                            $tab = 1;
                            $esteCampo = 'puntaje'.$key;
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
                            $atributos ['etiqueta'] = "";

                            /*if($valorPuntaje=='natural'){
                                $atributos ['validar']="required, custom[onlyNumberSp], min[0], max[".$resultadoCriterios[$key]['maximo_puntos']."]";
                            }else{
                                $atributos ['validar']="required, custom[number], min[0], max[".$resultadoCriterios[$key]['maximo_puntos']."]";
                            }*/

                            $atributos ['valor'] = '';
                            $atributos ['titulo'] = "";
                            $atributos ['deshabilitado'] = false;
                            $atributos ['tamanno'] = 8;
                            $atributos ['maximoTamanno'] = '';
                            $atributos ['anchoEtiqueta'] = 300;
                            $tab ++;
                            // Aplica atributos globales al control
                            $atributos = array_merge ( $atributos, $atributosGlobales );
                            $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                            unset ( $atributos );
                            // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------

                            $mostrarHtml .= "</td>";
                        }

					}

					echo $mostrarHtml;
					unset ( $mostrarHtml );
					echo "</tbody>";
					echo "</table></div>";

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
						echo $this->miFormulario->campoBoton ( $atributos );
						unset ( $atributos );
						// -----------------FIN CONTROL: Botón -----------------------------------------------------------
					}
					echo $this->miFormulario->division ( 'fin' );
				} else {
					$atributos ["id"] = "divNoEncontroInscrito";
					$atributos ["estilo"] = "";
					// $atributos["estiloEnLinea"]="display:none";
					echo $this->miFormulario->division ( "inicio", $atributos );

					// -------------Control Boton-----------------------
					$esteCampo = "noEncontroInscrito";
					$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
					$atributos ["etiqueta"] = "";
					$atributos ["estilo"] = "centrar";
					$atributos ["tipo"] = 'error';
					$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
					;
					echo $this->miFormulario->cuadroMensaje ( $atributos );
					unset ( $atributos );
					// -------------Fin Control Boton----------------------

					echo $this->miFormulario->division ( "fin" );
					// ------------------Division para los botones-------------------------
				}
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
			$valorCodificado .= "&opcion=guardarRespuestaNuevaEvaluacion";
			$valorCodificado .= "&reclamacion=" . $resultadoReclamacion [0] ['id'];
            $valorCodificado .= "&inscrito=" . $resultadoDetalleReclamacion[0]['id_inscrito'];
			$valorCodificado .= "&usuario=" . $this->miSesion->getSesionUsuarioId ();
			//$valorCodificado .= "&evaluar_respuesta=" . // $resultadoValidacion[0]['consecutivo_valida'];//la validación

			// $valorCodificado .= "&consecutivo_inscrito=".$_REQUEST['consecutivo_inscrito'];
			                                            // $valorCodificado .= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
			                                            // $valorCodificado .= "&consecutivo_perfil=".$_REQUEST['consecutivo_perfil'];

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

$miSeleccionador = new evaluarReclamacion ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
