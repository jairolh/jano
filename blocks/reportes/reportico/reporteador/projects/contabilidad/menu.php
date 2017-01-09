<?php
/*
$menu_title = SW_PROJECT_TITLE;
$menu = array (
	array ( "language" => "en_gb", "report" => "informe_ingresos.xml", "title" => "Ingresos" )
	);
*/

$menu_title = 'Reportes';
$menu = array();

$dropdown_menu = array(
    array(
        "project" => "contabilidad",
        "title" => "Contabilidad",
        "items" => array(
            array("reportfile" => "terceros.xml", "title" => "Terceros"),
          
        )
    ),
     
);
?>
