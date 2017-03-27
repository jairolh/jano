<?php

namespace gestionConcursante\gestionHoja;

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

			case 'buscarPais' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'general.pais ';
				$cadenaSql .= 'ORDER BY NOMBRE; ';
				break;
			
			case 'buscarDepartamento' ://Provisionalmente solo Departamentos de Colombia
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'general.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = 112;';
				break;
               		
			case 'buscarDepartamentoAjax' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'general.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRE; ';
				break;
               		
			case 'buscarCiudad' : //Provisionalmente Solo Ciudades de Colombia sin Agrupar
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'general.ciudad ';
				$cadenaSql .= 'WHERE ';
                                $cadenaSql .= 'ab_pais = \'CO\';';
                                /*
                                if(isset($variable))
                                    {  $cadenaSql .= "id_ciudad = ' . $variable . ' ";
                                    }
                                else {$cadenaSql .= 'ab_pais = \'CO\';';}    */
				
				break;
				
			case 'buscarCiudadAjax' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRECIUDAD ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'general.ciudad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_departamento = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRECIUDAD; ';
				break;
                            
	
			case 'buscarTipoSoporte' :
				$cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" tipo_soporte,";
                                $cadenaSql.=" nombre,";
                                $cadenaSql.=" ubicacion,";
                                $cadenaSql.=" descripcion,";
                                $cadenaSql.=" estado,";
                                $cadenaSql.=" extencion_permitida";
                                $cadenaSql.=" FROM general.tipo_soporte";
                                $cadenaSql.=" WHERE ";
				$cadenaSql.=" nombre = '".$variable['tipo_soporte']."'";
                                $cadenaSql.=" AND estado='A' ";
				break;
                            
			case 'buscarSoporte' :
				$cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" sop.consecutivo_soporte,";
                                $cadenaSql.=" sop.consecutivo_persona,";
                                $cadenaSql.=" sop.tipo_dato, ";
                                $cadenaSql.=" sop.consecutivo_dato,";
                                $cadenaSql.=" sop.nombre archivo,";
                                $cadenaSql.=" sop.alias,";
                                $cadenaSql.=" tsop.tipo_soporte,";
                                $cadenaSql.=" tsop.nombre, ";
                                $cadenaSql.=" tsop.ubicacion";
                                $cadenaSql.=" FROM concurso.soporte sop";
                                $cadenaSql.=" INNER JOIN general.tipo_soporte tsop";
                                $cadenaSql.=" ON tsop.tipo_soporte=sop.tipo_soporte";
                                $cadenaSql.=" AND tsop.estado=sop.estado";
                                $cadenaSql.=" WHERE";
                                $cadenaSql.=" tsop.estado='A' ";
                                $cadenaSql.=" AND sop.tipo_dato='".$variable['tipo_dato']."'";
                                $cadenaSql.=" AND sop.consecutivo_persona='".$variable['consecutivo']."'";
                                $cadenaSql.=" AND tsop.nombre='".$variable['nombre_soporte']."'";
                                $cadenaSql.=" ORDER BY sop.consecutivo_soporte DESC ";
				break;                            
                                                        
                         case "consultarBasicos":
                             
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" bas.consecutivo,";
                                $cadenaSql.=" bas.tipo_identificacion, ";
                                $cadenaSql.=" bas.identificacion, ";
                                $cadenaSql.=" bas.nombre, ";
                                $cadenaSql.=" bas.apellido,";
                                $cadenaSql.=" bas.lugar_nacimiento, ";
                                $cadenaSql.=" bas.fecha_nacimiento, ";
                                $cadenaSql.=" bas.pais_nacimiento, ";
                                $cadenaSql.=" bas.departamento_nacimiento, ";
                                $cadenaSql.=" bas.sexo ";
                                $cadenaSql.=" FROM concurso.persona bas ";
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario us ";
                                $cadenaSql.=" ON trim(us.tipo_identificacion)=trim(bas.tipo_identificacion) ";
                                $cadenaSql.=" AND bas.identificacion=us.identificacion ";
                                $cadenaSql.=" WHERE us.id_usuario='".$variable['id_usuario']."'";
			break;

                        case "consultarContacto":
                             
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" bas.identificacion, ";
                                $cadenaSql.=" bas.nombre, ";
                                $cadenaSql.=" bas.apellido,";
                                $cadenaSql.=" (CASE WHEN cont.consecutivo_contacto IS NULL THEN 0 ELSE cont.consecutivo_contacto END ) consecutivo_contacto, ";
                                $cadenaSql.=" bas.consecutivo consecutivo_persona, ";
                                $cadenaSql.=" cont.pais_residencia, ";
                                $cadenaSql.=" cont.departamento_residencia, ";
                                $cadenaSql.=" cont.ciudad_residencia, ";
                                $cadenaSql.=" cont.direccion_residencia, ";
                                $cadenaSql.=" (CASE WHEN cont.correo IS NULL THEN us.correo ELSE cont.correo END ) correo, ";
                                $cadenaSql.=" cont.correo_secundario, ";
                                $cadenaSql.=" (CASE WHEN cont.telefono IS NULL THEN us.telefono ELSE cont.telefono END ) telefono, ";
                                $cadenaSql.=" cont.celular";
                                $cadenaSql.=" FROM concurso.persona bas ";
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario us ";
                                $cadenaSql.=" ON trim(us.tipo_identificacion)=trim(bas.tipo_identificacion) ";
                                $cadenaSql.=" AND bas.identificacion=us.identificacion ";
                                $cadenaSql.=" LEFT OUTER JOIN concurso.contacto cont ON cont.consecutivo_persona=bas.consecutivo";
                                $cadenaSql.=" WHERE us.id_usuario='".$variable['id_usuario']."'";
			break;
                                        
			case 'registroSoporte' :
				$cadenaSql=" INSERT INTO";
                                $cadenaSql.=" concurso.soporte(";
                                $cadenaSql.=" consecutivo_soporte,";
                                $cadenaSql.=" tipo_soporte, ";
                                $cadenaSql.=" consecutivo_persona, ";
                                $cadenaSql.=" tipo_dato, ";
                                $cadenaSql.=" consecutivo_dato, ";
                                $cadenaSql.=" nombre, ";
                                $cadenaSql.=" alias, ";
                                $cadenaSql.=" estado)";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['tipo_soporte']."',";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['tipo_dato']."',";
                                $cadenaSql.=" '".$variable['consecutivo_dato']."',";
                                $cadenaSql.=" '".$variable['nombre']."',";
                                $cadenaSql.=" '".$variable['alias']."',";
                                $cadenaSql.=" 'A' )";
				break;
                    
			case 'registroContacto' :
                                $cadenaSql=" INSERT INTO concurso.contacto(";
                                $cadenaSql.=" consecutivo_contacto, ";
                                $cadenaSql.=" consecutivo_persona, ";
                                $cadenaSql.=" pais_residencia,";
                                $cadenaSql.=" departamento_residencia, ";
                                $cadenaSql.=" ciudad_residencia,";
                                $cadenaSql.=" direccion_residencia,";
                                $cadenaSql.=" correo,";
                                $cadenaSql.=" correo_secundario, ";
                                $cadenaSql.=" telefono,";
                                $cadenaSql.=" celular)";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['pais_residencia']."',";
                                $cadenaSql.=" '".$variable['departamento_residencia']."',";
                                $cadenaSql.=" '".$variable['ciudad_residencia']."',";
                                $cadenaSql.=" '".$variable['direccion_residencia']."',";
                                $cadenaSql.=" '".$variable['correo']."',";
                                $cadenaSql.=" '".$variable['correo_secundario']."',";
                                $cadenaSql.=" '".$variable['telefono']."',";
                                $cadenaSql.=" '".$variable['celular']."'";
                                $cadenaSql.=" )";
				break;                    

                        case "actualizarBasicos":
                                $cadenaSql=" UPDATE concurso.persona";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" nombre='".$variable['nombre']."', ";
                                $cadenaSql.=" apellido='".$variable['apellido']."', ";
                                $cadenaSql.=" lugar_nacimiento='".$variable['lugar_nacimiento']."', ";
                                $cadenaSql.=" fecha_nacimiento='".$variable['fecha_nacimiento']."', ";
                                $cadenaSql.=" pais_nacimiento='".$variable['pais_nacimiento']."', ";
                                $cadenaSql.=" departamento_nacimiento='".$variable['departamento_nacimiento']."', ";
                                $cadenaSql.=" sexo='".$variable['sexo']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo='".$variable['consecutivo']."' ";
                    	break;  
                    
                        case "actualizarContacto":
                                $cadenaSql=" UPDATE concurso.contacto";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" pais_residencia='".$variable['pais_residencia']."',";
                                $cadenaSql.=" departamento_residencia= '".$variable['departamento_residencia']."',";
                                $cadenaSql.=" ciudad_residencia='".$variable['ciudad_residencia']."',";
                                $cadenaSql.=" direccion_residencia='".$variable['direccion_residencia']."',";
                                $cadenaSql.=" correo='".$variable['correo']."',";
                                $cadenaSql.=" correo_secundario='".$variable['correo_secundario']."',";
                                $cadenaSql.=" telefono='".$variable['telefono']."',";
                                $cadenaSql.=" celular='".$variable['celular']."'";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_contacto='".$variable['consecutivo_contacto']."' ";
                                
                    	break;                      
                    
                    /*viejas consultas para revisar*/
                       
                    
                        case "consultarLogUsuario":
				$cadenaSql = "SELECT DISTINCT id_usuario ";
                                $cadenaSql .= "FROM ".$prefijo."log_usuario ";
                                $cadenaSql .= " WHERE  id_usuario = '".$variable['id_usuario']."'";
                                
			break;

                        case "tipoIdentificacion":
				$cadenaSql = "SELECT   tipo_identificacion,  tipo_nombre ";
                                $cadenaSql .= "FROM ".$prefijo."tipo_identificacion ";
                                $cadenaSql .= " WHERE  tipo_estado = 1";
                                $cadenaSql .= " ORDER BY tipo_nombre ASC";
                            break;                    
                    
                        case "borrarUsuario":
				$cadenaSql = "DELETE FROM ".$prefijo."usuario ";
                                $cadenaSql .= " WHERE id_usuario = '".$variable['id_usuario']."' ";
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
