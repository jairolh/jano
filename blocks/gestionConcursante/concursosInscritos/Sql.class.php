<?php

namespace gestionConcursante\concursosInscritos;

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

			 case "consultaPerfil":
 							$cadenaSql=" SELECT ";
 							$cadenaSql.=" c.nombre AS concurso, ";
 							$cadenaSql.=" p.nombre AS perfil, ";
 							$cadenaSql.=" p.requisitos ";
 							$cadenaSql.=" FROM concurso.concurso AS c, ";
 							$cadenaSql.=" concurso.concurso_perfil AS p ";
 							$cadenaSql.=" WHERE ";
 							$cadenaSql.=" c.consecutivo_concurso=".$variable['consecutivo_concurso'];
 							$cadenaSql.=" AND p.consecutivo_perfil=".$variable['consecutivo_perfil'];
 							$cadenaSql.=" AND c.consecutivo_concurso=p.consecutivo_concurso ";

 					break;

			 case "consultaInscripcion":
			 		$cadenaSql=" SELECT ";
			 		$cadenaSql.=" ci.consecutivo_inscrito, ";
			 		$cadenaSql.=" ci.consecutivo_perfil, ";
			 		$cadenaSql.=" ci.consecutivo_persona, ";

			 		$cadenaSql.=" c.consecutivo_concurso, ";
			 		$cadenaSql.=" cp.nombre AS perfil, ";
			 		$cadenaSql.=" c.nombre AS concurso, ";
			 		$cadenaSql.=" p.tipo_identificacion, ";
			 		$cadenaSql.=" p.identificacion, ";
			 		$cadenaSql.=" p.nombre, ";
			 		$cadenaSql.=" p.apellido, ";
			 		$cadenaSql.=" m.nombre AS modalidad ";

			 		$cadenaSql.=" FROM concurso.concurso_inscrito ci, concurso.concurso_perfil cp, concurso.persona p, concurso.concurso c, concurso.modalidad_concurso m ";
			 		$cadenaSql.=" WHERE ";
			 		$cadenaSql.=" consecutivo_inscrito=".$variable['consecutivo_inscrito'];
			 		$cadenaSql.=" AND ci.consecutivo_perfil=cp.consecutivo_perfil ";

			 		$cadenaSql.=" AND ci.consecutivo_persona=p.consecutivo ";
			 		$cadenaSql.=" AND c.consecutivo_concurso=cp.consecutivo_concurso ";
			 		$cadenaSql.=" AND m.consecutivo_modalidad=c.consecutivo_modalidad ";
			 break;

			 case "consultaEvaluacionesReclamacion":
				 $cadenaSql=" SELECT count(*) ";
				 $cadenaSql.=" FROM concurso.valida_requisito ";
				 $cadenaSql.=" WHERE consecutivo_inscrito=".$variable['consecutivo_inscrito'];
				 $cadenaSql.=" AND id_reclamacion=".$variable['reclamacion'];
				 break;

			 case "respuestaReclamacion":
  			 $cadenaSql=" SELECT";
  			 $cadenaSql.=" respuesta.id, respuesta.id_reclamacion, respuesta.respuesta, respuesta.observacion, respuesta.fecha_registro, respuesta.estado, ";
				 $cadenaSql.=" respuesta.id_evaluar_respuesta, respuesta.id_evaluador, concat(us.nombre, ' ', us.apellido) AS evaluador";
				 $cadenaSql.=" FROM concurso.respuesta_reclamacion respuesta, concurso.evaluacion_reclamacion reclamacion, jano_usuario us";
  			 $cadenaSql.=" WHERE";
 			 	 $cadenaSql.=" reclamacion.id=respuesta.id_reclamacion";
  			 $cadenaSql.=" AND reclamacion.id=".$variable['reclamacion'];
				 $cadenaSql.="AND concat(us.tipo_identificacion, '', us.identificacion)=respuesta.id_evaluador";
 			 	 //echo $cadenaSql;
  			break;

			 case "reclamacionesValidacion":
  			 $cadenaSql=" SELECT";
  			 $cadenaSql.=" er.id, er.observacion, er.fecha_registro, er.estado, er.consecutivo_calendario ";
  			 $cadenaSql.=" FROM concurso.valida_requisito vr, concurso.evaluacion_reclamacion er";
  			 $cadenaSql.=" WHERE";
 			 	 $cadenaSql.=" vr.id_reclamacion=".$variable['reclamacion'];
  			 $cadenaSql.=" AND vr.consecutivo_inscrito=".$variable['consecutivo_inscrito'];
 			 	 //echo $cadenaSql;
  			break;

			case "actualizaValidacion" :
				$cadenaSql = "UPDATE ";
				$cadenaSql .= "concurso.valida_requisito ";
				$cadenaSql .= "SET ";
				$cadenaSql .= "id_reclamacion = " . $variable ["reclamacion"]." ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "consecutivo_inscrito =" . $variable ["consecutivo_inscrito"];
				//echo $cadenaSql;
				break;

			case "consultaDatosInscripcion":
 			 $cadenaSql=" SELECT";
 			 $cadenaSql.=" calendario.consecutivo_calendario, c.nombre AS concurso, p.nombre AS perfil ";
 			 $cadenaSql.=" FROM concurso.concurso c, concurso.concurso_perfil p, concurso.concurso_calendario calendario";
 			 $cadenaSql.=" WHERE";
			 $cadenaSql.=" calendario.consecutivo_calendario=".$variable['consecutivo_calendario'];
 			 $cadenaSql.=" AND c.consecutivo_concurso=".$variable['consecutivo_concurso'];
 			 $cadenaSql.=" AND p.consecutivo_perfil=".$variable['consecutivo_perfil'];
 			 $cadenaSql.=" AND calendario.consecutivo_concurso=c.consecutivo_concurso";
			 //echo $cadenaSql;
 			break;

			case "fechaFinReclamacion":
			 $cadenaSql=" SELECT";
			 $cadenaSql.=" calendario.consecutivo_calendario, calendario.fecha_fin_reclamacion, calendario.fecha_fin_resolver, actividad.nombre ";
			 $cadenaSql.=" FROM concurso.concurso_calendario calendario, concurso.actividad_calendario actividad";
			 $cadenaSql.=" WHERE";
			 $cadenaSql.=" calendario.consecutivo_concurso=".$variable['consecutivo_concurso'];
			 $cadenaSql.=" AND calendario.consecutivo_actividad=".$variable['consecutivo_actividad'];
			 $cadenaSql.=" AND actividad.consecutivo_actividad=calendario.consecutivo_actividad";
			// echo $cadenaSql;
			break;

			 case "registroReclamacion":
						$cadenaSql =" INSERT INTO concurso.evaluacion_reclamacion (";
						$cadenaSql .=" consecutivo_calendario,";
						$cadenaSql .=" observacion,";
						$cadenaSql .=" fecha_registro,";
						$cadenaSql .=" id_inscrito";
						$cadenaSql .=" )";
						$cadenaSql .= " VALUES ( ";
						$cadenaSql .= " ".$variable['consecutivo_calendario'].", ";
						$cadenaSql .= " '".$variable['observaciones']."', ";
						$cadenaSql .= " '".$variable['fecha']."', ";
						$cadenaSql .= " ".$variable['id_inscrito']." ";
						$cadenaSql .= " )";
						$cadenaSql.=" RETURNING id";
 			break;


			case "consultarEvaluacionFinal":
			 $cadenaSql=" SELECT";
			 $cadenaSql.=" criterio.nombre,";
			 $cadenaSql.=" ef.puntaje_final,";
			 $cadenaSql.=" ef.observacion,";
			 $cadenaSql.=" ef.aprobo";
			 $cadenaSql.=" FROM concurso.evaluacion_final ef, concurso.concurso_evaluar ce, concurso.criterio_evaluacion criterio";
			 $cadenaSql.=" WHERE";
			 $cadenaSql.=" id_inscrito=".$variable;
			 $cadenaSql.=" AND ce.consecutivo_evaluar=ef.id_evaluar";
			 $cadenaSql.=" AND ce.consecutivo_criterio=criterio.consecutivo_criterio";
			break;

			 case "consultarEvaluacion":
	 			$cadenaSql=" SELECT DISTINCT";
	 			$cadenaSql.=" ep.id,";
	 			$cadenaSql.=" ep.id_grupo,";
	 			$cadenaSql.=" ep.id_inscrito,";
	 			$cadenaSql.=" ep.id_evaluar,";
	 			$cadenaSql.=" ep.puntaje_parcial,";
	 			$cadenaSql.=" ep.observacion,";
	 			$cadenaSql.=" ep.fecha_registro,";
	 			$cadenaSql.=" ep.estado, ";
	 			//$cadenaSql.=" ep.id_evaluacion_final, ";
	 			//$cadenaSql.=" ep.id_reclamacion, ";
	 			$cadenaSql.=" ce.consecutivo_criterio,";
	 			$cadenaSql.=" ceval.consecutivo_criterio AS id_criterio,";
	 			$cadenaSql.=" ceval.nombre AS criterio,";
				$cadenaSql.=" eg.id_evaluador,";
				$cadenaSql.=" concat(us.nombre, ' ', us.apellido) AS evaluador";
	 			$cadenaSql.=" FROM concurso.evaluacion_parcial ep, concurso.concurso_evaluar ce, concurso.criterio_evaluacion ceval, concurso.evaluacion_grupo eg, jano_usuario us ";
	 			$cadenaSql.=" WHERE";
	 			$cadenaSql.=" ep.id_inscrito=".$variable;
	 			$cadenaSql.=" AND ep.id_evaluar = ce.consecutivo_evaluar";
	 			$cadenaSql.=" AND ce.consecutivo_criterio=ceval.consecutivo_criterio";
	 			$cadenaSql.=" AND ep.id_grupo=eg.id";
				$cadenaSql.=" AND concat(us.tipo_identificacion, '', us.identificacion)=eg.id_evaluador";
	 			//echo $cadenaSql;
	 		break;

			 case "consultarValidacion":
	 				$cadenaSql=" SELECT ";
	 				$cadenaSql.=" consecutivo_valida, ";
	 				$cadenaSql.=" consecutivo_inscrito, ";
	 				$cadenaSql.=" cumple_requisito, ";
	 				$cadenaSql.=" observacion, ";
	 				$cadenaSql.=" fecha_registro, ";
	 				$cadenaSql.=" estado, ";
					$cadenaSql.=" id_reclamacion ";
	 				$cadenaSql.=" FROM concurso.valida_requisito ";
	 				$cadenaSql.=" WHERE ";
	 				$cadenaSql.=" consecutivo_inscrito=".$variable;

	 		break;

			 case "consultaConsecutivo":
 			 		$cadenaSql = "Select consecutivo ";
 					$cadenaSql .= "from concurso.persona ";
 			 		$cadenaSql .= "WHERE tipo_identificacion='".$variable['tipo_identificacion']."' ";
 					$cadenaSql .= "AND identificacion='".$variable['identificacion']."'";
 			 		break;

			case "consultaConcursosInscritos":
           $cadenaSql = "Select c.consecutivo_concurso, c.nombre AS concurso, cp.consecutivo_perfil, cp.nombre AS perfil, ci.consecutivo_inscrito, ci.estado AS estado ";
					 $cadenaSql .= "from concurso.concurso_inscrito ci, concurso.concurso_perfil cp, concurso.concurso c ";
           $cadenaSql .= "WHERE ci.consecutivo_persona= ".$variable;
					 $cadenaSql .= " AND ci.consecutivo_perfil=cp.consecutivo_perfil ";
					 $cadenaSql .= "AND cp.consecutivo_concurso=c.consecutivo_concurso ";
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
