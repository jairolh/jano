<?php

class soporteSql {
    
    private $prefijoTablas;
    var $cadenaSql;
    
    const VARIABLE='variable';
    
    function __construct() {
    
    }
    
    function setPrefijoTablas($valor) {
        
        $this->prefijoTablas = $valor;        
        return true;
    }
    
    function getCadenaSql($indice, $parametro = "") {
        
        $this->clausula($indice,$parametro);
        if (isset($this->cadena_sql[$indice])) {
            return $this->cadena_sql[$indice];
        }
        return false;
    }
    
    private function clausula($indice, $parametro) {
        
        $prefijo=explode('|', $this->prefijoTablas);
        switch ($indice) {

            
                case 'buscarTipoSoporte' :
                    $this->cadena_sql [$indice]=" SELECT DISTINCT";
                    $this->cadena_sql [$indice].=" tipo_soporte,";
                    $this->cadena_sql [$indice].=" nombre,";
                    $this->cadena_sql [$indice].=" ubicacion,";
                    $this->cadena_sql [$indice].=" descripcion,";
                    $this->cadena_sql [$indice].=" extencion_permitida,";
                    $this->cadena_sql [$indice].=" tamanno_permitido,";
                    $this->cadena_sql [$indice].=" dato_relaciona,";
                    $this->cadena_sql [$indice].=" alias,";
                    $this->cadena_sql [$indice].=" estado ";
                    $this->cadena_sql [$indice].=" FROM  ";
                    $this->cadena_sql [$indice].=  $prefijo[0]."tipo_soporte";
                    $this->cadena_sql [$indice].=" WHERE ";
                    $this->cadena_sql [$indice].=" nombre = '".$parametro['tipo_soporte']."'";
                    $this->cadena_sql [$indice].=" AND estado='A' ";
                    break;            
                
            
        	case 'registroSoporte' :
                    $this->cadena_sql [$indice]=  "INSERT INTO ";
                    $this->cadena_sql [$indice].=  $prefijo[1]."soporte(";
                    $this->cadena_sql [$indice].=  " consecutivo_soporte,";
                    $this->cadena_sql [$indice].=  " tipo_soporte, ";
                    $this->cadena_sql [$indice].=  " consecutivo_persona, ";
                    $this->cadena_sql [$indice].=  " tipo_dato, ";
                    $this->cadena_sql [$indice].=  " consecutivo_dato, ";
                    $this->cadena_sql [$indice].=  " nombre, ";
                    $this->cadena_sql [$indice].=  " alias, ";
                    $this->cadena_sql [$indice].=  " estado)";
                    $this->cadena_sql [$indice].=  " VALUES (";
                    $this->cadena_sql [$indice].=  " DEFAULT,";
                    $this->cadena_sql [$indice].=  " '".$parametro['tipo_soporte']."',";
                    $this->cadena_sql [$indice].=  " '".$parametro['consecutivo_persona']."',";
                    $this->cadena_sql [$indice].=  " '".$parametro['tipo_dato']."',";
                    $this->cadena_sql [$indice].=  " '".$parametro['consecutivo_dato']."',";
                    $this->cadena_sql [$indice].=  " '".$parametro['nombre']."',";
                    $this->cadena_sql [$indice].=  " '".$parametro['alias']."',";
                    $this->cadena_sql [$indice].=  " 'A' )";
                    $this->cadena_sql [$indice].=  " RETURNING consecutivo_soporte ";
                break;             
            
            default :
        }
    
    }

}
?>