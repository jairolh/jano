
$("#<?php echo $this->campoSeguro('funcionarioApellido')?>").validationEngine({
		promptPosition : "centerRight",
		scroll: false,
		autoHidePrompt: true,
		autoHideDelay: 2000,
	    updatePromptsPosition:true
});

$("#<?php echo $this->campoSeguro('funcionarioNombre')?>").validationEngine({
		promptPosition : "centerRight",
		scroll: false,
		autoHidePrompt: true,
		autoHideDelay: 2000,
	    updatePromptsPosition:true
});

/*
$('#tablaReporte').dataTable( {
	"sPaginationType": "full_numbers"
} );
*/

$('#datepicker').datepicker({
	autoHidePrompt: true
});

