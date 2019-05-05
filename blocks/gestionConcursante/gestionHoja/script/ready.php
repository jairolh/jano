<?php 
//Se coloca esta condición para evitar cargar algunos scripts en el formulario de confirmación de entrada de datos.
//if(!isset($_REQUEST["opcion"])||(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]!="confirmar")){
/*
?>
        $('#tablaProcesos').dataTable({bJQueryUI : true,
        "sPaginationType": "full_numbers"
        });
      */  
    ?>    
        
$('#tablaProcesos').DataTable({
"language": {
    "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
    "zeroRecords": "No se encontraron registros coincidentes",
    "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
    "infoEmpty": "Ninguna hay datos registrados",
    "infoFiltered": "(filtrado de un m&aacute;ximo de _MAX_)",
    "search": "Buscar:",
    "paginate": {
                "first":      "Primera",
                "last":       "&Uacute;ltima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
},
"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],

});


$('#tablaProfesional').DataTable({
"language": {
    "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
    "zeroRecords": "No se encontraron registros coincidentes",
    "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
    "infoEmpty": "Ninguna hay datos registrados",
    "infoFiltered": "(filtrado de un m&aacute;ximo de _MAX_)",
    "search": "Buscar:",
    "paginate": {
                "first":      "Primera",
                "last":       "&Uacute;ltima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
},
"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],

});

$('#tablaDocencia').DataTable({
"language": {
    "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
    "zeroRecords": "No se encontraron registros coincidentes",
    "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
    "infoEmpty": "Ninguna hay datos registrados",
    "infoFiltered": "(filtrado de un m&aacute;ximo de _MAX_)",
    "search": "Buscar:",
    "paginate": {
                "first":      "Primera",
                "last":       "&Uacute;ltima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
},
"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
});

$('#tablaActividad').DataTable({
"language": {
    "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
    "zeroRecords": "No se encontraron registros coincidentes",
    "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
    "infoEmpty": "Ninguna hay datos registrados",
    "infoFiltered": "(filtrado de un m&aacute;ximo de _MAX_)",
    "search": "Buscar:",
    "paginate": {
                "first":      "Primera",
                "last":       "&Uacute;ltima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
},
"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
});


$('#tablaInvestigacion').DataTable({
"language": {
    "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
    "zeroRecords": "No se encontraron registros coincidentes",
    "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
    "infoEmpty": "Ninguna hay datos registrados",
    "infoFiltered": "(filtrado de un m&aacute;ximo de _MAX_)",
    "search": "Buscar:",
    "paginate": {
                "first":      "Primera",
                "last":       "&Uacute;ltima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
},
"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
});

$('#tablaProducto').DataTable({
"language": {
    "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
    "zeroRecords": "No se encontraron registros coincidentes",
    "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
    "infoEmpty": "Ninguna hay datos registrados",
    "infoFiltered": "(filtrado de un m&aacute;ximo de _MAX_)",
    "search": "Buscar:",
    "paginate": {
                "first":      "Primera",
                "last":       "&Uacute;ltima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
},
"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
});

$('#tablaIdioma').DataTable({
"language": {
    "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
    "zeroRecords": "No se encontraron registros coincidentes",
    "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
    "infoEmpty": "Ninguna hay datos registrados",
    "infoFiltered": "(filtrado de un m&aacute;ximo de _MAX_)",
    "search": "Buscar:",
    "paginate": {
                "first":      "Primera",
                "last":       "&Uacute;ltima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
},
"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
});

// Asociar el widget de validación al formulario
$("#gestionHoja").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});

$(function() {
    $("#gestionHoja").submit(function() {
        $resultado=$("#gestionHoja").validationEngine("validate");
        if ($resultado) {
            return true;
        }
        return false;
    });

// Asociar el widget de validación al formulario
$("#datosBasicos").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});    
    $("#datosBasicos").submit(function() {
        $resultadoBas=$("#datosBasicos").validationEngine("validate");
        if ($resultadoBas) {
            return true;
        }
        return false;
    });   
    
// Asociar el widget de validación al formulario
$("#datosContacto").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});    
    $("#datosContacto").submit(function() {
        $resultadoCont=$("#datosContacto").validationEngine("validate");
        if ($resultadoCont) {
            return true;
        }
        return false;
    });        

// Asociar el widget de validación al formulario
$("#datosFormacion").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});    
    $("#datosFormacion").submit(function() {
        $resultadoForm=$("#datosFormacion").validationEngine("validate");
        if ($resultadoForm) {
            return true;
        }
        return false;
    });     
    
// Asociar el widget de validación al formulario
$("#datosProfesional").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});    
    $("#datosProfesional").submit(function() {
        $resultadoForm=$("#datosprofesional").validationEngine("validate");
        if ($resultadoForm) {
            return true;
        }
        return false;
    });     

