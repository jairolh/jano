<?php
namespace gestionConcurso\evaluacionConcurso;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

class consultarForm {
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

    $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
    $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
    $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

    $directorio = $this->miConfigurador->getVariableConfiguracion("host");
    $directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
    $directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

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

		// -------------------------------------------------------------------------------------------------
    $conexion="estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

		$valorCodificado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		$valorCodificado .= "&opcion=nuevoTipoJurado";

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


				//$cadena_sql = $this->miSql->getCadenaSql("consultaConcursosActivos", $parametro);
				//$resultadoConcursosActivos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
				//var_dump($resultadoConcursosActivos);


			    //var_dump($resultadoActividades);
            $esteCampo = "marcoDatosBasicos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>Validar Requisitos</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {

									echo "<div class='cell-border'><table id='tablaConsultaInscripcion' class='table table-striped table-bordered'>";
									echo "<thead>
													<tr align='center'>
															<th>N° Inscripción</th>
															<th>Identificacion</th>
															<th>Aspirante</th>
															<th>Hoja de Vida</th>
													</tr>
											</thead>
											<tbody>";

											$mostrarHtml = "<tr align='center'>
															<td align='left'>".$_REQUEST['consecutivo_inscrito']."</td>
															<td align='left'>".$_REQUEST['usuario']."</td>
															<td align='left'>".$_REQUEST['nombre_usuario']."</td>";
															$mostrarHtml .= "<td>";

																			//-------------Enlace-----------------------
																					$esteCampo = "validar";
																					$atributos["id"]=$esteCampo;
																					//$atributos['enlace']=$variableEditar;
																					$atributos['tabIndex']=$esteCampo;
																					$atributos['redirLugar']=true;
																					$atributos['estilo']='clasico';
																					$atributos['enlaceTexto']='Ver hoja de vida';
																					$atributos['ancho']='30';
																					$atributos['alto']='30';
																					//$atributos['enlaceImagen']=$rutaBloque."/images/check_file.png";
																					$mostrarHtml .= $this->miFormulario->enlace($atributos);
																					unset($atributos);

															 $mostrarHtml .= "</td>";


										 $mostrarHtml .= "</tr>";
										 echo $mostrarHtml;
										 unset($mostrarHtml);
										 echo "</tbody>";
										 echo "</table></div>";

										 $parametro=array(
											 'consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
											 'consecutivo_perfil'=>$_REQUEST['consecutivo_perfil']
										 );
										 $cadena_sql = $this->miSql->getCadenaSql("consultaPerfil", $parametro);
										 $resultadoPerfil= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
										 //var_dump($resultadoPerfil);


										 //mostrar listado de requisitos
										 //$resultadoPerfil[0]['requisitos']

										 echo "<div style ='width: 80%; padding-left: 10%;' class='cell-border'><table id='tablaRequisitos' class='table table-striped table-bordered'>";

		 								echo "
		 										<tbody>";

		 								$mostrarHtml = "<tr align='center'>
		 											<th >Concurso</th>
		 											<td class='table-tittle estilo_tr '>".$resultadoPerfil[0]['concurso']."</td>
													<th >Perfil</th>
		 											<td class='table-tittle estilo_tr '>".$resultadoPerfil[0]['perfil']."</td></tr>
		 								";


										$mostrarHtml .= "<tr align='center'>
													<th >Requisitos</th>
													<td class='table-tittle estilo_tr '>".'<div  >'.'<ul class="list-group">
													  <li class="list-group-item">Cras justo odio</li>
													  <li class="list-group-item">Dapibus ac facilisis in</li>
													  <li class="list-group-item">Morbi leo risus</li>
													</ul>'.'</div>'."</td>

													<th >Validar</th>
													<td class='table-tittle estilo_tr '>"."¿El aspirante cumple con los requisitos exigidos para el perfil?".'<div><br><div class="btn-group btn-toggle" data-toggle="buttons">
														<label class="btn btn-primary active">
															<input type="radio" name="options" value="option1" checked="checked"> Si
														</label>
														<label class="btn btn-default">
															<input type="radio" name="options" value="option2" > No
														</label>
													</div></div>
												</div>'."</td></tr>
																						";


										echo $mostrarHtml;
										unset($mostrarHtml);

										echo "</tbody>";
										echo "</table>";

												$tab=1;



												// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
												$esteCampo = 'descripcion';
												$atributos ['id'] = $esteCampo;
												$atributos ['nombre'] = $esteCampo;
												$atributos ['tipo'] = 'text';
												$atributos ['estilo'] = 'jqueryui';
												$atributos ['marco'] = true;
												$atributos ['estiloMarco'] = '';
												$atributos ["etiquetaObligatorio"] = true;
												$atributos ['columnas'] = 150;
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
												// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
													echo "</div>";



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


													}
													echo $this->miFormulario->division ( 'fin' );





            echo $this->miFormulario->marcoAgrupacion ( 'fin' );

            // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
            // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
            // Se debe declarar el mismo atributo de marco con que se inició el formulario.
        }
        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division ( "fin" );

    }
}

$miSeleccionador = new consultarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
