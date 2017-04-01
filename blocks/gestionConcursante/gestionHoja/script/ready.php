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
"lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],

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
    
    $("#datosBasicos").submit(function() {
        $resultado=$("#datosBasicos").validationEngine("validate");
        if ($resultado) {
            return true;
        }
        return false;
    });   
    
    $("#datosContacto").submit(function() {
        $resultado=$("#datosContacto").validationEngine("validate");
        if ($resultado) {
            return true;
        }
        return false;
    });        

    $("#datosFormacion").submit(function() {
        $resultado=$("#datosFormacion").validationEngine("validate");
        if ($resultado) {
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
<?php 
//}



?>



