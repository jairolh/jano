<?php
// url base

$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";
// Variables
$cadenaACodificar16 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar16 .= "&procesarAjax=true";
$cadenaACodificar16 .= "&action=index.php";
$cadenaACodificar16 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar16 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar16 .= $cadenaACodificar16 . "&funcion=consultarDepartamentoAjax";
$cadenaACodificar16 .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena16 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar16, $enlace );
// URL definitiva
$urlFinal16 = $url . $cadena16;
// echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";
// Variables
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
//echo $urlFinal16; exit;
// var_dump(112);exit;

//---------------------------------contacto----------------------------------------

$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";
// Variables
$cadenaACodificar18 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar18 .= "&procesarAjax=true";
$cadenaACodificar18 .= "&action=index.php";
$cadenaACodificar18 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar18 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar18 .= $cadenaACodificar18 . "&funcion=consultarDepartamentoAjax";
$cadenaACodificar18 .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena18 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar18, $enlace );
// URL definitiva
$urlFinal18 = $url . $cadena18;
// echo $urlFinal16; exit;

// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";
// Variables
$cadenaACodificar19 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar19 .= "&procesarAjax=true";
$cadenaACodificar19 .= "&action=index.php";
$cadenaACodificar19 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar19 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar19 .= $cadenaACodificar19 . "&funcion=consultarCiudadAjax";
$cadenaACodificar19 .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena19 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar19, $enlace );
// URL definitiva
$urlFinal19 = $url . $cadena19;

?>

<script>

function consultarDepartamento(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinal16?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('personaNaturalPais')?>").val()},
	    success: function(data){ 
	        if(data[0]!=" "){
		        alert("wtre");
	            $("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>").html('');
	            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>");
	            $.each(data , function(indice,valor){
	            	$("<option value='"+data[ indice ].id_departamento+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>");
	            	
	            });
	            
	            $("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>").removeAttr('disabled');
	            
	            //$('#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>').width(250);
	            $("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>").select2();
	            
	            $("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>").removeClass("validate[required]");
	            
		        }
	  			
	    }
		                    
	   });
	};

	function consultarCiudad(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal17?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('personaNaturalCiudad')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalCiudad')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalCiudad')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('personaNaturalCiudad')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('personaNaturalCiudad')?>').width(250);
		            $("#<?php echo $this->campoSeguro('personaNaturalCiudad')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('personaNaturalCiudad')?>").removeClass("validate[required]");
		            
			        }
		    			
		    }
			                    
		   });
		};

//------------------------contacto---------------------------

function consultarDepartamentoCon(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinal18?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('personaNaturalContactosPais')?>").val()},
	    success: function(data){ 
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>").html('');
	            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>");
	            $.each(data , function(indice,valor){
	            	$("<option value='"+data[ indice ].id_departamento+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>");
	            	
	            });
	            
	            $("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>").removeAttr('disabled');
	            
	            //$('#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>').width(250);
	            $("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>").select2();
	            
	            $("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>").removeClass("validate[required]");
	            
		        }
	  			
	    }
		                    
	   });
	};

	function consultarCiudadCon(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal19?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('personaNaturalContactosCiudad')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalContactosCiudad')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalContactosCiudad')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('personaNaturalContactosCiudad')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('personaNaturalContactosCiudad')?>').width(250);
		            $("#<?php echo $this->campoSeguro('personaNaturalContactosCiudad')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('personaNaturalContactosCiudad')?>").removeClass("validate[required]");
		            
			        }
		    			
		    }
			                    
		   });
		};

	
		 $(function () {
		        $("#<?php echo $this->campoSeguro('personaNaturalPais')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaNaturalPais')?>").val()!=''){
						consultarDepartamento();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>").attr('disabled','');
		    			}
		    	      });
		        $("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaNaturalDepartamento')?>").val()!=''){
		            	consultarCiudad();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaNaturalCiudad')?>").attr('disabled','');
		    			}
		    	      });

		        $("#<?php echo $this->campoSeguro('personaNaturalContactosPais')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaNaturalContactosPais')?>").val()!=''){
						consultarDepartamentoCon();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>").attr('disabled','');
		    			}
		    	      });
		        $("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaNaturalContactosDepartamento')?>").val()!=''){
		            	consultarCiudadCon();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaNaturalContactosCiudad')?>").attr('disabled','');
		    			}
		    	      });
	    	      
		 });
		
</script>