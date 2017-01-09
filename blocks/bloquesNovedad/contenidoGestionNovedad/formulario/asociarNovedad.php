<?php

namespace bloquesNovedad\contenidoGestionNovedad\formulario;

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
        $directorio = $this->miConfigurador->getVariableConfiguracion("host");
        $directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
        $directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");
        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
        $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];



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

        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        //var_dump($primerRecursoDB);
        //exit;
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
        $atributos ['marco'] = false;
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
        $atributos ["leyenda"] = "Gestión de Novedades del Empleado: " . $_REQUEST['nombres'] . " " . $_REQUEST['apellidos'];
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        // --------------------------------------------------------------------------------------------------

        $atributos ["id"] = "botones";
        $atributos ["estilo"] = "marcoBotones";
        echo $this->miFormulario->division("inicio", $atributos); {
            // -----------------CONTROL: Botón ----------------------------------------------------------------
            $esteCampo = 'botonAsociar';
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
        }

        echo $this->miFormulario->division("fin", $atributos);

         $cedula= (int)$_REQUEST['cedula'];
        
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarIdFuncionario", $cedula);
        $matrizFuncionario = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
        $longitudFuncionario = count($matrizFuncionario);
        
        
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarRegistrosDeNovedades", $matrizFuncionario[0][0]);
        $matrizItems = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
        $longitud = count($matrizItems);

        $i = 0;


        echo '<table id="tablaNovedad" class="display" cellspacing="0" width="100%"> '
        . '<thead style="display: table-row-group"><tr><th>' . "NOMBRE" . '</th><th>' . "SIMBOLO" . '</th> <th>' . "DESCRIPCIÓN" . '</th> <th>' . "TIPO" . '</th> <th>' . "NATURALEZA" . '</th><th>' . "ESTADO" . '</th><th>' . "VER DETALLE" . '</th><th>' . "MODIFICAR" . '</th><th>' . "ACTIVAR" . '</th></tr></thead>
 
                  .  <tbody>';
        if (!empty($matrizItems)) {
            while ($i < $longitud) {
                echo "<tr><td>" . $matrizItems[$i][0] . "</td>";
                echo "<td>" . $matrizItems[$i][1] . "</td>";
                echo "<td>" . $matrizItems[$i][2] . "</td>";
                echo "<td>" . $matrizItems[$i][3] . "</td>";
                echo "<td>" . $matrizItems[$i][4] . "</td>";
                echo "<td>" . $matrizItems[$i][5] . "</td>";
                $variableVD = "pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
                ; // pendiente la pagina para modificar parametro
                $variableVD .= "&opcion=verdetalle";
                $variableVD .= "&bloque=" . $esteBloque ['nombre'];
                $variableVD .="&tamaño=" . $longitud;
                $variableVD .= "&variable=" . $matrizItems[$i][6];
                $variableVD .= "&tipo=" . $matrizItems[$i][3];
                $variableVD .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                $variableVD = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVD, $directorio);

                echo "<td><center><a href='" . $variableVD . "'>
                          <img src='" . $rutaBloque . "/css/images/verDetalle.png' width='25px'>
                          </a></center> </td>";

                $variableMOD = "pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
                ; // pendiente la pagina para modificar parametro
                $variableMOD .= "&opcion=modificar";
                $variableMOD .= "&bloque=" . $esteBloque ['nombre'];
                $variableMOD .="&tamaño=" . $longitud;
                $variableMOD .= "&variable=" . $matrizItems[$i][6];
                $variableMOD .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                $variableMOD = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableMOD, $directorio);

                echo "<td><center><a href='" . $variableMOD . "'>
                          <img src='" . $rutaBloque . "/css/images/modificar.png' width='25px'>
                          </a></center> </td>";

                $variableACT = "pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
                ; // pendiente la pagina para modificar parametro
                $variableACT .= "&opcion=inactivar";
                $variableACT .= "&bloque=" . $esteBloque ['nombre'];
                $variableACT .="&tamaño=" . $longitud;
                $variableACT .= "&variable=" . $matrizItems[$i][6];
                $variableACT .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                $variableACT = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableACT, $directorio);

                echo "<td><center><a href='" . $variableACT . "'>";
                if ($matrizItems[$i][5] == 'Activo') {
                    echo "<img src='" . $rutaBloque . "/css/images/desactivacion.png' width='25px'>";
                } else {
                    echo "<img src='" . $rutaBloque . "/css/images/activacion.png' width='25px'>";
                }

                echo "</a></center> </td></tr>";





                $i+=1;
            }
        }
        echo '</tbody></table>';



        
        // ------------------- SECCION: Paso de variables ------------------------------------------------




        /**


          // ---------------- CONTROL: Tabla Cargos -----------------------------------------------
          //
          //        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarRegistroxCargo");
          //        $matrizItems=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
          //
          //        echo $this->miFormulario->tablaReporte($matrizItems);
          //
          //       echo $this->miFormulario->marcoAgrupacion ( 'fin' );

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
        $valorCodificado = "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina'); //Frontera mostrar formulario
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];

        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=asociacionUsuario";
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
        $atributos ['marco'] = false;
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