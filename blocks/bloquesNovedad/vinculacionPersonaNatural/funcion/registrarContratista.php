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
       
        
                   
    if($_REQUEST['tipoContrato']==1){
        $_REQUEST['tipoContrato']='CPS';
    }
    
    else {
        $_REQUEST['tipoContrato']='OPS';
    } 
        
        $datos = array(
            'id'=> $_REQUEST ['variable'],
            'tipoContrato' => $_REQUEST ['tipoContrato'],
            'numeroContrato' => $_REQUEST ['numeroContrato'],
            'numeroRegistro' => $_REQUEST ['numeroRegistro'],
            'numeroDisponibilidad' => $_REQUEST ['numeroDisponibilidad'],
            'unidadEjecutora' => $_REQUEST ['unidadEjecutora'],
            'rubro' => $_REQUEST ['rubro'],
            'vigencia' => $_REQUEST ['vigencia'],
            'fechaDisponibilidad' => $_REQUEST ['fecha'],
            'valorContrato' => $_REQUEST ['valorContrato'],
            'mesesDuracion' => $_REQUEST ['duracionContrato'],
            'valorMensual' => $_REQUEST ['valorContrato']/$_REQUEST ['duracionContrato'],
        );
                    
      
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarVinculacionContratista",$datos);
        
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
