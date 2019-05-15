<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use gestionConcurso\gestionInscripcion\funcion\redireccion;

class registrarForm {
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
		//$esteCampo = $esteBloque ['nombre'];
		$esteCampo = "asignar";
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
			$variable.= "&opcion=detalle";
			$variable.= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
			$variable.= "#tabJurados";

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

			$esteCampo = "marcoAsignarAspirantes";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{


				// ---------------- CONTROL AGRUPACION: Cuadro Agrupacion --------------------------------------------------------
				$atributos ["id"] = "cuadro_criterio";
				$atributos ["estiloEnLinea"] = "display:block";
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{

                                    $parametro['consecutivo_concurso']=$_REQUEST['consecutivo_concurso'];
                                    if(isset($_REQUEST['seleccionJurado']))
                                        { $rol=explode('-',$_REQUEST['seleccionJurado']); 
                                          $parametro['rol']= $rol[0];
                                          $parametro['id_usuario']= $rol[1];
                                        }
                                    else if(isset($_REQUEST['seleccionEvaluador'])) 
                                        { $rol=explode('-',$_REQUEST['seleccionEvaluador']); 
                                          $parametro['rol']= $rol[0];
                                          $parametro['id_usuario']= $rol[1];
                                        }
                                    else{ $parametro['id_usuario']='';}

                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarAspirantesNoAsignados", $parametro);
                        $aspirantes = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                        //var_dump($aspirantes);

                        if($aspirantes){

                        echo "<div class='cell-border'><table id='tablaConsultaAspirante' class='table table-striped table-bordered'>";
                        echo "<thead>
                                    <tr align='center'>
                                        <th>
                                         <input name='seleccionarTodo' id='seleccionarTodo' value='3' tabindex='4' class='justificado validate[]' type='checkbox'>
                                                        Seleccionar
                                        </th>
                                        <th>Inscripción</th>
                                        <th>Tipo Identificación</th>
                                        <th>Identificación</th>
                                        <th>Nombre</th>
                                        <th>Código</th>
                                        <th>Perfil</th>
                                    </tr>
                                </thead>
                                <tbody>";

                        if($aspirantes){
                                $primero=$aspirantes[0]['consecutivo_inscrito'];
                                foreach($aspirantes as $key=>$value ){

                                        $mostrarHtml = "<tr align='center'>
                                                         <td align='center'  width='8%'  >";

                                        // ---------------- CONTROL: Checkbox -----------
                                        $esteCampo = 'seleccion'.$aspirantes[$key]['consecutivo_inscrito'];
                                        $atributos ['id'] = $esteCampo;
                                        $atributos ['nombre'] = $esteCampo;
                                        $atributos ['marco'] = true;
                                        $atributos ['estiloMarco'] = true;
                                        $atributos ["etiquetaObligatorio"] = true;
                                        $atributos ['columnas'] = 1;
                                        $atributos ['dobleLinea'] = 1;
                                        $atributos ['tabIndex'] = $tab;
                                        $atributos ['etiqueta'] = "";
                                        $atributos ['seleccionado'] = false;
                                        $atributos ['valor'] = $aspirantes[$key]['consecutivo_inscrito'];

                                        $atributos ['estilo'] = 'justificado';
                                        $atributos ['eventoFuncion'] = ' ';
                                        $atributos ['validar'] = '';
                                        $atributos ['deshabilitado'] = false;
                                        $tab ++;
                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                        $mostrarHtml .= $this->miFormulario->campoCuadroSeleccion ( $atributos );


                                        $mostrarHtml.="</td>";


                                        $mostrarHtml .= "<td align='left' width='10%' >".$aspirantes[$key]['consecutivo_inscrito']."</td>
                                                            <td align='left' width='6%' >".$aspirantes[$key]['tipo_identificacion']."</td>
                                                            <td align='left' width='10%'>".$aspirantes[$key]['identificacion']."</td>
                                                            <td align='left'>".$aspirantes[$key]['nombre']."</td>
                                                            <td align='left' width='10%' >".$aspirantes[$key]['codigo']."</td>
                                                            <td align='left'>".$aspirantes[$key]['perfil']."</td>";
                                        $mostrarHtml .= "</tr>";
                                        echo $mostrarHtml;
                                        unset($mostrarHtml);
                                        unset($variable);
                                }

                                if($key>0){
                                        $ultimo=$aspirantes[$key]['consecutivo_inscrito'];
                                        $valores= $primero . ",".$ultimo;
                                }else{
                                        $valores= $primero;
                                }

                        }
                        echo "</tbody>";
                        echo "</table></div>";

                        // ////////////////Hidden////////////
                        $esteCampo = 'aspirantes';
                        $atributos ["id"] = $esteCampo;
                        $atributos ["tipo"] = "hidden";
                        $atributos ['estilo'] = '';
                        $atributos ['validar'] = '';
                        $atributos ["obligatorio"] = true;
                        $atributos ['marco'] = true;
                        $atributos ["etiqueta"] = "";

                        $atributos = array_merge ( $atributos, $atributosGlobales );
                        echo $this->miFormulario->campoCuadroTexto ( $atributos );
                        unset ( $atributos );

                        // ////////////////Hidden////////////
                        $esteCampo = 'numeroAspirantes';
                        $atributos ["id"] = $esteCampo;
                        $atributos ["tipo"] = "hidden";
                        $atributos ['estilo'] = '';
                        $atributos ['validar'] = '';
                        $atributos ["obligatorio"] = true;
                        $atributos ['marco'] = true;
                        $atributos ["etiqueta"] = "";
                        $atributos ['valor'] = $valores;

                        $atributos = array_merge ( $atributos, $atributosGlobales );
                        echo $this->miFormulario->campoCuadroTexto ( $atributos );
                        unset ( $atributos );

                        }else
                         {
				 $atributos["id"]="divNoEncontroCriterio";
				 $atributos["estilo"]="";
		//$atributos["estiloEnLinea"]="display:none";
				 echo $this->miFormulario->division("inicio",$atributos);

				 //-------------Control Boton-----------------------
				 $esteCampo = "noEncontroAspirantes";
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
echo $this->miFormulario->division ( "fin" );
unset ( $atributos );
// ---------------- CONTROL: Fin Cuadro Agrupacion --------------------------------------------------------


if($aspirantes){
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonGuardarAsignacion';
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
					$atributos ['nombreFormulario'] = "asignar";
					$tab ++;

					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoBoton ( $atributos );
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------
				}
				echo $this->miFormulario->division ( 'fin' );
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

			$valorCodificado = "action=" . $esteBloque ["nombre"];
			$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=guardarAspirantesJurado";
			$valorCodificado .= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];

			if(isset($_REQUEST['seleccionJurado'])){
                            $valorCodificado .= "&seleccionJurado=" . $_REQUEST['seleccionJurado'];
                            $valorCodificado .= "&tab=tabJurados";
                        }else if(isset($_REQUEST['seleccionEvaluador'])) {
                            $valorCodificado .= "&seleccionJurado=" . $_REQUEST['seleccionEvaluador'];
                            $valorCodificado .= "&tab=tabEvaluadores";
                        }

                        $parametro['concurso']=$_REQUEST['consecutivo_concurso'];
                        if(isset($_REQUEST['seleccionJurado'])){
                            $parametro['usuario']= $_REQUEST['seleccionJurado'];
                        }else if(isset($_REQUEST['seleccionEvaluador'])) {
                            $parametro['usuario']= $_REQUEST['seleccionEvaluador'];
                        }else{
                            $parametro['usuario']='';
                        }

                                            //buscar tipo de jurado
                          $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultaJurado3", $parametro);
                          $tipo = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

                        if(isset($_REQUEST ["tipoJurado"])){
                                $valorCodificado .= "&tipoJurado=" . $_REQUEST ["tipoJurado"];
                            }
                        else if($tipo)
                            { $valorCodificado .= "&tipoJurado=" . $tipo[0]['id_jurado_tipo'];}
                        else{
                                $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultaTipoInterno", $parametro);
                                $tipoInterno = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
                                if($tipoInterno){
                                                $valorCodificado .= "&tipoJurado=" . $tipoInterno[0]['id'];
                                }
                            }

			$valorCodificado .= "&nombre_concurso=" . $_REQUEST ["nombre_concurso"];

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
