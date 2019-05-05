<?php
//Se coloca esta condición para evitar cargar algunos scripts en el formulario de confirmación de entrada de datos.
//if(!isset($_REQUEST["opcion"])||(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]!="confirmar")){

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

   $('#tablaConsultaCriterio').DataTable({
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

   $('#tablaConsultaJurados').DataTable({
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
$("#gestionJurado").validationEngine({
    promptPosition : "centerRight",
    scroll: false
});

$(function() {
    $("#gestionJurado").submit(function() {
        $resultado=$("#gestionJurado").validationEngine("validate");
        if ($resultado) {
            return true;
        }
        return false;
    });
});

// Asociar el widget de validación al formulario
$("#datosCriterio").validationEngine({
    promptPosition : "centerRight",
    scroll: false
});

// Asociar el widget de validación al formulario
$("#datosJurado").validationEngine({
    promptPosition : "centerRight",
    scroll: false
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
$('#<?php echo $this->campoSeguro('fechaFin')?>').datepicker({
		<?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
                dateFormat: 'yy-mm-dd',
		minDate: 0,
               <?php /*?> maxDate: 0,<?php */?>
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],


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
$('#<?php echo $this->campoSeguro('subsistema')?>').width(210);
$("#<?php echo $this->campoSeguro('subsistema')?>").select2();
$('#<?php echo $this->campoSeguro('perfil')?>').width(210);
$("#<?php echo $this->campoSeguro('perfil')?>").select2();
$('#<?php echo $this->campoSeguro('estadoFactor')?>').width(210);
$("#<?php echo $this->campoSeguro('estadoFactor')?>").select2();
$('#<?php echo $this->campoSeguro('factor')?>').width(210);
$("#<?php echo $this->campoSeguro('factor')?>").select2();
$('#<?php echo $this->campoSeguro('criterio')?>').width(210);
$("#<?php echo $this->campoSeguro('criterio')?>").select2();

$('#<?php echo $this->campoSeguro('seleccionFactor')?>').width(210);
$("#<?php echo $this->campoSeguro('seleccionFactor')?>").select2();
$('#<?php echo $this->campoSeguro('estadoCriterio')?>').width(210);
$("#<?php echo $this->campoSeguro('estadoCriterio')?>").select2();

$('#<?php echo $this->campoSeguro('seleccionNivel')?>').width(210);
$("#<?php echo $this->campoSeguro('seleccionNivel')?>").select2();
$('#<?php echo $this->campoSeguro('nivel')?>').width(210);
$("#<?php echo $this->campoSeguro('nivel')?>").select2();

$('#<?php echo $this->campoSeguro('tipo_jurado')?>').width(210);
$("#<?php echo $this->campoSeguro('tipo_jurado')?>").select2();

$('#<?php echo $this->campoSeguro('tipo_jurado2')?>').width(210);
$("#<?php echo $this->campoSeguro('tipo_jurado2')?>").select2();

$('#<?php echo $this->campoSeguro('criterio_evaluacion')?>').width(210);
$("#<?php echo $this->campoSeguro('criterio_evaluacion')?>").select2();

$('#<?php echo $this->campoSeguro('usuario_jurado')?>').width(210);
$("#<?php echo $this->campoSeguro('usuario_jurado')?>").select2();



<?php
//}



?>
