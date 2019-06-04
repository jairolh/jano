<?php

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
}	
ob_end_clean();
$ruta=$this->miConfigurador->getVariableConfiguracion('raizDocumento');
//include($ruta.'/core/classes/html2pdf/html2pdf.class.php');
include($ruta.'/plugin/html2pdf/html2pdf.class.php');

//$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque");
$directorio=$this->miConfigurador->getVariableConfiguracion("rutaBloque");
$aplicativo=$this->miConfigurador->getVariableConfiguracion("nombreAplicativo");
$url = $this->miConfigurador->configuracion ["host"] . $this->miConfigurador->configuracion ["site"];
$correo=$this->miConfigurador->getVariableConfiguracion("correoAdministrador");

//$conexion="estructura";
$conexion="reportes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

$parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                'consecutivo_calendario'=>$_REQUEST['consecutivo_calendario'],
                'tipo_cierre'=>$_REQUEST['tipo_cierre']);    
$cadena_sql = $this->sql->getCadenaSql("listadoCierreEvaluacion", $parametro);
$resultadoListaFase= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//consulta los creterios de evaluación de la fase
$parametroEtp=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso']);   
$cadena_sql = $this->sql->getCadenaSql("consultaCriterioFase", $parametroEtp);
$criterioFase= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

//armar el contenido de la cabecera de la Página - carta vert 120,450,120 - oficio HOR 120,1000,120
$paginaHeader= "<page_header>";
$paginaHeader.= "<table align='center' border='0' width = '100%'>";
$paginaHeader.= " <tbody>";
$paginaHeader.= "   <tr valign='middle' >";
$paginaHeader.= "       <td width='120' align='center' rowspan=5 >";
$paginaHeader.= "            <img src='".$directorio."images/escudo_ud.png' alt='Universidad Distrital Francisco José de Caldas' height='100' width='92'>";
$paginaHeader.= "       </td>
                        <td width='1000' align='center'><b><span style='font-size:13.0pt;mso-bidi-font-size:11.0pt;line-height:107%'>".mb_strtoupper($aplicativo, 'UTF-8')."</span></b>
                        </td>";
$paginaHeader.= "       <td width='120' align='center' rowspan=5 >";
$paginaHeader.= "            <img src='".$directorio."images/Jano.png' alt='Sistema Gestión de Concursos' height='51' width='120'>";
$paginaHeader.= "       </td>";
$paginaHeader.= " </tr> ";
$paginaHeader.="    <tr >
                        <td width='1000' align='center'>  
                            <span style='font-size:10.0pt;'> Concurso: ".$_REQUEST['nombre_concurso']."</span>
                        </td>
                    </tr> ";
$paginaHeader.= "   <tr>
                        <td align='center'>  
                            <span style='font-size:10.0pt;'> Fase: ".$_REQUEST['nombre']."</span>
                        </td>
                    </tr> ";
$paginaHeader.="    <tr>
                        <td align='center'>
                            <span style='font-size:9.0pt;'>
                             Listado final de Aspirantes que superarón la fase - Fecha de cierre ".$_REQUEST['cierre']."
                            </span>
                        </td>
                    </tr> ";
$puntaje_aprueba=($_REQUEST['puntos_aprueba']>0)?'Puntaje mínimo aprobación: '.$_REQUEST['puntos_aprueba']:'';
$paginaHeader.= "   <tr>
                        <td align='center'>  
                            <span style='font-size:9.0pt;'>".$puntaje_aprueba."</span>
                        </td>
                    </tr> ";
$paginaHeader.= " </tbody>";
$paginaHeader.= "</table>";
 
$paginaHeader.= "</page_header>";

