<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */


//$this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "host" ) .$this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$directorioImg = $url."/blocks/".$esteBloque ["grupo"]."/".$esteBloque ["nombre"]."/images/";
$url .= "/index.php?";

//Variables cargar departamento
$cadenaCodificar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaCodificar .= "&procesarAjax=true";
$cadenaCodificar .= "&action=index.php";
$cadenaCodificar .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaCodificar .= "&bloqueGrupo=" . $esteBloque ["grupo"];


$cadenaCodificarPais = $cadenaCodificar . "&funcion=consultarDepartamentoAjax";
$cadenaCodificarPais .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadenaPais = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaCodificarPais, $enlace );
$urlFinalPais = $url.$cadenaPais;

//Variables cargar ciudad
$cadenaCodificarDepto = $cadenaCodificar . "&funcion=consultarCiudadAjax";
$cadenaCodificarDepto .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadenaDepto = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaCodificarDepto, $enlace );
$urlFinalDepto = $url . $cadenaDepto;

//Variables cargar IES
$cadenaCodificarIES = $cadenaCodificar . "&funcion=consultarIESAjax";
$cadenaCodificarIES .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadenaIES = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaCodificarIES, $enlace );
$urlFinalIES = $url . $cadenaIES;

//Variables cargar programa
$cadenaCodificarProg = $cadenaCodificar . "&funcion=consultarProgramaAjax";
$cadenaCodificarProg .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadenaProg = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaCodificarProg, $enlace );
$urlFinalProg = $url . $cadenaProg;

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
            if($("#<?php echo $this->campoSeguro('pais_residencia')?>").val()!=''){
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

// Controles Formacion
    $("#<?php echo $this->campoSeguro('pais_formacion')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('pais_formacion')?>").val()==112){
               consultarIES();
                  $("#<?php echo $this->campoSeguro('nombre_institucion')?>").val('');
                  $("#<?php echo $this->campoSeguro('nombre_institucion')?>").hide().slideDown("slow");
                  $("#<?php echo $this->campoSeguro('nombre_institucion')?>").addClass("validate[required]");
                  
                  $("#<?php echo $this->campoSeguro('nombre_programa')?>").val('');
                  $("#<?php echo $this->campoSeguro('nombre_programa')?>").hide().slideDown("slow");
                  $("#<?php echo $this->campoSeguro('nombre_programa')?>").addClass("validate[required]");
                
            }else{
                  $("#<?php echo $this->campoSeguro('codigo_institucion')?>").html('');
                  $("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion')?>");  
                  $("#<?php echo $this->campoSeguro('codigo_institucion')?>").select2();
                  $("#<?php echo $this->campoSeguro('nombre_institucion')?>").val('');
                  $("#<?php echo $this->campoSeguro('nombre_institucion')?>").hide().slideDown("slow");
                  $("#<?php echo $this->campoSeguro('nombre_institucion')?>").addClass("validate[required]");
                  
                  $("#<?php echo $this->campoSeguro('consecutivo_programa')?>").html('');
                  $("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('consecutivo_programa')?>");  
                  $("#<?php echo $this->campoSeguro('consecutivo_programa')?>").select2();
                  $("#<?php echo $this->campoSeguro('nombre_programa')?>").val('');
                  $("#<?php echo $this->campoSeguro('nombre_programa')?>").hide().slideDown("slow");
                  $("#<?php echo $this->campoSeguro('nombre_programa')?>").addClass("validate[required]");
                  
                 }
          });

    $("#<?php echo $this->campoSeguro('codigo_institucion')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('codigo_institucion')?>").val()>0){
                    $("#<?php echo $this->campoSeguro('nombre_institucion')?>").hide();
                    $("#<?php echo $this->campoSeguro('nombre_institucion')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_institucion')?>").removeClass("validate[required]");
                    consultarProgramaAcad();
            }else if($("#<?php echo $this->campoSeguro('codigo_institucion')?>").val()==0){
                    $("#<?php echo $this->campoSeguro('nombre_institucion')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_institucion')?>").hide().slideDown("slow");
                    $("#<?php echo $this->campoSeguro('nombre_institucion')?>").addClass("validate[required]");

                    $("#<?php echo $this->campoSeguro('consecutivo_programa')?>").html('');
                    $("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('consecutivo_programa')?>");  
                    $("#<?php echo $this->campoSeguro('consecutivo_programa')?>").select2();
                    $("#<?php echo $this->campoSeguro('nombre_programa')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_programa')?>").hide().slideDown("slow");
                    $("#<?php echo $this->campoSeguro('nombre_programa')?>").addClass("validate[required]");
            }
          });

    $("#<?php echo $this->campoSeguro('consecutivo_programa')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('consecutivo_programa')?>").val()>0){
                    $("#<?php echo $this->campoSeguro('nombre_programa')?>").hide();
                    $("#<?php echo $this->campoSeguro('nombre_programa')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_programa')?>").removeClass("validate[required]");
            }else if($("#<?php echo $this->campoSeguro('consecutivo_programa')?>").val()==0){
                    $("#<?php echo $this->campoSeguro('nombre_programa')?>").hide().slideDown("slow");
                    $("#<?php echo $this->campoSeguro('nombre_programa')?>").addClass("validate[required]");
                 }
          });  
          
    $("#<?php echo $this->campoSeguro('graduado')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('graduado')?>").val()=='N'){
                 $("#<?php echo $this->campoSeguro('fecha_grado')?>").val('');
                 $("#<?php echo $this->campoSeguro('fecha_grado')?>").removeClass("validate[required]");
                 $("#<?php echo $this->campoSeguro('fecha_grado')?>").attr('disabled','');
                 
            }else{
                  $("#<?php echo $this->campoSeguro('fecha_grado')?>").val('');
                  $("#<?php echo $this->campoSeguro('fecha_grado')?>").addClass("validate[required]");
                  $("#<?php echo $this->campoSeguro('fecha_grado')?>").removeAttr('disabled');
                 }
          });       
