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
                    
                    
                    
                    
                    /*viejas consultas para revisar*/
                        case "consultarUsuarios":
                            
				$cadenaSql = "SELECT DISTINCT ";
                            	$cadenaSql .= " usu.id_usuario, ";
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
                                $cadenaSql .= " usu.fecha_registro  ";
                                $cadenaSql .= "FROM ".$prefijo."usuario usu ";
                                $cadenaSql .= "INNER JOIN ".$prefijo."estado_registro est ";
                                $cadenaSql .= "ON est.estado_registro_id=usu.estado ";
                                $cadenaSql .= "INNER JOIN ".$prefijo."tipo_identificacion tiden ";
                                $cadenaSql .= "ON tiden.tipo_identificacion=usu.tipo_identificacion ";
                                
                                if(isset($variable['tipoAdm']) && $variable['tipoAdm']=='subsistema')
                                    {  $cadenaSql .= "INNER JOIN ".$prefijo."usuario_subsistema mod ON mod.id_usuario=usu.id_usuario ";
                                       $cadenaSql .= " AND mod.rol_id NOT IN (0) "; 
                                    }
                                if(isset($variable['identificacion']) && $variable['identificacion']>0)
                                    { $cadenaSql .= " WHERE ";
                                      $cadenaSql .= " usu.identificacion='".$variable['identificacion']."'"; 
                                      $cadenaSql .= " AND usu.tipo_identificacion='".$variable['tipo_identificacion']."'"; 
                                    }
                                elseif(isset($variable['id_usuario']) && $variable['id_usuario']!='')
                                    { $cadenaSql .= " WHERE ";
                                      $cadenaSql .= " usu.id_usuario='".$variable['id_usuario']."'"; 
                                    }    
                                $cadenaSql .= " ORDER BY id_usuario";
			break;
                        
                        case "consultarPerfilUsuario":

                            	$cadenaSql = "SELECT DISTINCT ";
                            	$cadenaSql .= " sist.id_usuario,  ";
                            	if(!isset($variable['tipo']))
                                    { $cadenaSql .= "sist.id_subsistema, ";
                                      $cadenaSql .= "mod.etiketa subsistema, ";
                                      $cadenaSql .= "sist.fecha_registro,  ";
                            	      $cadenaSql .= "sist.fecha_caduca,  ";
                                    }
                                //$cadenaSql .= "sist.id_subsistema, ";
                            	//$cadenaSql .= "mod.etiketa subsistema, ";
                            	$cadenaSql .= "sist.rol_id, ";
                            	$cadenaSql .= "rol.rol_alias , ";
                            	//$cadenaSql .= "sist.fecha_registro,  ";
                            	//$cadenaSql .= "sist.fecha_caduca,  ";
                            	$cadenaSql .= "est.estado_registro_alias estado  ";
                            	$cadenaSql .= "FROM ".$prefijo."usuario_subsistema sist ";
                            	$cadenaSql .= "INNER JOIN ".$prefijo."subsistema mod ON mod.id_subsistema=sist.id_subsistema ";
                                $cadenaSql .= "INNER JOIN ".$prefijo."rol rol ON rol.rol_id=sist.rol_id ";
                                $cadenaSql .= "INNER JOIN ".$prefijo."estado_registro est ";
                                $cadenaSql .= "ON est.estado_registro_id=sist.estado ";
                            	$cadenaSql .= "WHERE sist.id_usuario='".$variable['id_usuario']."'";
                                if(isset($variable['subsistema']) && $variable['subsistema']>0)
                                      { $cadenaSql .= " AND ";
                                        $cadenaSql .= " sist.id_subsistema='".$variable['subsistema']."' "; }
                                  if(isset($variable['rol_id']))
                                      {$cadenaSql .= " AND rol.rol_id ='".$variable['rol_id']."'"; }
                                  if(isset($variable['tipo']) && $variable['tipo']=='unico')
                                        {$cadenaSql.= " AND sist.estado=1 "; }    
                                $cadenaSql .= " ORDER BY rol.rol_alias";
			break;
                    
                        case "consultarUsuariosEditar":
                            
				$cadenaSql = "SELECT id_usuario, nombre, apellido, correo, telefono, tipo, identificacion ";
                                $cadenaSql .= "FROM ".$prefijo."usuario ";
                                $cadenaSql .= " WHERE  id_usuario = '".$variable."'";
			break;
                    
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
                    
                        case "tipoUsuario":
				$cadenaSql = "SELECT  idtipo, descripcion ";
                                $cadenaSql .= "FROM ".$prefijo."tipousuario ";
                                $cadenaSql .= " WHERE  idtipo != 1";
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
                                elseif($tam==1 && $variable[0]==1 )
                                    { $cadenaSql .= " > 0 ";
                                    }
                                else{ $cadenaSql .= " = ".$variable[0];
                                    }    
                               $cadenaSql .= " ORDER BY  etiketa ";
                                
                            break;

                        case "consultaPerfiles":
				$cadenaSql = " SELECT DISTINCT rol.\"rol_id\", ";
                                $cadenaSql .= " rol.\"rol_alias\" ";
                                $cadenaSql .= "  FROM ".$prefijo."rol rol ";
                                    $cadenaSql .= "INNER JOIN  ".$prefijo."rol_subsistema sub  ";
                                    $cadenaSql .= "ON rol.\"rol_id\"=sub.\"rol_id\"  ";
                                $cadenaSql .= " WHERE ";
                                $cadenaSql .= "sub.estado='1' ";
                                    
                            if(isset($variable['subsistema']) && $variable['subsistema']>0)
                                { $cadenaSql .= " AND ";
                                  $cadenaSql .= " sub.id_subsistema='".$variable['subsistema']."' "; 
                                  if(isset($variable['roles']))
                                      {$cadenaSql .= " AND rol.rol_id NOT IN (".$variable['roles'].")"; 
                                  }
                                }
                            break;
                    
                        case "insertarUsuario":
                            
				$cadenaSql = "INSERT INTO ".$prefijo."usuario(id_usuario, nombre, apellido, correo, telefono, imagen, clave, tipo, estilo, idioma, estado, fecha_registro, identificacion,tipo_identificacion) ";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " '".$variable['id_usuario']."', ";
                                $cadenaSql .= " '".$variable['nombres']."', ";
                                $cadenaSql .= " '".$variable['apellidos']."', ";
                                $cadenaSql .= " '".$variable['correo']."', ";
                                $cadenaSql .= " '".$variable['telefono']."', ";
                                $cadenaSql .= " 'N/A', ";
                                $cadenaSql .= " '".$variable['password']."', ";
                                $cadenaSql .= " '1', ";
                                $cadenaSql .= " 'basico', ";
                                $cadenaSql .= " 'es_es', ";
                                $cadenaSql .= " 2, ";
                                $cadenaSql .= " '".$variable['fechaIni']."', ";
                                $cadenaSql .= " ".$variable['identificacion'].", ";
                                $cadenaSql .= " '".$variable['tipo_identificacion']."' ";
                                $cadenaSql .= " )";
                                
			break;

                        case "insertarPerfilUsuario":
                            
				$cadenaSql = "INSERT INTO ".$prefijo."usuario_subsistema(id_usuario, id_subsistema, rol_id, fecha_registro, fecha_caduca, estado) ";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " '".$variable['id_usuario']."', ";
                                $cadenaSql .= " '".$variable['subsistema']."', ";
                                $cadenaSql .= " '".$variable['perfil']."', ";
                                $cadenaSql .= " '".$variable['fechaIni']."', ";
                                $cadenaSql .= " '".$variable['fechaFin']."', ";
                                $cadenaSql .= " '1'";
                                $cadenaSql .= " )";
                                
			break;                    
                    
                        case "actualizarUsuario":
                            
				$cadenaSql = "UPDATE ".$prefijo."usuario SET ";
                                $cadenaSql .= " nombre = '".$variable['nombres']."', ";
                                $cadenaSql .= " apellido = '".$variable['apellidos']."', ";
                                $cadenaSql .= " correo = '".$variable['correo']."', ";
                                $cadenaSql .= " telefono = '".$variable['telefono']."' ";
                                $cadenaSql .= " WHERE id_usuario = '".$variable['id_usuario']."' ";
			break;
                    
                        case "CambiarEstadoUsuario":
                            
				$cadenaSql = "UPDATE ".$prefijo."usuario SET ";
                                $cadenaSql .= " estado = '".$variable['estado']."'";
                                $cadenaSql .= " WHERE id_usuario = '".$variable['usuario']."' ";
			break;
                    
                        case "editarPerfilUsuario":
                            
				$cadenaSql = "UPDATE ".$prefijo."usuario_subsistema SET ";
                                $cadenaSql .= " estado = '".$variable['estado']."' , ";
                                $cadenaSql .= " fecha_caduca = '".$variable['fechaFin']."'";
                                $cadenaSql .= " WHERE id_usuario = '".$variable['id_usuario']."' ";
                                $cadenaSql .= " AND id_subsistema = '".$variable['id_subsistema']."' ";
                                $cadenaSql .= " AND rol_id = '".$variable['rol_id']."' ";
			break;      

                        case "CambiarEstadoPerfil":
                            
				$cadenaSql = "UPDATE ".$prefijo."usuario_subsistema SET ";
                                $cadenaSql .= " estado = '".$variable['estado']."'";
                                $cadenaSql .= " WHERE id_usuario = '".$variable['id_usuario']."' ";
                                $cadenaSql .= " AND id_subsistema = '".$variable['id_subsistema']."' ";
                                $cadenaSql .= " AND rol_id = '".$variable['rol_id']."' ";
			break;      
                    
                        case "borrarPerfil":
				$cadenaSql = "DELETE FROM ".$prefijo."usuario_subsistema ";
                                $cadenaSql .= " WHERE id_usuario = '".$variable['id_usuario']."' ";
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
