<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use gestionConcursante\concursosInscritos\funcion\redireccion;
class consultaForm {
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

			$esteCampo = "marcoCriterio";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = "<b>Evaluaciones</b>";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{

				if ($resultadoValidacion || $resultadoEvaluacionILUD || $resultadoEvaluaciones || $resultadoEvaluacionesHoja) {
					// -----------------Inicio de Conjunto de Controles----------------------------------------
					$esteCampo = "marcoConsultaPerfiles";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
					// echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
					unset ( $atributos );

					// ################################## VALIDACION DE REQUISITOS
					/*
					 * Actividades con reclamación (concurso.concurso_calendario):
					 * - Verificación de cumplimiento de Requisitos del perfil
					 * - Evaluación de Competencias Profesionales y Comunicativas
					 * - Prueba Segunda Lengua
					 * - Evaluación de la Hoja de Vida
					 */

					$cadena_sql = $this->miSql->getCadenaSql ( "actividadesConReclamacion" );
					$actividadesConReclamacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

					echo '<div class="panel-group" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Evaluación de requisitos</a>
        </h4>
      </div>';

					echo '
      <div id="collapse1" class="panel-collapse collapse">';

					echo "<table id='tablaConsultaAspirantes' class='table table-striped table-bordered'>";
					echo "<thead>
							<tr align='center'>
									<th>Resultado</th>
									<th>Observación</th>
									<th>Fecha</th>
							</tr>
					</thead>
					<tbody> ";

					$mostrarHtml = "<tr align='center'>
											<td align='left'>" . $resultadoValidacion [0] ['cumple_requisito'] . "</td>
											<td align='left'>" . $resultadoValidacion [0] ['observacion'] . "</td>
											<td align='left'>" . $resultadoValidacion [0] ['fecha_registro'] . "</td>";
					$mostrarHtml .= "</tr>";

					echo $mostrarHtml;
					unset ( $mostrarHtml );

					echo "</tbody>";

					echo "</table>";

					$esteCampo = "marcoConsultaPerfiles";
					$atributos ["estilo"] = "jqueryui";
					$atributos ["leyenda"] = "Reclamaciones";

					echo $this->miFormulario->marcoAgrupacion ( "inicio", $atributos );
					unset ( $atributos );

					// buscar reclamación
					$parametro = array (
							'consecutivo_inscrito' => $_REQUEST ['consecutivo_inscrito'],
							'reclamacion' => $resultadoValidacion [0] ['id_reclamacion']
					);

					$cadena_sql = $this->miSql->getCadenaSql ( "reclamacionesValidacion", $parametro );
					$reclamacionesValidacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

					$parametro = array (
							'consecutivo_concurso' => $_REQUEST ['consecutivo_concurso'],
							'consecutivo_actividad' => $actividadesConReclamacion [0] ['consecutivo_actividad']
					);
					$cadena_sql = $this->miSql->getCadenaSql ( "fechaFinReclamacion", $parametro );
					$fechaFinReclamacionValidacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
					// $id_etapa=$fechaFinReclamacionValidacion[0]['consecutivo_calendario'];
					// $etapa=$fechaFinReclamacionValidacion[0]['nombre'];

					if ($reclamacionesValidacion) {

						// buscar respuesta a la reclamación
						$parametro = array (
								'reclamacion' => $resultadoValidacion [0] ['id_reclamacion']
						);
						$cadena_sql = $this->miSql->getCadenaSql ( "respuestaReclamacion", $parametro );
						$respuestaReclamacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

						// enlace para consultar los criterios asociados al tipo de jurado
						$variableDetalleRta = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
						$variableDetalleRta .= "&opcion=consultarDetalleRta";
						$variableDetalleRta .= "&consecutivo_concurso=" . $_REQUEST ['consecutivo_concurso'];
						$variableDetalleRta .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_inscrito'];
						$variableDetalleRta .= "&consecutivo_perfil=" . $_REQUEST ['consecutivo_perfil'];
						$variableDetalleRta .= "&reclamacion=" . $resultadoValidacion [0] ['id_reclamacion'];
						$variableDetalleRta .= "&campoSeguro=" . $_REQUEST ['tiempo'];
						$variableDetalleRta .= "&tiempo=" . time ();
						$variableDetalleRta = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableDetalleRta, $directorio );

						$parametro = array (
								'consecutivo_inscrito' => $_REQUEST ['consecutivo_inscrito'],
								'reclamacion' => $resultadoValidacion [0] ['id_reclamacion']
						);
						$cadena_sql = $this->miSql->getCadenaSql ( "consultaEvaluacionesReclamacion", $parametro );
						$validacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

						echo "<table id='tablaConsultaAspirantes' class='table table-striped table-bordered'>";
						echo "<thead>
								<tr align='center'>
										<th>Reclamación</th>
										<th>Observación</th>
										<th>Fecha</th>
										<th>¿Aplica la reclamación?</th>
										<th>Nueva Evaluación</th>
								</tr>
						</thead>
						<tbody> ";

						$mostrarHtml = "<tr align='center'>
												<td align='left'>" . $reclamacionesValidacion [0] ['id'] . "</td>
												<td align='left'>" . $reclamacionesValidacion [0] ['observacion'] . "</td>
												<td align='left'>" . $reclamacionesValidacion [0] ['fecha_registro'] . "</td>";

						if ($respuestaReclamacion) {
							$mostrarHtml .= "<td align='left'>";
							$esteCampo = "detalle";
							$atributos ["id"] = $esteCampo;
							$atributos ['enlace'] = $variableDetalleRta;
							$atributos ['tabIndex'] = $esteCampo;
							$atributos ['redirLugar'] = true;
							$atributos ['estilo'] = 'clasico';
							$atributos ['enlaceTexto'] = $respuestaReclamacion [0] ['respuesta'];
							$atributos ['ancho'] = '25';
							$atributos ['alto'] = '25';
							// $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";

							$mostrarHtml .= $this->miFormulario->enlace ( $atributos );
							$mostrarHtml .= "</td>";
						} else {
							$mostrarHtml .= "<td align='left'>" . "Pendiente" . "</td>";
						}

						// $mostrarHtml .= "<td align='left'>"."Pendiente"."</td>";
						$mostrarHtml .= "<td align='left'>";
						if ($validacion [0] [0] == 2) {
							$variableValidacion = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
							$variableValidacion .= "&opcion=consultaNuevaEvaluacion";
							// $variableValidacion.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
							$variableValidacion .= "&id_usuario=" . $_REQUEST ['usuario'];
							$variableValidacion .= "&campoSeguro=" . $_REQUEST ['tiempo'];
							$variableValidacion .= "&tiempo=" . time ();
							$variableValidacion .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_concurso'];
							// $variableValidacion .= "&consecutivo_concurso=".$resultadoReclamaciones[$key]['id_concurso'];
							// $variableValidacion .= "&consecutivo_perfil=".$resultadoReclamaciones[$key]['consecutivo_perfil'];
							$variableValidacion .= "&reclamacion=" . $respuestaReclamacion [0] ['id_reclamacion'];
							$variableValidacion = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableValidacion, $directorio );

							// -------------Enlace-----------------------
							$esteCampo = "verEvaluacion";
							$esteCampo = 'enlace_hoja';
							$atributos ['id'] = $esteCampo;
							$atributos ['enlace'] = $variableValidacion;
							$atributos ['tabIndex'] = 0;
							$atributos ['columnas'] = 1;
							$atributos ['enlaceTexto'] = 'Ver Evaluación';
							$atributos ['estilo'] = 'clasico';
							$atributos ['enlaceImagen'] = $rutaBloque . "/images/xmag.png";
							$atributos ['posicionImagen'] = "atras"; // "adelante";
							$atributos ['ancho'] = '20px';
							$atributos ['alto'] = '20px';
							$atributos ['redirLugar'] = false;
							$atributos ['valor'] = '';
							$mostrarHtml .= $this->miFormulario->enlace ( $atributos );
							unset ( $atributos );
						} else {
							$mostrarHtml .= "Pendiente" . "</td>";
						}
						$mostrarHtml .= "</td>";
						$mostrarHtml .= "</tr>";

						echo $mostrarHtml;
						unset ( $mostrarHtml );

						echo "</tbody>";

						echo "</table>";
					} else {
						$atributos ["id"] = "divNoEncontroPerfil";
						$atributos ["estilo"] = "";
						// $atributos["estiloEnLinea"]="display:none";
						echo $this->miFormulario->division ( "inicio", $atributos );

						// -------------Control Boton-----------------------
						$esteCampo = "noEncontroPerfil";
						$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
						$atributos ["etiqueta"] = "";
						$atributos ["estilo"] = "centrar";
						$atributos ["tipo"] = 'error';
						$atributos ["mensaje"] = "No se han realizado reclamaciones para la inscripción en la etapa de <b>" . $fechaFinReclamacionValidacion [0] ['nombre'] . "</b>";
						echo $this->miFormulario->cuadroMensaje ( $atributos );
						unset ( $atributos );
						// -------------Fin Control Boton----------------------

						echo $this->miFormulario->division ( "fin" );
						// ------------------Division para los botones-------------------------

						$fecha = date ( "Y-m-d H:i:s" );
						if ($fecha <= $fechaFinReclamacionValidacion [0] ['fecha_fin_reclamacion']) {

							$id_etapa = $fechaFinReclamacionValidacion [0] ['consecutivo_calendario'];
							$etapa = $fechaFinReclamacionValidacion [0] ['nombre'];

							$variableNuevo = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
							$variableNuevo .= "&bloque=" . $esteBloque ['nombre'];
							$variableNuevo .= "&bloqueGrupo=" . $esteBloque ["grupo"];
							$variableNuevo .= "&opcion=solicitarReclamacion";
							$variableNuevo .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_inscrito'];
							$variableNuevo .= "&consecutivo_concurso=" . $_REQUEST ['consecutivo_concurso'];
							$variableNuevo .= "&consecutivo_perfil=" . $_REQUEST ['consecutivo_perfil'];

							$variableNuevo .= "&consecutivo_actividad=" . $fechaFinReclamacionValidacion [0] ['consecutivo_actividad'];
							$variableNuevo .= "&id_etapa=" . $id_etapa;
							$variableNuevo .= "&etapa=" . $etapa;

							$variableNuevo .= "&campoSeguro=" . $_REQUEST ['tiempo'];
							$variableNuevo .= "&tiempo=" . time ();
							$variableNuevo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableNuevo, $directorio );

							// enlace para hacer la reclamación
							echo "<div ><table width='20%' align='center'>
									<tr align='center'>
											<td align='center'>";
							$esteCampo = 'nuevaReclamacion';
							$atributos ['id'] = $esteCampo;
							$atributos ['enlace'] = $variableNuevo;
							$atributos ['tabIndex'] = 1;
							$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['estilo'] = 'textoPequenno textoGris';
							$atributos ['enlaceImagen'] = $rutaBloque . "/images/new.png";
							$atributos ['posicionImagen'] = "atras"; // "adelante";
							$atributos ['ancho'] = '45px';
							$atributos ['alto'] = '45px';
							$atributos ['redirLugar'] = true;
							echo $this->miFormulario->enlace ( $atributos );
							unset ( $atributos );
							echo "            </td>
									</tr>
								</table></div> ";
						}
					}

					echo $this->miFormulario->marcoAgrupacion ( 'fin' );

					echo '</div>';
					echo '</div>';

					// ############################# Evaluaciones ILUD#####################################################//

					echo '<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Evaluación Segunda Lengua</a>
		</h4>
	</div>
	<div id="collapse2" class="panel-collapse collapse">
    <br>';

					$parametro = array (
							'consecutivo_concurso' => $_REQUEST ['consecutivo_concurso'],
							'consecutivo_actividad' => $actividadesConReclamacion [2] ['consecutivo_actividad']
					);
					$cadena_sql = $this->miSql->getCadenaSql ( "fechaFinReclamacion", $parametro );
					$fechaFinReclamacionSegundaLengua = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

					if ($resultadoEvaluacionILUD) {
						echo "<table id='tablaConsultaAspirantes' class='table table-striped table-bordered'>";
						echo "<thead>
					<tr align='center'>
							<th>Criterio</th>
							<th>Puntaje</th>
							<th>Observación</th>
							<th>Fecha</th>
							<th>Evaluador</th>
					</tr>
			</thead>
			<tbody> ";
						$mostrarHtml = "";

						// $id_etapa=$fechaFinReclamacionSegundaLengua[0]['consecutivo_calendario'];
						// $etapa=$fechaFinReclamacionSegundaLengua[0]['nombre'];
						// var_dump($validacion);

						foreach ( $resultadoEvaluacionILUD as $key => $value ) {
							if ($resultadoEvaluacionILUD [$key] ['observacion'] == "") {
								$resultadoEvaluacionILUD [$key] ['observacion'] = "Sin observaciones";
							}

							$mostrarHtml .= "<tr align='center'>
											<td align='left'>" . $resultadoEvaluacionILUD [$key] ['criterio'] . "</td>
											<td align='left'>" . $resultadoEvaluacionILUD [$key] ['puntaje_parcial'] . "</td>
											<td align='left'>" . $resultadoEvaluacionILUD [$key] ['observacion'] . "</td>
											<td align='left'>" . $resultadoEvaluacionILUD [$key] ['fecha_registro'] . "</td>
											<td align='left'>" . $resultadoEvaluacionILUD [$key] ['evaluador'] . "</td>
											<td align='left'>" . "</td>";
							$mostrarHtml .= "</tr>";
						}
						echo $mostrarHtml;
						unset ( $mostrarHtml );

						echo "</tbody>";
						echo "</table>";
					} else {
						$atributos ["id"] = "divNoEncontroEvaluacionesILUD";
						$atributos ["estilo"] = "";
						// $atributos["estiloEnLinea"]="display:none";
						echo $this->miFormulario->division ( "inicio", $atributos );

						// -------------Control Boton-----------------------
						$esteCampo = "noEncontroPerfil";
						$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
						$atributos ["etiqueta"] = "";
						$atributos ["estilo"] = "centrar";
						$atributos ["tipo"] = 'error';
						$atributos ["mensaje"] = "No se han realizado evaluaciones para la inscripción en la etapa de <b>" . $fechaFinReclamacionSegundaLengua [0] ['nombre'] . "</b>";
						echo $this->miFormulario->cuadroMensaje ( $atributos );
						unset ( $atributos );
						// -------------Fin Control Boton----------------------

						echo $this->miFormulario->division ( "fin" );
						// ------------------Division para los botones-------------------------
					}

					if ($resultadoEvaluacionILUD) {

						$esteCampo = "marcoConsultaPerfiles";
						$atributos ["estilo"] = "jqueryui";
						$atributos ["leyenda"] = "Reclamaciones";

						echo $this->miFormulario->marcoAgrupacion ( "inicio", $atributos );
						unset ( $atributos );

						// buscar reclamación
						$parametro = array (
								'consecutivo_inscrito' => $_REQUEST ['consecutivo_inscrito'],
								'reclamacion' => $resultadoEvaluacionILUD [0] ['id_reclamacion']
						);

						$cadena_sql = $this->miSql->getCadenaSql ( "reclamacionesILUD", $parametro );
						$reclamacionesValidacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

						if ($reclamacionesValidacion) {

							// buscar respuesta a la reclamación
							$parametro = array (
									'reclamacion' => $resultadoEvaluacionILUD [0] ['id_reclamacion']
							);
							$cadena_sql = $this->miSql->getCadenaSql ( "respuestaReclamacion", $parametro );
							$respuestaReclamacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

							// enlace para consultar los criterios asociados al tipo de jurado
							$variableDetalleRta = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
							$variableDetalleRta .= "&opcion=consultarDetalleRta";
							$variableDetalleRta .= "&consecutivo_concurso=" . $_REQUEST ['consecutivo_concurso'];
							$variableDetalleRta .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_inscrito'];
							$variableDetalleRta .= "&consecutivo_perfil=" . $_REQUEST ['consecutivo_perfil'];
							$variableDetalleRta .= "&reclamacion=" . $resultadoValidacion [0] ['id_reclamacion'];
							$variableDetalleRta .= "&campoSeguro=" . $_REQUEST ['tiempo'];
							$variableDetalleRta .= "&tiempo=" . time ();
							$variableDetalleRta = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableDetalleRta, $directorio );

							$parametro = array (
									'consecutivo_inscrito' => $_REQUEST ['consecutivo_inscrito'],
									'reclamacion' => $resultadoValidacion [0] ['id_reclamacion']
							);
							$cadena_sql = $this->miSql->getCadenaSql ( "consultaEvaluacionesReclamacion", $parametro );
							$validacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
							// var_dump($validacion);

							echo "<table id='tablaConsultaAspirantes' class='table table-striped table-bordered'>";
							echo "<thead>
						<tr align='center'>
								<th>Reclamación</th>
								<th>Criterio</th>
								<th>Observación</th>
								<th>Fecha</th>
								<th>¿Aplica la reclamación?</th>
								<th>Nueva Evaluación</th>
						</tr>
				</thead>
				<tbody> ";

							$mostrarHtml = "<tr align='center'>
										<td align='left'>" . $reclamacionesValidacion [0] ['id'] . "</td>
										<td align='left'>" . $reclamacionesValidacion [0] ['nombre'] . "</td>
										<td align='left'>" . $reclamacionesValidacion [0] ['observacion'] . "</td>
										<td align='left'>" . $reclamacionesValidacion [0] ['fecha_registro'] . "</td>";

							if ($respuestaReclamacion) {
								$mostrarHtml .= "<td align='left'>";
								$esteCampo = "detalle";
								$atributos ["id"] = $esteCampo;
								$atributos ['enlace'] = $variableDetalleRta;
								$atributos ['tabIndex'] = $esteCampo;
								$atributos ['redirLugar'] = true;
								$atributos ['estilo'] = 'clasico';
								$atributos ['enlaceTexto'] = $respuestaReclamacion [0] ['respuesta'];
								$atributos ['ancho'] = '25';
								$atributos ['alto'] = '25';
								// $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";

								$mostrarHtml .= $this->miFormulario->enlace ( $atributos );
								$mostrarHtml .= "</td>";
							} else {
								$mostrarHtml .= "<td align='left'>" . "Pendiente" . "</td>";
							}

							// $mostrarHtml .= "<td align='left'>"."Pendiente"."</td>";
							$mostrarHtml .= "<td align='left'>";
							if ($validacion [0] [0] == 2) {
								$variableValidacion = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
								$variableValidacion .= "&opcion=consultaNuevaEvaluacion";
								// $variableValidacion.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
								$variableValidacion .= "&id_usuario=" . $_REQUEST ['usuario'];
								$variableValidacion .= "&campoSeguro=" . $_REQUEST ['tiempo'];
								$variableValidacion .= "&tiempo=" . time ();
								$variableValidacion .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_concurso'];
								// $variableValidacion .= "&consecutivo_concurso=".$resultadoReclamaciones[$key]['id_concurso'];
								// $variableValidacion .= "&consecutivo_perfil=".$resultadoReclamaciones[$key]['consecutivo_perfil'];
								$variableValidacion .= "&reclamacion=" . $respuestaReclamacion [0] ['id_reclamacion'];
								$variableValidacion = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableValidacion, $directorio );

								// -------------Enlace-----------------------
								$esteCampo = "verEvaluacion";
								$esteCampo = 'enlace_hoja';
								$atributos ['id'] = $esteCampo;
								$atributos ['enlace'] = $variableValidacion;
								$atributos ['tabIndex'] = 0;
								$atributos ['columnas'] = 1;
								$atributos ['enlaceTexto'] = 'Ver Evaluación';
								$atributos ['estilo'] = 'clasico';
								$atributos ['enlaceImagen'] = $rutaBloque . "/images/xmag.png";
								$atributos ['posicionImagen'] = "atras"; // "adelante";
								$atributos ['ancho'] = '20px';
								$atributos ['alto'] = '20px';
								$atributos ['redirLugar'] = false;
								$atributos ['valor'] = '';
								$mostrarHtml .= $this->miFormulario->enlace ( $atributos );
								unset ( $atributos );
							} else {
								$mostrarHtml .= "Pendiente" . "</td>";
							}
							$mostrarHtml .= "</td>";
							$mostrarHtml .= "</tr>";

							echo $mostrarHtml;
							unset ( $mostrarHtml );

							echo "</tbody>";

							echo "</table>";
						} else {
							$atributos ["id"] = "divNoEncontroPerfil";
							$atributos ["estilo"] = "";
							// $atributos["estiloEnLinea"]="display:none";
							echo $this->miFormulario->division ( "inicio", $atributos );
							echo "<br></br>";

							// -------------Control Boton-----------------------
							$esteCampo = "noEncontroPerfil";
							$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
							$atributos ["etiqueta"] = "";
							$atributos ["estilo"] = "centrar";
							$atributos ["tipo"] = 'error';
							$atributos ["mensaje"] = "No se han realizado reclamaciones para la inscripción en la etapa de <b>" . $fechaFinReclamacionSegundaLengua [0] ['nombre'] . "</b>";
							echo $this->miFormulario->cuadroMensaje ( $atributos );
							unset ( $atributos );
							// -------------Fin Control Boton----------------------

							echo $this->miFormulario->division ( "fin" );
							// ------------------Division para los botones-------------------------

							$fecha = date ( "Y-m-d H:i:s" );
							if ($fecha <= $fechaFinReclamacionSegundaLengua [0] ['fecha_fin_reclamacion'] && ! $resultadoEvaluacionILUD) {
								$id_etapa = $fechaFinReclamacionSegundaLengua [0] ['consecutivo_calendario'];
								$etapa = $fechaFinReclamacionSegundaLengua [0] ['nombre'];

								$variableNuevo = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
								$variableNuevo .= "&bloque=" . $esteBloque ['nombre'];
								$variableNuevo .= "&bloqueGrupo=" . $esteBloque ["grupo"];
								$variableNuevo .= "&opcion=solicitarReclamacion";
								$variableNuevo .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_inscrito'];
								$variableNuevo .= "&consecutivo_concurso=" . $_REQUEST ['consecutivo_concurso'];
								$variableNuevo .= "&consecutivo_perfil=" . $_REQUEST ['consecutivo_perfil'];

								$variableNuevo .= "&consecutivo_actividad=" . $fechaFinReclamacionSegundaLengua [0] ['consecutivo_actividad'];
								$variableNuevo .= "&id_etapa=" . $id_etapa;
								$variableNuevo .= "&etapa=" . $etapa;

								$variableNuevo .= "&campoSeguro=" . $_REQUEST ['tiempo'];
								$variableNuevo .= "&tiempo=" . time ();
								$variableNuevo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableNuevo, $directorio );

								// enlace para hacer la reclamación
								echo "<div ><table width='20%' align='center'>
							<tr align='center'>
									<td align='center'>";
								$esteCampo = 'nuevaReclamacion';
								$atributos ['id'] = $esteCampo;
								$atributos ['enlace'] = $variableNuevo;
								$atributos ['tabIndex'] = 1;
								$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
								$atributos ['estilo'] = 'textoPequenno textoGris';
								$atributos ['enlaceImagen'] = $rutaBloque . "/images/new.png";
								$atributos ['posicionImagen'] = "atras"; // "adelante";
								$atributos ['ancho'] = '45px';
								$atributos ['alto'] = '45px';
								$atributos ['redirLugar'] = true;
								echo $this->miFormulario->enlace ( $atributos );
								unset ( $atributos );
								echo "            </td>
							</tr>
						</table></div> ";
							}
						}
					}

					// echo $this->miFormulario->marcoAgrupacion ( 'fin' );
					echo '</div>