// Controles  experiencia Profesional
    $("#<?php echo $this->campoSeguro('cargo_actual')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('cargo_actual')?>").val()=='S'){
                 $("#<?php echo $this->campoSeguro('fecha_fin')?>").val('');
                 $("#<?php echo $this->campoSeguro('fecha_fin')?>").removeClass("validate[required]");
                 $("#<?php echo $this->campoSeguro('fecha_fin')?>").attr('disabled','');
                 $("#<?php echo $this->campoSeguro('fecha_fin')?>").hide();
                 
            }else{
                  $("#<?php echo $this->campoSeguro('fecha_inicio')?>").val('');
                  $("#<?php echo $this->campoSeguro('fecha_fin')?>").val('');
                  $("#<?php echo $this->campoSeguro('fecha_fin')?>").addClass("validate[required]");
                  $("#<?php echo $this->campoSeguro('fecha_fin')?>").removeAttr('disabled');
                  $("#<?php echo $this->campoSeguro('fecha_fin')?>").hide().slideDown("slow");
                 }
          });     
          
    if($("#<?php echo $this->campoSeguro('cargo_actual')?>").val()=='S'){
                $("#<?php echo $this->campoSeguro('fecha_fin')?>").val('');
                $("#<?php echo $this->campoSeguro('fecha_fin')?>").removeClass("validate[required]");
                $("#<?php echo $this->campoSeguro('fecha_fin')?>").attr('disabled','');
                $("#<?php echo $this->campoSeguro('fecha_fin')?>").hide();

           }     
