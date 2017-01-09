<?php
use usuarios\cambiarClave\Sql;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if ($_REQUEST ['funcion'] == 'consultarPerfil') {
    
        if(isset($_REQUEST['id_usuario']))
            {   $parametro['id_usuario']=$_REQUEST['id_usuario'];
                //datos perfiles
                $cadena_sql = $this->sql->getCadenaSql("consultarPerfilUsuario", $parametro);
                $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                
                $tam=count($resultadoPerfil);
                $rolUs='';
                foreach ($resultadoPerfil as $key => $value) {
                    $rolUs.="'".$resultadoPerfil[$key]['rol_id']."'";
                    $tam>($key+1)?$rolUs.=',':'';
                    }
                $parametro['roles']=$rolUs;      
            }
    
        $parametro['subsistema']=$_REQUEST ['valor'];
	$cadenaSql = $this->sql->getCadenaSql ( 'consultaPerfiles', $parametro );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado );
	echo $resultado;
}


?>