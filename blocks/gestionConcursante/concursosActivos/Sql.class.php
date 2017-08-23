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
           $cadenaSql = "Select consecutivo_concurso, nombre, descripcion, estado, fecha_inicio, fecha_fin from concurso.concurso ";
           $cadenaSql .= "WHERE estado='A' ";
					 $cadenaSql .= "AND '".$variable['fecha_actual']."'::DATE BETWEEN fecha_inicio::DATE ";
					 $cadenaSql .= "AND fecha_fin::DATE";
           break;

			 case "consultaPerfiles":
			 		$cadenaSql = "Select consecutivo_perfil, consecutivo_concurso, nombre, descripcion, requisitos, dependencia, area, vacantes, estado ";
					$cadenaSql .= "from concurso.concurso_perfil ";
			 		$cadenaSql .= "WHERE consecutivo_concurso=".$variable['concurso']." ";
					$cadenaSql .= "AND consecutivo_perfil not in  ";

					$cadenaSql .= "(Select cp.consecutivo_perfil ";
					$cadenaSql .= "from concurso.concurso_perfil cp, concurso.concurso_inscrito ci ";
					$cadenaSql .= "WHERE consecutivo_concurso=".$variable['concurso']." ";
					$cadenaSql .= "AND ci.consecutivo_perfil=cp.consecutivo_perfil ";
					$cadenaSql .= "AND ci.consecutivo_persona=".$variable['usuario'].")";
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
					$cadenaSql .= "AND identificacion='".$variable['identificacion']."'";
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
