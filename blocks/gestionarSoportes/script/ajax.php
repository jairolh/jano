<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */
// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

// Variables
$cadenaACodificar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar .= "&procesarAjax=true";
$cadenaACodificar .= "&action=index.php";
$cadenaACodificar .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar .= "&bloqueGrupo=" . $esteBloque ["grupo"];

$cadenaACodificar1 =$cadenaACodificar."&funcion=consultarConcepto";
$cadenaACodificar1 .= "&tiempo=" . $_REQUEST ['tiempo'];

//enlace 2
$cadenaACodificar2 =$cadenaACodificar."&funcion=consultarUnidad";
$cadenaACodificar2 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena1 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar1, $enlace );
$cadena2 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar2, $enlace );

// URL definitiva
$urlFinal1 = $url . $cadena1;
$urlFinal2 = $url . $cadena2;

?>

<script type='text/javascript'>

function consultarConcepto(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinal1?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('tipo')?>").val()},
	    success: function(data){ 
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('concepto')?>").html('');
	            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('concepto')?>");
	            $.each(data , function(indice,valor){
	            	$("<option value='"+data[ indice ].codigo+"'>"+data[ indice ].concepto+"</option>").appendTo("#<?php echo $this->campoSeguro('concepto')?>");
	            });
	            $("#<?php echo $this->campoSeguro('concepto')?>").removeAttr('disabled');
	            $('#<?php echo $this->campoSeguro('concepto')?>').width(300);
	            $("#<?php echo $this->campoSeguro('concepto')?>").select2();
		        }
	    }
		                    
	   });
	};

	    $(function () {
	        $("#<?php echo $this->campoSeguro('tipo')?>").change(function(){
	        	if($("#<?php echo $this->campoSeguro('tipo')?>").val()!=''){
	            	consultarConcepto();
	    		}else{
	    			$("#<?php echo $this->campoSeguro('concepto')?>").attr('disabled','');
	    			}
	    	      });
	    });


function consultarUnidad(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinal2?>",
	    dataType: "json",
	    data: {valor:$("#<?php echo $this->campoSeguro('concepto')?>").val()},
	    success: function(data){ 
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('unidad')?>").html('');
	            $.each(data , function(indice,valor){
	            	$("<option value='"+data[ indice ].codigo+"'>"+data[ indice ].unidad+"</option>").appendTo("#<?php echo $this->campoSeguro('unidad')?>");
	            });
	            $("#<?php echo $this->campoSeguro('unidad')?>").removeAttr('disabled');
	            $('#<?php echo $this->campoSeguro('unidad')?>').width(300);
	            $("#<?php echo $this->campoSeguro('unidad')?>").select2();
		        }
	    }
		                    
	   });
	};

 $(function () {
            $("#<?php echo $this->campoSeguro('concepto')?>").change(function(){
                    if($("#<?php echo $this->campoSeguro('concepto')?>").val()!=''){
                     consultarUnidad();
                    }else{
                            $("#<?php echo $this->campoSeguro('unidad')?>").attr('disabled','');
                            }
                  });
	    });
</script>