// Controles experiencia Docente

    $("#<?php echo $this->campoSeguro('pais_docencia')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('pais_docencia')?>").val()==112){
               consultarIESdocencia();
                  $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").val('');
                  $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").hide().slideDown("slow");
                  $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").removeClass("validate[required]");
            }else{
                  $("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>").html('');
                  $("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>");  
                  $("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>").select2();
                  $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").val('');
                  $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").hide().slideDown("slow");
                  $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").addClass("validate[required]");
                 }
          });


    $("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>").val()>0){
                    $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").hide();
                    $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").removeClass("validate[required]");
            }else if($("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>").val()==0){
                    $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").hide().slideDown("slow");
                    $("#<?php echo $this->campoSeguro('nombre_institucion_docencia')?>").addClass("validate[required]");
            }
          });  
          
    $("#<?php echo $this->campoSeguro('codigo_vinculacion')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('codigo_vinculacion')?>").val()>0){
                    $("#<?php echo $this->campoSeguro('nombre_vinculacion')?>").hide();
                    $("#<?php echo $this->campoSeguro('nombre_vinculacion')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_vinculacion')?>").removeClass("validate[required]");
                    
            }else if($("#<?php echo $this->campoSeguro('codigo_vinculacion')?>").val()==0){
                    $("#<?php echo $this->campoSeguro('nombre_vinculacion')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_vinculacion')?>").hide().slideDown("slow");
                    $("#<?php echo $this->campoSeguro('nombre_vinculacion')?>").addClass("validate[required]");
            }
          });             
   $("#<?php echo $this->campoSeguro('docencia_actual')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('docencia_actual')?>").val()=='S'){
                 $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").val('');
                 $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").removeClass("validate[required]");
                 $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").attr('disabled','');
                 $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").hide();
                 
            }else{
                  $("#<?php echo $this->campoSeguro('fecha_inicio_docencia')?>").val('');
                  $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").val('');
                  $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").addClass("validate[required]");
                  $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").removeAttr('disabled');
                  $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").hide().slideDown("slow");
                 }
          });          
    if($("#<?php echo $this->campoSeguro('docencia_actual')?>").val()=='S'){
                 $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").val('');
                 $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").removeClass("validate[required]");
                 $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").attr('disabled','');
                 $("#<?php echo $this->campoSeguro('fecha_fin_docencia')?>").hide();
                 
            }      

// Controles Actividad academica
          
    $("#<?php echo $this->campoSeguro('pais_actividad')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('pais_actividad')?>").val()==112){
               consultarIESactividad();
                  $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").val('');
                  $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").hide().slideDown("slow");
                  $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").removeClass("validate[required]");
            }else{
                  $("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>").html('');
                  $("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>");  
                  $("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>").select2();
                  $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").val('');
                  $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").hide().slideDown("slow");
                  $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").addClass("validate[required]");
                 }
          });


    $("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>").val()>0){
                    $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").hide();
                    $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").removeClass("validate[required]");
            }else if($("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>").val()==0){
                    $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").hide().slideDown("slow");
                    $("#<?php echo $this->campoSeguro('nombre_institucion_actividad')?>").addClass("validate[required]");
            }
          });  
          
       /*   
    $("#<?php echo $this->campoSeguro('codigo_tipo_actividad')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('codigo_tipo_actividad')?>").val()>0){
                    $("#<?php echo $this->campoSeguro('nombre_tipo_actividad')?>").hide();
                    $("#<?php echo $this->campoSeguro('nombre_tipo_actividad')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_tipo_actividad')?>").removeClass("validate[required]");
                    
            }else if($("#<?php echo $this->campoSeguro('codigo_tipo_actividad')?>").val()==0){
                    $("#<?php echo $this->campoSeguro('nombre_tipo_actividad')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_tipo_actividad')?>").hide().slideDown("slow");
                    $("#<?php echo $this->campoSeguro('nombre_tipo_actividad')?>").addClass("validate[required]");
            }
          });     */        
    
