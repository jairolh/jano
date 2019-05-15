<?php
use gestionConcurso\gestionInscripcion\Sql;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if ($_REQUEST ['funcion'] == 'consultarModalidad') {

        $parametro['tipo_concurso']=$_REQUEST ['valor'];
	$cadenaSql = $this->sql->getCadenaSql ( 'consultaModalidad', $parametro );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado );

	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultarCriterio') {

        $parametro['consecutivo_factor']=$_REQUEST ['valor'];
	$cadenaSql = $this->sql->getCadenaSql ( 'consultaCriterio', $parametro );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado );
	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultarAspirantesAsignados') {

       $rol=explode('-',$_REQUEST ['valor']);  
        $parametro['rol']=$rol[0];
        $parametro['usuario']=$rol[1];
        $parametro['concurso']=$_REQUEST ['valor2'];
  

	$cadenaSql = $this->sql->getCadenaSql ( 'consultaJurado2', $parametro );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
  $resultado = json_encode ( $resultado );
	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultarTiposJurado') {
	$cadenaSql = $this->sql->getCadenaSql ( 'consultarTiposJurado');
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado );
	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultarAspirantesEvaluador') {

    
        $rol=explode('-',$_REQUEST ['valor']);  
        $parametro['rol']=$rol[0];
        $parametro['usuario']=$rol[1];
        $parametro['concurso']=$_REQUEST ['valor2'];

	$cadenaSql = $this->sql->getCadenaSql ( 'consultaJurado2', $parametro );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
          $resultado = json_encode ( $resultado );
	echo $resultado;
}

?>
