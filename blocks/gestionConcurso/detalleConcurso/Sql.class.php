<?php

namespace gestionConcurso\detalleConcurso;

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
                        case "idioma":
				$cadenaSql = "SET lc_time_names = 'es_ES' ";
			break;
                        case "consultarNivel":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" codigo_nivel,";
                                $cadenaSql.=" nombre,";
                                $cadenaSql.=" tipo_nivel,";
                                $cadenaSql.=" descripcion,";
                                $cadenaSql.=" estado";
                                $cadenaSql.=" FROM general.nivel";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" estado='A'";
                                if(isset($variable['tipo_nivel']) && $variable['tipo_nivel']!='')
                                    {$cadenaSql.=" AND tipo_nivel='".$variable['tipo_nivel']."' ";}
                                if(isset($variable['codigo_nivel']) && $variable['codigo_nivel']>0)
                                    {$cadenaSql.=" AND codigo_nivel='".$variable['codigo_nivel']."' ";}
                                if(isset($variable['nombre']) && $variable['nombre']!='')
                                    {$cadenaSql.=" AND lower(nombre) LIKE lower('".$variable['nombre']."') ";} 
                                if(isset($variable['add_otro']) && $variable['add_otro']=='SI')
                                    {   $cadenaSql.=" UNION ";
                                        $cadenaSql.=" SELECT DISTINCT ";
                                        $cadenaSql.=" 0 codigo_nivel, ";
                                        $cadenaSql.=" 'OTRO' nombre, ";
                                        $cadenaSql.=" 'OTRO' tipo_nivel, ";
                                        $cadenaSql.=" 'OTRO' descripcion, ";
                                        $cadenaSql.=" 'A' estado";
                                        $cadenaSql.=" FROM general.nivel";
                                    }
                                if(isset($variable['order']) && $variable['order']=='codigo')
                                     {$cadenaSql.=" ORDER BY codigo_nivel ";  }    
                                else {$cadenaSql.=" ORDER BY nombre ";  }
                                
                            break; 
                    
                    
                        case "consultaModalidad":
                            
                                $cadenaSql=" SELECT DISTINCT  ";
                                $cadenaSql.=" consecutivo_modalidad codigo, ";
                                $cadenaSql.=" nombre, ";
                                $cadenaSql.=" estado";
                                $cadenaSql.=" FROM concurso.modalidad_concurso";
                                $cadenaSql.=" WHERE estado='A'";
                                if(isset($variable['tipo_concurso']) &&  $variable['tipo_concurso']!='' )
                                   { $cadenaSql .= " AND  codigo_nivel_concurso='".$variable['tipo_concurso']."' "; 
                                   }
                                   
                            break;
                            
                        case "consultaConcurso":
 
                                $cadenaSql=" SELECT conc.consecutivo_concurso, ";
                                $cadenaSql.=" conc.consecutivo_modalidad,";
                                $cadenaSql.=" conc.nombre, ";
                                $cadenaSql.=" conc.acuerdo, ";
                                $cadenaSql.=" conc.descripcion,";
                                $cadenaSql.=" conc.fecha_inicio,";
                                $cadenaSql.=" conc.fecha_fin, ";
                                //$cadenaSql.=" conc.estado, ";
                                $cadenaSql.=" (CASE WHEN conc.estado='A' THEN 'Activo' ELSE 'Inactivo' END) estado, ";
                                $cadenaSql.=" mdl.nombre modalidad, ";
                                $cadenaSql.=" mdl.codigo_nivel_concurso,";
                                $cadenaSql.=" nvl.nombre nivel_concurso,";
                                $cadenaSql.=" ( SELECT COUNT(prf.consecutivo_perfil) perfil ";
                                $cadenaSql.=" FROM concurso.concurso_perfil prf";
                                $cadenaSql.=" WHERE prf.estado='A' ";
                                $cadenaSql.=" AND prf.consecutivo_concurso=conc.consecutivo_concurso) perfiles";
                                $cadenaSql.=" FROM concurso.concurso conc ";
                                $cadenaSql.=" INNER JOIN concurso.modalidad_concurso mdl ON mdl.consecutivo_modalidad=conc.consecutivo_modalidad";
                                $cadenaSql.=" INNER JOIN general.nivel nvl ON nvl.tipo_nivel='TipoConcurso' AND nvl.codigo_nivel= mdl.codigo_nivel_concurso";
                                if(isset($variable['consecutivo_concurso']) &&  $variable['consecutivo_concurso']!='' )
                                   {$cadenaSql.=" WHERE "; 
                                    $cadenaSql .= " conc.consecutivo_concurso='".$variable['consecutivo_concurso']."' "; 
                                   }
                                $cadenaSql.=" ORDER BY ";
                                $cadenaSql.=" conc.fecha_inicio DESC, ";
                                $cadenaSql.=" conc.fecha_fin DESC ";   

                            break;                            
                            
                        
                        case "registroConcurso":
                            
                                $cadenaSql=" INSERT INTO concurso.concurso(";
                                $cadenaSql.=" consecutivo_concurso,";
                                $cadenaSql.=" consecutivo_modalidad, ";
                                $cadenaSql.=" nombre,";
                                $cadenaSql.=" acuerdo, ";
                                $cadenaSql.=" descripcion,";
                                $cadenaSql.=" fecha_inicio,";
                                $cadenaSql.=" fecha_fin, ";
                                $cadenaSql.=" estado)";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " DEFAULT, ";
                                $cadenaSql .= " '".$variable['codigo_modalidad']."', ";
                                $cadenaSql .= " '".$variable['nombre']."', ";
                                $cadenaSql .= " '".$variable['acuerdo']."', ";
                                $cadenaSql .= " '".$variable['descripcion']."', ";
                                $cadenaSql .= " '".$variable['fecha_inicio_concurso']."', ";
                                $cadenaSql .= " '".$variable['fecha_fin_concurso']."', ";
                                $cadenaSql .= " 'A' ";
                                $cadenaSql .= " )";
                                $cadenaSql.=" RETURNING consecutivo_concurso";
                        break;  
                    
                        case "actualizaConcurso":
                                $cadenaSql=" UPDATE concurso.concurso";
                                $cadenaSql.=" SET ";
                                $cadenaSql.= " consecutivo_modalidad= '".$variable['codigo_modalidad']."', ";
                                $cadenaSql.= " nombre= '".$variable['nombre']."', ";
                                $cadenaSql.= " acuerdo= '".$variable['acuerdo']."', ";
                                $cadenaSql.= " descripcion= '".$variable['descripcion']."', ";
                                $cadenaSql.= " fecha_inicio= '".$variable['fecha_inicio']."', ";
                                $cadenaSql.= " fecha_fin= '".$variable['fecha_fin']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_concurso= '".$variable['consecutivo_concurso']."', ";
                                $cadenaSql.=" RETURNING consecutivo_concurso";
                        break;   
                    
                        case "actualizaEstadoConcurso":
                                $cadenaSql=" UPDATE concurso.concurso";
                                $cadenaSql.=" SET ";
                                $cadenaSql.= " estado= '".$variable['estado']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_concurso= '".$variable['consecutivo_concurso']."' ";
                                $cadenaSql.=" RETURNING consecutivo_concurso";
                        break;                       
                            
                    
                        case "CambiarEstadoRol":
                            
				$cadenaSql = "UPDATE ".$prefijo."rol_subsistema SET ";
                                $cadenaSql .= " estado = '".$variable['estado']."'";
                                $cadenaSql .= " WHERE id_subsistema = '".$variable['id_subsistema']."' ";
                                $cadenaSql .= " AND rol_id = '".$variable['rol_id']."' ";
			break; 
                    
                      
                        case "borrarRol":
				$cadenaSql = "DELETE FROM ".$prefijo."rol_subsistema ";
                                $cadenaSql .= " WHERE id_subsistema = '".$variable['id_subsistema']."' ";
                                $cadenaSql .= " AND rol_id = '".$variable['rol_id']."' ";
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
