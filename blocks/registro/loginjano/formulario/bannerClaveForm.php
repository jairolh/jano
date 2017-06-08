<?php

namespace registro\loginjano;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class cabecera {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;

    function __construct($lenguaje, $formulario) {
        $this->miConfigurador = \Configurador::singleton();

        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');

        $this->lenguaje = $lenguaje;

        $this->miFormulario = $formulario;
    }
    
    function estructura() {
        // ------------------- Inicio División -------------------------------
        $esteCampo = 'divGeneral2';
        $atributos ['id'] = $esteCampo;
        $atributos ['estilo'] = 'divGeneral2';
        $atributos ['estiloEnLinea'] = '';
        //$atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        echo $this->miFormulario->division("inicio", $atributos); {

            // ------------------- Inicio División -------------------------------
            $esteCampo = 'divLogoNotificador';
            $atributos ['id'] = $esteCampo;
            $atributos['imagen'] = $this->miConfigurador->getVariableConfiguracion('host') .$this->miConfigurador->getVariableConfiguracion('site') . '/blocks/gui/bannerUsuario/css/images/bannerJano2.jpg';
            $atributos['estilo'] = $esteCampo;
           // $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
            $atributos['ancho'] = '100%';
            $atributos['alto'] = '5%';
                        
            echo $this->miFormulario->campoImagen($atributos);
            unset($atributos);

            // ------------------- Inicio División -------------------------------
        }

        // ---------------------Fin Division -----------------------------------
        echo $this->miFormulario->division("fin");
    }

}

?>
