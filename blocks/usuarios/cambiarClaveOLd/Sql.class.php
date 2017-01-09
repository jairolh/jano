<?php

namespace usuarios\cambiarClave;

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
                    
                        case "consultarUsuarios":
                                
				$cadenaSql = "SELECT usu.id_usuario, ";
                            	$cadenaSql .= "usu.nombre, ";
                            	$cadenaSql .= "usu.apellido, ";
                                $cadenaSql .= " usu.correo, ";
                                $cadenaSql .= " usu.telefono, ";
                                $cadenaSql .= " usu.tipo ,";
                                $cadenaSql .= " (CASE WHEN usu.tipo='0' THEN 'Anonimo' ELSE 'Conocido' END) nivel, ";
                                $cadenaSql .= " est.estado_registro_alias estado, ";
                                $cadenaSql .= " usu.identificacion, ";
                                $cadenaSql .= " usu.tipo_identificacion, ";
                                $cadenaSql .= " tiden.tipo_nombre, ";
                                $cadenaSql .= " usu.fecha_registro, ";
                                $cadenaSql .= " usu.clave  ";
                                $cadenaSql .= "FROM ".$prefijo."usuario usu ";
                                $cadenaSql .= "INNER JOIN ".$prefijo."estado_registro est ";
                                $cadenaSql .= "ON est.estado_registro_id=usu.estado ";
                                $cadenaSql .= "INNER JOIN ".$prefijo."tipo_identificacion tiden ";
                                $cadenaSql .= "ON tiden.tipo_identificacion=usu.tipo_identificacion ";
                                if(isset($variable['id_usuario']) && $variable['id_usuario']!='')
                                    { $cadenaSql .= " WHERE ";
                                      $cadenaSql .= " usu.id_usuario='".$variable['id_usuario']."'"; 
                                    }    
                                $cadenaSql .= " ORDER BY id_usuario";
			break;                       
                    
                    
			case "modificaClave" :
				 $cadenaSql = "UPDATE ";
				 $cadenaSql .= $prefijo."usuario ";
				 $cadenaSql .= "SET ";
				 $cadenaSql .= "clave='".$variable['contrasena']."', ";
				 $cadenaSql .= "estado = 1 ";
				 $cadenaSql .= "WHERE ";
				 $cadenaSql .= "id_usuario = '".$variable['id_usuario']."' ";
				break;
                            			
			case "rescatarValorSesion" :
				 $cadenaSql = "SELECT sesionid, variable, valor, expiracion FROM ".$prefijo."valor_sesion";
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
