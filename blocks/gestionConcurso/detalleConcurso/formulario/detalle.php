<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
 *
 * La ruta absoluta del bloque está definida en $this->ruta
 */

$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
$nombreFormulario = $esteBloque ["nombre"];
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
$this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "host" ) .$this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";

include_once ("core/crypto/Encriptador.class.php");
$cripto = Encriptador::singleton ();
$valorCodificado = "action=" . $esteBloque ["nombre"];
$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$valorCodificado = $cripto->codificar ( $valorCodificado );
$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" ) . "/imagen/";

// conectar base de datos
$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if(isset($_REQUEST['consecutivo_concurso']))
    {   $parametro['consecutivo_concurso']=$_REQUEST['consecutivo_concurso'];
        $cadena_sql = $this->sql->getCadenaSql("consultaConcurso", $parametro);
        $resultadoConcurso = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        $parametroSop = array('consecutivo'=>0,
                             'tipo_dato'=>'datosConcurso',
                             'nombre_soporte'=>'soporteAcuerdo',
                             'consecutivo_dato'=>$_REQUEST['consecutivo_concurso']);
        $cadenaSopAcu_sql = $this->sql->getCadenaSql("buscarSoporte", $parametroSop);
        $resultadoSopAcu = $esteRecursoDB->ejecutarAcceso($cadenaSopAcu_sql, "busqueda");
    }

