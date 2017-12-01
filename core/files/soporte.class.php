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
        $consultaTipo=$this->miConfigurador->getVariableConfiguracion ("esquemaTipoSoporte");
        $registroSoporte=$this->miConfigurador->getVariableConfiguracion ("esquemaSoporte");
        $this->setPrefijoTablas($consultaTipo.'.|'.$registroSoporte.'.');
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
     * @name sesiones registra en la tabla de soportes
     * @param
     *            string nombre_db
     * @return void
     * @access public
     */
    function procesarArchivo($datosSoporte) {
        $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
        $rutaArchivo = $this->miConfigurador->getVariableConfiguracion ( "raizSoportes" );
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
                                //$extension = end(explode(".", $_FILES [$key]['name']));
                                $carpeta[$key] = $rutaArchivo .$resultadoSoporte[0]['ubicacion'];
                                if (!file_exists($carpeta[$key])) 
                                    {mkdir($carpeta[$key], 0777, true);}
                                $extfile = explode(".", $_FILES [$key]['name']);
                                $extension = end($extfile);
                                $pattern = '1234567890';
                                $nombreArchivo= str_pad($datosSoporte['consecutivo_persona'],10,0, STR_PAD_LEFT).str_pad($datosSoporte['consecutivo_dato'],10,($pattern{mt_rand(0,(strlen($pattern)-1))}),STR_PAD_LEFT).time(). "." .$extension ;                              
                                $destino[$key] = $carpeta[$key]."/".$nombreArchivo;
                                $arregloSoporte = array('id_usuario'=>$datosSoporte['id_usuario'],
                                                        'tipo_soporte'=>$resultadoSoporte[0]['tipo_soporte'],
                                                        'consecutivo_persona'=>!isset($datosSoporte['consecutivo_persona'])?0:$datosSoporte['consecutivo_persona'],
                                                        'tipo_dato'=>$resultadoSoporte[0]['dato_relaciona'],
                                                        'consecutivo_dato'=>$datosSoporte['consecutivo_dato'],
                                                        'nombre'=>$nombreArchivo,
                                                        'alias'=>$_FILES [$key]['name']
                                                        ); 
                                if (copy ( $_FILES [$key]['tmp_name'], $destino[$key] )) 
                                    {   $cadenaSql = $this->miSql->getCadenaSql("registroSoporte",$arregloSoporte );
                                        $resultado = $this->miConexion->ejecutarAcceso($cadenaSql, self::ACCEDER ,$arregloSoporte,'registroSoporte');
                                        $status = "Archivo subido: <b>" . $nombreArchivo . "</b>";
                                    }
                                else{ $status = "Error al subir el archivo 1";
                                    }
                            } 
                        else{$status = "Error al subir el archivo 1";
                            }    
                    }
            }
    }    
}
?>
