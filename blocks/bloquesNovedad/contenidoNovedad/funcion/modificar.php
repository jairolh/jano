<?php

namespace bloquesNovedad\contenidoNovedad\funcion;

include_once('Redireccionador.php');
include_once('Interprete.php');
include_once('NodoConcepto.php');

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

        //***************************VALIDAR Formula*****************************************************************
        //-------------------------- Seccion Validar Formula ------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $_entradaFormulaCompilador = $_REQUEST['formulaConcepto'];

//        Interprete::evaluarSentencia($_entradaFormulaCompilador);
        $interprete = new Interprete();


//    $sentencia = 'IVAAA+((2+3)*RESRD)/+4-5';
        $ingresomod = 0;
        $aceptado = $interprete->evaluarSentencia($_entradaFormulaCompilador);

        if ($aceptado == "true") {

            $ingresomod = 1;
            if (isset($_REQUEST['naturalezaCon'])) {
                switch ($_REQUEST['naturalezaCon']) {
                    case 1 :
                        $_REQUEST['naturalezaCon'] = 'Devenga';
                        break;
                    case 2 :
                        $_REQUEST['naturalezaCon'] = 'Deduce';
                        break;
                }
            }

            $datosConcepto = array(
                'nombre' => $_REQUEST['nombreCon'],
                'tipo_novedad' => $_REQUEST['tipoNovedadCon'],
                'simbolo' => $_REQUEST['simboloCon'],
                'categoria' => $_REQUEST['categoriaNovedadCon'],
                'naturaleza' => $_REQUEST['naturalezaCon'],
                'descripcion' => $_REQUEST['descripcionCon'],
                'formula' => $_REQUEST['formulaConcepto'],
                'codigo' => $_REQUEST['variable']
            );

            $cadenaSql = $this->miSql->getCadenaSql("modificarConcepto", $datosConcepto);
            $id_concepto = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", "busqueda", $datosConcepto, "modificarConcepto");

            unset($cadenaSql);
            $cadenaSql = $this->miSql->getCadenaSql("eliminarLeyesConcepto", $datosConcepto);
            $accedo = $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
            unset($cadenaSql);

            $arrayLeyes = explode(",", $_REQUEST['leyCon']);
            $count = 0;


            while ($count < count($arrayLeyes)) {

                $datosLeyesConcepto = array(
                    'fk_id_ley' => $arrayLeyes[$count],
                    'fk_concepto' => $_REQUEST['variable']
                );

                $cadenaSql = $this->miSql->getCadenaSql("insertarLeyesConcepto", $datosLeyesConcepto);
                $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso"); //********************************

                $count++;
            }


            $arrayCampos = explode(",", $_REQUEST['variablesCampoCon']);
            $cuentaRegistro = 0;

            $datosFormulario = array(
                'fk_nombreFormulario' => $_REQUEST['nombreCon'],
                'fk_id_novedad' => $_REQUEST['variable']
            );

            //CREACION DE FORMULARIO Y GUARDO EN BD
            $cadenaSql = $this->miSql->getCadenaSql("buscarFormulario", $datosFormulario);
            $id_formulario = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
            $cadenaSql = $this->miSql->getCadenaSql("buscarCampos", $id_formulario[0][0]);
            $id_camposTrae = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

            $cuentainfocampos = 0;
            while ($cuentainfocampos < count($id_camposTrae)) {
                $cadenaSql = $this->miSql->getCadenaSql("eliminarInfoCampos", (int)$id_camposTrae[$cuentainfocampos][0]);
                $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
                $cuentainfocampos++;
            }

            $cadenaSql = $this->miSql->getCadenaSql("eliminarCampos", (int)$id_formulario[0][0]);
            $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
            
            $arrayInfoCampos = explode(",", $_REQUEST['camposInfoExtraCon']);
            $cuentaInfoCampos = 0;

            while ($cuentaRegistro < (count($arrayCampos) - 1)) {


                if ($arrayCampos[$cuentaRegistro] != 'undefined') {
                    $datosCampo = array(
                        'fk_nombreCampo' => $arrayCampos[$cuentaRegistro],
                        'fk_labelCampo' => $arrayCampos[$cuentaRegistro + 1],
                        'fk_tipoDatoCampo' => $arrayCampos[$cuentaRegistro + 2],
                        'fk_requeridoCampo' => $arrayCampos[$cuentaRegistro + 3],
                        'fk_formulacionCampo' => $arrayCampos[$cuentaRegistro + 4],
                        'fk_simboloCampo' => $arrayCampos[$cuentaRegistro + 5],
                        'fk_id_formulario' => $id_formulario[0][0]
                    );

                    unset($cadenaSql);
                    $cadenaSql = $this->miSql->getCadenaSql("insertarCampos", $datosCampo);
                    $id_campo = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosCampo, "insertarCampos");
                    $verificar = 0;

                    while (($cuentaInfoCampos < (count($arrayInfoCampos) - 1)) && $verificar < 2) {

                        if (($arrayInfoCampos[$cuentaInfoCampos] != '|T' && $arrayInfoCampos[$cuentaInfoCampos] != '|L' && $arrayInfoCampos[$cuentaInfoCampos] != '|V' && $arrayInfoCampos[$cuentaInfoCampos] != '|O')) {
                            $datosInfoCampo = array(
                                'fk_infoCampo' => $arrayInfoCampos[$cuentaInfoCampos],
                                'fk_id_campo' => (int) $id_campo[0][0]
                            );

                            $cadenaSql = $this->miSql->getCadenaSql("insertarInfoCampos", $datosInfoCampo);
                            $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
                        } else {
                            $verificar++;
                            if ($verificar == 2) {
                                $cuentaInfoCampos--;
                            }
                        }
                        $cuentaInfoCampos++;
                    }
                }

                $cuentaRegistro = $cuentaRegistro + 6;
            }
            
        } else {

            $datosConcepto = array(
                'nombre' => $_REQUEST['nombreCon'],
                'tipo_novedad' => $_REQUEST['tipoNovedadCon'],
                'simbolo' => $_REQUEST['simboloCon'],
                'categoria' => $_REQUEST['categoriaNovedadCon'],
                'naturaleza' => $_REQUEST['naturalezaCon'],
                'descripcion' => $_REQUEST['descripcionCon'],
                'formula' => $_REQUEST['formulaConcepto'],
                'error' => $aceptado,
                'refError' => "En el Campo Fórmula, "
            );

            Redireccionador::redireccionar('noModifico', $datosConcepto);
            exit();
        }


        //----------------------------------------------------------------------------------------------------------
        //------------------------ Codigo A Ejecutar Una Vez VALIDADA la Formula -----------------------------------
        //---------------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------------
        //***************************VALIDAR Condiciones*************************************************************
        // ---------------- INICIO: Lista Variables Control--------------------------------------------------------
