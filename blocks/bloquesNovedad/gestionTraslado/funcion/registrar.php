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
      
        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ($conexion );
        
        
          $datosubicacion = array(
            'fdpDepartamento' => $_REQUEST ['fdpDepartamento'],
            'fdpCiudad' => $_REQUEST ['fdpCiudad']
     );
                   
     
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarIdUbicacion",$datosubicacion);
   
              
        $ubicacion=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
    
          if(empty($ubicacion)){
              $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarUbicacion",$datosubicacion);
              $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "insertar");
           
              $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarIdUbicacion",$datosubicacion);
             
              $ubicacion=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
          }   
          
       $datos = array(
            'nitRegistro' => $_REQUEST ['nit'],
            'nombreRegistro' => $_REQUEST ['nombre'],
            'direccionRegistro' => $_REQUEST ['direccion'],
            'telefonoRegistro' => $_REQUEST ['telefono'],
            'extTelefonoRegistro' => $_REQUEST ['extencionTelefono'],
            'faxRegistro' => $_REQUEST ['fax'],
            'extFaxRegistro' => $_REQUEST ['extencionFax'],
            'id_ubicacion' => $ubicacion[0][0],
            'nomRepreRegistro' => $_REQUEST ['nombreRepresentante'],
            'emailRegistro' => $_REQUEST ['email']
        );
       
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("registrarCajaDeCompensacion",$datos);
      
   
    $resultado=  $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");
        
 
   if (!empty($resultado)) {
            Redireccionador::redireccionar('inserto');
            exit();
        } else {
           Redireccionador::redireccionar('noInserto');
            exit();
        }
        
        //Al final se ejecuta la redirección la cual pasará el control a otra página
        
       // Redireccionador::redireccionar('opcion1');
      
    	        
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

