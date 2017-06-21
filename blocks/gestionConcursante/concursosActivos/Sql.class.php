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

			case "consultaConcursosActivos":
           $cadenaSql = "Select consecutivo_concurso, nombre, descripcion, estado, fecha_inicio, fecha_fin from concurso.concurso ";
           $cadenaSql .= "WHERE estado='A' ";
					 $cadenaSql .= "AND '".$variable['fecha_actual']."'::DATE BETWEEN fecha_inicio::DATE ";
					 $cadenaSql .= "AND fecha_fin::DATE";
           break;

		 case "consultaPerfiles":
		 		$cadenaSql = "Select consecutivo_perfil, consecutivo_concurso, nombre, descripcion, requisitos, dependencia, area, vacantes, estado ";
				$cadenaSql .= "from concurso.concurso_perfil ";
		 		$cadenaSql .= "WHERE consecutivo_concurso=".$variable;
		 		break;

			case "consultaPerfil":
			 		$cadenaSql = "Select p.consecutivo_perfil, p.consecutivo_concurso, p.nombre AS perfil, c.nombre AS concurso, p.descripcion, p.requisitos, p.dependencia, p.area, p.vacantes, p.estado ";
					$cadenaSql .= "from concurso.concurso_perfil p, concurso.concurso c ";
			 		$cadenaSql .= "WHERE consecutivo_perfil=".$variable." ";
					$cadenaSql .= "AND p.consecutivo_concurso=c.consecutivo_concurso";
			 		break;

			case "consultaConsecutivo":
			 		$cadenaSql = "Select consecutivo ";
					$cadenaSql .= "from concurso.persona ";
			 		$cadenaSql .= "WHERE tipo_identificacion='".$variable['tipo_identificacion']."' ";
					$cadenaSql .= "AND identificacion=".$variable['identificacion'];

			 		break;


			case "registrarInscripcion":
      	$cadenaSql = "INSERT INTO concurso.concurso_inscrito(consecutivo_perfil, consecutivo_persona, fecha_registro)";
      	$cadenaSql .= " VALUES ( ";
     		$cadenaSql .= " ".$variable['perfil'].", ";
      	$cadenaSql .= " '".$variable['consecutivo_persona']."', ";
				$cadenaSql .= " '".$variable['fecha']."' ";
      	$cadenaSql .= " ) ";
      	$cadenaSql .= " RETURNING consecutivo_inscrito";

       	break;






			 case "consultaUsuariosTipoJurado":
				 $cadenaSql=" SELECT u.id_usuario AS id_usuario, concat( u.nombre, ' ', u.apellido) AS nombre, id_jurado_tipo AS id_tipo, jt.nombre AS tipo_jurado, cj.estado AS estado";
				 $cadenaSql.=" FROM jano_usuario_subsistema s, jano_usuario u, concurso.jurado cj, concurso.jurado_tipo jt";
				 $cadenaSql.=" WHERE rol_id=3";//rol de jurado
				 $cadenaSql.=" AND u.id_usuario=s.id_usuario";
				 $cadenaSql.=" AND cj.id_usuario=u.id_usuario";
				 $cadenaSql.=" AND jt.id=cj.id_jurado_tipo";
				 $cadenaSql.=" AND cj.id_jurado_tipo=".$variable;
				 break;

			case "consultaUsuariosJurado":
					$cadenaSql = "SELECT u.id_usuario, concat( u.nombre, ' ', u.apellido) AS nombre  ";
					$cadenaSql .= "FROM jano_usuario_subsistema s, jano_usuario u ";
					$cadenaSql .= "WHERE rol_id=3 ";//rol de jurado
					$cadenaSql .= "AND u.id_usuario=s.id_usuario ";
					$cadenaSql .= "AND u.id_usuario not in";
					$cadenaSql .= "(SELECT j.id_usuario from concurso.jurado j where j.id_jurado_tipo=".$variable.") ";
					$cadenaSql .= "order by nombre";
					break;

			case "consultaTiposJuradoId":
          $cadenaSql = "Select id, nombre, descripcion, estado from concurso.jurado_tipo ";
          $cadenaSql .= "WHERE id= ".$variable;
         	break;

			case "consultaCriteriosEvaluacion":
        	$cadenaSql = "Select consecutivo_criterio, nombre, estado from concurso.criterio_evaluacion ";
					$cadenaSql .= "WHERE consecutivo_criterio not in";
					$cadenaSql .= "(select id_criterio from concurso.jurado_criterio where id_jurado_tipo=".$variable.") ";
					$cadenaSql .= "order by nombre";
        	break;

    	case "consultarCriteriosTipoJurado":
				$cadenaSql=" SELECT ";
				$cadenaSql.=" jt.id AS id_tipo, ";
				$cadenaSql.=" jt.nombre AS tipo, ";
				$cadenaSql.=" ce.consecutivo_criterio AS id_criterio, ";
				$cadenaSql.=" ce.nombre AS criterio, ";
				$cadenaSql.=" jc.estado ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" concurso.jurado_criterio jc, ";
				$cadenaSql.=" concurso.jurado_tipo jt, ";
				$cadenaSql.=" concurso.criterio_evaluacion ce ";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" jc.id_jurado_tipo=".$variable['id_jurado_tipo'];
				$cadenaSql.=" and jc.id_jurado_tipo=jt.id ";
				$cadenaSql.=" and jc.id_criterio=ce.consecutivo_criterio ";
				$cadenaSql.=" ORDER BY ce.nombre ";
        break;

    	case "registrarCriterioTipoJurado":
      	$cadenaSql = "INSERT INTO concurso.jurado_criterio(id_jurado_tipo, id_criterio)";
      	$cadenaSql .= " VALUES ( ";
     		$cadenaSql .= " '".$variable['tipo_jurado']."', ";
      	$cadenaSql .= " '".$variable['criterio_evaluacion']."' ";
      	$cadenaSql .= " ) ";
      	$cadenaSql .= " RETURNING id";
       	break;

	   	case "registrarUsuarioTipoJurado":
      	$cadenaSql = "INSERT INTO concurso.jurado(id_jurado_tipo, id_usuario)";
      	$cadenaSql .= " VALUES ( ";
      	$cadenaSql .= " '".$variable['tipo_jurado']."', ";
      	$cadenaSql .= " '".$variable['usuario_jurado']."' ";
     		$cadenaSql .= " ) ";
      	$cadenaSql .= " RETURNING id";
      	break;

     	case "registrarTipoJurado":
     		$cadenaSql = "INSERT INTO concurso.jurado_tipo(nombre, descripcion)";
     		$cadenaSql .= " VALUES ( ";
     		$cadenaSql .= " '".$variable['nombre']."', ";
     		$cadenaSql .= " '".$variable['descripcion']."' ";
     		$cadenaSql .= " ) ";
     		$cadenaSql .= " RETURNING id";
     		break;

			case "cambiarEstadoJurado":
          $cadenaSql = "UPDATE concurso.jurado SET ";
         	$cadenaSql .= " estado = '".$variable['estado']."'";
          $cadenaSql .= " WHERE id_jurado_tipo = ".$variable['id_tipo_jurado']."";
					$cadenaSql .= " AND id_usuario = '".$variable['id_usuario']."'";
          $cadenaSql .= " RETURNING id";
					break;

			case "cambiarEstadoTipoJurado":
				$cadenaSql = "UPDATE concurso.jurado_tipo SET ";
				$cadenaSql .= " estado = '".$variable['estado']."'";
				$cadenaSql .= " WHERE id = '".$variable['id_tipoJurado']."' ";
				$cadenaSql .= " RETURNING id";
				break;

			case "cambiarEstadoCriterioTipoJurado":
				$cadenaSql = "UPDATE concurso.jurado_criterio SET ";
				$cadenaSql .= " estado = '".$variable['estado']."'";
				$cadenaSql .= " WHERE id_jurado_tipo = ".$variable['id_tipo']." ";
				$cadenaSql .= " AND id_criterio = ".$variable['id_criterio']." ";
				$cadenaSql .= " RETURNING id";
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
