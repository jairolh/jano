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
                        case 'buscarSoporte' :
				$cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" sop.consecutivo_soporte,";
                                $cadenaSql.=" sop.consecutivo_persona,";
                                $cadenaSql.=" sop.tipo_dato, ";
                                $cadenaSql.=" sop.consecutivo_dato,";
                                $cadenaSql.=" sop.nombre archivo,";
                                $cadenaSql.=" sop.alias,";
                                $cadenaSql.=" tsop.tipo_soporte,";
                                $cadenaSql.=" tsop.nombre, ";
                                $cadenaSql.=" tsop.ubicacion";
                                $cadenaSql.=" FROM concurso.soporte sop";
                                $cadenaSql.=" INNER JOIN general.tipo_soporte tsop";
                                $cadenaSql.=" ON tsop.tipo_soporte=sop.tipo_soporte";
                                $cadenaSql.=" AND tsop.estado=sop.estado";
                                $cadenaSql.=" WHERE";
                                $cadenaSql.=" tsop.estado='A' ";
                                $cadenaSql.=" AND sop.tipo_dato='".$variable['tipo_dato']."'";
                                $cadenaSql.=" AND sop.consecutivo_persona='".$variable['consecutivo']."'";
                                $cadenaSql.=" AND tsop.nombre='".$variable['nombre_soporte']."'";
                                if(isset($variable['consecutivo_dato']) && $variable['consecutivo_dato']!='')
                                    {$cadenaSql.=" AND sop.consecutivo_dato='".$variable['consecutivo_dato']."' ";}
                                $cadenaSql.=" ORDER BY sop.consecutivo_soporte DESC ";
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
                        case "consultaFactor":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" consecutivo_factor, ";
                                $cadenaSql.=" nombre, ";
                                $cadenaSql.=" estado ";
                                $cadenaSql.=" FROM  ";
                                $cadenaSql.=" concurso.factor_evaluacion ";
                                $cadenaSql.=" WHERE estado='A'";
                                if(isset($variable['consecutivo_factor']) &&  $variable['consecutivo_factor']!='' )
                                   { $cadenaSql .= " AND  consecutivo_factor='".$variable['consecutivo_factor']."' "; 
                                   }
                            break;     
                        case "consultaCriterio":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" consecutivo_criterio codigo, ";
                                $cadenaSql.=" nombre, ";
                                $cadenaSql.=" consecutivo_factor, ";
                                $cadenaSql.=" estado ";
                                $cadenaSql.=" FROM concurso.criterio_evaluacion ";
                                $cadenaSql.=" WHERE estado='A' ";
                                if(isset($variable['consecutivo_criterio']) &&  $variable['consecutivo_criterio']!='' )
                                   { $cadenaSql .= " AND consecutivo_criterio='".$variable['consecutivo_criterio']."' "; }
                                if(isset($variable['consecutivo_factor']) &&  $variable['consecutivo_factor']!='' )
                                   { $cadenaSql .= " AND consecutivo_factor='".$variable['consecutivo_factor']."' "; }   
                            break;                            
                        case "consultaConcurso":
                                $cadenaSql=" SELECT conc.consecutivo_concurso, ";
                                $cadenaSql.=" conc.consecutivo_modalidad,";
                                $cadenaSql.=" conc.nombre, ";
                                $cadenaSql.=" conc.acuerdo, ";
                                $cadenaSql.=" conc.descripcion,";
                                $cadenaSql.=" conc.fecha_inicio,";
                                $cadenaSql.=" conc.fecha_fin, ";
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
                        case "consultarCriterioConcurso":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" crt_ev.consecutivo_evaluar, ";
                                $cadenaSql.=" crt_ev.consecutivo_concurso, ";
                                $cadenaSql.=" crt.consecutivo_factor, ";
                                $cadenaSql.=" fac.nombre factor, ";
                                $cadenaSql.=" crt_ev.consecutivo_criterio, ";
                                $cadenaSql.=" crt.nombre criterio, ";
                                $cadenaSql.=" crt_ev.maximo_puntos, ";
                                $cadenaSql.=" crt_ev.estado, ";
                                $cadenaSql.=" est.estado nom_estado ";
                                $cadenaSql.=" FROM concurso.concurso_evaluar crt_ev ";
                                $cadenaSql.=" INNER JOIN concurso.criterio_evaluacion crt ON crt_ev.consecutivo_criterio=crt.consecutivo_criterio ";
                                $cadenaSql.=" INNER JOIN concurso.factor_evaluacion fac ON fac.consecutivo_factor=crt.consecutivo_factor ";
                                $cadenaSql.=" INNER JOIN general.estado est ON est.tipo=crt_ev.estado ";
                                $cadenaSql.=" WHERE "; 
                                $cadenaSql .= " crt_ev.consecutivo_concurso='".$variable['consecutivo_concurso']."' "; 
                                if(isset($variable['consecutivo_evaluar']) &&  $variable['consecutivo_evaluar']!='' )
                                   {$cadenaSql.=" AND crt_ev.consecutivo_evaluar='".$variable['consecutivo_evaluar']."' "; 
                                   }
                                $cadenaSql.=" ORDER BY fac.nombre, crt.nombre ";
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
                        case "registroCriterioConcurso":
                                $cadenaSql=" INSERT INTO ";
                                $cadenaSql.=" concurso.concurso_evaluar(";
                                $cadenaSql.=" consecutivo_evaluar,";
                                $cadenaSql.=" consecutivo_concurso,";
                                $cadenaSql.=" consecutivo_criterio,";
                                $cadenaSql.=" maximo_puntos,";
                                $cadenaSql.="  estado)";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " DEFAULT, ";
                                $cadenaSql .= " '".$variable['consecutivo_concurso']."', ";
                                $cadenaSql .= " '".$variable['consecutivo_criterio']."', ";
                                $cadenaSql .= " '".$variable['maximo_puntos']."', ";
                                $cadenaSql .= " 'A' ";
                                $cadenaSql .= " )";
                                $cadenaSql.=" RETURNING consecutivo_evaluar";
                        break;                    
                        case "actualizaConcurso":
                                $cadenaSql=" UPDATE concurso.concurso";
                                $cadenaSql.=" SET ";
                                $cadenaSql.= " consecutivo_modalidad= '".$variable['codigo_modalidad']."', ";
                                $cadenaSql.= " nombre= '".$variable['nombre']."', ";
                                $cadenaSql.= " acuerdo= '".$variable['acuerdo']."', ";
                                $cadenaSql.= " descripcion= '".$variable['descripcion']."', ";
                                $cadenaSql.= " fecha_inicio= '".$variable['fecha_inicio_concurso']."', ";
                                $cadenaSql.= " fecha_fin= '".$variable['fecha_fin_concurso']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_concurso= '".$variable['consecutivo_concurso']."' ";
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
                        case "actualizaCriterioConcurso":
                                $cadenaSql=" UPDATE ";
                                $cadenaSql.=" concurso.concurso_evaluar ";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" consecutivo_criterio='".$variable['consecutivo_criterio']."', ";
                                $cadenaSql.=" maximo_puntos='".$variable['maximo_puntos']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_evaluar= '".$variable['consecutivo_evaluar']."' ";
                                $cadenaSql.=" RETURNING consecutivo_evaluar";
                        break;                    
                    
                        case "actualizaEstadoCriterio":
                                $cadenaSql=" UPDATE concurso.concurso_evaluar";
                                $cadenaSql.=" SET ";
                                $cadenaSql.= " estado= '".$variable['estado']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_evaluar= '".$variable['consecutivo_evaluar']."' ";
                                $cadenaSql.=" RETURNING consecutivo_evaluar";
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
