<?php

namespace gestionConcursante\concursosActivos;

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

			 case "consultaMensaje":
                                $cadenaSql = "Select id, tipo, texto, estado FROM jano_texto ";
                                $cadenaSql .= "WHERE tipo='mensaje' ";
                                $cadenaSql .= "AND estado='A' ";
                            break;

			case "consultaConcursosActivos":
                                $cadenaSql = "SELECT DISTINCT  ";
                                $cadenaSql .= "conc.consecutivo_concurso, ";
                                $cadenaSql .= "conc.codigo, ";
                                $cadenaSql .= "conc.nombre,  ";
                                $cadenaSql .= "conc.descripcion,  ";
                                $cadenaSql .= "conc.estado,  ";
                                $cadenaSql .= "conc.fecha_inicio,  ";
                                $cadenaSql .= "conc.fecha_fin,  ";
                                $cadenaSql.=" cal.fecha_inicio inicio_inscripcion, ";
                                $cadenaSql.=" cal.fecha_fin  fin_inscripcion ";
                                $cadenaSql .= "FROM concurso.concurso conc ";
                                $cadenaSql.=" INNER JOIN concurso.concurso_calendario cal  ";
                                $cadenaSql.=" ON cal.consecutivo_concurso=conc.consecutivo_concurso ";
                                $cadenaSql.=" INNER JOIN concurso.actividad_calendario act  ";
                                $cadenaSql.=" ON act.consecutivo_actividad=cal.consecutivo_actividad ";
                                $cadenaSql.=" AND act.nombre IN ('Inscripción') ";
                                $cadenaSql .= "WHERE conc.estado='A' ";
                                $cadenaSql .= "AND ('".$variable['fecha_actual']."'::DATE BETWEEN conc.fecha_inicio::DATE AND conc.fecha_fin::DATE) ";
                                $cadenaSql .= "AND cal.fecha_fin::DATE >= '".$variable['fecha_actual']."'::DATE ";
                                break;

			 case "consultaPerfiles":
                                $cadenaSql = "Select ";
                                $cadenaSql .= " consecutivo_perfil, ";
                                $cadenaSql .= " consecutivo_concurso, ";
                                $cadenaSql .= " codigo, ";
                                $cadenaSql .= " nombre, ";
                                $cadenaSql .= " descripcion, ";
                                $cadenaSql .= " requisitos, ";
                                $cadenaSql .= " dependencia, ";
                                $cadenaSql .= " area, ";
                                $cadenaSql .= " vacantes, ";
                                $cadenaSql .= " estado ";
                                $cadenaSql .= "FROM concurso.concurso_perfil ";
                                $cadenaSql .= "WHERE consecutivo_concurso=".$variable['concurso']." ";
                                $cadenaSql .= "AND consecutivo_perfil not in  ";
                                $cadenaSql .= "(Select cp.consecutivo_perfil ";
                                $cadenaSql .= "from concurso.concurso_perfil cp, concurso.concurso_inscrito ci ";
                                $cadenaSql .= "WHERE consecutivo_concurso=".$variable['concurso']." ";
                                $cadenaSql .= "AND ci.consecutivo_perfil=cp.consecutivo_perfil ";
                                $cadenaSql .= "AND ci.consecutivo_persona=".$variable['usuario'].")";
                                break;
                            
			 case "consultaInscripciones":
                                $cadenaSql = "SELECT  ";
                                $cadenaSql .= " COUNT(DISTINCT cp.consecutivo_perfil) inscrito ";
                                $cadenaSql .= "FROM concurso.concurso_perfil cp, concurso.concurso_inscrito ci ";
                                $cadenaSql .= "WHERE consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                $cadenaSql .= "AND ci.consecutivo_perfil=cp.consecutivo_perfil ";
                                $cadenaSql .= "AND ci.consecutivo_persona='".$variable['usuario']."'";
                                break;                            
                            
/*
			case "consultaPerfiles":
 		 		$cadenaSql = "Select consecutivo_perfil, consecutivo_concurso, nombre, descripcion, requisitos, dependencia, area, vacantes, estado ";
 				$cadenaSql .= "from concurso.concurso_perfil ";
 		 		$cadenaSql .= "WHERE consecutivo_concurso=".$variable;
 		 		break;*/

			case "consultaPerfil":
                                $cadenaSql = "Select ";
                                $cadenaSql .= " p.consecutivo_perfil, ";
                                $cadenaSql .= " p.consecutivo_concurso, ";
                                $cadenaSql .= " p.codigo, ";
                                $cadenaSql .= " p.nombre AS perfil, ";
                                $cadenaSql .= " c.nombre AS concurso, ";
                                $cadenaSql .= " c.max_inscribe_aspirante AS max_inscribe, ";
                                $cadenaSql .= " p.descripcion, ";
                                $cadenaSql .= " p.requisitos, ";
                                $cadenaSql .= " p.dependencia, ";
                                $cadenaSql .= " p.area, ";
                                $cadenaSql .= " p.vacantes, ";
                                $cadenaSql .= " p.estado ";
                                $cadenaSql .= "from concurso.concurso_perfil p, concurso.concurso c ";
                                $cadenaSql .= "WHERE consecutivo_perfil=".$variable." ";
                                $cadenaSql .= "AND p.consecutivo_concurso=c.consecutivo_concurso";
                                break;

			case "consultaConsecutivo":
                                $cadenaSql = "Select consecutivo ";
                                $cadenaSql .= "from concurso.persona ";
                                $cadenaSql .= "WHERE tipo_identificacion='".$variable['tipo_identificacion']."' ";
                                $cadenaSql .= "AND identificacion='".$variable['identificacion']."'";
                                break;
                            
                        case "consultaCalendario":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" cal.fecha_inicio, ";
                                $cadenaSql.=" cal.fecha_fin, ";
                                $cadenaSql.=" cal.consecutivo_concurso,";
                                $cadenaSql.=" cal.consecutivo_actividad ";
                                $cadenaSql.=" FROM concurso.concurso_calendario cal ";
                                $cadenaSql.=" INNER JOIN concurso.actividad_calendario act ON act.consecutivo_actividad=cal.consecutivo_actividad ";
                                $cadenaSql.=" AND act.nombre IN ('".$variable['fase']."') ";
                                $cadenaSql.=" WHERE cal.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                $cadenaSql .= "AND ('".$variable['fecha_actual']."'::DATE BETWEEN cal.fecha_inicio::DATE AND cal.fecha_fin::DATE) ";
                                
                            break;         

			case "registrarInscripcion":
                                $cadenaSql = "INSERT INTO concurso.concurso_inscrito(consecutivo_perfil, consecutivo_persona, fecha_registro, autorizacion)";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " ".$variable['perfil'].", ";
                                $cadenaSql .= " '".$variable['consecutivo_persona']."', ";
                                $cadenaSql .= " '".$variable['fecha']."', ";
                                $cadenaSql .= " '".$variable['autorizacion']."' ";
                                $cadenaSql .= " ) ";
                                $cadenaSql .= " RETURNING consecutivo_inscrito";
                                                        //exit;
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
