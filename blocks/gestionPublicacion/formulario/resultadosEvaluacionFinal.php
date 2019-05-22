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
{
	$atributos ["id"] = "tabEvaluacion";
	$atributos ["estilo"] = "";
	echo $this->miFormulario->division ( "inicio", $atributos );
            {
	     include ($this->ruta . "formulario/tabs/perfil.php");
             include ($this->ruta . "formulario/tabs/datosBasicos.php");
             include ($this->ruta . "formulario/tabs/evaluacionPerfil.php");
             include ($this->ruta . "formulario/tabs/evaluacionFases.php");             
            }
	echo $this->miFormulario->division ( "fin" );	
	
}

echo $this->miFormulario->division ( "fin" );

?>
