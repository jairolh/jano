<?php

namespace registro\loginjano;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class Formulario {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;

    function __construct($lenguaje, $formulario) {
        $this->miConfigurador = \Configurador::singleton();

        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');

        $this->lenguaje = $lenguaje;

        $this->miFormulario = $formulario;
    }

    function formulario() {
        $directorioImagenes = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque") . "/imagenes";
        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque .= $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
        $rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];
        ?>

        <!--        <div id="slider1_container" style="position: absolute; top: -500px; left: 0px; width: 100%; height: 50px; overflow: hidden;">
                     Slides Container 
                    <div u="slides" style="cursor: move; position: absolute; overflow: hidden; left: 0px; top: 0px; width: 100%; height: 100px; overflow: hidden;">-->

        <div id="slider1_container" style="position: absolute; margin: 0 auto; top: 0px; left: 0px; width: 600px; height: 300px;">
            <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 600px;  height: 300px;
                 overflow: hidden;">
                 <?php
//                for ($i = 2; $i < 20; $i++) {
//                    echo " <div><img u='image' src='" . $rutaBloque . "/imagenes/slide_" . $i . ".jpg' /></div>";
//                }
                 ?>

                <div><img u="image" src="<?php echo $rutaBloque ?>/imagenes/slide_17.jpg" /></div>
                <div><img u="image" src="<?php echo $rutaBloque ?>/imagenes/slide_15.jpg" /></div>
                <div><img u="image" src="<?php echo $rutaBloque ?>/imagenes/slide_7.jpg" /></div>
                <div><img u="image" src="<?php echo $rutaBloque ?>/imagenes/slide_6.jpg" /></div>
                <div><img u="image" src="<?php echo $rutaBloque ?>/imagenes/slide_10.jpg" /></div>
                <div><img u="image" src="<?php echo $rutaBloque ?>/imagenes/slide_12.jpg" /></div>
                <div><img u="image" src="<?php echo $rutaBloque ?>/imagenes/slide_13.jpg" /></div>
                <div><img u="image" src="<?php echo $rutaBloque ?>/imagenes/slide_16.jpg" /></div>
                <div><img u="image" src="<?php echo $rutaBloque ?>/imagenes/slide_18.jpg" /></div>
                <div><img u="image" src="<?php echo $rutaBloque ?>/imagenes/slide_8.jpg" /></div>
            </div>
        </div>
        <header>
            <div id = "fondo_base"></div>


        </header>
        <section>
            <article id = "fondo_login">
                <?php
                $atributosGlobales ['campoSeguro'] = 'false';
                $_REQUEST ['tiempo'] = time();

                // -------------------------------------------------------------------------------------------------
                // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
                $esteCampo = $esteBloque ['nombre'];
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                /**
                 * Nuevo a partir de la versión 1.0.0.2, se utiliza para crear de manera rápida el js asociado a
                 * validationEngine.
                 */
                $atributos ['validar'] = true;

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

                $atributos ["id"] = "botones";
                $atributos ["estilo"] = "marcoBotones";
                echo $this->miFormulario->division("inicio", $atributos);
                unset($atributos);

                
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $variable = "pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
                $variable .= "&opcion=" . "recuperarClave";
                $variable .= "&bloque=" . $esteBloque ['nombre'];
                $variable .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url( $variable );
                
