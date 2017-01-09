<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class Sqlmenu extends sql {

    var $miConfigurador;

    function __construct() {
        $this->miConfigurador = Configurador::singleton();
    }

    function getCadenaSql($tipo, $variable = "") {
        /**
         * 1. Revisar las variables para evitar SQL Injection
         *
         */
        $prefijo = $this->miConfigurador->getVariableConfiguracion("prefijo");
        $idSesion = $this->miConfigurador->getVariableConfiguracion("id_sesion");

        switch ($tipo) {

            /**
             * Clausulas específicas
             */
            
            case "datosUsuario":
                $cadena_sql =" SELECT DISTINCT ";
                $cadena_sql.=" id_usuario, ";
                $cadena_sql.=" nombre ,";
                $cadena_sql.=" apellido ,";
                $cadena_sql.=" correo ,";
                $cadena_sql.=" imagen ,";
                $cadena_sql.=" estado ";
                $cadena_sql.=" FROM ".$prefijo."usuario";
                $cadena_sql.=" WHERE id_usuario='" . $variable . "' ";                
                break;
            
            case "datosMenus":
                $cadena_sql =" SELECT DISTINCT";
                $cadena_sql.=" mn.id_menu cod_menu,";
                //$cadena_sql.=" mn.nombre,";
                $cadena_sql.=" mn.etiqueta menu,";
                //$cadena_sql.=" mn.descripcion,";
                //$cadena_sql.=" mn.estado,";
                $cadena_sql.=" gru.id_grupo cod_grupo, ";
                //$cadena_sql.=" gru.id_menu,";
                //$cadena_sql.=" gru.nombre,";
                $cadena_sql.=" gru.etiqueta grupo,";
                //$cadena_sql.=" gru.descripcion,";
                //$cadena_sql.=" gru.estado,";
                $cadena_sql.=" gru.id_grupo_padre cod_grupoP,";
                $cadena_sql.=" gru.posicion pos,";
                //$cadena_sql.=" serv.id_subsistema,";
                //$cadena_sql.=" serv.rol_id,";
                //$cadena_sql.=" serv.id_grupo,";
                $cadena_sql.=" serv.id_enlace,";
                //$cadena_sql.=" serv.descripcion,";
                //$cadena_sql.=" serv.estado,";
                $cadena_sql.=" enl.id_enlace cod_enlace,";
                //$cadena_sql.=" enl.nombre,";
                $cadena_sql.=" enl.etiqueta enlace,";
                //$cadena_sql.=" enl.descripcion,";
                $cadena_sql.=" enl.url_host_enlace,";
                $cadena_sql.=" enl.pagina_enlace,";
                $cadena_sql.=" enl.parametros parametros";
                $cadena_sql.=" FROM ".$prefijo."menu mn";
                $cadena_sql.=" INNER JOIN ".$prefijo."grupo_menu gru ";
                $cadena_sql.="      ON mn.id_menu=gru.id_menu ";
                $cadena_sql.="      AND gru.estado=1 ";
                $cadena_sql.="      AND mn.estado=1";
                $cadena_sql.=" INNER JOIN ".$prefijo."servicio serv";
                $cadena_sql.="      ON serv.id_grupo=gru.id_grupo";
                $cadena_sql.="      AND serv.estado=1";
                $cadena_sql.=" INNER JOIN ".$prefijo."enlace enl ";
                $cadena_sql.="      ON enl.id_enlace=serv.id_enlace";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" serv.id_subsistema IN (".$variable['cod_app'].")";
                $cadena_sql.=" AND serv.rol_id IN (".$variable['cod_rol'].")";
                $cadena_sql.=" ORDER BY";
                $cadena_sql.=" mn.etiqueta,";
                $cadena_sql.=" gru.posicion,";
                $cadena_sql.=" gru.etiqueta,";
                $cadena_sql.=" enl.etiqueta";
                break;
            
            case "RolesInactivos" :
                $cadena_sql = "SELECT DISTINCT  ";
                $cadena_sql.= " perfil.id_usuario usuario, ";
                $cadena_sql.= " perfil.id_subsistema cod_app, ";
                $cadena_sql.= " perfil.rol_id cod_rol, ";
                $cadena_sql.= " rol.rol_alias rol, ";
                $cadena_sql.= " perfil.fecha_caduca fecha_caduca, ";
                $cadena_sql.= " perfil.estado estado ";
                $cadena_sql.= " FROM ".$prefijo."usuario_subsistema perfil ";
                $cadena_sql.= " INNER JOIN ".$prefijo."rol rol  ";
                $cadena_sql.= " ON rol.rol_id=perfil.rol_id  ";
                $cadena_sql.= " AND rol.estado_registro_id=1 ";
                $cadena_sql.= " WHERE ";
                $cadena_sql.= " id_usuario='" . $variable['id_usuario']  . "' ";
                if(isset($variable['tipo']) && $variable['tipo']=='inactivo')
                    {$cadena_sql.= " AND perfil.estado=0 ";}
                if(isset($variable['tipo']) && $variable['tipo']=='caduco')
                    {$cadena_sql.= " AND perfil.fecha_caduca < current_date ";}
                
                break;   
            }

        return $cadena_sql;
    }

}

?>
