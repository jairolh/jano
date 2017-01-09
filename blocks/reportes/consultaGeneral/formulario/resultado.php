<?php

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class registrarForm {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;

    function __construct($lenguaje, $formulario, $sql) {
        $this->miConfigurador = \Configurador::singleton();

        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');

        $this->lenguaje = $lenguaje;

        $this->miFormulario = $formulario;

        $this->miSql = $sql;
    }

    function miForm() {

// Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        $miPaginaActual = $this->miConfigurador->getVariableConfiguracion('pagina');

        $directorio = $this->miConfigurador->getVariableConfiguracion("host");
        $directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
        $directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque .= $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
        $rutaBloque .= $esteBloque ['grupo'] . $esteBloque ['nombre'];

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

// -------------------------------------------------------------------------------------------------
        $conexion = "inventarios";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $conexion = "sicapital";
        $esteRecursoDBO = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);


        $datos_consulta = array(
            // Filtro 1
            'sede' => (isset($_REQUEST['sede']) ? $_REQUEST['sede'] : ''),
            'dependencia' => (isset($_REQUEST['dependencia']) ? $_REQUEST['dependencia'] : ''),
            'funcionario' => (isset($_REQUEST['nombreFuncionario']) ? $_REQUEST['nombreFuncionario'] : ''),
            //Entrada
            'numero_entrada' => (isset($_REQUEST['numero_entrada']) ? $_REQUEST['numero_entrada'] : ''),
            'vigencia_entrada' => (isset($_REQUEST['vigencia_entrada']) ? $_REQUEST['vigencia_entrada'] : ''),
            'proveedor' => (isset($_REQUEST['proveedor']) ? $_REQUEST['proveedor'] : ''),
            'tipo_entrada' => (isset($_REQUEST['tipo_entrada']) ? $_REQUEST['tipo_entrada'] : ''),
            'fecha_inicio' => (isset($_REQUEST['fecha_inicio']) ? $_REQUEST['fecha_inicio'] : ''),
            'fecha_final' => (isset($_REQUEST['fecha_final']) ? $_REQUEST['fecha_final'] : ''),
            //Salida
            'numero_salida' => (isset($_REQUEST['numero_salida']) ? $_REQUEST['numero_salida'] : ''),
            'vigencia_salida' => (isset($_REQUEST['vigencia_salida']) ? $_REQUEST['vigencia_salida'] : ''),
            //Elemento
            'numero_placa' => (isset($_REQUEST['numero_placa']) ? $_REQUEST['numero_placa'] : ''),
            'numero_serie' => (isset($_REQUEST['numero_serie']) ? $_REQUEST['numero_serie'] : ''),
            //Bajas
            'IDbaja' => (isset($_REQUEST['IDbaja']) ? $_REQUEST['IDbaja'] : ''),
            'estado_baja' => (isset($_REQUEST['estado_baja']) ? $_REQUEST['estado_baja'] : ''),
            //Faltante
            'IDfaltante' => (isset($_REQUEST['IDfaltante']) ? $_REQUEST['IDfaltante'] : ''),
            //Hurto
            'IDhurto' => (isset($_REQUEST['IDhurto']) ? $_REQUEST['IDhurto'] : ''),
            //Traslado
            'IDtraslado' => (isset($_REQUEST['IDtraslado']) ? $_REQUEST['IDtraslado'] : ''),
        );

        switch ($_REQUEST['selec_tipoConsulta']) {

            //Entradas
            case 1:
                $cadenaSql = $this->miSql->getCadenaSql('consultarEntrada', $datos_consulta);
                $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
                break;
            //Salidas
            case 2:
                $cadenaSql = $this->miSql->getCadenaSql('consultarSalida', $datos_consulta);
                $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
                break;
            //Elementos
            case 3:
                $cadenaSql = $this->miSql->getCadenaSql('consultarElementos', $datos_consulta);
                $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
                break;
            //Traslados
            case 4:
                $cadenaSql = $this->miSql->getCadenaSql('consultarTraslados', $datos_consulta);
                $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
                break;
            //Sobrantes o faltantes
            case 6:
                $cadenaSql = $this->miSql->getCadenaSql('consultarSobranteFaltante', $datos_consulta);
                $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
                break;
            //Bajas
            case 5:
                $cadenaSql = $this->miSql->getCadenaSql('consultarBajas', $datos_consulta);
                $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
                break;
            //Inventario
            case 7:
                $cadenaSql = $this->miSql->getCadenaSql('consultarInventario', $datos_consulta);
                $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
                break;

            default:
                $datos = array();
                break;
        }
     

// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
        $esteCampo = $esteBloque ['nombre'];
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
        $atributos ['tipoFormulario'] = 'multipart/form-data';
// Si no se coloca, entonces toma el valor predeterminado 'POST'
        $atributos ['metodo'] = 'POST';
// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
        $atributos ['action'] = 'index.php';
// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );
// Si no se coloca, entonces toma el valor predeterminado.
        $atributos ['estilo'] = '';
        $atributos ['marco'] = true;
        $tab = 1;
// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos ['tipoEtiqueta'] = 'inicio';
        echo $this->miFormulario->formulario($atributos);
// ---------------- SECCION: Controles del Formulario -----------------------------------------------
        $esteCampo = "marcoDatosBasicos";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = "Resultado Consulta General";
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);

// ------------------Division para los botones-------------------------
        $atributos ["id"] = "botones";
        $atributos ["estilo"] = "marcoBotones";
        echo $this->miFormulario->division("inicio", $atributos);

// -----------------CONTROL: Botón ----------------------------------------------------------------
        $esteCampo = 'botonRegresar';
        $atributos ["id"] = $esteCampo;
        $atributos ["tabIndex"] = $tab;
        $atributos ["tipo"] = 'boton';
// submit: no se coloca si se desea un tipo button genérico
        $atributos ['submit'] = true;
        $atributos ["estiloMarco"] = '';
        $atributos ["estiloBoton"] = 'jqueryui';
// verificar: true para verificar el formulario antes de pasarlo al servidor.
        $atributos ["verificar"] = '';
        $atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
        $atributos ["valor"] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
        $tab ++;

// Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoBoton($atributos);
// -----------------FIN CONTROL: Botón -----------------------------------------------------------
// ---------------------------------------------------------
// ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");

        if ($datos) {
            echo $this->miFormulario->tablaReporte($datos);
// Fin de Conjunto de Controles
// echo $this->miFormulario->marcoAgrupacion("fin");
        } else {
            $mensaje = "No Se Encontraron<br>Datos Relacionados con la Búsqueda";
// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            $esteCampo = 'mensajeRegistro';
            $atributos ['id'] = $esteCampo;
            $atributos ['tipo'] = 'error';
            $atributos ['estilo'] = 'textoCentrar';
            $atributos ['mensaje'] = $mensaje;

            $tab ++;

// Aplica atributos globales al control
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->cuadroMensaje($atributos);
// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
        }

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

        $valorCodificado = "actionBloque=" . $esteBloque ["nombre"];
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=regresar";
        $valorCodificado .= "&redireccionar=regresar";
        /**
         * SARA permite que los nombres de los campos sean dinámicos.
         * Para ello utiliza la hora en que es creado el formulario para
         * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
         * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
         * (b) asociando el tiempo en que se está creando el formulario
         */
        $valorCodificado .= "&tiempo=" . time();
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

        $atributos ['marco'] = true;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario($atributos);
    }

}

$miSeleccionador = new registrarForm($this->lenguaje, $this->miFormulario, $this->sql);

$miSeleccionador->miForm();
?>