// Controles experiencia Investigacion

    $("#<?php echo $this->campoSeguro('pais_investigacion')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('pais_investigacion')?>").val()==112){
               consultarIESinvestigacion();
                  $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").val('');
                  $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").hide().slideDown("slow");
                  $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").removeClass("validate[required]");
            }else{
                  $("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>").html('');
                  $("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>");  
                  $("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>").select2();
                  $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").val('');
                  $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").hide().slideDown("slow");
                  $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").addClass("validate[required]");
                 }
          });


    $("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>").val()>0){
                    $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").hide();
                    $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").removeClass("validate[required]");
            }else if($("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>").val()==0){
                    $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").hide().slideDown("slow");
                    $("#<?php echo $this->campoSeguro('nombre_institucion_investigacion')?>").addClass("validate[required]");
            }
          });     
          
   $("#<?php echo $this->campoSeguro('investigacion_actual')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('investigacion_actual')?>").val()=='S'){
                 $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").val('');
                 $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").removeClass("validate[required]");
                 $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").attr('disabled','');
                 $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").hide();
                 
            }else{
                  $("#<?php echo $this->campoSeguro('fecha_inicio_investigacion')?>").val('');
                  $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").val('');
                  $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").addClass("validate[required]");
                  $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").removeAttr('disabled');
                  $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").hide().slideDown("slow");
                 }
          }); 
          
    if($("#<?php echo $this->campoSeguro('investigacion_actual')?>").val()=='S'){
                 $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").val('');
                 $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").removeClass("validate[required]");
                 $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").attr('disabled','');
                 $("#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>").hide();
            }               
          
// Controles experiencia Produccion          
          
    $("#<?php echo $this->campoSeguro('pais_produccion')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('pais_produccion')?>").val()!=''){
                consultarDepartamentoProd();
            }else{
                  $("#<?php echo $this->campoSeguro('departamento_produccion')?>").attr('disabled','');
                  $("#<?php echo $this->campoSeguro('cuidad_produccion')?>").attr('disabled', '');
                 }
          });  

    $("#<?php echo $this->campoSeguro('departamento_produccion')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('departamento_produccion')?>").val()!=''){
                consultarCiudadProd();
            }else{
                  $("#<?php echo $this->campoSeguro('cuidad_produccion')?>").attr('disabled','');
                 }
          });          

    $("#<?php echo $this->campoSeguro('codigo_tipo_produccion')?>").change(function(){
                
            if($("#<?php echo $this->campoSeguro('codigo_tipo_produccion')?>").val()>0){
                    $("#<?php echo $this->campoSeguro('nombre_tipo_produccion')?>").hide();
                    $("#<?php echo $this->campoSeguro('nombre_tipo_produccion')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_tipo_produccion')?>").removeClass("validate[required]");
            }else if($("#<?php echo $this->campoSeguro('codigo_tipo_produccion')?>").val()==0){
                    $("#<?php echo $this->campoSeguro('nombre_tipo_produccion')?>").attr('enabled');
                    $("#<?php echo $this->campoSeguro('nombre_tipo_produccion')?>").val('');
                    $("#<?php echo $this->campoSeguro('nombre_tipo_produccion')?>").hide().slideDown("slow");
                    $("#<?php echo $this->campoSeguro('nombre_tipo_produccion')?>").addClass("validate[required]");
            }
          });   
          
