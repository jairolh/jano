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
$this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "raizSoportes" );

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
    $variable.= "&opcion=consutarReclamaciones";
    $variable.= "&usuario=".$_REQUEST['usuario'];
    $variable.= "&consecutivo_concurso=".$_REQUEST['consecutivo_concurso'];
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

		$resultadoConcurso=true;

                if($resultadoConcurso)
                    {
  										 // -------------------- Listado de Pestañas (Como lista No Ordenada) -------------------------------
                       $items = array ( //"tabCalendario" => $this->lenguaje->getCadena ( "tabCalendario" ),
                                        "tabInscritos" => $this->lenguaje->getCadena ( "tabInscritos" ),
                                        //"tabRegistrarMasivo" => $this->lenguaje->getCadena ( "tabRegistrarMasivo" )
                        );
                        $atributos ["items"] = $items;
                        $atributos ["estilo"] = "jqueryui";
                        $atributos ["pestañas"] = "true";
                        echo $this->miFormulario->listaNoOrdenada ( $atributos );
                        // unset ( $atributos );
                        // ------------------Division para la pestaña 1-------------------------
                        /*$atributos ["id"] = "tabCalendario";
                        $atributos ["estilo"] = "";
                        echo $this->miFormulario->division ( "inicio", $atributos );
                        include_once ($this->ruta . "formulario/tabs/consultarCalendarioEjecuta.php");
                        echo $this->miFormulario->division ( "fin" );
                        unset ( $atributos );*/
                        // -----------------Fin Division para la pestaña 1-------------------------
                        // ------------------Division para la pestaña 2-------------------------
                        $atributos ["id"] = "tabInscritos";
                        $atributos ["estilo"] = "";
                        echo $this->miFormulario->division ( "inicio", $atributos );
                        include_once ($this->ruta . "formulario/tabs/consultarInscritos.php");
												//include_once ($this->ruta . "formulario/tabs/validarRequisitos.php");
                        echo $this->miFormulario->division ( "fin" );
                        unset ( $atributos );
                        // -----------------Fin Division para la pestaña 2-------------------------
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
