<?php

namespace bloquesNovedad\contenidoGestionNovedad;

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
            case 'buscarTipoVinculacion':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= "id as ID, ";
                $cadenaSql .= "nombre as NOMBRE ";
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.tipo_vinculacion ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'estado != \'Inactivo\';';
                break;

            case 'buscarPersonaVinculadaDetalle':
                $cadenaSql = 'SELECT ';

                $cadenaSql .= "(primer_nombre || ' ' || segundo_nombre) as NOMBRES, ";
                $cadenaSql .= "(primer_apellido || ' ' || segundo_apellido) as APELLIDOS, ";
                // nombre o naturaleza
                $cadenaSql .= 'nombre as TIPO_VINCULACION, ';
                $cadenaSql .= "fecha_inicio as FECHA_INICIO, ";
                $cadenaSql .= "fecha_final as FECHA_FINAL, ";
                $cadenaSql .= "d.id as ID_VINCULACION, ";
                $cadenaSql .= "j.id as ID_TIPO_VINCULACION, ";
                $cadenaSql .= "j.nombre as NOMBRE_TIPO_VINCULACION, ";
                $cadenaSql .= 'j.estado as ESTADO_VINCULACION, ';
                $cadenaSql .= 'c.tipo_cargo as CARGO, ';
                $cadenaSql .= 'f.id_funcionario as ID_FUNCIONARIO, ';
                $cadenaSql .= 'p.documento as DOCUMENTO ';

                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'persona.persona_natural p, ';
                $cadenaSql .= 'parametro.tipo_vinculacion j, ';
                $cadenaSql .= 'novedad.funcionario f, ';
                $cadenaSql .= 'novedad.cargoxfuncionario l, ';
                $cadenaSql .= 'parametro.cargo c, ';
                $cadenaSql .= 'persona.vinculacion_persona_natural d ';

                $cadenaSql .= 'where d.documento = p.documento';
                $cadenaSql .= ' and d.id_tipo_vinculacion = j.id ';
                $cadenaSql .= ' and d.documento=f.documento ';
                $cadenaSql .= ' and l.id_funcionario=f.id_funcionario ';
                $cadenaSql .= ' and c.codigo_cargo=l.codigo_cargo ';
                $cadenaSql .= ' and f.estado_funcionario!= \'Inactivo\' ';
                $cadenaSql .= ' and d.estado_vinculacion!= \'Inactivo\' ';
                $cadenaSql .= ' and j.id = ';
                $cadenaSql .= $variable;
                break;
            case 'buscarRegistrosDeNovedades':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'n.nombre as NOMBRE, ';
                $cadenaSql .= 'n.simbolo as SIMBOLO, ';
                $cadenaSql .= 'n.descripcion as DESCRIPCION, ';
                $cadenaSql .= 'n.tipo_novedad as TIPO, ';
                $cadenaSql .= 'n.naturaleza as NATURALEZA, ';
                $cadenaSql .= 'n.estado as ESTADO, ';
                $cadenaSql .= 'n.codigo as ID ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.novedad n, ';
                $cadenaSql .= 'concepto.novedadxfuncionario c ';
                $cadenaSql .= 'where n.codigo = c.codigo';
                $cadenaSql .= ' and c.id_funcionario = ';
                $cadenaSql .= $variable;

                break;
            case 'buscarIdFuncionario':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_funcionario as ID_FUNCIONARIO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'novedad.funcionario ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'documento= ';
                $cadenaSql .= $variable;

                break;
            case 'buscarNovedades':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'codigo as CODIGO, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.novedad ';
                break;
            case 'buscarNovedadesAjax':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'codigo as CODIGO, ';
                $cadenaSql .= 'nombre as NOMBRE, ';
                $cadenaSql .= 'tipo_novedad as TIPO_NOVEDAD ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'concepto.novedad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'codigo= ';
                $cadenaSql .= $variable;
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
        }


        return $cadenaSql;
    }

}

?>
