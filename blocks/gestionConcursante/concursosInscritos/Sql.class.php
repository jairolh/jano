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
	 				$cadenaSql.=" estado ";
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
