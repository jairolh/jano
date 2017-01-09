<?php 
namespace bloquesConcepto\asociacionConcepto\formulario;



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
        $directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
       $directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
       $directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
        // Rescatar los datos de este bloque
       $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
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
        $atributos ['marco'] = true;
        $tab = 1;
        // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------

        // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos ['tipoEtiqueta'] = 'inicio';
        echo $this->miFormulario->formulario ( $atributos );

        // ---------------- SECCION: Controles del Formulario -----------------------------------------------
        
        
       // --------------------------------------------------------------------------------------------------
        if($_REQUEST['opciones']==1){
      
            
        // --------------------------------------------------------------------------------------------------
        
        $esteCampo = "marcoDatosBasicos";
	$atributos ['id'] = $esteCampo;
	$atributos ["estilo"] = "jqueryui";
	$atributos ['tipoEtiqueta'] = 'inicio';
	$atributos ["leyenda"] = "Modelo de Vinculación Contratista";
	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
        
        

        // ---------------- CONTROL: Select --------------------------------------------------------
        $esteCampo = 'tipoContrato';
        $atributos['nombre'] = $esteCampo;
        $atributos['id'] = $esteCampo;
        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['marco'] = true;
        $atributos['tab'] = $tab;
        $atributos['seleccion'] = -1;
        $atributos['evento'] = ' ';
        $atributos['deshabilitado'] = false;
        $atributos['limitar']= 50;
        $atributos['tamanno']= 1;
        $atributos['columnas']= 1;
           $atributos ['anchoEtiqueta']=200;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required';
        
                
                 $naturaleza = array( 
    array(1,'CPS'), 
    array(2,'OPS'), 
   
); 
        $atributos['matrizItems'] =  $naturaleza;
        
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
               
          unset($atributos);
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'numeroRegistro';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
       
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '12432';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = true;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'vigencia';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '2016';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = true;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'numeroDisponibilidad';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
          $atributos ['anchoEtiqueta']=200; 
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '50000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
      
         // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'unidadEjecutora';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
          $atributos ['anchoEtiqueta']=200; 
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '30000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
           // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'rubro';
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
	      $atributos ['anchoEtiqueta']=200;
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	         
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarRubro" );
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
	         unset($atributos);
         
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'fechaDisponibilidad';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '2015-05-16';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = true;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'valorContrato';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '12000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = true;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'duracionContrato';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '12 meses';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'valorMensual';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '1000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'valorCancelado';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '6000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'saldo';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '6000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
         // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'leyRegistros';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'hidden';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
           $atributos ['anchoEtiqueta']=200;	
        $atributos ['valor'] = '';
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 30;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        	
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
          unset($atributos);      
           // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'rubrosRegistros';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'hidden';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
           $atributos ['anchoEtiqueta']=200;	
        $atributos ['valor'] = '';
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 30;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        	
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
          unset($atributos);     
        // -----------------FIN CONTROL: Botón -----------------------------------------------------------

        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division ( "fin" );
         
        // ------------------Division para los botones-------------------------
        $atributos ["id"] = "botones";
        $atributos ["estilo"] = "marcoBotones";
        $atributos ["titulo"] = "Enviar Información";
        echo $this->miFormulario->division ( "inicio", $atributos );

        // -----------------CONTROL: Botón ----------------------------------------------------------------
        $esteCampo = 'botonRegistrar';
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
        $atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoBoton ( $atributos );
        
      
        echo $this->miFormulario->marcoAgrupacion ( 'fin' );
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
       // $valorCodificado  = "action=" . $esteBloque ["nombre"];
        $valorCodificado = "actionBloque=" . $esteBloque ["nombre"];
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );//Frontera mostrar formulario
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=form";
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
        $atributos ['marco'] = true;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario ( $atributos );

            
        }else{
            
         $esteCampo = "marcoDatosBasicos";
	$atributos ['id'] = $esteCampo;
	$atributos ["estilo"] = "jqueryui";
	$atributos ['tipoEtiqueta'] = 'inicio';
	$atributos ["leyenda"] = "Modelo de Vinculación especial";
	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );     
            
         // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'numeroRegistro';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[2], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '123';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = true;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'numeroContrato';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[2], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '312';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = true;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'vigencia';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '2016';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = true;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'numeroDisponibilidad';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
          $atributos ['anchoEtiqueta']=200; 
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '50000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = true;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
      
         // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'unidadEjecutora';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
          $atributos ['anchoEtiqueta']=200; 
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '30000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = true;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
          
           // ---------------- CONTROL: Select --------------------------------------------------------
	        $esteCampo = 'rubro';
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
	      $atributos ['anchoEtiqueta']=200;
	        $atributos ['obligatorio'] = true;
	        $atributos ['etiquetaObligatorio'] = true;
	        $atributos ['validar'] = 'required';
	         
	        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarRubro" );
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
	         unset($atributos);
         
                 
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'fechaDisponibilidad';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '2015-05-16';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = true;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
                 
        // ---------------- CONTROL: Select --------------------------------------------------------
        $esteCampo = 'tipoVincu';
        $atributos['nombre'] = $esteCampo;
        $atributos['id'] = $esteCampo;
        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['marco'] = true;
        $atributos['tab'] = $tab;
        $atributos['seleccion'] = -1;
        $atributos['evento'] = ' ';
        $atributos['deshabilitado'] = false;
        $atributos['limitar']= 50;
        $atributos['tamanno']= 1;
        $atributos['columnas']= 1;
           $atributos ['anchoEtiqueta']=200;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required';
        
                
                 $naturaleza = array( 
    array(1,'Sueldos'), 
    array(2,'Honorarios'), 
   
); 
        $atributos['matrizItems'] =  $naturaleza;
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        $tab ++;
        
        
        
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroLista ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
         
         
        // ---------------- CONTROL: Select --------------------------------------------------------
        $esteCampo = 'dedicacion';
        $atributos['nombre'] = $esteCampo;
        $atributos['id'] = $esteCampo;
        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['marco'] = true;
        $atributos['tab'] = $tab;
        $atributos['seleccion'] = -1;
        $atributos['evento'] = ' ';
        $atributos['deshabilitado'] = false;
        $atributos['limitar']= 50;
        $atributos['tamanno']= 1;
        $atributos['columnas']= 1;
           $atributos ['anchoEtiqueta']=200;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required';
        
                
                 $naturaleza = array( 
    array(1,'TCO'), 
    array(2,'MTC'), 
    array(3,'HC'),                  
   
); 
        $atributos['matrizItems'] =  $naturaleza;
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        $tab ++;
        
        
        
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroLista ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
         
          
        // ---------------- CONTROL: Select --------------------------------------------------------
        $esteCampo = 'semanas';
        $atributos['nombre'] = $esteCampo;
        $atributos['id'] = $esteCampo;
        $atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['marco'] = true;
        $atributos['tab'] = $tab;
        $atributos['seleccion'] = -1;
        $atributos['evento'] = ' ';
        $atributos['deshabilitado'] = false;
        $atributos['limitar']= 50;
        $atributos['tamanno']= 1;
        $atributos['columnas']= 1;
           $atributos ['anchoEtiqueta']=200;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required';
        
                
                 $naturaleza = array( 
    array(1,'1'), 
    array(2,'2'), 
    array(3,'3'),                  
    array(4,'4'), 
    array(5,'5'),  
    array(6,'6'),  
    array(7,'7'),                   
    array(8,'8'),  
    array(9,'9'),  
    array(10,'10'),  
    array(11,'11'),   
    array(12,'12'),                  
    array(13,'13'),                  
    array(14,'14'),                  
    array(15,'15'),                  
    array(16,'16'),                  
    array(17,'17'),  
    array(18,'18'),   
    array(19,'19'),                  
    array(21,'20'),                  
    array(22,'21'),                  
    array(23,'22'),                  
    array(24,'23'),                  
    array(25,'24'),                    
                     
                     
                     
                     
); 
        $atributos['matrizItems'] =  $naturaleza;
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        $tab ++;
        
        
        
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroLista ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
         
         
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'horas';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[1], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'valorContrato';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '12000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'valorCancelado';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '1000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'saldo';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
           $atributos ['anchoEtiqueta']=200;
        $atributos ['obligatorio'] = true;
        $atributos ['etiquetaObligatorio'] = true;
        $atributos ['validar'] = 'required, minSize[4], maxSize[15]';
        
        if (isset ( $_REQUEST [$esteCampo] )) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '11000000.00';
        }
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        $atributos ['deshabilitado'] = false;
        $atributos ['filas'] = 4;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto ------------
         unset($atributos);
         
         // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'leyRegistros';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'hidden';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
           $atributos ['anchoEtiqueta']=200;	
        $atributos ['valor'] = '';
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 30;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        	
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
          unset($atributos);      
           // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'rubrosRegistros';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'hidden';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
           $atributos ['anchoEtiqueta']=200;	
        $atributos ['valor'] = '';
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 30;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        	
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
          unset($atributos);     
        // -----------------FIN CONTROL: Botón -----------------------------------------------------------

        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division ( "fin" );
         
        // --------------------------------------------------------------------------------------------------
        
        
        // ------------------Division para los botones-------------------------
        $atributos ["id"] = "botones";
        $atributos ["estilo"] = "marcoBotones";
        $atributos ["titulo"] = "Enviar Información";
        echo $this->miFormulario->division ( "inicio", $atributos );

        // -----------------CONTROL: Botón ----------------------------------------------------------------
        $esteCampo = 'botonRegistrar';
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
        $atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoBoton ( $atributos );
        
      
        
        // -----------------FIN CONTROL: Botón -----------------------------------------------------------

        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division ( "fin" );
        echo $this->miFormulario->marcoAgrupacion ( 'fin' );
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
       // $valorCodificado  = "action=" . $esteBloque ["nombre"];
        $valorCodificado = "actionBloque=" . $esteBloque ["nombre"];
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );//Frontera mostrar formulario
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=form";
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
        $atributos ['marco'] = true;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario ( $atributos );

        }
        
        
        
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