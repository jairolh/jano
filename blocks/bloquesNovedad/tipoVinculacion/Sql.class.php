<?php

namespace bloquesParametro\tipoVinculacion;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
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
        $prefijo = $this->miConfigurador->getVariableConfiguracion("prefijo");
        $idSesion = $this->miConfigurador->getVariableConfiguracion("id_sesion");
        $cadenaSql = '';
        switch ($tipo) {

            /**
             * Clausulas específicas
             */
            case 'modificarRegistro' :
                $cadenaSql = 'UPDATE ';
                $cadenaSql .= 'parametro.tipo_vinculacion ';
                $cadenaSql .= 'SET ';

                $cadenaSql .= 'descripcion= ';
                $cadenaSql .= "'" . $variable ['descripcion'] . "', ";

                $cadenaSql .= 'tipo_liquidacion= ';
                $cadenaSql .= "'" . $variable ['tipoLiquidacion'] . "', ";

                if ($variable ['naturaleza'] != '') {
                    $cadenaSql .= 'naturaleza = ';
                    $cadenaSql .= "'" . $variable ['naturaleza'] . "', ";
                }
                $cadenaSql .= 'nombre = ';
                $cadenaSql .= "'" . $variable ['nombre'] . "'";

                $cadenaSql .= ' WHERE ';
                $cadenaSql .= 'id = ';
                $cadenaSql .= $variable ['id'] . ';';
                break;

            case 'inactivarRegistro' :
                $cadenaSql = 'UPDATE ';
                $cadenaSql .= 'parametro.tipo_vinculacion ';
                $cadenaSql .= 'SET ';
                $cadenaSql .= 'estado = ';
                $cadenaSql .= "'" . $variable ['estadoRegistro'] . "' ";
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ';
                $cadenaSql .= $variable ['codigoRegistro'] . ";";
                break;
            case 'buscarTipoVinculacion':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id as ID, ';
                $cadenaSql .= 'nombre as NOMBRE, ';
                $cadenaSql .= 'descripcion as DESCRIPCION, ';
                $cadenaSql .= 'naturaleza as NATURALEZA, ';
                $cadenaSql .= 'tipo_liquidacion as LIQUIDACION, ';

                $cadenaSql .= 'estado as ESTADO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.tipo_vinculacion';
                break;
            case 'buscarLey' ://Provisionalmente solo Departamentos de Colombia

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_ldn as ID, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.ley_decreto_norma ';

                break;
            case 'buscarRubro' ://Provisionalmente solo Departamentos de Colombia

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'rub_identificador as ID, ';
                $cadenaSql .= 'rub_nombre_rubro as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'otro.rubro ';

                break;
            case "registrarTipoVinculacion" :
                $cadenaSql = " INSERT INTO parametro.tipo_vinculacion";
                $cadenaSql.=" (";

                $cadenaSql.=" nombre,";
                $cadenaSql.=" descripcion,";
                $cadenaSql.=" naturaleza,";

                $cadenaSql.=" tipo_liquidacion,";
                $cadenaSql.=" estado";
                $cadenaSql.=" )";
                $cadenaSql.=" VALUES";
                $cadenaSql.=" (";

                $cadenaSql.=" '" . $variable['nombre'] . "',";
                $cadenaSql.=" '" . $variable['descripcion'] . "',";
                $cadenaSql.=" '" . $variable['naturaleza'] . "',";

                $cadenaSql.=" '" . $variable['tipoLiquidacion'] . "',";
                $cadenaSql.=" 'Activo'";
                $cadenaSql.=" ) ";
                $cadenaSql .= "RETURNING  id; ";
                break;

            case 'insertarLeyesTipoVinculacion' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'parametro.ldnxtipo_vinculacion';
                $cadenaSql .= '( ';
                $cadenaSql .= 'id_ldn,';
                $cadenaSql .= 'id';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $variable ['id_ley'] . ', ';
                $cadenaSql .= $variable ['tipo_vinculacion'];
                $cadenaSql .= '); ';
                break;
            case 'insertarRubrosTipoVinculacion' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'parametro.rubro_tipo_vinculacion';
                $cadenaSql .= '( ';
                $cadenaSql .= 'rub_identificador,';
                $cadenaSql .= 'id';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $variable ['id_rubro'] . ', ';
                $cadenaSql .= $variable ['tipo_vinculacion'];
                $cadenaSql .= '); ';
                break;

            case 'eliminarLeyesModificar' :
                $cadenaSql = 'DELETE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.ldnxtipo_vinculacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ' . $variable ['tipo_vinculacion'] . ';';
                break;

            case 'eliminarRubrosModificar' :
                $cadenaSql = 'DELETE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.rubro_tipo_vinculacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ' . $variable ['tipo_vinculacion'] . ';';
                break;
            
            case 'consultarLeyesParametros' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_ldn as ID, ';
                $cadenaSql .= 'id as CODIGO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.ldnxtipo_vinculacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ' . $variable . ';';
                break;
            case 'consultarRubros' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'rub_identificador as ID, ';
                $cadenaSql .= 'id as CODIGO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.rubro_tipo_vinculacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ' . $variable . ';';
                break;
            case 'insertarRegistro' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'parametro.cargo ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'nivel,';
                $cadenaSql .= 'codigo_alternativo,';
                $cadenaSql .= 'grado,';
                $cadenaSql .= 'nombre,';
                $cadenaSql .= 'cod_tipo_cargo,';
                $cadenaSql .= 'sueldo,';
                $cadenaSql .= 'tipo_sueldo,';
                $cadenaSql .= 'estado';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $_REQUEST ['nivelRegistro'] . ', ';
                $cadenaSql .= $_REQUEST ['codAlternativoRegistro'] . ', ';
                $cadenaSql .= $_REQUEST ['gradoRegistro'] . ', ';
                $cadenaSql .= '\'' . $_REQUEST ['nombreRegistro'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['codTipoCargoRegistro'] . '\', ';
                $cadenaSql .= $_REQUEST ['sueldoRegistro'] . ', ';
                $cadenaSql .= '\'' . $_REQUEST ['tipoSueldoRegistro'] . '\', ';
                $cadenaSql .= '\'' . 'Activo' . '\' ';
                $cadenaSql .= ') ';
                echo $cadenaSql;
                break;

            case 'actualizarRegistro' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= $prefijo . 'pagina ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'nombre,';
                $cadenaSql .= 'descripcion,';
                $cadenaSql .= 'modulo,';
                $cadenaSql .= 'nivel,';
                $cadenaSql .= 'parametro';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= '\'' . $_REQUEST ['nombrePagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['descripcionPagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['moduloPagina'] . '\', ';
                $cadenaSql .= $_REQUEST ['nivelPagina'] . ', ';
                $cadenaSql .= '\'' . $_REQUEST ['parametroPagina'] . '\'';
                $cadenaSql .= ') ';
                break;

            case 'buscarRegistro' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_pagina as PAGINA, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                //$cadenaSql .= 'descripcion as DESCRIPCION,';
                //$cadenaSql .= 'modulo as MODULO,';
                //$cadenaSql .= 'nivel as NIVEL,';
                //$cadenaSql .= 'parametro as PARAMETRO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= $prefijo . 'pagina ';
                //$cadenaSql .= 'WHERE ';
                //$cadenaSql .= 'nombre=\'' . $_REQUEST ['nombrePagina'] . '\' ';
                break;

            case 'buscarRegistroxCargo' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'codigo_cargo as COD_CARGO, ';
                $cadenaSql .= 'nivel as NIVEL, ';
                $cadenaSql .= 'codigo_alternativo as COD_ALTERNATIVO,';
                $cadenaSql .= 'grado as GRADO,';
                $cadenaSql .= 'nombre as NOMBRE,';
                $cadenaSql .= 'cod_tipo_cargo as COD_TIPO, ';
                $cadenaSql .= 'estado as ESTADO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.cargo';
//                        $cadenaSql .= 'WHERE ';
//                        $cadenaSql .= 'nombre=\'' . $_REQUEST ['usuario']  . '\' AND ';
//                        $cadenaSql .= 'clave=\'' . $claveEncriptada . '\' ';

                break;
            case 'consultarLeyesTipoVinculacion' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_ldn as ID, ';
                $cadenaSql .= 'id as CODIGO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.ldnxtipo_vinculacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ' . $variable . ';';
                break;


            case 'consultarLeyesTipoVinculacion' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_ldn as ID, ';
                $cadenaSql .= 'id as CODIGO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.ldnxtipo_vinculacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ' . $variable . ';';
                break;

            case 'buscarRegistroUsuarioWhere' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_usuario as USUARIO, ';
                $cadenaSql .= 'nombre as NOMBRE, ';
                $cadenaSql .= 'apellido as APELLIDO, ';
                $cadenaSql .= 'fecha_reg as FECHA_REG, ';
                $cadenaSql .= 'edad as EDAD, ';
                $cadenaSql .= 'telefono as TELEFONO, ';
                $cadenaSql .= 'direccion as DIRECCION, ';
                $cadenaSql .= 'ciudad as CIUDAD, ';
                $cadenaSql .= 'estado as ESTADO ';
                //$cadenaSql .= 'descripcion as DESCRIPCION,';
                //$cadenaSql .= 'modulo as MODULO,';
                //$cadenaSql .= 'nivel as NIVEL,';
                //$cadenaSql .= 'parametro as PARAMETRO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= "parametro." . $prefijo . 'usuarios ';
//                		$cadenaSql .= 'WHERE ';
//                		$cadenaSql .= 'fecha_reg <=\'' . $_REQUEST ['fechaRegistroConsulta'] . '\' ';
                break;

            case 'borrarRegistro' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= $prefijo . 'pagina ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'nombre,';
                $cadenaSql .= 'descripcion,';
                $cadenaSql .= 'modulo,';
                $cadenaSql .= 'nivel,';
                $cadenaSql .= 'parametro';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= '\'' . $_REQUEST ['nombrePagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['descripcionPagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['moduloPagina'] . '\', ';
                $cadenaSql .= $_REQUEST ['nivelPagina'] . ', ';
                $cadenaSql .= '\'' . $_REQUEST ['parametroPagina'] . '\'';
                $cadenaSql .= ') ';
                break;
        }

        return $cadenaSql;
    }

}

?>
