<?php

class loggerSql {
    
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
        
        switch ($indice) {
            
            case "registroLogUsuario" :

                $this->cadena_sql [$indice]= " INSERT INTO  ";
                $this->cadena_sql [$indice].=  $this->prefijoTablas."log_usuario  ";
                $this->cadena_sql [$indice].= "(  ";
                $this->cadena_sql [$indice].= "id_usuario,  ";
                $this->cadena_sql [$indice].= "accion,  ";
                $this->cadena_sql [$indice].= "id_registro,  ";
                $this->cadena_sql [$indice].= "tipo_registro,  ";
                $this->cadena_sql [$indice].= "nombre_registro,  ";
                $this->cadena_sql [$indice].= "fecha_log,  ";
                $this->cadena_sql [$indice].= "descripcion , ";
                $this->cadena_sql [$indice].= "host  ";
                $this->cadena_sql [$indice].= ")  ";
                $this->cadena_sql [$indice].= "VALUES  ";
                $this->cadena_sql [$indice].= "(  ";
                $this->cadena_sql [$indice].= "'".$parametro['id_usuario']."',  ";
                $this->cadena_sql [$indice].= "'".$parametro['accion']."',  ";
                $this->cadena_sql [$indice].= "'".$parametro['id_registro']."',  ";
                $this->cadena_sql [$indice].= "'".$parametro['tipo_registro']."',  ";
                $this->cadena_sql [$indice].= "'".$parametro['nombre_registro']."',  ";
                $this->cadena_sql [$indice].= "'".$parametro['fecha_log']."',  ";
                $this->cadena_sql [$indice].= "'".$parametro['descripcion']."',  ";
                $this->cadena_sql [$indice].= "'".$parametro['host']."'  ";
                $this->cadena_sql [$indice].= ")"; 
                 
                break;             
            
            
            default :
        }
    
    }

}
?>