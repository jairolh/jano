<?php
use usuarios\gestionRoles\Sql;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if ($_REQUEST ['funcion'] == 'consultarPerfil') {
    
        $parametro['subsistema']=$_REQUEST ['valor'];
	$cadenaSql = $this->sql->getCadenaSql ( 'consultaPerfiles', $parametro );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado );
	echo $resultado;
}


?>