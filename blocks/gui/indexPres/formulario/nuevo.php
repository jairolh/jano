<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");
?>


<div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 1800px; height: 500px; overflow: hidden;">
    <!-- Slides Container -->
    <div u="slides" style="cursor: move; position: absolute; overflow: hidden; left: 0px; top: 0px; width: 1800px; height: 500px; overflow: hidden;">

        <div><img u="image" src="<?php echo $rutaBloque ?>/images/slide_10.jpg" /></div>
        <div><img u="image" src="<?php echo $rutaBloque ?>/images/slide_12.jpg" /></div>
        <div><img u="image" src="<?php echo $rutaBloque ?>/images/slide_13.jpg" /></div>
        <div><img u="image" src="<?php echo $rutaBloque ?>/images/slide_16.jpg" /></div>
        <div><img u="image" src="<?php echo $rutaBloque ?>/images/slide_18.jpg" /></div>
                <div><img u="image" src="<?php echo $rutaBloque ?>/images/slide_8.jpg" /></div>
        <div><img u="image" src="<?php echo $rutaBloque ?>/images/slide_7.jpg" /></div>
        <div><img u="image" src="<?php echo $rutaBloque ?>/images/slide_15.jpg" /></div>
        <div><img u="image" src="<?php echo $rutaBloque ?>/images/slide_6.jpg" /></div>

    </div>
</div>