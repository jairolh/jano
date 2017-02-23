<?php

namespace registro\loginjano;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class FormularioRegistro {

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

    function formulario() {
        //INVOCA EL BANNER
        include_once 'bannerClaveForm.php';
        $miBanner = new cabecera($this->lenguaje, $this->miFormulario);
        $miBanner->estructura();
        
        $conexion = 'estructura';
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);   
        
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

        $atributosGlobales ['campoSeguro'] = 'true';


        // -------------------------------------------------------------------------------------------------
        // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
        $esteCampo = $esteBloque ['nombre'];
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        /**
         * Nuevo a partir de la versión 1.0.0.2, se utiliza para crear de manera rápida el js asociado a
         * validationEngine.
         */
        //$atributos ['validar'] = true;

        // Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
        $atributos ['tipoFormulario'] = '';

        // Si no se coloca, entonces toma el valor predeterminado 'POST'
        $atributos ['metodo'] = 'POST';

        // Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
        $atributos ['action'] = 'index.php';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo);

        // Si no se coloca, entonces toma el valor predeterminado.
        $atributos ['estilo'] = '';
        $atributos ['marco'] = false;
        $tab = 1;
        // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------



        // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->formulario($atributos);

        //----------------------------------------------------------------------

        //-----------------------------------MENSAJE--------------------------------------------------------
        $esteCampo = 'mensajeRegistro';
        $atributos ['tipo'] = $esteCampo;
        $atributos ['mensaje'] = $this->lenguaje->getCadena($esteCampo);
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);
        
        //-------------------------RECUPERACION DE CAMPOS-----------------------------

        //$usuario = $_REQUEST['usuario'];
        //$fecha = $_REQUEST['fecha'];
        
        //-----------------------------------------------------------------------------
// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'identificacion';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['estiloMarco'] = '';
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = 0;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['validar']="required, minSize[5], custom[integer]";
        if (isset ( $_REQUEST [$esteCampo] )) 
            {	$atributos ['valor'] = $_REQUEST [$esteCampo];}
        else{	$atributos ['valor'] = '';}
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        //$atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 60;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 170;
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        unset ( $atributos );
        // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
        // ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
        $esteCampo = 'tipo_identificacion';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['seleccion'] = - 1;
        $atributos ['anchoEtiqueta'] = 170;
        $atributos ['evento'] = '';

        if (isset ( $_REQUEST [$esteCampo]  ))
             {$atributos ['seleccion'] = $_REQUEST [$esteCampo] ;}
        else {	$atributos ['seleccion'] = - 1;}
        
        $atributos ['deshabilitado'] = false;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['estilo'] = "jqueryui";
        $atributos ['validar'] = "required";
        $atributos ['limitar'] = true;
        $atributos ['anchoCaja'] = 60;
        $atributos ['miEvento'] = '';
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "tipoIdentificacion" );
        $matrizItems = array (array (0,' '));
        $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
        $atributos ['matrizItems'] = $matrizItems;
        // $atributos['miniRegistro']=;
        // $atributos ['baseDatos'] = "inventarios";
        // $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroLista ( $atributos );
        unset ( $atributos );
        // ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'nombres';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['estiloMarco'] = '';
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = 0;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['validar']="required, minSize[5]";
        if (isset ( $_REQUEST [$esteCampo] )) 
            {	$atributos ['valor'] = $_REQUEST [$esteCampo];}
        else{	$atributos ['valor'] = '';}
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 60;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 170;
        $tab ++;
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        unset ( $atributos );
        // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'apellidos';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['estiloMarco'] = '';
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = 0;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['validar']="required, minSize[5]";
        if (isset ( $_REQUEST [$esteCampo] )) 
            {	$atributos ['valor'] = $_REQUEST [$esteCampo];}
        else{	$atributos ['valor'] = '';}
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 60;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 170;
        $tab ++;
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        unset ( $atributos );
        // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'correo';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['estiloMarco'] = '';
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = 0;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['validar']="required, custom[email]";
        if (isset ( $_REQUEST [$esteCampo] )) 
            {	$atributos ['valor'] = $_REQUEST [$esteCampo];}
        else{	$atributos ['valor'] = '';}
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 60;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 170;
        $tab ++;
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        unset ( $atributos );
        // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'telefono';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['estiloMarco'] = '';
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = 0;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['validar']="required,minSize[7],custom[phone]";
        if (isset ( $_REQUEST [$esteCampo] )) 
            {	$atributos ['valor'] = $_REQUEST [$esteCampo];}
        else{	$atributos ['valor'] = '';}
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 60;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 170;
        $tab ++;
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        unset ( $atributos );


        
        //------------------------------------------------------------------------------------
        $esteCampo = 'clave';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'password';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['anchoEtiqueta'] = 170;
        $atributos ['validar'] = 'required,minSize[8],maxSize[16],custom[minNumberChars],custom[minLowerAlphaChars],';
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 60;
        $atributos ['maximoTamanno'] = '16';
        $atributos ['anchoEtiqueta'] = 170;
        $tab ++;
        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        //------------------------------------------------------------------------------------------

        
        
        //------------------------------------------------------------------------------------
        $esteCampo = 'verificacionNuevaClave';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'password';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['anchoEtiqueta'] = 170;
        $atributos ['validar'] = 'required,minSize[8],maxSize[16],custom[minNumberChars],custom[minLowerAlphaChars], passwordEquals[nuevaClave]';
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 60;
        $atributos ['maximoTamanno'] = '16';
        $tab ++;
        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        //------------------------------------------------------------------------------------------

        
        $atributos ["id"] = "botones";
        $atributos ["estilo"] = "marcoBotones";
        echo $this->miFormulario->division("inicio", $atributos);
        unset($atributos);

        // -----------------CONTROL: Botón ----------------------------------------------------------------
        $esteCampo = 'botonEnviar';
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
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoBoton($atributos);
        unset($atributos);

        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");



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

        $valorCodificado = "action=" . $esteBloque ["nombre"]; //Ir pagina Funcionalidad
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );//Frontera mostrar formulario
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=registrarUsuario";
        //$valorCodificado .= "&fecha=".$fecha;
        /**
         * SARA permite que los nombres de los campos sean dinámicos.
         * Para ello utiliza la hora en que es creado el formulario para
         * codificar el nombre de cada campo.
         */
        if(!isset($_REQUEST ['tiempo']))$_REQUEST ['tiempo']='';
        $valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
        // Paso 2: codificar la cadena resultante
        $valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );

        $atributos ["id"] = "formSaraData"; // No cambiar este nombre
        $atributos ["tipo"] = "hidden";
        $atributos ['estilo'] = '';
        $atributos ["obligatorio"] = false;
        $atributos ['marco'] = true;
        $atributos ["etiqueta"] = "";
        $atributos ["valor"] = $valorCodificado;
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        unset ( $atributos );

        // ----------------FIN SECCION: Paso de variables -------------------------------------------------
        // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
        // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
        // Se debe declarar el mismo atributo de marco con que se inició el formulario.
        $atributos ['marco'] = true;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario($atributos);
    }

    function mensaje() {

        // Si existe algun tipo de error en el login aparece el siguiente mensaje
        $mensaje = $this->miConfigurador->getVariableConfiguracion('mostrarMensaje');
        $this->miConfigurador->setVariableConfiguracion('mostrarMensaje', null);

        if (isset($_REQUEST ['error'])) {
            if ($_REQUEST ['error'] == 'formularioExpirado') {
                $atributos ["estilo"] = 'information';
            } else {
                $atributos ["estilo"] = 'error';
            }

            // -------------Control texto-----------------------
            $esteCampo = 'divMensaje';
            $atributos ['id'] = $esteCampo;
            $atributos ["tamanno"] = '';
            $atributos ["etiqueta"] = '';
            $atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
            $atributos ['mensaje'] = $this->lenguaje->getCadena($_REQUEST ['error']);
            echo $this->miFormulario->campoMensaje($atributos);
            unset($atributos);
        }
        return true;
    }

}

$miFormulario = new FormularioRegistro($this->lenguaje, $this->miFormulario, $this->sql);
$miFormulario->formulario();
$miFormulario->mensaje();
?>