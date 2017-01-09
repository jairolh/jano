<?php

namespace usuarios\gestionRoles;

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
                        case "idPerfil":

				$cadenaSql = "SELECT  MAX(rol_id) rol_id FROM ".$prefijo."rol rol ";
			break;
                        case "idSubsistema":
				$cadenaSql = "SELECT  MAX(id_subsistema) id_subsistema FROM ".$prefijo."subsistema ";
			break;
                        case "consultaPerfilesSistema":
                            
				$cadenaSql = " SELECT DISTINCT ";
                                $cadenaSql .= " sub.id_subsistema, ";
                                $cadenaSql .= " sub.nombre, ";
                                $cadenaSql .= " sub.etiketa, ";
                                $cadenaSql .= " sub.id_pagina, ";
                                $cadenaSql .= " sub.observacion, ";
                                $cadenaSql .= " rol.rol_id, ";
                                $cadenaSql .= " rol.rol_nombre, ";
                                $cadenaSql .= " rol.rol_alias, ";
                                $cadenaSql .= " rol.rol_descripcion, ";
                                $cadenaSql .= "est.estado_registro_alias estado,  ";
                                $cadenaSql .= " rol.rol_fecha_registro ";
                                $cadenaSql .= " FROM ".$prefijo."rol rol  ";
                                $cadenaSql .= " INNER JOIN ".$prefijo."rol_subsistema rolSub ON rolSub.rol_id=rol.rol_id ";
                                $cadenaSql .= " INNER JOIN ".$prefijo."subsistema sub ON sub.id_subsistema=rolSub.id_subsistema  ";
                                $cadenaSql .= " INNER JOIN ".$prefijo."estado_registro est ON est.estado_registro_id=rolSub.estado ";
                                if(isset($variable['subsistema']) && isset($variable['rol_id']) )
                                   {
                                    $cadenaSql .= " WHERE rolSub.rol_id ='".$variable['rol_id']."' "; 
                                    $cadenaSql .= " AND  rolSub.id_subsistema='".$variable['subsistema']."' "; 
                                    }
                                                            
                            break;
                        
                        case "subsistema":
                                $tam= count($variable);
                                $aux=1;
				$cadenaSql = "SELECT  id_subsistema, etiketa ";
                                $cadenaSql .= "FROM ".$prefijo."subsistema ";
                                $cadenaSql .= "WHERE  ";
                                $cadenaSql .= " id_subsistema ";
                                if($tam>1)
                                    { $cadenaSql .= " IN ( ";
                                        foreach ($variable as $value) 
                                            {  $cadenaSql .= $value;
                                               $aux<$tam?$cadenaSql .= ',':'';
                                               $aux++;
                                            }
                                      $cadenaSql .= " ) ";
                                    }
                                elseif($tam==1)
                                    { $cadenaSql .= " > 0 ";
                                    }
                                $cadenaSql .= "ORDER BY  etiketa ";
                                
                            break;
                        
                        case "pagina":
                                $cadenaSql = "SELECT  id_pagina, nombre ";
                                $cadenaSql .= "FROM ".$prefijo."pagina ";
                                $cadenaSql .= "WHERE  ";
                                $cadenaSql .= " id_pagina > 0 ";
                                $cadenaSql .= "ORDER BY  nombre ";
                            break;
                            
                        case "consultaPerfiles":
				$cadenaSql = " SELECT DISTINCT ";                                
                                $cadenaSql .= " rol.rol_id, ";
                                $cadenaSql .= " rol.rol_alias ";
                                $cadenaSql .= " FROM ".$prefijo."rol rol  ";
                                if(isset($variable['subsistema']) && $variable['subsistema']>0)
                                   {
                                    $cadenaSql .= " WHERE rol.rol_id  ";
                                    $cadenaSql .= " NOT IN (SELECT DISTINCT  ";
                                        $cadenaSql .= " sub.rol_id  ";
                                        $cadenaSql .= " FROM ".$prefijo."rol_subsistema sub  ";
                                         $cadenaSql .= " WHERE ";
                                              $cadenaSql .= " sub.id_subsistema='".$variable['subsistema']."' "; 

                                        $cadenaSql .= " )";
                                    }
                            break;
                            
                        case "consultarPerfilUsuario":

                            	$cadenaSql = "SELECT sist.id_usuario,  ";
                            	$cadenaSql .= "sist.id_subsistema, ";
                            	$cadenaSql .= "mod.etiketa subsistema, ";
                            	$cadenaSql .= "sist.rol_id, ";
                            	$cadenaSql .= "rol.rol_alias , ";
                            	$cadenaSql .= "sist.fecha_registro,  ";
                            	$cadenaSql .= "sist.fecha_caduca,  ";
                            	$cadenaSql .= "est.estado_registro_alias estado  ";
                            	$cadenaSql .= "FROM ".$prefijo."usuario_subsistema sist ";
                            	$cadenaSql .= "INNER JOIN ".$prefijo."subsistema mod ON mod.id_subsistema=sist.id_subsistema ";
                                $cadenaSql .= "INNER JOIN ".$prefijo."rol rol ON rol.rol_id=sist.rol_id ";
                                $cadenaSql .= "INNER JOIN ".$prefijo."estado_registro est ";
                                $cadenaSql .= "ON est.estado_registro_id=sist.estado ";
                            	$cadenaSql .= "WHERE sist.id_subsistema='".$variable['id_subsistema']."'";
                                $cadenaSql .= " AND sist.rol_id ='".$variable['rol_id']."'"; 
                                $cadenaSql .= " ORDER BY rol.rol_alias";
			break;                            
                            
                        case "insertarSubsistema":
                            
				$cadenaSql = "INSERT INTO ".$prefijo."subsistema(id_subsistema, nombre, etiketa, id_pagina, observacion) ";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " '".$variable['id_subsistema']."', ";
                                $cadenaSql .= " '".$variable['nombre']."', ";
                                $cadenaSql .= " '".$variable['etiqueta']."', ";
                                $cadenaSql .= " '".$variable['pagina']."', ";
                                $cadenaSql .= " '".$variable['descripcion']."' ";
                                $cadenaSql .= " )";
                        break;                    
                            
                        case "insertarRol":
                            
				$cadenaSql = "INSERT INTO ".$prefijo."rol (rol_id, rol_nombre, rol_alias, rol_descripcion, estado_registro_id,rol_fecha_registro) ";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " '".$variable['rol_id']."', ";
                                $cadenaSql .= " '".$variable['rol_nombre']."', ";
                                $cadenaSql .= " '".$variable['rol_alias']."', ";
                                $cadenaSql .= " '".$variable['rol_descripcion']."', ";
                                $cadenaSql .= " '".$variable['rol_estado']."', ";
                                $cadenaSql .= " '".$variable['rol_fechaIni']."' ";
                                $cadenaSql .= " )";
                        break;                    
                        
                            
                        case "insertarRolSubsistema":
                            
				$cadenaSql = "INSERT INTO ".$prefijo."rol_subsistema(rol_id, id_subsistema,estado) ";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " '".$variable['rol_id']."', ";
                                $cadenaSql .= " '".$variable['subsistema']."',";
                                $cadenaSql .= " '1'";
                                $cadenaSql .= " )";
                                
			break;   
                    
                        case "CambiarEstadoRol":
                            
				$cadenaSql = "UPDATE ".$prefijo."rol_subsistema SET ";
                                $cadenaSql .= " estado = '".$variable['estado']."'";
                                $cadenaSql .= " WHERE id_subsistema = '".$variable['id_subsistema']."' ";
                                $cadenaSql .= " AND rol_id = '".$variable['rol_id']."' ";
			break; 
                    
                        case "EditarSubsistema":
                                $cadenaSql = "UPDATE ".$prefijo."subsistema ";
                                $cadenaSql .= " SET ";
				$cadenaSql .= "nombre='".$variable['nombreSub']."',";
                                $cadenaSql .= "etiketa='".$variable['etiqueta']."',";
                                $cadenaSql .= "id_pagina='".$variable['pagina']."',";
                                $cadenaSql .= "observacion='".$variable['descripcionSub']."'";
                                $cadenaSql .= " WHERE  ";
                                $cadenaSql .= " id_subsistema = '".$variable['id_subsistema']."' ";
                        break;                    
                            
                        case "EditarRol":
                            
                                $cadenaSql = "UPDATE ".$prefijo."rol ";
                                $cadenaSql .= " SET ";
                                $cadenaSql .= "rol_nombre='".$variable['rol_nombre']."',";
                                $cadenaSql .= "rol_alias='".$variable['rol_alias']."',";
                                $cadenaSql .= "rol_descripcion='".$variable['rol_descripcion']."'";
                                $cadenaSql .= " WHERE ";
                                $cadenaSql .= " rol_id = '".$variable['rol_id']."' ";
                        break; 
                    
                        case "borrarRol":
				$cadenaSql = "DELETE FROM ".$prefijo."rol_subsistema ";
                                $cadenaSql .= " WHERE id_subsistema = '".$variable['id_subsistema']."' ";
                                $cadenaSql .= " AND rol_id = '".$variable['rol_id']."' ";
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
