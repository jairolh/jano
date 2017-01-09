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
                        
                        case "buscarUsuarioActivo":
                            $cadenaSql = 'SELECT ';
                            $cadenaSql .= 'id_usuario ';
                            $cadenaSql .= 'FROM ';
                            $cadenaSql .= $prefijo . 'usuario ';
                            $cadenaSql .= "WHERE ";
                            $cadenaSql .= "estado=1 ";
                            $cadenaSql .= "AND id_usuario = '" . trim ( $variable ["usuario"] ) . "' ";
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
				$cadenaSql .= "id_usuario = '" . trim ( $variable ["usuario"] ) . "' ";
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

        }

        return $cadenaSql;

    }
}
?>