// Asociar el widget de validación al formulario
$("#datosDocencia").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});    
    $("#datosDocencia").submit(function() {
        $resultadoForm=$("#datosDocencia").validationEngine("validate");
        if ($resultadoForm) {
            return true;
        }
        return false;
    });
    
// Asociar el widget de validación al formulario
$("#datosActividad").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});    
    $("#datosActividad").submit(function() {
        $resultadoForm=$("#datosActividad").validationEngine("validate");
        if ($resultadoForm) {
            return true;
        }
        return false;
    });     

// Asociar el widget de validación al formulario
$("#datosInvestigacion").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});    
    $("#datosInvestigacion").submit(function() {
        $resultadoForm=$("#datosInvestigacion").validationEngine("validate");
        if ($resultadoForm) {
            return true;
        }
        return false;
    });      
        
    
// Asociar el widget de validación al formulario
$("#datosProduccion").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});    
    $("#datosProduccion").submit(function() {
        $resultadoForm=$("#datosProduccion").validationEngine("validate");
        if ($resultadoForm) {
            return true;
        }
        return false;
    });      
    
    
// Asociar el widget de validación al formulario
$("#datosIdioma").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});    
    $("#datosIdioma").submit(function() {
        $resultadoForm=$("#datosIdioma").validationEngine("validate");
        if ($resultadoForm) {
            return true;
        }
        return false;
    });     

    
});
 
<?php /*?>
               $('#<?php echo $this->campoSeguro('fecha_final')?>').datepicker({
		dateFormat: 'yy-mm-dd',
		maxDate: 0,
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		    onSelect: function(dateText, inst) {
			var lockDate = new Date($('#<?php echo $this->campoSeguro('fecha_final')?>').datepicker('getDate'));
			$('input#<?php echo $this->campoSeguro('fecha_inicio')?>').datepicker('option', 'maxDate', lockDate);
			 },
			 onClose: function() { 
		 	    if ($('input#<?php echo $this->campoSeguro('fecha_final')?>').val()!='')
                    {
                        $('#<?php echo $this->campoSeguro('fecha_inicio')?>').attr("class", "cuadroTexto ui-widget ui-widget-content ui-corner-all   validate[required]");
                }else {
                        $('#<?php echo $this->campoSeguro('fecha_inicio')?>').attr("class", "cuadroTexto ui-widget ui-widget-content ui-corner-all ");
                    }
			  }
			
	   });
 <?php */?>        
        
        
<?php /*?>$('#<?php echo $this->campoSeguro('fechaFin')?>').datetimepicker({<?php */?>
$('#<?php echo $this->campoSeguro('fecha_nacimiento')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',

       <?php /*?> maxDate: 0,<?php */?>
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-80:-18'

   });
$('#<?php echo $this->campoSeguro('fecha_identificacion')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
        maxDate: 0,
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-90:+0'

   });
   
$('#<?php echo $this->campoSeguro('fecha_grado')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
       <?php /*?> maxDate: 0,<?php */?>
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-60:+0'
   });

$('#<?php echo $this->campoSeguro('fecha_inicio')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
       <?php /*?> maxDate: 0,<?php */?>
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-60:+0',
        <?php /*?>permite asirnara a otro campo como fecha minima */?>
        onSelect: function(dateText, inst) {
        var lockDate = new Date($('#<?php echo $this->campoSeguro('fecha_inicio')?>').datepicker('getDate'));
        //lockDate.setDate(lockDate.getDate() + 1);
        $('input#<?php echo $this->campoSeguro('fecha_fin')?>').datepicker('option', 'minDate', lockDate);} 
   });

$('#<?php echo $this->campoSeguro('fecha_fin')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
        maxDate: 0,
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-50:+0'
   });   

$('#<?php echo $this->campoSeguro('fecha_inicio_docencia')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
       <?php /*?> maxDate: 0,<?php */?>
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-50:+0',
        <?php /*?>permite asirnara a otro campo como fecha minima */?>
        onSelect: function(dateText, inst) {
        var lockDate = new Date($('#<?php echo $this->campoSeguro('fecha_inicio_docencia')?>').datepicker('getDate'));
        //lockDate.setDate(lockDate.getDate() + 1);
        $('input#<?php echo $this->campoSeguro('fecha_fin_docencia')?>').datepicker('option', 'minDate', lockDate);} 
   });

$('#<?php echo $this->campoSeguro('fecha_fin_docencia')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
        maxDate: 0,
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-50:+0'
   });    

