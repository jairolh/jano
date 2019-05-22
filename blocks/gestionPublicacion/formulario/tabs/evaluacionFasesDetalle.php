<?php
use gestionPublicacion\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class fasesEvaluacion {
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
            //$conexion="estructura";
            $conexion="reportes";
            $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
            
            
            //identifca lo roles para la busqueda de subsistemas
            $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                             'consecutivo_inscrito'=>$_REQUEST['consecutivo_inscrito'],
                             'tipo_dato'=>'evaluacionFases');    
            $cadena_sql = $this->miSql->getCadenaSql("consultarFasesEvaluacion", $parametro);
            $resultadoFases = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
           // var_dump($resultadoFases);
            
            
            
        if($resultadoFases)    
            {
            foreach($resultadoFases as $fase => $value)    
                {
                //identifca lo roles para la busqueda de subsistemas
                $parametro['consecutivo_calendario']=$resultadoFases[$fase]['consecutivo_calendario'];
                $cadena_sql = $this->miSql->getCadenaSql("consultaCriterioFase", $parametro);
                $criterioFase= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
              //  var_dump($criterioFase);

                $parametro['estado_evaluar']='A';
                $cadena_sql = $this->miSql->getCadenaSql("listadoCierreEvaluacion", $parametro);
                $resultadoListaFase= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                //fases de evaluacion
                              
                $maximo_puntos=0;
                foreach ($criterioFase as $crt => $criterio)
                        {
                         $maximo_puntos+=$criterioFase[$crt]['maximo_puntos'];
                        }
                    $esteCampo = "marcoEvaluacion".$fase;
                    $atributos ['id'] = $esteCampo;
                    $atributos ["estilo"] = "jqueryui";
                    $atributos ['tipoEtiqueta'] = 'inicio';
                    $atributos ["leyenda"] = "Resultados ".$resultadoFases[$fase]['nombre']."";

                    echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
                    unset ( $atributos );
                        {
                            if($resultadoListaFase)
                                {     
                                    //-----------------Inicio de Conjunto de Controles----------------------------------------
                                    $esteCampo = "marcoIdioma";
                                    $atributos["estilo"] = "jqueryui";
                                    $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                    //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                    unset($atributos);
                                    $mostrarHtml =  "<div class='cell-border'><table id='tablaProcesos' class='table table-striped table-bordered'>";
                                    $mostrarHtml .= "<thead>
                                            <tr align='center' class='textoAzul'>";
                                           /* foreach ($criterioFase as $crt => $criterio)
                                                {
                                                 $mostrarHtml.="<th>".$criterioFase[$crt]['nombre']."</th>";
                                                }*/
                                   $mostrarHtml.="  <th width='30%'>Criterios Evaluación</th> 
                                                    <th>Total Puntos</th> 
                                                    <th>Estado Evaluación</th>
                                                    <th width='15%' >Reclamación</th>
                                                    <th>Fecha Reclamación</th>
                                                    <th width='30%'>Respuesta Reclamación </th>
                                                </tr>
                                            </thead>
                                            <tbody>";
                                  // var_dump($resultadoListaFase);
                                        $datosFase= count($resultadoListaFase);
                                        foreach($resultadoListaFase as $key=>$value )
                                            { 
                                                //buscar reclamaciones
                                                $puntajes=json_decode($resultadoListaFase[$key]['evaluaciones']);
                                                //calcula los puntos de aprobacion
                                                $puntos_aprueba=($maximo_puntos*$resultadoFases[$fase]['porcentaje_aprueba'])/100;
                                                $mostrarHtml.= "<tr align='center'>";

                                                $mostrarHtml.="<td align='center'>";
                                                foreach ($criterioFase as $crt => $criterio)
                                                   {if($criterioFase[$crt]['codigo']==$resultadoListaFase[$key]['consecutivo_evaluar'])
                                                       {$mostrarHtml.="<p  class='textoAzul'><b>".$criterioFase[$crt]['nombre']."</b></p>";
                                                        
                                                       $parametro['criterios']=$criterioFase[$crt]['codigo'];
                                                       $cadena_sql = $this->miSql->getCadenaSql ( "consultarEvaluacion", $parametro );
                                                       $resultadoEvaluaciones = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
                                                        $mostrarHtml.=  "<table id='tablaEvaluacion' class='table table-striped table-bordered'>
                                                                         <tr align='center'>
                                                                           <td>Evaluador</td> 
                                                                                 <td>Fecha</td>
                                                                                 <td>Puntos</td>
                                                                                 <td>Fecha revisión</td>
                                                                                 <td>Puntos revisión</td>
                                                                                 <td>Promedio</td>
                                                                             </tr>";
                                                        $datos=count($resultadoEvaluaciones);
                                                        foreach ($resultadoEvaluaciones as $evl => $value) 
                                                              { $mostrarHtml.= "<tr align='center' valign='middle' >";
                                                                $mostrarHtml.= "<td>".$resultadoEvaluaciones[$evl]['id_evaluador']."- ".$resultadoEvaluaciones[$evl]['evaluador']."</td>";
                                                                $mostrarHtml.= "<td>".substr($resultadoEvaluaciones[$evl]['fecha_registro'],0,10)."</td>";
                                                                $mostrarHtml.= "<td>".$resultadoEvaluaciones[$evl]['puntaje_parcial']."</td>";
                                                                $mostrarHtml.= "<td>".substr($resultadoEvaluaciones[$evl]['fecha_nuevo'],0,10)."</td>";
                                                                $mostrarHtml.= "<td>".$resultadoEvaluaciones[$evl]['nuevo_puntaje']."</td>";
                                                                if($evl==0)
                                                                    { $mostrarHtml.= "<td rowspan='$datos' ><b>".$resultadoEvaluaciones[0]['puntaje_final']."</b></td>";}
                                                                $mostrarHtml.= "</tr'>";
                                                              }
                                                        $mostrarHtml .= "
                                                                   </table>";
                                                       }
                                                   }
                                                $mostrarHtml.="</td>";
                                                        unset($puntajes);
                                            if($key==0)            
                                                    {    
                                                        $mostrarHtml.= "    <td rowspan='$datosFase' align='center'>".number_format($resultadoListaFase[$key]['puntaje_promedio'],2)."</td>";
                                                        //$mostrarHtml.= "    <td rowspan='$datosFase'  align='left'>".(($resultadoListaFase[$key]['puntaje_promedio']>=$puntos_aprueba)?'Aprobó':'No aprobó');
                                                        $mostrarHtml.= "    <td rowspan='$datosFase'  align='center'>".$resultadoListaFase[$key]['estado_prom']."</td>";
                                                        //busca reclamaciones
                                                        $parametro['id_reclamacion']=$resultadoListaFase[$key]['id_reclamacion'];
                                                        $cadena_sql = $this->miSql->getCadenaSql("consultarReclamacionConcurso", $parametro);
                                                        $resultadoReclamo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                                                        //var_dump($resultadoReclamo);
                                                        if($resultadoReclamo)
                                                            {   $mostrarHtml.= " <td rowspan='$datosFase'  align='left'>".$resultadoReclamo[0]['id']." - ".$resultadoReclamo[0]['reclamo']."</td>
                                                                                 <td rowspan='$datosFase'  align='left'>".substr($resultadoReclamo[0]['fecha_registro'],0,10)."</td>
                                                                                 <td rowspan='$datosFase'  align='left'>";
                                                                    $mostrarHtml.=  "<table id='tablaReclamos' class='table table-striped table-bordered'>
                                                                                        <tr align='center'>
                                                                                          <td>Evaluador</td> 
                                                                                                <td>Código</td>
                                                                                                <td>Respuesta</td>
                                                                                                <td>Observación</td>
                                                                                            </tr>";

                                                                                foreach ($resultadoReclamo as $recl => $value) 
                                                                                               {
                                                                                               $mostrarHtml.= "<tr>";
                                                                                               $mostrarHtml.= "<td>".$resultadoReclamo[$recl]['id_evaluador']."</td> ";
                                                                                               $mostrarHtml.= "<td>".$resultadoReclamo[$recl]['id_rsta']."</td> ";
                                                                                               $mostrarHtml.= "<td>".$resultadoReclamo[$recl]['resultado']."</td> ";
                                                                                               $mostrarHtml.= "<td>".$resultadoReclamo[$recl]['observacion']."</td> ";
                                                                                               $mostrarHtml.= "</tr>";
                                                                                               }
                                                                    $mostrarHtml.= "  </table>"; 
                                                                $mostrarHtml.= "  </td>";
                                                                 
                                                            }
                                                        else{   $mostrarHtml.= "
                                                                                <td rowspan='$datosFase'  align='left'></td>
                                                                                <td rowspan='$datosFase'  align='left'></td>
                                                                                <td rowspan='$datosFase'  align='left'></td>";
                                                            }
                                                    }        
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
                


    }
}

$miSeleccionador = new fasesEvaluacion ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
