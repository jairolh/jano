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
$cadenaACodificar17 .= $cadenaACodificar17 . "&funcion=consultarDependenciaAjax";
$cadenaACodificar17 .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena17 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar17, $enlace );
// URL definitiva
$urlFinal17 = $url . $cadena17;

// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";
//Variables
$cadenaACodificar18 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar18 .= "&procesarAjax=true";
$cadenaACodificar18 .= "&action=index.php";
$cadenaACodificar18 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar18 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar18 .= $cadenaACodificar18 . "&funcion=consultarTipoVinculacionAjax";
$cadenaACodificar18 .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena18 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar18, $enlace );
// URL definitiva
$urlFinal18 = $url . $cadena18;


?>

<script>
    
 function consultarDependencia(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal17?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('sede')?>").val()},
		    success: function(data){ 
		        if(data[0]!=""){
                            
		            $("#<?php echo $this->campoSeguro('dependencia')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('dependencia')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('dependencia')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('dependencia')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('dependencia')?>').width(250);
		            $("#<?php echo $this->campoSeguro('dependencia')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('dependencia')?>").removeClass("validate[required]");
		            
			        }
		    			
		    }
			                    
		   });
		};
                
     function consultarTipoVinculacion(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal18?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('tipoVinculacion')?> option:selected").text()},
		    success: function(data){ 
		        if(data[0]!=""){
		           $("#<?php echo $this->campoSeguro('fechaFin')?>").hide();
                           
		            if(data[0].naturaleza=="Temporal"){
                                 
		            $("#<?php echo $this->campoSeguro('fechaFin')?>").show();
                            
                        }
			        }
		    			
		    }
			                    
		   });
		};
                
          $(function () {
	        
	        $("#<?php echo $this->campoSeguro('sede')?>").change(function(){
	        	if($("#<?php echo $this->campoSeguro('sede')?>").val()!=''){
                           
	            	consultarDependencia();
	    		}else{
	    			$("#<?php echo $this->campoSeguro('dependencia')?>").attr('disabled','');
	    			}
	    	      });
                 
                 $("#<?php echo $this->campoSeguro('tipoVinculacion')?>").change(function(){
                
	        	if($("#<?php echo $this->campoSeguro('tipoVinculacion')?> ").val()!=''){
                      
                      
                        
	            	consultarTipoVinculacion();
	    		}else{
	    			$("#<?php echo $this->campoSeguro('fechaFin')?>").attr('disabled','');
	    			}
	    	      });
	        
		
	    });    
    
    
    
$( "#<?php echo $this->campoSeguro('personaNaturalPrimerNombre')?>" ).change(function() {
	$("#<?php echo $this->campoSeguro('personaNaturalPrimerApellido') ?>").val('Nada');
	$("#<?php echo $this->campoSeguro('personaCarrera') ?>").val(-6);
});



//--------------------FECHAS VALIDACION
	/*Validar Fecha de Retiro Mayor a la Fecha de Entrada Experiencia Laboral*/
	$('#<?php echo $this->campoSeguro('fechaInicio')?>').datepicker({
		autoHidePrompt: true,
		dateFormat: 'yy-mm-dd',
		
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		    onSelect: function(dateText, inst) {
			var lockDate = new Date($('#<?php echo $this->campoSeguro('fechaInicio')?>').datepicker('getDate'));
			$('input#<?php echo $this->campoSeguro('fechaFin')?>').datepicker('option', 'minDate', lockDate);
			},
			onClose: function() { 
		 	    if ($('input#<?php echo $this->campoSeguro('fechaInicio')?>').val()!='')
	             {
	                 $('#<?php echo $this->campoSeguro('fechaFin')?>').attr("class", "cuadroTexto ui-widget ui-widget-content ui-corner-all  ");
	         }else {
	                 $('#<?php echo $this->campoSeguro('fechaFin')?>').attr("class", "cuadroTexto ui-widget ui-widget-content ui-corner-all ");
	             }
			  }
			
			
		});
	       $('#<?php echo $this->campoSeguro('fechaFin')?>').datepicker({
		dateFormat: 'yy-mm-dd',
		
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		    onSelect: function(dateText, inst) {
			var lockDate = new Date($('#<?php echo $this->campoSeguro('fechaFin')?>').datepicker('getDate'));
			$('input#<?php echo $this->campoSeguro('fechaInicio')?>').datepicker('option', 'maxDate', lockDate);
			 },
			 onClose: function() { 
		 	    if ($('input#<?php echo $this->campoSeguro('fechaFin')?>').val()!='')
	             {
	                 $('#<?php echo $this->campoSeguro('fechaInicio')?>').attr("class", "cuadroTexto ui-widget ui-widget-content ui-corner-all   validate[required]");
	         }else {
	                 $('#<?php echo $this->campoSeguro('fechaInicio')?>').attr("class", "cuadroTexto ui-widget ui-widget-content ui-corner-all ");
	             }
			  }
			
	});
	//******************

	        
                 
                 ;
</script>