                //echo "<a  href=index.php?data" . $variable . ">".$this->lenguaje->getCadena("recuperarClave")."</a>";

                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'recuperarClave';
                $atributos ['id'] = $esteCampo;
                $atributos ['enlace'] = "index.php?".$this->miConfigurador->getVariableConfiguracion('enlace').$variable;
                $atributos ['tabIndex'] = 1;
                $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                $atributos ['estilo'] = 'textoPequenno textoGris textoNegrita';
                $atributos ['enlaceImagen'] = $rutaBloque."/images/player_rew.png";
                $atributos ['posicionImagen'] = "";//"atras";//"adelante";
                $atributos ['ancho'] = '30px';
                $atributos ['alto'] = '30px';
                $atributos ['redirLugar'] = true;
                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo);
                echo $this->miFormulario->enlace ( $atributos );
                unset ( $atributos );                
                
                 // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $variable = "pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
                $variable .= "&opcion=" . "registro";
                $variable .= "&bloque=" . $esteBloque ['nombre'];
                $variable .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                $variableRegistro = $this->miConfigurador->fabricaConexiones->crypto->codificar_url( $variable );
                
                //echo "<a  href=index.php?data" . $variable . ">".$this->lenguaje->getCadena("recuperarClave")."</a>";
                
                 // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'registro';
                $atributos ['id'] = $esteCampo;
                $atributos ['enlace'] = "index.php?".$this->miConfigurador->getVariableConfiguracion('enlace').$variableRegistro;
                $atributos ['tabIndex'] = 1;
                $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                $atributos ['estilo'] = 'textoPequenno textoGris textoNegrita';
                $atributos ['enlaceImagen'] = $rutaBloque."/images/player_rew.png";
                $atributos ['posicionImagen'] = "";//"atras";//"adelante";
                $atributos ['ancho'] = '30px';
                $atributos ['alto'] = '30px';
                $atributos ['redirLugar'] = true;
                echo " | ".$this->miFormulario->enlace ( $atributos );
                unset ( $atributos );   

                 // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------                
                $esteCampo = 'usuario';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'text';
                $atributos ['estilo'] = 'login jqueryui';
                $atributos ['marco'] = false;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;
                $atributos ['textoFondo'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['validar'] = 'required';

                if (isset($_REQUEST [$esteCampo])) {
                    $atributos ['valor'] = $_REQUEST [$esteCampo];
                } else {
                    $atributos ['valor'] = '';
                }
                //$atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                $atributos ['titulo'] = '';
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 20;
                $atributos ['maximoTamanno'] = '25';
                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
                
                $esteCampo = 'clave';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'password';
                $atributos ['estilo'] = 'login jqueryui';
                $atributos ['marco'] = false;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;
                $atributos ['textoFondo'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['validar'] = 'required';

                if (isset($_REQUEST [$esteCampo])) {
                    $atributos ['valor'] = $_REQUEST [$esteCampo];
                } else {
                    $atributos ['valor'] = '';
                }
                //$atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                $atributos ['titulo'] = '';
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 20;
                $atributos ['maximoTamanno'] = '25';
                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                echo $this->miFormulario->division("fin");

                $atributos ["id"] = "botones";
                $atributos ["estilo"] = "marcoBotones";
                echo $this->miFormulario->division("inicio", $atributos);
                unset($atributos);

                // -----------------CONTROL: Botón ----------------------------------------------------------------
                $esteCampo = 'botonIngresar';
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

                // Paso 1: crear el listado de variables

                $valorCodificado = "actionBloque=" . $esteBloque ["nombre"];
                $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
                $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
                $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                $valorCodificado .= "&opcion=validarLogin";
                /**
                 * SARA permite que los nombres de los campos sean dinámicos.
                 * Para ello utiliza la hora en que es creado el formulario para
                 * codificar el nombre de cada campo.
                 */
                $valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
                $valorCodificado .= "&tiempo=" . $_REQUEST ['tiempo'];
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
                ?>
            </article>
            <div id="logo_u">
                <img src="<?php echo $directorioImagenes ?>/UD_logo2.png" />
            </div>

        </section>
        <section>
            <div id="fondo_texto">
                <div id="texto">
                    <h3>Sistema de Gestión para Concurso de Méritos</h3><BR>
                    <h2>JANO</h2>
                </div>
            </div>
        </section>
        <footer>
            <div id="footerLeft">
                <p style="font-size: 15px;">Universidad Distrital Francisco José de
                    Caldas</p>
                <p>
                    Todos los derechos reservados. Carrera 8 N. 40-78 Piso 1 / PBX
                    3238400 - 3239300 <a href="">computo@udistrital.edu.co</a>
                </p>
            </div>
            <div id="footerRight">
                <a href="https://www.facebook.com/UniversidadDistrital.SedeIngenieria"
                   target="_blank"><img
                        src="<?php echo $directorioImagenes ?>/facebook.png" /></a> <a
                    href="https://plus.google.com/110031223488101566921/about?gl=co&hl=es"
                    target="_blank"><img
                        src="<?php echo $directorioImagenes ?>/google+.png" /></a> <a
                    href="http://www.udistrital.edu.co/" target="_blank"><img
                        src="<?php echo $directorioImagenes ?>/mail.png" /></a>
            </div>
        </footer>

        <?php
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

$miFormulario = new Formulario($this->lenguaje, $this->miFormulario);
$miFormulario->formulario();
$miFormulario->mensaje();
?>
