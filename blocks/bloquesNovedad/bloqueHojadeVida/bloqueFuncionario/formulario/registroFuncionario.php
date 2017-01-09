<?php 
namespace bloquesNovedad\bloqueHojadeVida\bloqueFuncionario\formulario;



if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


class Formulario {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;

    function __construct($lenguaje, $formulario, $sql) {

        $this->miConfigurador = \Configurador::singleton ();

        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );

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
        $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

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
        $_REQUEST['tiempo']=time();
        
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
        $atributos ['titulo'] = false;//$this->lenguaje->getCadena ( $esteCampo );

        // Si no se coloca, entonces toma el valor predeterminado.
        $atributos ['estilo'] = '';
        $atributos ['marco'] = false;
        $tab = 1;
        // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		
        
        // ---------------- INICIO: Lista Variables Control--------------------------------------------------------
        
        //Establecimiento Limite de Campos y Referencias Dinamicas **************************************************
        //***********************************************************************************************************
        $cantidad_referencias = 8;
        $cantidad_referencias_info = 20;
        $cantidad_idiomas = 7;
        $cantidad_experiencia = 10;
        $cantidad_referencias_per = 20;
        
        //Para cambiar revisar el archivo ajax.php para ajustar los limites de los campos y las funciones AJAX
        //***********************************************************************************************************
        //***********************************************************************************************************
        
        // ---------------- FIN: Lista Variables Control--------------------------------------------------------

        
        // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos ['tipoEtiqueta'] = 'inicio';
        echo $this->miFormulario->formulario ( $atributos );

        // ---------------- SECCION: Controles del Formulario -----------------------------------------------
        
        // ---------------- CONTROL: Cuadro Mensaje Titulo --------------------------------------------------
         
        $esteCampo = "AgrupacionGeneral";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = "<center>REGISTRO HOJA DE VIDA</center>";
        echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
        {
         
        // --------------------------------------------------------------------------------------------------
       
        
        $esteCampo = "novedadesIdentificacion";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
        echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
        unset ( $atributos );
        {	
        	$atributos ["id"] = "botonDatos";
        	$atributos ["estilo"] = "botonDatos";
        	echo $this->miFormulario->division ( "inicio", $atributos );
        	{
	        	echo "<button id=\"ocultarb1\" ALIGN=RIGHT name=\"menos1\" class=\"\">Siguiente</button>";
        	}
        	echo $this->miFormulario->division ( "fin" );
        	
        	$atributos ["id"] = "contentDatos1";
        	$atributos ["estilo"] = "marcoBotones";
        	echo $this->miFormulario->agrupacion ( "inicio", $atributos );
        	{
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioIdentificacion';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        
	        $cadenaSql = $this->miSql->getCadenaSql("buscarTipoDoc", $_REQUEST['funcionarioDocumentoBusqueda']);
	        $matrizDoc = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
	        
	        $atributos['seleccion'] = $matrizDoc[0][0];
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['ajax_function'] = "";
	        $atributos ['ajax_control'] = $esteCampo;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        //var_dump($this->miSql->getCadenaSql("buscarRegistro"));
	        
	                 $matrizItems=array(
	                 		array(1,'Cédula de Ciudadanía'),
	                 		array(2,'Tarjeta de Identidad'),
	                 		array(3,'Cédula de extranjería'),
	                 		array(4,'Pasaporte')
	                 );
	                 
	        $atributos['matrizItems'] = $matrizItems;
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioDocumento';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos ['estiloEtiqueta'] = 'labelTamano';
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, minSize[5], custom[onlyNumberSp]';
	        
	        $atributos ['valor'] = $_REQUEST['funcionarioDocumentoBusqueda'];
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = true;
	        $atributos ['tamanno'] = 15;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        
	        unset($atributos);
	        $esteCampo = 'funcionarioSoporteIden';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'hidden';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        //$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = '';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioFechaExpDoc';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, custom[date]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 10;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'lugarExp';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	       
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioPais';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	         
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	         
	        $atributos['matrizItems'] = $matrizItems;
	         
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	         
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select ----------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioDepartamento';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioCiudad';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	                
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'nombresCampos';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	        
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioPrimerApellido';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, minSize[1], custom[onlyLetterSp]';
	        
	        $cadenaSql = $this->miSql->getCadenaSql("buscarPrimerApellido", $_REQUEST['funcionarioDocumentoBusqueda']);
	        $matrizDoc = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
	        
	        $atributos ['valor'] = $matrizDoc[0][0];
	        
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = true;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioSegundoApellido';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, minSize[1], custom[onlyLetterSp]';
	        
	        $cadenaSql = $this->miSql->getCadenaSql("buscarSegundoApellido", $_REQUEST['funcionarioDocumentoBusqueda']);
	        $matrizDoc = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
	         
	        $atributos ['valor'] = $matrizDoc[0][0];
	        
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = true;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioPrimerNombre';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, minSize[1], custom[onlyLetterSp]';
	        
	        $cadenaSql = $this->miSql->getCadenaSql("buscarPrimerNombre", $_REQUEST['funcionarioDocumentoBusqueda']);
	        $matrizDoc = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
	         
	        $atributos ['valor'] = $matrizDoc[0][0];
	        
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] =true;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioSegundoNombre';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[onlyLetterSp]';
	        
	        $cadenaSql = $this->miSql->getCadenaSql("buscarSegundoNombre", $_REQUEST['funcionarioDocumentoBusqueda']);
	        $matrizDoc = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
	         
	        $atributos ['valor'] = $matrizDoc[0][0];
	        
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = true;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioOtrosNombres';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = true;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
        	}
	        echo $this->miFormulario->agrupacion ( "fin" );
        }
        echo $this->miFormulario->marcoAgrupacion ( 'fin' );
        
        $esteCampo = "novedadesDatosPersonales";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
        echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
        unset ( $atributos );
        {
        	
        	$atributos ["id"] = "botonDatos";
        	$atributos ["estilo"] = "botonDatos";
        	echo $this->miFormulario->division ( "inicio", $atributos );
        	{
        		echo "<button id=\"mostrarb2\" name=\"mas1\" ALIGN=RIGHT class=\"\">Atrás</button>";
        		echo "<button id=\"ocultarb2\" ALIGN=RIGHT name=\"menos1\" class=\"\">Siguiente</button>";
        	}
        	echo $this->miFormulario->division ( "fin" );
        	 
        	$atributos ["id"] = "contentDatos2";
        	$atributos ["estilo"] = "marcoBotones";
        	echo $this->miFormulario->agrupacion ( "inicio", $atributos );
        	{
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'novedadesDatosNacimiento';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	     
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioFechaNacimiento';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, custom[date]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 10;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioPaisNacimiento';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        /*
	        $matrizItems=array(
	        		array(1,'Argentina'),
	        		array(2,'Peru'),
	        		array(3,'Chile'),
	        		array(4,'Colombia')
	        
	        );*/
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select ----------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioDepartamentoNacimiento';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        /*
	        $matrizItems=array(
	        		array(1,'Cundinamarca'),
	        		array(2,'Antioquia'),
	        		array(3,'Santander'),
	        		array(4,'Bolivar'),
	        		array(5,'Bogotá D.C.')
	        
	        );*/
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioCiudadNacimiento';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        /*
	        $matrizItems=array(
	        		array(1,'Bogota D.C.'),
	        		array(2,'Medellin'),
	        		array(3,'Barranquilla'),
	        		array(4,'Cali'),
	        		array(5,'Cucuta'),
	        		array(6,'Bucaramanga')
	        
	        );*/
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'novedadesDatosGeneroEtc';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	           
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioGenero';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $matrizItems=array(
	        		array(1,'Masculino'),
	        		array(2,'Femenino')
	        
	        );
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioEstadoCivil';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	                 $matrizItems=array(
	                 		array(1,'Soltero'),
	                 		array(2,'Casado'),
	                 		array(3,'Unión Libre'),
	                 		array(4,'Viudo'),
	                 		array(5,'Divorciado')
	        
	                 );
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioEdad';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, minSize[2]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = true;
	        $atributos ['tamanno'] = 3;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioTipoSangre';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = ' ';
	        
	        $matrizItems=array(
	        		array(1,'A'),
	        		array(2,'B'),
	        		array(3,'O'),
	        		array(4,'AB')
	        );
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioSangreRH';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = ' ';
	        
	        $matrizItems=array(
	        		array(1,'Positivo'),
	        		array(2,'Negativo')      
	        );
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioTipoLibreta';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = ' ';
	        
	        $matrizItems=array(
	        		array(1,'Primera'),
	        		array(2,'Segunda')
	        
	        );
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioNumeroLibreta';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[onlyNumberSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = true;
	        $atributos ['tamanno'] = 20;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioDistritoLibreta';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[onlyNumberSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = true;
	        $atributos ['tamanno'] = 2;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        unset($atributos);
	        $esteCampo = 'funcionarioSoporteLibreta';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'hidden';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        //$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = '';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = true;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'novedadesDatosCaracterizacion';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioGrupoEtnico';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['anchoEtiqueta'] = 300;
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = ' ';
	        
	        $matrizItems=array(
	        		array(1,'Afrodescendientes'),
	        		array(2,'Indígenas'),
	        		array(3,'Raizales'),
	        		array(4,'Rom')
	        
	        );
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioGrupoLGBT';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = ' ';
	        
	        $matrizItems=array(
	        		array(1,'Si'),
	        		array(2,'No')
	        );
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioCabezaFamilia';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = ' ';
	        
