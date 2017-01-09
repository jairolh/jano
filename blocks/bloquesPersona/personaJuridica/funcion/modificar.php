<?php

namespace bloquesPersona\personaJuridica\funcion;


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
       
        if(isset($_REQUEST['personaNaturalTipoPago'])){
			switch($_REQUEST ['personaNaturalTipoPago']){
				case 1 :
					$_REQUEST ['personaNaturalTipoPago']='Transferencia';
					break;
				case 2 :
					$_REQUEST ['personaNaturalTipoPago']='SAP';
					break;
			}
		}
		 
		if(isset($_REQUEST['personaNaturalEconomicoEstado'])){
			switch($_REQUEST ['personaNaturalEconomicoEstado']){
				case 1 :
					$_REQUEST ['personaNaturalEconomicoEstado']='Activo';
					break;
				case 2 :
					$_REQUEST ['personaNaturalEconomicoEstado']='Inactivo';
					break;
			}
		}
		
		$datosCom = array(
				'numeroCuenta' => $_REQUEST['personaNaturalNumeroCuenta'],
				'tipoPago' => $_REQUEST['personaNaturalTipoPago'],
				'estado' => $_REQUEST['personaNaturalEconomicoEstado'],
				'soporteRUT' => $_REQUEST['personaNaturalSoporteRUT']
		);
		
		$atributos ['cadena_sql'] = $this->miSql->getCadenaSql("modificarRegistroComercial",$datosCom);
		$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");
		
		$datosCon = array(
				'estado' => $_REQUEST['personaNaturalEconomicoEstado'],
				'consecutivo' => $_REQUEST['personaNaturalContactosConsecutivo']
		);
		
		$atributos ['cadena_sql'] = $this->miSql->getCadenaSql("modificarRegistroContacto",$datosCon);
		$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");
		    
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

