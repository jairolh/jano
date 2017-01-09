<?php
$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
if ($_REQUEST ['funcion'] == 'consultarParametroAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarParametroAjax', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}
if ($_REQUEST ['funcion'] == 'consultarValorParametroAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarValorParametroAjax', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}
if ($_REQUEST ['funcion'] == 'consultarConceptoAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarConceptoAjax', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}
if ($_REQUEST ['funcion'] == 'consultarValorConceptoAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarValorConceptoAjax', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}
?>