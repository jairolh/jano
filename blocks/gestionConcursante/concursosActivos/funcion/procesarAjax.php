<?php
use gestionConcursante\concursosActivos\Sql;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if ($_REQUEST ['funcion'] == 'consultarCriterios') {
	$cadenaSql = $this->sql->getCadenaSql ( 'consultarCriterios', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado2 = json_encode ( $resultado );
	echo $resultado2;
}


?>
