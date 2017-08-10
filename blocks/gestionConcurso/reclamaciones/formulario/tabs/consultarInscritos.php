<?php
use gestionConcurso\reclamaciones\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class evaluarReclamacion {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
  var $miSesion;
  var $rutaSoporte;

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

		// -------------------------------------------------------------------------------------------------
    $conexion="estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

		//consultar la evaluación actual
    $parametro=array(
                        //'consecutivo_inscrito'=>$_REQUEST['consecutivo_inscrito']
												'consecutivo_inscrito'=>1
                );
    $cadena_sql = $this->miSql->getCadenaSql("consultarValidacion2", $parametro);
    $resultadoValidacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    //var_dump($resultadoValidacion);

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
																		 "<th >Reclamación</th>
																			<td colspan='3'>"."¿Aplica la reclamación para la Validación de Requisitos?".'<div><br>';

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

																	 $mostrarHtml .='</div>'."</td>";

															 echo $mostrarHtml;
															 unset($mostrarHtml);

															 echo "</tbody>";
															 echo "</table>";
                                //Fin de Conjunto de Controles

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
            unset ( $atributos );
    }
}

$miSeleccionador = new evaluarReclamacion ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
