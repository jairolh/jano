<?php
use gestionPublicacion\funcion\redireccion;

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
            //$conexion="estructura";
            $conexion="reportes";
            $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
                //identifca lo roles para la busqueda de subsistemas
            $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                             'consecutivo_inscrito'=>$_REQUEST['consecutivo_inscrito'],
                             'tipo_dato'=>'evaluacionPerfil');    
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
                    if($resultadoEvaluacion)
                        {     
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                            $esteCampo = "marcoIdioma";
                            $atributos["estilo"] = "jqueryui";
                            $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                            //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                            unset($atributos);
                            $mostrarHtml =  "<div class='cell-border'><table id='tablaProcesos' class='table table-striped table-bordered'>";
                            $mostrarHtml .= "<thead>
                                    <tr align='center' class='textoAzul'>
                                        <th>Fecha</th>
                                        <th>Resultado</th>
                                        <th>Estado Evaluación</th>
                                        <th>Observación</th>
                                        <th>Reclamación</th>
                                        <th>Fecha Reclamación</th>
                                        <th>Respuesta</th>
                                        <th>Observación Respuesta </th>
                                    </tr>
                                </thead>
                                <tbody>";
                                foreach($resultadoEvaluacion as $key=>$value )
                                    {   $parametro['id_evalua']=$resultadoEvaluacion[$key]['consecutivo_valida'];
                                        $cadena_sql = $this->miSql->getCadenaSql("consultarReclamacionConcurso", $parametro);
                                        $resultadoReclamo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                                       // var_dump($resultadoReclamo);
                                        $mostrarHtml.= "<tr align='center'>
                                                            <td align='left'>".substr($resultadoEvaluacion[$key]['fecha_registro'],0,10)."</td>
                                                            <td align='left'>".$resultadoEvaluacion[$key]['cumple_requisito']."</td>
                                                            <td align='left'>".$resultadoEvaluacion[$key]['estado']."</td>
                                                            <td align='justify' width='20%'>".$resultadoEvaluacion[$key]['observacion']."</td>";
                                        if($resultadoReclamo)
                                            {  $mostrarHtml.= "
                                                                <td align='left'>".$resultadoReclamo[0]['id']." - ".$resultadoReclamo[0]['reclamo']."</td>
                                                                <td align='left'>".substr($resultadoReclamo[0]['fecha_registro'],0,10)."</td>
                                                                <td align='left'>".$resultadoReclamo[0]['resultado']."</td>
                                                                <td align='justify' width='20%' >".$resultadoReclamo[0]['observacion']."</td>";}
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

$miSeleccionador = new perfilEvaluacion ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
