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


                        case "consultarCalendarioConcurso":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" cal.consecutivo_calendario, ";
                                $cadenaSql.=" cal.consecutivo_concurso, ";
                                $cadenaSql.=" cal.consecutivo_actividad, ";
                                $cadenaSql.=" act.nombre ,";
                                $cadenaSql.=" cal.descripcion,";
                                $cadenaSql.=" cal.fecha_inicio, ";
                                $cadenaSql.=" cal.fecha_fin, ";
                                $cadenaSql.=" cal.porcentaje_aprueba, ";
                                $cadenaSql.=" cal.fecha_fin_reclamacion, ";
                                $cadenaSql.=" cal.fecha_fin_resolver, ";
                                $cadenaSql.=" cal.cierre, ";
                                $cadenaSql.=" cal.estado, ";
                                $cadenaSql.=" est.estado nom_estado, ";
                                $cadenaSql.=" (CASE WHEN act.nombre='Inscripción' THEN 'registro' ";
                                $cadenaSql.=" WHEN act.nombre='Registro Soportes' THEN 'soporte' ";
                                $cadenaSql.=" WHEN act.nombre='Evaluar Requisitos' THEN 'requisito' ";
                                $cadenaSql.=" WHEN act.nombre='Lista Elegibles' THEN 'elegibles'  ";
                                $cadenaSql.="  ELSE 'evaluacion' END ) fase, ";
                                    $cadenaSql.=" (SELECT count(DISTINCT sop.consecutivo_inscrito) soporte  ";
                                    $cadenaSql.="FROM concurso.concurso_perfil prf  ";
                                    $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc ON prf.consecutivo_perfil=insc.consecutivo_perfil  ";
                                    $cadenaSql.="INNER JOIN concurso.soporte_inscrito sop ON sop.consecutivo_inscrito=insc.consecutivo_inscrito  ";
                                    $cadenaSql.="WHERE prf.consecutivo_concurso=cal.consecutivo_concurso  ";
                                $cadenaSql.=" ) inscrito,  ";
                                    $cadenaSql.="(SELECT count(DISTINCT val.consecutivo_inscrito) valido  ";
                                    $cadenaSql.="FROM concurso.concurso_perfil prf2   ";
                                    $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc2 ON prf2.consecutivo_perfil=insc2.consecutivo_perfil   ";
                                    $cadenaSql.="INNER JOIN concurso.valida_requisito val ON val.consecutivo_inscrito=insc2.consecutivo_inscrito  ";
                                    $cadenaSql.="WHERE prf2.consecutivo_concurso=cal.consecutivo_concurso  ";
                                $cadenaSql.=" ) validado, ";
                                    $cadenaSql.="(SELECT count(etapa.consecutivo_etapa) paso FROM concurso.concurso_perfil prf3  ";
                                    $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc3 ON prf3.consecutivo_perfil=insc3.consecutivo_perfil  ";
                                    $cadenaSql.="INNER JOIN concurso.etapa_inscrito etapa ON etapa.consecutivo_inscrito=insc3.consecutivo_inscrito ";
                                    $cadenaSql.="WHERE prf3.consecutivo_concurso=cal.consecutivo_concurso  ";
                                    $cadenaSql.="AND etapa.consecutivo_calendario=cal.consecutivo_calendario ";
                                $cadenaSql.=" ) clasifico, ";
                                    $cadenaSql.="(SELECT COUNT(DISTINCT id_inscrito) parcial ";
                                    $cadenaSql.="FROM concurso.evaluacion_parcial parc ";
                                    $cadenaSql.="INNER JOIN concurso.concurso_evaluar eval ON eval.consecutivo_evaluar = parc.id_evaluar ";
                                    $cadenaSql.="WHERE parc.estado='A' ";
                                    $cadenaSql.="AND eval.consecutivo_concurso=cal.consecutivo_concurso ";
                                    $cadenaSql.="AND eval.consecutivo_calendario=cal.consecutivo_calendario ";
                                $cadenaSql.=" ) evaluado , ";
                                    $cadenaSql.="(SELECT count(etapa2.consecutivo_etapa) paso2 FROM concurso.concurso_perfil prf4  ";
                                    $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc4 ON prf4.consecutivo_perfil=insc4.consecutivo_perfil  ";
                                    $cadenaSql.="INNER JOIN concurso.etapa_inscrito etapa2 ON etapa2.consecutivo_inscrito=insc4.consecutivo_inscrito ";
                                    $cadenaSql.="WHERE prf4.consecutivo_concurso=cal.consecutivo_concurso  ";
                                    $cadenaSql.="AND etapa2.consecutivo_calendario_ant=cal.consecutivo_calendario ";
                                $cadenaSql.=" ) proceso, ";
                                    $cadenaSql.="(SELECT count(DISTINCT recl.id_inscrito) reclama   ";
                                    $cadenaSql.="FROM concurso.evaluacion_reclamacion recl ";
                                    $cadenaSql.=" WHERE   recl.estado='A'  ";
                                    $cadenaSql.="AND recl.consecutivo_calendario=cal.consecutivo_calendario ";
                                $cadenaSql.=" ) reclamos ";
                                $cadenaSql.=" FROM concurso.concurso_calendario cal";
                                $cadenaSql.=" INNER JOIN concurso.actividad_calendario act ON act.consecutivo_actividad=cal.consecutivo_actividad";
                                $cadenaSql.=" INNER JOIN general.estado est ON est.tipo=cal.estado ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" cal.estado='A' ";
                                $cadenaSql .= "AND cal.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                
                                if(isset($variable['consecutivo_calendario']) &&  $variable['consecutivo_calendario']!='' )
                                   {
                                    $cadenaSql.=" AND cal.consecutivo_calendario='".$variable['consecutivo_calendario']."' ";
                                   }
                                $cadenaSql.=" AND act.nombre NOT IN (".$variable['noFases'].") ";   
                                $cadenaSql.=" ORDER BY  cal.fecha_inicio ASC, cal.fecha_fin ASC ";

                            break;    
                    
                    
                    /*******/


                    
                    
                    
            case "consultarGruposReclamacion" :
                $cadenaSql=" SELECT distinct grupo.id id_grupo ";
                $cadenaSql.=" FROM concurso.evaluacion_reclamacion reclamacion, ";
                $cadenaSql.=" concurso.evaluacion_parcial evaluacion, concurso.concurso_evaluar ce, concurso.criterio_evaluacion criterio, ";
                $cadenaSql.=" concurso.evaluacion_grupo grupo, jano_usuario us ";
                $cadenaSql.=" WHERE reclamacion.id=evaluacion.id_reclamacion and evaluacion.id_evaluar=ce.consecutivo_evaluar and ";
                $cadenaSql.=" ce.consecutivo_criterio=criterio.consecutivo_criterio";
                $cadenaSql.=" AND reclamacion.id=".$variable['reclamacion'];
                $cadenaSql.=" AND grupo.id=evaluacion.id_grupo AND evaluacion.estado='A'";
                break;

            case "consultarDetalleReclamacion" :
                   $cadenaSql = " SELECT reclamacion.id id_reclamacion, reclamacion.observacion observacion_reclamacion, reclamacion.fecha_registro, reclamacion.consecutivo_calendario,";
                   $cadenaSql .= " evaluacion.id_inscrito, evaluacion.id_evaluar, evaluacion.puntaje_parcial, evaluacion.observacion, ce.maximo_puntos,";
                   $cadenaSql .= " evaluacion.fecha_registro evaluacion_fecha, criterio.nombre nombre_criterio, grupo.id_evaluador, concat(us.nombre, ' ', us.apellido) AS evaluador, grupo.id id_grupo, evaluacion.id evaluacion_parcial";
                   $cadenaSql .= " FROM concurso.evaluacion_reclamacion reclamacion, concurso.evaluacion_parcial evaluacion, concurso.concurso_evaluar ce, concurso.criterio_evaluacion criterio, concurso.evaluacion_grupo grupo, jano_usuario us";
                   $cadenaSql .= " WHERE";
                   $cadenaSql .= " reclamacion.id=evaluacion.id_reclamacion";
                   $cadenaSql .= " and evaluacion.id_evaluar=ce.consecutivo_evaluar";
                   $cadenaSql .= " and ce.consecutivo_criterio=criterio.consecutivo_criterio";
                   $cadenaSql .= " AND reclamacion.id=" . $variable ['reclamacion'];
                   $cadenaSql .= " AND grupo.id=evaluacion.id_grupo";
                   $cadenaSql .= " AND concat(us.tipo_identificacion, '', us.identificacion)=grupo.id_evaluador";
                   $cadenaSql .= " AND grupo.id='" . $variable ['grupo'] . "'";
                   $cadenaSql .= " AND evaluacion.estado='A'";
                   //echo $cadenaSql;
                   break;

            case "consultaRespuestaReclamaciones" :
                    $cadenaSql = "SELECT er.id, er.observacion, er.fecha_registro, er.estado, er.consecutivo_calendario, respuesta.id AS id_respuesta, respuesta.respuesta, ";
                    $cadenaSql .= "respuesta.id_evaluar_respuesta, ep.id_evaluar ";
                    $cadenaSql .= "FROM concurso.evaluacion_reclamacion er, concurso.respuesta_reclamacion respuesta, concurso.evaluacion_parcial ep ";
                    $cadenaSql .= "WHERE respuesta.id_reclamacion=er.id ";
                    $cadenaSql .= "AND respuesta.id_evaluar_respuesta=ep.id ";
                    $cadenaSql .= "AND respuesta.estado='A' ";
                    $cadenaSql .= "AND respuesta.id_reclamacion=" . $variable ['reclamacion'];
                    $cadenaSql .= " AND respuesta.id_evaluar_respuesta=" . $variable ['evaluacion_parcial'];
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
                $cadenaSql.=" AND id_grupo=".$variable ['grupo'];
		//echo $cadenaSql;
		break;

            case "actividadesConReclamacion":
                $cadenaSql=" SELECT ";
                $cadenaSql.=" consecutivo_actividad, ";
                $cadenaSql.=" nombre ";
                $cadenaSql.=" FROM concurso.actividad_calendario";
                $cadenaSql.=" WHERE ";
                $cadenaSql.=" nombre='Evaluar Requisitos'";
                $cadenaSql.=" OR nombre='Evaluar Hoja de Vida'";
                $cadenaSql.=" OR nombre='Prueba idioma extranjero'";
                $cadenaSql.=" OR nombre='Pruebas de Competencias'";
                break;

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

            case "consultarDatosReclamacion":
                $cadenaSql=" SELECT ";
                $cadenaSql.=" fecha_registro ";
                $cadenaSql.=" FROM concurso.evaluacion_reclamacion ";
                $cadenaSql.=" WHERE ";
                $cadenaSql.=" id=".$variable['reclamacion'];
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
                                $cadenaSql.=" AND reclamacion.id='".$variable['reclamacion']."'";
				$cadenaSql.=" AND concat(us.tipo_identificacion, '', us.identificacion)=upper(respuesta.id_evaluador)";
 			 	 //echo $cadenaSql;
  			break;

			 case "reclamacionesValidacion":
  			 $cadenaSql=" SELECT";
  			 $cadenaSql.=" er.id, er.observacion, er.fecha_registro, er.estado, er.consecutivo_calendario ";
  			 $cadenaSql.=" FROM concurso.valida_requisito vr, concurso.evaluacion_reclamacion er";
  			 $cadenaSql.=" WHERE";
                         $cadenaSql.=" vr.id_reclamacion=er.id";
 			 $cadenaSql.=" AND vr.id_reclamacion=".$variable['reclamacion'];
  			 $cadenaSql.=" AND vr.consecutivo_inscrito=".$variable['consecutivo_inscrito'];
 			 	 //echo $cadenaSql;
  			break;

				case "reclamacionesILUD":
					$cadenaSql=" SELECT";
					$cadenaSql.=" er.id, er.observacion, er.fecha_registro, er.estado, er.consecutivo_calendario,";
					$cadenaSql.=" ep.id_evaluar, ce.consecutivo_criterio, criterio.nombre";
					$cadenaSql.=" FROM concurso.evaluacion_parcial ep, concurso.evaluacion_reclamacion er,";
					$cadenaSql.=" concurso.concurso_evaluar ce, concurso.criterio_evaluacion criterio";
					$cadenaSql.=" WHERE";
					$cadenaSql.=" ep.id_reclamacion=".$variable['reclamacion'];
					$cadenaSql.=" AND ep.id_inscrito=".$variable['consecutivo_inscrito'];
					$cadenaSql.=" AND ep.id_reclamacion=er.id";
					$cadenaSql.=" AND ce.consecutivo_evaluar=ep.id_evaluar";
					$cadenaSql.=" AND criterio.consecutivo_criterio=ce.consecutivo_criterio";
	  			 //echo $cadenaSql;
	   			break;

				case "reclamacionesCompetencias":
   			 $cadenaSql=" SELECT";
   			 $cadenaSql.=" er.id, er.observacion, er.fecha_registro, er.estado, er.consecutivo_calendario,";
				 $cadenaSql.=" ep.id_evaluar, ce.consecutivo_criterio, criterio.nombre";
   			 $cadenaSql.=" FROM concurso.evaluacion_parcial ep, concurso.evaluacion_reclamacion er,";
				 $cadenaSql.=" concurso.concurso_evaluar ce, concurso.criterio_evaluacion criterio";
   			 $cadenaSql.=" WHERE";
  			 $cadenaSql.=" ep.id_reclamacion=".$variable['reclamacion'];
   			 $cadenaSql.=" AND ep.id_inscrito=".$variable['consecutivo_inscrito'];
				 $cadenaSql.=" AND ep.id_reclamacion=er.id";
				 $cadenaSql.=" AND ce.consecutivo_evaluar=ep.id_evaluar";
				 $cadenaSql.=" AND criterio.consecutivo_criterio=ce.consecutivo_criterio";
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

			case "actualizaEvaluacion" :
				$cadenaSql = "UPDATE ";
				$cadenaSql .= "concurso.evaluacion_parcial ";
				$cadenaSql .= "SET ";
				$cadenaSql .= "id_reclamacion = " . $variable ["reclamacion"]." ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "id_inscrito =" . $variable ["consecutivo_inscrito"];
				$cadenaSql .= " AND id_evaluar in ";

				$cadenaSql .= "(select distinct ce.consecutivo_evaluar from ";
				$cadenaSql .= "concurso.concurso_evaluar ce, ";
				$cadenaSql .= "concurso.criterio_evaluacion criterio, ";
				$cadenaSql .= "concurso.evaluacion_parcial ep ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "ce.consecutivo_criterio=criterio.consecutivo_criterio ";
				$cadenaSql .= "and ep.id_evaluar=ce.consecutivo_evaluar ";
				$cadenaSql .= "and id_inscrito= ".$variable ["consecutivo_inscrito"];
				$cadenaSql .= " and (criterio.nombre='Prueba de Lengua Extranjera') )";
				//echo $cadenaSql;
				break;

				case "actualizaEvaluacionCompetencias" :
					$cadenaSql = "UPDATE ";
					$cadenaSql .= "concurso.evaluacion_parcial ";
					$cadenaSql .= "SET ";
					$cadenaSql .= "id_reclamacion = " . $variable ["reclamacion"]." ";
					$cadenaSql .= "WHERE ";
					$cadenaSql .= "id_inscrito =" . $variable ["consecutivo_inscrito"];
					$cadenaSql .= " AND id_evaluar in ";

					$cadenaSql .= "(select distinct ce.consecutivo_evaluar from ";
					$cadenaSql .= "concurso.concurso_evaluar ce, ";
					$cadenaSql .= "concurso.criterio_evaluacion criterio, ";
					$cadenaSql .= "concurso.evaluacion_parcial ep ";
					$cadenaSql .= "WHERE ";
					$cadenaSql .= "ce.consecutivo_criterio=criterio.consecutivo_criterio ";
					$cadenaSql .= "and ep.id_evaluar=ce.consecutivo_evaluar ";
					$cadenaSql .= "and id_inscrito= ".$variable ["consecutivo_inscrito"];
					$cadenaSql .= " and ((criterio.nombre='Prueba escrita') or (criterio.nombre='Prueba oral')))";
					break;

				case "actualizaEvaluacionHojaVida" :
					$cadenaSql = "UPDATE ";
					$cadenaSql .= "concurso.evaluacion_parcial ";
					$cadenaSql .= "SET ";
					$cadenaSql .= "id_reclamacion = " . $variable ["reclamacion"]." ";
					$cadenaSql .= "WHERE ";
					$cadenaSql .= "id_inscrito =" . $variable ["consecutivo_inscrito"];
					$cadenaSql .= " AND id_evaluar NOT IN ";

					$cadenaSql .= "(select distinct ce.consecutivo_evaluar from ";
					$cadenaSql .= "concurso.concurso_evaluar ce, ";
					$cadenaSql .= "concurso.criterio_evaluacion criterio, ";
					$cadenaSql .= "concurso.evaluacion_parcial ep ";
					$cadenaSql .= "WHERE ";
					$cadenaSql .= "ce.consecutivo_criterio=criterio.consecutivo_criterio ";
					$cadenaSql .= "and ep.id_evaluar=ce.consecutivo_evaluar ";
					$cadenaSql .= "and id_inscrito= ".$variable ["consecutivo_inscrito"];
					$cadenaSql .= " and ((criterio.nombre='Prueba escrita') or (criterio.nombre='Prueba oral') or (criterio.nombre='Prueba de Lengua Extranjera')))";
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
			 $cadenaSql.=" calendario.consecutivo_calendario, calendario.fecha_fin_reclamacion, calendario.fecha_fin_resolver, actividad.nombre, calendario.consecutivo_actividad ";
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

			case "consultarEvaluacionILUD":
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
			 $cadenaSql.=" ep.id_reclamacion, ";
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
			 $cadenaSql.=" AND ce.consecutivo_criterio in ";

			 $cadenaSql .= "(select distinct criterio.consecutivo_criterio from ";
			 $cadenaSql .= "concurso.concurso_evaluar ce, ";
			 $cadenaSql .= "concurso.criterio_evaluacion criterio, ";
			 $cadenaSql .= "concurso.evaluacion_parcial ep ";
			 $cadenaSql .= "WHERE ";
			 $cadenaSql .= "ce.consecutivo_criterio=criterio.consecutivo_criterio ";
			 $cadenaSql .= "and ep.id_evaluar=ce.consecutivo_evaluar ";
			 $cadenaSql .= "and id_inscrito= ".$variable;
			 $cadenaSql .= " and (criterio.nombre='Prueba de Lengua Extranjera') )";

			 //echo $cadenaSql;
		 break;

			 case "consultarEvaluacionCompetencias":
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
	 			$cadenaSql.=" ep.id_reclamacion, ";
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
				$cadenaSql.=" AND ce.consecutivo_criterio IN";

				$cadenaSql .= "(select distinct criterio.consecutivo_criterio from ";
				$cadenaSql .= "concurso.concurso_evaluar ce, ";
				$cadenaSql .= "concurso.criterio_evaluacion criterio, ";
				$cadenaSql .= "concurso.evaluacion_parcial ep ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "ce.consecutivo_criterio=criterio.consecutivo_criterio ";
				$cadenaSql .= "and ep.id_evaluar=ce.consecutivo_evaluar ";
				$cadenaSql .= "and id_inscrito= ".$variable;
				$cadenaSql .= " and ((criterio.nombre='Prueba escrita') or (criterio.nombre='Prueba oral')))";
	 			//echo $cadenaSql;
	 		break;

			case "consultarEvaluacionHoja":
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
			 $cadenaSql.=" ep.id_reclamacion, ";
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
			 $cadenaSql.=" AND ce.consecutivo_criterio NOT IN ";

			 $cadenaSql .= "(select distinct criterio.consecutivo_criterio from ";
			 $cadenaSql .= "concurso.concurso_evaluar ce, ";
			 $cadenaSql .= "concurso.criterio_evaluacion criterio, ";
			 $cadenaSql .= "concurso.evaluacion_parcial ep ";
			 $cadenaSql .= "WHERE ";
			 $cadenaSql .= "ce.consecutivo_criterio=criterio.consecutivo_criterio ";
			 $cadenaSql .= "and ep.id_evaluar=ce.consecutivo_evaluar ";
			 $cadenaSql .= "and id_inscrito= ".$variable;
			 $cadenaSql .= " and ((criterio.nombre='Prueba escrita') or (criterio.nombre='Prueba oral') or (criterio.nombre='Prueba de Lengua Extranjera')))";

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
                            $cadenaSql = "Select c.consecutivo_concurso,  ";
                            $cadenaSql .= "c.codigo AS codigo_concurso,  ";
                            $cadenaSql .= "c.nombre AS concurso,  ";
                            $cadenaSql .= "c.fecha_inicio,  ";
                            $cadenaSql .= "c.fecha_fin,  ";
                            $cadenaSql .= "cp.consecutivo_perfil,  ";
                            $cadenaSql .= "cp.codigo AS codigo_perfil,  ";
                            $cadenaSql .= "cp.nombre AS perfil,  ";
                            $cadenaSql .= "ci.consecutivo_inscrito,  ";
                            $cadenaSql .= "ci.estado AS estado ";
                            $cadenaSql .= " FROM concurso.concurso_inscrito ci, concurso.concurso_perfil cp, concurso.concurso c ";
                            $cadenaSql .= " WHERE ci.consecutivo_persona= ".$variable;
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
