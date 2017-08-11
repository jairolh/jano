<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use gestionConcurso\reclamaciones\funcion\redireccion;

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

			$parametro=array(
	              //'consecutivo_inscrito'=>$_REQUEST['consecutivo_inscrito']
									'consecutivo_inscrito'=>1
	    );
	    $cadena_sql = $this->miSql->getCadenaSql("consultarValidacion2", $parametro);
	    $resultadoValidacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

			$esteCampo = "marcoEvaluacionReclamacion";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = "<b>".$this->lenguaje->getCadena ( $esteCampo )."</b>";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
				 if($resultadoValidacion)
							{
									//-----------------Inicio de Conjunto de Controles----------------------------------------
											$esteCampo = "marcoConsultaInscrito";
											$atributos["estilo"] = "jqueryui";
											$atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
											//echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
											unset($atributos);
											echo "<div class='cell-border'><table id='tablaConsultaInscrito' class='table table-striped table-bordered'>";
											echo "<thead>
															<tr align='center'>
																	<th>consecutivo_valida</th>
																	<th>consecutivo_inscrito</th>
																	<th>cumple_requisito</th>
																	<th>Observación</th>
																	<th>Fecha</th>
																	<th>Reclamación</th>
															</tr>
													</thead>
													<tbody>";

											foreach($resultadoValidacion as $key=>$value )
													{   $parametro['tipo']='unico';

															$variableEvaluar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
															$variableEvaluar.= "&opcion=evaluar";
															$variableEvaluar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
															//$variableEvaluar.= "&nombre_usuario=". $resultadoListaInscrito[$key]['nombre']." ".$resultadoListaInscrito[$key]['apellido'];
															$variableEvaluar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
															$variableEvaluar.= "&tiempo=" . time ();
													//    $variableEvaluar .= "&consecutivo_concurso=".$resultadoListaInscrito[$key]['consecutivo_concurso'];
														//  $variableEvaluar .= "&consecutivo_perfil=".$resultadoListaInscrito[$key]['consecutivo_perfil'];
															//$variableEvaluar .= "&consecutivo_inscrito=".$resultadoListaInscrito[$key]['consecutivo_inscrito'];
															$variableEvaluar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEvaluar, $directorio);
															//$variableEvaluar.= "#tabInscrito";

															//enlace actualizar estado
															$variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
															/*if($resultadoListaInscrito[$key]['estado']=='A')
																	{$variableEstado.= "&opcion=inhabilitarInscrito";}
															else{$variableEstado.= "&opcion=habilitarInscrito";}*/
															$variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
													//    $variableEstado.= "&consecutivo_concurso=".$resultadoListaInscrito[$key]['consecutivo_concurso'];
														//  $variableEstado.= "&consecutivo_perfil=".$resultadoListaInscrito[$key]['consecutivo_perfil'];
															//$variableEstado.= "&nombre=" .$resultadoListaInscrito[$key]['nombre'];
															$variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
															$variableEstado.= "&tiempo=" . time ();
															$variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);

															//verificar si ya se realizó la validación de la inscripción
															/*$cadena_sql = $this->miSql->getCadenaSql("consultarValidacion", $resultadoListaInscrito[$key]['consecutivo_inscrito']);
															$resultadoValidacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

															if($resultadoValidacion){
																$validacion=$resultadoValidacion[0]['cumple_requisito'];
															}else{
																$validacion="PENDIENTE";
															}*/

															$mostrarHtml = "<tr align='center'>
																			<td align='left'>".$resultadoValidacion[$key]['consecutivo_valida']."</td>
																			<td align='left'>".$resultadoValidacion[$key]['consecutivo_inscrito']."</td>
																			<td align='left'>".$resultadoValidacion[$key]['cumple_requisito']."</td>
																			<td align='left'>".$resultadoValidacion[$key]['observacion']."</td>
																			<td align='left'>".$resultadoValidacion[$key]['fecha_registro']."</td>
																			<td align='left'>".$resultadoValidacion[$key]['id_reclamacion']."</td>";


														 $mostrarHtml .= "</tr>";
														 echo $mostrarHtml;
														 unset($mostrarHtml);
														 unset($variable);
													}
											echo "</tbody>";
											echo "</table></div>";

											echo "<div style ='width: 100%; padding-left: 12%; padding-right: 12%;' class='cell-border'><table id='tablaRequisitos' class='table table-striped table-bordered'>";

										 echo "
												 <tbody>";


										 $mostrarHtml =  "<tr align='center'>".
													 "<th >Reclamación</th></tr>";


										$mostrarHtml .="<td align='center' colspan='3'>"."¿Aplica la reclamación para la Validación de Requisitos?".'<div><br>';

													$mostrarHtml .= '<div id="radioBtn" class="btn-group">';

													//-------------Enlace-----------------------
															$esteCampo = "enlace1";
															$atributos["id"]=$esteCampo;
															$atributos["toogle"]="validar";
															$atributos["toogletitle"]="SI";
															$atributos['enlace']='';
															$atributos['tabIndex']=$esteCampo;
															$atributos['redirLugar']=false;
															$atributos['estilo']='btn btn-primary btn-sm active';
															$atributos['enlaceTexto']='SI';
															$atributos['ancho']='30';
															$atributos['alto']='30';
															$atributos['onClick'] ="show(\"marcoSubsistema\")";
															//$atributos['enlaceImagen']=$rutaBloque."/images/check_file.png";
															$mostrarHtml .= $this->miFormulario->enlace($atributos);
															unset($atributos);
														//----------------------------------------

														//-------------Enlace-----------------------
																$esteCampo = "enlace2";
																$atributos["id"]=$esteCampo;
																$atributos["toogle"]="validar";
																$atributos["toogletitle"]="NO";
																//$atributos['enlace']=$variableEditar;
																$atributos['tabIndex']=$esteCampo;
																$atributos['redirLugar']=false;
																$atributos['estilo']='btn btn-primary btn-sm notActive';
																$atributos['enlaceTexto']='NO';
																$atributos['ancho']='30';
																$atributos['alto']='30';
																//$atributos['enlaceImagen']=$rutaBloque."/images/check_file.png";
																$mostrarHtml .= $this->miFormulario->enlace($atributos);
																unset($atributos);
															//----------------------------------------

															//$mostrarHtml .= '<input type="hidden" name="validar" id="validar">';

															$mostrarHtml .= '</div>';

												$mostrarHtml .='</div>'."</tr>";







										 echo $mostrarHtml;
										 unset($mostrarHtml);

										 echo "</tbody>";
										 echo "</table>";
											//Fin de Conjunto de Controles

											echo "<div style ='width: 100%; padding-left: 12%; padding-right: 12%;' class='cell-border'><table id='tablaRequisitos' class='table table-striped table-bordered'>";

										 echo "<tbody>";

										 $mostrarHtml ="<tr align='center' colspan='3'>";

										$tab=1;
										// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
										$esteCampo = 'observaciones';
										$atributos ['id'] = $esteCampo;
										$atributos ['nombre'] = $esteCampo;
										$atributos ['tipo'] = 'text';
										$atributos ['estilo'] = 'jqueryui';
										$atributos ['marco'] = true;
										$atributos ['estiloMarco'] = '';
										$atributos ["etiquetaObligatorio"] = true;
										$atributos ['columnas'] = 140;
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
										$tab ++;

										// Aplica atributos globales al control
										$atributos = array_merge ( $atributos, $atributosGlobales );
										echo $this->miFormulario->campoTextArea ( $atributos );
										unset ( $atributos );

										$mostrarHtml .="</tr>";


										// ------------------Division para los botones-------------------------
										$atributos ["id"] = "botones";
										$atributos ["estilo"] = "marcoBotones";
										echo $this->miFormulario->division ( "inicio", $atributos );
										unset  ( $atributos );
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

										 echo $mostrarHtml;
										 unset($mostrarHtml);

										 echo "</tbody>";
										 echo "</table>";

							}else
							{
											$atributos["id"]="divNoEncontroInscrito";
											$atributos["estilo"]="";
								 //$atributos["estiloEnLinea"]="display:none";
											echo $this->miFormulario->division("inicio",$atributos);

											//-------------Control Boton-----------------------
											$esteCampo = "noEncontroInscrito";
											$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
											$atributos["etiqueta"] = "";
											$atributos["estilo"] = "centrar";
											$atributos["tipo"] = 'error';
											$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
											echo $this->miFormulario->cuadroMensaje($atributos);
											unset($atributos);
											//-------------Fin Control Boton----------------------

										 echo $this->miFormulario->division("fin");
											//------------------Division para los botones-------------------------
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
			$valorCodificado .= "&opcion=guardarRespuestaEvaluacion";
			//$valorCodificado .= "&consecutivo_inscrito=".$_REQUEST['consecutivo_inscrito'];
			//$valorCodificado .= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
			//$valorCodificado .= "&consecutivo_perfil=".$_REQUEST['consecutivo_perfil'];

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