</div> ';
					// ########################################################################################//

					// ############################# Evaluación de Competencias ###############################//

					echo '<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Evaluación Competencias Profesionales y Comunicativas</a>
			</h4>
		</div>
		<div id="collapse4" class="panel-collapse collapse">
        <br>';
					// consulta fecha máxima para realizar reclamación: Fase de EVALUACION DE COMPETENCIAS
					$parametro = array (
							'consecutivo_concurso' => $_REQUEST ['consecutivo_concurso'],
							'consecutivo_actividad' => $actividadesConReclamacion [3] ['consecutivo_actividad']
					);
					$cadena_sql = $this->miSql->getCadenaSql ( "fechaFinReclamacion", $parametro );
					$fechaFinReclamacionCompetencias = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

					if ($resultadoEvaluaciones) {
						echo "<table id='tablaConsultaAspirantes' class='table table-striped table-bordered'>";
						echo "<thead>
						<tr align='center'>
								<th>Criterio</th>
								<th>Puntaje</th>
								<th>Observación</th>
								<th>Fecha</th>
								<th>Evaluador</th>
						</tr>
                    </thead>
                    <tbody> ";

						$mostrarHtml = "";

						// $id_etapa=$fechaFinReclamacionCompetencias[0]['consecutivo_calendario'];
						// $etapa=$fechaFinReclamacionCompetencias[0]['nombre'];
						// var_dump($fechaFinReclamacionCompetencias);

						foreach ( $resultadoEvaluaciones as $key => $value ) {
							if ($resultadoEvaluaciones [$key] ['observacion'] == "") {
								$resultadoEvaluaciones [$key] ['observacion'] = "Sin observaciones";
							}

							$mostrarHtml .= "<tr align='center'>
														<td align='left'>" . $resultadoEvaluaciones [$key] ['criterio'] . "</td>
														<td align='left'>" . $resultadoEvaluaciones [$key] ['puntaje_parcial'] . "</td>
														<td align='left'>" . $resultadoEvaluaciones [$key] ['observacion'] . "</td>
														<td align='left'>" . $resultadoEvaluaciones [$key] ['fecha_registro'] . "</td>
														<td align='left'>" . $resultadoEvaluaciones [$key] ['evaluador'] . "</td>";
							$mostrarHtml .= "</tr>";
						}
						echo $mostrarHtml;
						unset ( $mostrarHtml );

						echo "</tbody>";

						echo "</table>";
					} else {

						$atributos ["id"] = "divNoEncontroEvaluacionesCompetencias";
						$atributos ["estilo"] = "";
						// $atributos["estiloEnLinea"]="display:none";
						echo $this->miFormulario->division ( "inicio", $atributos );

						// -------------Control Boton-----------------------
						$esteCampo = "noEncontroPerfil";
						$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
						$atributos ["etiqueta"] = "";
						$atributos ["estilo"] = "centrar";
						$atributos ["tipo"] = 'error';
						$atributos ["mensaje"] = "No se han realizado evaluaciones para la inscripción en la etapa de <b>" . $fechaFinReclamacionCompetencias [0] ['nombre'] . "</b>";
						echo $this->miFormulario->cuadroMensaje ( $atributos );
						unset ( $atributos );
						// -------------Fin Control Boton----------------------

						echo $this->miFormulario->division ( "fin" );
						// ------------------Division para los botones-------------------------
					}

					if ($resultadoEvaluaciones) {
						$esteCampo = "marcoConsultaPerfiles";
						$atributos ["estilo"] = "jqueryui";
						$atributos ["leyenda"] = "Reclamaciones";

						echo $this->miFormulario->marcoAgrupacion ( "inicio", $atributos );
						unset ( $atributos );

						// buscar reclamación
						$parametro = array (
								'consecutivo_inscrito' => $_REQUEST ['consecutivo_inscrito'],
								'reclamacion' => $resultadoEvaluaciones [0] ['id_reclamacion']
						);


						$parametro = array (
								'reclamacion' => $resultadoEvaluaciones [0] ['id_reclamacion'],
								'grupo'=>2//$_REQUEST['usuario']
						);
						$cadena_sql = $this->miSql->getCadenaSql ( "consultarDetalleReclamacion", $parametro );
						$resultadoDetalleReclamacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
						//var_dump($resultadoDetalleReclamacion);

						$cadena_sql = $this->miSql->getCadenaSql("consultaRespuestaReclamaciones", $parametro);
						$resultadoRespuestaReclamaciones = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
						//var_dump($resultadoRespuestaReclamaciones);

						echo "Reclamación # <br>";
						echo "Fecha de realización<br></br>";

						echo "<div style ='padding-left: 5%; padding-right: 5%;' class='cell-border'><table id='tablaResultadoReclamacion' class='table table-striped table-bordered'>";
						echo "<tbody>";

						$mostrarHtml = "";
						/*$mostrarHtml = "<tr>
														<th colspan='4'>Evaluación Reclamación</th>
														</tr>";*/


						$mostrarHtml .= "<tr align='center'>" . "<th colspan='1'>Grupo</th>
														<th colspan='1'>Criterio</th>
														<th colspan='1'>Calificación Anterior</th>
														<th colspan='1'>¿Aplica Reclamación?</th>
														<th colspan='1'>Nueva Calificación</th><tr>";




														foreach ( $resultadoDetalleReclamacion as $key => $value ) {

						                    $parametro = array (
																	'reclamacion' => $resultadoEvaluaciones [0] ['id_reclamacion'],
																	'criterio'=>$resultadoRespuestaReclamaciones[$key]['id_evaluar']
														    );

						                    // buscar datos Inactivos del criterio
						                    $cadena_sql = $this->miSql->getCadenaSql("consultaPuntajeInactivo", $parametro);
														    $resultadoPuntajeInactivo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

						                                    if($resultadoPuntajeInactivo){
																									//var_dump($key);
						                                        $puntajeInactivo=$resultadoPuntajeInactivo[0]['puntaje_parcial'];
						                                        $puntajeActivo=$resultadoDetalleReclamacion[$key]['puntaje_parcial'];
						                                    }else{
						                                        $puntajeInactivo=$resultadoDetalleReclamacion[$key]['puntaje_parcial'];
						                                    }
															$mostrarHtml .= "<tr>
																				<td colspan='1'>" .$resultadoDetalleReclamacion [$key] ['id_grupo']. "</td>
																				<td colspan='1'>" . $resultadoDetalleReclamacion [$key] ['nombre_criterio'] . "</td>
																															<td colspan='1'>" . $puntajeInactivo . "</td>
																															<td colspan='1'>" . $resultadoRespuestaReclamaciones [$key] ['respuesta'] . "</td>";

																					$mostrarHtml .= "<td align='center' colspan='1'>";
																					if($resultadoRespuestaReclamaciones [$key] ['respuesta']=='SI'){
																							$mostrarHtml .= $puntajeActivo;
																					}else{
																						  $mostrarHtml .= "No Aplica";
																					}
																					$mostrarHtml .= "</td>";
														}


														$mostrarHtml .= "<tr>
																			<td colspan='1'>" . "3" . "</td>
																			<td colspan='1'>" . "Prueba Escrita" . "</td>
																			<td colspan='1'>" . "16" . "</td>
																			<td colspan='1'>" . "Pendiente" . "</td>
																			<td colspan='1'>" . "Pendiente" . "</td></tr>";

																			$mostrarHtml .= "<tr>
																								<td colspan='1'>" . "3" . "</td>
																								<td colspan='1'>" . "Prueba Oral" . "</td>
																								<td colspan='1'>" . "9" . "</td>
																								<td colspan='1'>" . "Pendiente" . "</td>
																								<td colspan='1'>" . "Pendiente" . "</td></tr>";

														echo $mostrarHtml;
														unset ( $mostrarHtml );


						//###########################################
											echo "</tbody>";
											echo "</table>";


														echo "</div>";





						$parametro = array (
								'consecutivo_inscrito' => $_REQUEST ['consecutivo_inscrito'],
								'reclamacion' => $resultadoEvaluaciones [0] ['id_reclamacion']
						);

						// buscar reclamaciones realizadas
						$cadena_sql = $this->miSql->getCadenaSql ( "reclamacionesCompetencias", $parametro );
						$reclamacionesCompetencias = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

						if ($reclamacionesCompetencias) {
							// buscar respuesta a la reclamación
							$parametro = array (
									'reclamacion' => $resultadoEvaluacionILUD [0] ['id_reclamacion']
							);
							$cadena_sql = $this->miSql->getCadenaSql ( "respuestaReclamacion", $parametro );
							$respuestaReclamacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

							// enlace para consultar los criterios asociados al tipo de jurado
							$variableDetalleRta = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
							$variableDetalleRta .= "&opcion=consultarDetalleRta";
							$variableDetalleRta .= "&consecutivo_concurso=" . $_REQUEST ['consecutivo_concurso'];
							$variableDetalleRta .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_inscrito'];
							$variableDetalleRta .= "&consecutivo_perfil=" . $_REQUEST ['consecutivo_perfil'];
							$variableDetalleRta .= "&reclamacion=" . $resultadoValidacion [0] ['id_reclamacion'];
							$variableDetalleRta .= "&campoSeguro=" . $_REQUEST ['tiempo'];
							$variableDetalleRta .= "&tiempo=" . time ();
							$variableDetalleRta = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableDetalleRta, $directorio );

							$parametro = array (
									'consecutivo_inscrito' => $_REQUEST ['consecutivo_inscrito'],
									'reclamacion' => $resultadoValidacion [0] ['id_reclamacion']
							);
							$cadena_sql = $this->miSql->getCadenaSql ( "consultaEvaluacionesReclamacion", $parametro );
							$validacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
							// var_dump($validacion);



						} else {
							$atributos ["id"] = "divNoEncontroPerfil";
							$atributos ["estilo"] = "";
							// $atributos["estiloEnLinea"]="display:none";
							echo $this->miFormulario->division ( "inicio", $atributos );

							// -------------Control Boton-----------------------
							$esteCampo = "noEncontroPerfil";
							$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
							$atributos ["etiqueta"] = "";
							$atributos ["estilo"] = "centrar";
							$atributos ["tipo"] = 'error';
							$atributos ["mensaje"] = "No se han realizado reclamaciones para la inscripción en la etapa de <b>" . $fechaFinReclamacionCompetencias [0] ['nombre'] . "</b>";
							echo $this->miFormulario->cuadroMensaje ( $atributos );
							unset ( $atributos );
							// -------------Fin Control Boton----------------------

							echo $this->miFormulario->division ( "fin" );
							// ------------------Division para los botones-------------------------
						}

						$fecha = date ( "Y-m-d H:i:s" );

						if ($fecha <= $fechaFinReclamacionCompetencias [0] ['fecha_fin_reclamacion'] && ! $reclamacionesCompetencias) {

							$id_etapa = $fechaFinReclamacionCompetencias [0] ['consecutivo_calendario'];
							$etapa = $fechaFinReclamacionCompetencias [0] ['nombre'];

							$variableNuevo = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
							$variableNuevo .= "&bloque=" . $esteBloque ['nombre'];
							$variableNuevo .= "&bloqueGrupo=" . $esteBloque ["grupo"];
							$variableNuevo .= "&opcion=solicitarReclamacion";
							$variableNuevo .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_inscrito'];
							$variableNuevo .= "&consecutivo_concurso=" . $_REQUEST ['consecutivo_concurso'];
							$variableNuevo .= "&consecutivo_perfil=" . $_REQUEST ['consecutivo_perfil'];

							$variableNuevo .= "&consecutivo_actividad=" . $fechaFinReclamacionCompetencias [0] ['consecutivo_actividad'];
							$variableNuevo .= "&id_etapa=" . $id_etapa;
							$variableNuevo .= "&etapa=" . $etapa;

							$variableNuevo .= "&campoSeguro=" . $_REQUEST ['tiempo'];
							$variableNuevo .= "&tiempo=" . time ();
							$variableNuevo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableNuevo, $directorio );

							// enlace para hacer la reclamación
							echo "<div ><table width='20%' align='center'>
							<tr align='center'>
									<td align='center'>";
							$esteCampo = 'nuevaReclamacion';
							$atributos ['id'] = $esteCampo;
							$atributos ['enlace'] = $variableNuevo;
							$atributos ['tabIndex'] = 1;
							$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['estilo'] = 'textoPequenno textoGris';
							$atributos ['enlaceImagen'] = $rutaBloque . "/images/new.png";
							$atributos ['posicionImagen'] = "atras"; // "adelante";
							$atributos ['ancho'] = '45px';
							$atributos ['alto'] = '45px';
							$atributos ['redirLugar'] = true;
							echo $this->miFormulario->enlace ( $atributos );
							unset ( $atributos );
							echo "</td>
							</tr>
						</table></div> ";
						}
					}

					// echo $this->miFormulario->marcoAgrupacion ( 'fin' );

					echo '</div>

	</div> ';
					// ########################################################################################//

					// ################################ Evaluación Hoja de Vida ##################################//
					echo '<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Evaluación Hoja de Vida</a>
			</h4>
		</div>
		<div id="collapse3" class="panel-collapse collapse">
        <br>';

					// consulta fecha máxima para realizar reclamación: Fase de EVALUACION DE COMPETENCIAS
					$parametro = array (
							'consecutivo_concurso' => $_REQUEST ['consecutivo_concurso'],
							'consecutivo_actividad' => $actividadesConReclamacion [1] ['consecutivo_actividad']
					);
					$cadena_sql = $this->miSql->getCadenaSql ( "fechaFinReclamacion", $parametro );
					$fechaFinReclamacionHoja = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
					$id_etapa = $fechaFinReclamacionHoja [0] ['consecutivo_calendario'];
					$etapa = $fechaFinReclamacionHoja [0] ['nombre'];

					if ($resultadoEvaluacionesHoja) {

						echo "<table id='tablaConsultaAspirantes' class='table table-striped table-bordered'>";
						echo "<thead>
                <tr align='center'>
                        <th>Criterio</th>
                        <th>Puntaje</th>
                        <th>Observación</th>
                        <th>Fecha</th>
                        <th>Evaluador</th>
                </tr>
            </thead>
            <tbody> ";

						$mostrarHtml = "";

						foreach ( $resultadoEvaluacionesHoja as $key => $value ) {
							if ($resultadoEvaluacionesHoja [$key] ['observacion'] == "") {
								$resultadoEvaluacionesHoja [$key] ['observacion'] = "Sin observaciones";
							}

							$mostrarHtml .= "<tr align='center'>
												<td align='left'>" . $resultadoEvaluacionesHoja [$key] ['criterio'] . "</td>
												<td align='left'>" . $resultadoEvaluacionesHoja [$key] ['puntaje_parcial'] . "</td>
												<td align='left'>" . $resultadoEvaluacionesHoja [$key] ['observacion'] . "</td>
												<td align='left'>" . $resultadoEvaluacionesHoja [$key] ['fecha_registro'] . "</td>
												<td align='left'>" . $resultadoEvaluacionesHoja [$key] ['evaluador'] . "</td>";
							$mostrarHtml .= "</tr>";
						}
						echo $mostrarHtml;
						unset ( $mostrarHtml );

						echo "</tbody>";

						echo "</table>";
					}

					else {

						$atributos ["id"] = "divNoEncontroEvaluacionesHoja";
						$atributos ["estilo"] = "";
						// $atributos["estiloEnLinea"]="display:none";
						echo $this->miFormulario->division ( "inicio", $atributos );

						// -------------Control Boton-----------------------
						$esteCampo = "noEncontroPerfil";
						$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
						$atributos ["etiqueta"] = "";
						$atributos ["estilo"] = "centrar";
						$atributos ["tipo"] = 'error';
						$atributos ["mensaje"] = "No se han realizado evaluaciones para la inscripción en la etapa de <b>" . $fechaFinReclamacionHoja [0] ['nombre'] . "</b>";
						echo $this->miFormulario->cuadroMensaje ( $atributos );
						unset ( $atributos );
						// -------------Fin Control Boton----------------------

						echo $this->miFormulario->division ( "fin" );
						// ------------------Division para los botones-------------------------
					}

					if ($resultadoEvaluacionesHoja) {
						$esteCampo = "marcoConsultaPerfiles";
						$atributos ["estilo"] = "jqueryui";
						$atributos ["leyenda"] = "Reclamaciones";

						echo $this->miFormulario->marcoAgrupacion ( "inicio", $atributos );

						// buscar reclamación
						$parametro = array (
								'consecutivo_inscrito' => $_REQUEST ['consecutivo_inscrito'],
								'reclamacion' => $resultadoEvaluacionesHoja [0] ['id_reclamacion']
						);
						// buscar reclamaciones realizadas
						$cadena_sql = $this->miSql->getCadenaSql ( "reclamacionesCompetencias", $parametro );
						$reclamacionesHoja = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
						// var_dump($reclamacionesHoja);

						if ($reclamacionesHoja) {
							// buscar respuesta a la reclamación
							$parametro = array (
									'reclamacion' => $reclamacionesHoja [0] ['id']
							);
							$cadena_sql = $this->miSql->getCadenaSql ( "respuestaReclamacion", $parametro );
							$respuestaReclamacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

							// enlace para consultar los criterios asociados al tipo de jurado
							$variableDetalleRta = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
							$variableDetalleRta .= "&opcion=consultarDetalleRta";
							$variableDetalleRta .= "&consecutivo_concurso=" . $_REQUEST ['consecutivo_concurso'];
							$variableDetalleRta .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_inscrito'];
							$variableDetalleRta .= "&consecutivo_perfil=" . $_REQUEST ['consecutivo_perfil'];
							$variableDetalleRta .= "&reclamacion=" . $resultadoValidacion [0] ['id_reclamacion'];
							$variableDetalleRta .= "&campoSeguro=" . $_REQUEST ['tiempo'];
							$variableDetalleRta .= "&tiempo=" . time ();
							$variableDetalleRta = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableDetalleRta, $directorio );

							$parametro = array (
									'consecutivo_inscrito' => $_REQUEST ['consecutivo_inscrito'],
									'reclamacion' => $resultadoValidacion [0] ['id_reclamacion']
							);
							$cadena_sql = $this->miSql->getCadenaSql ( "consultaEvaluacionesReclamacion", $parametro );
							$validacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
							// var_dump($validacion);

							echo "<table id='tablaConsultaAspirantes' class='table table-striped table-bordered'>";
							echo "<thead>
							<tr align='center'>
									<th>Reclamación</th>
									<th>Criterio</th>
									<th>Observación</th>
									<th>Fecha</th>
									<th>¿Aplica la reclamación?</th>
									<th>Nueva Evaluación</th>
							</tr>
					</thead>
					<tbody> ";

							$mostrarHtml = "";
							foreach ( $reclamacionesHoja as $key => $value ) {

								$mostrarHtml .= "<tr align='center'>
											<td align='left'>" . $reclamacionesHoja [$key] ['id'] . "</td>
											<td align='left'>" . $reclamacionesHoja [$key] ['nombre'] . "</td>
											<td align='left'>" . $reclamacionesHoja [$key] ['observacion'] . "</td>
											<td align='left'>" . $reclamacionesHoja [$key] ['fecha_registro'] . "</td>";

								if ($respuestaReclamacion) {
									$mostrarHtml .= "<td align='left'>";
									$esteCampo = "detalle";
									$atributos ["id"] = $esteCampo;
									$atributos ['enlace'] = $variableDetalleRta;
									$atributos ['tabIndex'] = $esteCampo;
									$atributos ['redirLugar'] = true;
									$atributos ['estilo'] = 'clasico';
									$atributos ['enlaceTexto'] = $respuestaReclamacion [0] ['respuesta'];
									$atributos ['ancho'] = '25';
									$atributos ['alto'] = '25';
									// $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";

									$mostrarHtml .= $this->miFormulario->enlace ( $atributos );
									$mostrarHtml .= "</td>";
								} else {
									$mostrarHtml .= "<td align='left'>" . "Pendiente" . "</td>";
								}

								// $mostrarHtml .= "<td align='left'>"."Pendiente"."</td>";
								$mostrarHtml .= "<td align='left'>";
								if ($validacion [0] [0] == 2) {
									$variableValidacion = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
									$variableValidacion .= "&opcion=consultaNuevaEvaluacion";
									// $variableValidacion.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
									$variableValidacion .= "&id_usuario=" . $_REQUEST ['usuario'];
									$variableValidacion .= "&campoSeguro=" . $_REQUEST ['tiempo'];
									$variableValidacion .= "&tiempo=" . time ();
									$variableValidacion .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_concurso'];
									// $variableValidacion .= "&consecutivo_concurso=".$resultadoReclamaciones[$key]['id_concurso'];
									// $variableValidacion .= "&consecutivo_perfil=".$resultadoReclamaciones[$key]['consecutivo_perfil'];
									$variableValidacion .= "&reclamacion=" . $respuestaReclamacion [0] ['id_reclamacion'];
									$variableValidacion = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableValidacion, $directorio );

									// -------------Enlace-----------------------
									$esteCampo = "verEvaluacion";
									$esteCampo = 'enlace_hoja';
									$atributos ['id'] = $esteCampo;
									$atributos ['enlace'] = $variableValidacion;
									$atributos ['tabIndex'] = 0;
									$atributos ['columnas'] = 1;
									$atributos ['enlaceTexto'] = 'Ver Evaluación';
									$atributos ['estilo'] = 'clasico';
									$atributos ['enlaceImagen'] = $rutaBloque . "/images/xmag.png";
									$atributos ['posicionImagen'] = "atras"; // "adelante";
									$atributos ['ancho'] = '20px';
									$atributos ['alto'] = '20px';
									$atributos ['redirLugar'] = false;
									$atributos ['valor'] = '';
									$mostrarHtml .= $this->miFormulario->enlace ( $atributos );
									unset ( $atributos );
								} else {
									$mostrarHtml .= "Pendiente" . "</td>";
								}
								$mostrarHtml .= "</td>";
								$mostrarHtml .= "</tr>";
							}

							echo $mostrarHtml;
							unset ( $mostrarHtml );

							echo "</tbody>";
							echo "</table>";
						} else {
							$atributos ["id"] = "divNoEncontroPerfil";
							$atributos ["estilo"] = "";
							// $atributos["estiloEnLinea"]="display:none";
							echo $this->miFormulario->division ( "inicio", $atributos );

							// -------------Control Boton-----------------------
							$esteCampo = "noEncontroPerfil";
							$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
							$atributos ["etiqueta"] = "";
							$atributos ["estilo"] = "centrar";
							$atributos ["tipo"] = 'error';
							$atributos ["mensaje"] = "No se han realizado reclamaciones para la inscripción en la etapa de <b>" . $fechaFinReclamacionHoja [0] ['nombre'] . "</b>";
							echo $this->miFormulario->cuadroMensaje ( $atributos );
							unset ( $atributos );
							// -------------Fin Control Boton----------------------

							echo $this->miFormulario->division ( "fin" );
							// ------------------Division para los botones-------------------------

							if ($fecha <= $fechaFinReclamacionHoja [0] ['fecha_fin_reclamacion'] && ! $reclamacionesHoja) {

								$id_etapa = $fechaFinReclamacionHoja [0] ['consecutivo_calendario'];
								$etapa = $fechaFinReclamacionHoja [0] ['nombre'];

								$variableNuevo = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
								$variableNuevo .= "&bloque=" . $esteBloque ['nombre'];
								$variableNuevo .= "&bloqueGrupo=" . $esteBloque ["grupo"];
								$variableNuevo .= "&opcion=solicitarReclamacion";
								$variableNuevo .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_inscrito'];
								$variableNuevo .= "&consecutivo_concurso=" . $_REQUEST ['consecutivo_concurso'];
								$variableNuevo .= "&consecutivo_perfil=" . $_REQUEST ['consecutivo_perfil'];

								$variableNuevo .= "&consecutivo_actividad=" . $fechaFinReclamacionHoja [0] ['consecutivo_actividad'];
								$variableNuevo .= "&id_etapa=" . $id_etapa;
								$variableNuevo .= "&etapa=" . $etapa;

								$variableNuevo .= "&campoSeguro=" . $_REQUEST ['tiempo'];
								$variableNuevo .= "&tiempo=" . time ();
								$variableNuevo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableNuevo, $directorio );

								// enlace para hacer la reclamación
								echo "<div ><table width='20%' align='center'>
								<tr align='center'>
										<td align='center'>";
								$esteCampo = 'nuevaReclamacion';
								$atributos ['id'] = $esteCampo;
								$atributos ['enlace'] = $variableNuevo;
								$atributos ['tabIndex'] = 1;
								$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
								$atributos ['estilo'] = 'textoPequenno textoGris';
								$atributos ['enlaceImagen'] = $rutaBloque . "/images/new.png";
								$atributos ['posicionImagen'] = "atras"; // "adelante";
								$atributos ['ancho'] = '45px';
								$atributos ['alto'] = '45px';
								$atributos ['redirLugar'] = true;
								echo $this->miFormulario->enlace ( $atributos );
								unset ( $atributos );
								echo "            </td>
								</tr>
							</table></div> ";
							}
						}
					}

					// echo $this->miFormulario->marcoAgrupacion ( 'fin' );
					echo '</div>
	</div> ';
				}

				else {

					$atributos ["id"] = "divNoEncontroEvaluaciones";
					$atributos ["estilo"] = "";
					// $atributos["estiloEnLinea"]="display:none";
					echo $this->miFormulario->division ( "inicio", $atributos );

					// -------------Control Boton-----------------------
					$esteCampo = "noEncontroPerfil";
					$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
					$atributos ["etiqueta"] = "";
					$atributos ["estilo"] = "centrar";
					$atributos ["tipo"] = 'error';
					$atributos ["mensaje"] = "No se han realizado evaluaciones para la inscripción";
					echo $this->miFormulario->cuadroMensaje ( $atributos );
					unset ( $atributos );
					// -------------Fin Control Boton----------------------

					echo $this->miFormulario->division ( "fin" );
					// ------------------Division para los botones-------------------------
				}

				echo $this->miFormulario->marcoAgrupacion ( 'fin' );

				// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
				// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
				// Se debe declarar el mismo atributo de marco con que se inició el formulario.
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
