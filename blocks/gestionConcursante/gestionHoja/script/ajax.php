<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */


//$this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "host" ) .$this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
echo $this->rutaSoporte;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";


//Variables cargar departamento
$cadenaCodificarPais = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaCodificarPais .= "&procesarAjax=true";
$cadenaCodificarPais .= "&action=index.php";
$cadenaCodificarPais .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaCodificarPais .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaCodificarPais .= $cadenaCodificarPais . "&funcion=consultarDepartamentoAjax";
$cadenaCodificarPais .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadenaPais = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaCodificarPais, $enlace );
$urlFinalPais = $url.$cadenaPais;

//Variables cargar ciudad
$cadenaCodificarDepto = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaCodificarDepto .= "&procesarAjax=true";
$cadenaCodificarDepto .= "&action=index.php";
$cadenaCodificarDepto .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaCodificarDepto .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaCodificarDepto .= $cadenaCodificarDepto . "&funcion=consultarCiudadAjax";
$cadenaCodificarDepto .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadenaDepto = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaCodificarDepto, $enlace );
$urlFinalDepto = $url . $cadenaDepto;

?>

<script type='text/javascript'>



function soporte() {
alert($("#<?php echo $this->campoSeguro('rutasoporte')?>").val());
var miPopup
  miPopup = window.open('about:blank','soporte','width=600,height=850,menubar=no') 
  miPopup.location = $("#<?php echo $this->campoSeguro('rutasoporte')?>").val();
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

        

$(function () {
          
    $("#<?php echo $this->campoSeguro('pais')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('pais')?>").val()!=''){
                consultarDepartamento();
            }else{
                  $("#<?php echo $this->campoSeguro('departamento')?>").attr('disabled','');
                  $("#<?php echo $this->campoSeguro('cuidad')?>").attr('disabled', '');
                 }
          });  

    $("#<?php echo $this->campoSeguro('departamento')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('departamento')?>").val()!=''){
                consultarCiudad();
            }else{
                  $("#<?php echo $this->campoSeguro('cuidad')?>").attr('disabled','');
                 }
          });          
});

function consultarDepartamento(elem, request, response){
            $.ajax({
	    url: "<?php echo $urlFinalPais?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('pais')?>").val()},
            success: function(data){ 
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('departamento')?>").html('');
                        $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('departamento')?>");
                        $.each(data , function(indice,valor){
                            $("<option value='"+data[ indice ].id_departamento+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('departamento')?>");
                        });
                        $("#<?php echo $this->campoSeguro('departamento')?>").removeAttr('disabled');
                        //$('#<?php echo $this->campoSeguro('departamento')?>').width(250);
                        $("#<?php echo $this->campoSeguro('departamento')?>").select2();
                        $("#<?php echo $this->campoSeguro('departamento')?>").removeClass("validate[required]");

                    $("#<?php echo $this->campoSeguro('ciudad')?>").html('');
                        $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudad')?>");
                        $("#<?php echo $this->campoSeguro('ciudad')?>").select2();
	            }
	    }
	   });
	};

function consultarCiudad(elem, request, response){
        $.ajax({
            url: "<?php echo $urlFinalDepto?>",
            dataType: "json",
            data: { valor:$("#<?php echo $this->campoSeguro('departamento')?>").val()},
            success: function(data){ 
                if(data[0]!=" "){
                    $("#<?php echo $this->campoSeguro('ciudad')?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudad')?>");
                    $.each(data , function(indice,valor){
                           $("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('ciudad')?>");
                        });
                    $("#<?php echo $this->campoSeguro('ciudad')?>").removeAttr('disabled');
                    //$('#<?php echo $this->campoSeguro('ciudad')?>').width(250);
                    $("#<?php echo $this->campoSeguro('ciudad')?>").select2();
                }
            }
        });
    };



</script>