<?php

class SesionSql {

    private $prefijoTablas;
    var $cadenaSql;

    const SESIONID = 'sesionId';
    const EXPIRACION = 'expiracion';
    const VARIABLE = 'variable';

    function __construct() {
        
    }

    function setPrefijoTablas($valor) {

        $this->prefijoTablas = $valor;
        return true;
    }

    function getCadenaSql($indice, $parametro = "") {

        $this->clausula($indice, $parametro);
        if (isset($this->cadena_sql[$indice])) {
            return $this->cadena_sql[$indice];
        }
        return false;
    }

    private function clausula($indice, $parametro) {
        $sqlDelete = 'DELETE FROM ';

        switch ($indice) {

            case "seleccionarPagina" :
                $this->cadena_sql [$indice] = "SELECT nivel  FROM " . $this->prefijoTablas . "pagina WHERE  nombre='" . $parametro . "' LIMIT 1";
                break;

            case "actualizarSesion" :

                $this->cadena_sql [$indice] = "UPDATE " . $this->prefijoTablas . "valor_sesion SET expiracion=" . $parametro [self::EXPIRACION] . " WHERE sesionid='" . $parametro [self::SESIONID] . "' ";
                break;

            case "borrarVariableSesion" :
                $this->cadena_sql [$indice] = $sqlDelete . $this->prefijoTablas . "valor_sesion  WHERE sesionid='" . $parametro [self::SESIONID] . " AND variable='" . $parametro ["dato"] . "'";
                break;

            case "borrarSesionesExpiradas" :
                $this->cadena_sql [$indice] = $sqlDelete . $this->prefijoTablas . "valor_sesion  WHERE  expiracion<" . time();
                break;

            case "borrarSesion" :
                $this->cadena_sql [$indice] = $sqlDelete . $this->prefijoTablas . "valor_sesion WHERE sesionid='" . $parametro[self::SESIONID] . "' ";
                break;

            case "borrarSesionActiva" :
                $this->cadena_sql [$indice] = $sqlDelete . $this->prefijoTablas . "valor_sesion WHERE sesionid='" . $parametro . "' ";
                break;

            case "buscarValorSesion" :
                $this->cadena_sql [$indice] = "SELECT valor, sesionid, variable, expiracion FROM " . $this->prefijoTablas . "valor_sesion WHERE sesionid ='" . $parametro [self::SESIONID] . "' AND variable='" . $parametro [self::VARIABLE] . "' ";
                break;

            case "buscarSesionActiva" :
                $this->cadena_sql [$indice] = "SELECT sesionid,valor FROM " . $this->prefijoTablas . "valor_sesion WHERE valor ='" . $parametro["usuario"] . "' AND variable='" . $parametro ["dato"] . "' ";
                break;

            case "actualizarValorSesion" :
                $this->cadena_sql [$indice] = "UPDATE " . $this->prefijoTablas . "valor_sesion SET valor='" . $parametro ["valor"] . "', expiracion='" . $parametro [self::EXPIRACION] . "' WHERE sesionid='" . $parametro [self::SESIONID] . "' AND variable='" . $parametro [self::VARIABLE] . "'";
                break;

            case "insertarValorSesion" :
                $this->cadena_sql [$indice] = "INSERT INTO " . $this->prefijoTablas . "valor_sesion ( sesionid, variable, valor, expiracion) VALUES ('" . $parametro [self::SESIONID] . "', '" . $parametro [self::VARIABLE] . "', '" . $parametro ["valor"] . "', '" . $parametro [self::EXPIRACION] . "' )";
                break;

            case "verificarNivelUsuario" :
                $this->cadena_sql [$indice] = "SELECT tipo, id_usuario FROM " . $this->prefijoTablas . "usuario WHERE id_usuario='" . $parametro . "' ";
                break;

            case "verificarRolesUsuario" :
                $this->cadena_sql [$indice] = "SELECT DISTINCT  ";
                $this->cadena_sql [$indice].= " perfil.id_usuario usuario, ";
                $this->cadena_sql [$indice].= " perfil.id_subsistema cod_app, ";
                $this->cadena_sql [$indice].= " perfil.rol_id cod_rol, ";
                $this->cadena_sql [$indice].= " rol.rol_alias rol ";
                $this->cadena_sql [$indice].= " FROM " . $this->prefijoTablas . "usuario_subsistema perfil ";
                $this->cadena_sql [$indice].= " INNER JOIN " . $this->prefijoTablas . "rol rol  ";
                $this->cadena_sql [$indice].= " ON rol.rol_id=perfil.rol_id  ";
                $this->cadena_sql [$indice].= " AND rol.estado_registro_id=1 ";
                $this->cadena_sql [$indice].= " WHERE ";
                $this->cadena_sql [$indice].= " id_usuario='" . $parametro . "' ";
                $this->cadena_sql [$indice].= " AND perfil.fecha_caduca>=current_date ";
                $this->cadena_sql [$indice].= " AND perfil.estado=1 ";

                break;

            case "verificarRolesUsuario_unico" :
                $this->cadena_sql [$indice] = "SELECT DISTINCT  ";
                $this->cadena_sql [$indice].= " perfil.id_usuario usuario, ";
                $this->cadena_sql [$indice].= " perfil.rol_id cod_rol, ";
                $this->cadena_sql [$indice].= " rol.rol_alias rol ";
                $this->cadena_sql [$indice].= " FROM " . $this->prefijoTablas . "usuario_subsistema perfil ";
                $this->cadena_sql [$indice].= " INNER JOIN " . $this->prefijoTablas . "rol rol  ";
                $this->cadena_sql [$indice].= " ON rol.rol_id=perfil.rol_id  ";
                $this->cadena_sql [$indice].= " AND rol.estado_registro_id=1 ";
                $this->cadena_sql [$indice].= " WHERE ";
                $this->cadena_sql [$indice].= " id_usuario='" . $parametro . "' ";
                $this->cadena_sql [$indice].= " AND perfil.fecha_caduca>=current_date ";
                $this->cadena_sql [$indice].= " AND perfil.estado=1 ";

                break;

            default :
        }
    }

}

?>