<?php
$indice = 0;
$estilo [$indice ++] = "estiloBloque.css";
$estilo [$indice ++] = "validationEngine.jquery.css";
$estilo [$indice ++] = "estiloTexto.css";
$estilo [$indice ++] = "select2.min.css";

// Tablas
$estilo [$indice ++] = "dataTables.bootstrap.min.css";

//datepicker
$estilo [$indice ++] = "bootstrap-datetimepicker.css";

//fileinput
$estilo [$indice ++] = "fileinput.min.css";

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" );

if ($unBloque ["grupo"] == "") {
	$rutaBloque .= "/blocks/" . $unBloque ["nombre"];
} else {
	$rutaBloque .= "/blocks/" . $unBloque ["grupo"] . "/" . $unBloque ["nombre"];
}

foreach ( $estilo as $nombre ) {
	echo "<link rel='stylesheet' type='text/css' href='" . $rutaBloque . "/css/" . $nombre . "'>\n";
}
?>