	        $matrizItems=array(
	        		array(1,'Si'),
	        		array(2,'No')
	        );
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioPersonasCargo';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $matrizItems=array(
	        		array(1,'Si'),
	        		array(2,'No')
	        );
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        unset($atributos);
	        $esteCampo = 'funcionarioSoporteCaracterizacion';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'hidden';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        //$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = '';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
        	}
	        echo $this->miFormulario->agrupacion ( "fin" );
        }
        echo $this->miFormulario->marcoAgrupacion ( 'fin' );
        
        $esteCampo = "novedadesDatosCiudadania";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
        echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
        unset ( $atributos );
        {
        	
        	$atributos ["id"] = "botonDatos";
        	$atributos ["estilo"] = "botonDatos";
        	echo $this->miFormulario->division ( "inicio", $atributos );
        	{
        		echo "<button id=\"mostrarb3\" name=\"mas1\" ALIGN=RIGHT class=\"\">Atrás</button>";
        		echo "<button id=\"ocultarb3\" ALIGN=RIGHT name=\"menos1\" class=\"\">Siguiente</button>";
        	}
        	echo $this->miFormulario->division ( "fin" );
        	 
        	$atributos ["id"] = "contentDatos3";
        	$atributos ["estilo"] = "marcoBotones";
        	echo $this->miFormulario->agrupacion ( "inicio", $atributos );
        	{
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'novedadesDatosResidencia';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	           
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoNacionalidad';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, minSize[3], custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 20;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoPais';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	         
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	         
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	         
	        $atributos['matrizItems'] = $matrizItems;
	         
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	         
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select ----------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoDepartamento';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoCiudad';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoLocalidad';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, minSize[3], custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 20;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoBarrio';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, minSize[3], custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 20;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoDireccion';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, minSize[10]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 50;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoEstrato';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['ajax_function'] = "";
	        $atributos ['ajax_control'] = $esteCampo;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] =false;
	        $atributos ['validar'] = ' ';
	        /*
	        $atributos['cadena_sql'] = $this->miSql->getCadenaSql("buscarRegistro");
	        $matrizItems = $primerRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");*/
	        
	        $matrizItems=array(
	        		array(1,'Uno'),
	        		array(2,'Dos'),
	        		array(3,'Tres'),
	        		array(4,'Cuatro'),
	        		array(5,'Cinco'),
	        		array(6,'Seis')
	        
	        );
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        unset($atributos);
	        $esteCampo = 'funcionarioSoporteEstrato';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'hidden';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        //$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = '';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        unset($atributos);
	        $esteCampo = 'funcionarioSoporteResidencia';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'hidden';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        //$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = '';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoTelFijo';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, minSize[7], custom[phone]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 7;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoTelMovil';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required,  minSize[10], custom[phone]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 10;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoEmail';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required,  minSize[8], custom[email]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'novedadesDatosOrganizacion';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoOrganiTelOficina';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos ['anchoEtiqueta'] = 280;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[phone]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 10;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoOrganiEmail';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos ['anchoEtiqueta'] = 280;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[email]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoOrganiDireccion';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos ['anchoEtiqueta'] = 280;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = ' ';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 50;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioContactoOrganiCargo';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos ['anchoEtiqueta'] = 280;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 20;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        }
	        echo $this->miFormulario->agrupacion ( "fin" );
	    
	    }
	    echo $this->miFormulario->marcoAgrupacion ( 'fin' );