//controles idioma        
if($("#<?php echo $this->campoSeguro('certificacion')?>").val()==''){
        $("#<?php echo $this->campoSeguro('certificacion')?>").val('');
        $("#<?php echo $this->campoSeguro('certificacion')?>").removeClass("validate[required]");
        $("#<?php echo $this->campoSeguro('certificacion')?>").attr('disabled','');
        $("#<?php echo $this->campoSeguro('certificacion')?>").hide();

        $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").val('');
        $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").removeClass("validate[required]");
        $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").attr('disabled','');
        $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").hide();
        
        $("#<?php echo $this->campoSeguro('soporteIdioma')?>").val('');
        $("#<?php echo $this->campoSeguro('soporteIdioma')?>").removeClass("validate[required]");
        $("#<?php echo $this->campoSeguro('soporteIdioma')?>").attr('disabled','');
        $("#<?php echo $this->campoSeguro('soporteIdioma')?>").hide();        
    }
   
   $("#<?php echo $this->campoSeguro('certificado')?>").change(function(){
            if($("#<?php echo $this->campoSeguro('certificado')?>").val()=='N'){
                 $("#<?php echo $this->campoSeguro('certificacion')?>").val('');
                 $("#<?php echo $this->campoSeguro('certificacion')?>").removeClass("validate[required]");
                 $("#<?php echo $this->campoSeguro('certificacion')?>").attr('disabled','');
                 $("#<?php echo $this->campoSeguro('certificacion')?>").hide();
                 
                 $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").val('');
                 $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").removeClass("validate[required]");
                 $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").attr('disabled','');
                 $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").hide();
                 
                 $("#<?php echo $this->campoSeguro('soporteIdioma')?>").val('');
                 $("#<?php echo $this->campoSeguro('soporteIdioma')?>").removeClass("validate[required]");
                 $("#<?php echo $this->campoSeguro('soporteIdioma')?>").attr('disabled','');
                 $("#<?php echo $this->campoSeguro('soporteIdioma')?>").hide();
                 
                 
            }else{
                  $("#<?php echo $this->campoSeguro('certificacion')?>").val('');
                  $("#<?php echo $this->campoSeguro('certificacion')?>").addClass("validate[required]");
                  $("#<?php echo $this->campoSeguro('certificacion')?>").removeAttr('disabled');
                  $("#<?php echo $this->campoSeguro('certificacion')?>").hide().slideDown("slow");
                  
                  $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").val('');
                  $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").addClass("validate[required]");
                  $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").removeAttr('disabled');
                  $("#<?php echo $this->campoSeguro('institucion_certificacion')?>").hide().slideDown("slow");
                  
                  $("#<?php echo $this->campoSeguro('soporteIdioma')?>").val('');
                  $("#<?php echo $this->campoSeguro('soporteIdioma')?>").addClass("validate[required]");
                  $("#<?php echo $this->campoSeguro('soporteIdioma')?>").removeAttr('disabled');
                  $("#<?php echo $this->campoSeguro('soporteIdioma')?>").hide().slideDown("slow");
                 }
          });       
          
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

function consultarDepartamentoProd(elem, request, response){
            $.ajax({
	    url: "<?php echo $urlFinalPais?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('pais_produccion')?>").val()},
            success: function(data){ 
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('departamento_produccion')?>").html('');
                        $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('departamento_produccion')?>");
                        $.each(data , function(indice,valor){
                            $("<option value='"+data[ indice ].id_departamento+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('departamento_produccion')?>");
                        });
                        $("#<?php echo $this->campoSeguro('departamento_produccion')?>").removeAttr('disabled');
                        //$('#<?php echo $this->campoSeguro('departamento_produccion')?>').width(250);
                        $("#<?php echo $this->campoSeguro('departamento_produccion')?>").select2();
                        $("#<?php echo $this->campoSeguro('departamento_produccion')?>").removeClass("validate[required]");

                    $("#<?php echo $this->campoSeguro('ciudad_produccion')?>").html('');
                        $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudad_produccion')?>");
                        $("#<?php echo $this->campoSeguro('ciudad_produccion')?>").select2();
	            }
	    }
	   });
	};

function consultarCiudadProd(elem, request, response){
        $.ajax({
            url: "<?php echo $urlFinalDepto?>",
            dataType: "json",
            data: { valor:$("#<?php echo $this->campoSeguro('departamento_produccion')?>").val()},
            success: function(data){ 
                if(data[0]!=" "){
                    $("#<?php echo $this->campoSeguro('ciudad_produccion')?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudad_produccion')?>");
                    $.each(data , function(indice,valor){
                           $("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('ciudad_produccion')?>");
                        });
                    $("#<?php echo $this->campoSeguro('ciudad_produccion')?>").removeAttr('disabled');
                    //$('#<?php echo $this->campoSeguro('ciudad_produccion')?>').width(250);
                    $("#<?php echo $this->campoSeguro('ciudad_produccion')?>").select2();
                }
            }
        });
    };


