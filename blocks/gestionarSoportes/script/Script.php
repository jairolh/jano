<?php
/**
 * Importante: Este script es invocado desde la clase ArmadorPagina. La información del bloque se encuentra
 * en el arreglo $esteBloque. Esto también aplica para todos los archivos que se incluyan.
 */
$indice = 0;
$funcion [$indice ++] = "jquery.validationEngine.js";
$funcion [$indice ++] = "jquery.validationEngine-es.js";

//select2
$funcion [$indice ++] = "select2.full.js";

//datatable
$funcion [$indice ++] = "jquery.dataTables.min.js";
$funcion [$indice ++] = "dataTables.bootstrap.min.js";

//datetime
$funcion [$indice ++] = "moment-with-locales.js";
$funcion [$indice ++] = "bootstrap-datetimepicker.js";

//fileinput
$funcion [$indice ++] = "fileinput.min.js";
$funcion [$indice ++] = "es.js";

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" );

if ($esteBloque ["grupo"] == "") {
	$rutaBloque .= "/blocks/" . $esteBloque ["nombre"];
} else {
	$rutaBloque .= "/blocks/" . $esteBloque ["grupo"] . "/" . $esteBloque ["nombre"];
}

$_REQUEST ['tiempo'] = time ();

foreach ( $funcion as $clave => $nombre ) {
	if (! isset ( $embebido [$clave] )) {
		echo "\n<script type='text/javascript' src='" . $rutaBloque . "/script/" . $nombre . "'>\n</script>\n";
	} else {
		echo "\n<script type='text/javascript'>";
		include ($nombre);
		echo "\n</script>\n";
	}
}

include ("ajax.php");

?>