//*************************************************************************************************************
//*************************************************************************************************************	    
	    $esteCampo = "novedadesDatosFormacionAcademica";
	    $atributos ['id'] = $esteCampo;
	    $atributos ["estilo"] = "jqueryui";
	    $atributos ['tipoEtiqueta'] = 'inicio';
	    $atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
	    echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
	    unset ( $atributos );
        {
        	
        	$atributos ["id"] = "botonDatos";
        	$atributos ["estilo"] = "botonDatos";
        	echo $this->miFormulario->division ( "inicio", $atributos );
        	{
        		echo "<button id=\"mostrarb4\" name=\"mas1\" ALIGN=RIGHT class=\"\">Atrás</button>";
        		echo "<button id=\"ocultarb4\" ALIGN=RIGHT name=\"menos1\" class=\"\">Siguiente</button>";
        	}
        	echo $this->miFormulario->division ( "fin" );
        	 
        	$atributos ["id"] = "contentDatos4";
        	$atributos ["estilo"] = "marcoBotones";
        	echo $this->miFormulario->agrupacion ( "inicio", $atributos );
        	{
        		
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'novedadesDatosFormacionBasica';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionBasicaModalidad';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 20;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionBasicaPais';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	         
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	         
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	         
	        $atributos['matrizItems'] = $matrizItems;
	         
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	         
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select ----------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionBasicaDepartamento';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionBasicaCiudad';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionBasicaColegio';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 90;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionBasicaTitul';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 50;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioFechaFormacionBasica';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, custom[date]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 10;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        unset($atributos);
	        $esteCampo = 'funcionarioSoporteFormacionBasica';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'hidden';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        //$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = '';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'novedadesDatosFormacionMedia';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionMediaModalidad';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 20;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionMediaPais';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = false;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	         
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	         
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	         
	        $atributos['matrizItems'] = $matrizItems;
	         
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	         
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select ----------------------------------------------------
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionMediaDepartamento';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        
	        // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionMediaCiudad';
	        $atributos['nombre'] = $esteCampo;
	        $atributos['id'] = $esteCampo;
	        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos['tab'] = $tab;
	        $atributos['seleccion'] = -1;
	        $atributos['evento'] = ' ';
	        $atributos['deshabilitado'] = true;
	        $atributos['limitar']= 50;
	        $atributos['tamanno']= 1;
	        $atributos['columnas']= 1;
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	        
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
	        $matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        
	        $atributos['matrizItems'] = $matrizItems;
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroLista ( $atributos );
	        // --------------- FIN CONTROL : Select --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionMediaColegio';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 90;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioFormacionMediaTitul';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 50;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioFechaFormacionMedia';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required, custom[date]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 10;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        unset($atributos);
	        $esteCampo = 'funcionarioSoporteFormacionMedia';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'hidden';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        //$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = '';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 30;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	         
	        $esteCampo = 'novedadesDatosFormacionSuperior';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	         
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	         
	        // --------------------------------------------------------------------------------------------------
	        
//***************************************************************************************************************
//***************************************************************************************************************

	        
	        for($i = 0; $i < $cantidad_referencias; $i++){
	        	
	        	 
	        	$esteCampo = "novedadesDatosCantidadEduacionSuperior_";
	        	$baseCampo = "novedadesDatosCantidadEduacionSuperior";
	        	$atributos ['id'] = $esteCampo.$i;
	        	$atributos ["estilo"] = "jqueryui";
	        	$atributos ['tipoEtiqueta'] = 'inicio';
	        	$numero_estudio = $i+1;
	        	$atributos ["leyenda"] = $this->lenguaje->getCadena ( $baseCampo ).$numero_estudio;
	        	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
	        	{
	        	
	        	
	        	// ---------------- CONTROL: Select --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorModalidad_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorModalidad';
	        	$atributos['nombre'] = $esteCampo;
	        	$atributos['id'] = $esteCampo;
	        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	$atributos['tab'] = $tab;
	        	$atributos['seleccion'] = -1;
	        	$atributos['evento'] = ' ';
	        	$atributos['deshabilitado'] = false;
	        	$atributos['limitar']= 50;
	        	$atributos['tamanno']= 1;
	        	$atributos['columnas']= 1;
	        	 
	        	$atributos ['obligatorio'] = true;
	        	$atributos ['etiquetaObligatorio'] = true;
	        	$atributos ['validar'] = '';
	        	 
	        	$matrizItems=array(
	        			array(1,'Técnica'),
	        			array(2,'Tecnológica'),
	        			array(3,'Tecnológica Especializada'),
	        			array(4,'Universitaria'),
	        			array(5,'Especialización'),
	        			array(6,'Maestría'),
	        			array(7,'Doctorado')
	        			 
	        	);
	        	$atributos['matrizItems'] = $matrizItems;
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	$caracteSelect = $atributos;
	        	echo $this->miFormulario->campoCuadroLista ( $atributos );
	        	// --------------- FIN CONTROL : Select --------------------------------------------------
	        	
	        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorSemestres_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorSemestres';
	        	$atributos ['id'] = $esteCampo;
	        	$atributos ['nombre'] = $esteCampo;
	        	$atributos ['tipo'] = 'text';
	        	$atributos ['estilo'] = 'jqueryui';
	        	$atributos ['marco'] = true;
	        	$atributos ['columnas'] = 1;
	        	$atributos ['dobleLinea'] = false;
	        	$atributos ['tabIndex'] = $tab;
	        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	 
	        	$atributos ['obligatorio'] = true;
	        	$atributos ['etiquetaObligatorio'] = true;
	        	$atributos ['validar'] = 'custom[onlyNumberSp]';
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        	$atributos ['deshabilitado'] = false;
	        	$atributos ['tamanno'] = 20;
	        	$atributos ['maximoTamanno'] = '';
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        		    
	        	// ---------------- CONTROL: Select --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorGraduado_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorGraduado';
	        	$atributos['nombre'] = $esteCampo;
	        	$atributos['id'] = $esteCampo;
	        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	$atributos['tab'] = $tab;
	        	$atributos['seleccion'] = -1;
	        	$atributos['evento'] = ' ';
	        	$atributos['deshabilitado'] = false;
	        	$atributos['limitar']= 50;
	        	$atributos['tamanno']= 1;
	        	$atributos['columnas']= 1;
	        	 
	        	$atributos ['obligatorio'] = true;
	        	$atributos ['etiquetaObligatorio'] = true;
	        	$atributos ['validar'] = '';
	        	 
	        	$matrizItems=array(
	        			array(1,'Si'),
	        			array(2,'No')
	        			 
	        	);
	        	$atributos['matrizItems'] = $matrizItems;
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroLista ( $atributos );
	        	// --------------- FIN CONTROL : Select --------------------------------------------------
	        	
	        	// ---------------- CONTROL: Select --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorPais_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorPais';
	        	$atributos['nombre'] = $esteCampo;
	        	$atributos['id'] = $esteCampo;
	        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	$atributos['tab'] = $tab;
	        	$atributos['seleccion'] = -1;
	        	$atributos['evento'] = ' ';
	        	$atributos['deshabilitado'] = false;
	        	$atributos['limitar']= 50;
	        	$atributos['tamanno']= 1;
	        	$atributos['columnas']= 1;
	        	 
	        	$atributos ['obligatorio'] = true;
	        	$atributos ['etiquetaObligatorio'] = true;
	        	$atributos ['validar'] = '';
	        	 
	        	$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
	        	$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        	
	        	$atributos['matrizItems'] = $matrizItems;
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroLista ( $atributos );
	        	// --------------- FIN CONTROL : Select --------------------------------------------------
	        	 
	        	// ---------------- CONTROL: Select --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorDepartamento_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorDepartamento';
	        	$atributos['nombre'] = $esteCampo;
	        	$atributos['id'] = $esteCampo;
	        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	$atributos['tab'] = $tab;
	        	$atributos['seleccion'] = -1;
	        	$atributos['evento'] = ' ';
	        	$atributos['deshabilitado'] = true;
	        	$atributos['limitar']= 50;
	        	$atributos['tamanno']= 1;
	        	$atributos['columnas']= 1;
	        	 
	        	$atributos ['obligatorio'] = true;
	        	$atributos ['etiquetaObligatorio'] = true;
	        	$atributos ['validar'] = '';
	        	 
	        	$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
	        	$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        	
	        	$atributos['matrizItems'] = $matrizItems;
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroLista ( $atributos );
	        	// --------------- FIN CONTROL : Select --------------------------------------------------
	        	 
	        	// ---------------- CONTROL: Select --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorCiudad_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorCiudad';
	        	$atributos['nombre'] = $esteCampo;
	        	$atributos['id'] = $esteCampo;
	        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	$atributos['tab'] = $tab;
	        	$atributos['seleccion'] = -1;
	        	$atributos['evento'] = ' ';
	        	$atributos['deshabilitado'] = true;
	        	$atributos['limitar']= 50;
	        	$atributos['tamanno']= 1;
	        	$atributos['columnas']= 1;
	        	 
	        	$atributos ['obligatorio'] = true;
	        	$atributos ['etiquetaObligatorio'] = true;
	        	$atributos ['validar'] = '';
	        	 
	        	$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
	        	$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	        	
	        	$atributos['matrizItems'] = $matrizItems;
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroLista ( $atributos );
	        	// --------------- FIN CONTROL : Select --------------------------------------------------
	        		    
	        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorResolucionConvali_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorResolucionConvali';
	        	$atributos ['id'] = $esteCampo;
	        	$atributos ['nombre'] = $esteCampo;
	        	$atributos ['tipo'] = 'text';
	        	$atributos ['estilo'] = 'jqueryui';
	        	$atributos ['marco'] = true;
	        	$atributos ['columnas'] = 1;
	        	$atributos ['dobleLinea'] = false;
	        	$atributos ['tabIndex'] = $tab;
	        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	 
	        	$atributos ['obligatorio'] = false;
	        	$atributos ['etiquetaObligatorio'] = false;
	        	$atributos ['validar'] = '';
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        	$atributos ['deshabilitado'] = true;
	        	$atributos ['tamanno'] = 20;
	        	$atributos ['maximoTamanno'] = '';
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        	
	        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        	$esteCampo = 'funcionarioFechaConvalidaSuperior_'.$i;
	        	$baseCampo = 'funcionarioFechaConvalidaSuperior';
	        	$atributos ['id'] = $esteCampo;
	        	$atributos ['nombre'] = $esteCampo;
	        	$atributos ['tipo'] = 'text';
	        	$atributos ['estilo'] = 'jqueryui';
	        	$atributos ['marco'] = true;
	        	$atributos ['columnas'] = 1;
	        	$atributos ['dobleLinea'] = false;
	        	$atributos ['tabIndex'] = $tab;
	        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	 
	        	$atributos ['obligatorio'] = false;
	        	$atributos ['etiquetaObligatorio'] = false;
	        	$atributos ['validar'] = 'custom[date]';
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        	$atributos ['deshabilitado'] = true;
	        	$atributos ['tamanno'] = 10;
	        	$atributos ['maximoTamanno'] = '';
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
	        	
	        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorEntidadConvali_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorEntidadConvali';
	        	$atributos ['id'] = $esteCampo;
	        	$atributos ['nombre'] = $esteCampo;
	        	$atributos ['tipo'] = 'text';
	        	$atributos ['estilo'] = 'jqueryui';
	        	$atributos ['marco'] = true;
	        	$atributos ['columnas'] = 1;
	        	$atributos ['dobleLinea'] = false;
	        	$atributos ['tabIndex'] = $tab;
	        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	 
	        	$atributos ['obligatorio'] = false;
	        	$atributos ['etiquetaObligatorio'] = false;
	        	$atributos ['validar'] = '';
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        	$atributos ['deshabilitado'] = true;
	        	$atributos ['tamanno'] = 20;
	        	$atributos ['maximoTamanno'] = '';
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        	
	        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorUniversidad_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorUniversidad';
	        	$atributos ['id'] = $esteCampo;
	        	$atributos ['nombre'] = $esteCampo;
	        	$atributos ['tipo'] = 'text';
	        	$atributos ['estilo'] = 'jqueryui';
	        	$atributos ['marco'] = true;
	        	$atributos ['columnas'] = 1;
	        	$atributos ['dobleLinea'] = false;
	        	$atributos ['tabIndex'] = $tab;
	        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	 
	        	$atributos ['obligatorio'] = true;
	        	$atributos ['etiquetaObligatorio'] = true;
	        	$atributos ['validar'] = 'custom[onlyLetterSp]';
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        	$atributos ['deshabilitado'] = false;
	        	$atributos ['tamanno'] = 80;
	        	$atributos ['maximoTamanno'] = '';
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        	
	        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorTituloObtenido_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorTituloObtenido';
	        	$atributos ['id'] = $esteCampo;
	        	$atributos ['nombre'] = $esteCampo;
	        	$atributos ['tipo'] = 'text';
	        	$atributos ['estilo'] = 'jqueryui';
	        	$atributos ['marco'] = true;
	        	$atributos ['columnas'] = 1;
	        	$atributos ['dobleLinea'] = false;
	        	$atributos ['tabIndex'] = $tab;
	        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	 
	        	$atributos ['obligatorio'] = true;
	        	$atributos ['etiquetaObligatorio'] = true;
	        	$atributos ['validar'] = 'custom[onlyLetterSp]';
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        	$atributos ['deshabilitado'] = false;
	        	$atributos ['tamanno'] = 30;
	        	$atributos ['maximoTamanno'] = '';
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        	
	        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        	$esteCampo = 'funcionarioFechaTituloSuperior_'.$i;
	        	$baseCampo = 'funcionarioFechaTituloSuperior';
	        	$atributos ['id'] = $esteCampo;
	        	$atributos ['nombre'] = $esteCampo;
	        	$atributos ['tipo'] = 'text';
	        	$atributos ['estilo'] = 'jqueryui';
	        	$atributos ['marco'] = true;
	        	$atributos ['columnas'] = 1;
	        	$atributos ['dobleLinea'] = false;
	        	$atributos ['tabIndex'] = $tab;
	        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	 
	        	$atributos ['obligatorio'] = false;
	        	$atributos ['etiquetaObligatorio'] = false;
	        	$atributos ['validar'] = 'custom[date]';
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        	$atributos ['deshabilitado'] = false;
	        	$atributos ['tamanno'] = 10;
	        	$atributos ['maximoTamanno'] = '';
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        	
	        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        	$esteCampo = 'funcionarioFormacionSuperiorNumeroTarjeta_'.$i;
	        	$baseCampo = 'funcionarioFormacionSuperiorNumeroTarjeta';
	        	$atributos ['id'] = $esteCampo;
	        	$atributos ['nombre'] = $esteCampo;
	        	$atributos ['tipo'] = 'text';
	        	$atributos ['estilo'] = 'jqueryui';
	        	$atributos ['marco'] = true;
	        	$atributos ['columnas'] = 1;
	        	$atributos ['dobleLinea'] = false;
	        	$atributos ['tabIndex'] = $tab;
	        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	 
	        	$atributos ['obligatorio'] = true;
	        	$atributos ['etiquetaObligatorio'] = true;
	        	$atributos ['validar'] = '';
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        	$atributos ['deshabilitado'] = false;
	        	$atributos ['tamanno'] = 30;
	        	$atributos ['maximoTamanno'] = '';
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        	
	        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        	$esteCampo = 'funcionarioFechaTarjetaSuperior_'.$i;
	        	$baseCampo = 'funcionarioFechaTarjetaSuperior';
	        	$atributos ['id'] = $esteCampo;
	        	$atributos ['nombre'] = $esteCampo;
	        	$atributos ['tipo'] = 'text';
	        	$atributos ['estilo'] = 'jqueryui';
	        	$atributos ['marco'] = true;
	        	$atributos ['columnas'] = 1;
	        	$atributos ['dobleLinea'] = false;
	        	$atributos ['tabIndex'] = $tab;
	        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	$atributos['anchoEtiqueta'] = 300;
	        	 
	        	$atributos ['obligatorio'] = false;
	        	$atributos ['etiquetaObligatorio'] = false;
	        	$atributos ['validar'] = 'custom[date]';
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        	$atributos ['deshabilitado'] = false;
	        	$atributos ['tamanno'] = 10;
	        	$atributos ['maximoTamanno'] = '';
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        	
	        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        	unset($atributos);
	        	$esteCampo = 'funcionarioSoporteFormacionSuperior_'.$i;
	        	$baseCampo = 'funcionarioSoporteFormacionSuperior';
	        	$atributos ['id'] = $esteCampo;
	        	$atributos ['nombre'] = $esteCampo;
	        	$atributos ['tipo'] = 'hidden';
	        	$atributos ['estilo'] = 'jqueryui';
	        	$atributos ['marco'] = true;
	        	$atributos ['columnas'] = 1;
	        	$atributos ['dobleLinea'] = false;
	        	$atributos ['tabIndex'] = $tab;
	        	//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        	 
	        	$atributos ['obligatorio'] = false;
	        	$atributos ['etiquetaObligatorio'] = false;
	        	$atributos ['validar'] = '';
	        	 
	        	if (isset ( $_REQUEST [$esteCampo] )) {
	        		$atributos ['valor'] = $_REQUEST [$esteCampo];
	        	} else {
	        		$atributos ['valor'] = '';
	        	}
	        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        	$atributos ['deshabilitado'] = false;
	        	$atributos ['tamanno'] = 30;
	        	$atributos ['maximoTamanno'] = '';
	        	$tab ++;
	        	 
	        	// Aplica atributos globales al control
	        	$atributos = array_merge ( $atributos, $atributosGlobales );
	        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        	
	        	}
	        	echo $this->miFormulario->marcoAgrupacion ( 'fin' );
	        	
	   
	        	
	        	
	        }
	        unset($atributos);
	        $atributos ["id"] = "mainSuperior";
	        $atributos ["estilo"] = "botonDinamico";
	        echo $this->miFormulario->agrupacion ( "inicio", $atributos );
	        {
	        	echo "<input type=\"button\" id=\"btAdd\" value=\"Agregar\" class=\"btn btn-success\"/> &nbsp;";
	        	echo "<input type=\"button\" id=\"btRemove\" value=\"Eliminar\" class=\"btn btn-danger\" /> &nbsp;";	        
	        }
	        echo $this->miFormulario->agrupacion ( "fin" );
	        
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'novedadesDatosFormacionInformal';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	         
//**************************************************************************************************************
//**************************************************************************************************************

	         
	        for($i = 0; $i < $cantidad_referencias_info; $i++){
	        
	        	$esteCampo = "novedadesDatosCantidadEduacionInformal_";
	        	$baseCampo = "novedadesDatosCantidadEduacionInformal";
	        	$atributos ['id'] = $esteCampo.$i;
	        	$atributos ["estilo"] = "jqueryui";
	        	$atributos ['tipoEtiqueta'] = 'inicio';
	        	$numero_estudio = $i+1;
	        	$atributos ["leyenda"] = $this->lenguaje->getCadena ( $baseCampo ).$numero_estudio;
	        	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
	        	{
	        		
	        		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        		$esteCampo = 'funcionarioFormacionInformalCurso_'.$i;
	        		$baseCampo = 'funcionarioFormacionInformalCurso';
	        		$atributos ['id'] = $esteCampo;
	        		$atributos ['nombre'] = $esteCampo;
	        		$atributos ['tipo'] = 'text';
	        		$atributos ['estilo'] = 'jqueryui';
	        		$atributos ['marco'] = true;
	        		$atributos ['columnas'] = 1;
	        		$atributos ['dobleLinea'] = false;
	        		$atributos ['tabIndex'] = $tab;
	        		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        		 
	        		$atributos ['obligatorio'] = false;
	        		$atributos ['etiquetaObligatorio'] = false;
	        		$atributos ['validar'] = 'custom[onlyLetterSp]';
	        		 
	        		if (isset ( $_REQUEST [$esteCampo] )) {
	        			$atributos ['valor'] = $_REQUEST [$esteCampo];
	        		} else {
	        			$atributos ['valor'] = '';
	        		}
	        		$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        		$atributos ['deshabilitado'] = false;
	        		$atributos ['tamanno'] = 100;
	        		$atributos ['maximoTamanno'] = '';
	        		$tab ++;
	        		 
	        		// Aplica atributos globales al control
	        		$atributos = array_merge ( $atributos, $atributosGlobales );
	        		echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        		// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        		
	        		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        		$esteCampo = 'funcionarioFormacionInformalCursoLugar_'.$i;
	        		$baseCampo = 'funcionarioFormacionInformalCursoLugar';
	        		$atributos ['id'] = $esteCampo;
	        		$atributos ['nombre'] = $esteCampo;
	        		$atributos ['tipo'] = 'text';
	        		$atributos ['estilo'] = 'jqueryui';
	        		$atributos ['marco'] = true;
	        		$atributos ['columnas'] = 1;
	        		$atributos ['dobleLinea'] = false;
	        		$atributos ['tabIndex'] = $tab;
	        		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        		
	        		$atributos ['obligatorio'] = false;
	        		$atributos ['etiquetaObligatorio'] = false;
	        		$atributos ['validar'] = 'custom[onlyLetterSp]';
	        		
	        		if (isset ( $_REQUEST [$esteCampo] )) {
	        			$atributos ['valor'] = $_REQUEST [$esteCampo];
	        		} else {
	        			$atributos ['valor'] = '';
	        		}
	        		$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        		$atributos ['deshabilitado'] = false;
	        		$atributos ['tamanno'] = 30;
	        		$atributos ['maximoTamanno'] = '';
	        		$tab ++;
	        		
	        		// Aplica atributos globales al control
	        		$atributos = array_merge ( $atributos, $atributosGlobales );
	        		echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        		// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        		
	        		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        		$esteCampo = 'funcionarioFormacionInformalCursoIntensidad_'.$i;
	        		$baseCampo = 'funcionarioFormacionInformalCursoIntensidad';
	        		$atributos ['id'] = $esteCampo;
	        		$atributos ['nombre'] = $esteCampo;
	        		$atributos ['tipo'] = 'text';
	        		$atributos ['estilo'] = 'jqueryui';
	        		$atributos ['marco'] = true;
	        		$atributos ['columnas'] = 1;
	        		$atributos ['dobleLinea'] = false;
	        		$atributos ['tabIndex'] = $tab;
	        		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        		 
	        		$atributos ['obligatorio'] = false;
	        		$atributos ['etiquetaObligatorio'] = false;
	        		$atributos ['validar'] = 'custom[onlyNumberSp]';
	        		 
	        		if (isset ( $_REQUEST [$esteCampo] )) {
	        			$atributos ['valor'] = $_REQUEST [$esteCampo];
	        		} else {
	        			$atributos ['valor'] = '';
	        		}
	        		$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        		$atributos ['deshabilitado'] = false;
	        		$atributos ['tamanno'] = 10;
	        		$atributos ['maximoTamanno'] = '';
	        		$tab ++;
	        		 
	        		// Aplica atributos globales al control
	        		$atributos = array_merge ( $atributos, $atributosGlobales );
	        		echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        		// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        		
	        		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        		$esteCampo = 'funcionarioFechaInformal_'.$i;
	        		$baseCampo = 'funcionarioFechaInformal';
	        		$atributos ['id'] = $esteCampo;
	        		$atributos ['nombre'] = $esteCampo;
	        		$atributos ['tipo'] = 'text';
	        		$atributos ['estilo'] = 'jqueryui';
	        		$atributos ['marco'] = true;
	        		$atributos ['columnas'] = 1;
	        		$atributos ['dobleLinea'] = false;
	        		$atributos ['tabIndex'] = $tab;
	        		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        		 
	        		$atributos ['obligatorio'] = false;
	        		$atributos ['etiquetaObligatorio'] = false;
	        		$atributos ['validar'] = 'custom[date]';
	        		 
	        		if (isset ( $_REQUEST [$esteCampo] )) {
	        			$atributos ['valor'] = $_REQUEST [$esteCampo];
	        		} else {
	        			$atributos ['valor'] = '';
	        		}
	        		$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        		$atributos ['deshabilitado'] = false;
	        		$atributos ['tamanno'] = 10;
	        		$atributos ['maximoTamanno'] = '';
	        		$tab ++;
	        		 
	        		// Aplica atributos globales al control
	        		$atributos = array_merge ( $atributos, $atributosGlobales );
	        		echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        		// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        		
	        		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        		unset($atributos);
	        		$esteCampo = 'funcionarioSoporteFormacionInformal_'.$i;
	        		$baseCampo = 'funcionarioSoporteFormacionInformal';
	        		$atributos ['id'] = $esteCampo;
	        		$atributos ['nombre'] = $esteCampo;
	        		$atributos ['tipo'] = 'hidden';
	        		$atributos ['estilo'] = 'jqueryui';
	        		$atributos ['marco'] = true;
	        		$atributos ['columnas'] = 1;
	        		$atributos ['dobleLinea'] = false;
	        		$atributos ['tabIndex'] = $tab;
	        		//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	        		 
	        		$atributos ['obligatorio'] = false;
	        		$atributos ['etiquetaObligatorio'] = false;
	        		$atributos ['validar'] = '';
	        		 
	        		if (isset ( $_REQUEST [$esteCampo] )) {
	        			$atributos ['valor'] = $_REQUEST [$esteCampo];
	        		} else {
	        			$atributos ['valor'] = '';
	        		}
	        		$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	        		$atributos ['deshabilitado'] = false;
	        		$atributos ['tamanno'] = 30;
	        		$atributos ['maximoTamanno'] = '';
	        		$tab ++;
	        		 
	        		// Aplica atributos globales al control
	        		$atributos = array_merge ( $atributos, $atributosGlobales );
	        		echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        		// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        		
	        		
	        		
	        	}
	        	echo $this->miFormulario->marcoAgrupacion ( 'fin' );
	        	
	        }
	        unset($atributos);
	        $atributos ["id"] = "mainInformal";
	        $atributos ["estilo"] = "botonDinamico";
	        echo $this->miFormulario->agrupacion ( "inicio", $atributos );
	        {
	        	echo "<input type=\"button\" id=\"btAddIn\" value=\"Agregar\" class=\"btn btn-success\"/> &nbsp;";
	        	echo "<input type=\"button\" id=\"btRemoveIn\" value=\"Eliminar\" class=\"btn btn-danger\" /> &nbsp;";
	        }
	        echo $this->miFormulario->agrupacion ( "fin" );
	        
//**********************************************************************************************************************
	        
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	         
	        $esteCampo = 'novedadesDatosFormacionIdiomas';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	         
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	         
	        // --------------------------------------------------------------------------------------------------
	        
	       
	        
	        for($i = 0; $i < $cantidad_idiomas; $i++){
	        	 
	        	 
		        	$esteCampo = "novedadesDatosCantidadEduacionIdiomas_";
		        	$baseCampo = "novedadesDatosCantidadEduacionIdiomas";
		        	$atributos ['id'] = $esteCampo.$i;
		        	$atributos ["estilo"] = "jqueryui";
		        	$atributos ['tipoEtiqueta'] = 'inicio';
		        	$numero_estudio = $i+1;
		        	$atributos ["leyenda"] = $this->lenguaje->getCadena ( $baseCampo ).$numero_estudio;
		        	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		        	{
		        		 
		        		// ---------------- CONTROL: Select --------------------------------------------------------
			        	$esteCampo = 'funcionarioFormacionIdioma_'.$i;
			        	$baseCampo = 'funcionarioFormacionIdioma';
			        	$atributos['nombre'] = $esteCampo;
			        	$atributos['id'] = $esteCampo;
			        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			        	$atributos['tab'] = $tab;
			        	$atributos['seleccion'] = -1;
			        	$atributos['evento'] = ' ';
			        	$atributos['deshabilitado'] = false;
			        	$atributos['limitar']= 50;
			        	$atributos['tamanno']= 1;
			        	$atributos['columnas']= 1;
			        	 
			        	$atributos ['obligatorio'] = true;
			        	$atributos ['etiquetaObligatorio'] = true;
			        	$atributos ['validar'] = '';
			        	 
			        	$matrizItems=array(
			        			array(1,'Inglés'),
			        			array(2,'Francés'),
			        			array(3,'Alemán'),
			        			array(4,'Portugués'),
			        			array(5,'Italiano'),
			        			array(6,'Mandarín'),
			        			array(7,'Otro')
			        			 
			        	);
			        	$atributos['matrizItems'] = $matrizItems;
			        	 
			        	if (isset ( $_REQUEST [$esteCampo] )) {
			        		$atributos ['valor'] = $_REQUEST [$esteCampo];
			        	} else {
			        		$atributos ['valor'] = '';
			        	}
			        	$tab ++;
			        	 
			        	// Aplica atributos globales al control
			        	$atributos = array_merge ( $atributos, $atributosGlobales );
			        	echo $this->miFormulario->campoCuadroLista ( $atributos );
			        	// --------------- FIN CONTROL : Select --------------------------------------------------
			        	
			        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			        	$esteCampo = 'funcionarioFormacionIdiomaUniversidad_'.$i;
			        	$baseCampo = 'funcionarioFormacionIdiomaUniversidad';
			        	$atributos ['id'] = $esteCampo;
			        	$atributos ['nombre'] = $esteCampo;
			        	$atributos ['tipo'] = 'text';
			        	$atributos ['estilo'] = 'jqueryui';
			        	$atributos ['marco'] = true;
			        	$atributos ['columnas'] = 1;
			        	$atributos ['dobleLinea'] = false;
			        	$atributos ['tabIndex'] = $tab;
			        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			        	 
			        	$atributos ['obligatorio'] = true;
			        	$atributos ['etiquetaObligatorio'] = true;
			        	$atributos ['validar'] = 'custom[onlyLetterSp]';
			        	 
			        	if (isset ( $_REQUEST [$esteCampo] )) {
			        		$atributos ['valor'] = $_REQUEST [$esteCampo];
			        	} else {
			        		$atributos ['valor'] = '';
			        	}
			        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
			        	$atributos ['deshabilitado'] = false;
			        	$atributos ['tamanno'] = 50;
			        	$atributos ['maximoTamanno'] = '';
			        	$tab ++;
			        	 
			        	// Aplica atributos globales al control
			        	$atributos = array_merge ( $atributos, $atributosGlobales );
			        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
			        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			        		     
			        	// ---------------- CONTROL: Select --------------------------------------------------------
			        	$esteCampo = 'funcionarioFormacionIdiomaNivel_'.$i;
			        	$baseCampo = 'funcionarioFormacionIdiomaNivel';
			        	$atributos['nombre'] = $esteCampo;
			        	$atributos['id'] = $esteCampo;
			        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			        	$atributos['tab'] = $tab;
			        	$atributos['seleccion'] = -1;
			        	$atributos['evento'] = ' ';
			        	$atributos['deshabilitado'] = false;
			        	$atributos['limitar']= 50;
			        	$atributos['tamanno']= 1;
			        	$atributos['columnas']= 1;
			        	 
			        	$atributos ['obligatorio'] = true;
			        	$atributos ['etiquetaObligatorio'] = true;
			        	$atributos ['validar'] = '';
			        	 
			        	$matrizItems=array(
			        			array(1,'(A1) Básico'),
			        			array(2,'(A2) Elemental'),
			        			array(3,'(B1) Pre-Intermedio'),
			        			array(4,'(B2) Intermedio Alto'),
			        			array(5,'(C1) Avanzado'),
			        			array(6,'(C2) Superior')
			        			 
			        	);
			        	$atributos['matrizItems'] = $matrizItems;
			        	 
			        	if (isset ( $_REQUEST [$esteCampo] )) {
			        		$atributos ['valor'] = $_REQUEST [$esteCampo];
			        	} else {
			        		$atributos ['valor'] = '';
			        	}
			        	$tab ++;
			        	 
			        	// Aplica atributos globales al control
			        	$atributos = array_merge ( $atributos, $atributosGlobales );
			        	echo $this->miFormulario->campoCuadroLista ( $atributos );
			        	// --------------- FIN CONTROL : Select --------------------------------------------------
			        	
			        	// ---------------- CONTROL: Select --------------------------------------------------------
			        	$esteCampo = 'funcionarioFormacionIdiomaNivelHabla_'.$i;
			        	$baseCampo = 'funcionarioFormacionIdiomaNivelHabla';
			        	$atributos['nombre'] = $esteCampo;
			        	$atributos['id'] = $esteCampo;
			        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			        	$atributos['tab'] = $tab;
			        	$atributos['seleccion'] = -1;
			        	$atributos['evento'] = ' ';
			        	$atributos['deshabilitado'] = false;
			        	$atributos['limitar']= 50;
			        	$atributos['tamanno']= 1;
			        	$atributos['columnas']= 1;
			        	 
			        	$atributos ['obligatorio'] = false;
			        	$atributos ['etiquetaObligatorio'] = false;
			        	$atributos ['validar'] = '';
			        	 
			        	$matrizItems=array(
			        			array(1,'Aceptable'),
			        			array(2,'Bueno'),
			        			array(3,'Excelente')
			        			 
			        	);
			        	$atributos['matrizItems'] = $matrizItems;
			        	 
			        	if (isset ( $_REQUEST [$esteCampo] )) {
			        		$atributos ['valor'] = $_REQUEST [$esteCampo];
			        	} else {
			        		$atributos ['valor'] = '';
			        	}
			        	$tab ++;
			        	 
			        	// Aplica atributos globales al control
			        	$atributos = array_merge ( $atributos, $atributosGlobales );
			        	echo $this->miFormulario->campoCuadroLista ( $atributos );
			        	// --------------- FIN CONTROL : Select --------------------------------------------------
			        	
			        	// ---------------- CONTROL: Select --------------------------------------------------------
			        	$esteCampo = 'funcionarioFormacionIdiomaNivelLee_'.$i;
			        	$baseCampo = 'funcionarioFormacionIdiomaNivelLee';
			        	$atributos['nombre'] = $esteCampo;
			        	$atributos['id'] = $esteCampo;
			        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			        	$atributos['tab'] = $tab;
			        	$atributos['seleccion'] = -1;
			        	$atributos['evento'] = ' ';
			        	$atributos['deshabilitado'] = false;
			        	$atributos['limitar']= 50;
			        	$atributos['tamanno']= 1;
			        	$atributos['columnas']= 1;
			        	 
			        	$atributos ['obligatorio'] = false;
			        	$atributos ['etiquetaObligatorio'] = false;
			        	$atributos ['validar'] = '';
			        	 
			        	$matrizItems=array(
			        			array(1,'Aceptable'),
			        			array(2,'Bueno'),
			        			array(3,'Excelente')
			        			 
			        	);
			        	$atributos['matrizItems'] = $matrizItems;
			        	 
			        	if (isset ( $_REQUEST [$esteCampo] )) {
			        		$atributos ['valor'] = $_REQUEST [$esteCampo];
			        	} else {
			        		$atributos ['valor'] = '';
			        	}
			        	$tab ++;
			        	 
			        	// Aplica atributos globales al control
			        	$atributos = array_merge ( $atributos, $atributosGlobales );
			        	echo $this->miFormulario->campoCuadroLista ( $atributos );
			        	// --------------- FIN CONTROL : Select --------------------------------------------------
			        	
			        	// ---------------- CONTROL: Select --------------------------------------------------------
			        	$esteCampo = 'funcionarioFormacionIdiomaNivelEscribe_'.$i;
			        	$baseCampo = 'funcionarioFormacionIdiomaNivelEscribe';
			        	$atributos['nombre'] = $esteCampo;
			        	$atributos['id'] = $esteCampo;
			        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			        	$atributos['tab'] = $tab;
			        	$atributos['seleccion'] = -1;
			        	$atributos['evento'] = ' ';
			        	$atributos['deshabilitado'] = false;
			        	$atributos['limitar']= 50;
			        	$atributos['tamanno']= 1;
			        	$atributos['columnas']= 1;
			        	 
			        	$atributos ['obligatorio'] = false;
			        	$atributos ['etiquetaObligatorio'] = false;
			        	$atributos ['validar'] = '';
			        	 
			        	$matrizItems=array(
			        			array(1,'Aceptable'),
			        			array(2,'Bueno'),
			        			array(3,'Excelente')
			        			 
			        	);
			        	$atributos['matrizItems'] = $matrizItems;
			        	 
			        	if (isset ( $_REQUEST [$esteCampo] )) {
			        		$atributos ['valor'] = $_REQUEST [$esteCampo];
			        	} else {
			        		$atributos ['valor'] = '';
			        	}
			        	$tab ++;
			        	 
			        	// Aplica atributos globales al control
			        	$atributos = array_merge ( $atributos, $atributosGlobales );
			        	echo $this->miFormulario->campoCuadroLista ( $atributos );
			        	// --------------- FIN CONTROL : Select --------------------------------------------------
			        	
			        	// ---------------- CONTROL: Select --------------------------------------------------------
			        	$esteCampo = 'funcionarioFormacionIdiomaNivelEscucha_'.$i;
			        	$baseCampo = 'funcionarioFormacionIdiomaNivelEscucha';
			        	$atributos['nombre'] = $esteCampo;
			        	$atributos['id'] = $esteCampo;
			        	$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			        	$atributos['tab'] = $tab;
			        	$atributos['seleccion'] = -1;
			        	$atributos['evento'] = ' ';
			        	$atributos['deshabilitado'] = false;
			        	$atributos['limitar']= 50;
			        	$atributos['tamanno']= 1;
			        	$atributos['columnas']= 1;
			        	 
			        	$atributos ['obligatorio'] = false;
			        	$atributos ['etiquetaObligatorio'] = false;
			        	$atributos ['validar'] = '';
			        	 
			        	$matrizItems=array(
			        			array(1,'Aceptable'),
			        			array(2,'Bueno'),
			        			array(3,'Excelente')
			        			 
			        	);
			        	$atributos['matrizItems'] = $matrizItems;
			        	 
			        	if (isset ( $_REQUEST [$esteCampo] )) {
			        		$atributos ['valor'] = $_REQUEST [$esteCampo];
			        	} else {
			        		$atributos ['valor'] = '';
			        	}
			        	$tab ++;
			        	 
			        	// Aplica atributos globales al control
			        	$atributos = array_merge ( $atributos, $atributosGlobales );
			        	echo $this->miFormulario->campoCuadroLista ( $atributos );
			        	// --------------- FIN CONTROL : Select --------------------------------------------------
			        	
			        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			        	unset($atributos);
			        	$esteCampo = 'funcionarioSoporteIdioma_'.$i;
			        	$baseCampo = 'funcionarioSoporteIdioma';
			        	$atributos ['id'] = $esteCampo;
			        	$atributos ['nombre'] = $esteCampo;
			        	$atributos ['tipo'] = 'hidden';
			        	$atributos ['estilo'] = 'jqueryui';
			        	$atributos ['marco'] = true;
			        	$atributos ['columnas'] = 1;
			        	$atributos ['dobleLinea'] = false;
			        	$atributos ['tabIndex'] = $tab;
			        	//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			        	
			        	$atributos ['obligatorio'] = false;
			        	$atributos ['etiquetaObligatorio'] = false;
			        	$atributos ['validar'] = '';
			        	
			        	if (isset ( $_REQUEST [$esteCampo] )) {
			        		$atributos ['valor'] = $_REQUEST [$esteCampo];
			        	} else {
			        		$atributos ['valor'] = '';
			        	}
			        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
			        	$atributos ['deshabilitado'] = false;
			        	$atributos ['tamanno'] = 30;
			        	$atributos ['maximoTamanno'] = '';
			        	$tab ++;
			        	
			        	// Aplica atributos globales al control
			        	$atributos = array_merge ( $atributos, $atributosGlobales );
			        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
			        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
			        	
			        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			        	$esteCampo = 'funcionarioIdiomaObservacion_'.$i;
			        	$baseCampo = 'funcionarioIdiomaObservacion';
			        	$atributos ['id'] = $esteCampo;
			        	$atributos ['nombre'] = $esteCampo;
			        	$atributos ['estilo'] = '';
			        	$atributos ['marco'] = false;
			        	$atributos ['columnas'] = 50;
			        	$atributos ['filas'] = 3;
			        	$atributos ['tabIndex'] = $tab;
			        	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
			        	
			        	$atributos ['obligatorio'] = false;
			        	$atributos ['etiquetaObligatorio'] = false;
			        	$atributos ['validar'] = '';
			        	
			        	if (isset ( $_REQUEST [$esteCampo] )) {
			        		$atributos ['valor'] = $_REQUEST [$esteCampo];
			        	} else {
			        		$atributos ['valor'] = '';
			        	}
			        	$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
			        	$tab ++;
			        	
			        	// Aplica atributos globales al control
			        	$atributos = array_merge ( $atributos, $atributosGlobales );
			        	echo $this->miFormulario->campoTextArea ( $atributos );
			        	// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------

	        	}
	        	echo $this->miFormulario->marcoAgrupacion ( 'fin' );
	        		
	        }
	        unset($atributos);
	        $atributos ["id"] = "mainIdioma";
	        $atributos ["estilo"] = "botonDinamico";
	        echo $this->miFormulario->agrupacion ( "inicio", $atributos );
	        {
	        	echo "<input type=\"button\" id=\"btAddId\" value=\"Agregar\" class=\"btn btn-success\"/> &nbsp;";
	        	echo "<input type=\"button\" id=\"btRemoveId\" value=\"Eliminar\" class=\"btn btn-danger\" /> &nbsp;";
	        }
	        echo $this->miFormulario->agrupacion ( "fin" );
	         
	        
	        // ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	        
	        $esteCampo = 'novedadesDatosPublicaciones';
	        $atributos['texto'] = ' ';
	        $atributos['estilo'] = 'text-success';
	        $atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTexto( $atributos );
	        
	        // --------------------------------------------------------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioPublicacionesTematica';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['estilo'] = '';
	        $atributos ['marco'] = false;
	        $atributos ['columnas'] = 50;
	        $atributos ['filas'] = 3;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos ['anchoEtiqueta'] = 300;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = '';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTextArea ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        	    
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioPublicacionesTipo';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos ['anchoEtiqueta'] = 300;
	         
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[onlyLetterSp]';
	         
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 50;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	         
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioPublicacionesLogros';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['tipo'] = 'text';
	        $atributos ['estilo'] = 'jqueryui';
	        $atributos ['marco'] = true;
	        $atributos ['columnas'] = 1;
	        $atributos ['dobleLinea'] = false;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos ['anchoEtiqueta'] = 300;
	        
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = 'custom[onlyLetterSp]';
	        
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $atributos ['deshabilitado'] = false;
	        $atributos ['tamanno'] = 50;
	        $atributos ['maximoTamanno'] = '';
	        $tab ++;
	        
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoCuadroTexto ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        
	        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	        $esteCampo = 'funcionarioPublicacionesReferencias';
	        $atributos ['id'] = $esteCampo;
	        $atributos ['nombre'] = $esteCampo;
	        $atributos ['estilo'] = '';
	        $atributos ['marco'] = false;
	        $atributos ['columnas'] = 50;
	        $atributos ['filas'] = 3;
	        $atributos ['tabIndex'] = $tab;
	        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
	        $atributos ['anchoEtiqueta'] = 300;
	         
	        $atributos ['obligatorio'] = false;
	        $atributos ['etiquetaObligatorio'] = false;
	        $atributos ['validar'] = '';
	         
	        if (isset ( $_REQUEST [$esteCampo] )) {
	        	$atributos ['valor'] = $_REQUEST [$esteCampo];
	        } else {
	        	$atributos ['valor'] = '';
	        }
	        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	        $tab ++;
	         
	        // Aplica atributos globales al control
	        $atributos = array_merge ( $atributos, $atributosGlobales );
	        echo $this->miFormulario->campoTextArea ( $atributos );
	        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	        	    
	        
	        
	        //echo "<a href=\"#\" id=\"mascampos\">Más campos</a>"; ----Mirar para Campos dinamicos
	        }
	        echo $this->miFormulario->agrupacion ( "fin" );
	        
	        
	    }
	    echo $this->miFormulario->marcoAgrupacion ( 'fin' );
	    
