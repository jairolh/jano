<?php
namespace gestionConcursante\gestionHoja\funcion;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class CargarArchivo {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;
    var $miLogger;

    function __construct($lenguaje, $sql, $funcion, $miLogger) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
        $this->miLogger= $miLogger;
    }

    function procesarArchivo($tipoSoporte) {
        
        $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
        $rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/";
        $conexion="estructura";
	$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        foreach ( $_FILES as $key => $values ) 
            {   if(strlen($_FILES [$key]['name'])>0)
                    {
                        $parametro['tipo_soporte']=$key;
                        $cadena_sql = $this->miSql->getCadenaSql("buscarTipoSoporte", $parametro);
                        $resultadoSoporte = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                        if($resultadoSoporte)
                            {
                                $nombreArchivo= $_REQUEST['consecutivo']. "_" . $_FILES [$key]['name'];
                                $destino[$key] = $rutaBloque .$resultadoSoporte[0]['ubicacion']."/".$nombreArchivo;
                                $arregloSoporte = array('id_usuario'=>$_REQUEST['id_usuario'],
                                                        'tipo_soporte'=>$resultadoSoporte[0]['tipo_soporte'],
                                                        'consecutivo_persona'=>$_REQUEST['consecutivo'],
                                                        'tipo_dato'=>$tipoSoporte,
                                                        'consecutivo_dato'=>$_REQUEST['consecutivo_dato'],
                                                        'nombre'=>$nombreArchivo,
                                                        'alias'=>$_FILES [$key]['name']
                                                        );
                                if (copy ( $_FILES [$key]['tmp_name'], $destino[$key] )) 
                                    {   $cadenaSql = $this->miSql->getCadenaSql ( 'registroSoporte',$arregloSoporte );
                                        $resultadoBasicos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registro", $arregloSoporte, "registroSoporte" );
                                        $status = "Archivo subido: <b>" . $nombreArchivo . "</b>";
                                    }
                                else{$status = "Error al subir el archivo 1";
                                    }
                            } 
                        else{$status = "Error al subir el archivo 1";
                            }    
                    }
            }
    }

}

?>