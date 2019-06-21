<?php
namespace gestionConcursante\concursosInscritos;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

class consultarForm {
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
		$tab = 1;

		// -------------------------------------------------------------------------------------------------
    $conexion="estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

		$valorCodificado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		$valorCodificado .= "&opcion=nuevoTipoJurado";
    $valorCodificado .= "&usuario=" . $this->miSesion->getSesionUsuarioId();

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

		$usuario=$this->miSesion->getSesionUsuarioId();

		//buscar consecutivo_persona
                $tam=2;
                if(strtoupper(substr($usuario,0,1))!='C')
                    {$tam=3;}
                //buscar consecutivo_persona
                $tipo=strtoupper(substr($usuario,0,$tam));
                $id=substr($usuario,$tam);

		$persona = array('tipo_identificacion'=> $tipo,
				'identificacion'=> $id
		);

		//buscar el consecutivo de la persona
		$cadena_sql = $this->miSql->getCadenaSql("consultaConsecutivo", $persona);
		$resultadoPersona = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

		$datos = array('concurso'=> $_REQUEST['id_concurso'],
				'usuario'=> $resultadoPersona[0][0]
		);


				//concursos a los que se ha inscrito el usuario
				$cadena_sql = $this->miSql->getCadenaSql("consultaPerfiles", $datos);
				$resultadoPerfiles = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

				$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
				$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
				$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
				$variable = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
				$variable .= "&opcion=consultar";
				$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );

					// ---------------- CONTROL: Enlace --------------------------------------------------------
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

            $esteCampo = "marcoDatosBasicos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>Perfiles del Concurso</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {


                if($resultadoPerfiles)
                {
                    //-----------------Inicio de Conjunto de Controles----------------------------------------
                        $esteCampo = "marcoConsultaPerfiles";
                        $atributos["estilo"] = "jqueryui";
                        $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                        //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                        unset($atributos);

                        echo "<div class='cell-border'><table id='tablaProcesos' class='table table-striped table-bordered'>";

                        echo "<thead>
                                <tr align='center'>
                                  <th>Perfil</th>
                        					<th>Dependencia</th>
                                  <th>Area</th>
																	<th>Vacantes</th>
                        					<th>Estado</th>
																	<th>Detalle</th>
                                </tr>
                            </thead>
                            <tbody>";

                        foreach($resultadoPerfiles as $key=>$value )
                            {
                            	//enlace para consultar los criterios asociados al tipo de jurado
                            	$variableDetalle = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                            	$variableDetalle.= "&opcion=consultarPerfil";
															$variableDetalle.= "&id_concurso=" .$resultadoPerfiles[$key]['consecutivo_concurso'];
                            	$variableDetalle.= "&id_perfil=" .$resultadoPerfiles[$key]['consecutivo_perfil'];
                            	$variableDetalle.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                            	$variableDetalle.= "&tiempo=" . time ();
                            	$variableDetalle = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableDetalle, $directorio);

															if($resultadoPerfiles[$key]['estado']=='A'){
																$resultadoPerfiles[$key]['estado']="Activo";
															}else{
																$resultadoPerfiles[$key]['estado']="Inactivo";
															}

                                $mostrarHtml = "<tr align='center'>
																			<td align='left'>".$resultadoPerfiles[$key]['nombre']."</td>
																			<td align='left'>".$resultadoPerfiles[$key]['dependencia']."</td>
																			<td align='left'>".$resultadoPerfiles[$key]['area']."</td>
																			<td align='left'>".$resultadoPerfiles[$key]['vacantes']."</td>
																			<td align='left'>".$resultadoPerfiles[$key]['estado']."</td>
                                ";


                                $mostrarHtml .= "<td>";
                                $esteCampo = "detalle";
                                $atributos["id"]=$esteCampo;
                                $atributos['enlace']=$variableDetalle;
                                $atributos['tabIndex']=$esteCampo;
                                $atributos['redirLugar']=true;
                                $atributos['estilo']='clasico';
                                $atributos['enlaceTexto']='';
                                $atributos['ancho']='25';
                                $atributos['alto']='25';
                                $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";

                                $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                $mostrarHtml .= "</td>";



                               $mostrarHtml .= "</tr>";
                               echo $mostrarHtml;
                               unset($mostrarHtml);
                               unset($variable);
                            }

                        echo "</tbody>";

                        echo "</table></div>";

                        //Fin de Conjunto de Controles
                        //echo $this->miFormulario->marcoAgrupacion("fin");

                }
								else{
									$atributos["id"]="divNoEncontroModalidades";
									$atributos["estilo"]="";
									//$atributos["estiloEnLinea"]="display:none";
									echo $this->miFormulario->division("inicio",$atributos);

									//-------------Control Boton-----------------------
									$esteCampo = "noEncontroPerfilesActivos";
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
