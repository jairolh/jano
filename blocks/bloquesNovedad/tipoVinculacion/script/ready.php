

$("#tipoVinculacion").validationEngine({
	promptPosition : "centerRight",
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});


    
    
$('#datepicker').datepicker({
	autoHidePrompt: true
});

$('#<?php echo $this->campoSeguro('naturaleza')?>').width(250); 
$("#<?php echo $this->campoSeguro('naturaleza')?>").select2();

$('#<?php echo $this->campoSeguro('naturaleza1')?>').width(250); 
$("#<?php echo $this->campoSeguro('naturaleza1')?>").select2();


$('#<?php echo $this->campoSeguro('ley')?>').width(250); 
$("#<?php echo $this->campoSeguro('ley')?>").select2();


$('#<?php echo $this->campoSeguro('reglamentacion')?>').width(250); 
$("#<?php echo $this->campoSeguro('reglamentacion')?>").select2();

$('#<?php echo $this->campoSeguro('rubro')?>').width(250); 
$("#<?php echo $this->campoSeguro('rubro')?>").select2();


$('#<?php echo $this->campoSeguro('tipoLiquidacion')?>').width(250); 
$("#<?php echo $this->campoSeguro('tipoLiquidacion')?>").select2();

$( '#<?php echo $this->campoSeguro('ley')?>' ).change(function() {
		$("#<?php echo $this->campoSeguro('leyRegistros') ?>").val($("#<?php echo $this->campoSeguro('ley') ?>").val());
});

$( '#<?php echo $this->campoSeguro('rubro')?>' ).change(function() {
		$("#<?php echo $this->campoSeguro('rubros') ?>").val($("#<?php echo $this->campoSeguro('rubro') ?>").val());
});