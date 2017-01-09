<?php
/*
$menu_title = SW_PROJECT_TITLE;
$menu = array (
	array ( "language" => "en_gb", "report" => ".*\.xml", "title" => "<AUTO>" )
	);*/

$menu_title = 'Reportes';
$menu = array();

$dropdown_menu = array(
		array(
				"project" => "nomina",
				"title" => "Nomina",
				"items" => array(
						array("reportfile" => "reporte_desagregado_nomina.xml", "title" => "Nomina Desagregada"),
						array("reportfile" => "reporte_retencion_fuente.xml", "title" => "Retención en la Fuente"),
				)
		),
		 
);
?>
