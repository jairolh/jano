<?php

namespace bloquesNovedad\contenidoNovedad\funcion;

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


        //Al final se ejecuta la redirección la cual pasará el control a otra página

        $datos = array(
            'tipoNovedad' => $_REQUEST['tipoNovedad'],
            'categoriaConceptos' => $_REQUEST['categoriaConceptos'],
            'nombre' => $_REQUEST['nombre'],
            'simbolo' => $_REQUEST['simbolo'],
            'ley' => $_REQUEST['ley'],
            'leyRegistros' => $_REQUEST['leyRegistros'],
            'naturaleza' => $_REQUEST['naturaleza'],
            'descripcion' => $_REQUEST['descripcion']
        );
        if ($_REQUEST['tipoNovedad'] == 1) {

            Redireccionador::redireccionar('periodica', $datos);
        } else {


            Redireccionador::redireccionar('esporadica', $datos);
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