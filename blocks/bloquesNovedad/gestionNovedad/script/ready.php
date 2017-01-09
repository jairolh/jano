

$("#gestionNovedad").validationEngine({
	promptPosition : "centerRight",
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});


    
    
$('#datepicker').datepicker({
	autoHidePrompt: true
});

$('#<?php echo $this->campoSeguro('fdpDepartamento')?>').width(200); 
$("#<?php echo $this->campoSeguro('fdpDepartamento')?>").select2();

$('#<?php echo $this->campoSeguro('naturaleza')?>').width(200); 
$("#<?php echo $this->campoSeguro('naturaleza')?>").select2();

$('#<?php echo $this->campoSeguro('concepto')?>').width(200); 
$("#<?php echo $this->campoSeguro('concepto')?>").select2();

$('#<?php echo $this->campoSeguro('categoriaConceptos')?>').width(200); 
$("#<?php echo $this->campoSeguro('categoriaConceptos')?>").select2();

$('#<?php echo $this->campoSeguro('tipoNovedad')?>').width(200); 
$("#<?php echo $this->campoSeguro('tipoNovedad')?>").select2();

$('#<?php echo $this->campoSeguro('ley')?>').width(200); 
$("#<?php echo $this->campoSeguro('ley')?>").select2();