//        $cantidadCondiciones = $_REQUEST['cantidadCondicionesConcepto'];
//
//        // ---------------- FIN: Lista Variables Control--------------------------------------------------------
//        // --------------------------------- n Condiciones ----------------------------------
//
//        $count = 0;
//        $control = 0;
//        $limite = 0;
//
//        $arrayPartCondicion = explode(",", $_REQUEST['variablesRegistros']);
//
//        while ($control < $cantidadCondiciones) {
//
//            $arrayCondiciones[$control] = 'Si(' . $arrayPartCondicion[$limite++] . ') Entonces{' . $arrayPartCondicion[$limite++] . '}';
//
//            $control++;
//        }
//
//        while ($count < $cantidadCondiciones) {
//
//            //-------------------------- Seccion Validar Condiciones ------------------------------------------------
//            //Formato:
//            //					Si(condiciones) Entonces{Accion}
//            //-------------------------------------------------------------------------------------------------------
//
//            $_entradaCondicionCompilador = $arrayCondiciones[$count];
//
//
//
//
//
//
//            //----------------------------------------------------------------------------------------------------------
//            //------------------------ Codigo A Ejecutar Una Vez VALIDADA la Condicion -----------------------------------
//
//            $datosCondicion = array(
//                'cadena' => $arrayCondiciones[$count],
//                'fk_concepto' => $id_concepto[0][0]
//            );
//
//            $cadenaSql = $this->miSql->getCadenaSql("insertarCondicion", $datosCondicion);
//            $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso"); //********************************
//            //-------------------------------------------------------------------------------------------------------
//
//            $count++;
//        }

        if ($ingresomod == 1) {
            Redireccionador::redireccionar('modifico', $datosConcepto);
            exit();
        } else {
            Redireccionador::redireccionar('noModifico', $datosConcepto);
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

