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
        if (isset($_REQUEST['naturaleza'])) {
            switch ($_REQUEST ['naturaleza']) {
                case 1 :
                    $_REQUEST ['naturaleza'] = 'Temporal';
                    break;

                case 2 :
                    $_REQUEST ['naturaleza'] = 'Indefinido';
                    break;
            }
        }

        if (isset($_REQUEST['tipoLiquidacion'])) {
            switch ($_REQUEST ['tipoLiquidacion']) {
                case 1 :
                    $_REQUEST ['tipoLiquidacion'] = 'Rubro de salida';
                    break;

                case 2 :
                    $_REQUEST ['tipoLiquidacion'] = 'Rubro de entrada';
                    break;
            }
        }

        $datos = array(
            'nombre' => $_REQUEST ['nombre'],
            'descripcion' => $_REQUEST ['descripcion'],
            'naturaleza' => $_REQUEST ['naturaleza'],
            'tipoLiquidacion' => $_REQUEST ['tipoLiquidacion'],
        );


        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("registrarTipoVinculacion", $datos);

        $resultado = $primerRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda", $datos, "registrarTipoVinculacion");


        $arrayLeyes = explode(",", $_REQUEST['leyRegistros']);
        $count = 0;

        while ($count < count($arrayLeyes)) {

            $datosLeyesConcepto = array(
                'id_ley' => $arrayLeyes[$count],
                'tipo_vinculacion' => $resultado[0][0]
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
                'tipo_vinculacion' => $resultado[0][0]
            );

            $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarRubrosTipoVinculacion", $datosRubros);

        
            $resultado2 = $primerRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "acceso"); //********************************


            $count++;
        }


        if (!empty($resultado) && !empty($resultado1)&& !empty($resultado2)) {
            Redireccionador::redireccionar('inserto');
            exit();
        } else {
            Redireccionador::redireccionar('noInserto');
            exit();
        }

        //Al final se ejecuta la redirección la cual pasará el control a otra página
        // Redireccionador::redireccionar('opcion1');
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

