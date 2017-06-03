<?php

require_once ("core/files/soporteSql.class.php");
require_once ("core/files/soporteBase.class.php");


class soporte extends soporteBase {

    private static $instancia;

    const ACCEDER = 'registro';
    const BUSCAR = 'busqueda';

    /**
     *
     * @name sesiones
     *       constructor
     */
    public function __construct() {
        
        $this->miSql = new soporteSql (); 
        $this->miConfigurador = \Configurador::singleton ();
        //$this->setPrefijoTablas($this->miConfigurador->getVariableConfiguracion ("prefijo"));
        $this->setPrefijoTablas('general.|concurso.');
        $this->setConexion($this->miConfigurador->fabricaConexiones->getRecursoDB("estructura"));
  
    }

    public static function singleton() {

        if (!isset(self::$instancia)) {
            $className = __CLASS__;
            self::$instancia = new $className ();
        }
        return self::$instancia;
    }

     /**
     *
     * @name sesiones registra en la tabla de log de usuarios
     * @param
     *            string nombre_db
     * @return void
     * @access public
     */
    function procesarArchivo($tipoSoporte) {
        $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
        $rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/";
        //$conexion="estructura";
	//$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        foreach ( $_FILES as $key => $values ) 
            {   if(strlen($_FILES [$key]['name'])>0)
                    {
                        $parametro['tipo_soporte']=$key;
                        $cadenaSql = $this->miSql->getCadenaSql("buscarTipoSoporte", $parametro );
                        $resultadoSoporte = $this->miConexion->ejecutarAcceso($cadenaSql, self::BUSCAR);
                        if($resultadoSoporte)
                            {  //$nombreArchivo= $_REQUEST['consecutivo']. "_" . $_FILES [$key]['name'];
                                $extension = end(explode(".", $_FILES [$key]['name']));
                                $nombreArchivo= $_REQUEST['consecutivo']. "_" .$_REQUEST['consecutivo_dato']. "_" .time(). "." .$extension ;                              
                                $destino[$key] = $rutaBloque .$resultadoSoporte[0]['ubicacion']."/".$nombreArchivo;
                                $arregloSoporte = array('id_usuario'=>$_REQUEST['id_usuario'],
                                                        'tipo_soporte'=>$resultadoSoporte[0]['tipo_soporte'],
                                                        'consecutivo_persona'=>!isset($_REQUEST['consecutivo'])?0:$_REQUEST['consecutivo'],
                                                        'tipo_dato'=>$tipoSoporte,
                                                        'consecutivo_dato'=>$_REQUEST['consecutivo_dato'],
                                                        'nombre'=>$nombreArchivo,
                                                        'alias'=>$_FILES [$key]['name']
                                                        ); 

                                if (copy ( $_FILES [$key]['tmp_name'], $destino[$key] )) 
                                    {   $cadenaSql = $this->miSql->getCadenaSql("registroSoporte",$arregloSoporte );
                                        $resultado = $this->miConexion->ejecutarAcceso($cadenaSql, self::ACCEDER ,$arregloSoporte,'registroSoporte');
                                        
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
