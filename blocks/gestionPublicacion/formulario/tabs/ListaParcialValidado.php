<?php
use gestionPublicacion\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class faseParcial{
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
            $rutaBloque.= $esteBloque['grupo'] . $esteBloque['nombre'];
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
            
            $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                             'consecutivo_calendario'=>$_REQUEST['consecutivo_calendario'],
                             'tipo_cierre'=>$_REQUEST['tipo_cierre']);    
            $cadena_sql = $this->miSql->getCadenaSql("listadoCierreRequisitos", $parametro);
            $resultadoListaFase= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $cierre=isset($resultadoListaFase)?substr($resultadoListaFase[0]['fecha_registro'],0,10):'';
            //$cierre=isset($resultadoListaFase)?$resultadoListaFase[0]['fecha_registro']:'';
            $esteCampo = "marcoCerrado";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            //$atributos ["leyenda"] =  "<b>".$this->lenguaje->getCadena ( $esteCampo )."</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {   
                    $variableResumen = "pagina=publicacion"; //pendiente la pagina para modificar parametro                                                        
                    $variableResumen.= "&action=".$esteBloque["nombre"];
                    $variableResumen.= "&bloque=" . $esteBloque["id_bloque"];
                    $variableResumen.= "&bloqueGrupo=" . $esteBloque["grupo"];
                    $variableResumen.= "&opcion=resumenRequisitos";
                    $variableResumen.= "&tipo_cierre=".$_REQUEST['tipo_cierre'];
                    $variableResumen.= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
                    $variableResumen.= "&consecutivo_calendario=".$_REQUEST['consecutivo_calendario']; 
                    $variableResumen.= "&fase=".$_REQUEST['fase'];  
                    $variableResumen.= "&nombre_concurso=" . $_REQUEST ['nombre_concurso'];
                    $variableResumen.= "&nombre=" .$_REQUEST['nombre'];      
                    $variableResumen.= "&cierre=" .$cierre;      
                    $variableResumen = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableResumen, $directorio);
                    //------------------Division para los botones-------------------------
                    $atributos["id"]="botones";
                    $atributos["estilo"]="marcoBotones";
                    echo $this->miFormulario->division("inicio",$atributos);

                    $enlace = "<a href='".$variableResumen."'>";
                    $enlace.="<img src='".$rutaBloque."/images/pdfImage.png' width='25px'> <u>Descargar Listado</u>";
                    $enlace.="</a><br>";       
                    echo $enlace;
                    echo $this->miFormulario->division("fin");
                    //muestra la cabecera del reporte
                    $mostrarHtml=$this->cabecera($atributosGlobales,$rutaBloque,$cierre);
                    if($resultadoListaFase)
                    {   //-----------------Inicio de Conjunto de Controles----------------------------------------
                        $esteCampo = "marcoLista";
                        $atributos["estilo"] = "jqueryui";
                        $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                        //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                        unset($atributos);
                         $mostrarHtml.="<div class='cell-border'>";
                         $mostrarHtml.="<table id='tablaListaParcial' class='table table-striped table-bordered'>";
                         $mostrarHtml.="<thead>
                                        <tr align='center' class='textoAzul'>
                                            <th>Id</th>
                                            <th>Código</th>
                                            <th>Perfil</th>
                                            <th>Inscripción</th>
                                            <th>Identificación</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Cumple</th>
                                            <th>Observacion</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoListaFase as $key=>$value )
                                    {   $mostrarHtml.= "<tr align='center'>
                                                    <td align='left'>".($key+1)."</td>
                                                    <td align='justify' width='10%'>".$resultadoListaFase[$key]['codigo']."</td>
                                                    <td align='justify' width='15%'>".$resultadoListaFase[$key]['perfil']."</td>
                                                    <td align='left'>".$resultadoListaFase[$key]['inscripcion']."</td>
                                                    <td align='left'>".$resultadoListaFase[$key]['identificacion']."</td>
                                                    <td align='left' width='10%' >".$resultadoListaFase[$key]['nombre']."</td>
                                                    <td align='left' width='10%'>".$resultadoListaFase[$key]['apellido']."</td>
                                                    <td align='left'>".ucwords(strtolower($resultadoListaFase[$key]['cumple_requisito']))." cumple</td>
                                                    <td align='justify' width='25%' >".$resultadoListaFase[$key]['observacion']."</td>";
                                           $mostrarHtml.= "</tr>";
                                           //echo $mostrarHtml;
                                           //unset($mostrarHtml);
                                    }
                        $mostrarHtml.="</tbody>";
                        $mostrarHtml.="</table></div>";
                        echo $mostrarHtml;
                                //Fin de Conjunto de Controles

                    }else
                    {
                            $atributos["id"]="divNoEncontroPerfil";
                            $atributos["estilo"]="";
                       //$atributos["estiloEnLinea"]="display:none"; 
                            echo $this->miFormulario->division("inicio",$atributos);

                            //-------------Control Boton-----------------------
                            $esteCampo = "noEncontroPerfil";
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
    
    function cabecera($atributosGlobales,$rutaBloque,$cierre )
            {  $cajaNombre="width='15%'";
                        $cajaDato="width='35%'";
                        $mostrarHtml= "<div style ='width: 98%; padding-left: 2%;' class='cell-border'>";
                        $mostrarHtml.= "<table class='table table-striped table-bordered'>";
                        $mostrarHtml.= " <tbody>";
                                $mostrarHtml.= "<tr align='center' valign='middle' >
                                                        <td rowspan=3 colspan=2 align='center'>";
                                                               // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                              $esteCampo = 'escudo';
                                                              $atributos ['id'] = $esteCampo;
                                                              $atributos['imagen']= $rutaBloque."/images/escudo_ud.png";
                                                              $atributos['estilo']='campoImagen';
                                                              $atributos['etiqueta']='Universidad Distrital Francisco José de Caldas';
                                                              $atributos['borde']='';
                                                              $atributos ['ancho'] = '110px';
                                                              $atributos ['alto'] = '120px';
                                                              $atributos = array_merge ( $atributos, $atributosGlobales );
                                                              $mostrarHtml.= $this->miFormulario->campoImagen( $atributos );
                                                              unset ( $atributos );
                                                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------  
                                $mostrarHtml.=     "    </td>
                                                        <th class='textoAzul' colspan=2> LISTA RESULTADOS EVALUACIÓN - FECHA CIERRE ".$cierre."</th></tr> ";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>CONCURSO: </th>
                                                        <td class='table-tittle estilo_tr' $cajaDato>".$_REQUEST['nombre_concurso']."</td></tr> ";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>FASE:</th>
                                                        <td class='table-tittle estilo_tr' $cajaDato>".$_REQUEST['nombre']."</td></tr> "; 
                                
                        $mostrarHtml.= "</tbody>";
                        $mostrarHtml.= "</table></div>";
                        return $mostrarHtml;
                        unset($mostrarHtml);
            }
    
}

$miSeleccionador = new faseParcial( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
