<?php
namespace gestionConcurso\reclamaciones;

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
						$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
            $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

            $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
            $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
            $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

            $directorio = $this->miConfigurador->getVariableConfiguracion("host");
            $directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
            $directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
            $this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "raizSoportes" );
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

            //consulta de todas las reclamaciones
            $parametro=array(
                    'consecutivo_concurso'=>$_REQUEST['consecutivo_concurso']
            );
            $cadena_sql = $this->miSql->getCadenaSql("consultarReclamaciones", $parametro);
            $resultadoReclamaciones = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
						
            $variable = "pagina=" . $miPaginaActual;
            $variable.= "&opcion=listar";
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
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

            $esteCampo = "marcoEjecucion";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {
                if($resultadoReclamaciones){
                    //-----------------Inicio de Conjunto de Controles----------------------------------------
                        $esteCampo = "marcoListaConcurso";
                        $atributos["estilo"] = "jqueryui";
                        $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                        //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                        unset($atributos);

                        echo "<div class='cell-border'><table id='tablaConcursos' class='table table-striped table-bordered'>";

                        echo "<thead>
                                <tr align='center'>
                                    <th>Reclamación</th>
                                    <th>Observación</th>
                                    <th>Fecha</th>
                                    <th>Etapa</th>
                                    <th>Aplica Reclamación</th>
                                    <th>Nueva Evaluación</th>
                                </tr>
                            </thead>
                            <tbody>";

                        foreach($resultadoReclamaciones as $key=>$value ){

                                $parametro=array(
                                        'reclamacion'=>$resultadoReclamaciones[$key]['id']
                                );
                                $cadena_sql = $this->miSql->getCadenaSql("consultaRespuestaReclamaciones", $parametro);
                                $resultadoRespuestaReclamaciones = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                               // var_dump($resultadoRespuestaReclamaciones);

                                $variableDetalleRta = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                $variableDetalleRta.= "&opcion=consultarDetalleRta";
                                $variableDetalleRta.= "&consecutivo_concurso=" .$_REQUEST['consecutivo_concurso'];
                                $variableDetalleRta.= "&reclamacion=" .$resultadoReclamaciones[$key]['id'];
                                $variableDetalleRta.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                $variableDetalleRta.= "&tiempo=" . time ();
                                $variableDetalleRta = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableDetalleRta, $directorio);

                                $parametro=array(
                                        'consecutivo_inscrito'=>$resultadoReclamaciones[$key]['id_inscrito'],
                                        'reclamacion'=>$resultadoReclamaciones[$key]['id']
                                );
                                $cadena_sql = $this->miSql->getCadenaSql("consultaEvaluacionesReclamacion", $parametro);
                                $validacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                                                if($resultadoRespuestaReclamaciones[0]['respuesta']){
                                                        $respuesta=$resultadoRespuestaReclamaciones[0]['respuesta'];
                                                }else{
                                                        $respuesta="PENDIENTE";
                                                }

                                $mostrarHtml = "<tr align='center'>
                                        <td align='left'>".$resultadoReclamaciones[$key]['id']."</td>
                                        <td align='left'>".$resultadoReclamaciones[$key]['observacion']."</td>
                                        <td align='left'>".$resultadoReclamaciones[$key]['fecha_registro']."</td>
					<td align='left'>".$resultadoReclamaciones[$key]['nombre']."</td>
                                        <td align='left'>";

                                            //consulta fecha máxima para dar respuesta a la reclamación: Fase de VALIDACION DE REQUISITOS
                                            $parametro=array(
                                                    'consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                                                    'consecutivo_actividad'=>3
                                            );
                                            $cadena_sql = $this->miSql->getCadenaSql("fechaFinResolver", $parametro);
                                            $fechaFinResolver = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                                            if($respuesta=="PENDIENTE"){

                                                    $fecha = date("Y-m-d");
                                                    if($fecha<=$fechaFinResolver[0]['fecha_fin_resolver']){

                                                        $variableVerHoja = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                                        $variableVerHoja.= "&opcion=validacion";
                                                        $variableVerHoja.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                                        $variableVerHoja.= "&id_usuario=" .$_REQUEST['usuario'];
                                                        $variableVerHoja.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                                        $variableVerHoja.= "&tiempo=" . time ();
                                                        //$variableVerHoja .= "&consecutivo_inscrito=".$_REQUEST['consecutivo_inscrito'];
                                                        $variableVerHoja.= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                                                        //$variableVerHoja .= "&consecutivo_perfil=".$_REQUEST['consecutivo_perfil'];
                                                        $variableVerHoja.= "&reclamacion=" .$resultadoReclamaciones[$key]['id'];
                                                        $variableVerHoja = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVerHoja, $directorio);

                                                        //-------------Enlace-----------------------
                                                        $esteCampo = "verHojaVida";
                                                        $esteCampo = 'enlace_hoja';
                                                        $atributos ['id'] = $esteCampo;
                                                        $atributos ['enlace'] = $variableVerHoja;
                                                        $atributos ['tabIndex'] = 0;
                                                        $atributos ['columnas'] = 1;
                                                        $atributos ['enlaceTexto'] = 'Verificar Reclamación';
                                                        $atributos ['estilo'] = 'clasico';
                                                        $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";
                                                        $atributos ['posicionImagen'] ="atras";//"adelante";
                                                        $atributos ['ancho'] = '20px';
                                                        $atributos ['alto'] = '20px';
                                                        $atributos ['redirLugar'] = false;
                                                        $atributos ['valor'] = '';
                                                        $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                                                        unset ( $atributos );

                                                    }else{
                                                            $mostrarHtml .="RECLAMACIÓN SIN RESPUESTA";
                                                    }
                                            }else{
                                                    $esteCampo = "detalle";
                                                    $atributos["id"]=$esteCampo;
                                                    $atributos['enlace']=$variableDetalleRta;
                                                    $atributos['tabIndex']=$esteCampo;
                                                    $atributos['redirLugar']=true;
                                                    $atributos['estilo']='clasico';
                                                    $atributos['enlaceTexto']=$respuesta;
                                                    $atributos['ancho']='25';
                                                    $atributos['alto']='25';
                                                    //$atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";
                                                    $mostrarHtml .= $this->miFormulario->enlace($atributos);

                                            }

                                    $mostrarHtml .="</td>";



                                            $mostrarHtml .= "<td>";
                                            if($respuesta=="PENDIENTE" || $respuesta=="NO" ){
                                                    $mostrarHtml .="----------";

                                            }else if($respuesta && $validacion[0][0]==1){
                                                    $fecha = date("Y-m-d");
                                                    if($fecha<=$fechaFinResolver[0]['fecha_fin_resolver']){
                                                        $variableVerHoja = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                                        $variableVerHoja.= "&opcion=evaluar";
                                                        $variableVerHoja.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                                        $variableVerHoja.= "&id_usuario=" .$_REQUEST['usuario'];
                                                        $variableVerHoja.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                                        $variableVerHoja.= "&tiempo=" . time ();
                                                        $variableVerHoja .= "&consecutivo_inscrito=".$resultadoReclamaciones[$key]['id_inscrito'];
                                                        $variableVerHoja .= "&consecutivo_concurso=".$resultadoReclamaciones[$key]['id_concurso'];
                                                        $variableVerHoja .= "&consecutivo_perfil=".$resultadoReclamaciones[$key]['consecutivo_perfil'];
                                                        $variableVerHoja .= "&reclamacion=".$resultadoReclamaciones[$key]['id'];
                                                        $variableVerHoja = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVerHoja, $directorio);

                                                        //-------------Enlace-----------------------
                                                        $esteCampo = "verHojaVida";
                                                        $esteCampo = 'enlace_hoja';
                                                        $atributos ['id'] = $esteCampo;
                                                        $atributos ['enlace'] = $variableVerHoja;
                                                        $atributos ['tabIndex'] = 0;
                                                        $atributos ['columnas'] = 1;
                                                        $atributos ['enlaceTexto'] = 'Evaluar';
                                                        $atributos ['estilo'] = 'clasico';
                                                        $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";
                                                        $atributos ['posicionImagen'] ="atras";//"adelante";
                                                        $atributos ['ancho'] = '20px';
                                                        $atributos ['alto'] = '20px';
                                                        $atributos ['redirLugar'] = false;
                                                        $atributos ['valor'] = '';
                                                        $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                                                        unset ( $atributos );
                                                }else{
                                                        $mostrarHtml .="EVALUACION NO REALIZADA";
                                                }

        }else if($validacion[0][0]==2){
                $variableValidacion = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                $variableValidacion.= "&opcion=consultaValidacion";
                $variableValidacion.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                $variableValidacion.= "&id_usuario=" .$_REQUEST['usuario'];
                $variableValidacion.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                $variableValidacion.= "&tiempo=" . time ();
                $variableValidacion .= "&consecutivo_inscrito=".$resultadoReclamaciones[$key]['id_inscrito'];
                $variableValidacion .= "&consecutivo_concurso=".$resultadoReclamaciones[$key]['id_concurso'];
                $variableValidacion .= "&consecutivo_perfil=".$resultadoReclamaciones[$key]['consecutivo_perfil'];
                $variableValidacion .= "&reclamacion=".$resultadoReclamaciones[$key]['id'];
                $variableValidacion = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableValidacion, $directorio);

                //-------------Enlace-----------------------
                $esteCampo = "verEvaluacion";
                $esteCampo = 'enlace_hoja';
                $atributos ['id'] = $esteCampo;
                $atributos ['enlace'] = $variableValidacion;
                $atributos ['tabIndex'] = 0;
                $atributos ['columnas'] = 1;
                $atributos ['enlaceTexto'] = 'Ver Evaluación';
                $atributos ['estilo'] = 'clasico';
                $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";
                $atributos ['posicionImagen'] ="atras";//"adelante";
                $atributos ['ancho'] = '20px';
                $atributos ['alto'] = '20px';
                $atributos ['redirLugar'] = false;
                $atributos ['valor'] = '';
                $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                unset ( $atributos );
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

                }else
                {
                    $tab=1;
                    //---------------Inicio Formulario (<form>)--------------------------------
                    $atributos["id"]="divNoEncontroConcurso";
                    $atributos["estilo"]="marcoBotones";
                    //$atributos["estiloEnLinea"]="display:none";
                        echo $this->miFormulario->division("inicio",$atributos);

                        //-------------Control Boton-----------------------
                        $esteCampo = "noEncontroReclamaciones";
                        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                        $atributos["etiqueta"] = "";
                        $atributos["estilo"] = "centrar";
                        $atributos["tipo"] = 'error';
                        $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
                        echo $this->miFormulario->cuadroMensaje($atributos);
                        unset($atributos);
                        //------------------Fin Division para los botones-------------------------
                        echo $this->miFormulario->division("fin");
                        //-------------Control cuadroTexto con campos ocultos-----------------------
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
