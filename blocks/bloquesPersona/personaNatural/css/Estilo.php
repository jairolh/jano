<?php
$indice=0;
$estilo[$indice++]="estiloBloque.css";
$estilo[$indice++]="validationEngine.jquery.css";
$estilo[$indice++]="jquery.dataTables.css";
$estilo[$indice++]="jquery.dataTables.min.css";

$estilo[$indice++]="dataTables.bootstrap.min.css";
$estilo[$indice++]="dataTables.bootstrap.css";
$estilo[$indice++]="dataTables.jqueryui.css";
$estilo[$indice++]="dataTables.jqueryui.min.css";
$estilo[$indice++]="jquery.dataTables_themeroller.css";
$estilo[$indice++]="jquery-ui.min.css";
$estilo[$indice++]="jquery-ui.css";
$estilo[$indice++]="timepicker.css";
$estilo[$indice++]="select2.css";
$estilo[$indice++]="bootstrap-theme.css";
$estilo[$indice++]="bootstrap-theme.min.css";
$estilo[$indice++]="bootstrap.css";
$estilo[$indice++]="bootstrap.min.css";


$rutaBloque=$this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site");

if($unBloque["grupo"]==""){
	$rutaBloque.="/blocks/".$unBloque["nombre"];
}else{
	$rutaBloque.="/blocks/".$unBloque["grupo"]."/".$unBloque["nombre"];
}

foreach ($estilo as $nombre){
	echo "<link rel='stylesheet' type='text/css' href='".$rutaBloque."/css/".$nombre."'>\n";

}
?>