$('#<?php echo $this->campoSeguro('fecha_inicio_actividad')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
       <?php /*?> maxDate: 0,<?php */?>
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-50:+0',
        <?php /*?>permite asirnara a otro campo como fecha minima */?>
        onSelect: function(dateText, inst) {
        var lockDate = new Date($('#<?php echo $this->campoSeguro('fecha_inicio_actividad')?>').datepicker('getDate'));
        //lockDate.setDate(lockDate.getDate() + 1);
        $('input#<?php echo $this->campoSeguro('fecha_fin_actividad')?>').datepicker('option', 'minDate', lockDate);} 
   });

$('#<?php echo $this->campoSeguro('fecha_fin_actividad')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
       <?php /*?> maxDate: 0,<?php */?>
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-50:+0'
   });      
   
$('#<?php echo $this->campoSeguro('fecha_inicio_investigacion')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
       <?php /*?> maxDate: 0,<?php */?>
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-50:+0',
        <?php /*?>permite asirnara a otro campo como fecha minima */?>
        onSelect: function(dateText, inst) {
        var lockDate = new Date($('#<?php echo $this->campoSeguro('fecha_inicio_investigacion')?>').datepicker('getDate'));
        //lockDate.setDate(lockDate.getDate() + 1);
        $('input#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>').datepicker('option', 'minDate', lockDate);} 
   });

$('#<?php echo $this->campoSeguro('fecha_fin_investigacion')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
       <?php /*?> maxDate: 0,<?php */?>
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-50:+0'
   });     
   
$('#<?php echo $this->campoSeguro('fecha_produccion')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
       <?php /*?> maxDate: 0,<?php */?>
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '-50:+0'
   });   
   
$(function() {
        $(document).tooltip();
});

// Asociar el widget tabs a la división cuyo id es tabs
$(function() {
        $("#tabs").tabs();
});

$(function() {
    $("button").button().click(function(event) {
            event.preventDefault();
    });
});
        
$('#<?php echo $this->campoSeguro('tipo_identificacion')?>').width(210);
$("#<?php echo $this->campoSeguro('tipo_identificacion')?>").select2(); 
$('#<?php echo $this->campoSeguro('pais')?>').width(210);
$("#<?php echo $this->campoSeguro('pais')?>").select2(); 
$('#<?php echo $this->campoSeguro('departamento')?>').width(210);
$("#<?php echo $this->campoSeguro('departamento')?>").select2(); 
$('#<?php echo $this->campoSeguro('ciudad')?>').width(210);
$("#<?php echo $this->campoSeguro('ciudad')?>").select2(); 
$('#<?php echo $this->campoSeguro('sexo')?>').width(210);
$("#<?php echo $this->campoSeguro('sexo')?>").select2(); 
$('#<?php echo $this->campoSeguro('lugar_identificacion')?>').width(210);
$("#<?php echo $this->campoSeguro('lugar_identificacion')?>").select2(); 
$('#<?php echo $this->campoSeguro('codigo_idioma_nativo')?>').width(210);
$("#<?php echo $this->campoSeguro('codigo_idioma_nativo')?>").select2();

$('#<?php echo $this->campoSeguro('pais_residencia')?>').width(210);
$("#<?php echo $this->campoSeguro('pais_residencia')?>").select2(); 
$('#<?php echo $this->campoSeguro('departamento_residencia')?>').width(210);
$("#<?php echo $this->campoSeguro('departamento_residencia')?>").select2(); 
$('#<?php echo $this->campoSeguro('ciudad_residencia')?>').width(210);
$("#<?php echo $this->campoSeguro('ciudad_residencia')?>").select2(); 

$('#<?php echo $this->campoSeguro('pais_formacion')?>').width(210);
$("#<?php echo $this->campoSeguro('pais_formacion')?>").select2(); 
$('#<?php echo $this->campoSeguro('modalidad')?>').width(210);
$("#<?php echo $this->campoSeguro('modalidad')?>").select2(); 
$('#<?php echo $this->campoSeguro('nivel_formacion')?>').width(210);
$("#<?php echo $this->campoSeguro('nivel_formacion')?>").select2(); 
$('#<?php echo $this->campoSeguro('codigo_institucion')?>').width(450);
$("#<?php echo $this->campoSeguro('codigo_institucion')?>").select2(); 
$('#<?php echo $this->campoSeguro('consecutivo_programa')?>').width(450);
$("#<?php echo $this->campoSeguro('consecutivo_programa')?>").select2(); 
$('#<?php echo $this->campoSeguro('graduado')?>').width(450);
$("#<?php echo $this->campoSeguro('graduado')?>").select2(); 
$('#<?php echo $this->campoSeguro('cursos_temporalidad')?>').width(210);
$("#<?php echo $this->campoSeguro('cursos_temporalidad')?>").select2(); 


