<?php
use gestionConcursante\gestionHoja\funcion;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );


if ($_REQUEST ['funcion'] == 'consultarDepartamentoAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarDepartamentoAjax', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultarCiudadAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarCiudadAjax', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultarIESAjax') {
        $parametro=array('pais_institucion'=>$_REQUEST['valor']);
	$cadenaSql = $this->sql->getCadenaSql ( 'consultarInstitucion', $parametro );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultarProgramaAjax') {
        $parametro=array('codigo_ies'=>$_REQUEST['valor']);
	$cadenaSql = $this->sql->getCadenaSql ( 'consultarPrograma', $parametro );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}


?>