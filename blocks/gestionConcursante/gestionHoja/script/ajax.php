<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */


//$this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "host" ) .$this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";


//Variables cargar departamento
$cadenaCodificar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaCodificar .= "&procesarAjax=true";
$cadenaCodificar .= "&action=index.php";
$cadenaCodificar .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaCodificar .= "&bloqueGrupo=" . $esteBloque ["grupo"];


$cadenaCodificarPais .= $cadenaCodificar . "&funcion=consultarDepartamentoAjax";
$cadenaCodificarPais .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadenaPais = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaCodificarPais, $enlace );
$urlFinalPais = $url.$cadenaPais;

//Variables cargar ciudad
$cadenaCodificarDepto .= $cadenaCodificar . "&funcion=consultarCiudadAjax";
$cadenaCodificarDepto .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadenaDepto = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaCodificarDepto, $enlace );
$urlFinalDepto = $url . $cadenaDepto;

?>

<script type='text/javascript'>

function soporte() {
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
          
    $("#<?php echo $this->campoSeguro('pais_residencia')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('paisResidencia')?>").val()!=''){
                consultarDepartamentoRes();
            }else{
                  $("#<?php echo $this->campoSeguro('departamento_residencia')?>").attr('disabled','');
                  $("#<?php echo $this->campoSeguro('cuidad_residencia')?>").attr('disabled', '');
                 }
          });  

    $("#<?php echo $this->campoSeguro('departamento_residencia')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('departamento_residencia')?>").val()!=''){
                consultarCiudadRes();
            }else{
                  $("#<?php echo $this->campoSeguro('cuidad_residencia')?>").attr('disabled','');
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




function consultarDepartamentoRes(elem, request, response){
            $.ajax({
	    url: "<?php echo $urlFinalPais?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('pais_residencia')?>").val()},
            success: function(data){ 
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('departamento_residencia')?>").html('');
                        $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('departamento_residencia')?>");
                        $.each(data , function(indice,valor){
                            $("<option value='"+data[ indice ].id_departamento+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('departamento_residencia')?>");
                        });
                        $("#<?php echo $this->campoSeguro('departamento_residencia')?>").removeAttr('disabled');
                        //$('#<?php echo $this->campoSeguro('departamento_residencia')?>').width(250);
                        $("#<?php echo $this->campoSeguro('departamento_residencia')?>").select2();
                        $("#<?php echo $this->campoSeguro('departamento_residencia')?>").removeClass("validate[required]");

                    $("#<?php echo $this->campoSeguro('ciudad_residencia')?>").html('');
                        $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudad_residencia')?>");
                        $("#<?php echo $this->campoSeguro('ciudad_residencia')?>").select2();
	            }
	    }
	   });
	};

function consultarCiudadRes(elem, request, response){
        $.ajax({
            url: "<?php echo $urlFinalDepto?>",
            dataType: "json",
            data: { valor:$("#<?php echo $this->campoSeguro('departamento_residencia')?>").val()},
            success: function(data){ 
                if(data[0]!=" "){
                    $("#<?php echo $this->campoSeguro('ciudad_residencia')?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudad_residencia')?>");
                    $.each(data , function(indice,valor){
                           $("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('ciudad_residencia')?>");
                        });
                    $("#<?php echo $this->campoSeguro('ciudad_residencia')?>").removeAttr('disabled');
                    //$('#<?php echo $this->campoSeguro('ciudad_residencia')?>').width(250);
                    $("#<?php echo $this->campoSeguro('ciudad_residencia')?>").select2();
                }
            }
        });
    };

</script>