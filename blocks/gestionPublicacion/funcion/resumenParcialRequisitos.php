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
$cadena_sql = $this->sql->getCadenaSql("listadoCierreRequisitos", $parametro);
$resultadoListaFase= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

//armar el contenido de la cabecera de la Página
$paginaHeader= "<page_header>";
$paginaHeader.= "<table align='center' border='0' width = '100%'>";
$paginaHeader.= " <tbody>";
$paginaHeader.= "   <tr valign='middle' >";
$paginaHeader.= "       <td width='120' align='center' rowspan=4 >";
$paginaHeader.= "            <img src='".$directorio."images/escudo_ud.png' alt='Universidad Distrital Francisco José de Caldas' height='100' width='92'>";
$paginaHeader.= "       </td>
                        <td width='450'align='center'><b><span style='font-size:13.0pt;mso-bidi-font-size:11.0pt;line-height:107%'>".mb_strtoupper($aplicativo, 'UTF-8')."</span></b>
                        </td>";
$paginaHeader.= "       <td width='120' align='center' rowspan=4 >";
$paginaHeader.= "            <img src='".$directorio."images/Jano.png' alt='Sistema Gestión de Concursos' height='51' width='120'>";
$paginaHeader.= "       </td>";
$paginaHeader.= " </tr> ";
$paginaHeader.="    <tr >
                        <td width='450' align='center'>  
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
                             Lista de resultados de evaluación - Fecha Cierre ".$_REQUEST['cierre']."
                            </span>
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

//registra el contenido de la tabla - Tamaños carta  max: vert 680 - horz 940
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
$contenido .= "     <td align='center'  valign=top style='border:solid windowtext 1.0pt;background:#BDD6EE;'>";        
$contenido .= "     <span style='font-size:9.0pt;'>Cumple</span>";        
$contenido .= "     </td>"; 
$contenido .= "   </tr>";        


foreach($resultadoListaFase as $key=>$value )
   {           
    $contenido .= "   <tr style='mso-yfti-irow:1' align='center' valign='middle'>";        
    $contenido .= "   <td width='25'  align='center' ><span style='font-size:7.0pt;'>".($key+1)."</span></td>";        
    $contenido .= "   <td width='85' align='justify'><span style='font-size:7.0pt;'>".$resultadoListaFase[$key]['codigo']."</span></td>";                
    $contenido .= "   <td width='150' align='justify'><span style='font-size:7.5pt;'>".$resultadoListaFase[$key]['perfil']."</span></td>";        
    $contenido .= "   <td width='60'  align='center'><span style='font-size:7.5pt;'>".$resultadoListaFase[$key]['inscripcion']."</span></td>";        
    $contenido .= "   <td width='60' align='left'><span style='font-size:7.5pt;'>".$resultadoListaFase[$key]['identificacion']."</span></td>";        
    $contenido .= "   <td width='180' align='left'><span style='font-size:7.5pt;'>".$resultadoListaFase[$key]['nombre']." ".$resultadoListaFase[$key]['apellido']."</span></td>";
    $contenido .= "   <td width='60'  align='center'><span style='font-size:7.5pt;'>".ucwords(strtolower($resultadoListaFase[$key]['cumple_requisito']))." cumple</span></td>";        
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
$html2pdf = new HTML2PDF('P','LETTER','es','true','UTF-8',array(0.5, 5, 0.5, 4)); //Horizontal
$res = $html2pdf->WriteHTML($contenidoPagina);
$html2pdf->Output($nombre,'D');
//$html2pdf->Output('certificado.pdf');
?>