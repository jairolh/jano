

$("#gestionCategorias").validationEngine({
	promptPosition : "centerRight",
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});


    
    
$('#datepicker').datepicker({
	autoHidePrompt: true
});

$('#<?php echo $this->campoSeguro('personaNaturalPais')?>').width(); 
$("#<?php echo $this->campoSeguro('personaNaturalPais')?>").select2();

$('#<?php echo $this->campoSeguro('ley')?>').width(); 
$("#<?php echo $this->campoSeguro('ley')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>').width(); 
$("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalCiudad')?>').width(); 
$("#<?php echo $this->campoSeguro('personaNaturalCiudad')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalPaisMod')?>').width(); 
$("#<?php echo $this->campoSeguro('personaNaturalPaisMod')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalDepartamentoMod')?>').width(); 
$("#<?php echo $this->campoSeguro('personaNaturalDepartamentoMod')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalCiudadMod')?>').width(); 
$("#<?php echo $this->campoSeguro('personaNaturalCiudadMod')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalContactosPais')?>').width(); 
$("#<?php echo $this->campoSeguro('personaNaturalContactosPais')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>').width(); 
$("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalContactosCiudad')?>').width(); 
$("#<?php echo $this->campoSeguro('personaNaturalContactosCiudad')?>").select2();