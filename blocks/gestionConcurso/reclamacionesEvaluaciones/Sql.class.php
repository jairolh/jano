<?php

namespace gestionConcurso\reclamacionesEvaluaciones;

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
		$cadenaSql = '';
		switch ($tipo) {

			/**
			 * Clausulas específicas
			 */
			case "idioma" :
				$cadenaSql = "SET lc_time_names = 'es_ES' ";
				break;

				case "consultaEvaluacionParcialReclamacion" :
					$cadenaSql=" SELECT reclamacion.id id_reclamacion, reclamacion.observacion, reclamacion.fecha_registro, reclamacion.consecutivo_calendario, reclamacion.id_inscrito,";
					$cadenaSql.=" evaluacion.id_evaluar, evaluacion.puntaje_parcial, evaluacion.observacion observacion_evaluacion, evaluacion.id evaluacion_id, ";
					$cadenaSql.=" evaluacion.fecha_registro evaluacion_fecha, criterio.nombre nombre_criterio";
					$cadenaSql.=" FROM concurso.evaluacion_reclamacion reclamacion, concurso.evaluacion_parcial evaluacion, concurso.concurso_evaluar ce, concurso.criterio_evaluacion criterio";
					$cadenaSql.=" WHERE";
					$cadenaSql.=" reclamacion.id=evaluacion.id_reclamacion";
					$cadenaSql.=" and evaluacion.id_evaluar=ce.consecutivo_evaluar";
					$cadenaSql.=" and ce.consecutivo_criterio=criterio.consecutivo_criterio";
					$cadenaSql.=" AND reclamacion.id=".$variable ['reclamacion'];
					$cadenaSql.=" AND evaluacion.estado='A'";
					break;

				case "consultaEvaluacionReclamacion" :
					$cadenaSql="SELECT id, id_grupo, id_inscrito, id_evaluar, puntaje_parcial, observacion, ";
					$cadenaSql.=" fecha_registro, estado, id_evaluacion_final, id_reclamacion, ";
					$cadenaSql.=" id_evaluacion_final";
					$cadenaSql.=" FROM concurso.evaluacion_parcial";
					$cadenaSql.=" WHERE id_reclamacion=".$variable ['reclamacion'];
					$cadenaSql.=" AND estado='A'";
					$cadenaSql.=" AND id_grupo=".$variable ['grupo'];
					//echo $cadenaSql;
					break;

                case "consultaPuntajeInactivo" :
					$cadenaSql="SELECT id, id_grupo, id_inscrito, id_evaluar, puntaje_parcial, observacion, ";
					$cadenaSql.=" fecha_registro, estado, id_evaluacion_final, id_reclamacion, ";
					$cadenaSql.=" id_evaluacion_final";
					$cadenaSql.=" FROM concurso.evaluacion_parcial";
					$cadenaSql.=" WHERE id_reclamacion=".$variable ['reclamacion'];
					$cadenaSql.=" AND estado='I'";
					$cadenaSql.=" AND id_evaluar=".$variable ['criterio'];
					//echo $cadenaSql;
					break;


				case "consultarDetalleReclamacion2" :
					$cadenaSql = " SELECT reclamacion.id id_reclamacion, reclamacion.observacion, reclamacion.fecha_registro, reclamacion.consecutivo_calendario, reclamacion.id_inscrito,";
					$cadenaSql .= " evaluacion.id_evaluar, evaluacion.puntaje_parcial, evaluacion.observacion observacion_evaluacion, evaluacion.id evaluacion_id, grupo.id id_grupo,";
					$cadenaSql .= " evaluacion.fecha_registro evaluacion_fecha, criterio.nombre nombre_criterio, grupo.id_evaluador, concat(us.nombre, ' ', us.apellido) AS evaluador";
					$cadenaSql .= " FROM concurso.evaluacion_reclamacion reclamacion, concurso.evaluacion_parcial evaluacion, concurso.concurso_evaluar ce, concurso.criterio_evaluacion criterio, concurso.evaluacion_grupo grupo, jano_usuario us";
					$cadenaSql .= " WHERE";
					$cadenaSql .= " reclamacion.id=evaluacion.id_reclamacion";
					$cadenaSql .= " and evaluacion.id_evaluar=ce.consecutivo_evaluar";
					$cadenaSql .= " and ce.consecutivo_criterio=criterio.consecutivo_criterio";
					$cadenaSql .= " AND reclamacion.id=" . $variable ['reclamacion'];
					$cadenaSql .= " AND grupo.id=evaluacion.id_grupo";
					$cadenaSql .= " AND concat(us.tipo_identificacion, '', us.identificacion)=grupo.id_evaluador";
					$cadenaSql .= " AND grupo.id_evaluador='" . $variable ['usuario'] . "'";
					//$cadenaSql .= " AND evaluacion.estado='A'";
					//echo $cadenaSql;
					break;

			case "consultarDetalleReclamacion" :
				$cadenaSql = " SELECT reclamacion.id id_reclamacion, reclamacion.observacion observacion_reclamacion, reclamacion.fecha_registro, reclamacion.consecutivo_calendario,";
				$cadenaSql .= " evaluacion.id_inscrito, evaluacion.id_evaluar, evaluacion.puntaje_parcial, evaluacion.observacion, ";
				$cadenaSql .= " evaluacion.fecha_registro evaluacion_fecha, criterio.nombre nombre_criterio, grupo.id_evaluador, concat(us.nombre, ' ', us.apellido) AS evaluador";
				$cadenaSql .= " FROM concurso.evaluacion_reclamacion reclamacion, concurso.evaluacion_parcial evaluacion, concurso.concurso_evaluar ce, concurso.criterio_evaluacion criterio, concurso.evaluacion_grupo grupo, jano_usuario us";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " reclamacion.id=evaluacion.id_reclamacion";
				$cadenaSql .= " and evaluacion.id_evaluar=ce.consecutivo_evaluar";
				$cadenaSql .= " and ce.consecutivo_criterio=criterio.consecutivo_criterio";
				$cadenaSql .= " AND reclamacion.id=" . $variable ['reclamacion'];
				$cadenaSql .= " AND grupo.id=evaluacion.id_grupo";
				$cadenaSql .= " AND concat(us.tipo_identificacion, '', us.identificacion)=grupo.id_evaluador";
				$cadenaSql .= " AND grupo.id_evaluador='" . $variable ['usuario'] . "'";
                $cadenaSql .= " AND evaluacion.estado='A'";
				//echo $cadenaSql;
				break;

			case "fechaFinResolver" :
				$cadenaSql = " SELECT";
				$cadenaSql .= " calendario.consecutivo_calendario, calendario.fecha_fin_reclamacion, calendario.fecha_fin_resolver, actividad.nombre ";
				$cadenaSql .= " FROM concurso.concurso_calendario calendario, concurso.actividad_calendario actividad";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " calendario.consecutivo_concurso=" . $variable ['consecutivo_concurso'];
				$cadenaSql .= " AND calendario.consecutivo_actividad=" . $variable ['consecutivo_actividad'];
				$cadenaSql .= " AND actividad.consecutivo_actividad=calendario.consecutivo_actividad";
				// echo $cadenaSql;
				break;

			case "respuestaReclamacion" :
				$cadenaSql = " SELECT";
				$cadenaSql .= " respuesta.id, respuesta.id_reclamacion, respuesta.respuesta, respuesta.observacion, respuesta.fecha_registro, respuesta.estado, ";
				$cadenaSql .= " respuesta.id_evaluar_respuesta, respuesta.id_evaluador, concat(us.nombre, ' ', us.apellido) AS evaluador";
				$cadenaSql .= " FROM concurso.respuesta_reclamacion respuesta, concurso.evaluacion_reclamacion reclamacion, jano_usuario us";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " reclamacion.id=respuesta.id_reclamacion";
				$cadenaSql .= " AND reclamacion.id=" . $variable ['reclamacion'];
				$cadenaSql .= "AND concat(us.tipo_identificacion, '', us.identificacion)=respuesta.id_evaluador";
				// echo $cadenaSql;
				break;

			case "inactivarValidacion" :
				$cadenaSql = "UPDATE ";
				$cadenaSql .= "concurso.evaluacion_parcial ";
				$cadenaSql .= "SET ";
				$cadenaSql .= "estado = 'I' ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "id =" . $variable ['evaluacion'] . " ";
				$cadenaSql .= " RETURNING id";
				//echo $cadenaSql;
				break;

			case "consultaConcurso" :
				$cadenaSql = " SELECT conc.consecutivo_concurso, ";
				$cadenaSql .= " conc.consecutivo_modalidad,";
				$cadenaSql .= " conc.nombre, ";
				$cadenaSql .= " conc.acuerdo, ";
				$cadenaSql .= " conc.descripcion,";
				$cadenaSql .= " conc.fecha_inicio,";
				$cadenaSql .= " conc.fecha_fin, ";
				$cadenaSql .= " (CASE WHEN conc.estado='A' THEN 'Activo' ELSE 'Inactivo' END) estado, ";
				$cadenaSql .= " mdl.nombre modalidad, ";
				$cadenaSql .= " mdl.codigo_nivel_concurso,";
				$cadenaSql .= " nvl.nombre nivel_concurso,";

				$cadenaSql .= " ( SELECT count(*)";
				$cadenaSql .= " FROM concurso.evaluacion_reclamacion reclamacion,";
				$cadenaSql .= " concurso.concurso_inscrito ci,";
				$cadenaSql .= " concurso.concurso_perfil cp,";
				$cadenaSql .= " concurso.concurso_calendario calendar,";
				$cadenaSql .= " concurso.concurso c,";
				$cadenaSql .= " concurso.actividad_calendario actividad";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " calendar.consecutivo_actividad=9 "; // Actividad de pruebas de factor Competencias profesionales y comunicativas
				$cadenaSql .= " AND reclamacion.id_inscrito=ci.consecutivo_inscrito";
				$cadenaSql .= " AND ci.consecutivo_perfil=cp.consecutivo_perfil";
				$cadenaSql .= " AND conc.consecutivo_concurso=cp.consecutivo_concurso";
				$cadenaSql .= " AND calendar.consecutivo_concurso=c.consecutivo_concurso";
				$cadenaSql .= " AND c.consecutivo_concurso=cp.consecutivo_concurso";
				$cadenaSql .= " AND reclamacion.consecutivo_calendario=calendar.consecutivo_calendario";
				$cadenaSql .= " and actividad.nombre='Evaluar Requisitos'";
				$cadenaSql .= " ) reclamaciones";

				$cadenaSql .= " FROM concurso.concurso conc,";
				$cadenaSql .= " concurso.modalidad_concurso mdl,";
				$cadenaSql .= " general.nivel nvl";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " conc.estado='A' ";
				$cadenaSql .= " AND nvl.tipo_nivel='TipoConcurso'";
				$cadenaSql .= " AND mdl.consecutivo_modalidad=conc.consecutivo_modalidad";
				$cadenaSql .= " AND nvl.codigo_nivel= mdl.codigo_nivel_concurso";

				if (isset ( $variable ['hoy'] ) && $variable ['hoy'] != '') {
					$cadenaSql .= " AND conc.fecha_inicio <='" . $variable ['hoy'] . "' ";
					$cadenaSql .= " AND conc.fecha_fin>= '" . $variable ['hoy'] . "' ";
				}
				$cadenaSql .= " ORDER BY ";
				$cadenaSql .= " conc.fecha_inicio DESC, ";
				$cadenaSql .= " conc.fecha_fin DESC ";
				// echo $cadenaSql;
				break;

            case "consultaRespuestaReclamacion" :
				$cadenaSql = " SELECT * ";
				$cadenaSql .= " FROM concurso.respuesta_reclamacion respuesta";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_reclamacion=" . $variable ['reclamacion'];
				$cadenaSql .= " AND id_evaluador='".$variable ['usuario']."'";
				//echo $cadenaSql;
				break;

			case "consultaEvaluacionesReclamacionInactivas" :
				$cadenaSql = " SELECT count(*) ";
				$cadenaSql .= " FROM concurso.evaluacion_parcial evaluacion, concurso.evaluacion_grupo grupo";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_reclamacion=" . $variable ['reclamacion'];
				$cadenaSql .= " AND evaluacion.estado='I'";
				$cadenaSql .= " AND evaluacion.id_grupo=grupo.id";
				$cadenaSql .= " AND id_evaluador='".$variable ['usuario']."'";
				//echo $cadenaSql;
				break;

				case "consultaEvaluacionesReclamacionActivas" :
					$cadenaSql = " SELECT count(*) ";
					$cadenaSql .= " FROM concurso.evaluacion_parcial evaluacion, concurso.evaluacion_grupo grupo";
					$cadenaSql .= " WHERE";
					$cadenaSql .= " id_reclamacion=" . $variable ['reclamacion'];
					$cadenaSql .= " AND evaluacion.estado='A'";
					$cadenaSql .= " AND evaluacion.id_grupo=grupo.id";
					$cadenaSql .= " AND id_evaluador='".$variable ['usuario']."'";
					//echo $cadenaSql;
					break;

            case "consultaEvaluacionesReclamacion" :
				$cadenaSql = " SELECT id, id_reclamacion, respuesta, observacion, fecha_registro, estado,
                id_evaluar_respuesta, id_evaluador";
				$cadenaSql .= " FROM concurso.respuesta_reclamacion ";
				$cadenaSql .= " WHERE id_reclamacion=" . $variable ['reclamacion'];
				break;

			case "consultaEvaluacionesReclamacion2" :
				$cadenaSql = " SELECT consecutivo_valida, consecutivo_inscrito, cumple_requisito, observacion, fecha_registro, estado, id_reclamacion";
				$cadenaSql .= " FROM concurso.valida_requisito ";
				$cadenaSql .= " WHERE consecutivo_inscrito=" . $variable ['consecutivo_inscrito'];
				$cadenaSql .= " AND id_reclamacion=" . $variable ['reclamacion'];
				$cadenaSql .= " ORDER BY consecutivo_valida ASC ";
				break;

            case "registroNuevaEvaluacion" :
				$cadenaSql =" INSERT INTO concurso.evaluacion_parcial (";
                $cadenaSql .=" id_grupo,";
                $cadenaSql .=" id_inscrito, ";
                $cadenaSql .=" id_evaluar, ";
                $cadenaSql .=" puntaje_parcial,";
                $cadenaSql .=" observacion, ";
                $cadenaSql .=" fecha_registro, ";
								$cadenaSql .=" id_reclamacion ";
                $cadenaSql .=" )";
                $cadenaSql .= " VALUES ( ";
                $cadenaSql .= " ".$variable['grupo'].", ";
                $cadenaSql .= " ".$variable['inscrito'].", ";
                $cadenaSql .= " ".$variable['id_evaluar'].", ";
                $cadenaSql .= " ".$variable['puntaje'].", ";
                $cadenaSql .= " '".$variable['observacion']."', ";
                $cadenaSql .= " '".$variable['fecha']."', ";
								$cadenaSql .= " ".$variable['reclamacion']." ";
                $cadenaSql .= " )";
                $cadenaSql.=" RETURNING id";
                //echo $cadenaSql;
                break;

			case "registroValidacion" :
				$cadenaSql = " INSERT INTO concurso.valida_requisito (";
				$cadenaSql .= " consecutivo_inscrito,";
				$cadenaSql .= " cumple_requisito, ";
				$cadenaSql .= " observacion,";
				$cadenaSql .= " fecha_registro, ";
				$cadenaSql .= " estado, ";
				$cadenaSql .= " id_reclamacion ";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " '" . $variable ['consecutivo_inscrito'] . "', ";
				$cadenaSql .= " '" . $variable ['validacion'] . "', ";
				$cadenaSql .= " '" . $variable ['observaciones'] . "', ";
				$cadenaSql .= " '" . $variable ['fecha'] . "', ";
				$cadenaSql .= " 'A', ";
				$cadenaSql .= " '" . $variable ['reclamacion'] . "' ";
				$cadenaSql .= " )";
				$cadenaSql .= " RETURNING consecutivo_valida";
				break;

			case "registroEvaluacionReclamacion" :
				$cadenaSql = " INSERT INTO concurso.respuesta_reclamacion (";
				$cadenaSql .= " id_reclamacion,";
				$cadenaSql .= " respuesta, ";
				$cadenaSql .= " observacion, ";
				$cadenaSql .= " fecha_registro, ";
				$cadenaSql .= " id_evaluar_respuesta, ";
				$cadenaSql .= " id_evaluador ";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " " . $variable ['reclamacion'] . ", ";
				$cadenaSql .= " '" . $variable ['respuesta'] . "', ";
				$cadenaSql .= " '" . $variable ['observacion'] . "', ";
				$cadenaSql .= " '" . $variable ['fecha'] . "', ";
				$cadenaSql .= " " . $variable ['evaluar_respuesta'] . ", ";
				$cadenaSql .= " '" . $variable ['evaluador'] . "' ";
				$cadenaSql .= " )";
				$cadenaSql .= " RETURNING id";
				break;

			case "consultarValidacion2" :
				$cadenaSql = " SELECT consecutivo_valida, consecutivo_inscrito, cumple_requisito, observacion, fecha_registro, estado, id_reclamacion ";
				$cadenaSql .= " FROM concurso.valida_requisito ";
				$cadenaSql .= " WHERE consecutivo_inscrito=" . $variable ['consecutivo_inscrito'];
				break;

			case "consultaReclamaciones" :
				$cadenaSql = " SELECT er.id, er.observacion, er.fecha_registro, er.estado, er.consecutivo_calendario, a.nombre,";
				$cadenaSql .= " (SELECT COUNT(*) ";
				$cadenaSql .= " FROM concurso.respuesta_reclamacion ";
				$cadenaSql .= " WHERE id_reclamacion=er.id";
				$cadenaSql .= " AND estado='A') respuestas";
				$cadenaSql .= " FROM concurso.evaluacion_reclamacion er, concurso.concurso_inscrito ci, ";
				$cadenaSql .= " concurso.concurso_perfil cp, concurso.concurso c, concurso.concurso_calendario calendario,";
				$cadenaSql .= " concurso.actividad_calendario a";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " er.id_inscrito=ci.consecutivo_inscrito";
				$cadenaSql .= " AND cp.consecutivo_perfil=c.consecutivo_concurso";
				$cadenaSql .= " AND cp.consecutivo_concurso=ci.consecutivo_perfil";
				$cadenaSql .= " AND calendario.consecutivo_calendario=er.consecutivo_calendario";
				$cadenaSql .= " AND calendario.consecutivo_actividad=a.consecutivo_actividad";
				$cadenaSql .= " AND cp.consecutivo_concurso=" . $variable ['consecutivo_concurso'];
				break;

			case "consultarReclamaciones" :
				$cadenaSql = " SELECT er.id, er.observacion, er.fecha_registro, er.estado, er.consecutivo_calendario, a.nombre,c.consecutivo_concurso AS id_concurso, c.nombre AS concurso, cp.consecutivo_perfil, cp.nombre AS perfil,";
				$cadenaSql .= " cp.requisitos, er.id_inscrito, concat(p.nombre, ' ', p.apellido) AS nombre_inscrito, concat(p.tipo_identificacion, '', p.identificacion) AS identificacion";
				$cadenaSql .= " FROM concurso.evaluacion_reclamacion er, concurso.concurso_inscrito ci, ";
				$cadenaSql .= " concurso.concurso_perfil cp, concurso.concurso c, concurso.concurso_calendario calendario,";
				$cadenaSql .= " concurso.actividad_calendario a, concurso.persona p";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " er.id_inscrito=ci.consecutivo_inscrito";
				$cadenaSql .= " AND cp.consecutivo_perfil=c.consecutivo_concurso";
				$cadenaSql .= " AND cp.consecutivo_concurso=ci.consecutivo_perfil";
				$cadenaSql .= " AND calendario.consecutivo_calendario=er.consecutivo_calendario";
				$cadenaSql .= " AND calendario.consecutivo_actividad=a.consecutivo_actividad";
				$cadenaSql .= " AND cp.consecutivo_concurso=" . $variable ['consecutivo_concurso'];
				$cadenaSql .= " AND ci.consecutivo_persona=p.consecutivo";
				$cadenaSql .= " and a.nombre='Pruebas de Competencias'";
				break;

			case "consultaRespuestaReclamaciones" :
				$cadenaSql = "SELECT er.id, er.observacion, er.fecha_registro, er.estado, er.consecutivo_calendario, respuesta.id AS id_respuesta, respuesta.respuesta, ";
				$cadenaSql .= "respuesta.id_evaluar_respuesta, ep.id_evaluar ";
				$cadenaSql .= "FROM concurso.evaluacion_reclamacion er, concurso.respuesta_reclamacion respuesta, concurso.evaluacion_parcial ep ";
				$cadenaSql .= "WHERE respuesta.id_reclamacion=er.id ";
				$cadenaSql .= "AND respuesta.id_evaluar_respuesta=ep.id ";
				$cadenaSql .= "AND respuesta.estado='A' ";
				$cadenaSql .= "AND respuesta.id_reclamacion=" . $variable ['reclamacion'];
				//echo $cadenaSql;
				break;

			case "registroEvaluacion" :
				$cadenaSql = " INSERT INTO concurso.evaluacion_parcial (";
				$cadenaSql .= " id_grupo,";
				$cadenaSql .= " id_inscrito, ";
				$cadenaSql .= " id_evaluar, ";
				$cadenaSql .= " puntaje_parcial,";
				$cadenaSql .= " observacion, ";
				$cadenaSql .= " fecha_registro ";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " " . $variable ['grupo'] . ", ";
				$cadenaSql .= " " . $variable ['inscrito'] . ", ";
				$cadenaSql .= " " . $variable ['id_evaluar'] . ", ";
				$cadenaSql .= " " . $variable ['puntaje'] . ", ";
				$cadenaSql .= " '" . $variable ['observacion'] . "', ";
				$cadenaSql .= " '" . $variable ['fecha'] . "' ";
				$cadenaSql .= " )";
				$cadenaSql .= " RETURNING id";

				break;

			case "registrarGrupo" :
				$cadenaSql = " INSERT INTO concurso.evaluacion_grupo (";
				$cadenaSql .= " id_evaluador,";
				$cadenaSql .= " id_perfil, ";
				$cadenaSql .= " fecha_registro ";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " '" . $variable ['jurado'] . "', ";
				$cadenaSql .= " " . $variable ['perfil'] . ", ";
				$cadenaSql .= " '" . $variable ['fecha'] . "' ";
				$cadenaSql .= " )";
				$cadenaSql .= " RETURNING id";
				break;

			case "consultaInscripcion" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " ci.consecutivo_inscrito, ";
				$cadenaSql .= " ci.consecutivo_perfil, ";
				$cadenaSql .= " ci.consecutivo_persona, ";

				$cadenaSql .= " c.consecutivo_concurso, ";
				$cadenaSql .= " cp.nombre AS perfil, ";
				$cadenaSql .= " c.nombre AS concurso, ";
				$cadenaSql .= " p.tipo_identificacion, ";
				$cadenaSql .= " p.identificacion, ";
				$cadenaSql .= " p.nombre, ";
				$cadenaSql .= " p.apellido, ";
				$cadenaSql .= " m.nombre AS modalidad ";

				$cadenaSql .= " FROM concurso.concurso_inscrito ci, concurso.concurso_perfil cp, concurso.persona p, concurso.concurso c, concurso.modalidad_concurso m ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " consecutivo_inscrito=" . $variable ['consecutivo_inscrito'];
				$cadenaSql .= " AND ci.consecutivo_perfil=cp.consecutivo_perfil ";

				$cadenaSql .= " AND ci.consecutivo_persona=p.consecutivo ";
				$cadenaSql .= " AND c.consecutivo_concurso=cp.consecutivo_concurso ";
				$cadenaSql .= " AND m.consecutivo_modalidad=c.consecutivo_modalidad ";
				break;


			case "consultarValidacion" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " consecutivo_valida, ";
				$cadenaSql .= " consecutivo_inscrito, ";
				$cadenaSql .= " cumple_requisito, ";
				$cadenaSql .= " observacion, ";
				$cadenaSql .= " fecha_registro, ";
				$cadenaSql .= " estado ";
				$cadenaSql .= " FROM concurso.valida_requisito ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " consecutivo_inscrito=" . $variable;
				break;

			case "consultarEvaluacion" :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " ep.id, ";
				$cadenaSql .= " ep.id_grupo, ";
				$cadenaSql .= " ep.id_inscrito, ";
				$cadenaSql .= " ep.id_evaluar, ";
				$cadenaSql .= " ep.puntaje_parcial, ";
				$cadenaSql .= " ep.observacion, ";
				$cadenaSql .= " ep.fecha_registro, ";
				$cadenaSql .= " ep.estado, ";
				// $cadenaSql.=" ep.id_evaluacion_final, ";
				// $cadenaSql.=" ep.id_reclamacion, ";
				$cadenaSql .= " ce.consecutivo_criterio, ";
				$cadenaSql .= " ceval.consecutivo_criterio AS id_criterio, ";
				$cadenaSql .= " ceval.nombre AS criterio";
				$cadenaSql .= " FROM concurso.evaluacion_parcial ep, concurso.concurso_evaluar ce, concurso.criterio_evaluacion ceval, concurso.evaluacion_grupo eg ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " ep.id_inscrito=" . $variable ['inscrito'];
				$cadenaSql .= " AND ep.id_evaluar = ce.consecutivo_evaluar ";
				$cadenaSql .= " AND ce.consecutivo_criterio=ceval.consecutivo_criterio ";
				$cadenaSql .= " AND ep.id_grupo=eg.id";
				$cadenaSql .= " AND ep.id_grupo=" . $variable ['grupo'];
				// echo $cadenaSql;
				break;

			case "consultaRolesUsuario" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " perfil.id_usuario usuario,";
				$cadenaSql .= " perfil.id_subsistema cod_app,";
				$cadenaSql .= " perfil.rol_id cod_rol,";
				$cadenaSql .= " rol.rol_alias rol,";
				$cadenaSql .= " perfil.fecha_caduca fecha_caduca,";
				$cadenaSql .= " perfil.estado estado";
				$cadenaSql .= " FROM jano_usuario_subsistema perfil";
				$cadenaSql .= " INNER JOIN jano_rol rol";
				$cadenaSql .= " ON rol.rol_id=perfil.rol_id";
				$cadenaSql .= " AND rol.estado_registro_id=1";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_usuario='" . $variable . "'";
				break;

			case "consultaCriteriosRol" :
				$cadenaSql = " SELECT jc.id, jc.id_jurado_rol, jc.id_criterio, f.nombre, ";
				$cadenaSql .= " ce.consecutivo_criterio, ce.consecutivo_factor, ev.consecutivo_evaluar AS id_evaluar, ce.nombre AS criterio, ev.maximo_puntos";
				$cadenaSql .= " FROM concurso.jurado_criterio jc, concurso.criterio_evaluacion ce, concurso.concurso_evaluar ev, concurso.factor_evaluacion f";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " id_jurado_rol=" . $variable ['rol'];
				$cadenaSql .= " AND jc.id_criterio=ce.consecutivo_criterio ";
				$cadenaSql .= " AND ev.consecutivo_criterio=ce.consecutivo_criterio ";
				$cadenaSql .= " AND ce.consecutivo_factor=f.consecutivo_factor ";
				$cadenaSql .= " AND consecutivo_concurso=" . $variable ['consecutivo_concurso'];
				// $cadenaSql.=" AND f.nombre='".$variable['factor']."'";
				// echo $cadenaSql;
				break;

			case "consultarEvaluacionParcial" :
				$cadenaSql = " SELECT ep.id, ep.id_grupo, ep.id_inscrito, ep.id_evaluar";
				$cadenaSql .= " FROM concurso.evaluacion_parcial ep";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_inscrito=" . $variable ['inscrito'];
				$cadenaSql .= " AND id_grupo='" . $variable ['grupo'] . "'";
				break;

			case "consultarGrupo" :
				$cadenaSql = " SELECT eg.id, eg.id_evaluador, eg.id_perfil";
				$cadenaSql .= " FROM concurso.evaluacion_grupo eg";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_evaluador='" . $variable ['jurado'] . "'";
				$cadenaSql .= " AND id_perfil=" . $variable ['perfil'];
				break;

			case "consultaPerfil" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " c.nombre AS concurso, ";
				$cadenaSql .= " p.nombre AS perfil, ";
				$cadenaSql .= " p.requisitos ";
				$cadenaSql .= " FROM concurso.concurso AS c, ";
				$cadenaSql .= " concurso.concurso_perfil AS p ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " c.consecutivo_concurso=" . $variable ['consecutivo_concurso'];
				$cadenaSql .= " AND p.consecutivo_perfil=" . $variable ['consecutivo_perfil'];
				$cadenaSql .= " AND c.consecutivo_concurso=p.consecutivo_concurso ";

				break;

			case 'buscarSoporte' :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " sop.consecutivo_soporte,";
				$cadenaSql .= " sop.consecutivo_persona,";
				$cadenaSql .= " sop.tipo_dato, ";
				$cadenaSql .= " sop.consecutivo_dato,";
				$cadenaSql .= " sop.nombre archivo,";
				$cadenaSql .= " sop.alias,";
				$cadenaSql .= " tsop.tipo_soporte,";
				$cadenaSql .= " tsop.nombre, ";
				$cadenaSql .= " tsop.ubicacion";
				$cadenaSql .= " FROM concurso.soporte sop";
				$cadenaSql .= " INNER JOIN general.tipo_soporte tsop";
				$cadenaSql .= " ON tsop.tipo_soporte=sop.tipo_soporte";
				$cadenaSql .= " AND tsop.estado=sop.estado";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " tsop.estado='A' ";
				$cadenaSql .= " AND sop.tipo_dato='" . $variable ['tipo_dato'] . "'";
				$cadenaSql .= " AND sop.consecutivo_persona='" . $variable ['consecutivo'] . "'";
				$cadenaSql .= " AND tsop.nombre='" . $variable ['nombre_soporte'] . "'";
				if (isset ( $variable ['consecutivo_dato'] ) && $variable ['consecutivo_dato'] != '') {
					$cadenaSql .= " AND sop.consecutivo_dato='" . $variable ['consecutivo_dato'] . "' ";
				}
				$cadenaSql .= " ORDER BY sop.consecutivo_soporte DESC ";
				break;
			case "consultarNivel" :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " codigo_nivel,";
				$cadenaSql .= " nombre,";
				$cadenaSql .= " tipo_nivel,";
				$cadenaSql .= " descripcion,";
				$cadenaSql .= " estado";
				$cadenaSql .= " FROM general.nivel";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " estado='A'";
				if (isset ( $variable ['tipo_nivel'] ) && $variable ['tipo_nivel'] != '') {
					$cadenaSql .= " AND tipo_nivel='" . $variable ['tipo_nivel'] . "' ";
				}
				if (isset ( $variable ['codigo_nivel'] ) && $variable ['codigo_nivel'] > 0) {
					$cadenaSql .= " AND codigo_nivel='" . $variable ['codigo_nivel'] . "' ";
				}
				if (isset ( $variable ['nombre'] ) && $variable ['nombre'] != '') {
					$cadenaSql .= " AND lower(nombre) LIKE lower('" . $variable ['nombre'] . "') ";
				}
				if (isset ( $variable ['add_otro'] ) && $variable ['add_otro'] == 'SI') {
					$cadenaSql .= " UNION ";
					$cadenaSql .= " SELECT DISTINCT ";
					$cadenaSql .= " 0 codigo_nivel, ";
					$cadenaSql .= " 'OTRO' nombre, ";
					$cadenaSql .= " 'OTRO' tipo_nivel, ";
					$cadenaSql .= " 'OTRO' descripcion, ";
					$cadenaSql .= " 'A' estado";
					$cadenaSql .= " FROM general.nivel";
				}
				if (isset ( $variable ['order'] ) && $variable ['order'] == 'codigo') {
					$cadenaSql .= " ORDER BY codigo_nivel ";
				} else {
					$cadenaSql .= " ORDER BY nombre ";
				}

				break;
			case "consultaConcurso2" :
				$cadenaSql = " SELECT conc.consecutivo_concurso, ";
				$cadenaSql .= " conc.consecutivo_modalidad,";
				$cadenaSql .= " conc.nombre, ";
				$cadenaSql .= " conc.acuerdo, ";
				$cadenaSql .= " conc.descripcion,";
				$cadenaSql .= " conc.fecha_inicio,";
				$cadenaSql .= " conc.fecha_fin, ";
				$cadenaSql .= " (CASE WHEN conc.estado='A' THEN 'Activo' ELSE 'Inactivo' END) estado, ";
				$cadenaSql .= " mdl.nombre modalidad, ";
				$cadenaSql .= " mdl.codigo_nivel_concurso,";
				$cadenaSql .= " nvl.nombre nivel_concurso,";
				$cadenaSql .= " ( SELECT count(insc.consecutivo_inscrito) inscrito";
				$cadenaSql .= " FROM concurso.concurso_perfil prf";
				$cadenaSql .= " INNER JOIN concurso.concurso_inscrito insc ON prf.consecutivo_perfil=insc.consecutivo_perfil";
				$cadenaSql .= " WHERE prf.estado='A'";
				$cadenaSql .= " AND prf.consecutivo_concurso=conc.consecutivo_concurso) inscritos";
				$cadenaSql .= " FROM concurso.concurso conc ";
				$cadenaSql .= " INNER JOIN concurso.modalidad_concurso mdl ON mdl.consecutivo_modalidad=conc.consecutivo_modalidad";
				$cadenaSql .= " INNER JOIN general.nivel nvl ON nvl.tipo_nivel='TipoConcurso' AND nvl.codigo_nivel= mdl.codigo_nivel_concurso";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " conc.estado='A' ";

				if (isset ( $variable ['consecutivo_concurso'] ) && $variable ['consecutivo_concurso'] != '') {
					$cadenaSql .= " AND conc.consecutivo_concurso='" . $variable ['consecutivo_concurso'] . "' ";
				}
				if (isset ( $variable ['hoy'] ) && $variable ['hoy'] != '') {
					$cadenaSql .= " AND conc.fecha_inicio <='" . $variable ['hoy'] . "' ";
					$cadenaSql .= " AND conc.fecha_fin>= '" . $variable ['hoy'] . "' ";
				}
				$cadenaSql .= " ORDER BY ";
				$cadenaSql .= " conc.fecha_inicio DESC, ";
				$cadenaSql .= " conc.fecha_fin DESC ";

				break;

			case "consultaConcurso" :
				$cadenaSql = " SELECT DISTINCT conc.consecutivo_concurso, ";
				$cadenaSql .= " conc.consecutivo_modalidad,";
				$cadenaSql .= " conc.nombre, ";
				$cadenaSql .= " conc.acuerdo, ";
				$cadenaSql .= " conc.descripcion,";
				$cadenaSql .= " conc.fecha_inicio,";
				$cadenaSql .= " conc.fecha_fin, ";
				$cadenaSql .= " (CASE WHEN conc.estado='A' THEN 'Activo' ELSE 'Inactivo' END) estado, ";
				$cadenaSql .= " mdl.nombre modalidad, ";
				$cadenaSql .= " mdl.codigo_nivel_concurso,";
				$cadenaSql .= " nvl.nombre nivel_concurso,";
				$cadenaSql .= " ci.consecutivo_perfil";

				$cadenaSql .= " FROM";
				$cadenaSql .= " concurso.modalidad_concurso mdl, concurso.concurso conc, general.nivel nvl,";
				$cadenaSql .= " concurso.jurado_inscrito ji, concurso.concurso_inscrito ci, concurso.concurso_perfil cp";

				$cadenaSql .= " WHERE ";
				$cadenaSql .= " mdl.consecutivo_modalidad=conc.consecutivo_modalidad";
				$cadenaSql .= " AND nvl.tipo_nivel='TipoConcurso'";
				$cadenaSql .= " AND nvl.codigo_nivel= mdl.codigo_nivel_concurso";
				$cadenaSql .= " AND conc.estado='A' ";

				/*
				 * if(isset($variable['consecutivo_concurso']) && $variable['consecutivo_concurso']!='' )
				 * {$cadenaSql .= " AND conc.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
				 * }
				 */
				if (isset ( $variable ['hoy'] ) && $variable ['hoy'] != '') {
					$cadenaSql .= " AND conc.fecha_inicio <='" . $variable ['hoy'] . "' ";
					$cadenaSql .= " AND conc.fecha_fin>= '" . $variable ['hoy'] . "' ";
				}

				$cadenaSql .= " AND ji.id_inscrito=ci.consecutivo_inscrito";
				$cadenaSql .= " AND ji.id_usuario='" . $variable ['jurado'] . "'";
				$cadenaSql .= " AND ji.id_inscrito=ci.consecutivo_inscrito";
				$cadenaSql .= " AND conc.consecutivo_concurso = cp.consecutivo_concurso";

				$cadenaSql .= " ORDER BY ";
				$cadenaSql .= " conc.fecha_inicio DESC, ";
				$cadenaSql .= " conc.fecha_fin DESC ";

				break;

			case "consultarCalendarioConcurso" :
				$cadenaSql = " SELECT DISTINCT ";
				$cadenaSql .= " cal.consecutivo_calendario, ";
				$cadenaSql .= " cal.consecutivo_concurso, ";
				$cadenaSql .= " cal.consecutivo_actividad, ";
				$cadenaSql .= " act.nombre ,";
				$cadenaSql .= " cal.descripcion,";
				$cadenaSql .= " cal.fecha_inicio, ";
				$cadenaSql .= " cal.fecha_fin, ";
				$cadenaSql .= " cal.estado, ";
				$cadenaSql .= " est.estado nom_estado, ";
				$cadenaSql .= " cal.consecutivo_evaluar,";
				$cadenaSql .= " crt.nombre criterio,";
				$cadenaSql .= " (CASE WHEN act.nombre='Registro Soportes'  ";
				$cadenaSql .= " THEN 'S' ";
				$cadenaSql .= " ELSE 'N' END ) soporte,  ";
				$cadenaSql .= " (SELECT count(sop.consecutivo_soporte_ins) soporte  ";
				$cadenaSql .= "FROM concurso.concurso_perfil prf  ";
				$cadenaSql .= "INNER JOIN concurso.concurso_inscrito insc ON prf.consecutivo_perfil=insc.consecutivo_perfil  ";
				$cadenaSql .= "INNER JOIN concurso.soporte_inscrito sop ON sop.consecutivo_inscrito=insc.consecutivo_inscrito  ";
				$cadenaSql .= "WHERE prf.consecutivo_concurso=cal.consecutivo_concurso  ";
				$cadenaSql .= " ) inscrito  ";
				$cadenaSql .= " FROM concurso.concurso_calendario cal";
				$cadenaSql .= " INNER JOIN concurso.actividad_calendario act ON act.consecutivo_actividad=cal.consecutivo_actividad";
				$cadenaSql .= " INNER JOIN general.estado est ON est.tipo=cal.estado ";
				$cadenaSql .= " LEFT OUTER JOIN concurso.concurso_evaluar eval ON eval.consecutivo_evaluar=cal.consecutivo_evaluar";
				$cadenaSql .= " LEFT OUTER JOIN concurso.criterio_evaluacion crt ON crt.consecutivo_criterio=eval.consecutivo_criterio";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " cal.consecutivo_concurso='" . $variable ['consecutivo_concurso'] . "' ";
				if (isset ( $variable ['consecutivo_calendario'] ) && $variable ['consecutivo_calendario'] != '') {
					$cadenaSql .= " AND cal.consecutivo_calendario='" . $variable ['consecutivo_calendario'] . "' ";
				}
				$cadenaSql .= " ORDER BY  cal.fecha_inicio ASC, cal.fecha_fin ASC ";

				break;

			case 'consultarAspirantesAsignados' :
				$cadenaSql = "SELECT DISTINCT ji.id, ";
				$cadenaSql .= " ji.id_usuario, ";
				$cadenaSql .= " ji.id_inscrito, ";
				$cadenaSql .= " ji.id_jurado_tipo, ";
				$cadenaSql .= " ji.fecha_registro, ";
				$cadenaSql .= " ji.estado, ";
				$cadenaSql .= " cp.nombre AS perfil, ";
				$cadenaSql .= " ci.consecutivo_persona, ";
				$cadenaSql .= " p.consecutivo, ";
				$cadenaSql .= " p.tipo_identificacion, ";
				$cadenaSql .= " p.nombre, ";
				$cadenaSql .= " p.apellido, ";
				$cadenaSql .= " p.identificacion, ";
				$cadenaSql .= " c.consecutivo_concurso, ";
				$cadenaSql .= " cp.consecutivo_perfil, ";
				$cadenaSql .= " ci.consecutivo_inscrito ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " concurso.jurado_inscrito ji ";
				$cadenaSql .= " INNER JOIN  concurso.concurso_inscrito ci ON ji.id_inscrito=ci.consecutivo_inscrito ";
				$cadenaSql .= " INNER JOIN  concurso.etapa_inscrito paso ON paso.consecutivo_inscrito = ci.consecutivo_inscrito ";
				$cadenaSql .= " INNER JOIN  concurso.concurso_perfil cp ON ci.consecutivo_perfil = cp.consecutivo_perfil ";
				$cadenaSql .= " INNER JOIN  concurso.concurso c ON cp.consecutivo_concurso=c.consecutivo_concurso ";
				$cadenaSql .= " INNER JOIN  concurso.persona p ON p.consecutivo = ci.consecutivo_persona ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " ji.id_usuario='" . $variable ['jurado'] . "'";
				$cadenaSql .= " AND paso.consecutivo_calendario IN ";
				$cadenaSql .= " (SELECT DISTINCT eval.consecutivo_calendario fase ";
				$cadenaSql .= " FROM concurso.concurso_evaluar eval ";
				$cadenaSql .= " INNER JOIN  concurso.jurado_criterio jcrt ON jcrt.id_criterio=eval.consecutivo_criterio AND jcrt.estado=eval.estado ";
				$cadenaSql .= " INNER JOIN public.jano_usuario_subsistema usu ON usu.rol_id=jcrt.id_jurado_rol AND usu.estado='1' AND usu.fecha_caduca>='" . $variable ['hoy'] . "' ";
				$cadenaSql .= " WHERE usu.id_usuario='" . $variable ['jurado'] . "'";
				$cadenaSql .= " AND eval.estado='A' ";
				$cadenaSql .= " AND eval.consecutivo_concurso='" . $variable ['concurso'] . "') ";
				break;

			case 'buscarSoporte' :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " sop.consecutivo_soporte,";
				$cadenaSql .= " sop.consecutivo_persona,";
				$cadenaSql .= " sop.tipo_dato, ";
				$cadenaSql .= " sop.consecutivo_dato,";
				$cadenaSql .= " sop.nombre archivo,";
				$cadenaSql .= " sop.alias,";
				$cadenaSql .= " tsop.tipo_soporte,";
				$cadenaSql .= " tsop.nombre, ";
				$cadenaSql .= " tsop.ubicacion";
				$cadenaSql .= " FROM concurso.soporte sop";
				$cadenaSql .= " INNER JOIN general.tipo_soporte tsop";
				$cadenaSql .= " ON tsop.tipo_soporte=sop.tipo_soporte";
				$cadenaSql .= " AND tsop.estado=sop.estado";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " tsop.estado='A' ";
				$cadenaSql .= " AND sop.tipo_dato='" . $variable ['tipo_dato'] . "'";
				$cadenaSql .= " AND sop.consecutivo_persona='" . $variable ['consecutivo'] . "'";
				$cadenaSql .= " AND tsop.nombre='" . $variable ['nombre_soporte'] . "'";
				if (isset ( $variable ['consecutivo_dato'] ) && $variable ['consecutivo_dato'] != '') {
					$cadenaSql .= " AND sop.consecutivo_dato='" . $variable ['consecutivo_dato'] . "' ";
				}
				$cadenaSql .= " ORDER BY sop.consecutivo_soporte DESC ";
				break;
			// busca datos basicos
			case "concurso.persona" :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " bas.consecutivo,";
				$cadenaSql .= " bas.tipo_identificacion, ";
				$cadenaSql .= " bas.identificacion, ";
				$cadenaSql .= " bas.nombre, ";
				$cadenaSql .= " bas.apellido,";
				$cadenaSql .= " bas.lugar_nacimiento, ";
				$cadenaSql .= "(SELECT c.nombre ";
				$cadenaSql .= "FROM general.ciudad c ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "c.id_ciudad = bas.lugar_nacimiento) ciudad, ";
				$cadenaSql .= " bas.fecha_nacimiento, ";
				$cadenaSql .= " bas.pais_nacimiento, ";
				$cadenaSql .= "(SELECT nombre_pais ";
				$cadenaSql .= "FROM general.pais ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "id_pais =bas.pais_nacimiento) pais, ";
				$cadenaSql .= " bas.departamento_nacimiento, ";
				$cadenaSql .= "(SELECT dep.nombre ";
				$cadenaSql .= "FROM general.departamento dep ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "dep.id_departamento = bas.departamento_nacimiento) departamento,";
				$cadenaSql .= " bas.sexo ";
				$cadenaSql .= " FROM concurso.persona bas ";
				$cadenaSql .= " WHERE bas.consecutivo='" . $variable ['consecutivo_persona'] . "'";
				break;
			// busca datos de contacto
			case "concurso.contacto" :
				$cadenaSql = " SELECT DISTINCT ";
				$cadenaSql .= " cont.consecutivo_contacto, ";
				$cadenaSql .= " cont.consecutivo_persona, ";
				$cadenaSql .= " cont.pais_residencia, ";
				$cadenaSql .= "(SELECT nombre_pais ";
				$cadenaSql .= "FROM general.pais ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "id_pais =cont.pais_residencia) pais, ";
				$cadenaSql .= " cont.departamento_residencia, ";
				$cadenaSql .= "(SELECT dep.nombre ";
				$cadenaSql .= "FROM general.departamento dep ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "dep.id_departamento = cont.departamento_residencia) departamento,";
				$cadenaSql .= " cont.ciudad_residencia, ";
				$cadenaSql .= "(SELECT c.nombre ";
				$cadenaSql .= "FROM general.ciudad c ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "c.id_ciudad = cont.ciudad_residencia) ciudad, ";
				$cadenaSql .= " cont.direccion_residencia, ";
				$cadenaSql .= " cont.correo, ";
				$cadenaSql .= " cont.correo_secundario, ";
				$cadenaSql .= " cont.telefono, ";
				$cadenaSql .= " cont.celular";
				$cadenaSql .= " FROM concurso.contacto cont ";
				$cadenaSql .= " WHERE cont.consecutivo_persona='" . $variable ['consecutivo_persona'] . "'";
				break;
			// busca formacion academica
			case "concurso.formacion" :
				$cadenaSql = " SELECT DISTINCT ";
				$cadenaSql .= " form.consecutivo_formacion, ";
				$cadenaSql .= " form.consecutivo_persona, ";
				$cadenaSql .= " form.codigo_modalidad, ";
				$cadenaSql .= " modo.nombre modalidad,";
				$cadenaSql .= " form.codigo_nivel, ";
				$cadenaSql .= " nv.nombre nivel,";
				$cadenaSql .= " form.pais_formacion, ";
				$cadenaSql .= " ps.nombre_pais pais,";
				$cadenaSql .= " form.codigo_institucion, ";
				$cadenaSql .= " form.nombre_institucion, ";
				$cadenaSql .= " form.codigo_programa, ";
				$cadenaSql .= " form.nombre_programa, ";
				$cadenaSql .= " form.cursos_aprobados, ";
				$cadenaSql .= " form.graduado, ";
				$cadenaSql .= " form.fecha_grado, ";
				$cadenaSql .= " form.promedio ";
				$cadenaSql .= " FROM concurso.formacion form ";
				$cadenaSql .= " INNER JOIN general.modalidad_educacion modo ON modo.codigo_modalidad=form.codigo_modalidad ";
				$cadenaSql .= " INNER JOIN general.nivel nv ON nv.codigo_nivel=form.codigo_nivel";
				$cadenaSql .= " INNER JOIN general.pais ps ON ps.id_pais=form.pais_formacion";
				$cadenaSql .= " WHERE form.consecutivo_persona='" . $variable ['consecutivo_persona'] . "'";
				$cadenaSql .= " ORDER BY form.codigo_nivel, ";
				$cadenaSql .= " form.fecha_grado";
				break;

			case "concurso.experiencia_laboral" :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " prof.consecutivo_experiencia,";
				$cadenaSql .= " prof.consecutivo_persona,";
				$cadenaSql .= " prof.codigo_nivel_experiencia, ";
				$cadenaSql .= " (SELECT nombre FROM general.nivel WHERE codigo_nivel=prof.codigo_nivel_experiencia) nivel_experiencia,";
				$cadenaSql .= " prof.pais_experiencia,";
				$cadenaSql .= " ps.nombre_pais pais,";
				$cadenaSql .= " prof.codigo_nivel_institucion, ";
				$cadenaSql .= " (SELECT nombre FROM general.nivel WHERE codigo_nivel=prof.codigo_nivel_institucion) nivel_institucion,";
				$cadenaSql .= " prof.codigo_institucion,";
				$cadenaSql .= " prof.nombre_institucion, ";
				$cadenaSql .= " prof.direccion_institucion,";
				$cadenaSql .= " prof.correo_institucion,";
				$cadenaSql .= " prof.telefono_institucion, ";
				$cadenaSql .= " prof.cargo,";
				$cadenaSql .= " prof.descripcion_cargo,";
				$cadenaSql .= " prof.actual,";
				$cadenaSql .= " prof.fecha_inicio,";
				$cadenaSql .= " prof.fecha_fin ";
				$cadenaSql .= " FROM concurso.experiencia_laboral prof ";
				$cadenaSql .= " INNER JOIN general.pais ps ON ps.id_pais=prof.pais_experiencia";
				$cadenaSql .= " WHERE prof.consecutivo_persona='" . $variable ['consecutivo_persona'] . "'";
				$cadenaSql .= " ORDER BY prof.fecha_inicio DESC";
				break;

			case "concurso.experiencia_docencia" :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " doc.consecutivo_docencia,";
				$cadenaSql .= " doc.consecutivo_persona,";
				$cadenaSql .= " doc.codigo_nivel_docencia,";
				$cadenaSql .= " (SELECT nombre FROM general.nivel WHERE codigo_nivel=doc.codigo_nivel_docencia) nivel_docencia,";
				$cadenaSql .= " doc.pais_docencia,";
				$cadenaSql .= " ps.nombre_pais pais,";
				$cadenaSql .= " doc.codigo_nivel_institucion,";
				$cadenaSql .= " (SELECT nombre FROM general.nivel WHERE codigo_nivel=doc.codigo_nivel_institucion) nivel_institucion,";
				$cadenaSql .= " doc.codigo_institucion,";
				$cadenaSql .= " doc.nombre_institucion,";
				$cadenaSql .= " doc.direccion_institucion,";
				$cadenaSql .= " doc.correo_institucion,";
				$cadenaSql .= " doc.telefono_institucion,";
				$cadenaSql .= " doc.codigo_vinculacion,";
				$cadenaSql .= " doc.nombre_vinculacion,";
				$cadenaSql .= " doc.descripcion_docencia,";
				$cadenaSql .= " doc.actual,";
				$cadenaSql .= " doc.fecha_inicio,";
				$cadenaSql .= " doc.fecha_fin";
				$cadenaSql .= " FROM concurso.experiencia_docencia doc ";
				$cadenaSql .= " INNER JOIN general.pais ps ON ps.id_pais=doc.pais_docencia";
				$cadenaSql .= " WHERE doc.consecutivo_persona='" . $variable ['consecutivo_persona'] . "'";
				$cadenaSql .= " ORDER BY doc.fecha_inicio DESC";
				break;

			case "concurso.actividad_academica" :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " act.consecutivo_actividad,";
				$cadenaSql .= " act.consecutivo_persona, ";
				$cadenaSql .= " act.pais_actividad,";
				$cadenaSql .= " ps.nombre_pais pais,";
				$cadenaSql .= " act.codigo_nivel_institucion, ";
				$cadenaSql .= " (SELECT nombre FROM general.nivel WHERE codigo_nivel=act.codigo_nivel_institucion) nivel_institucion,";
				$cadenaSql .= " act.codigo_institucion,";
				$cadenaSql .= " act.nombre_institucion,";
				$cadenaSql .= " act.correo_institucion, ";
				$cadenaSql .= " act.telefono_institucion, ";
				$cadenaSql .= " act.codigo_tipo_actividad,";
				$cadenaSql .= " act.nombre_tipo_actividad, ";
				$cadenaSql .= " act.nombre_actividad, ";
				$cadenaSql .= " act.descripcion, ";
				$cadenaSql .= " act.jefe_actividad,";
				$cadenaSql .= " act.fecha_inicio,";
				$cadenaSql .= " act.fecha_fin";
				$cadenaSql .= " FROM concurso.actividad_academica act";
				$cadenaSql .= " INNER JOIN general.pais ps ON ps.id_pais=act.pais_actividad";
				$cadenaSql .= " WHERE act.consecutivo_persona='" . $variable ['consecutivo_persona'] . "'";
				$cadenaSql .= " ORDER BY act.fecha_inicio DESC";
				break;
			case "concurso.experiencia_investigacion" :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " inv.consecutivo_investigacion,";
				$cadenaSql .= " inv.consecutivo_persona,";
				$cadenaSql .= " inv.pais_investigacion,";
				$cadenaSql .= " ps.nombre_pais pais,";
				$cadenaSql .= " inv.codigo_nivel_institucion,";
				$cadenaSql .= " (SELECT nombre FROM general.nivel WHERE codigo_nivel=inv.codigo_nivel_institucion) nivel_institucion,";
				$cadenaSql .= " inv.codigo_institucion,";
				$cadenaSql .= " inv.nombre_institucion,";
				$cadenaSql .= " inv.direccion_institucion,";
				$cadenaSql .= " inv.correo_institucion,";
				$cadenaSql .= " inv.telefono_institucion,";
				$cadenaSql .= " inv.titulo_investigacion,";
				$cadenaSql .= " inv.jefe_investigacion,";
				$cadenaSql .= " inv.descripcion_investigacion,";
				$cadenaSql .= " inv.direccion_investigacion,";
				$cadenaSql .= " inv.actual,";
				$cadenaSql .= " inv.fecha_inicio,";
				$cadenaSql .= " inv.fecha_fin,";
				$cadenaSql .= " inv.grupo_investigacion,";
				$cadenaSql .= " inv.categoria_grupo ";
				$cadenaSql .= " FROM concurso.experiencia_investigacion inv";
				$cadenaSql .= " INNER JOIN general.pais ps ON ps.id_pais=inv.pais_investigacion";
				$cadenaSql .= " WHERE inv.consecutivo_persona='" . $variable ['consecutivo_persona'] . "'";
				$cadenaSql .= " ORDER BY inv.fecha_inicio DESC";
				break;
			case "concurso.produccion_academica" :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " prod.consecutivo_produccion,";
				$cadenaSql .= " prod.consecutivo_persona,";
				$cadenaSql .= " prod.codigo_tipo_produccion,";
				$cadenaSql .= " (SELECT nombre FROM general.nivel WHERE codigo_nivel=prod.codigo_tipo_produccion) nombre_tipo_produccion,";
				$cadenaSql .= " prod.titulo_produccion,";
				$cadenaSql .= " prod.nombre_autor,";
				$cadenaSql .= " prod.nombre_producto_incluye,";
				$cadenaSql .= " prod.nombre_editorial,";
				$cadenaSql .= " prod.volumen,";
				$cadenaSql .= " prod.pagina,";
				$cadenaSql .= " prod.codigo_isbn,";
				$cadenaSql .= " prod.codigo_issn,";
				$cadenaSql .= " prod.indexado,";
				$cadenaSql .= " prod.pais_produccion,";
				$cadenaSql .= " prod.departamento_produccion,";
				$cadenaSql .= " prod.ciudad_produccion,";
				$cadenaSql .= " city.nombre ciudad,";
				$cadenaSql .= " prod.descripcion,";
				$cadenaSql .= " prod.direccion_produccion,";
				$cadenaSql .= " prod.fecha_produccion";
				$cadenaSql .= " FROM concurso.produccion_academica prod ";
				$cadenaSql .= " INNER JOIN general.ciudad city ON city.id_ciudad=prod.ciudad_produccion";
				$cadenaSql .= " WHERE prod.consecutivo_persona='" . $variable ['consecutivo_persona'] . "'";
				$cadenaSql .= " ORDER BY prod.fecha_produccion DESC";
				break;
			case "concurso.conocimiento_idioma" :
				$cadenaSql = " SELECT DISTINCT";
				$cadenaSql .= " conidm.consecutivo_conocimiento,";
				$cadenaSql .= " conidm.consecutivo_persona, ";
				$cadenaSql .= " conidm.codigo_idioma, ";
				$cadenaSql .= " idm.nombre idioma, ";
				$cadenaSql .= " conidm.nivel_lee, ";
				// $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=to_number(conidm.nivel_lee,'99')) nombre_nivel_lee,";
				$cadenaSql .= " conidm.nivel_escribe,";
				// $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=to_number(conidm.nivel_escribe,'99')) nombre_nivel_escribe,";
				$cadenaSql .= " conidm.nivel_habla, ";
				// $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=to_number(conidm.nivel_habla,'99')) nombre_nivel_habla,";
				$cadenaSql .= " conidm.certificacion,";
				$cadenaSql .= " conidm.institucion_certificacion";
				$cadenaSql .= " FROM concurso.conocimiento_idioma conidm ";
				$cadenaSql .= " INNER JOIN general.idioma idm ON idm.codigo_idioma=conidm.codigo_idioma";
				$cadenaSql .= " WHERE conidm.consecutivo_persona='" . $variable ['consecutivo_persona'] . "'";
				$cadenaSql .= " ORDER BY idm.nombre DESC";
				break;

			case "registroSoporteConcurso" :
				$cadenaSql = " INSERT INTO concurso.soporte_inscrito(";
				$cadenaSql .= " consecutivo_soporte_ins,";
				$cadenaSql .= " consecutivo_inscrito, ";
				$cadenaSql .= " tipo_dato, ";
				$cadenaSql .= " consecutivo_dato,";
				$cadenaSql .= " fuente_dato, ";
				$cadenaSql .= " valor_dato, ";
				$cadenaSql .= " consecutivo_soporte, ";
				$cadenaSql .= " alias_soporte, ";
				$cadenaSql .= " nombre_soporte, ";
				$cadenaSql .= " fecha_registro, ";
				$cadenaSql .= " estado)";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " DEFAULT, ";
				$cadenaSql .= " '" . $variable ['consecutivo_inscrito'] . "', ";
				$cadenaSql .= " '" . $variable ['tipo_dato'] . "', ";
				$cadenaSql .= " '" . $variable ['consecutivo_dato'] . "', ";
				$cadenaSql .= " '" . $variable ['fuente_dato'] . "', ";
				$cadenaSql .= " '" . $variable ['valor_dato'] . "', ";
				$cadenaSql .= " '" . $variable ['consecutivo_soporte'] . "', ";
				$cadenaSql .= " '" . $variable ['alias_soporte'] . "', ";
				$cadenaSql .= " '" . $variable ['nombre_soporte'] . "', ";
				$cadenaSql .= " '" . $variable ['fecha_registro'] . "', ";
				$cadenaSql .= " 'A' ";
				$cadenaSql .= " )";
				$cadenaSql .= " RETURNING consecutivo_soporte_ins";
				break;

			/**
			 * ********
			 */

			case "consultaModalidad" :
				$cadenaSql = " SELECT DISTINCT  ";
				$cadenaSql .= " consecutivo_modalidad codigo, ";
				$cadenaSql .= " nombre, ";
				$cadenaSql .= " estado";
				$cadenaSql .= " FROM concurso.modalidad_concurso";
				$cadenaSql .= " WHERE estado='A'";
				if (isset ( $variable ['tipo_concurso'] ) && $variable ['tipo_concurso'] != '') {
					$cadenaSql .= " AND  codigo_nivel_concurso='" . $variable ['tipo_concurso'] . "' ";
				}
				break;
			case "consultaFactor" :
				$cadenaSql = " SELECT DISTINCT ";
				$cadenaSql .= " consecutivo_factor, ";
				$cadenaSql .= " nombre, ";
				$cadenaSql .= " estado ";
				$cadenaSql .= " FROM  ";
				$cadenaSql .= " concurso.factor_evaluacion ";
				$cadenaSql .= " WHERE estado='A'";
				if (isset ( $variable ['consecutivo_factor'] ) && $variable ['consecutivo_factor'] != '') {
					$cadenaSql .= " AND  consecutivo_factor='" . $variable ['consecutivo_factor'] . "' ";
				}
				break;
			case "consultaCriterio" :
				$cadenaSql = " SELECT DISTINCT ";
				$cadenaSql .= " consecutivo_criterio codigo, ";
				$cadenaSql .= " nombre, ";
				$cadenaSql .= " consecutivo_factor, ";
				$cadenaSql .= " estado ";
				$cadenaSql .= " FROM concurso.criterio_evaluacion ";
				$cadenaSql .= " WHERE estado='A' ";
				if (isset ( $variable ['consecutivo_criterio'] ) && $variable ['consecutivo_criterio'] != '') {
					$cadenaSql .= " AND consecutivo_criterio='" . $variable ['consecutivo_criterio'] . "' ";
				}
				if (isset ( $variable ['consecutivo_factor'] ) && $variable ['consecutivo_factor'] != '') {
					$cadenaSql .= " AND consecutivo_factor='" . $variable ['consecutivo_factor'] . "' ";
				}
				break;
			case "consultaActividadObligatoria" :
				$cadenaSql = " SELECT DISTINCT ";
				$cadenaSql .= " consecutivo_actividad, ";
				$cadenaSql .= " nombre, ";
				$cadenaSql .= " descripcion, ";
				$cadenaSql .= " estado ";
				$cadenaSql .= " FROM concurso.actividad_calendario ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " nombre IN ('Inscripción','Registro Soportes','Evaluar Requisitos') ";
				break;
			case "consultaActividadCalendario" :
				$cadenaSql = " SELECT DISTINCT ";
				$cadenaSql .= " consecutivo_actividad codigo, ";
				$cadenaSql .= " nombre, ";
				$cadenaSql .= " descripcion, ";
				$cadenaSql .= " estado ";
				$cadenaSql .= " FROM concurso.actividad_calendario ";
				$cadenaSql .= " WHERE  ";
				$cadenaSql .= " consecutivo_actividad NOT IN  ";
				$cadenaSql .= " (SELECT DISTINCT ";
				$cadenaSql .= "  consecutivo_actividad ";
				$cadenaSql .= " FROM concurso.concurso_calendario ";
				$cadenaSql .= " WHERE consecutivo_concurso='" . $variable ['consecutivo_concurso'] . "' ";
				$cadenaSql .= "  ) ";
				if (isset ( $variable ['consecutivo_actividad'] ) && $variable ['consecutivo_actividad'] != '') {
					$cadenaSql .= " UNION ";
					$cadenaSql .= " SELECT DISTINCT ";
					$cadenaSql .= " consecutivo_actividad codigo, ";
					$cadenaSql .= " nombre, ";
					$cadenaSql .= " descripcion, ";
					$cadenaSql .= " estado ";
					$cadenaSql .= " FROM concurso.actividad_calendario ";
					$cadenaSql .= " WHERE  ";
					$cadenaSql .= " consecutivo_actividad ='" . $variable ['consecutivo_actividad'] . "' ";
				}
				break;
			case "consultaCriterioCalendario" :
				$cadenaSql = " SELECT DISTINCT ";
				$cadenaSql .= " eval.consecutivo_evaluar codigo, ";
				$cadenaSql .= " crt.nombre, ";
				$cadenaSql .= " crt.estado ";
				$cadenaSql .= " FROM concurso.criterio_evaluacion crt ";
				$cadenaSql .= " INNER JOIN concurso.concurso_evaluar eval  ";
				$cadenaSql .= " ON crt.consecutivo_criterio=eval.consecutivo_criterio  ";
				$cadenaSql .= " AND eval.estado='A' ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " eval.consecutivo_concurso='" . $variable ['consecutivo_concurso'] . "' ";
				if (isset ( $variable ['consecutivo_criterio'] ) && $variable ['consecutivo_criterio'] != '') {
					$cadenaSql .= " AND crt_ev.consecutivo_criterio='" . $variable ['consecutivo_criterio'] . "' ";
				}
				$cadenaSql .= " ORDER BY crt.nombre ";
				break;

			case "consultarCriterioConcurso" :
				$cadenaSql = " SELECT DISTINCT ";
				$cadenaSql .= " crt_ev.consecutivo_evaluar, ";
				$cadenaSql .= " crt_ev.consecutivo_concurso, ";
				$cadenaSql .= " crt.consecutivo_factor, ";
				$cadenaSql .= " fac.nombre factor, ";
				$cadenaSql .= " crt_ev.consecutivo_criterio, ";
				$cadenaSql .= " crt.nombre criterio, ";
				$cadenaSql .= " crt_ev.maximo_puntos, ";
				$cadenaSql .= " crt_ev.estado, ";
				$cadenaSql .= " est.estado nom_estado ";
				$cadenaSql .= " FROM concurso.concurso_evaluar crt_ev ";
				$cadenaSql .= " INNER JOIN concurso.criterio_evaluacion crt ON crt_ev.consecutivo_criterio=crt.consecutivo_criterio ";
				$cadenaSql .= " INNER JOIN concurso.factor_evaluacion fac ON fac.consecutivo_factor=crt.consecutivo_factor ";
				$cadenaSql .= " INNER JOIN general.estado est ON est.tipo=crt_ev.estado ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " crt_ev.consecutivo_concurso='" . $variable ['consecutivo_concurso'] . "' ";
				if (isset ( $variable ['consecutivo_evaluar'] ) && $variable ['consecutivo_evaluar'] != '') {
					$cadenaSql .= " AND crt_ev.consecutivo_evaluar='" . $variable ['consecutivo_evaluar'] . "' ";
				}
				$cadenaSql .= " ORDER BY fac.nombre, crt.nombre ";
				break;

			case "registroConcurso" :
				$cadenaSql = " INSERT INTO concurso.concurso(";
				$cadenaSql .= " consecutivo_concurso,";
				$cadenaSql .= " consecutivo_modalidad, ";
				$cadenaSql .= " nombre,";
				$cadenaSql .= " acuerdo, ";
				$cadenaSql .= " descripcion,";
				$cadenaSql .= " fecha_inicio,";
				$cadenaSql .= " fecha_fin, ";
				$cadenaSql .= " estado)";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " DEFAULT, ";
				$cadenaSql .= " '" . $variable ['codigo_modalidad'] . "', ";
				$cadenaSql .= " '" . $variable ['nombre'] . "', ";
				$cadenaSql .= " '" . $variable ['acuerdo'] . "', ";
				$cadenaSql .= " '" . $variable ['descripcion'] . "', ";
				$cadenaSql .= " '" . $variable ['fecha_inicio_concurso'] . "', ";
				$cadenaSql .= " '" . $variable ['fecha_fin_concurso'] . "', ";
				$cadenaSql .= " 'A' ";
				$cadenaSql .= " )";
				$cadenaSql .= " RETURNING consecutivo_concurso";
				break;
			case "registroCriterioConcurso" :
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " concurso.concurso_evaluar(";
				$cadenaSql .= " consecutivo_evaluar,";
				$cadenaSql .= " consecutivo_concurso,";
				$cadenaSql .= " consecutivo_criterio,";
				$cadenaSql .= " maximo_puntos,";
				$cadenaSql .= "  estado)";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " DEFAULT, ";
				$cadenaSql .= " '" . $variable ['consecutivo_concurso'] . "', ";
				$cadenaSql .= " '" . $variable ['consecutivo_criterio'] . "', ";
				$cadenaSql .= " '" . $variable ['maximo_puntos'] . "', ";
				$cadenaSql .= " 'A' ";
				$cadenaSql .= " )";
				$cadenaSql .= " RETURNING consecutivo_evaluar";
				break;
			case "registroCalendarioConcurso" :
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " concurso.concurso_calendario(";
				$cadenaSql .= " consecutivo_calendario,";
				$cadenaSql .= " consecutivo_concurso,";
				$cadenaSql .= " consecutivo_actividad,";
				$cadenaSql .= " descripcion,";
				$cadenaSql .= " fecha_inicio,";
				$cadenaSql .= " fecha_fin,";
				$cadenaSql .= " estado,";
				$cadenaSql .= " consecutivo_evaluar)";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " DEFAULT, ";
				$cadenaSql .= " '" . $variable ['consecutivo_concurso'] . "', ";
				$cadenaSql .= " '" . $variable ['consecutivo_actividad'] . "', ";
				$cadenaSql .= " '" . $variable ['descripcion'] . "', ";
				$cadenaSql .= " '" . $variable ['fecha_inicio'] . "', ";
				$cadenaSql .= " '" . $variable ['fecha_fin'] . "', ";
				$cadenaSql .= " 'A', ";
				$cadenaSql .= " '" . $variable ['consecutivo_evaluar'] . "' ";
				$cadenaSql .= " )";
				$cadenaSql .= " RETURNING consecutivo_calendario";
				break;
			case "registroPerfilConcurso" :
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " concurso.concurso_perfil(";
				$cadenaSql .= " consecutivo_perfil,";
				$cadenaSql .= " consecutivo_concurso,";
				$cadenaSql .= " nombre,";
				$cadenaSql .= " descripcion,";
				$cadenaSql .= " requisitos,";
				$cadenaSql .= " dependencia,";
				$cadenaSql .= " area,";
				$cadenaSql .= " vacantes,";
				$cadenaSql .= " estado)";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " DEFAULT, ";
				$cadenaSql .= " '" . $variable ['consecutivo_concurso'] . "', ";
				$cadenaSql .= " '" . $variable ['nombre'] . "', ";
				$cadenaSql .= " '" . $variable ['descripcion'] . "', ";
				$cadenaSql .= " '" . $variable ['requisitos'] . "', ";
				$cadenaSql .= " '" . $variable ['dependencia'] . "', ";
				$cadenaSql .= " '" . $variable ['area'] . "', ";
				$cadenaSql .= " '" . $variable ['vacantes'] . "', ";
				$cadenaSql .= " 'A' ";
				$cadenaSql .= " )";
				$cadenaSql .= " RETURNING consecutivo_perfil";
				break;

			case "actualizaConcurso" :
				$cadenaSql = " UPDATE concurso.concurso";
				$cadenaSql .= " SET ";
				$cadenaSql .= " consecutivo_modalidad= '" . $variable ['codigo_modalidad'] . "', ";
				$cadenaSql .= " nombre= '" . $variable ['nombre'] . "', ";
				$cadenaSql .= " acuerdo= '" . $variable ['acuerdo'] . "', ";
				$cadenaSql .= " descripcion= '" . $variable ['descripcion'] . "', ";
				$cadenaSql .= " fecha_inicio= '" . $variable ['fecha_inicio_concurso'] . "', ";
				$cadenaSql .= " fecha_fin= '" . $variable ['fecha_fin_concurso'] . "' ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " consecutivo_concurso= '" . $variable ['consecutivo_concurso'] . "' ";
				$cadenaSql .= " RETURNING consecutivo_concurso";
				break;
			case "actualizaEstadoConcurso" :
				$cadenaSql = " UPDATE concurso.concurso";
				$cadenaSql .= " SET ";
				$cadenaSql .= " estado= '" . $variable ['estado'] . "' ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " consecutivo_concurso= '" . $variable ['consecutivo_concurso'] . "' ";
				$cadenaSql .= " RETURNING consecutivo_concurso";
				break;
			case "actualizaCriterioConcurso" :
				$cadenaSql = " UPDATE ";
				$cadenaSql .= " concurso.concurso_evaluar ";
				$cadenaSql .= " SET ";
				$cadenaSql .= " consecutivo_criterio='" . $variable ['consecutivo_criterio'] . "', ";
				$cadenaSql .= " maximo_puntos='" . $variable ['maximo_puntos'] . "' ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " consecutivo_evaluar= '" . $variable ['consecutivo_evaluar'] . "' ";
				$cadenaSql .= " RETURNING consecutivo_evaluar";
				break;
			case "actualizaEstadoCriterio" :
				$cadenaSql = " UPDATE concurso.concurso_evaluar";
				$cadenaSql .= " SET ";
				$cadenaSql .= " estado= '" . $variable ['estado'] . "' ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " consecutivo_evaluar= '" . $variable ['consecutivo_evaluar'] . "' ";
				$cadenaSql .= " RETURNING consecutivo_evaluar";
				break;
			case "actualizaCalendarioConcurso" :
				$cadenaSql = " UPDATE ";
				$cadenaSql .= "  concurso.concurso_calendario ";
				$cadenaSql .= " SET ";
				$cadenaSql .= " consecutivo_actividad='" . $variable ['consecutivo_actividad'] . "', ";
				$cadenaSql .= " fecha_inicio='" . $variable ['fecha_inicio'] . "', ";
				$cadenaSql .= " fecha_fin='" . $variable ['fecha_fin'] . "', ";
				$cadenaSql .= " descripcion='" . $variable ['descripcion'] . "', ";
				$cadenaSql .= " consecutivo_evaluar='" . $variable ['consecutivo_evaluar'] . "' ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " consecutivo_calendario= '" . $variable ['consecutivo_calendario'] . "' ";
				$cadenaSql .= " RETURNING consecutivo_calendario";
				break;
			case "actualizaEstadoCalendario" :
				$cadenaSql = " UPDATE concurso.concurso_calendario";
				$cadenaSql .= " SET ";
				$cadenaSql .= " estado= '" . $variable ['estado'] . "' ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " consecutivo_calendario= '" . $variable ['consecutivo_calendario'] . "' ";
				$cadenaSql .= " RETURNING consecutivo_calendario";
				break;
			case "actualizaPerfilConcurso" :
				$cadenaSql = " UPDATE ";
				$cadenaSql .= "  concurso.concurso_perfil ";
				$cadenaSql .= " SET ";
				$cadenaSql .= "nombre= '" . $variable ['nombre'] . "', ";
				$cadenaSql .= "descripcion= '" . $variable ['descripcion'] . "', ";
				$cadenaSql .= "requisitos= '" . $variable ['requisitos'] . "', ";
				$cadenaSql .= "dependencia= '" . $variable ['dependencia'] . "', ";
				$cadenaSql .= "area= '" . $variable ['area'] . "', ";
				$cadenaSql .= "vacantes= '" . $variable ['vacantes'] . "' ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " consecutivo_perfil= '" . $variable ['consecutivo_perfil'] . "' ";
				$cadenaSql .= " RETURNING consecutivo_perfil";
				break;
			case "actualizaEstadoPerfil" :
				$cadenaSql = " UPDATE concurso.concurso_perfil";
				$cadenaSql .= " SET ";
				$cadenaSql .= " estado= '" . $variable ['estado'] . "' ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " consecutivo_perfil= '" . $variable ['consecutivo_perfil'] . "' ";
				$cadenaSql .= " RETURNING consecutivo_perfil";
				break;
			case "CambiarEstadoRol" :

				$cadenaSql = "UPDATE " . $prefijo . "rol_subsistema SET ";
				$cadenaSql .= " estado = '" . $variable ['estado'] . "'";
				$cadenaSql .= " WHERE id_subsistema = '" . $variable ['id_subsistema'] . "' ";
				$cadenaSql .= " AND rol_id = '" . $variable ['rol_id'] . "' ";
				break;

			case "borrarRol" :
				$cadenaSql = "DELETE FROM " . $prefijo . "rol_subsistema ";
				$cadenaSql .= " WHERE id_subsistema = '" . $variable ['id_subsistema'] . "' ";
				$cadenaSql .= " AND rol_id = '" . $variable ['rol_id'] . "' ";
				break;

			/**
			 * Clausulas genéricas.
			 * se espera que estén en todos los formularios
			 * que utilicen esta plantilla
			 */

			case "iniciarTransaccion" :
				$cadenaSql = "START TRANSACTION";
				break;

			case "finalizarTransaccion" :
				$cadenaSql = "COMMIT";
				break;

			case "cancelarTransaccion" :
				$cadenaSql = "ROLLBACK";
				break;
		}
		return $cadenaSql;
	}
}

?>
