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
                $cadena_sql = $this->miSql->getCadenaSql("listadoCierreEvaluacion", $parametro);
                $resultadoListaFase= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                //fases de evaluacion
                $cadena_sql = $this->miSql->getCadenaSql("consultaCriterioFase", $parametro);
                $criterioFase= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
               // var_dump($criterioFase);
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
                                            foreach ($criterioFase as $crt => $criterio)
                                                {
                                                 $mostrarHtml.="<th>".$criterioFase[$crt]['nombre']."</th>";
                                                }
                                   $mostrarHtml.="  <th>Total</th> 
                                                    <th>Resultado</th>
                                                    <th>Estado Evaluación</th>
                                                    <th>Reclamación</th>
                                                    <th>Fecha Reclamación</th>
                                                    <th>Respuestas</th>
                                                    <th>Observaciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
                                        foreach($resultadoListaFase as $key=>$value )
                                            { 
                                                //buscar reclamaciones
                                                $puntajes=json_decode($resultadoListaFase[$key]['evaluaciones']);
                                                //calcula los puntos de aprobacion
                                                $puntos_aprueba=($maximo_puntos*$resultadoFases[$fase]['porcentaje_aprueba'])/100;
                                                $mostrarHtml.= "<tr align='center'>";
                                                    foreach ($puntajes as $pts => $puntos)
                                                        {
                                                         $mostrarHtml.="<td align='center'>";
                                                         foreach ($criterioFase as $crt => $criterio)
                                                            {if($criterioFase[$crt]['codigo']==$puntajes[$pts]->id_evaluar)
                                                                {$mostrarHtml.=$puntajes[$pts]->puntaje_final;}
                                                            }
                                                         $mostrarHtml.="</td>";
                                                        }
                                                        unset($puntajes);
                                                $mostrarHtml.= "    <td align='right'>".number_format($resultadoListaFase[$key]['puntaje_promedio'],2)."</td>";
                                                $mostrarHtml.= "    <td align='left'>".(($resultadoListaFase[$key]['puntaje_promedio']>=$puntos_aprueba)?'Aprobó':'No aprobó');
                                                $mostrarHtml.= "    <td align='center'>".$resultadoListaFase[$key]['estado_prom']."</td>";
                                                //busca reclamaciones
                                                $parametro['id_reclamacion']=$resultadoListaFase[$key]['id_reclamacion'];
                                                $cadena_sql = $this->miSql->getCadenaSql("consultarReclamacionConcurso", $parametro);
                                                $resultadoReclamo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                                                if($resultadoReclamo)
                                                    {   $mostrarHtml.= " <td align='left'>".$resultadoReclamo[0]['id']." - ".$resultadoReclamo[0]['reclamo']."</td>
                                                                         <td align='left'>".substr($resultadoReclamo[0]['fecha_registro'],0,10)."</td>
                                                                         <td align='left'>";
                                                         foreach ($resultadoReclamo as $recl => $value) 
                                                                        {
                                                                        $mostrarHtml.= " - ".$resultadoReclamo[$recl]['resultado']."<br>";
                                                                        }
                                                        $mostrarHtml.= "  </td><td align='justify' width='20%' >";
                                                         foreach ($resultadoReclamo as $recl2 => $value) 
                                                                        {
                                                                        $mostrarHtml.= " - ".$resultadoReclamo[$recl2]['observacion']."<br>";
                                                                        }                                                        
                                                        $mostrarHtml.= "  </td>";                
                                                    }
                                                else{   $mostrarHtml.= "
                                                                        <td align='left'></td>
                                                                        <td align='left'></td>
                                                                        <td align='left'></td>
                                                                        <td align='left'></td>";
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
