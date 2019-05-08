<?php
use gestionConcurso\evaluacionConcurso\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarInscrito {
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

            $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso']);
            $cadena_sql = $this->miSql->getCadenaSql("consultarInscritoConcurso", $parametro);
            $resultadoListaInscrito = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

            //var_dump($resultadoListaInscrito);
            $esteCampo = "marcoListaInscrito";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>".$this->lenguaje->getCadena ( $esteCampo )."</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );

            unset ( $atributos );
                {
                   if($resultadoListaInscrito)
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
                                            <th>Tipo Identificación</th>
                                            <th>Identificación</th>
                                            <th>Nombre</th>
                                            <th>Código</th>
                                            <th>Perfil</th>
                                            <th>Inscripción</th>
                                            <th>Fecha</th>
                                            <th>Estado Validación</th>
                                            <th>Validar Requisitos</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

                                //consulta para verificar que la etapa esté activa
                                $hoy = date("Y-m-d");
                                $parametro['hoy']=$hoy;
                                $parametro['consecutivo_concurso']=$resultadoListaInscrito[0]['consecutivo_concurso'];
                                $cadena_sql = $this->miSql->getCadenaSql("consultaEtapaActiva", $parametro);
                                $resultadoEtapa = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                                foreach($resultadoListaInscrito as $key=>$value )
                                    {   $parametro['tipo']='unico';
                                        /*
                                        $parametroSop = array('consecutivo'=>$resultadoListaInscrito[$key]['consecutivo_persona'],
                                             'tipo_dato'=>'datosInscrito',
                                             'nombre_soporte'=>'soporteInscrito',
                                             'consecutivo_dato'=>$resultadoListaInscrito[$key]['consecutivo_actividad']
                                            );
                                        $cadenaSop_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                        $resultadoSact = $esteRecursoDB->ejecutarAcceso($cadenaSop_sql, "busqueda");*/
                                        $variableValidar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                        $variableValidar.= "&opcion=validar";
                                        $variableValidar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableValidar.= "&nombre_usuario=". $resultadoListaInscrito[$key]['nombre']." ".$resultadoListaInscrito[$key]['apellido'];
                                        $variableValidar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableValidar.= "&tiempo=" . time ();
                                        $variableValidar .= "&consecutivo_concurso=".$resultadoListaInscrito[$key]['consecutivo_concurso'];
                                        $variableValidar .= "&consecutivo_perfil=".$resultadoListaInscrito[$key]['consecutivo_perfil'];
					$variableValidar .= "&consecutivo_inscrito=".$resultadoListaInscrito[$key]['consecutivo_inscrito'];
                                        $variableValidar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableValidar, $directorio);
                                        $variableValidar.= "#tabInscrito";

                                        //verificar si ya se realizó la validación de la inscripción
                                        $cadena_sql = $this->miSql->getCadenaSql("consultarValidacion", $resultadoListaInscrito[$key]['consecutivo_inscrito']);
                                        $resultadoValidacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                                            if($resultadoValidacion){
                                                    if($validacion=$resultadoValidacion[0]['cumple_requisito']=='SI'){
                                                            $validacion="APROBADO";
                                                    }else{
                                                            $validacion="NO APROBADO";
                                                    }

                                            }else{
                                                    $validacion="PENDIENTE";
                                            }

                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoListaInscrito[$key]['tipo_identificacion']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['identificacion']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['nombre']." ".$resultadoListaInscrito[$key]['apellido']."</td>
						<td align='left'>".$resultadoListaInscrito[$key]['codigo']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['perfil']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['consecutivo_inscrito']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['fecha_registro']."</td>
						<td align='left'>".$validacion."</td>";
                                        $mostrarHtml .= "<td>";

					$variableVerValidacion = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                        $variableVerValidacion .= "&opcion=consultarValidacion";
					$variableVerValidacion .= "&nombre_usuario=". $resultadoListaInscrito[$key]['nombre']." ".$resultadoListaInscrito[$key]['apellido'];
                                        $variableVerValidacion .= "&consecutivo_concurso=".$resultadoListaInscrito[$key]['consecutivo_concurso'];
                                        $variableVerValidacion .= "&consecutivo_perfil=".$resultadoListaInscrito[$key]['consecutivo_perfil'];
					$variableVerValidacion .= "&consecutivo_inscrito=".$resultadoListaInscrito[$key]['consecutivo_inscrito'];
                                        $variableVerValidacion .= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableVerValidacion .= "&tiempo=" . time ();
                                        $variableVerValidacion = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVerValidacion, $directorio);

                                            if(!$resultadoValidacion && $resultadoEtapa){
                                                //-------------Enlace-----------------------
                                                    $esteCampo = "validar";
                                                    $atributos["id"]=$esteCampo;
                                                    $atributos['enlace']=$variableValidar;
                                                    $atributos['tabIndex']=$esteCampo;
                                                    $atributos['redirLugar']=true;
                                                    $atributos['estilo']='clasico';
                                                    $atributos['enlaceTexto']='';
                                                    $atributos['ancho']='30';
                                                    $atributos['alto']='30';
                                                    $atributos['enlaceImagen']=$rutaBloque."/images/check_file.png";
                                                    $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                    unset($atributos);
                                                }
                                            else if ($resultadoValidacion){
                                                    $esteCampo = "validar";
                                                    $atributos["id"]=$esteCampo;
                                                    $atributos['enlace']=$variableVerValidacion;
                                                    $atributos['tabIndex']=$esteCampo;
                                                    $atributos['redirLugar']=true;
                                                    $atributos['estilo']='clasico';
                                                    $atributos['enlaceTexto']='Ver Validación';
                                                    $atributos['ancho']='30';
                                                    $atributos['alto']='30';
                                                    //$atributos['enlaceImagen']=$rutaBloque."/images/check_file.png";
                                                    $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                    unset($atributos);
                                            }else{
                                                    $mostrarHtml .=  "Etapa Finalizada";
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

$miSeleccionador = new consultarInscrito ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
