<?php
use gestionConcurso\evaluacion\funcion\redireccion;

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
            
               //identifca lo roles para la busqueda de subsistemas
                $roles=  $this->miSesion->RolesSesion();
                $aux=0;
                
                $tipo = array();
                $find='';
                foreach ($roles as $key => $value) 
                    {  if (!in_array($roles[$key]['nom_app'], $tipo)) 
                          { array_push ( $tipo , $roles[$key]['nom_app'] );}
                    }
                
                foreach ($tipo as $key => $value) 
                    {   $find.="'".$value."'";
                        if(($key+1)<count($tipo))
                            {$find.=",";}
                    }

            $parametro=array(
                                'concurso'=>$_REQUEST['consecutivo_concurso'],
                                'jurado'=>$this->miSesion->getSesionUsuarioId(),
                                'hoy'=>date("Y-m-d"),
                                'tipo'=>$find
                        );
            $cadena_sql = $this->miSql->getCadenaSql("consultarAspirantesAsignados", $parametro);
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
                                            <th>Inscripción</th>
                                            <th>Identificación</th>
                                            <th>Nombre</th>
                                            <th>Código</th>
                                            <th>Perfil</th>
                                            <th>Evaluar</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoListaInscrito as $key=>$value )
                                    {   $parametro['tipo']='unico';
                                        
                                        $variableEvaluar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                        $variableEvaluar.= "&opcion=evaluar";
                                        $variableEvaluar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
					//$variableEvaluar.= "&nombre_usuario=". $resultadoListaInscrito[$key]['nombre']." ".$resultadoListaInscrito[$key]['apellido'];
                                        $variableEvaluar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEvaluar.= "&tiempo=" . time ();
                                        $variableEvaluar .= "&consecutivo_concurso=".$resultadoListaInscrito[$key]['consecutivo_concurso'];
                                        $variableEvaluar .= "&consecutivo_perfil=".$resultadoListaInscrito[$key]['consecutivo_perfil'];
					$variableEvaluar .= "&consecutivo_inscrito=".$resultadoListaInscrito[$key]['consecutivo_inscrito'];
                                        $variableEvaluar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEvaluar, $directorio);
                                        //$variableEvaluar.= "#tabInscrito";

                                        //enlace actualizar estado
                                        $variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                        if($resultadoListaInscrito[$key]['estado']=='A')
                                            {$variableEstado.= "&opcion=inhabilitarInscrito";}
                                        else{$variableEstado.= "&opcion=habilitarInscrito";}
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
                                                <td align='left' width='6%'>".$resultadoListaInscrito[$key]['id_inscrito']."</td>
                                                <td align='left' width='10%' >".$resultadoListaInscrito[$key]['tipo_identificacion']." ".$resultadoListaInscrito[$key]['identificacion']."</td>
                                                <td align='left' >".$resultadoListaInscrito[$key]['nombre']." ".$resultadoListaInscrito[$key]['apellido']."</td>
                        			<td align='left' width='10%'>".$resultadoListaInscrito[$key]['codigo']."</td>
                        			<td align='left'>".$resultadoListaInscrito[$key]['perfil']."</td>";
                                        $mostrarHtml .= "<td  width='8%'>";

                                        //consultar grupo de concurso y jurado (con evaluador y perfil)
                                        $parametro=array(
                                                'consecutivo_concurso'=>$resultadoListaInscrito[$key]['consecutivo_concurso'], 
                                                'jurado'=>$this->miSesion->getSesionUsuarioId(),
                                                'perfil'=>$resultadoListaInscrito[$key]['consecutivo_perfil'],
                                                'inscrito'=>$resultadoListaInscrito[$key]['id_inscrito'],
                                                'hoy'=>date("Y-m-d"),
                                                );

                                        $cadena_sql = $this->miSql->getCadenaSql("verificarEvaluacionParcialJurado", $parametro);
                                        $resultadoValidacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                                        //var_dump($resultadoValidacion);
                                        $evaluacion='evaluado';
                                        if($resultadoValidacion){
                                            foreach ($resultadoValidacion as $key => $value) {
                                                if($resultadoValidacion[$key]['evaluo']=='' && $parametro['hoy']>=$resultadoValidacion[$key]['fecha_inicio'] && $parametro['hoy']<=$resultadoValidacion[$key]['fecha_fin'] )
                                                    {$evaluacion='evaluar';}
                                                }
                                        }    
                                        $variableVerEvaluacion = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                        $variableVerEvaluacion .= "&opcion=consultarEvaluacion";
                                        $variableVerEvaluacion .= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableVerEvaluacion .= "&consecutivo_concurso=".$resultadoListaInscrito[$key]['consecutivo_concurso'];
                                      	$variableVerEvaluacion .= "&consecutivo_perfil=".$resultadoListaInscrito[$key]['consecutivo_perfil'];
                                        $variableVerEvaluacion .= "&consecutivo_inscrito=".$resultadoListaInscrito[$key]['consecutivo_inscrito'];
                                        $variableVerEvaluacion .= "&grupo=".$resultadoValidacion[0]['id_grupo'];
                                        $variableVerEvaluacion .= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableVerEvaluacion .= "&tiempo=" . time ();
                                        $variableVerEvaluacion = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVerEvaluacion, $directorio);
                                            if(!$resultadoValidacion || $evaluacion=='evaluar' ){
                                                //-------------Enlace-----------------------
                                                    $esteCampo = "validar";
                                                    $atributos["id"]=$esteCampo;
                                                    $atributos['enlace']=$variableEvaluar;
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
                                            else{
                                                    $esteCampo = "validar";
                                                    $atributos["id"]=$esteCampo;
                                                    $atributos['enlace']=$variableVerEvaluacion;
                                                    $atributos['tabIndex']=$esteCampo;
                                                    $atributos['redirLugar']=true;
                                                    $atributos['estilo']='clasico';
                                                    $atributos['enlaceTexto']='Ver Evaluación';
                                                    $atributos['ancho']='30';
                                                    $atributos['alto']='30';
                                                    //$atributos['enlaceImagen']=$rutaBloque."/images/check_file.png";
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
