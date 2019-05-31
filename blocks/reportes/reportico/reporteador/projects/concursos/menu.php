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
				"project" => "concursos",
				"title" => "Concursos",
				"items" => array(
						array("reportfile" => "listado_evaluadores.xml", "title" => "Listado usuarios evaluadores"),
						array("reportfile" => "verifico_requisitos.xml", "title" => "Resumen verificación requisitos"),
                                                array("reportfile" => "verifica_registro_evaluaciones.xml", "title" => "Resumen registro evaluaciones"),
				)
		),
		 
);
?>
