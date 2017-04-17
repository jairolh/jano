<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
 *
 * La ruta absoluta del bloque está definida en $this->ruta
 */
 
$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$nombreFormulario = $esteBloque ["nombre"];

include_once ("core/crypto/Encriptador.class.php");
$cripto = Encriptador::singleton ();
$valorCodificado = "action=" . $esteBloque ["nombre"];
$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$valorCodificado = $cripto->codificar ( $valorCodificado );
//$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" ) . "images/";
 
$host = $this->miConfigurador->getVariableConfiguracion("host");
$host .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$host .= $this->miConfigurador->getVariableConfiguracion("enlace");

    //llama funcion para visualizar al div cuando termina de cargar
    //echo "<script language='javascript'> setTimeout(function(){visible(\"divcarga\")},500)  </script>";
    //echo "<script language='javascript'> $('#divcarga').hide(); </script>";
    //var_dump($_REQUEST);
    // ------------------Division para las pestañas-------------------------
    $atributos ["id"] = "tabs";
    $atributos ["estilo"] = "";
    $atributos ["estiloEnLinea"] = "display:none;"; 
    echo $this->miFormulario->division ( "inicio", $atributos );
    unset ( $atributos );
    {
            // -------------------- Listado de Pestañas (Como lista No Ordenada) -------------------------------

            $items = array (
                            "tabBasicos"   => $this->lenguaje->getCadena ( "tabBasicos" ),            
                            "tabContacto"  => $this->lenguaje->getCadena ( "tabContacto" ),
                            "tabFormacion"  => $this->lenguaje->getCadena ( "tabFormacion" ),
                            "tabProfesional"  => $this->lenguaje->getCadena ( "tabProfesional" ),
                            "tabDocencia"  => $this->lenguaje->getCadena ( "tabDocencia" ),
                            //"tabRegistrarMasivo" => $this->lenguaje->getCadena ( "tabRegistrarMasivo" ) 
            );
            $atributos ["items"] = $items;
            $atributos ["estilo"] = "jqueryui";
            $atributos ["pestañas"] = "true";
            //$atributos ["menu"] = "true";
            //$atributos ["enlacePestaña"] = "true";
            echo $this->miFormulario->listaNoOrdenada ( $atributos );
            unset ( $atributos );



       // ------------------Division para la pestaña 1-------------------------
            $atributos ["id"] = "tabBasicos";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                {//echo '<iframe src="'.$enlaceBasico.'" style="width: 100%; height: 100% " frameborder="0"></iframe> '; 
                 include_once ($this->ruta . "formulario/tabs/datosBasicos.php");
                }
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 1-------------------------

            // ------------------Division para la pestaña 2-------------------------
            $atributos ["id"] = "tabContacto";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                   include_once ($this->ruta . "formulario/tabs/datosContacto.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 2-------------------------
            // ------------------Division para la pestaña 3-------------------------
            $atributos ["id"] = "tabFormacion";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );

            if(!isset($_REQUEST['consecutivo_formacion']))
                   {include_once ($this->ruta . "formulario/tabs/consultarFormacion.php"); }
                   include_once ($this->ruta . "formulario/tabs/datosFormacion.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 3-------------------------
            // ------------------Division para la pestaña 3-------------------------
            $atributos ["id"] = "tabProfesional";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );

            if(!isset($_REQUEST['consecutivo_experiencia']))
                   {include_once ($this->ruta . "formulario/tabs/consultarProfesional.php"); }
                   include_once ($this->ruta . "formulario/tabs/datosProfesional.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 3-------------------------



    }

    echo $this->miFormulario->division ( "fin" );
    unset ( $atributos );
    
// ------------------Inicio Division para progreso-------------------------
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$directorioImg = $url."/blocks/".$esteBloque ["grupo"]."/".$esteBloque ["nombre"]."/images/";
include_once 'my_scriptjs.php'; 

    $atributos ["id"] = "divcarga";
    $atributos ["estilo"] = "jqueryu";
    $atributos ["estiloEnLinea"] = "text-align:center;height:300px;"; 
    echo $this->miFormulario->division ( "inicio", $atributos );
    unset ( $atributos );
        {
        $atributos ["id"] = "gif";
        $atributos ["estilo"] = "jqueryu";
        $atributos ["mensaje"] = "Procesando la información<br>Espere por favor ...";
        $atributos ["estiloEnLinea"] = "color:#000;margin-top:20px; font-size:20px;font-weight:bold;"; 
        //$atributos ["tamanno"] = "grande";
        echo "<img  src='".$directorioImg."load.gif'>";
        echo $this->miFormulario->campoMensaje ( $atributos );
        unset ( $atributos );
        }
    echo $this->miFormulario->division ("fin");
    unset ( $atributos );
    // ------------------Fin Division para progreso-------------------------    
//llama funcion para visualizar al div cuando termina de cargar
echo "<script language='javascript'> setTimeout(function(){desbloquea('divcarga','tabs')},2000)  </script>";

?>
