<?php
namespace bloquesNovedad\gestionNovedad\funcion;

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
        
        
        //Al final se ejecuta la redirección la cual pasará el control a otra página
       
            
                if($_REQUEST['tipoNovedad'] == 1){
                    
                 Redireccionador::redireccionar('periodica'); 
                
                }
                else{
                 Redireccionador::redireccionar('esporadica'); 
                 
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