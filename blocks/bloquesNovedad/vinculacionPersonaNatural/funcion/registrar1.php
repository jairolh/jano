<?php

namespace bloquesNovedad\vinculacionPersonaNatural\funcion;
    
include_once('Redireccionador.php');
class FormProcessor {
    
    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;
    var $conexion;
    
    function __construct($lenguaje, $sql) {
        
        $this->miConfigurador = \Configurador::singleton ();
        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
    
    }
    
    function procesarFormulario() {    
        //Aquí va la lógica de procesamiento
        
        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
       
       
                   
                    $datos = array(
            
            'id' => $_REQUEST ['variable'],
            
           
            
        );
                    
                
                    
               
             
               
        
        
//       
        
             
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarIdFuncionario",$datos);
      
        $resultado=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda",$datos,"buscarIdFuncionario");

        $datos = array(
            'codigo_cargo' => $_REQUEST ['cargo'],
          'id_funcionario' =>  $resultado[0][0],
            
           
            
        );
      
         $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarCargo",$datos);
    
        $resultado1=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");

       
       
      
        if (!empty($resultado1)) {
           
            Redireccionador::redireccionar('inserto');
            exit();
        } else {
          
           Redireccionador::redireccionar('noInserto');
            exit();
        }
    	        
    }
    
    function resetForm(){
        foreach($_REQUEST as $clave=>$valor){
             
            if($clave !='pagina' && $clave!='development' && $clave !='jquery' &&$clave !='tiempo'){
                unset($_REQUEST[$clave]);
            }
        }
    }
    
}
$miProcesador = new FormProcessor ( $this->lenguaje, $this->sql );
$resultado= $miProcesador->procesarFormulario ();
