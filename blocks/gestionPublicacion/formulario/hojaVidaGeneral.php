<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
 * La ruta absoluta del bloque está definida en $this->ruta
 */
$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
$nombreFormulario = $esteBloque ["nombre"];
include_once ("core/crypto/Encriptador.class.php");
$cripto = Encriptador::singleton ();
$valorCodificado = "action=" . $esteBloque ["nombre"];
$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$valorCodificado = $cripto->codificar ( $valorCodificado );
$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" ) . "css/images/";

// ------------------Division para las pestañas-------------------------
$atributos ["id"] = "tabs";
$atributos ["estilo"] = "";
$atributos ["estiloEnLinea"] = "display:none;"; 

echo $this->miFormulario->division ( "inicio", $atributos );
// unset ( $atributos );
{   
    
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
           $esteCampo = 'logo';
           $atributos ['id'] = $esteCampo;
           $atributos['imagen']= $directorio."LogoJano_Nombre_banco.png";
           $atributos['estilo']='campoImagen';
           $atributos['etiqueta']='Jano - Banco hojas de vida y Concuros de Méritos';
           $atributos['borde']='2';
           $atributos ['ancho'] = '120px';
           $atributos ['alto'] = '50px';
           //$atributos = array_merge ( $atributos, $atributosGlobales );
           echo  $this->miFormulario->campoImagen( $atributos );
           unset ( $atributos );
         // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------  
    
                $items = array (
                            "tabBasicos"   => $this->lenguaje->getCadena ( "tabBasicos" ),            
                            "tabContacto"  => $this->lenguaje->getCadena ( "tabContacto" ),
                            "tabFormacion"  => $this->lenguaje->getCadena ( "tabFormacion" ),
                            "tabProfesional"  => $this->lenguaje->getCadena ( "tabProfesional" ),
                            "tabDocencia"  => $this->lenguaje->getCadena ( "tabDocencia" ),
                            "tabInvestigacion"  => $this->lenguaje->getCadena ( "tabInvestigacion" ),
                            "tabProduccion"  => $this->lenguaje->getCadena ( "tabProduccion" ),
                            "tabActividad"  => $this->lenguaje->getCadena ( "tabActividad" ),
                            "tabIdiomas"  => $this->lenguaje->getCadena ( "tabIdiomas" ),
                            //"tabRegistrarMasivo" => $this->lenguaje->getCadena ( "tabRegistrarMasivo" ) 
            );
            $atributos ["items"] = $items;
            $atributos ["estilo"] = "";
            $atributos ["pestañas"] = "true";
            //$atributos ["menu"] = "true";
            //$atributos ["enlacePestaña"] = "true";
            echo $this->miFormulario->listaNoOrdenada ( $atributos );
            unset ( $atributos );



       // ------------------Division para la pestaña 1-------------------------
            $atributos ["id"] = "tabBasicos";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                {//echo '<iframe src="'.$enlaceBasico.'" style="width: 100%; height: 100% " frameborder="0"></iframe> '; 
                 include_once ($this->ruta . "formulario/tabs/datosBasicosGeneral.php");
                }
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 1-------------------------

            // ------------------Division para la pestaña 2-------------------------
            $atributos ["id"] = "tabContacto";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                   include_once ($this->ruta . "formulario/tabs/datosContactoGeneral.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 2-------------------------
            // ------------------Division para la pestaña 3-------------------------
            $atributos ["id"] = "tabFormacion";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                   include_once ($this->ruta . "formulario/tabs/datosFormacionGeneral.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 3-------------------------
            // ------------------Division para la pestaña 4-------------------------
            $atributos ["id"] = "tabProfesional";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                   include_once ($this->ruta . "formulario/tabs/datosProfesionalGeneral.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 4-------------------------
            // ------------------Division para la pestaña 5-------------------------
            $atributos ["id"] = "tabDocencia";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                   include_once ($this->ruta . "formulario/tabs/datosDocenciaGeneral.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 5-------------------------
            // ------------------Division para la pestaña 6-------------------------
            $atributos ["id"] = "tabInvestigacion";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                   include_once ($this->ruta . "formulario/tabs/datosInvestigacionGeneral.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 6-------------------------
            // ------------------Division para la pestaña 7-------------------------
            $atributos ["id"] = "tabProduccion";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                   include_once ($this->ruta . "formulario/tabs/datosProduccionGeneral.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 7-------------------------
            // ------------------Division para la pestaña 8-------------------------
            $atributos ["id"] = "tabActividad";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                   include_once ($this->ruta . "formulario/tabs/datosActividadGeneral.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 8-------------------------
            // ------------------Division para la pestaña 9-------------------------
            $atributos ["id"] = "tabIdiomas";
            $atributos ["estilo"] = "";
            echo $this->miFormulario->division ( "inicio", $atributos );
                   include_once ($this->ruta . "formulario/tabs/datosIdiomaGeneral.php"); 
            echo $this->miFormulario->division ( "fin" );
            unset ( $atributos );
            // -----------------Fin Division para la pestaña 9-------------------------
	
}

echo $this->miFormulario->division ( "fin" );
   
// ------------------Inicio Division para progreso-------------------------
include_once 'my_scriptjs.php'; 
    $atributos ["id"] = "divcarga";
    $atributos ["estilo"] = "jqueryu";
    $atributos ["estiloEnLinea"] = "text-align:center;height:300px;"; 
    echo $this->miFormulario->division ( "inicio", $atributos );
    unset ( $atributos );
        {
        $atributos ["id"] = "gif";
        $atributos ["estilo"] = "jqueryu";
        $atributos ["mensaje"] = "Procesando la información<br>Espere por favor ...";
        $atributos ["estiloEnLinea"] = "color:#000;margin-top:20px; font-size:20px;font-weight:bold;"; 
        //$atributos ["tamanno"] = "grande";
        echo "<img  src='".$directorio."load.gif'>";
        echo $this->miFormulario->campoMensaje ( $atributos );
        unset ( $atributos );
        }
    echo $this->miFormulario->division ("fin");
    unset ( $atributos );
    // ------------------Fin Division para progreso-------------------------    
//llama funcion para visualizar al div cuando termina de cargar
echo "<script language='javascript'> setTimeout(function(){desbloquea('divcarga','tabs')},500)  </script>";
?>
