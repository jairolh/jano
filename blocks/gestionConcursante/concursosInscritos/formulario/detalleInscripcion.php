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
      $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
      $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
      $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

			$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );

			$variable = "pagina=" . $miPaginaActual;
			//$variable .= "&opcion=detalleConcurso";
			//$variable .= "&id_concurso=".$_REQUEST['id_concurso'];
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );

			$cadena_sql = $this->miSql->getCadenaSql("consultarValidacion", $_REQUEST['consecutivo_inscrito']);
			$resultadoValidacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

			$cadena_sql = $this->miSql->getCadenaSql("consultarEvaluacion", $_REQUEST['consecutivo_inscrito']);
			$resultadoEvaluaciones = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

			$cadena_sql = $this->miSql->getCadenaSql("consultarEvaluacionFinal", $_REQUEST['consecutivo_inscrito']);
			$resultadoEvaluacionFinal = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
      $esteCampo = 'botonRegresar';
      $atributos ['id'] = $esteCampo;
      $atributos ['enlace'] = $variable;
      $atributos ['tabIndex'] = $tab;
      $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
      $atributos ['estilo'] = 'textoPequenno textoGris';
      $atributos ['enlaceImagen'] = $rutaBloque."/images/player_rew.png";
      $atributos ['posicionImagen'] = "atras";//"adelante";
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
			$atributos ["leyenda"] = "<b>Perfil de Concurso: "/*.$resultadoPerfil[0]['perfil']*/."</b>";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{

				if($resultadoEvaluaciones){
						//-----------------Inicio de Conjunto de Controles----------------------------------------
								$esteCampo = "marcoConsultaPerfiles";
								$atributos["estilo"] = "jqueryui";
								$atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
								//echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
								unset($atributos);


echo '<div class="panel-group" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Validación de requisitos</a>
        </h4>
      </div>';

echo '
      <div id="collapse1" class="panel-collapse collapse in">';

			echo "<table id='tablaConsultaAspirantes' class='table table-striped table-bordered'>";
			echo "<thead>
							<tr align='center'>
									<th>Resultado</th>
									<th>Observación</th>
									<th>Fecha</th>
									<th>Reclamaciones</th>
							</tr>
					</thead>
					<tbody> ";

			$mostrarHtml = "<tr align='center'>
											<td align='left'>".$resultadoValidacion[0]['cumple_requisito']."</td>
											<td align='left'>".$resultadoValidacion[0]['observacion']."</td>
											<td align='left'>".$resultadoValidacion[0]['fecha_registro']."</td>
											<td align='left'>"."</td>";
			$mostrarHtml .= "</tr>";

			echo $mostrarHtml;
			unset($mostrarHtml);

			echo "</tbody>";

			echo "</table>";

echo '</div>';
echo '</div>';

    echo '<div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Evaluación Competencias Profesionales y Comunicativas</a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse">';

			echo "<table id='tablaConsultaAspirantes' class='table table-striped table-bordered'>";
			echo "<thead>
							<tr align='center'>
									<th>Criterio</th>
									<th>Puntaje</th>
									<th>Observación</th>
									<th>Fecha</th>
									<th>Evaluador</th>
									<th>Reclamaciones</th>
							</tr>
					</thead>
					<tbody> ";
					$mostrarHtml = "";
			foreach($resultadoEvaluaciones as $key=>$value ){
				if ($resultadoEvaluaciones[$key]['observacion']==""){
					$resultadoEvaluaciones[$key]['observacion']="Sin observaciones";
				}

				$mostrarHtml .= "<tr align='center'>
												<td align='left'>".$resultadoEvaluaciones[$key]['criterio']."</td>
												<td align='left'>".$resultadoEvaluaciones[$key]['puntaje_parcial']."</td>
												<td align='left'>".$resultadoEvaluaciones[$key]['observacion']."</td>
												<td align='left'>".$resultadoEvaluaciones[$key]['fecha_registro']."</td>
												<td align='left'>".$resultadoEvaluaciones[$key]['evaluador']."</td>
												<td align='left'>"."</td>";
				$mostrarHtml .= "</tr>";
			}
			echo $mostrarHtml;
			unset($mostrarHtml);

			echo "</tbody>";

			echo "</table>";
    echo '</div>

  </div> ';


	echo '<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Evaluación Competencias Profesionales y Comunicativas</a>
			</h4>
		</div>
		<div id="collapse3" class="panel-collapse collapse">';

		echo "<table id='tablaConsultaAspirantes' class='table table-striped table-bordered'>";
		echo "<thead>
						<tr align='center'>
								<th>Criterio</th>
								<th>Puntaje</th>
								<th>Observación</th>
								<th>Aprobación</th>
						</tr>
				</thead>
				<tbody> ";
				$mostrarHtml = "";
		foreach($resultadoEvaluacionFinal as $key=>$value ){
			if ($resultadoEvaluacionFinal[$key]['observacion']==""){
				$resultadoEvaluacionFinal[$key]['observacion']="Sin observaciones";
			}

			$mostrarHtml .= "<tr align='center'>
											<td align='left'>".$resultadoEvaluacionFinal[$key]['nombre']."</td>
											<td align='left'>".$resultadoEvaluacionFinal[$key]['puntaje_final']."</td>
											<td align='left'>".$resultadoEvaluacionFinal[$key]['observacion']."</td>
											<td align='left'>".$resultadoEvaluacionFinal[$key]['aprobo']."</td>";
			$mostrarHtml .= "</tr>";
		}
		echo $mostrarHtml;
		unset($mostrarHtml);



		echo "</tbody>";

		echo "</table>";

		$esteCampo = "marcoConsultaPerfiles";
		$atributos["estilo"] = "jqueryui";
		$atributos["leyenda"] = "Reclamaciones";

		echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
		unset($atributos);

		//buscar reclamaciones realizadas
		$reclamaciones=false;

		if($reclamaciones){

		}else{
			$atributos["id"]="divNoEncontroPerfil";
			$atributos["estilo"]="";
			//$atributos["estiloEnLinea"]="display:none";
			echo $this->miFormulario->division("inicio",$atributos);

			//-------------Control Boton-----------------------
			$esteCampo = "noEncontroPerfil";
			$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
			$atributos["etiqueta"] = "";
			$atributos["estilo"] = "centrar";
			$atributos["tipo"] = 'error';
			$atributos["mensaje"] = "No se han realizado reclamaciones para la inscripción";
			echo $this->miFormulario->cuadroMensaje($atributos);
			unset($atributos);
			//-------------Fin Control Boton----------------------

		 echo $this->miFormulario->division("fin");
			//------------------Division para los botones-------------------------
		}

		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botones";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division ( "inicio", $atributos );
		unset ( $atributos );
		{
			// -----------------CONTROL: Botón ----------------------------------------------------------------
			$esteCampo = 'botonSolicitarReclamacion';
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
		}
		echo $this->miFormulario->division ( 'fin' );

		echo $this->miFormulario->marcoAgrupacion ( 'fin' );

	echo '</div>

	</div> ';


				}

				else{
					$atributos["id"]="divNoEncontroPerfil";
					$atributos["estilo"]="";
					//$atributos["estiloEnLinea"]="display:none";
					echo $this->miFormulario->division("inicio",$atributos);

					//-------------Control Boton-----------------------
					$esteCampo = "noEncontroPerfil";
					$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
					$atributos["etiqueta"] = "";
					$atributos["estilo"] = "centrar";
					$atributos["tipo"] = 'error';
					$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
					echo $this->miFormulario->cuadroMensaje($atributos);
					unset($atributos);
					//-------------Fin Control Boton----------------------

				 echo $this->miFormulario->division("fin");
					//------------------Division para los botones-------------------------

				}


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

			//$valorCodificado = "action=" . $esteBloque ["nombre"];
			$valorCodificado = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=solicitarReclamacion";
			$valorCodificado .= "&consecutivo_inscrito=".$_REQUEST['consecutivo_inscrito'];
			//$valorCodificado .= "&perfil=".$resultadoPerfil[0]['consecutivo_perfil'];
			//$valorCodificado .= "&nombre_perfil=".$resultadoPerfil[0]['perfil'];

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

$miSeleccionador = new consultaForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