//***************************************************************************************************************
//***************************************************************************************************************
        
	    $esteCampo = "novedadesDatosExperiencia";
	    $atributos ['id'] = $esteCampo;
	    $atributos ["estilo"] = "jqueryui";
	    $atributos ['tipoEtiqueta'] = 'inicio';
	    $atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
	    echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
	    unset ( $atributos );
        {
        	
        	$atributos ["id"] = "botonDatos";
        	$atributos ["estilo"] = "botonDatos";
        	echo $this->miFormulario->division ( "inicio", $atributos );
        	{
        		echo "<button id=\"mostrarb5\" name=\"mas1\" ALIGN=RIGHT class=\"\">Atrás</button>";
        	}
        	echo $this->miFormulario->division ( "fin" );
        	 
        	$atributos ["id"] = "contentDatos5";
        	$atributos ["estilo"] = "marcoBotones";
        	echo $this->miFormulario->agrupacion ( "inicio", $atributos );
        	{
        		
	    	// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	    	 
	    	$esteCampo = 'novedadesDatosExperienciaLaboral';
	    	$atributos['texto'] = ' ';
	    	$atributos['estilo'] = 'text-success';
	    	$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	    	$tab ++;
	    	 
	    	// Aplica atributos globales al control
	    	$atributos = array_merge ( $atributos, $atributosGlobales );
	    	echo $this->miFormulario->campoTexto( $atributos );
	    	 
	    	// --------------------------------------------------------------------------------------------------
	    	 
	    	for($i = 0; $i < $cantidad_experiencia; $i++){
	    		 
	    		 
	    		$esteCampo = "novedadesDatosCantidadExperiencia_";
	    		$baseCampo = "novedadesDatosCantidadExperiencia";
	    		$atributos ['id'] = $esteCampo.$i;
	    		$atributos ["estilo"] = "jqueryui";
	    		$atributos ['tipoEtiqueta'] = 'inicio';
	    		$numero_estudio = $i+1;
	    		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $baseCampo ).$numero_estudio;
	    		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
	    		{
	    			 
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaEmpresa_'.$i;
	    			$baseCampo = 'funcionarioExperienciaEmpresa';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = true;
	    			$atributos ['etiquetaObligatorio'] = true;
	    			$atributos ['validar'] = 'custom[onlyLetterSp]';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 100;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaEmpresaNIT_'.$i;
	    			$baseCampo = 'funcionarioExperienciaEmpresaNIT';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = false;
	    			$atributos ['etiquetaObligatorio'] = false;
	    			$atributos ['validar'] = 'custom[onlyNumberSp]';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 15;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Select --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaTipo_'.$i;
	    			$baseCampo = 'funcionarioExperienciaTipo';
	    			$atributos['nombre'] = $esteCampo;
	    			$atributos['id'] = $esteCampo;
	    			$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			$atributos['tab'] = $tab;
	    			$atributos['seleccion'] = -1;
	    			$atributos['evento'] = ' ';
	    			$atributos['deshabilitado'] = false;
	    			$atributos['limitar']= 50;
	    			$atributos['tamanno']= 1;
	    			$atributos['columnas']= 1;
	    			 
	    			$atributos ['obligatorio'] = true;
	    			$atributos ['etiquetaObligatorio'] = true;
	    			$atributos ['validar'] = '';
	    			 
	    			$matrizItems=array(
	    					array(1,'Pública'),
	    					array(2,'Privada')
	    					 
	    			);
	    			$atributos['matrizItems'] = $matrizItems;
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control select
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroLista ( $atributos );
	    			// --------------- FIN CONTROL : Select --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Select --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaPais_'.$i;
	    			$baseCampo = 'funcionarioExperienciaPais';
	    			$atributos['nombre'] = $esteCampo;
	    			$atributos['id'] = $esteCampo;
	    			$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			$atributos['tab'] = $tab;
	    			$atributos['seleccion'] = -1;
	    			$atributos['evento'] = ' ';
	    			$atributos['deshabilitado'] = false;
	    			$atributos['limitar']= 50;
	    			$atributos['tamanno']= 1;
	    			$atributos['columnas']= 1;
	    			 
	    			$atributos ['obligatorio'] = true;
	    			$atributos ['etiquetaObligatorio'] = true;
	    			$atributos ['validar'] = '';
	    			 
	    			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
	    			$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	    			
	    			$atributos['matrizItems'] = $matrizItems;
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroLista ( $atributos );
	    			// --------------- FIN CONTROL : Select --------------------------------------------------
	    			 
	    			// ---------------- CONTROL: Select --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaDepartamento_'.$i;
	    			$baseCampo = 'funcionarioExperienciaDepartamento';
	    			$atributos['nombre'] = $esteCampo;
	    			$atributos['id'] = $esteCampo;
	    			$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			$atributos['tab'] = $tab;
	    			$atributos['seleccion'] = -1;
	    			$atributos['evento'] = ' ';
	    			$atributos['deshabilitado'] = true;
	    			$atributos['limitar']= 50;
	    			$atributos['tamanno']= 1;
	    			$atributos['columnas']= 1;
	    			 
	    			$atributos ['obligatorio'] = true;
	    			$atributos ['etiquetaObligatorio'] = true;
	    			$atributos ['validar'] = '';
	    			 
	    			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
	    			$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	    			
	    			$atributos['matrizItems'] = $matrizItems;
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroLista ( $atributos );
	    			// --------------- FIN CONTROL : Select --------------------------------------------------
	    			 
	    			// ---------------- CONTROL: Select --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaCiudad_'.$i;
	    			$baseCampo = 'funcionarioExperienciaCiudad';
	    			$atributos['nombre'] = $esteCampo;
	    			$atributos['id'] = $esteCampo;
	    			$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			$atributos['tab'] = $tab;
	    			$atributos['seleccion'] = -1;
	    			$atributos['evento'] = ' ';
	    			$atributos['deshabilitado'] = true;
	    			$atributos['limitar']= 50;
	    			$atributos['tamanno']= 1;
	    			$atributos['columnas']= 1;
	    			 
	    			$atributos ['obligatorio'] = true;
	    			$atributos ['etiquetaObligatorio'] = true;
	    			$atributos ['validar'] = '';
	    			 
	    			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudad" );
	    			$matrizItems = $primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
	    			
	    			$atributos['matrizItems'] = $matrizItems;
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroLista ( $atributos );
	    			// --------------- FIN CONTROL : Select --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaEmpresaCorreo_'.$i;
	    			$baseCampo = 'funcionarioExperienciaEmpresaCorreo';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = true;
	    			$atributos ['etiquetaObligatorio'] = true;
	    			$atributos ['validar'] = 'custom[email]';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 50;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaEmpresaTelefono_'.$i;
	    			$baseCampo = 'funcionarioExperienciaEmpresaTelefono';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = true;
	    			$atributos ['etiquetaObligatorio'] = true;
	    			$atributos ['validar'] = 'custom[phone]';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 10;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioFechaEntradaExperiencia_'.$i;
	    			$baseCampo = 'funcionarioFechaEntradaExperiencia';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			
	    			$atributos ['obligatorio'] = true;
	    			$atributos ['etiquetaObligatorio'] = true;
	    			$atributos ['validar'] = 'custom[date]';
	    			
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 10;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    				     
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioFechaSalidaExperiencia_'.$i;
	    			$baseCampo = 'funcionarioFechaSalidaExperiencia';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			
	    			$atributos ['obligatorio'] = true;
	    			$atributos ['etiquetaObligatorio'] = true;
	    			$atributos ['validar'] = 'custom[date]';
	    			
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 10;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaEmpresaDependencia_'.$i;
	    			$baseCampo = 'funcionarioExperienciaEmpresaDependencia';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = false;
	    			$atributos ['etiquetaObligatorio'] = false;
	    			$atributos ['validar'] = ' ';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 20;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaEmpresaCargo_'.$i;
	    			$baseCampo = 'funcionarioExperienciaEmpresaCargo';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = true;
	    			$atributos ['etiquetaObligatorio'] = true;
	    			$atributos ['validar'] = 'custom[onlyLetterSp]';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 30;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioExperienciaEmpresaHoras_'.$i;
	    			$baseCampo = 'funcionarioExperienciaEmpresaHoras';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = false;
	    			$atributos ['etiquetaObligatorio'] = false;
	    			$atributos ['validar'] = 'custom[onlyNumberSp]';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 10;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			unset($atributos);
	    			$esteCampo = 'funcionarioSoporteExperiencia_'.$i;
	    			$baseCampo = 'funcionarioSoporteExperiencia';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'hidden';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			
	    			$atributos ['obligatorio'] = false;
	    			$atributos ['etiquetaObligatorio'] = false;
	    			$atributos ['validar'] = '';
	    			
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 150;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			
	    		}
	    		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
	    	}
	    	unset($atributos);
	    	$atributos ["id"] = "mainExperiencia";
	    	$atributos ["estilo"] = "botonDinamico";
	    	echo $this->miFormulario->agrupacion ( "inicio", $atributos );
	    	{
	    		echo "<input type=\"button\" id=\"btAddEx\" value=\"Agregar\" class=\"btn btn-success\"/> &nbsp;";
	    		echo "<input type=\"button\" id=\"btRemoveEx\" value=\"Eliminar\" class=\"btn btn-danger\" /> &nbsp;";
	    	}
	    	echo $this->miFormulario->agrupacion ( "fin" );
	    	
//*********************************************************************************************************************	    	
	    	
	    	// ---------------- CONTROL: Cuadro Mensaje SubTitulo -----------------------------------------------
	    	 
	    	$esteCampo = 'novedadesDatosReferenciaLaboral';
	    	$atributos['texto'] = ' ';
	    	$atributos['estilo'] = 'text-success';
	    	$atributos['etiqueta'] = "<h4>".$this->lenguaje->getCadena ( $esteCampo )."</h4>";
	    	$tab ++;
	    	 
	    	// Aplica atributos globales al control
	    	$atributos = array_merge ( $atributos, $atributosGlobales );
	    	echo $this->miFormulario->campoTexto( $atributos );
	    	 
	    	// --------------------------------------------------------------------------------------------------

	  
	    	 
	    	for($i = 0; $i < $cantidad_referencias_per; $i++){
	    	
	    	
	    		$esteCampo = "novedadesDatosCantidadReferencia_";
	    		$baseCampo = "novedadesDatosCantidadReferencia";
	    		$atributos ['id'] = $esteCampo.$i;
	    		$atributos ["estilo"] = "jqueryui";
	    		$atributos ['tipoEtiqueta'] = 'inicio';
	    		$numero_estudio = $i+1;
	    		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $baseCampo ).$numero_estudio;
	    		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
	    		{
	    			
	    			// ---------------- CONTROL: Select --------------------------------------------------------
	    			$esteCampo = 'funcionarioReferenciaTipo_'.$i;
	    			$baseCampo = 'funcionarioReferenciaTipo';
	    			$atributos['nombre'] = $esteCampo;
	    			$atributos['id'] = $esteCampo;
	    			$atributos['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			$atributos['tab'] = $tab;
	    			$atributos['seleccion'] = -1;
	    			$atributos['evento'] = ' ';
	    			$atributos['deshabilitado'] = false;
	    			$atributos['limitar']= 50;
	    			$atributos['tamanno']= 1;
	    			$atributos['columnas']= 1;
	    			 
	    			$atributos ['obligatorio'] = false;
	    			$atributos ['etiquetaObligatorio'] = false;
	    			$atributos ['validar'] = ' ';
	    			 
	    			$matrizItems=array(
	    					array(1,'Personal'),
	    					array(2,'Profesional')
	    						
	    			);
	    			$atributos['matrizItems'] = $matrizItems;
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroLista ( $atributos );
	    			// --------------- FIN CONTROL : Select --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioReferenciaNombres_'.$i;
	    			$baseCampo = 'funcionarioReferenciaNombres';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = false;
	    			$atributos ['etiquetaObligatorio'] = false;
	    			$atributos ['validar'] = 'custom[onlyLetterSp]';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 50;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioReferenciaApellidos_'.$i;
	    			$baseCampo = 'funcionarioReferenciaApellidos';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = false;
	    			$atributos ['etiquetaObligatorio'] = false;
	    			$atributos ['validar'] = 'custom[onlyLetterSp]';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 50;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioReferenciaTelefono_'.$i;
	    			$baseCampo = 'funcionarioReferenciaTelefono';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = false;
	    			$atributos ['etiquetaObligatorio'] = false;
	    			$atributos ['validar'] = 'custom[phone]';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 10;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			$esteCampo = 'funcionarioReferenciaRelacion_'.$i;
	    			$baseCampo = 'funcionarioReferenciaRelacion';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'text';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			 
	    			$atributos ['obligatorio'] = false;
	    			$atributos ['etiquetaObligatorio'] = false;
	    			$atributos ['validar'] = 'custom[onlyLetterSp]';
	    			 
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 20;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			 
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    			
	    			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    			unset($atributos);
	    			$esteCampo = 'funcionarioSoporteReferencia_'.$i;
	    			$baseCampo = 'funcionarioSoporteReferencia';
	    			$atributos ['id'] = $esteCampo;
	    			$atributos ['nombre'] = $esteCampo;
	    			$atributos ['tipo'] = 'hidden';
	    			$atributos ['estilo'] = 'jqueryui';
	    			$atributos ['marco'] = true;
	    			$atributos ['columnas'] = 1;
	    			$atributos ['dobleLinea'] = false;
	    			$atributos ['tabIndex'] = $tab;
	    			//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $baseCampo );
	    			
	    			$atributos ['obligatorio'] = false;
	    			$atributos ['etiquetaObligatorio'] = false;
	    			$atributos ['validar'] = '';
	    			
	    			if (isset ( $_REQUEST [$esteCampo] )) {
	    				$atributos ['valor'] = $_REQUEST [$esteCampo];
	    			} else {
	    				$atributos ['valor'] = '';
	    			}
	    			$atributos ['titulo'] = $this->lenguaje->getCadena ( $baseCampo . 'Titulo' );
	    			$atributos ['deshabilitado'] = false;
	    			$atributos ['tamanno'] = 150;
	    			$atributos ['maximoTamanno'] = '';
	    			$tab ++;
	    			
	    			// Aplica atributos globales al control
	    			$atributos = array_merge ( $atributos, $atributosGlobales );
	    			echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    			// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    	
	    		}
	    		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
	    	}
	    	unset($atributos);
	    	$atributos ["id"] = "mainReferencias";
	    	$atributos ["estilo"] = "botonDinamico";
	    	echo $this->miFormulario->agrupacion ( "inicio", $atributos );
	    	{
	    		echo "<input type=\"button\" id=\"btAddRe\" value=\"Agregar\" class=\"btn btn-success\"/> &nbsp;";
	    		echo "<input type=\"button\" id=\"btRemoveRe\" value=\"Eliminar\" class=\"btn btn-danger\" /> &nbsp;";
	    	}
	    	echo $this->miFormulario->agrupacion ( "fin" );
	    	
	    	
	    	
	    	
	    	}
	    	echo $this->miFormulario->agrupacion ( "fin" );
	    	
	    
	    }
	    echo $this->miFormulario->marcoAgrupacion ( 'fin' );
	    
