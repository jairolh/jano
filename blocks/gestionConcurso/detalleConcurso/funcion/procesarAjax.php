<?php
use gestionConcurso\detalleConcurso\Sql;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if ($_REQUEST ['funcion'] == 'consultarModalidad') {
    
        $parametro['tipo_concurso']=$_REQUEST ['valor'];
	$cadenaSql = $this->sql->getCadenaSql ( 'consultaModalidad', $parametro );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado );
	echo $resultado;
}


?>