$('#<?php echo $this->campoSeguro('pais_experiencia')?>').width(210);
$("#<?php echo $this->campoSeguro('pais_experiencia')?>").select2(); 
$('#<?php echo $this->campoSeguro('cargo_actual')?>').width(210);
$("#<?php echo $this->campoSeguro('cargo_actual')?>").select2(); 
$('#<?php echo $this->campoSeguro('nivel_institucion')?>').width(210);
$("#<?php echo $this->campoSeguro('nivel_institucion')?>").select2(); 


$('#<?php echo $this->campoSeguro('pais_docencia')?>').width(210);
$("#<?php echo $this->campoSeguro('pais_docencia')?>").select2(); 
$('#<?php echo $this->campoSeguro('docencia_actual')?>').width(210);
$("#<?php echo $this->campoSeguro('docencia_actual')?>").select2(); 
$('#<?php echo $this->campoSeguro('nivel_institucion_docencia')?>').width(210);
$("#<?php echo $this->campoSeguro('nivel_institucion_docencia')?>").select2(); 
$('#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>').width(450);
$("#<?php echo $this->campoSeguro('codigo_institucion_docencia')?>").select2(); 
$('#<?php echo $this->campoSeguro('codigo_nivel_docencia')?>').width(210);
$("#<?php echo $this->campoSeguro('codigo_nivel_docencia')?>").select2(); 
$('#<?php echo $this->campoSeguro('codigo_vinculacion')?>').width(210);
$("#<?php echo $this->campoSeguro('codigo_vinculacion')?>").select2(); 

$('#<?php echo $this->campoSeguro('pais_actividad')?>').width(210);
$("#<?php echo $this->campoSeguro('pais_actividad')?>").select2(); 
$('#<?php echo $this->campoSeguro('nivel_institucion_actividad')?>').width(210);
$("#<?php echo $this->campoSeguro('nivel_institucion_actividad')?>").select2(); 
$('#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>').width(450);
$("#<?php echo $this->campoSeguro('codigo_institucion_actividad')?>").select2(); 
$('#<?php echo $this->campoSeguro('codigo_tipo_actividad')?>').width(450);
$("#<?php echo $this->campoSeguro('codigo_tipo_actividad')?>").select2(); 
$('#<?php echo $this->campoSeguro('actividad_actual')?>').width(210);
$("#<?php echo $this->campoSeguro('actividad_actual')?>").select2(); 


$('#<?php echo $this->campoSeguro('pais_investigacion')?>').width(210);
$("#<?php echo $this->campoSeguro('pais_investigacion')?>").select2(); 
$('#<?php echo $this->campoSeguro('investigacion_actual')?>').width(210);
$("#<?php echo $this->campoSeguro('investigacion_actual')?>").select2(); 
$('#<?php echo $this->campoSeguro('nivel_institucion_investigacion')?>').width(210);
$("#<?php echo $this->campoSeguro('nivel_institucion_investigacion')?>").select2(); 
$('#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>').width(450);
$("#<?php echo $this->campoSeguro('codigo_institucion_investigacion')?>").select2(); 

$('#<?php echo $this->campoSeguro('pais_produccion')?>').width(210);
$("#<?php echo $this->campoSeguro('pais_produccion')?>").select2(); 
$('#<?php echo $this->campoSeguro('departamento_produccion')?>').width(210);
$("#<?php echo $this->campoSeguro('departamento_produccion')?>").select2(); 
$('#<?php echo $this->campoSeguro('ciudad_produccion')?>').width(210);
$("#<?php echo $this->campoSeguro('ciudad_produccion')?>").select2(); 
$('#<?php echo $this->campoSeguro('codigo_tipo_produccion')?>').width(450);
$("#<?php echo $this->campoSeguro('codigo_tipo_produccion')?>").select2(); 


$('#<?php echo $this->campoSeguro('codigo_idioma')?>').width(210);
$("#<?php echo $this->campoSeguro('codigo_idioma')?>").select2(); 
$('#<?php echo $this->campoSeguro('nivel_lee')?>').width(210);
$("#<?php echo $this->campoSeguro('nivel_lee')?>").select2(); 
$('#<?php echo $this->campoSeguro('nivel_escribe')?>').width(210);
$("#<?php echo $this->campoSeguro('nivel_escribe')?>").select2(); 
$('#<?php echo $this->campoSeguro('nivel_habla')?>').width(210);
$("#<?php echo $this->campoSeguro('nivel_habla')?>").select2(); 
$('#<?php echo $this->campoSeguro('certificado')?>').width(210);
$("#<?php echo $this->campoSeguro('certificado')?>").select2(); 
$('#<?php echo $this->campoSeguro('idioma_concurso')?>').width(210);
$("#<?php echo $this->campoSeguro('idioma_concurso')?>").select2(); 


<?php 
//}



?>



