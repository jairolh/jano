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
if(isset($_REQUEST['id_usuario']))
    {$cadenaACodificar.= "&id_usuario=".$_REQUEST['id_usuario'];}
    
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );    
//modalidad
$cadenaACodificarMod = $cadenaACodificar . "&funcion=consultarModalidad";
$cadenaACodificarMod .= "&tiempo=" . $_REQUEST ['tiempo'];
$cadenaMod = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificarMod, $enlace );
$urlFinalMod = $url . $cadenaMod;
//criterio
$cadenaACodificarCrit = $cadenaACodificar . "&funcion=consultarCriterio";
$cadenaACodificarCrit .= "&tiempo=" . $_REQUEST ['tiempo'];
$cadenaCrit = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificarCrit, $enlace );
$urlFinalCrit = $url . $cadenaCrit;


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

$(function () {
    $("#<?php echo $this->campoSeguro('tipo')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('tipo')?>").val()!=''){
            consultarModalidad();
            }else{
                    $("#<?php echo $this->campoSeguro('modalidad')?>").attr('disabled','');
                    }
          });
    $("#<?php echo $this->campoSeguro('consecutivo_factor')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('consecutivo_factor')?>").val()!=''){
            consultarCriterio();
            }else{
                    $("#<?php echo $this->campoSeguro('consecutivo_criterio')?>").attr('disabled','');
                    }
          });

    $("#<?php echo $this->campoSeguro('maximo_puntos')?>").change(function(){
             $("#<?php echo $this->campoSeguro('puntos_aprueba')?>").val('0');
          });              

    $("#<?php echo $this->campoSeguro('puntos_aprueba')?>").change(function(){
        
            var maximo = parseFloat($("#<?php echo $this->campoSeguro('maximo_puntos')?>").val());
            var aprueba = parseFloat($("#<?php echo $this->campoSeguro('puntos_aprueba')?>").val());
            if(maximo<aprueba)
                {alert('El puntaje de aprobación no puede ser mayor al puntaje máximo!' );
                 $("#<?php echo $this->campoSeguro('puntos_aprueba')?>").val('0');
                }
            
          });              

             
          
          
});


function consultarModalidad(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinalMod?>",
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
	            $("#<?php echo $this->campoSeguro('modalidad')?>").width(210);
	            $("#<?php echo $this->campoSeguro('modalidad')?>").select2();
		        }
	    }
		                    
	   });
	};

function consultarCriterio(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinalCrit?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('consecutivo_factor')?>").val()},
	    success: function(data){ 
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('consecutivo_criterio')?>").html('');
	            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('consecutivo_criterio')?>");
	            $.each(data , function(indice,valor){
	            	$("<option value='"+data[ indice ].codigo+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('consecutivo_criterio')?>");
	            });
	            $("#<?php echo $this->campoSeguro('consecutivo_criterio')?>").removeAttr('disabled');
	            $("#<?php echo $this->campoSeguro('consecutivo_criterio')?>").width(450);
	            $("#<?php echo $this->campoSeguro('consecutivo_criterio')?>").select2();
		        }
	    }
		                    
	   });
	};


</script>