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
$cadenaACodificar16 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar16 .= "&procesarAjax=true";
$cadenaACodificar16 .= "&action=index.php";
$cadenaACodificar16 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar16 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar16 .= "&funcion=consultarCriterios";
if(isset($_REQUEST['id_usuario']))
    {$cadenaACodificar16 .= "&id_usuario=".$_REQUEST['id_usuario'];}
$cadenaACodificar16 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadenaACodificar16 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar16, $enlace );

// URL definitiva
$urlFinal16 = $url . $cadenaACodificar16;



?>

<script type='text/javascript'>

function soporte(archivo) {
  var miPopup
  miPopup = window.open('about:blank','soporte','width=600,height=850,menubar=no') 
  //miPopup.location = $("#<?php echo $this->campoSeguro('rutasoporte')?>").val();
  miPopup.location = $("#"+archivo).val();
  miPopup.focus();
}

function enlaceSop(direccion) {
  var miVentana
  miVentana = window.open('about:blank','soporte','width=600,height=850,menubar=no,scrollbars=yes') 
  miVentana.location = $("#"+direccion).val();
  miVentana.focus();
}

function enlace(direccion) {
  var miVentana
  miVentana = window.open('about:blank','enlace','width=900,height=700,menubar=no,scrollbars=yes') 
  miVentana.location = $("#"+direccion).val();
  miVentana.focus();
}

function show(bloq) {
    obj = document.getElementById(bloq);
    obj.style.display = (obj.style.display=='none') ? 'block' : 'none';
}    

$("#<?php echo $this->campoSeguro('factor')?>").change(function(){
	if($("#<?php echo $this->campoSeguro('factor')?>").val()!=''){
    	consultarPerfil();
	}else{
		$("#<?php echo $this->campoSeguro('criterio')?>").attr('disabled','');
		}
  });

function consultarPerfil(){
	  $.ajax({
	    url: "<?php echo $urlFinal16?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('factor')?>").val()},
	    success: function(data){ 
	        if(data[0]!=" "){
		        
	            $("#<?php echo $this->campoSeguro('criterio')?>").html('');
	            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('criterio')?>");
	            $.each(data , function(indice,valor){
	            	$("<option value='"+data[ indice ].consecutivo_criterio+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('criterio')?>");
	            });
	            $("#<?php echo $this->campoSeguro('criterio')?>").removeAttr('disabled');
	            $('#<?php echo $this->campoSeguro('criterio')?>').width(210);
	            $("#<?php echo $this->campoSeguro('criterio')?>").select2();
		     }
	    },
        error: function(result) {
            console.log(result);
        }
		                    
	   });
};


   

</script>