//armar el contenido del pie de Página
$paginafooter  = "<page_footer>";
$paginafooter .= "<table align='center' width = '100%'>";
$paginafooter .= "  <tr>";
$paginafooter .= "      <td align='center' >";
$paginafooter .= "        <span style='font-size:8.0pt;'>";
$paginafooter .= "        Universidad Distrital Francisco Jos&eacute; de Caldas";
$paginafooter .= "        <br> Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300";
$paginafooter .= "        <br>                        ".$correo;
$paginafooter .= "        </span> ";
$paginafooter .= "        <span style='font-size:7.0pt;'>";
$paginafooter .= "        <br><br>Impreso mediante ". mb_strtolower($aplicativo, 'UTF-8')." - ".date("Y-m-d H:i:s")." - Página [[page_cu]] de [[page_nb]]";
$paginafooter .= "        </span> ";
$paginafooter .= "      </td>";
$paginafooter .= " </tr>";
$paginafooter .= "</table>";
$paginafooter .= "</page_footer>";

//registra el contenido de la tabla - Tamaños carta  max: vert 680 - horz 940 ; Tamaños oficio  max: vert 680 - horz 1240
$anchoCriterio=(600/(count($criterioFase)+1));
$contenido  = "<div align=center>";        
$contenido .= "<table align='center' class=MsoTableGrid border='1' cellspacing='0' cellpadding='0' style='border-collapse:collapse;border:none;'>";        
$contenido .= "   <tr align='center' style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>";        
$contenido .= "     <td align='center' valign=top style='border:solid windowtext 1.0pt;background:#BDD6EE;'>";        
$contenido .= "     <span style='font-size:9.0pt;'>Nro</span>";        
$contenido .= "     </td>"; 
$contenido .= "     <td align='center'  valign=top style='border:solid windowtext 1.0pt;background:#BDD6EE;'>";        
$contenido .= "     <span style='font-size:9.0pt;'>Código</span>";        
$contenido .= "     </td>";         
$contenido .= "     <td align='center'  valign=top style='border:solid windowtext 1.0pt;background:#BDD6EE;'>";        
$contenido .= "     <span style='font-size:9.0pt;'>Perfil</span>";        
$contenido .= "     </td>";    
$contenido .= "     <td align='center'  valign=top style='border:solid windowtext 1.0pt;background:#BDD6EE;'>";        
$contenido .= "     <span style='font-size:9.0pt;'>Inscripción</span>";        
$contenido .= "     </td>";        
$contenido .= "     <td align='center'  valign=top style='border:solid windowtext 1.0pt;background:#BDD6EE;'>";        
$contenido .= "     <span style='font-size:9.0pt;'>Identificación</span>";        
$contenido .= "     </td>";        
$contenido .= "     <td align='center'  valign=top style='border:solid windowtext 1.0pt;background:#BDD6EE;'>";        
$contenido .= "     <span style='font-size:9.0pt;'>Nombre</span>";        
$contenido .= "     </td>";       
foreach ($criterioFase as $crt => $criterio)
    {
    $contenido .= "     <td width='".$anchoCriterio."' align='center'  valign=top style='border:solid windowtext 1.0pt;background:#BDD6EE;'>";        
    $contenido .= "     <span style='font-size:9.0pt;'>".$criterioFase[$crt]['nombre']."</span>";        
    $contenido .= "     </td>"; 
    }
$contenido .= "     <td align='center'  valign=top style='border:solid windowtext 1.0pt;background:#BDD6EE;'>";        
$contenido .= "     <span style='font-size:9.0pt;'>Total</span>";        
$contenido .= "     </td>"; 
$contenido .= "     <td align='center'  valign=top style='border:solid windowtext 1.0pt;background:#BDD6EE;'>";        
$contenido .= "     <span style='font-size:9.0pt;'>Estado</span>";        
$contenido .= "     </td>"; 

$contenido .= "   </tr>";        

