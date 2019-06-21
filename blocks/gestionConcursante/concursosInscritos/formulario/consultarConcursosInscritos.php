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

		$miSesion = \Sesion::singleton();
		$usuario=$miSesion->idUsuario();

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
            //buscar perfiles inscritos
            $cadena_sql = $this->miSql->getCadenaSql("consultaConcursosInscritos", $resultadoPersona[0][0]);
            $resultadoConcursosActivos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            //var_dump($resultadoConcursosActivos);
            $esteCampo = "marcoDatosBasicos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>Concursos Inscritos</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {

                if($resultadoConcursosActivos){
                    $hoy = date("Y-m-d");
    
                    //-----------------Inicio de Conjunto de Controles----------------------------------------
                        $esteCampo = "marcoConsultaPerfiles";
                        $atributos["estilo"] = "jqueryui";
                        $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                        //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                        unset($atributos);
                        echo "<div class='cell-border'><table id='tablaProcesos' class='table table-striped table-bordered'>";
                        echo "<thead>
                                <tr align='center'>
                                  <th>Inscripción</th>
                                  <th>Código</th>
                                  <th>Concurso</th>
                                  <th>Código Perfil</th>
                                  <th>Perfil</th>
                                  <th>Estado Concurso</th>
                                  <th>Detalle</th>
                                  <th>Evaluaciones</th>
                                </tr>
                            </thead>
                            <tbody>";
                        foreach($resultadoConcursosActivos as $key=>$value )
                            {                               
                                if($resultadoConcursosActivos[$key]['fecha_fin'] <= $hoy   )
                                    {$estado_concurso='Finalizado';}
                                else{$estado_concurso='En ejecución';}    
                            
                            	//enlace para consultar los criterios asociados al tipo de jurado
                            	$variableDetalle = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                            	$variableDetalle.= "&opcion=detalleConcurso";
                            	//$variableDetalle.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                            	//$variableDetalle.= "&id_concurso=" .$resultadoConcursosActivos[$key]['consecutivo_concurso'];
                            	$variableDetalle.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                            	$variableDetalle.= "&tiempo=" . time ();
                            	$variableDetalle = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableDetalle, $directorio);


                                $variableVerHoja = "pagina=publicacion";
                                $variableVerHoja.= "&opcion=hojaVida";
                                $variableVerHoja.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                $variableVerHoja.= "&id_usuario=" .$_REQUEST['usuario'];
                                $variableVerHoja.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                $variableVerHoja.= "&tiempo=" . time ();
                                $variableVerHoja .= "&consecutivo_inscrito=".$resultadoConcursosActivos[$key]['consecutivo_inscrito'];
                                $variableVerHoja .= "&consecutivo_concurso=".$resultadoConcursosActivos[$key]['consecutivo_concurso'];
                                $variableVerHoja .= "&consecutivo_perfil=".$resultadoConcursosActivos[$key]['consecutivo_perfil'];
                                $variableVerHoja = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVerHoja, $directorio);

                                if($resultadoConcursosActivos[$key]['estado']=='A'){
                                        $resultadoConcursosActivos[$key]['estado']="Activo";
                                }else{
                                        $resultadoConcursosActivos[$key]['estado']="Inactivo";
                                }

                                $mostrarHtml = "<tr align='center'>
                                        <td align='center'>".$resultadoConcursosActivos[$key]['consecutivo_inscrito']."</td>
                                        <td align='center'>".$resultadoConcursosActivos[$key]['codigo_concurso']."</td>
                                        <td align='left'>".$resultadoConcursosActivos[$key]['concurso']."</td>
                                        <td align='left'>".$resultadoConcursosActivos[$key]['codigo_perfil']."</td>
                                        <td align='left'>".$resultadoConcursosActivos[$key]['perfil']."</td>
                                        <td align='left'>".$estado_concurso."</td>";

                                $mostrarHtml .= "<td>";

                                    //-------------Enlace-----------------------
                                    $esteCampo = 'enlace_hoja'.$key;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['enlace'] = 'javascript:enlace("ruta_enlace_hoja'.$key.'");';
                                    $atributos ['tabIndex'] = 0;
                                    //$atributos ['columnas'] = 1;
                                    $atributos ['enlaceTexto'] = 'Ver Detalle';
                                    $atributos ['estilo'] = 'clasico';
                                    $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";
                                    $atributos ['posicionImagen'] ="atras";//"adelante";
                                    $atributos ['ancho'] = '20px';
                                    $atributos ['alto'] = '20px';
                                    $atributos ['redirLugar'] = false;
                                    $atributos ['valor'] = '';
                                    $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                                    unset ( $atributos );
                                     // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                    $esteCampo = 'ruta_enlace_hoja'.$key;
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['nombre'] = $esteCampo;
                                    $atributos ['tipo'] = 'hidden';
                                    $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ['obligatorio'] = false;
                                    $atributos ['valor'] = $variableVerHoja;
                                    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                    $atributos ['deshabilitado'] = FALSE;
                                    $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------


                                $mostrarHtml .= "</td>";

                                $variableConsulta = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                $variableConsulta.= "&opcion=consultaEvaluacion";
                                $variableConsulta.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                $variableConsulta.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                $variableConsulta.= "&tiempo=" . time ();
                                $variableConsulta .= "&consecutivo_inscrito=".$resultadoConcursosActivos[$key]['consecutivo_inscrito'];
                                $variableConsulta .= "&consecutivo_concurso=".$resultadoConcursosActivos[$key]['consecutivo_concurso'];
                                $variableConsulta .= "&consecutivo_perfil=".$resultadoConcursosActivos[$key]['consecutivo_perfil'];
                                $variableConsulta = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableConsulta, $directorio);

                                $mostrarHtml .= "<td>";

                                //-------------Enlace-----------------------
                                $esteCampo = "validar";
                                $atributos["id"]=$esteCampo;
                                $atributos['enlace']=$variableConsulta;
                                $atributos['tabIndex']=$esteCampo;
                                $atributos['redirLugar']=true;
                                $atributos['estilo']='clasico';
                                $atributos['enlaceTexto']='Ver Evaluaciones';
                                $atributos ['posicionImagen'] ="atras";//"adelante";
                                $atributos['ancho']='20px';
                                $atributos['alto']='20px';
                                $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";
                                $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                unset($atributos);

                                $mostrarHtml .= "</td>";

                               $mostrarHtml .= "</tr>";
                               echo $mostrarHtml;
                               unset($mostrarHtml);
                               unset($variable);
                            }

                        echo "</tbody>";

                        echo "</table></div>";


                        //echo $this->miFormulario->marcoAgrupacion("fin");

                }
								else{
									$atributos["id"]="divNoEncontroModalidades";
									$atributos["estilo"]="";
									//$atributos["estiloEnLinea"]="display:none";
									echo $this->miFormulario->division("inicio",$atributos);

									//-------------Control Boton-----------------------
									$esteCampo = "noEncontroConcursosInscritos";
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
