<?php

// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";
//Variables
$cadenaACodificar17 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar17 .= "&procesarAjax=true";
$cadenaACodificar17 .= "&action=index.php";
$cadenaACodificar17 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar17 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar17 .= $cadenaACodificar17 . "&funcion=consultarCiudadAjax";
$cadenaACodificar17 .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena17 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar17, $enlace );
// URL definitiva
$urlFinal17 = $url . $cadena17;
?>

<script>
    $('#<?php echo $this->campoSeguro('fdpDepartamento')?>').width(250);
$("#<?php echo $this->campoSeguro('fdpDepartamento')?>").select2();
$('#<?php echo $this->campoSeguro('fdpCiudad')?>').width(250);
$("#<?php echo $this->campoSeguro('fdpCiudad')?>").select2();

    function consultarCiudad(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal17?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('fdpDepartamento')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('fdpCiudad')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('fdpCiudad')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('fdpCiudad')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('fdpCiudad')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('fdpCiudad')?>').width(250);
		            $("#<?php echo $this->campoSeguro('fdpCiudad')?>").select2();
		            
		            
		            
			        }
		    			
		    }
			                    
		   });
		};

                
          $(function () {
	        
	        $("#<?php echo $this->campoSeguro('fdpDepartamento')?>").change(function(){
	        	if($("#<?php echo $this->campoSeguro('fdpDepartamento')?>").val()!=''){
	            	consultarCiudad();
	    		}else{
	    			$("#<?php echo $this->campoSeguro('fdpCiudad')?>").attr('disabled','');
	    			}
	    	      });
	        $("#<?php echo $this->campoSeguro('faxRegistro')?>").change(function(){
                 if($("#<?php echo $this->campoSeguro('faxRegistro')?>").val()!=''){
                        
	            	$("#<?php echo $this->campoSeguro('extFaxRegistro')?>").removeAttr('readonly');
	    		}
                        else{
                             $("#<?php echo $this->campoSeguro('extFaxRegistro')?>").val('');
                            $("#<?php echo $this->campoSeguro('extFaxRegistro')?>").attr('readonly','');
                        }
                 });
		
	    });
</script>