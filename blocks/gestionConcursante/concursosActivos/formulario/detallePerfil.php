<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use gestionConcursante\concursosActivos\funcion\redireccion;

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

                $usuario=$this->miSesion->getSesionUsuarioId();
                
                //buscar consecutivo_persona
                $tam=2;
                if(strtoupper(substr($usuario,0,1))!='C')
                    {$tam=3;}
                //buscar consecutivo_persona
                $tipo=strtoupper(substr($usuario,0,$tam));
                $id=substr($usuario,$tam);
                $persona = array('tipo_identificacion'=> $tipo,'identificacion'=> $id);
		//buscar el consecutivo de la persona
		$cadena_sql = $this->miSql->getCadenaSql("consultaConsecutivo", $persona);
		$resultadoPersona = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                
                $variable = "pagina=" . $miPaginaActual;
                $variable .= "&opcion=detalleConcurso";
                $variable .= "&id_concurso=".$_REQUEST['id_concurso'];
                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );

                $cadena_sql = $this->miSql->getCadenaSql("consultaPerfil", $_REQUEST['id_perfil']);
                $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                $parametro=array('consecutivo_concurso'=>$_REQUEST['id_concurso'],
                                 'fecha_actual' => date("Y-m-d"),
                                 'fase'=>'Inscripción',
				 'usuario'=> $resultadoPersona[0][0]
                        );
                $cadena_sql = $this->miSql->getCadenaSql("consultaCalendario", $parametro);
                $resultadoCalendar = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                
                $cadena_sql = $this->miSql->getCadenaSql("consultaInscripciones", $parametro);
                $resultadoInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                
                //---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
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
			$atributos ["leyenda"] = "<b>Perfil de Concurso: ".$resultadoPerfil[0]['perfil']."</b>";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{

				if($resultadoPerfil){
					if($resultadoPerfil[0]['estado']=='A'){
						$resultadoPerfil[0]['estado']="Activo";
					}else{
						$resultadoPerfil[0]['estado']="Inactivo";
					}

                                //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoConsultaPerfiles";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);

                                echo "<div style ='width: 80%; padding-left: 10%;' class='cell-border'><table id='tablaPerfiles' class='table table-striped table-bordered'>";
                                echo "<tbody>";
                                $mostrarHtml = "<tr align='center'>
                                                        <th class='textoAzul'>Concurso</th>
                                                        <td class='table-tittle estilo_tr '>".$resultadoPerfil[0]['concurso']."</td>
                                               </tr>";
                                $mostrarHtml .= "<tr align='center'>
                                                        <th class='textoAzul'>Código Perfil</th>
                                                        <td class='table-tittle estilo_tr '>".$resultadoPerfil[0]['codigo']."</td>
                                               </tr>";
                                $mostrarHtml .= "<tr align='center'>
                                                        <th class='textoAzul'>Perfil</th>
                                                        <td class='table-tittle estilo_tr '>".$resultadoPerfil[0]['perfil']."</td>
                                               </tr>";
                                $mostrarHtml .= "<tr align='center'>
                                                        <th class='textoAzul'>Dependencia</th>
                                                        <td class='table-tittle estilo_tr '>".$resultadoPerfil[0]['dependencia']."</td>
                                               </tr>";
                                $mostrarHtml .= "<tr align='center'>
                                                        <th class='textoAzul'>Area</td>
                                                        <td class='table-tittle estilo_tr '>".$resultadoPerfil[0]['area']."</td>
                                               </tr>";
                                $mostrarHtml .= "<tr align='center'>
                                                        <th class='textoAzul'>Vacantes</th>
                                                        <td class='table-tittle estilo_tr '>".$resultadoPerfil[0]['vacantes']."</td>
                                               </tr>";
                                $mostrarHtml .= "<tr align='center'>
                                                        <th class='textoAzul'>Descripción</th>
                                                        <td class='table-tittle estilo_tr '>".$resultadoPerfil[0]['descripcion']."</td>
                                               </tr>";
                                $mostrarHtml .= "<tr align='center'>
                                                        <th class='textoAzul'>Requisitos</th>
                                                        <td class='table-tittle estilo_tr '>".$resultadoPerfil[0]['requisitos']."</td>
                                               </tr>";

                                echo $mostrarHtml;
                                unset($mostrarHtml);
                                echo "</tbody>";
                                echo "</table></div>";
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
					$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
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
					/*
					$esteCampo = 'botonInscribir';
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
					*/
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------


					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonInscribir';
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
                                        
                                        if($resultadoCalendar && $resultadoInscripcion[0]['inscrito'] < $resultadoPerfil[0]['max_inscribe'] )
                                            {   echo $this->miFormulario->campoBoton ( $atributos );    }
                                        elseif($resultadoInscripcion[0]['inscrito'] >= $resultadoPerfil[0]['max_inscribe'])
                                            {   //div limite de inscripciones hechas
                                                $atributos["id"]="divNoInscripcion";
                                                $atributos["estilo"]="";
                                                //$atributos["estiloEnLinea"]="display:none";
                                                echo $this->miFormulario->division("inicio",$atributos);
                                                //-------------Control Boton-----------------------
                                                $esteCampo = "limiteInscripcion";
                                                $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                                                $atributos["etiqueta"] = "";
                                                $atributos["estilo"] = "centrar";
                                                $atributos["tipo"] = 'warning';
                                                $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
                                                echo $this->miFormulario->cuadroMensaje($atributos);
                                                unset($atributos);
                                                //-------------Fin Control Boton----------------------
                                                 echo $this->miFormulario->division("fin");}    
                                        else{
                                                //div no inicia fecha inscripcion
                                                $atributos["id"]="divNoEncontroConcurso";
                                                $atributos["estilo"]="";
                                                //$atributos["estiloEnLinea"]="display:none";
                                                echo $this->miFormulario->division("inicio",$atributos);
                                                //-------------Control Boton-----------------------
                                                $esteCampo = "inscripcionCerrada";
                                                $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                                                $atributos["etiqueta"] = "";
                                                $atributos["estilo"] = "centrar";
                                                $atributos["tipo"] = 'warning';
                                                $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
                                                echo $this->miFormulario->cuadroMensaje($atributos);
                                                unset($atributos);
                                                //-------------Fin Control Boton----------------------
                                                echo $this->miFormulario->division("fin");
                                            }
                                        
					unset ( $atributos );
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------


				}
				echo $this->miFormulario->division ( 'fin' );

				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				include ("dialog.php");
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
			$valorCodificado .= "&opcion=guardarInscripcion";
			$valorCodificado .= "&perfil=".$resultadoPerfil[0]['consecutivo_perfil'];
			$valorCodificado .= "&nombre_perfil=".$resultadoPerfil[0]['perfil'];

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
