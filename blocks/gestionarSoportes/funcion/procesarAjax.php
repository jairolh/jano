<?php 
use gestionarSoportes\Sql;

$conexion = "novedades";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if (isset($_REQUEST ['funcion'])) {
	switch ($_REQUEST ['funcion']) {
		case 'consultarConcepto':
                        $parametros['tipo']=$_REQUEST['valor'];
			$cadenaSql = $this->sql->getCadenaSql ('listarConcepto', $parametros);
			break;
                    
                case 'consultarUnidad':
                        $tam=strlen($_REQUEST['valor']);
                        $parametros['tipo']=substr($_REQUEST['valor'],0,1);
                        $parametros['concepto']=substr($_REQUEST['valor'],-($tam-1));
			$cadenaSql = $this->sql->getCadenaSql ('buscarUnidad', $parametros);
			break;    
	}
	
	$resultadoItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	echo json_encode($resultadoItems);
}


?>