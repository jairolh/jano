<?php

namespace bloquesParametro\cajaDeCompensacion\funcion;
    
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
         if(isset ( $_REQUEST ['regresar'] ) && $_REQUEST ['regresar'] == "true"){
                    
                     Redireccionador::redireccionar('form'); 
                     exit;
                }
        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
       
      if(isset($_REQUEST ['fdpCiudad'])){
            $datosubicacion = array(
            'fdpDepartamento' => $_REQUEST ['fdpDepartamento'],
            'fdpCiudad' => $_REQUEST ['fdpCiudad']
           );
        }
        else{
            $datosubicacion = array(
            'fdpDepartamento' => $_REQUEST ['fdpDepartamento'],
            'fdpCiudad' => $_REQUEST ['ciudad']
           );
        }
       
     
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarIdUbicacion",$datosubicacion);
   
              
        $ubicacion=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
    
          if(!empty($ubicacion)){
              $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarUbicacion",$datosubicacion);
              $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "insertar");
           
              $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarIdUbicacion",$datosubicacion);
              
              $ubicacion=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
              
              
              
              $datos = array(
            'nit' => $_REQUEST ['nit'],
            'nombre' => $_REQUEST ['nombre'],
            'direccion' => $_REQUEST ['direccion'],
            'telefono' => $_REQUEST ['telefono'],
            'extencionTelefono' => $_REQUEST ['extencionTelefono'],
            'fax' => $_REQUEST ['fax'],
            'extencionFax' => $_REQUEST ['extencionFax'],
            'lugar' => $ubicacion[0][0],
            'nombreRepresentante' => $_REQUEST ['nombreRepresentante'],
            'email' => $_REQUEST ['email']
            
        );
                       
       }else 
       {
        $datos = array(
            'nit' => $_REQUEST ['nit'],
            'nombre' => $_REQUEST ['nombre'],
            'direccion' => $_REQUEST ['direccion'],
            'telefono' => $_REQUEST ['telefono'],
            'extencionTelefono' => $_REQUEST ['extencionTelefono'],
            'fax' => $_REQUEST ['fax'],
            'extencionFax' => $_REQUEST ['extencionFax'],
           'lugar' => "",
            'nombreRepresentante' => $_REQUEST ['nombreRepresentante'],
            'email' => $_REQUEST ['email']
            
        );
//       
       }
                
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("modificarRegistro",$datos);
       
                
    
        
        
        
        $resultado=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");
        //Al final se ejecuta la redirección la cual pasará el control a otra página
    
      
   if (!empty($resultado)) {
            Redireccionador::redireccionar('modifico');
            exit();
        } else {
           Redireccionador::redireccionar('nomodifico');
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