//*************************************************************************************************************	    
	    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    $esteCampo = 'funcionarioRegistrosSuperior';
	    $atributos ['id'] = $esteCampo;
	    $atributos ['nombre'] = $esteCampo;
	    $atributos ['tipo'] = 'hidden';
	    $atributos ['estilo'] = 'jqueryui';
	    $atributos ['marco'] = true;
	    $atributos ['columnas'] = 1;
	    $atributos ['dobleLinea'] = false;
	    $atributos ['tabIndex'] = $tab;
	    $atributos ['etiqueta'] = '';
	     
	    $atributos ['obligatorio'] = false;
	    $atributos ['etiquetaObligatorio'] = false;
	    $atributos ['validar'] = 'custom[onlyLetterSp]';
	     
	    if (isset ( $_REQUEST [$esteCampo] )) {
	    	$atributos ['valor'] = $_REQUEST [$esteCampo];
	    } else {
	    	$atributos ['valor'] = 0;
	    }
	    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	    $atributos ['deshabilitado'] = false;
	    $atributos ['tamanno'] = 30;
	    $atributos ['maximoTamanno'] = '';
	    $tab ++;
	     
	    // Aplica atributos globales al control
	    $atributos = array_merge ( $atributos, $atributosGlobales );
	    echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    
	    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    $esteCampo = 'funcionarioRegistrosInformal';
	    $atributos ['id'] = $esteCampo;
	    $atributos ['nombre'] = $esteCampo;
	    $atributos ['tipo'] = 'hidden';
	    $atributos ['estilo'] = 'jqueryui';
	    $atributos ['marco'] = true;
	    $atributos ['columnas'] = 1;
	    $atributos ['dobleLinea'] = false;
	    $atributos ['tabIndex'] = $tab;
	    $atributos ['etiqueta'] = '';
	    
	    $atributos ['obligatorio'] = false;
	    $atributos ['etiquetaObligatorio'] = false;
	    $atributos ['validar'] = 'custom[onlyLetterSp]';
	    
	    if (isset ( $_REQUEST [$esteCampo] )) {
	    	$atributos ['valor'] = $_REQUEST [$esteCampo];
	    } else {
	    	$atributos ['valor'] = 0;
	    }
	    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	    $atributos ['deshabilitado'] = false;
	    $atributos ['tamanno'] = 30;
	    $atributos ['maximoTamanno'] = '';
	    $tab ++;
	    
	    // Aplica atributos globales al control
	    $atributos = array_merge ( $atributos, $atributosGlobales );
	    echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    
	    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    $esteCampo = 'funcionarioRegistrosIdioma';
	    $atributos ['id'] = $esteCampo;
	    $atributos ['nombre'] = $esteCampo;
	    $atributos ['tipo'] = 'hidden';
	    $atributos ['estilo'] = 'jqueryui';
	    $atributos ['marco'] = true;
	    $atributos ['columnas'] = 1;
	    $atributos ['dobleLinea'] = false;
	    $atributos ['tabIndex'] = $tab;
	    $atributos ['etiqueta'] = '';
	    
	    $atributos ['obligatorio'] = false;
	    $atributos ['etiquetaObligatorio'] = false;
	    $atributos ['validar'] = 'custom[onlyLetterSp]';
	    
	    if (isset ( $_REQUEST [$esteCampo] )) {
	    	$atributos ['valor'] = $_REQUEST [$esteCampo];
	    } else {
	    	$atributos ['valor'] = 0;
	    }
	    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	    $atributos ['deshabilitado'] = false;
	    $atributos ['tamanno'] = 30;
	    $atributos ['maximoTamanno'] = '';
	    $tab ++;
	    
	    // Aplica atributos globales al control
	    $atributos = array_merge ( $atributos, $atributosGlobales );
	    echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    
	    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    $esteCampo = 'funcionarioRegistrosExperiencia';
	    $atributos ['id'] = $esteCampo;
	    $atributos ['nombre'] = $esteCampo;
	    $atributos ['tipo'] = 'hidden';
	    $atributos ['estilo'] = 'jqueryui';
	    $atributos ['marco'] = true;
	    $atributos ['columnas'] = 1;
	    $atributos ['dobleLinea'] = false;
	    $atributos ['tabIndex'] = $tab;
	    $atributos ['etiqueta'] = '';
	    
	    $atributos ['obligatorio'] = false;
	    $atributos ['etiquetaObligatorio'] = false;
	    $atributos ['validar'] = 'custom[onlyLetterSp]';
	    
	    if (isset ( $_REQUEST [$esteCampo] )) {
	    	$atributos ['valor'] = $_REQUEST [$esteCampo];
	    } else {
	    	$atributos ['valor'] = 0;
	    }
	    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	    $atributos ['deshabilitado'] = false;
	    $atributos ['tamanno'] = 30;
	    $atributos ['maximoTamanno'] = '';
	    $tab ++;
	    
	    // Aplica atributos globales al control
	    $atributos = array_merge ( $atributos, $atributosGlobales );
	    echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
	    
	    
	    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	    $esteCampo = 'funcionarioRegistrosReferencia';
	    $atributos ['id'] = $esteCampo;
	    $atributos ['nombre'] = $esteCampo;
	    $atributos ['tipo'] = 'hidden';
	    $atributos ['estilo'] = 'jqueryui';
	    $atributos ['marco'] = true;
	    $atributos ['columnas'] = 1;
	    $atributos ['dobleLinea'] = false;
	    $atributos ['tabIndex'] = $tab;
	    $atributos ['etiqueta'] = '';
	     
	    $atributos ['obligatorio'] = false;
	    $atributos ['etiquetaObligatorio'] = false;
	    $atributos ['validar'] = 'custom[onlyLetterSp]';
	     
	    if (isset ( $_REQUEST [$esteCampo] )) {
	    	$atributos ['valor'] = $_REQUEST [$esteCampo];
	    } else {
	    	$atributos ['valor'] = 0;
	    }
	    $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
	    $atributos ['deshabilitado'] = false;
	    $atributos ['tamanno'] = 30;
	    $atributos ['maximoTamanno'] = '';
	    $tab ++;
	     
	    // Aplica atributos globales al control
	    $atributos = array_merge ( $atributos, $atributosGlobales );
	    echo $this->miFormulario->campoCuadroTexto ( $atributos );
	    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
