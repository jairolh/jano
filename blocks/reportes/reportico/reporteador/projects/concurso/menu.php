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
        "project" => "concurso",
        "title" => "Concursos de Meritos",
        "items" => array(
            array("reportfile" => "inscritosConcurso.xml", "title" => "Inscritos por Concurso"),
          
        )
    ),
     
);

?>
