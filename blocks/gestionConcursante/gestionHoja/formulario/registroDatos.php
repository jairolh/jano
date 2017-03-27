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
$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" ) . "/imagen/";

$host = $this->miConfigurador->getVariableConfiguracion("host");
$host .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$host .= $this->miConfigurador->getVariableConfiguracion("enlace");


//$_REQUEST ['tiempo'] = time();
$variable = "pagina=gestionConcursanteTab";
$variable .= "&opcion=". $_REQUEST ['opcion'];
$variable .= "&campoSeguro=" . $_REQUEST ['tiempo'];
$variable .= "&usuario=". $_REQUEST ['usuario'];
//tabBasico
$enlaceBasico =$variable."&tab=tabBasicos";
$enlaceBasico = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlaceBasico, $host);

$enlaceContacto =$variable."&tab=tabContacto";
$enlaceContacto = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlaceContacto, $host);

//var_dump($_REQUEST);
// ------------------Division para las pestañas-------------------------
$atributos ["id"] = "tabs";
$atributos ["estilo"] = "";
echo $this->miFormulario->division ( "inicio", $atributos );
unset ( $atributos );
{
	// -------------------- Listado de Pestañas (Como lista No Ordenada) -------------------------------
	
	$items = array (
			"tabBasicos"   => $this->lenguaje->getCadena ( "tabBasicos" ),            
                        "tabContacto"  => $this->lenguaje->getCadena ( "tabContacto" ),
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
        
	// ------------------Division para la pestaña 2-------------------------
	
        /*
        $atributos ["id"] = "tabRegistrarMasivo";
	$atributos ["estilo"] = "";
	echo $this->miFormulario->division ( "inicio", $atributos );
	{
		include ($this->ruta . "formulario/tabs/registro_masivo.php");
	}*/
	
	// -----------------Fin Division para la pestaña 2-------------------------
	//echo $this->miFormulario->division ( "fin" );
	
}

echo $this->miFormulario->division ( "fin" );
unset ( $atributos );
?>
