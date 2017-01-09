<?php

namespace bloquesNovedad\contenidoNovedad;

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
            case 'insertarConcepto' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'concepto.novedad ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'id_categoria, ';
                $cadenaSql .= 'estado, ';
                $cadenaSql .= 'nombre, ';
                $cadenaSql .= 'simbolo, ';
                $cadenaSql .= 'naturaleza, ';
                $cadenaSql .= 'descripcion, ';
                $cadenaSql .= 'tipo_novedad, ';
                $cadenaSql .= 'formula';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $variable ['categoria'] . ', ';
                $cadenaSql .= '\'Activo\', ';
                $cadenaSql .= '\'' . $variable ['nombre'] . '\', ';
                $cadenaSql .= '\'' . $variable ['simbolo'] . '\', ';
                $cadenaSql .= '\'' . $variable ['naturaleza'] . '\', ';
                $cadenaSql .= '\'' . $variable ['descripcion'] . '\', ';
                $cadenaSql .= '\'' . $variable ['tipo_novedad'] . '\', ';
                $cadenaSql .= '\'' . $variable ['formula'] . '\' ';
                $cadenaSql .= ') ';
                $cadenaSql .= "RETURNING  codigo; ";
                break;
            case 'modificarConcepto' :
                $cadenaSql = 'UPDATE ';
                $cadenaSql .= 'concepto.novedad ';
                $cadenaSql .= 'SET ';
                $cadenaSql .= 'id_categoria = ';
                $cadenaSql .= $variable ['categoria'] . ', ';
                $cadenaSql .= 'estado = ';
                $cadenaSql .= '\'Activo\', ';
                $cadenaSql .= 'nombre = ';
                $cadenaSql .= '\'' . $variable ['nombre'] . '\', ';
                $cadenaSql .= 'simbolo = ';
                $cadenaSql .= '\'' . $variable ['simbolo'] . '\', ';
                $cadenaSql .= 'naturaleza = ';
                $cadenaSql .= '\'' . $variable ['naturaleza'] . '\', ';
                $cadenaSql .= 'descripcion = ';
                $cadenaSql .= '\'' . $variable ['descripcion'] . '\', ';
                $cadenaSql .= 'tipo_novedad = ';
                $cadenaSql .= '\'' . $variable ['tipo_novedad'] . '\', ';
                $cadenaSql .= 'formula = ';
                $cadenaSql .= '\'' . $variable ['formula'] . '\' ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'codigo = ';
                $cadenaSql .= $variable ['codigo'] . ';';
                break;
            case 'buscarVariables' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id as ID, ';
                $cadenaSql .= 'simbolo as SIMBOLO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.variable ';
                break;
            case 'insertarLeyesConcepto' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'concepto.novedadxldn ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'id_ldn,';
                $cadenaSql .= 'codigo';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $variable ['fk_id_ley'] . ', ';
                $cadenaSql .= $variable ['fk_concepto'];
                $cadenaSql .= '); ';
                break;
            case 'eliminarLeyesConcepto' :
                $cadenaSql = 'DELETE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.novedadxldn ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'codigo = ';
                $cadenaSql .= $variable ['codigo'] . '; ';
                break;
            case 'insertarFormulario' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'concepto.formulario_novedad ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'codigo, ';
                $cadenaSql .= 'nombre_formulario';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $variable ['fk_id_novedad'] . ', ';
                $cadenaSql .= '\'' . $variable ['fk_nombreFormulario'] . '\' ';
                $cadenaSql .= ') ';
                $cadenaSql .= "RETURNING  id_formulario; ";
                break;
            case 'insertarFormulario' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'concepto.formulario_novedad ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'codigo, ';
                $cadenaSql .= 'nombre_formulario';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $variable ['fk_id_novedad'] . ', ';
                $cadenaSql .= '\'' . $variable ['fk_nombreFormulario'] . '\' ';
                $cadenaSql .= ') ';
                $cadenaSql .= "RETURNING  id_formulario; ";
                break;
            case 'buscarFormulario' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_formulario as ID ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.formulario_novedad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'codigo = ';
                $cadenaSql .= $variable ['fk_id_novedad'] . ';';
                break;
            case 'buscarCampos' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_campo as ID_CAMPO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.campo_novedad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_formulario = ';
                $cadenaSql .= $variable . ';';
                break;
            case 'insertarCondicion' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'concepto.condicion_novedad ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'codigo,';
                $cadenaSql .= 'cadena';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $variable ['fk_concepto'] . ', ';
                $cadenaSql .= '\'' . $variable ['cadena'] . '\' ';
                $cadenaSql .= '); ';
                break;
            case 'modificarRegistro' :
                $cadenaSql = 'UPDATE ';
                $cadenaSql .= 'concepto.asociacion_concepto ';
                $cadenaSql .= 'SET ';
                $cadenaSql .= 'codigo_concepto = ';
                $cadenaSql .= "'" . $variable ['codigo_concepto'] . "',";
                $cadenaSql .= 'tipo_nomina = ';
                $cadenaSql .= "'" . $variable ['tipo_vinculacion_nomina'] . "'";
                $cadenaSql .= ' WHERE ';
                $cadenaSql .= 'id= ';
                $cadenaSql .= $variable ['id'] . ';';
                break;
            case 'insertarCampos' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'concepto.campo_novedad ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'id_formulario, ';
                $cadenaSql .= 'nombre_campo, ';
                $cadenaSql .= 'label_campo, ';
                $cadenaSql .= 'tipo_dato, ';
                $cadenaSql .= 'requerido, ';
                $cadenaSql .= 'simbolo, ';
                $cadenaSql .= 'formulacion';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $foo = (int) $variable ['fk_id_formulario'];
                $cadenaSql .= $foo . ', ';
                $cadenaSql .= '\'' . $variable ['fk_nombreCampo'] . '\', ';
                $cadenaSql .= '\'' . $variable ['fk_labelCampo'] . '\', ';
                $cadenaSql .= '\'' . $variable ['fk_tipoDatoCampo'] . '\', ';
                $cadenaSql .= '\'' . $variable ['fk_requeridoCampo'] . '\', ';
                $cadenaSql .= '\'' . $variable ['fk_simboloCampo'] . '\', ';
                $cadenaSql .= '\'' . $variable ['fk_formulacionCampo'] . '\' ';
                $cadenaSql .= ') ';
                $cadenaSql .= "RETURNING  id_campo; ";
                break;
            case 'insertarInfoCampos' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'concepto.datos_campo ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'id_campo, ';
                $cadenaSql .= 'valor ';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $variable ['fk_id_campo'] . ', ';
                $cadenaSql .= '\'' . $variable ['fk_infoCampo'] . '\'';
                $cadenaSql .= '); ';
                break;

            case 'eliminarCampos' :
                $cadenaSql = 'DELETE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.campo_novedad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_formulario = ';
                $cadenaSql .= $variable . ';';
                break;
            case 'eliminarInfoCampos' :
                $cadenaSql = 'DELETE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.datos_campo ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_campo = ';
                $cadenaSql .= $variable . ';';
                break;
            
            case 'inactivarRegistro' :
                $cadenaSql = 'UPDATE ';
                $cadenaSql .= 'concepto.novedad ';
                $cadenaSql .= 'SET ';
                $cadenaSql .= 'estado = ';
                $cadenaSql .= "'" . $variable ['estadoRegistro'] . "' ";
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'codigo = ';
                $cadenaSql .= $variable ['codigoRegistro'] . ";";
                break;


            case "registrarAsociacion" :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'concepto.asociacion_concepto';
                $cadenaSql .= '( ';


                $cadenaSql .= 'codigo_concepto,';
                $cadenaSql .= 'tipo_nomina,';

                $cadenaSql .= 'estado';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $variable ['codigo_concepto'] . ', ';

                $cadenaSql .= $variable ['tipo_vinculacion_nomina'] . ', ';


                $cadenaSql .= '\'' . 'Activo' . '\' ';
                $cadenaSql .= ') ';

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
            case 'buscarConcepto' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'codigo as ID, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.concepto ';

                break;
            case 'buscarNovedadInactivar' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'nombre as NOMBRE, ';
                $cadenaSql .= 'simbolo as SIMBOLO, ';
                $cadenaSql .= 'tipo_novedad as TIPO_NOVEDAD, ';
                $cadenaSql .= 'estado as ESTADO, ';
                $cadenaSql .= 'codigo as ID ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.novedad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'codigo = ';
                $cadenaSql .= $variable . ' ';

                break;
            case 'buscarTipoVinculacion1' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id as ID, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.tipo_vinculacion';

                break;


            case 'buscarTipoVinculacion':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id as ID, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.tipo_vinculacion';
                break;
            case 'buscarCategoriaNovedad' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_categoria as ID, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.categoria_novedad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'estado != \'Inactivo\';';
                break;
            case 'buscarCategoriaConcepto' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id as ID, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.categoria ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'estado != \'Inactivo\';';
                break;
            case 'buscarLey' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_ldn as ID, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.ley_decreto_norma ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'estado != \'Inactivo\';';
                break;
            case 'consultarLeyesDeNomina' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'codigo as CODIGO, ';
                $cadenaSql .= 'id_ldn as ID ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.novedadxldn ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'codigo = ' . $variable . '';
                break;
            case 'buscarCategoriaParametro' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_categoria as ID, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.categoria_parametro ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'estado != \'Inactivo\';';
                break;
            case 'buscarRegistroxParametro' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id as ID, ';
                $cadenaSql .= 'simbolo as SIMBOLO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.parametro_liquidacion';
                break;
            case 'buscarParametroAjax' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id as ID_CATEGORIA, ';
                $cadenaSql .= 'simbolo as SIMBOLO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.parametro_liquidacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_categoria = ' . $variable . ';';
                break;

            case 'buscarConceptoAjax' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'codigo as ID, ';
                $cadenaSql .= 'simbolo as SIMBOLO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.concepto ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ' . $variable . ';';
                break;

            case 'buscarValorParametroAjax' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'valor as VALOR ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.parametro_liquidacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ' . $variable . ';';
                break;

            case 'buscarValorConceptoAjax' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'formula as FORMULA ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.concepto ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'codigo = ' . $variable . ';';
                break;

            case 'buscarIdTipoVinculacion':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id as ID ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.tipo_vinculacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= "nombre = '" . $variable["tipo_vinculacion"] . "';";
                break;



            case 'buscarNomina':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'codigo_nomina as CODIGO_NOMINA, ';
                $cadenaSql .= 'nombre as NOMBRE ';

                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'liquidacion.nomina ';


                break;
            case 'buscarIdNomina':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'codigo_nomina as CODIGO_NOMINA, ';
                $cadenaSql .= 'nombre as NOMBRE ';

                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'liquidacion.nomina ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= "nombre = '" . $variable["tipo_nomina"] . "';";

                break;


            case 'buscarDepartamento' ://Provisionalmente solo Departamentos de Colombia

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'otro.departamento ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_pais = 112;';
                break;
            case 'buscarDepartamentoAjax' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'otro.departamento ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_pais = ' . $variable . ';';
                break;

            case 'buscarCiudad' : //Provisionalmente Solo Ciudades de Colombia sin Agrupar

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'otro.ciudad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'ab_pais = \'CO\';';
                break;

            case 'buscarCiudadAjax' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'codigo_nomina as ID, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'liquidacion.nomina ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ' . $variable . ';';
                break;
            case 'buscarDepartamentoEspecifico' ://Provisionalmente solo Departamentos de Colombia

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'otro.departamento ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_pais = 112 and ';
                $cadenaSql .= 'id_departamento = ' . $variable;
                break;
            case 'buscarCiudadEspecifico' ://Provisionalmente solo Departamentos de Colombia

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'otro.ciudad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_ciudad = ' . $variable;
                break;
            case 'buscartipovinculacionnomina' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'codigo_nomina as CODIGO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'liquidacion.nomina ';
                $cadenaSql .= 'WHERE ';

                $cadenaSql .= 'id = ';
                $cadenaSql .= $variable ['tipo_vinculacion'] . ' AND ';
                $cadenaSql .= 'codigo_nomina = ';
                $cadenaSql .= $variable ['tipo_nomina'] . ';';
                break;



            case 'buscarUbicacion' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
                $cadenaSql .= 'id_departamento as ID_DEPARTAMENTO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'otro.ubicacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_ubicacion = ';
                $cadenaSql .= $variable . '';
                break;
            case 'buscarCiudadUbicacion' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'nombre as NOMBRE, ';
                $cadenaSql .= 'departamento as DEPARTAMENTO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'otro.ciudad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_ciudad = ';
                $cadenaSql .= $variable . '';
                break;
            case 'insertarUbicacion' :

                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'otro.ubicacion ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'id_pais,';
                $cadenaSql .= 'id_departamento,';
                $cadenaSql .= 'id_ciudad';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= 112 . ', ';
                $cadenaSql .= $variable ['fdpDepartamento'] . ', ';
                $cadenaSql .= $variable ['fdpCiudad'] . '';
                $cadenaSql .= ') ';
                break;
            case 'buscarRegistrosDeNovedades':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'nombre as NOMBRE, ';
                $cadenaSql .= 'simbolo as SIMBOLO, ';
                $cadenaSql .= 'descripcion as DESCRIPCION, ';
                $cadenaSql .= 'tipo_novedad as TIPO, ';
                $cadenaSql .= 'naturaleza as NATURALEZA, ';
                $cadenaSql .= 'estado as ESTADO, ';
                $cadenaSql .= 'codigo as ID ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.novedad';
                break;
            case 'buscarNovedadxReg':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'nombre as NOMBRE, ';
                $cadenaSql .= 'simbolo as SIMBOLO, ';
                $cadenaSql .= 'descripcion as DESCRIPCION, ';
                $cadenaSql .= 'tipo_novedad as TIPO, ';
                $cadenaSql .= 'naturaleza as NATURALEZA, ';
                $cadenaSql .= 'estado as ESTADO, ';
                $cadenaSql .= 'codigo as ID, ';
                $cadenaSql .= 'id_categoria as ID_CATEGORIA, ';
                $cadenaSql .= 'formula as FORMULA ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.novedad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'codigo = ';
                $cadenaSql .= $variable . '';
                break;
            case 'buscarCategoriaxReg':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.categoria_novedad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_categoria = ';
                $cadenaSql .= $variable . '';
                break;
            case 'buscarFormularioDeCampos':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_formulario as ID, ';
                $cadenaSql .= 'nombre_formulario as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.formulario_novedad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'codigo = ';
                $cadenaSql .= $variable . '';
                break;
            case 'buscarRegistrosDeCampos':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_campo as ID, ';
                $cadenaSql .= 'nombre_campo as NOMBRE, ';
                $cadenaSql .= 'label_campo as LABEL, ';
                $cadenaSql .= 'tipo_dato as TIPO, ';
                $cadenaSql .= 'requerido as REQUERIDO, ';
                $cadenaSql .= 'formulacion as FORMULACION, ';
                $cadenaSql .= 'simbolo as SIMBOLO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.campo_novedad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_formulario = ';
                $cadenaSql .= $variable . '';
                break;
            case 'buscarInformacionDeCamposMod':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'valor as VALOR ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.datos_campo ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_campo = ';
                $cadenaSql .= $variable . '';
                break;
        }


        return $cadenaSql;
    }

}

?>
