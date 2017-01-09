<?php

namespace bloquesNovedad\vinculacionPersonaNatural\funcion;

include_once('Redireccionador.php');

class FormProcessor {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;
    var $conexion;

    function __construct($lenguaje, $sql) {

        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
    }

    function procesarFormulario() {
        //Aquí va la lógica de procesamiento

        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);



        if ($_REQUEST['tipoVincu'] == 1) {
            $_REQUEST['tipoVincu'] = 'Sueldos';
        } else {
            $_REQUEST['tipoVincu'] = 'Honorarios';
        }


        if ($_REQUEST['dedicacionn'] == 1) {
            $_REQUEST['dedicacionn'] = 'TCO';
        } else {
            if ($_REQUEST['dedicacionn'] == 2) {
                $_REQUEST['dedicacionn'] = 'MTC';
            } else {
                $_REQUEST['dedicacionn'] = 'HC';
            }
        }


        $datos = array(
            'id' => $_REQUEST ['variable'],
            'numeroContrato' => $_REQUEST ['numeroContrato'],
            'numeroRegistro' => $_REQUEST ['numeroRegistro'],
            'numeroDisponibilidad' => $_REQUEST ['numeroDisponibilidad'],
            'unidadEjecutora' => $_REQUEST ['unidadEjecutora'],
            'rubro' => $_REQUEST ['rubro'],
            'vigencia' => $_REQUEST ['vigencia'],
            'fechaDisponibilidad' => $_REQUEST ['fecha'],
            'tipoVincu' => $_REQUEST ['tipoVincu'],
            'dedicacion' => $_REQUEST ['dedicacionn'],
            'semanas' => $_REQUEST ['semanass'],
            'horas' => $_REQUEST ['horas'],
            'valorContrato' => 640000*$_REQUEST ['semanass']*$_REQUEST ['horas'] ,
            
        );


        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarVinculacionEspecial", $datos);

        $resultado1 = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");



        if (!empty($resultado1)) {

            Redireccionador::redireccionar('inserto');
            exit();
        } else {

            Redireccionador::redireccionar('noInserto');
            exit();
        }
    }

    function resetForm() {
        foreach ($_REQUEST as $clave => $valor) {

            if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
                unset($_REQUEST[$clave]);
            }
        }
    }

}

$miProcesador = new FormProcessor($this->lenguaje, $this->sql);
$resultado = $miProcesador->procesarFormulario();
