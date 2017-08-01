<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
use gestionConcurso\gestionInscripcion\funcion\redireccion;

class aspirantesForm {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
        var $rutaSoporte;   
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
                $directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
                $rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/";
                $this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "host" ) .$this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		
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
                //identifca el usuario
		$parametro['id_usuario']=$usuario;
               
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
                $estefomulario= 'datosAspirantes';
		$atributos ['id'] = $estefomulario;
		$atributos ['nombre'] =$estefomulario;
		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = 'multipart/form-data';
		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';
		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		$atributos ['titulo'] = '';//$this->lenguaje->getCadena ( $esteCampo );
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
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = "marcoCriterio";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
                        if(!isset($_REQUEST['consecutivo_evaluar']))
                            { $atributos ["estiloEnLinea"] = "display:none;"; }
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
					$parametro['id_usuario']= 'CC111111';//cambiarlo por Ajax CC222222
					
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
																 <th>Perfil</th>
														 </tr>
												 </thead>
												 <tbody>";
						
						if($aspirantes){
							$primero=$aspirantes[0]['consecutivo_inscrito'];
							foreach($aspirantes as $key=>$value ){
						
								$mostrarHtml = "<tr align='center'>
															 <td align='left'>";
						
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
								//$atributos = array_merge ( $atributos, $atributosGlobales );
								$mostrarHtml .= $this->miFormulario->campoCuadroSeleccion ( $atributos );
						
						
								$mostrarHtml.="</td>";
						
						
								$mostrarHtml .= "<td align='left'>".$aspirantes[$key]['consecutivo_inscrito']."</td>
															 <td align='left'>".$aspirantes[$key]['tipo_identificacion']."</td>
															 <td align='left'>".$aspirantes[$key]['identificacion']."</td>
															 <td align='left'>".$aspirantes[$key]['nombre']."</td>
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
				
				
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					if($aspirantes){
					
					
					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonCriterio';
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
					$atributos ['nombreFormulario'] = $estefomulario;//$esteBloque ['nombre'];
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoBoton ( $atributos );
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------
                                        //-------------Control Boton-----------------------
                                        $esteCampo = "botonCancelar";
                                        $atributos["verificar"]="true";
                                        $atributos["tipo"]="boton";
                                        $atributos["id"]="botonCancelar";
                                        $atributos["tipoSubmit"] = "";
                                        $atributos["tabIndex"]=$tab++;
                                        $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                      //  echo $this->miFormulario->campoBoton($atributos);
                                        //-------------Fin Control Boton---------------------- 
                                        
                                        // -----------------CONTROL: Botón ----------------------------------------------------------------
                                        $pestanna='#tabCriterio';
                                        $variable= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                        $variable.= "&opcion=detalle";
                                        $variable.= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
                                        
                                        $esteCampo = 'botonCancelar';
                                        $atributos ['id'] = $esteCampo;
                                        $atributos["tipo"]="boton";
                                        $atributos ['enlace'] = $variable.$pestanna;
                                        $atributos ['tabIndex'] = 1;
                                        $atributos ['estilo'] = 'jqueryui';
                                        $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                                        //$atributos ['ancho'] = '10%';
                                        //$atributos ['alto'] = '10%';
                                        $atributos ['redirLugar'] = true;
                                        echo $this->miFormulario->enlace ( $atributos );
                                        unset($atributos);

                                        // -----------------FIN CONTROL: Botón -----------------------------------------------------------
                                        
                                        
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
                                    $criterio=isset($resultadoCriterio[0]['consecutivo_evaluar'])?$resultadoCriterio[0]['consecutivo_evaluar']:0;    
                                    $valorCodificado = "action=" . $esteBloque ["nombre"];
                                    $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                    $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
                                    $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                                    $valorCodificado .= "&opcion=guardarCriterioConcurso";
                                    $valorCodificado .= "&id_usuario=".$usuario;
                                    $valorCodificado .= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                                    $valorCodificado .= "&consecutivo_evaluar=".$criterio;
                                    
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
					}
				}
				echo $this->miFormulario->division ( 'fin' );
				// ---------------- FIN SECCION: Botones del Formulario -------------------------------------------
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
		}
                // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
                // Se debe declarar el mismo atributo de marco con que se inició el formulario.
                $atributos ['tipoEtiqueta'] = 'fin';
                echo $this->miFormulario->formulario ( $atributos );
                return true;
	}
}
$miSeleccionador = new aspirantesForm ( $this->lenguaje, $this->miFormulario, $this->sql );
$miSeleccionador->miForm ();
?>
