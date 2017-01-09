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
       
         if(isset ( $_REQUEST ['regresar'] ) && $_REQUEST ['regresar'] == "true"){
                    
                     Redireccionador::redireccionar('form'); 
                     exit;
                }
                
               
             
            
        if($_REQUEST ['tipoVinculacion']){
        $datos = array(
            'tipoVinculacion' => $_REQUEST ['tipoVinculacion'],
            'fechaInicio' => $_REQUEST ['fechaInicio'],
            'fechaFin' => $_REQUEST ['fechaFin'],
            'id'=> $_REQUEST ['id']
            
        );
//       
        }else {
            
            $datos = array(
            'tipoVinculacion' => '',
            'fechaInicio' => $_REQUEST ['fechaInicio'],
            'fechaFin' => $_REQUEST ['fechaFin'],
             'id'=> $_REQUEST ['id']
            
        );
        }
                
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("modificarRegistro",$datos);
        
        
        
        $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");
        //Al final se ejecuta la redirección la cual pasará el control a otra página
       
        Redireccionador::redireccionar('form');
    	        
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