// ------------------Division para las pestañas-------------------------
$atributos ["id"] = "tabs";
$atributos ["estilo"] = "";
echo $this->miFormulario->division ( "inicio", $atributos );
// unset ( $atributos );
{ // ---------------- SECCION: Controles del Formulario -----------------------------------------------
    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
    $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
    $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
    $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
    $directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
    $directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
    $directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
    $variable = "pagina=" . $miPaginaActual;
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );

    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
    $esteCampo = 'botonRegresar';
    $atributos ['id'] = $esteCampo;
    $atributos ['enlace'] = $variable;
    $atributos ['tabIndex'] = 1;
    $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
    $atributos ['estilo'] = 'textoPequenno textoGris';
    $atributos ['enlaceImagen'] = $rutaBloque."/images/player_rew.png";
    $atributos ['posicionImagen'] = "atras";//"adelante";
    $atributos ['ancho'] = '30px';
    $atributos ['alto'] = '30px';
    $atributos ['redirLugar'] = true;
    echo $this->miFormulario->enlace ( $atributos );
    echo "<br><br>";
    unset ( $atributos );
    
        $esteCampo = "marcoDetalleConcurso";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
        echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
        unset ( $atributos );
        {
                if($resultadoConcurso)
                    {       
                    $_REQUEST['inicio_concurso']=$resultadoConcurso[0]['fecha_inicio'];
                    $_REQUEST['cierre_concurso']=$resultadoConcurso[0]['fecha_fin'];
                    $datosConcurso=array(array('0'=>$resultadoConcurso[0]['nivel_concurso'],
                                            'Tipo'=>$resultadoConcurso[0]['nivel_concurso'],
                                            '1'=>$resultadoConcurso[0]['modalidad'],
                                            'Modalidad'=>$resultadoConcurso[0]['modalidad'],
                                            '2'=>$resultadoConcurso[0]['nombre'],
                                            'Nombre'=>$resultadoConcurso[0]['nombre'],
                                            '3'=>$resultadoConcurso[0]['descripcion'],
                                            'Descripcion'=>$resultadoConcurso[0]['descripcion'],
                                            '4'=>$resultadoConcurso[0]['fecha_inicio'],
                                            'Fecha inicial'=>$resultadoConcurso[0]['fecha_inicio'],
                                            '5'=>$resultadoConcurso[0]['fecha_fin'],
                                            'Fecha cierre'=>$resultadoConcurso[0]['fecha_fin'],
                                            '6'=>$resultadoConcurso[0]['estado'],
                                            'Estado'=>$resultadoConcurso[0]['estado'],
                                            '7'=>$resultadoConcurso[0]['acuerdo'],
                                            'Acuerdo'=>$resultadoConcurso[0]['acuerdo'],
                                             ));
                    
                    // ---------------- CONTROL: Cuadro de division --------------------------------------------------------
                                            $atributos ["id"]="acuerdo";
                                            $atributos ["estiloEnLinea"] = "border-width: 0";//display:block";
                                            $atributos = array_merge ( $atributos );
                                            echo $this->miFormulario->division ( "inicio", $atributos );
                                            unset ( $atributos );
                                                    {// --------------- CONTROL : Cuadro de Texto --------------------------------------------------  
                                                       $esteCampo = 'ruta_acuerdo';
                                                       $atributos ['id'] = $esteCampo;
                                                       $atributos ['nombre'] = $esteCampo;
                                                       $atributos ['tipo'] = 'hidden';
                                                       $atributos ['estilo'] = 'jqueryui';
                                                       $atributos ['marco'] = true;
                                                       $atributos ['columnas'] = 1;
                                                       $atributos ['dobleLinea'] = false;
                                                       $atributos ['tabIndex'] = $tab=0;
                                                       $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                       $atributos ['obligatorio'] = false;
                                                       $atributos ['etiquetaObligatorio'] = false;
                                                       $atributos ['validar'] = '';
                                                       $atributos ['valor'] = $this->rutaSoporte.$resultadoSopAcu[0]['ubicacion']."/".$resultadoSopAcu[0]['archivo'];
                                                       $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                       $atributos ['deshabilitado'] = FALSE;
                                                       $atributos ['tamanno'] = 30;
                                                       $atributos ['anchoCaja'] = 60;
                                                       $atributos ['maximoTamanno'] = '';
                                                       $atributos ['anchoEtiqueta'] = 170;
                                                       //$atributos = array_merge ( $atributos, $atributosGlobales );
                                                       echo $this->miFormulario->campoCuadroTexto ( $atributos );
                                                       // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                       // --------------- CONTROL : Tabla --------------------------------------------------  
                                                       echo $this->miFormulario->tablaReporte ($datosConcurso); 
                                                       // --------------- Fin CONTROL : Tabla --------------------------------------------------  
                                                       // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                       $esteCampo = 'archivoAcuerdo';
                                                       $atributos ['id'] = $esteCampo;
                                                       $atributos ['enlace'] = 'javascript:soporte("ruta_acuerdo");';
                                                       $atributos ['tabIndex'] = 0;
                                                       $atributos ['marco'] = true;
                                                       $atributos ['columnas'] = 1;
                                                       $atributos ['enlaceTexto'] = $resultadoSopAcu[0]['alias'];
                                                       $atributos ['estilo'] = 'textoPequenno textoGris ';
                                                       $atributos ['enlaceImagen'] = $rutaBloque."/images/pdfImage.png";
                                                       $atributos ['posicionImagen'] ="atras";//"adelante";
                                                       $atributos ['ancho'] = '25px';
                                                       $atributos ['alto'] = '25px';
                                                       $atributos ['redirLugar'] = false;
                                                       $atributos ['valor'] = '';
                                                       $atributos = array_merge ( $atributos);
                                                       echo $this->miFormulario->enlace( $atributos );
                                                       unset ( $atributos );
                                                       // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                       
                                                  }
                                            echo $this->miFormulario->division( 'fin' );
                                            unset ( $atributos );
                                    // --------------- FIN CONTROL : Cuadro de Soporte Diploma --------------------------------------------------


                        // -------------------- Listado de Pestañas (Como lista No Ordenada) -------------------------------

                        $items = array (
                                        "tabCriterio" => $this->lenguaje->getCadena ( "tabCriterio" ),
                                        "tabCalendario" => $this->lenguaje->getCadena ( "tabCalendario" ),
                                        "tabPerfil" => $this->lenguaje->getCadena ( "tabPerfil" ),
                                        //"tabRegistrarMasivo" => $this->lenguaje->getCadena ( "tabRegistrarMasivo" ) 
                        );
                        $atributos ["items"] = $items;
                        $atributos ["estilo"] = "jqueryui";
                        $atributos ["pestañas"] = "true";
                        echo $this->miFormulario->listaNoOrdenada ( $atributos );
                        // unset ( $atributos );
                        // ------------------Division para la pestaña 1-------------------------
                        $atributos ["id"] = "tabCriterio";
                        $atributos ["estilo"] = "";
                        echo $this->miFormulario->division ( "inicio", $atributos );
                        include_once ($this->ruta . "formulario/tabs/datosCriterio.php"); 
                        if(!isset($_REQUEST['consecutivo_evaluar']))
                               {include_once ($this->ruta . "formulario/tabs/consultarCriterio.php"); }
                        echo $this->miFormulario->division ( "fin" );
                        unset ( $atributos );
                        // -----------------Fin Division para la pestaña 1-------------------------
                        // ------------------Division para la pestaña 2-------------------------
                        $atributos ["id"] = "tabCalendario";
                        $atributos ["estilo"] = "";
                        echo $this->miFormulario->division ( "inicio", $atributos );
                        include_once ($this->ruta . "formulario/tabs/datosCalendario.php"); 
                        if(!isset($_REQUEST['consecutivo_calendario']))
                               {include_once ($this->ruta . "formulario/tabs/consultarCalendario.php"); }
                               
                        echo $this->miFormulario->division ( "fin" );
                        unset ( $atributos );
                        // -----------------Fin Division para la pestaña 2-------------------------
                        // ------------------Division para la pestaña 3-------------------------
                        $atributos ["id"] = "tabPerfil";
                        $atributos ["estilo"] = "";
                        echo $this->miFormulario->division ( "inicio", $atributos );
                        include_once ($this->ruta . "formulario/tabs/datosPerfil.php"); 
                        if(!isset($_REQUEST['consecutivo_perfil']))
                               {include_once ($this->ruta . "formulario/tabs/consultarPerfil.php"); }
                        echo $this->miFormulario->division ( "fin" );
                        unset ( $atributos );
                        // -----------------Fin Division para la pestaña 3-------------------------                        
            }else
                    {   $tab=1;
                        //---------------Inicio Formulario (<form>)--------------------------------
                        $atributos["id"]="divNoEncontroConcurso";
                        $atributos["estilo"]="marcoBotones";
                        //$atributos["estiloEnLinea"]="display:none"; 
                            echo $this->miFormulario->division("inicio",$atributos);

                            //-------------Control Boton-----------------------
                            $esteCampo = "noEncontroDetalle";
                            $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                            $atributos["etiqueta"] = "";
                            $atributos["estilo"] = "centrar";
                            $atributos["tipo"] = 'error';
                            $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
                            echo $this->miFormulario->cuadroMensaje($atributos);
                            unset($atributos); 
                            //------------------Fin Division para los botones-------------------------
                            echo $this->miFormulario->division("fin");
                            //-------------Control cuadroTexto con campos ocultos-----------------------
                    }            
        }            
         echo $this->miFormulario->marcoAgrupacion ( 'fin' );   

	
	// ------------------Division para la pestaña 2-------------------------
	
        /*
        $atributos ["id"] = "tabRegistrarMasivo";
	$atributos ["estilo"] = "";
	echo $this->miFormulario->division ( "inicio", $atributos );
	{
		include ($this->ruta . "formulario/tabs/registro_masivo.php");
	}*/
	
	// -----------------Fin Division para la pestaña 2-------------------------
	echo $this->miFormulario->division ( "fin" );
	
}

echo $this->miFormulario->division ( "fin" );

?>
