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
        
        
        //Al final se ejecuta la redirección la cual pasará el control a otra página
        
         $i=0;
            while($i<$_REQUEST['tamaño']){
                if(isset($_REQUEST['botonVincular'.$i])&& $_REQUEST['botonVincular'.$i] == 'true'){
                 
                 Redireccionador::redireccionar('vincular',$i); 
                // var_dump($_REQUEST['botonModificar'.$i]);
                  break; 
                   
                }
               
                if(isset($_REQUEST['botonVerDetalle'.$i])&&$_REQUEST['botonVerDetalle'.$i] == 'true'){
                 Redireccionador::redireccionar('verdetalle',$i); 
                // var_dump($_REQUEST['botonModificar'.$i]);
                  break; 
                   
                }
                  if(isset($_REQUEST['botonModificar'.$i])&&$_REQUEST['botonModificar'.$i] == 'true'){
                 Redireccionador::redireccionar('modificar',$i); 
                // var_dump($_REQUEST['botonModificar'.$i]);
                  break; 
                   
                }
                
                $i+=1;
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