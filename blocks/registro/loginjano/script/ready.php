<?php 

$_REQUEST['tiempo']=time();


?>            
            
// Asociar el widget de validación al formulario
$("#<?php echo sha1('login'.$_REQUEST['tiempo']);?>").validationEngine({
	promptPosition : "centerRight",
	scroll : false
});

$('#<?php echo sha1('usuario'.$_REQUEST['tiempo']);?>').keydown(function(e) {
    if (e.keyCode == 13) {
        $('#login').submit();
    }
});

$('#<?php echo sha1('clave'.$_REQUEST['tiempo']);?>').keydown(function(e) {
    if (e.keyCode == 13) {
        $('#<?php echo sha1('login'.$_REQUEST['tiempo']);?>').submit();
    }
});

        $(function() {
            $("#loginjano").submit(function() {
                $resultado=$("#loginjano").validationEngine("validate");
                if ($resultado) {
                                
                    return true;
                    
                }
                return false;
            });
        });


 $( "button" ).button().click(function( event ) 
 {
    event.preventDefault();
    });
    
setTimeout(function() {
    $('#divMensaje').hide( "drop", { direction: "up" }, "slow" );
}, 10000); // <-- time in milliseconds


        $(function() {
		$(document).tooltip();
	});
	
	// Asociar el widget tabs a la división cuyo id es tabs
	$(function() {
		$("#tabs").tabs();
	});

        $(function() {
            $("button").button().click(function(event) {
                    event.preventDefault();
            });
        });

$('#<?php echo $this->campoSeguro('tipo_identificacion')?>').width(210);
$("#<?php echo $this->campoSeguro('tipo_identificacion')?>").select2(); 



