<?php

namespace gestionConcurso\caracterizaConcurso;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

// Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
// en camel case precedida por la palabra sql
class Sql extends \Sql {
	var $miConfigurador;
	function __construct() {
		$this->miConfigurador = \Configurador::singleton ();
	}
	function getCadenaSql($tipo, $variable = "") {
		
		/**
		 * 1.
		 * Revisar las variables para evitar SQL Injection
		 */
		$prefijo = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
		$idSesion = $this->miConfigurador->getVariableConfiguracion ( "id_sesion" );
		
		switch ($tipo) {
			
			/**
			 * Clausulas específicas
			 */
                        
			case "consultaFactores":
                $cadenaSql = "Select f.consecutivo_factor, f.nombre AS factor, f.estado AS estado_factor, c.consecutivo_criterio, c.nombre AS criterio, c.estado AS estado_criterio";
                $cadenaSql .= " FROM concurso.factor_evaluacion f, concurso.criterio_evaluacion c";
                $cadenaSql .= " WHERE f.consecutivo_factor=c.consecutivo_factor order by f.nombre";
                break;
            
            case "consultaActividades":
                $cadenaSql = "Select * from concurso.actividad_calendario";
               	break;
                
            case "consultaModalidades":
                $cadenaSql = "SELECT codigo_nivel_concurso, modalidad.nombre AS nombre, modalidad.estado AS estado, nivel.nombre AS nivel";
                $cadenaSql .= " FROM concurso.modalidad_concurso modalidad, general.nivel nivel";
                $cadenaSql .= " WHERE codigo_nivel_concurso=codigo_nivel";
                break;
           
            case "buscarNiveles":
              	$cadenaSql = " SELECT * FROM general.nivel ";
               	$cadenaSql .= " WHERE tipo_nivel ='TipoConcurso'";
               	break;
                            
            case "buscarFactores":
            	$cadenaSql = " SELECT * FROM concurso.factor_evaluacion ";
            	$cadenaSql .= " WHERE estado ='A'";
            	$cadenaSql .= " order by nombre";
            	break;
                            
            case "consultarCriterios":
				$cadenaSql = " SELECT * FROM concurso.criterio_evaluacion"; 
				$cadenaSql .= " WHERE consecutivo_factor=".$variable;
                break;
            
         	case "registrarFactor":
         		$cadenaSql = "INSERT INTO concurso.factor_evaluacion(nombre)";
         		$cadenaSql .= " VALUES ( ";
         		$cadenaSql .= " '".$variable['nombre']."' ";
         		$cadenaSql .= " ) ";
         		$cadenaSql .= " RETURNING consecutivo_factor";
         		break;
         	
         	case "registrarCriterio":
         		$cadenaSql = "INSERT INTO concurso.criterio_evaluacion(consecutivo_factor, nombre)";
         		$cadenaSql .= " VALUES ( ";
         		$cadenaSql .= " '".$variable['factor']."', ";
         		$cadenaSql .= " '".$variable['nombre']."' ";
         		$cadenaSql .= " ) ";
         		$cadenaSql .= " RETURNING consecutivo_criterio";
         		break;
                        		
        	case "registrarModalidad":
        		$cadenaSql = "INSERT INTO concurso.modalidad_concurso(codigo_nivel_concurso, nombre)";
        		$cadenaSql .= " VALUES ( ";
        		$cadenaSql .= " ".$variable['nivel'].", ";
        		$cadenaSql .= " '".$variable['nombre']."' ";
        		$cadenaSql .= " ) ";
        		$cadenaSql .= " RETURNING consecutivo_modalidad";
        		break;
        	
        	case "registrarActividad":
        		$cadenaSql = "INSERT INTO concurso.actividad_calendario(nombre, descripcion)";
        		$cadenaSql .= " VALUES ( ";
        		$cadenaSql .= " '".$variable['nombre']."', ";
        		$cadenaSql .= " '".$variable['descripcion']."' ";
        		$cadenaSql .= " ) ";
        		$cadenaSql .= " RETURNING consecutivo_actividad";
        		break;
                    
            case "cambiarEstadoCriterio":
                $cadenaSql = "UPDATE concurso.criterio_evaluacion SET ";
               	$cadenaSql .= " estado = '".$variable['estado']."'";
                $cadenaSql .= " WHERE consecutivo_criterio = '".$variable['id_criterio']."' ";
                $cadenaSql .= " RETURNING consecutivo_criterio";
				break; 
				
			case "cambiarEstadoModalidad":
				$cadenaSql = "UPDATE concurso.modalidad_concurso SET ";
				$cadenaSql .= " estado = '".$variable['estado']."'";
				$cadenaSql .= " WHERE consecutivo_modalidad = '".$variable['id_modalidad']."' ";
				$cadenaSql .= " RETURNING consecutivo_modalidad";
				break;
				
			case "cambiarEstadoActividad":
				$cadenaSql = "UPDATE concurso.actividad_calendario SET ";
				$cadenaSql .= " estado = '".$variable['estado']."'";
				$cadenaSql .= " WHERE consecutivo_actividad = '".$variable['id_actividad']."' ";
				break;
                    
          	case "editarFactor":
                $cadenaSql = "UPDATE concurso.factor_evaluacion ";
                $cadenaSql .= " SET ";
				$cadenaSql .= "nombre='".$variable['nombreFactor']."'";
               	$cadenaSql .= " WHERE ";
               	$cadenaSql .= " consecutivo_factor = '".$variable['id_factor']."' ";
               	break;  
           	
           	case "editarModalidad":
           		$cadenaSql = "UPDATE concurso.modalidad_concurso ";
           		$cadenaSql .= " SET ";
           		$cadenaSql .= "nombre='".$variable['nombreModalidad']."',";
           		$cadenaSql .= "codigo_nivel_concurso=".$variable['nivel']."";
           		$cadenaSql .= " WHERE ";
           		$cadenaSql .= " consecutivo_modalidad = '".$variable['id_modalidad']."' ";
           		$cadenaSql .= " RETURNING consecutivo_modalidad";
           		break;
          
           	case "editarActividad":
           		$cadenaSql = "UPDATE concurso.actividad_calendario ";
           		$cadenaSql .= " SET ";
           		$cadenaSql .= "nombre='".$variable['nombreActividad']."',";
           		$cadenaSql .= "descripcion='".$variable['descripcionActividad']."'";
           		$cadenaSql .= " WHERE ";
           		$cadenaSql .= " consecutivo_actividad = '".$variable['id_actividad']."' ";
           		$cadenaSql .= " RETURNING consecutivo_actividad";
           		break;
           		
           	case "actividadEnConsurso":
           		$cadenaSql = "Select * from concurso.concurso_calendario";
           		$cadenaSql .= " WHERE ";
           		$cadenaSql .= " consecutivo_actividad = ".$variable['id_actividad'];
           		break;
           		
           	case "modalidadEnConsurso":
           		$cadenaSql = "Select * from concurso.concurso";
           		$cadenaSql .= " WHERE ";
           		$cadenaSql .= " consecutivo_modalidad = ".$variable['id_modalidad'];
           		break;
                    
			/**
			 * Clausulas genéricas. se espera que estén en todos los formularios
			 * que utilicen esta plantilla
			 */

			case "iniciarTransaccion":
				$cadenaSql="START TRANSACTION";
				break;

			case "finalizarTransaccion":
				$cadenaSql="COMMIT";
				break;

			case "cancelarTransaccion":
				$cadenaSql="ROLLBACK";
				break;

		}
		return $cadenaSql;
	}
}

?>
