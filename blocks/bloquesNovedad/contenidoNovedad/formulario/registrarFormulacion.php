<?php

namespace bloquesNovedad\contenidoNovedad\formulario;

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

class Formulario {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;

    function __construct($lenguaje, $formulario, $sql) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miFormulario = $formulario;
        $this->miSql = $sql;
    }

    function formulario() {

        /**
         * IMPORTANTE: Este formulario está utilizando jquery.
         * Por tanto en el archivo ready.php se delaran algunas funciones js
         * que lo complementan.
         */
        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        // ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------
        /**
         * Atributos que deben ser aplicados a todos los controles de este formulario.
         * Se utiliza un arreglo
         * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
         *
         * Si se utiliza esta técnica es necesario realizar un mezcla entre este arreglo y el específico en cada control:
         * $atributos= array_merge($atributos,$atributosGlobales);
         */
        $atributosGlobales ['campoSeguro'] = 'true';
        $_REQUEST['tiempo'] = time();
        $tiempo = $_REQUEST['tiempo'];
        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);


        // -------------------------------------------------------------------------------------------------
        // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
        $esteCampo = $esteBloque ['nombre'];
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        // Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
        $atributos ['tipoFormulario'] = '';
        // Si no se coloca, entonces toma el valor predeterminado 'POST'
        $atributos ['metodo'] = 'POST';
        // Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
        $atributos ['action'] = 'index.php';
        $atributos ['titulo'] = false; //$this->lenguaje->getCadena ( $esteCampo );
        // Si no se coloca, entonces toma el valor predeterminado.
        $atributos ['estilo'] = '';
        $atributos ['marco'] = true;
        $tab = 1;
        // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
        // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos ['tipoEtiqueta'] = 'inicio';
        echo $this->miFormulario->formulario($atributos);
        // ---------------- SECCION: Controles del Formulario -----------------------------------------------
        // --------------------------------------------------------------------------------------------------


        $esteCampo = "marcoDatosBasicos";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = "FÓRMULA DEL CONCEPTO";
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        // --------------------------------------------------------------------------------------------------

        $atributos ["id"] = "formula";
        $atributos ["estilo"] = "row";
        echo $this->miFormulario->division("inicio", $atributos);
        {
            $atributos ["id"] = "ingresoFormula";
            $atributos ["estilo"] = "col-md-6";
            echo $this->miFormulario->division("inicio", $atributos);
            {
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'formula';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['estilo'] = '';
                $atributos ['marco'] = false;
                $atributos ['columnas'] = 50;
                $atributos ['filas'] = 18;
                $atributos ['tabIndex'] = $tab;
                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['anchoEtiqueta'] = 90;
                $atributos ['deshabilitado'] = false;

                $atributos ['obligatorio'] = true;
                $atributos ['etiquetaObligatorio'] = true;
                $atributos ['validar'] = 'required, minSize[1], maxSize[5]';

                if (isset($_REQUEST [$esteCampo])) {
                    $atributos ['valor'] = $_REQUEST [$esteCampo];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                $tab++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoTextArea($atributos);
                // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------

                unset($atributos);

                $atributos ["id"] = "ingresoBotones";
                $atributos ["estilo"] = "col-md-12";
                echo $this->miFormulario->division("inicio", $atributos);
                {
                    $atributos ["id"] = "botonesPanel1";
                    $atributos ["estilo"] = "col-md-2";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {
                        echo("Operadores:");
                    }
                    echo $this->miFormulario->division("fin");

                    $atributos ["id"] = "botonesPanel2";
                    $atributos ["estilo"] = "col-md-10 btn-group btn-group-lg";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {
                        echo "<input type=\"button\" id=\"btOper1\" value=\"(\" class=\"btn btn-primary\"/>";
                        echo "<input type=\"button\" id=\"btOper2\" value=\")\" class=\"btn btn-primary\" />";
                        echo "<input type=\"button\" id=\"btOper3\" value=\"+\" class=\"btn btn-primary\"/>";
                        echo "<input type=\"button\" id=\"btOper4\" value=\"-\" class=\"btn btn-primary\" />";
                        echo "<input type=\"button\" id=\"btOper5\" value=\"*\" class=\"btn btn-primary\"/>";
                        echo "<input type=\"button\" id=\"btOper6\" value=\"÷\" class=\"btn btn-primary\" />";
                        echo "<input type=\"button\" id=\"btOper7\" value=\"√\" class=\"btn btn-primary\"/>";
                        echo "<input type=\"button\" id=\"btOper8\" value=\"^\" class=\"btn btn-primary\" />";
                        echo "<input type=\"button\" id=\"btOper9\" value=\"Borrar\" class=\"btn btn-danger\" />";
                    }
                    echo $this->miFormulario->division("fin");
                }
                echo $this->miFormulario->division("fin");
            }
            echo $this->miFormulario->division("fin");

            // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
            unset($atributos);
            // ---------------- CONTROL: Select --------------------------------------------------------
            $atributos ["id"] = "variables";
            $atributos ["estilo"] = "col-md-6";
            echo $this->miFormulario->division("inicio", $atributos);
            {
                unset($atributos);


                $esteCampo = "marcoDatosParametros";
                $atributos ['id'] = $esteCampo;
                $atributos ["estilo"] = "jqueryui";
                $atributos ['tipoEtiqueta'] = 'inicio';
                $atributos ["leyenda"] = "Panel Parámetros";
                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
                {

                    $atributos ["id"] = "categoriaParametros";
                    $atributos ["estilo"] = "col-md-12";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {
                        // ---------------- CONTROL: Select --------------------------------------------------------
                        $esteCampo = 'categoriaParametrosList';
                        $atributos['nombre'] = $esteCampo;
                        $atributos['id'] = $esteCampo;
                        $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 230;
                        $atributos['tab'] = $tab;
                        $atributos['seleccion'] = -1;
                        $atributos['evento'] = ' ';
                        $atributos['deshabilitado'] = false;
                        $atributos['limitar'] = 50;
                        $atributos['tamanno'] = 1;
                        $atributos['columnas'] = 1;

                        $atributos ['obligatorio'] = false;
                        $atributos ['etiquetaObligatorio'] = false;
                        $atributos ['validar'] = '';

                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarCategoriaParametro");
                        $matrizParametros = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");

                        $atributos['matrizItems'] = $matrizParametros;

// 					$matrizItems=array(
// 							array(1,'SMLV'),
// 							array(2,'IVA'),
// 							array(3,'RTF'),
// 							array(4,'HED')false,
// 							array(5,'HEN')
// 					);
// 					$atributos['matrizItems'] = $matrizItems;

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] = '';
                        }
                        $atributos ["titulo"] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $tab++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        // --------------- FIN CONTROL : Select --------------------------------------------------
                    }
                    echo $this->miFormulario->division("fin");

                    unset($atributos);

                    $atributos ["id"] = "parametros";
                    $atributos ["estilo"] = "col-md-12";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {
                        // ---------------- CONTROL: Select --------------------------------------------------------
                        $esteCampo = 'seccionParametros';
                        $atributos['nombre'] = $esteCampo;
                        $atributos['id'] = $esteCampo;
                        $atributos['etiqueta'] = '';
                        $atributos ['anchoEtiqueta'] = 180;
                        $atributos['tab'] = $tab;
                        $atributos['seleccion'] = -1;
                        $atributos['evento'] = ' ';
                        $atributos['deshabilitado'] = true;
                        $atributos['limitar'] = 50;
                        $atributos['tamanno'] = 1;
                        $atributos['columnas'] = 1;

                        $atributos ['obligatorio'] = false;
                        $atributos ['etiquetaObligatorio'] = false;
                        $atributos ['validar'] = '';

                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarCategoriaParametro");
                        $matrizParametros = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");

                        $atributos['matrizItems'] = $matrizParametros;

// 					$matrizItems=array(
// 							array(1,'SMLV'),
// 							array(2,'IVA'),
// 							array(3,'RTF'),
// 							array(4,'HED'),
// 							array(5,'HEN')
// 					);
// 					$atributos['matrizItems'] = $matrizItems;

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] = '';
                        }
                        $atributos ["titulo"] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $tab++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        // --------------- FIN CONTROL : Select --------------------------------------------------
                    }
                    echo $this->miFormulario->division("fin");

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    unset($atributos);
                    $esteCampo = 'valorParametro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['estilo'] = '';
                    $atributos ['marco'] = false;
                    $atributos ['columnas'] = 50;
                    $atributos ['filas'] = 1;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 90;
                    $atributos['deshabilitado'] = true;

                    $atributos ['obligatorio'] = false;
                    $atributos ['etiquetaObligatorio'] = false;
                    $atributos ['validar'] = '';

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['valor'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['valor'] = '';
                    }
                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                    $tab++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoTextArea($atributos);
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }
                echo $this->miFormulario->marcoAgrupacion("fin");

                unset($atributos);


                $esteCampo = "marcoDatosConceptos";
                $atributos ['id'] = $esteCampo;
                $atributos ["estilo"] = "jqueryui";
                $atributos ['tipoEtiqueta'] = 'inicio';
                $atributos ["leyenda"] = "Panel Conceptos";
                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
                {

                    $atributos ["id"] = "categoriaConceptos";
                    $atributos ["estilo"] = "col-md-12";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {
                        // ---------------- CONTROL: Select --------------------------------------------------------
                        $esteCampo = 'categoriaConceptosList';
                        $atributos['nombre'] = $esteCampo;
                        $atributos['id'] = $esteCampo;
                        $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 230;
                        $atributos['tab'] = $tab;
                        $atributos['seleccion'] = -1;
                        $atributos['evento'] = ' ';
                        $atributos['deshabilitado'] = false;
                        $atributos['limitar'] = 50;
                        $atributos['tamanno'] = 1;
                        $atributos['columnas'] = 1;

                        $atributos ['obligatorio'] = false;
                        $atributos ['etiquetaObligatorio'] = false;
                        $atributos ['validar'] = '';

                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarCategoriaConcepto");
                        $matrizParametros = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");

                        $atributos['matrizItems'] = $matrizParametros;

                        /*
                          $matrizItems=array(
                          array(1,'CP0001'),
                          array(2,'CP0002'),
                          array(3,'CP0003'),
                          array(4,'CP0004'),
                          array(5,'CP0005')

                          );
                          $atributos['matrizItems'] = $matrizItems; */

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] = '';
                        }

                        $atributos ["titulo"] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $tab++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        // --------------- FIN CONTROL : Select --------------------------------------------------
                    }
                    echo $this->miFormulario->division("fin");

                    unset($atributos);

                    $atributos ["id"] = "conceptos";
                    $atributos ["estilo"] = "col-md-12";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {
                        // ---------------- CONTROL: Select --------------------------------------------------------
                        $esteCampo = 'seccionConceptos';
                        $atributos['nombre'] = $esteCampo;
                        $atributos['id'] = $esteCampo;
                        $atributos['etiqueta'] = '';
                        $atributos ['anchoEtiqueta'] = 180;
                        $atributos['tab'] = $tab;
                        $atributos['seleccion'] = -1;
                        $atributos['evento'] = ' ';
                        $atributos['deshabilitado'] = true;
                        $atributos['limitar'] = 50;
                        $atributos['tamanno'] = 1;
                        $atributos['columnas'] = 1;

                        $atributos ['obligatorio'] = false;
                        $atributos ['etiquetaObligatorio'] = false;
                        $atributos ['validar'] = '';

                        $matrizItems = array(
                            array(1, 'CP0001'),
                            array(2, 'CP0002'),
                            array(3, 'CP0003'),
                            array(4, 'CP0004'),
                            array(5, 'CP0005')
                        );
                        $atributos['matrizItems'] = $matrizItems;

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] = '';
                        }

                        $atributos ["titulo"] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $tab++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        // --------------- FIN CONTROL : Select --------------------------------------------------
                    }
                    echo $this->miFormulario->division("fin");

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    unset($atributos);

                    $atributos ["id"] = "conceptosFormula";
                    $atributos ["estilo"] = "col-md-12";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {
                        $esteCampo = 'valorConcepto';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['estilo'] = '';
                        $atributos ['marco'] = false;
                        $atributos ['columnas'] = 50;
                        $atributos ['filas'] = 6;
                        $atributos ['tabIndex'] = $tab;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 90;
                        $atributos['deshabilitado'] = true;

                        $atributos ['obligatorio'] = false;
                        $atributos ['etiquetaObligatorio'] = false;
                        $atributos ['validar'] = '';

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] = '';
                        }
                        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $tab++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoTextArea($atributos);
                        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                    }
                    echo $this->miFormulario->division("fin");

                    unset($atributos);

                    $atributos ["id"] = "editarBotonesConcepto";
                    $atributos ["estilo"] = "col-md-12";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {
                        echo "<center><input type=\"button\" id=\"btEditB\" value=\"Editar Concepto\" class=\"btn btn-success\"/></center>";
                    }
                    echo $this->miFormulario->division("fin");


                    unset($atributos);

                    $atributos ["id"] = "ingresoBotonesConcepto";
                    $atributos ["estilo"] = "col-md-12";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {
                        $atributos ["id"] = "botonesPanel1B";
                        $atributos ["estilo"] = "col-md-12";
                        echo $this->miFormulario->division("inicio", $atributos);
                        {
                            echo("Operadores:");
                        }
                        echo $this->miFormulario->division("fin");

                        $atributos ["id"] = "botonesPanel2B";
                        $atributos ["estilo"] = "col-md-12 btn-group";
                        echo $this->miFormulario->division("inicio", $atributos);
                        {
                            echo "<center>";
                            echo "<input type=\"button\" id=\"btOper1B\" value=\"(\" class=\"btn btn-warning\"/>";
                            echo "<input type=\"button\" id=\"btOper2B\" value=\")\" class=\"btn btn-warning\" />";
                            echo "<input type=\"button\" id=\"btOper3B\" value=\"+\" class=\"btn btn-warning\"/>";
                            echo "<input type=\"button\" id=\"btOper4B\" value=\"-\" class=\"btn btn-warning\" />";
                            echo "<input type=\"button\" id=\"btOper5B\" value=\"*\" class=\"btn btn-warning\"/>";
                            echo "<input type=\"button\" id=\"btOper6B\" value=\"÷\" class=\"btn btn-warning\" />";
                            echo "<input type=\"button\" id=\"btOper7B\" value=\"√\" class=\"btn btn-warning\"/>";
                            echo "<input type=\"button\" id=\"btOper8B\" value=\"^\" class=\"btn btn-warning\" />";
                            echo "<input type=\"button\" id=\"btOper9B\" value=\"Borrar\" class=\"btn btn-danger\" />";
                            echo "<input type=\"button\" id=\"btOper10B\" value=\"Insertar\" class=\"btn btn-success\" />";
                            echo "</center>";
                        }
                        echo $this->miFormulario->division("fin");
                    }
                    echo $this->miFormulario->division("fin");
                }
                echo $this->miFormulario->marcoAgrupacion("fin");


                unset($atributos);

                $esteCampo = "marcoDatosVariables";
                $atributos ['id'] = $esteCampo;
                $atributos ["estilo"] = "jqueryui";
                $atributos ['tipoEtiqueta'] = 'inicio';
                $atributos ["leyenda"] = "Panel Variables";
                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
                {

                    $atributos ["id"] = "variables_lista";
                    $atributos ["estilo"] = "col-md-12";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {
                        // ---------------- CONTROL: Select --------------------------------------------------------
                        $esteCampo = 'VariablesList';
                        $atributos['nombre'] = $esteCampo;
                        $atributos['id'] = $esteCampo;
                        $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 230;
                        $atributos['tab'] = $tab;
                        $atributos['seleccion'] = -1;
                        $atributos['evento'] = ' ';
                        $atributos['deshabilitado'] = false;
                        $atributos['limitar'] = 50;
                        $atributos['tamanno'] = 1;
                        $atributos['columnas'] = 1;

                        $atributos ['obligatorio'] = false;
                        $atributos ['etiquetaObligatorio'] = false;
                        $atributos ['validar'] = '';

                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarVariables");
                        $matrizParametros = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");

                        $atributos['matrizItems'] = $matrizParametros;

// 					$matrizItems=array(
// 							array(1,'SMLV'),
// 							array(2,'IVA'),
// 							array(3,'RTF'),
// 							array(4,'HED'),
// 							array(5,'HEN')
// 					);
// 					$atributos['matrizItems'] = $matrizItems;

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] = '';
                        }
                        $atributos ["titulo"] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $tab++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        // --------------- FIN CONTROL : Select --------------------------------------------------
                    }
                    echo $this->miFormulario->division("fin");

                    unset($atributos);
                }
                echo $this->miFormulario->marcoAgrupacion("fin");

                $esteCampo = "marcoDatosCamposFormulacion";
                $atributos ['id'] = $esteCampo;
                $atributos ["estilo"] = "jqueryui";
                $atributos ['tipoEtiqueta'] = 'inicio';
                $atributos ["leyenda"] = "Panel Campos";
                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
                {

                    $atributos ["id"] = "formulacion_campos";
                    $atributos ["estilo"] = "col-md-12";
                    echo $this->miFormulario->division("inicio", $atributos);
                    {

                        $matrizCamposFormulacion = array();
                        $arrayCamposFormulacion = explode(",", $_REQUEST['camposFormulacion']);
                        $cuentaRegistro = 0;
                        $auxiliarcuen = 1;
                        while ($cuentaRegistro < (count($arrayCamposFormulacion) - 1)) {
                            $matrixAux = array();
                            if ($arrayCamposFormulacion[$cuentaRegistro] != 'undefined') {
                                array_push($matrixAux, $auxiliarcuen, $arrayCamposFormulacion[$cuentaRegistro]);
                                array_push($matrizCamposFormulacion, $matrixAux);
                            } else {
                                $auxiliarcuen--;
                            }

                            $cuentaRegistro = $cuentaRegistro + 1;
                            $auxiliarcuen++;
                        }

                        // ---------------- CONTROL: Select --------------------------------------------------------
                        $esteCampo = 'CamposFormulacionList';
                        $atributos['nombre'] = $esteCampo;
                        $atributos['id'] = $esteCampo;
                        $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 230;
                        $atributos['tab'] = $tab;
                        $atributos['seleccion'] = -1;
                        $atributos['evento'] = ' ';

                        $atributos['limitar'] = 50;
                        $atributos['tamanno'] = 1;
                        $atributos['columnas'] = 1;

                        $atributos ['obligatorio'] = false;
                        $atributos ['etiquetaObligatorio'] = false;
                        $atributos ['validar'] = '';
                        if (empty($matrizCamposFormulacion)) {
                            $atributos['deshabilitado'] = true;
                            $matrizCamposFormulacion = array(
                                array(1, 'seleccion'),
                                array(2, 'prueba'),
                            );
                        } else {

                            $atributos['deshabilitado'] = false;
                        }

                        $atributos['matrizItems'] = $matrizCamposFormulacion;



                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] = '';
                        }
                        $atributos ["titulo"] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $tab++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        // --------------- FIN CONTROL : Select --------------------------------------------------
                    }
                    echo $this->miFormulario->division("fin");

                    unset($atributos);
                }
                echo $this->miFormulario->marcoAgrupacion("fin");
                //***********************************************************************************************
                //***********************************************************************************************
                //Campos atributos pagina anterior Info Basica
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------

                unset($atributos);
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'variablesCampoFor';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;
                if (isset($_REQUEST ['variablesCampo'])) {
                    $atributos ['valor'] = $_REQUEST ['variablesCampo'];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['deshabilitado'] = false;
                $atributos ['maximoTamanno'] = '';
                $tab++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
                //---------------------------------
                //
                //
                //
                unset($atributos);
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'camposInfoExtraFor';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;
                if (isset($_REQUEST ['camposInfoExtra'])) {
                    $atributos ['valor'] = $_REQUEST ['camposInfoExtra'];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['deshabilitado'] = false;
                $atributos ['maximoTamanno'] = '';
                $tab++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                $esteCampo = 'tipoNovedadFor';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;

                if (isset($_REQUEST ['tipoNovedadInfo'])) {
                    $atributos ['valor'] = $_REQUEST ['tipoNovedadInfo'];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['deshabilitado'] = false;
                $atributos ['maximoTamanno'] = '';
                $tab++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'categoriaNovedadFor';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;

                if (isset($_REQUEST ['categoriaConceptosInfo'])) {
                    $atributos ['valor'] = $_REQUEST ['categoriaConceptosInfo'];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['deshabilitado'] = false;
                $atributos ['maximoTamanno'] = '';
                $tab++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------

                unset($atributos);

                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'nombreFor';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;

                if (isset($_REQUEST ['nombreInfo'])) {
                    $atributos ['valor'] = $_REQUEST ['nombreInfo'];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['deshabilitado'] = false;
                $atributos ['maximoTamanno'] = '';
                $tab++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'simboloFor';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;

                if (isset($_REQUEST ['simboloInfo'])) {
                    $atributos ['valor'] = $_REQUEST ['simboloInfo'];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['deshabilitado'] = false;
                $atributos ['maximoTamanno'] = '';
                $tab++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'leyRegistrosFor';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;

                if (isset($_REQUEST ['leyInfo'])) {
                    $atributos ['valor'] = $_REQUEST ['leyInfo'];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['deshabilitado'] = false;
                $atributos ['maximoTamanno'] = '';
                $tab++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'naturalezaFor';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;

                if (isset($_REQUEST ['naturalezaInfo'])) {
                    $atributos ['valor'] = $_REQUEST ['naturalezaInfo'];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['deshabilitado'] = false;
                $atributos ['maximoTamanno'] = '';
                $tab++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                unset($atributos);
                $esteCampo = 'descripcionFor';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;

                if (isset($_REQUEST ['descripcionInfo'])) {
                    $atributos ['valor'] = $_REQUEST ['descripcionInfo'];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['deshabilitado'] = false;
                $atributos ['maximoTamanno'] = '';
                $tab++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                //***********************************************************************************************
                //***********************************************************************************************
            }
            echo $this->miFormulario->division("fin");
        }
        echo $this->miFormulario->division("fin");

        // ------------------Division para los botones-------------------------
        $atributos ["id"] = "botonesFormula";
        $atributos ["estilo"] = "marcoBotones";
        $atributos ["titulo"] = "Enviar Información";
        echo $this->miFormulario->division("inicio", $atributos);
        {
            // -----------------CONTROL: Botón ----------------------------------------------------------------
            $esteCampo = 'siguiente';
            $atributos ["id"] = $esteCampo;
            $atributos ["tabIndex"] = $tab;
            $atributos ["tipo"] = 'boton';
            // submit: no se coloca si se desea un tipo button genérico
            $atributos ['submit'] = true;
            $atributos ["estiloMarco"] = '';
            $atributos ["estiloBoton"] = 'jqueryui';
            // verificar: true para verificar el formulario antes de pasarlo al servidor.
            $atributos ["verificar"] = true;
            $atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
            $atributos ["valor"] = $this->lenguaje->getCadena($esteCampo);
            $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
            $tab++;

            // Aplica atributos globales al control
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoBoton($atributos);

            // -----------------FIN CONTROL: Botón -----------------------------------------------------------
        }
        echo $this->miFormulario->division("fin");


        echo $this->miFormulario->marcoAgrupacion('fin');
        // ------------------- SECCION: Paso de variables ------------------------------------------------
        /**
         * En algunas ocasiones es útil pasar variables entre las diferentes páginas.
         * SARA permite realizar esto a través de tres
         * mecanismos:
         * (a). Registrando las variables como variables de sesión. Estarán disponibles durante toda la sesión de usuario. Requiere acceso a
         * la base de datos.
         * (b). Incluirlas de manera codificada como campos de los formularios. Para ello se utiliza un campo especial denominado
         * formsara, cuyo valor será una cadena codificada que contiene las variables.
         * (c) a través de campos ocultos en los formularios. (deprecated)
         */
        // En este formulario se utiliza el mecanismo (b) para pasar las siguientes variables:
        // Paso 1: crear el listado de variables
        //$valorCodificado = "actionBloque=" . $esteBloque ["nombre"]; //Ir pagina Funcionalidad
        //$valorCodificado = "actionBloque=" . $esteBloque ["nombre"];
        $valorCodificado = "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina'); //Frontera mostrar formulario
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=condicion";
        /**
         * SARA permite que los nombres de los campos sean dinámicos.
         * Para ello utiliza la hora en que es creado el formulario para
         * codificar el nombre de cada campo.
         */
        $valorCodificado .= "&campoSeguro=" . $_REQUEST['tiempo'];
        // Paso 2: codificar la cadena resultante
        $valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);
        $atributos ["id"] = "formSaraData"; // No cambiar este nombre
        $atributos ["tipo"] = "hidden";
        $atributos ['estilo'] = '';
        $atributos ["obligatorio"] = false;
        $atributos ['marco'] = true;
        $atributos ["etiqueta"] = "";
        $atributos ["valor"] = $valorCodificado;
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN SECCION: Paso de variables -------------------------------------------------
        // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
        // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
        // Se debe declarar el mismo atributo de marco con que se inició el formulario.
        $atributos ['marco'] = true;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario($atributos);
        return true;
    }

    function mensaje() {
        // Si existe algun tipo de error en el login aparece el siguiente mensaje
        $mensaje = $this->miConfigurador->getVariableConfiguracion('mostrarMensaje');
        $this->miConfigurador->setVariableConfiguracion('mostrarMensaje', null);
        if ($mensaje) {
            $tipoMensaje = $this->miConfigurador->getVariableConfiguracion('tipoMensaje');
            if ($tipoMensaje == 'json') {
                $atributos ['mensaje'] = $mensaje;
                $atributos ['json'] = true;
            } else {
                $atributos ['mensaje'] = $this->lenguaje->getCadena($mensaje);
            }
            // -------------Control texto-----------------------
            $esteCampo = 'divMensaje';
            $atributos ['id'] = $esteCampo;
            $atributos ["tamanno"] = '';
            $atributos ["estilo"] = 'information';
            $atributos ["etiqueta"] = '';
            $atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
            echo $this->miFormulario->campoMensaje($atributos);
            unset($atributos);
        }
        return true;
    }

}

$miFormulario = new Formulario($this->lenguaje, $this->miFormulario, $this->sql);
$miFormulario->formulario();
$miFormulario->mensaje();
?>