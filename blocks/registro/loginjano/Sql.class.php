<?php

namespace registro\loginjano;

if (! isset ( $GLOBALS ["autorizado"] )) {
    include ("../index.php");
    exit ();
}

if (! isset ( $GLOBALS ["autorizado"] )) {
    include ("../index.php");
    exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

/**
 * IMPORTANTE: Se recomienda que no se borren registros. Utilizar mecanismos para - independiente del motor de bases de datos,
 * poder realizar rollbacks gestionados por el aplicativo.
*/

class Sql extends \Sql {

    var $miConfigurador;

    function getCadenaSql($tipo, $variable = '') {

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
			case 'cambiarClave':
                            $cadenaSql = "UPDATE ";
                            $cadenaSql .= $prefijo."usuario ";
                            $cadenaSql .= " SET ";
                            $cadenaSql .= "clave = '".trim ( $variable ["clave"] )."' ";
                            $cadenaSql .= "WHERE ";
                            $cadenaSql .= "id_usuario = '".trim ( $variable ["usuario"] )."' ";
                            break;
                        
                        case "registroLogUsuario" :

                            $cadenaSql= " INSERT INTO  ";
                            $cadenaSql.=  $prefijo."log_usuario  ";
                            $cadenaSql.= "(  ";
                            $cadenaSql.= "id_usuario,  ";
                            $cadenaSql.= "accion,  ";
                            $cadenaSql.= "id_registro,  ";
                            $cadenaSql.= "tipo_registro,  ";
                            $cadenaSql.= "nombre_registro,  ";
                            $cadenaSql.= "fecha_log,  ";
                            $cadenaSql.= "descripcion , ";
                            $cadenaSql.= "host  ";
                            $cadenaSql.= ")  ";
                            $cadenaSql.= "VALUES  ";
                            $cadenaSql.= "(  ";
                            $cadenaSql.= "'".$variable['id_usuario']."',  ";
                            $cadenaSql.= "'".$variable['accion']."',  ";
                            $cadenaSql.= "'".$variable['id_registro']."',  ";
                            $cadenaSql.= "'".$variable['tipo_registro']."',  ";
                            $cadenaSql.= "'".$variable['nombre_registro']."',  ";
                            $cadenaSql.= "'".$variable['fecha_log']."',  ";
                            $cadenaSql.= "'".$variable['descripcion']."',  ";
                            $cadenaSql.= "'".$variable['host']."'  ";
                            $cadenaSql.= ")"; 

                            break;          
                        
                        case "buscarUsuarioActivo":
                            $cadenaSql = 'SELECT ';
                            $cadenaSql .= 'id_usuario ';
                            $cadenaSql .= 'FROM ';
                            $cadenaSql .= $prefijo . 'usuario ';
                            $cadenaSql .= "WHERE ";
                            $cadenaSql .= "estado=1 ";
                            $cadenaSql .= "AND id_usuario = '" . trim ( $variable ["usuario"] ) . "' ";
                            break;
                        
                        case "buscarCorreoUsuario":
                            $cadenaSql = 'SELECT ';
                            $cadenaSql .= 'correo ';
                            $cadenaSql .= 'FROM ';
                            $cadenaSql .= $prefijo . 'usuario ';
                            $cadenaSql .= "WHERE ";
                            $cadenaSql .= "id_usuario = '" . trim ( $variable ["usuario"] ) . "' ";
                            break;
                        
                        case 'buscarPerfilActivoxUsuario':
                            $cadenaSql = "SELECT ";
                            $cadenaSql .= "count(*) AS perfiles ";
                            $cadenaSql .= "FROM ";
                            $cadenaSql .= $prefijo."usuario_subsistema ";
                            $cadenaSql .= "WHERE ";
                            $cadenaSql .= "fecha_caduca >= NOW() ";
                            $cadenaSql .= "AND estado = 1 ";
                            $cadenaSql .= "AND id_usuario = '".trim ( $variable ["usuario"] )."'";
                            break;
                        
                	case "buscarUsuario" :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_usuario, ';
				$cadenaSql .= 'nombre, ';
				$cadenaSql .= 'apellido, ';
				$cadenaSql .= 'correo, ';
				$cadenaSql .= 'telefono, ';
				$cadenaSql .= 'imagen, ';
				$cadenaSql .= 'clave, ';
				$cadenaSql .= 'tipo, ';
				$cadenaSql .= 'estilo, ';
				$cadenaSql .= 'idioma, ';
				$cadenaSql .= 'estado ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= $prefijo . 'usuario ';
				$cadenaSql .= "WHERE ";
				
                                if(isset($variable['identificacion']) && $variable['identificacion']>0)
                                    { $cadenaSql .= " identificacion='".$variable['identificacion']."'"; 
                                      $cadenaSql .= " AND tipo_identificacion='".$variable['tipo_identificacion']."'"; 
                                    }
                                elseif(isset($variable['usuario']) && $variable['usuario']!='')
                                    { $cadenaSql .= "id_usuario = '" . trim ( $variable ["usuario"] ) . "' ";
                                    } 
                                
				break;
			
			case "registrarEvento" :
				$cadenaSql = "INSERT INTO ";
				$cadenaSql .= $prefijo . "logger( ";
				$cadenaSql .= "id, ";
				$cadenaSql .= "evento, ";
				$cadenaSql .= "fecha) ";
				$cadenaSql .= "VALUES( ";
				$cadenaSql .= "'" . $variable[0] . "', ";
				$cadenaSql .= "'" . $variable[1] . "', ";
				$cadenaSql .= "'" .date('Y-m-d  h:i:s A') . "') ";

				break;

                        case "tipoIdentificacion":
				$cadenaSql = "SELECT   tipo_identificacion,  tipo_nombre ";
                                $cadenaSql .= "FROM ".$prefijo."tipo_identificacion ";
                                $cadenaSql .= " WHERE  tipo_estado = 1";
                                $cadenaSql .= " ORDER BY tipo_nombre ASC";
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
                                $cadenaSql .= " WHERE trim(rol.rol_nombre)='".$variable."'";
                                                            
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
                                $cadenaSql .= " 1, ";
                                $cadenaSql .= " '".$variable['fechaIni']."', ";
                                $cadenaSql .= " '".$variable['identificacion']."', ";
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
                    
                        case "insertarConcursante":
                            
				$cadenaSql=" INSERT INTO concurso.persona(";
                                $cadenaSql.=" consecutivo, tipo_identificacion, identificacion, nombre, apellido)";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql .= " DEFAULT, ";
                                $cadenaSql .= " '".$variable['tipo_identificacion']."', ";
                                $cadenaSql .= " '".$variable['identificacion']."', ";
                                $cadenaSql .= " '".$variable['nombres']."', ";
                                $cadenaSql .= " '".$variable['apellidos']."' ";
                                $cadenaSql .= " )";
                                
			break;                       
                    
                    

        }
        return $cadenaSql;

    }
}
?>