function consultarIES(elem, request, response){
        $.ajax({
            url: "<?php echo $urlFinalIES?>",
            dataType: "json",
            data: { valor:$("#<?php echo $this->campoSeguro('pais_formacion')?>").val()},
            success: function(data){ 
                if(data[0]!=" "){
                    $("#<?php echo $this->campoSeguro('codigo_institucion')?>").html('');
                    $("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion')?>");
                    $.each(data , function(indice,valor){
                           $("<option value='"+data[ indice ].codigo_ies+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion')?>");
                        });
                    $("#<?php echo $this->campoSeguro('codigo_institucion')?>").removeAttr('disabled');
                    //$('#<?php echo $this->campoSeguro('ciudad_residencia')?>').width(250);
                    $("#<?php echo $this->campoSeguro('codigo_institucion')?>").select2();
                }
            }
        });
    }; 
function consultarIESdocencia(elem, request, response){
        $.ajax({
            url: "<?php echo $urlFinalIES?>",
            dataType: "json",
            data: { valor:$("#<?php echo $this->campoSeguro('pais_docencia')?>").val()},
            success: function(data){ 
                if(data[0]!=" "){
                    $("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>").html('');
                    $("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>");
                    $.each(data , function(indice,valor){
                           $("<option value='"+data[ indice ].codigo_ies+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>");
                        });
                    $("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>").removeAttr('disabled');
                    $("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>").select2();
                }
            }
        });
    }; 
function consultarIESactividad(elem, request, response){
        $.ajax({
            url: "<?php echo $urlFinalIES?>",
            dataType: "json",
            data: { valor:$("#<?php echo $this->campoSeguro('pais_actividad')?>").val()},
            success: function(data){ 
                if(data[0]!=" "){
                    $("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>").html('');
                    $("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>");
                    $.each(data , function(indice,valor){
                           $("<option value='"+data[ indice ].codigo_ies+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>");
                        });
                    $("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>").removeAttr('disabled');
                    $("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>").select2();
                }
            }
        });
    }; 
function consultarIESinvestigacion(elem, request, response){
        $.ajax({
            url: "<?php echo $urlFinalIES?>",
            dataType: "json",
            data: { valor:$("#<?php echo $this->campoSeguro('pais_investigacion')?>").val()},
            success: function(data){ 
                if(data[0]!=" "){
                    $("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>").html('');
                    $("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>");
                    $.each(data , function(indice,valor){
                           $("<option value='"+data[ indice ].codigo_ies+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>");
                        });
                    $("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>").removeAttr('disabled');
                    $("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>").select2();
                }
            }
        });
    };  
    
function consultarProgramaAcad(elem, request, response){
        $.ajax({
            url: "<?php echo $urlFinalProg?>",
            dataType: "json",
            data: { valor:$("#<?php echo $this->campoSeguro('codigo_institucion')?>").val()},
            success: function(data){ 
                if(data[0]!=" "){
                    $("#<?php echo $this->campoSeguro('consecutivo_programa')?>").html('');
                    //$("<option value='0'>OTRO </option>").appendTo("#<?php echo $this->campoSeguro('consecutivo_programa')?>");
                    $.each(data , function(indice,valor){
                           $("<option value='"+data[ indice ].consecutivo_programa+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('consecutivo_programa')?>");
                        });
                    $("#<?php echo $this->campoSeguro('consecutivo_programa')?>").removeAttr('disabled');
                    //$('#<?php echo $this->campoSeguro('consecutivo_programa')?>').width(250);
                    $("#<?php echo $this->campoSeguro('consecutivo_programa')?>").select2();
                }
            }
        });
    };    


</script>