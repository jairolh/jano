<?php
use gestionConcursante\concursosInscritos;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class perfilEvaluacion {
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
            $atributosGlobales ['campoSeguro'] = 'true';
            $_REQUEST ['tiempo'] = time ();
            // -------------------------------------------------------------------------------------------------
            $conexion="estructura";
            $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
            $hoy=date("Y-m-d");
            $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                             'consecutivo_inscrito'=>$_REQUEST['consecutivo_inscrito'],
                             'tipo_dato'=>'evaluacionPerfil',
                             'fase'=>'requisitos');    
            $cadena_sql = $this->miSql->getCadenaSql("consultarFasesEvaluacion", $parametro);
            $resultadoFases = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            //var_dump($resultadoFases);
            if($hoy<=$resultadoFases[0]['fecha_fin_resolver'])
                {   $parametro['version']='1'; }
            $cadena_sql = $this->miSql->getCadenaSql("consultarValidadoPerfilConcurso", $parametro);
            $resultadoEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            //var_dump($resultadoEvaluacion);
            $esteCampo = "marcoEvaluacionPerfil";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "".$this->lenguaje->getCadena ( $esteCampo )."";
            
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {
                    if($resultadoEvaluacion && $hoy>$resultadoFases[0]['fecha_fin'] )
                        { 
                         if ($hoy <= $resultadoFases[0]['fecha_fin_reclamacion'])
                            {
                                    $id_etapa = $resultadoFases[0] ['consecutivo_calendario'];
                                    $etapa = $resultadoFases[0] ['nombre'];

                                    $variableNuevo = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                    $variableNuevo .= "&bloque=" . $esteBloque ['nombre'];
                                    $variableNuevo .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                                    $variableNuevo .= "&opcion=solicitarReclamacion";
                                    $variableNuevo .= "&consecutivo_inscrito=" . $_REQUEST ['consecutivo_inscrito'];
                                    $variableNuevo .= "&consecutivo_concurso=" . $_REQUEST ['consecutivo_concurso'];
                                    $variableNuevo .= "&consecutivo_perfil=" . $_REQUEST ['consecutivo_perfil'];
                                    $variableNuevo .= "&consecutivo_actividad=" . $resultadoFases[0] ['consecutivo_actividad'];
                                    $variableNuevo .= "&id_etapa=" . $id_etapa;
                                    $variableNuevo .= "&etapa=" . $etapa;

                                    $variableNuevo .= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                    $variableNuevo .= "&tiempo=" . time ();
                                    $variableNuevo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableNuevo, $directorio );

                                    // enlace para hacer la reclamación
                            echo "<div ><table width='20%' align='center'>
                                   <tr align='center'>
                                    <td align='center'>";
                                    $esteCampo = 'nuevaReclamacion';
                                    $atributos ['id'] = $esteCampo;
                                    $atributos ['enlace'] = $variableNuevo;
                                    $atributos ['tabIndex'] = 1;
                                    $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                                    $atributos ['estilo'] = 'textoPequenno textoGris';
                                    $atributos ['enlaceImagen'] = $rutaBloque . "/images/new.png";
                                    $atributos ['posicionImagen'] = "atras"; // "adelante";
                                    $atributos ['ancho'] = '45px';
                                    $atributos ['alto'] = '45px';
                                    $atributos ['redirLugar'] = true;
                                    echo $this->miFormulario->enlace ( $atributos );
                                    unset ( $atributos );
                            echo " </td>
                                  </tr>
                                </table></div> ";
                            }
                        
                        
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                            $esteCampo = "marcoIdioma";
                            $atributos["estilo"] = "jqueryui";
                            $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                            //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                            unset($atributos);
                            $mostrarHtml =  "<div class='cell-border'><table id='tablaEvaluacion' class='table table-striped table-bordered'>";
                            $mostrarHtml .= "<thead>
                                    <tr align='center' class='textoAzul'>
                                        <th>Resultado</th>
                                        <th>Estado Evaluación</th>
                                        <th>Observación</th>
                                        <th>Reclamación</th>
                                        <th>Fecha Reclamación</th>
                                        <th>Respuesta  Reclamación</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                foreach($resultadoEvaluacion as $key=>$value )
                                    {   $estado=$resultadoEvaluacion[$key]['estado'];
                                        $reclamo=$fecha=$resultado=$observacion=$codigo='';
                                        $parametro['id_evalua']=$resultadoEvaluacion[$key]['consecutivo_valida'];
                                        $cadena_sql = $this->miSql->getCadenaSql("consultarReclamacionConcurso", $parametro);
                                        $resultadoReclamo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                                        
                                        if($resultadoReclamo)
                                            {$reclamo=$resultadoReclamo[0]['id']." - ".$resultadoReclamo[0]['reclamo'];
                                             $fecha=substr($resultadoReclamo[0]['fecha_registro'],0,10);
                                            }  
                                        //oculta los resultados de la reclamación hasta la fecha de publicación.
                                        if($resultadoReclamo && $hoy<=$resultadoFases[0]['fecha_fin_resolver'])
                                            { $estado='Activo'; }
                                        elseif($resultadoReclamo)
                                            {$resultado=$resultadoReclamo[0]['resultado'];
                                             $observacion=$resultadoReclamo[0]['observacion'];
                                             $codigo=$resultadoReclamo[0]['id_rsta'];
                                            }    
                                        $mostrarHtml.= "<tr align='center'>";     
                                        //$mostrarHtml.= "    <td align='left'>".substr($resultadoEvaluacion[$key]['fecha_registro'],0,10)."</td>"; 
                                        $mostrarHtml.= "    <td align='left'>".$resultadoEvaluacion[$key]['cumple_requisito']."</td>
                                                            <td align='left'>".$estado."</td>
                                                            <td align='justify' width='20%'>".$resultadoEvaluacion[$key]['observacion']."</td>";
                                        $mostrarHtml.= "   <td align='left'>".$reclamo."</td>
                                                            <td align='left'>".$fecha."</td>
                                                            <td align='justify' width='20%' >";       
                                                            $mostrarHtml.=  "<table id='tablaReclamos' class='table table-striped table-bordered'>
                                                                                <tr align='center'>
                                                                                        <td>Código</td>
                                                                                        <td>Respuesta</td>
                                                                                        <td>Observación</td>
                                                                                </tr>";

                                                                        foreach ($resultadoReclamo as $recl => $value) 
                                                                                       {
                                                                                       $mostrarHtml.= "<tr>";
                                                                                       $mostrarHtml.= "<td>".$codigo."</td> ";
                                                                                       $mostrarHtml.= "<td>".$resultado."</td> ";
                                                                                       $mostrarHtml.= "<td>".$observacion."</td> ";
                                                                                       $mostrarHtml.= "</tr>";
                                                                                       }
                                                            $mostrarHtml.= "  </table>"; 
                                        $mostrarHtml.= "  </td>";
                                        
                                       $mostrarHtml .= "</tr>";
                                    }
                                $mostrarHtml .= "</tbody>";
                                $mostrarHtml .= "</table></div>";
                                //Fin de Conjunto de Controles
                            echo $mostrarHtml;
                            unset($mostrarHtml);                                
                                

                        }else
                        {
                                $atributos["id"]="divnoEncontroEvaluacion";
                                $atributos["estilo"]="";
                           //$atributos["estiloEnLinea"]="display:none"; 
                                echo $this->miFormulario->division("inicio",$atributos);

                                //-------------Control Boton-----------------------
                                $esteCampo = "noEncontroEvaluacion";
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
            echo $this->miFormulario->marcoAgrupacion ( 'fin');
            unset ( $atributos );
    }
}

$miSeleccionador = new perfilEvaluacion ( $this->lenguaje, $this->miFormulario,$this->miSql );

$miSeleccionador->miForm ();
?>
