<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
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
// ------------------Division para las pestañas-------------------------
$atributos ["id"] = "tabs";
$atributos ["estilo"] = "";
echo $this->miFormulario->division ( "inicio", $atributos );
// unset ( $atributos );
{	$atributos ["id"] = "tabParcial";
	$atributos ["estilo"] = "";
	echo $this->miFormulario->division ( "inicio", $atributos );
            {
                switch ($_REQUEST ['fase']) {
                                case 'requisito':    
					include ($this->ruta . "formulario/tabs/ListaParcialValidado.php");
				    break;
                                case 'evaluacion':    
					include ($this->ruta . "formulario/tabs/ListaParcialEvaluado.php");
				    break;				                                  
                                case 'elegibles':    
					include ($this->ruta . "formulario/tabs/ListaParcialElegibles.php");
				    break;                                
        		}
                
	    
            }
	echo $this->miFormulario->division ( "fin" );	
	
}

echo $this->miFormulario->division ( "fin" );

?>
