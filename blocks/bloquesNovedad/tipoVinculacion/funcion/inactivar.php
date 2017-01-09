<?php
namespace bloquesParametro\tipoVinculacion\funcion;
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
       
       
        if($_REQUEST['enviarInactivar'] =='true'){
            if($_REQUEST ['estado']=='Inactivo'){
                      $opcion='Activo';
        }
        else{
            $opcion='Inactivo';
        }
        
            
            $datos = array(
            'codigoRegistro' => $_REQUEST ['id'],
            'estadoRegistro' => $opcion       
        );
//       
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("inactivarRegistro",$datos);
       
        
        $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");
                
        
                  Redireccionador::redireccionar('form');      
        
       }
                
      if(isset ( $_REQUEST ['cancelarInactivar'] ) && $_REQUEST ['cancelarInactivar'] == "true"){
                    
                     Redireccionador::redireccionar('form'); 
                }
        
       
        //Al final se ejecuta la redirección la cual pasará el control a otra página
        
        
    	        
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

