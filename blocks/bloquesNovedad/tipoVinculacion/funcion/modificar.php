<?php

namespace bloquesParametro\tipoVinculacion\funcion;

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
        if (isset($_REQUEST ['regresar']) && $_REQUEST ['regresar'] == "true") {

            Redireccionador::redireccionar('form');
            exit;
        }
        if ($_REQUEST ['tipoLiquidacion'] == 1) {
                $_REQUEST ['tipoLiquidacion'] = 'Rubro de salida';
            } else {

                $_REQUEST ['tipoLiquidacion'] = 'Rubro de entrada';
            }
        if (isset($_REQUEST ['naturaleza'])) {
            if ($_REQUEST ['naturaleza'] == 1) {
                $_REQUEST ['naturaleza'] = 'Temporal';
            } else {

                $_REQUEST ['naturaleza'] = 'Indefinido';
            }


            $datos = array(
                'id' => $_REQUEST ['id'],
                'nombre' => $_REQUEST ['nombre'],
                'descripcion' => $_REQUEST ['descripcion'],
                'naturaleza' => $_REQUEST ['naturaleza'],
                'tipoLiquidacion' => $_REQUEST ['tipoLiquidacion'],
            );
        } else {
            $datos = array(
                'id' => $_REQUEST ['id'],
                'nombre' => $_REQUEST ['nombre'],
                'descripcion' => $_REQUEST ['descripcion'],
            );
        }




//       


        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("modificarRegistro", $datos);



        $resultado = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");
        //Al final se ejecuta la redirección la cual pasará el control a otra página




        $datosLeyesConcepto = array(
            'tipo_vinculacion' => $_REQUEST['id']
        );

        $cadenaSql = $this->miSql->getCadenaSql("eliminarLeyesModificar", $datosLeyesConcepto);

        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso"); //********************************


        $datosRubros = array(
            'tipo_vinculacion' => $_REQUEST['id']
        );

        $cadenaSql = $this->miSql->getCadenaSql("eliminarRubrosModificar", $datosRubros);

        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso"); //********************************

        
        $arrayLeyes = explode(",", $_REQUEST['leyRegistros']);
        $count = 0;

        while ($count < count($arrayLeyes)) {

            $datosLeyesConcepto = array(
                'id_ley' => $arrayLeyes[$count],
                'tipo_vinculacion' => $_REQUEST ['id']
            );

            $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarLeyesTipoVinculacion", $datosLeyesConcepto);

            $resultado1 = $primerRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "acceso"); //********************************


            $count++;
        }

        $arrayRubros = explode(",", $_REQUEST['rubros']);
        $count = 0;

        while ($count < count($arrayRubros)) {

            $datosRubros = array(
                'id_rubro' => $arrayRubros[$count],
                'tipo_vinculacion' =>$_REQUEST ['id']
            );

            $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarRubrosTipoVinculacion", $datosRubros);

            $resultado2 = $primerRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "acceso"); //********************************


            $count++;
        }
        if (!empty($resultado) && !empty($resultado1)) {

            Redireccionador::redireccionar('modifico');
            exit();
        } else {

            Redireccionador::redireccionar('nomodifico');
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
