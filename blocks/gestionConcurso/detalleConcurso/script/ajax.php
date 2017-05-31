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
$cadenaACodificar16 .= $cadenaACodificar16 . "&funcion=consultarModalidad";
if(isset($_REQUEST['id_usuario']))
    {$cadenaACodificar16 .= "&id_usuario=".$_REQUEST['id_usuario'];}
$cadenaACodificar16 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena16 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar16, $enlace );

// URL definitiva
$urlFinal16 = $url . $cadena16;


?>

<script type='text/javascript'>

function soporte(archivo) {
  var miPopup
  miPopup = window.open('about:blank','soporte','width=600,height=850,menubar=no') 
  //miPopup.location = $("#<?php echo $this->campoSeguro('rutasoporte')?>").val();
  miPopup.location = $("#"+archivo).val();
}

function enlace(direccion) {
  var miVentana
  miVentana = window.open('about:blank','enlace','width=800,height=600,menubar=no,scrollbars=yes') 
  miVentana.location = $("#"+direccion).val();
}

function marcar(obj) {
    elem=obj.elements;
    for (i=0;i<elem.length;i++)
        if (elem[i].type=="checkbox")
            elem[i].checked=true;
} 

function desmarcar(obj) {
    elem=obj.elements;
    for (i=0;i<elem.length;i++)
        if (elem[i].type=="checkbox")
            elem[i].checked=false;
} 

function show(bloq) {
    obj = document.getElementById(bloq);
    obj.style.display = (obj.style.display=='none') ? 'block' : 'none';
}        

$(function () {
// Controles de validacion de tipo de archivo          
    $("input[type='file']").bind('change',function(){
       var sizeByte = this.files[0].size;
       var ext=$(this).val().substring($(this).val().lastIndexOf('.') + 1).toLowerCase();   
       var accept = $(this).attr('accept').toLowerCase();
       var siezekiloByte = parseInt(sizeByte / 1024);
       if(accept.indexOf(ext) >= 0){
              if(siezekiloByte > $(this).attr('size')){
                  alert('El tamaño del archivo, supera el limite permitido de '+($(this).attr('size')/1024).toFixed(2)+' Mb' );
                  $(this).val('');
                  }
          }
       else{alert('El tipo archivo no es permitido, debe ser '+accept);
            $(this).val('');
           }
     });
          
});


function consultarModalidad(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinal16?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('tipo')?>").val()},
	    success: function(data){ 
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('modalidad')?>").html('');
	            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('modalidad')?>");
	            $.each(data , function(indice,valor){
	            	$("<option value='"+data[ indice ].codigo+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('modalidad')?>");
	            });
	            $("#<?php echo $this->campoSeguro('modalidad')?>").removeAttr('disabled');
	            $('#<?php echo $this->campoSeguro('modalidad')?>').width(210);
	            $("#<?php echo $this->campoSeguro('modalidad')?>").select2();
		        }
	    }
		                    
	   });
	};

	    $(function () {
	        $("#<?php echo $this->campoSeguro('tipo')?>").change(function(){
	        	if($("#<?php echo $this->campoSeguro('tipo')?>").val()!=''){
	            	consultarModalidad();
	    		}else{
	    			$("#<?php echo $this->campoSeguro('modalidad')?>").attr('disabled','');
	    			}
	    	      });
	    });
</script>