//*********************************************************************************************************	    
	    
	    }
	    echo $this->miFormulario->marcoAgrupacion ( 'fin' );
        
        // ------------------Division para los botones-----------------------------------------------------
        $atributos ["id"] = "botones";
        $atributos ["estilo"] = "marcoBotones";
        $atributos ["titulo"] = "Enviar Información";
        echo $this->miFormulario->division ( "inicio", $atributos );

        // -----------------CONTROL: Botón ----------------------------------------------------------------
        $esteCampo = 'botonGuardar';
        $atributos ["id"] = $esteCampo;
        $atributos ["tabIndex"] = $tab ++;
        $atributos ["tipo"] = 'boton';
        // submit: no se coloca si se desea un tipo button genérico
        $atributos ['submit'] = true;
        $atributos ["estiloMarco"] = '';
        $atributos ["estiloBoton"] = 'jqueryui';
        // verificar: true para verificar el formulario antes de pasarlo al servidor.
        $atributos ["verificar"] = '';
        $atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
        $atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoBoton ( $atributos );
        // -----------------FIN CONTROL: Botón -----------------------------------------------------------

        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division ( "fin" );

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

        $valorCodificado = "actionBloque=" . $esteBloque ["nombre"]; //Ir pagina Funcionalidad
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );//Frontera mostrar formulario
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=registrar";
        /**
         * SARA permite que los nombres de los campos sean dinámicos.
         * Para ello utiliza la hora en que es creado el formulario para
         * codificar el nombre de cada campo. 
         */
        $valorCodificado .= "&campoSeguro=" . $_REQUEST['tiempo'];
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
        $atributos ['marco'] = false;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario ( $atributos );

        return true;

    }

    function mensaje() {

        // Si existe algun tipo de error en el login aparece el siguiente mensaje
        $mensaje = $this->miConfigurador->getVariableConfiguracion ( 'mostrarMensaje' );
        $this->miConfigurador->setVariableConfiguracion ( 'mostrarMensaje', null );

        if ($mensaje) {

            $tipoMensaje = $this->miConfigurador->getVariableConfiguracion ( 'tipoMensaje' );

            if ($tipoMensaje == 'json') {

                $atributos ['mensaje'] = $mensaje;
                $atributos ['json'] = true;
            } else {
                $atributos ['mensaje'] = $this->lenguaje->getCadena ( $mensaje );
            }
            // -------------Control texto-----------------------
            $esteCampo = 'divMensaje';
            $atributos ['id'] = $esteCampo;
            $atributos ["tamanno"] = '';
            $atributos ["estilo"] = 'information';
            $atributos ["etiqueta"] = '';
            $atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
            echo $this->miFormulario->campoMensaje ( $atributos );
            unset ( $atributos );

             
        }

        return true;

    }

}

$miFormulario = new Formulario ( $this->lenguaje, $this->miFormulario, $this->sql );


$miFormulario->formulario ();
$miFormulario->mensaje ();

?>
