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

			 case "consultaConsecutivo":
 			 		$cadenaSql = "Select consecutivo ";
 					$cadenaSql .= "from concurso.persona ";
 			 		$cadenaSql .= "WHERE tipo_identificacion='".$variable['tipo_identificacion']."' ";
 					$cadenaSql .= "AND identificacion=".$variable['identificacion'];
 			 		break;

			case "consultaConcursosInscritos":
           $cadenaSql = "Select c.nombre AS concurso, cp.nombre AS perfil, ci.estado AS estado ";
					 $cadenaSql .= "from concurso.concurso_inscrito ci, concurso.concurso_perfil cp, concurso.concurso c ";
           $cadenaSql .= "WHERE ci.consecutivo_persona= ".$variable;
					 $cadenaSql .= " AND ci.consecutivo_perfil=ci.consecutivo_perfil ";
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
