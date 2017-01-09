

$("#gestionRetiro").validationEngine({
	promptPosition : "centerRight",
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});


    
    
$('#datepicker').datepicker({
	autoHidePrompt: true
});

$('#<?php echo $this->campoSeguro('tipoVinculacion')?>').width(250); 
$("#<?php echo $this->campoSeguro('tipoVinculacion')?>").select2();