<?php
$host = $this->miConfigurador->getVariableConfiguracion ( 'host' );
$sitio = $this->miConfigurador->getVariableConfiguracion ( 'site' );
$estiloPredeterminado = $this->miConfigurador->getVariableConfiguracion ( 'estiloPredeterminado' );

$indice = 0;
$estilo = array ();

$funcion [$indice] = "funciones.js";
$indice ++;
$funcion[$indice++]="tinymce/tinymce.min.js";


if (isset ( $_REQUEST ['jquery'] )) {
    $funcion [$indice] = 'javascript/jquery.js';
    $indice ++;
    $funcion [$indice] = 'javascript/jquery-ui/jquery-ui.js';
    $estilo [$indice] = 'javascript/jquery-ui/jquery-ui-themes/themes/' . $estiloPredeterminado . '/jquery-ui.css';
    $indice ++;
    $funcion [$indice] = "javascript/jquery.validationEngine.js";
    $indice ++;
    $funcion [$indice] = "javascript/jquery.validationEngine-es.js";
    $indice ++;
}

/////////////////////////////////////////////////////////////////////////////////////////////////
if (isset ( $_REQUEST ['jquery'] )) {
	if($_REQUEST ['jquery'] != 'true'){//Se carga una versión de jquery en particular
		$funcion [$indice] = 'javascript/jquery-'. $_REQUEST ['jquery'] . '.js';
	} else {
		$funcion [$indice] = 'javascript/jquery.js';
	}
	$indice ++;
}
///////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_REQUEST['bootstrapjs'])){
	$funcion [] = "javascript/boostrap/js/bootstrap.js";

}

if(isset($_REQUEST['bootstrapcss'])){

	$estilo [] = 'javascript/bootstrap/css/bootstrap.css';

}

if(isset($_REQUEST['bootstrap'])){

	$estilo [] = 'javascript/bootstrap/css/bootstrap.css';
	$funcion [] = "bootstrap/bootstrap-3.3.5-dist/js/bootstrap.js";
}

/*if(isset($_REQUEST['datatables'])){
	$funcion [] = "javascript/datatables/jquery.dataTables.js";
	$estilo [] = 'javascript/datatables/jquery.dataTables_themeroller.css';
	$estilo [] = 'javascript/datatables/dataTables.responsive.css';

}
*/

foreach ( $funcion as $nombre ) {
    echo "<script type='text/javascript' src='" . $host . $sitio . '/plugin/scripts/' . $nombre . "'></script>\n";
}

foreach ( $estilo as $nombre ) {
    echo "<link rel='stylesheet' type='text/css' href='" . $host . $sitio . '/plugin/scripts/' . $nombre . "'>\n";
}


?>
