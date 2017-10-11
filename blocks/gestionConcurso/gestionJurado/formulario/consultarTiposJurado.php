<?php
namespace gestionConcurso\gestionJurado;

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
        $variableNuevo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($valorCodificado, $directorio);

        $cadena_sql = $this->miSql->getCadenaSql("consultaTiposJurado", "");
        $resultadoTiposJurado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            //var_dump($resultadoActividades);
            $esteCampo = "marcoDatosBasicos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>Caracterización Jurados</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {

                echo "<div ><table width='100%' align='center'>
                        <tr align='center'>
                            <td align='center'>";
                                $esteCampo = 'nuevoTipo';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['enlace'] = $variableNuevo;
                                $atributos ['tabIndex'] = 1;
                                $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                                $atributos ['estilo'] = 'textoPequenno textoGris';
                                $atributos ['enlaceImagen'] = $rutaBloque."/images/new.png";
                                $atributos ['posicionImagen'] = "atras";//"adelante";
                                $atributos ['ancho'] = '45px';
                                $atributos ['alto'] = '45px';
                                $atributos ['redirLugar'] = true;
                                echo $this->miFormulario->enlace ( $atributos );
                                unset ( $atributos );
                echo "            </td>
                        </tr>
                      </table></div> ";

                if($resultadoTiposJurado)
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
                                  <th>Tipo de Jurado</th>
                                  <th>Descripción</th>
                                  <th>Estado</th>
                        	  <th>Actualizar Estado</th>
                                </tr>
                            </thead>
                            <tbody>";

                        foreach($resultadoTiposJurado as $key=>$value )
                            {
                            	//enlace para consultar los criterios asociados al tipo de jurado
                            	$variableDetalle = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                            	$variableDetalle.= "&opcion=detalleCriterios";
                            	$variableDetalle.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                            	$variableDetalle.= "&id_tipoJurado=" .$resultadoTiposJurado[$key]['id'];
                              $variableDetalle.= "&nombre_tipoJurado=" .$resultadoTiposJurado[$key]['nombre'];
                            	$variableDetalle.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                            	$variableDetalle.= "&tiempo=" . time ();
                            	$variableDetalle = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableDetalle, $directorio);

                                //enlace actualizar estado
                                $variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                if($resultadoTiposJurado[$key]['estado']=='A')
                                    {$variableEstado.= "&opcion=inhabilitarTipoJurado";}
                                else{$variableEstado.= "&opcion=habilitarTipoJurado";}
                                $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                $variableEstado.= "&id_tipoJurado=" .$resultadoTiposJurado[$key]['id'];
                                $variableEstado.= "&nombre_tipoJurado=" .$resultadoTiposJurado[$key]['nombre'];
                                $variableEstado.= "&estado_tipoJurado=" .$resultadoTiposJurado[$key]['estado'];
                                $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                $variableEstado.= "&tiempo=" . time ();
                                $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);

                                if($resultadoTiposJurado[$key]['estado']=='A'){
                                	$resultadoTiposJurado[$key]['estado']="Activo";
                                }else{
                                	$resultadoTiposJurado[$key]['estado']="Inactivo";
                                }

                                $mostrarHtml = "<tr align='center'>
                                        <td align='left'>".$resultadoTiposJurado[$key]['nombre']."</td>
                                        <td align='left'>".$resultadoTiposJurado[$key]['descripcion']."</td>
                                        <td align='left'>".$resultadoTiposJurado[$key]['estado']."</td>
                                ";

                                /*
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
                                */

                                $mostrarHtml .= "<td>";

                                        if($resultadoTiposJurado[$key]['estado']=='Activo')
                                            {
                                            	$esteCampo = "habilitar";
                                                $atributos["id"]=$esteCampo;
                                                $atributos['enlace']=$variableEstado;
                                                $atributos['tabIndex']=$esteCampo;
                                                $atributos['redirLugar']=true;
                                                $atributos['estilo']='clasico';
                                                $atributos['enlaceTexto']='';
                                                $atributos['ancho']='25';
                                                $atributos['alto']='25';
                                                $atributos['enlaceImagen']=$rutaBloque."/images/player_pause.png";
                                                $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                unset($atributos);
                                            }
                                        else{
                                                //-------------Enlace-----------------------
                                                $esteCampo = "habilitar";
                                                $atributos["id"]=$esteCampo;
                                                $atributos['enlace']=$variableEstado;
                                                $atributos['tabIndex']=$esteCampo;
                                                $atributos['redirLugar']=true;
                                                $atributos['estilo']='clasico';
                                                $atributos['enlaceTexto']='';
                                                $atributos['ancho']='25';
                                                $atributos['alto']='25';
                                                $atributos['enlaceImagen']=$rutaBloque."/images/success.png";
                                                $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                unset($atributos);
                                            }

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
									$esteCampo = "noEncontroTiposJurado";
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