$aux=0; 
$listado=array();
foreach($resultadoListaFase as $key=>$value )
    {   if(!in_array($resultadoListaFase[$key]['codigo'], $listado))
            { array_push($listado, $resultadoListaFase[$key]['codigo']);
              $aux=1;
            }
        if($resultadoListaFase[$key]['vacantes']>=$aux)
            {
              $estado='<b>Seleccionado</b>';
              $aux++;
            }
        else{
            //$estado='continúa';
            $estado='';
            $aux++;
            }  

    
    $contenido .= "   <tr style='mso-yfti-irow:1' align='center' valign='middle'>";        
    $contenido .= "   <td width='30'  align='center' ><span style='font-size:7.0pt;'>".($key+1)."</span></td>";        
    $contenido .= "   <td width='80' align='justify'><span style='font-size:7.0pt;'>".$resultadoListaFase[$key]['codigo']."</span></td>";        
    $contenido .= "   <td width='150' align='justify'><span style='font-size:7.0pt;'>".$resultadoListaFase[$key]['perfil']."</span></td>";        
    $contenido .= "   <td width='55'  align='center'><span style='font-size:7.0pt;'>".$resultadoListaFase[$key]['inscripcion']."</span></td>";        
    $contenido .= "   <td width='70' align='left'><span style='font-size:7.5pt;'>".$resultadoListaFase[$key]['identificacion']."</span></td>";        
    $contenido .= "   <td width='130' align='left'><span style='font-size:7.0pt;'>".$resultadoListaFase[$key]['nombre']." ".$resultadoListaFase[$key]['apellido']."</span></td>";
    //decodifica los puntaje de los criterios                    
    $puntajes=json_decode($resultadoListaFase[$key]['evaluaciones']);
    foreach ($criterioFase as $crt => $criterio)
        {
         $contenido .="<td width='".$anchoCriterio."'  align='center'> ";
         foreach ($puntajes as $pts => $puntos)
            {if($criterioFase[$crt]['codigo']==$puntajes[$pts]->id_evaluar)
                {$contenido .= "<span style='font-size:7.5pt;'>".$puntajes[$pts]->puntaje_final."</span>";
                }
            }
         $contenido .="</td>";
        }
        unset($puntajes);    
    $contenido .= "   <td width='".$anchoCriterio."'  align='center'><span style='font-size:7.5pt;'>".number_format($resultadoListaFase[$key]['puntaje_promedio'],2)."</span></td>";        
    if($resultadoListaFase[$key]['puntaje_promedio']>=$_REQUEST['puntos_aprueba'])
        {$contenido .= "   <td width='60' align='justify'><span style='font-size:7.5pt;color:green'>$estado</span></td>"; }       
    else
        {$contenido .= "   <td width='60' align='justify'><span style='font-size:7.5pt;color:red'> No continúa</span></td>"; }       
    $contenido .= "   </tr>";
    }

$contenido .= "</table>   ";    
$contenido .= "</div>   ";        
    
//arma la pagina en pdf    
$contenidoPagina = "<page backtop='30mm' backbottom='20mm' backleft='10mm' backright='10mm'>";    
$contenidoPagina .= $paginaHeader;
$contenidoPagina .= $paginafooter;
$contenidoPagina .= $contenido;
$contenidoPagina .= "</page>";
$nombre= 'ListaFase_'.str_replace(' ','_',trim($_REQUEST['nombre'])).'_'.str_replace(' ','_',trim($_REQUEST['nombre_concurso'])).'.pdf';
//$contenido .= "<nobreak>"; 

/**
* @param  string   $orientation page orientation, same as TCPDF
* @param  mixed    $format      The format used for pages, same as TCPDF
* @param  $tring   $langue      Langue : fr, en, it...
* @param  boolean  $unicode     TRUE means that the input text is unicode (default = true)
* @param  String   $encoding    charset encoding; default is UTF-8
* @param  array    $marges      Default marges (left, top, right, bottom)
* @return HTML2PDF $this
*/
//$html2pdf = new HTML2PDF('P','LETTER','es'); //Vertical
$html2pdf = new HTML2PDF('L','LEGAL','es','true','UTF-8',array(0.5, 5, 0.5, 4)); //Horizontal
$res = $html2pdf->WriteHTML($contenidoPagina);
$html2pdf->Output($nombre,'D');
//$html2pdf->Output('certificado.pdf');
?>