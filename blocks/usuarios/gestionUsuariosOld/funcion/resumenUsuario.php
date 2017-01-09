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


$contenidoPagina = "<page backtop='30mm' backbottom='10mm' backleft='20mm' backright='20mm'>";
$contenidoPagina .= "<page_header>
        <table align='center' style='width: 100%;'>
            <tr>
                <td align='center' >
                    <img src='".$directorio."css/images/escudo.jpg'>
                </td>
                <td align='center' >
                    <font size='18px'><b>UNIVERSIDAD DISTRITAL</b></font>
                    <br>
                    <font size='18px'><b>FRANCISCO JOS&Eacute; DE CALDAS</b></font>
                    <br>
                </td>
                <td align='center' >
                    <img src='".$directorio."css/images/sabio.jpg' width='60'>
                </td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table align='center' width = '100%'>
            <tr>
                <td align='center'>
                    <img src='".$directorio."css/images/escudo.jpg'>
                </td>
            </tr>
            <tr>
                <td align='center'>
                    Universidad Distrital Francisco Jos&eacute; de Caldas
                    <br>
                    Todos los derechos reservados.
                    <br>
                    Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300
                    <br>
                    ".$correo."                    
                </td>
            </tr>
        </table>
    </page_footer>";
    
    $contenidoPagina .= "
        <p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>".$aplicativo."</span></b></p>
<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>REGISTRO DE USUARIO EXITOSO</span></b></p>

<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'> &nbsp; </span></b></p>

<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'>El usuario ".$_REQUEST['nombres']." ".$_REQUEST['apellidos'].", con
identificación ".$_REQUEST['identificacion'].", se ha registrado con éxito. A continuación se relaciona
la información del usuario incluyendo su nueva contraseña. Es importante
resaltar que al ingresar por primera vez a la plataforma debe cambiar su
contraseña  </span></p>

<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'> &nbsp; </span></p>

<div align=center>
 
<table align='center' class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='width:80%;border-collapse:collapse;border:none;'>   
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
  <td width=123 valign=top style='width:12%;border:solid windowtext 1.0pt; 
  mso-border-alt:solid windowtext .5pt;background:#BDD6EE;mso-background-themecolor:
  accent1;mso-background-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height: 
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Identificación  </span></b></p>
  </td>
  <td width=236 valign=top style='width:95%;border:solid windowtext 1.0pt;  
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$_REQUEST['identificacion']."</span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1'>
  <td width=123 valign=top style='width:92.1pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  background:#BDD6EE;mso-background-themecolor:accent1;mso-background-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Nombres  </span></b></p>
  </td>
  <td width=236 valign=top style='width:177.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$_REQUEST['nombres']."</span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:2'>
  <td width=123 valign=top style='width:92.1pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  background:#BDD6EE;mso-background-themecolor:accent1;mso-background-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Apellidos  </span></b></p>
  </td>
  <td width=236 valign=top style='width:177.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$_REQUEST['apellidos']."</span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:3'>
  <td width=123 valign=top style='width:92.1pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  background:#BDD6EE;mso-background-themecolor:accent1;mso-background-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Correo  </span></b></p>
  </td>
  <td width=236 valign=top style='width:177.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$_REQUEST['correo']."</span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:4'>
  <td width=123 valign=top style='width:92.1pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  background:#BDD6EE;mso-background-themecolor:accent1;mso-background-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Teléfono  </span></b></p>
  </td>
  <td width=236 valign=top style='width:177.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$_REQUEST['telefono']."</span></p>
  </td>
 </tr>
<tr style='mso-yfti-irow:5;mso-yfti-lastrow:yes'>
  <td width=123 valign=top style='width:92.1pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  background:#BDD6EE;mso-background-themecolor:accent1;mso-background-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Perfil </span></b></p>
  </td>
  <td width=236 valign=top style='width:177.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$_REQUEST['perfilUs']."</span></p>
  </td>
 </tr>
</table>

</div>

<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%'> &nbsp; </span></p>

<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>DATOS DE INGRESO A LA PLATAFORMA</span></b></p>

<div align=center>

<table align='center' class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;
 mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
  <td width=123 valign=top style='width:12%;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;background:#BDD6EE;mso-background-themecolor:
  accent1;mso-background-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Dirección </span></b></p>
  </td>
  <td width=236 valign=top style='width:75%;border:solid windowtext 1.0pt; 
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$url."</span></p>
  </td>
 </tr>
  <tr style='mso-yfti-irow:3'>
  <td width=123 valign=top style='width:92.1pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  background:#BDD6EE;mso-background-themecolor:accent1;mso-background-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Usuario </span></b></p>
  </td>
  <td width=236 valign=top style='width:177.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$_REQUEST['id_usuario']."</span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1;mso-yfti-lastrow:yes'>
  <td width=123 valign=top style='width:92.1pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  background:#BDD6EE;mso-background-themecolor:accent1;mso-background-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Contraseña  </span></b></p>
  </td>
  <td width=236 valign=top style='width:177.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$_REQUEST['password']."</span></p>
  </td>
 </tr>
</table>

</div>

    ";
    
    $contenidoPagina .= "</page>";
    
    $html2pdf = new HTML2PDF('P','LETTER','es');
    $res = $html2pdf->WriteHTML($contenidoPagina);
    $html2pdf->Output('resumenUsuario.pdf','D');
    //$html2pdf->Output('certificado.pdf');
?>