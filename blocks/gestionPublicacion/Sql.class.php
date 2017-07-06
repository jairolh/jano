<?php

namespace gestionPublicacion;

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
		 * 1. Revisar las variables para evitar SQL Injection
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
                                        
                        case "consultarInscritoConcurso":
                                $cadenaSql="SELECT DISTINCT ";
                                $cadenaSql.="insc.consecutivo_inscrito, ";
                                $cadenaSql.="insc.fecha_registro, ";
                                $cadenaSql.="prf.nombre perfil, ";
                                $cadenaSql.="prf.dependencia, ";
                                $cadenaSql.="prf.area, ";
                                $cadenaSql.=" conc.consecutivo_modalidad,";
                                $cadenaSql.=" conc.nombre, ";
                                $cadenaSql.=" conc.acuerdo, ";
                                $cadenaSql.=" conc.descripcion,";
                                $cadenaSql.=" mdl.nombre modalidad, ";
                                $cadenaSql.=" nvl.nombre nivel_concurso ";
                                
                                $cadenaSql.="FROM concurso.concurso_perfil prf ";
                                $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc ON prf.consecutivo_perfil=insc.consecutivo_perfil ";
                                $cadenaSql.=" INNER JOIN concurso.concurso conc ON conc.consecutivo_concurso=prf.consecutivo_concurso";
                                $cadenaSql.=" INNER JOIN concurso.modalidad_concurso mdl ON mdl.consecutivo_modalidad=conc.consecutivo_modalidad";
                                $cadenaSql.=" INNER JOIN general.nivel nvl ON nvl.tipo_nivel='TipoConcurso' AND nvl.codigo_nivel= mdl.codigo_nivel_concurso";
                                $cadenaSql.=" WHERE "; 
                                $cadenaSql .= " insc.consecutivo_inscrito='".$variable['consecutivo_inscrito']."' "; 
                                $cadenaSql.=" ORDER BY prf.dependencia, prf.area,prf.nombre ";
                                
                            break;                     
                    
                        case "consultaSoportesInscripcion":
                                $cadenaSql="SELECT DISTINCT ";
                                $cadenaSql.="ins.consecutivo_soporte_ins, ";
                                $cadenaSql.="ins.consecutivo_inscrito, ";
                                $cadenaSql.="ins.tipo_dato, ";
                                $cadenaSql.="ins.consecutivo_dato, ";
                                $cadenaSql.="ins.fuente_dato, ";
                                $cadenaSql.="ins.valor_dato, ";
                                $cadenaSql.="ins.consecutivo_soporte, ";
                                $cadenaSql.="ins.nombre_soporte, ";
                                $cadenaSql.="ins.alias_soporte, ";
                                $cadenaSql.="ins.fecha_registro, ";
                                $cadenaSql.="ins.estado, ";
                                $cadenaSql.=" tsop.tipo_soporte,";
                                $cadenaSql.=" tsop.nombre nombre_tipo ";
                                $cadenaSql.=" FROM concurso.soporte_inscrito ins ";
                                $cadenaSql.=" LEFT OUTER JOIN concurso.soporte sop ON sop.consecutivo_soporte=ins.consecutivo_soporte ";
                                $cadenaSql.=" LEFT OUTER JOIN general.tipo_soporte tsop ON tsop.tipo_soporte=sop.tipo_soporte ";
                                $cadenaSql.=" WHERE ins.consecutivo_inscrito='".$variable['consecutivo_inscrito']."' "; 
                                $cadenaSql.="AND ins.estado='A' ";
                                if(isset($variable['tipo_dato']) &&  $variable['tipo_dato']!='' )
                                   {$cadenaSql .= " AND ins.tipo_dato='".$variable['tipo_dato']."' ";} 
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
