<?php

?>

<script>
$( "#<?php echo $this->campoSeguro('personaNaturalPrimerNombre')?>" ).change(function() {
	$("#<?php echo $this->campoSeguro('personaNaturalPrimerApellido') ?>").val('Nada');
	$("#<?php echo $this->campoSeguro('personaCarrera') ?>").val(-6);
});


	        $("#<?php echo $this->campoSeguro('fax')?>").change(function(){
                 if($("#<?php echo $this->campoSeguro('fax')?>").val()!=''){
                        
	            	$("#<?php echo $this->campoSeguro('extencionFax')?>").removeAttr('readonly');
	    		}
                        else{
                             $("#<?php echo $this->campoSeguro('extencionFax')?>").val('');
                            $("#<?php echo $this->campoSeguro('extencionFax')?>").attr('readonly','');
                        }
                 });






</script>