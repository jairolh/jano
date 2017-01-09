<?php
$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if ($_REQUEST ['funcion'] == 'consultarDependenciaAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarDependenciaAjax', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
        
	$resultado = json_encode ( $resultado);
	echo $resultado;
}


if ($_REQUEST ['funcion'] == 'consultarTipoVinculacionAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'consultarTipoVinculacionAjax', $_REQUEST['valor'] );
	$resultado1 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
        
	$resultado1 = json_encode ( $resultado1);
	echo $resultado1;
}
?>

