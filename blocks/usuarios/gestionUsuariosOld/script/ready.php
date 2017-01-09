<?php 
//Se coloca esta condición para evitar cargar algunos scripts en el formulario de confirmación de entrada de datos.
//if(!isset($_REQUEST["opcion"])||(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]!="confirmar")){

?>
        $('#tablaProcesos').dataTable({bJQueryUI : true,
        "sPaginationType": "full_numbers"
        });
                      
                      
        // Asociar el widget de validación al formulario
        $("#gestionUsuarios").validationEngine({
            promptPosition : "centerRight", 
            scroll: false
        });

        $(function() {
            $("#gestionUsuarios").submit(function() {
                $resultado=$("#gestionUsuarios").validationEngine("validate");
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
$('#<?php echo $this->campoSeguro('tipo_identificacion')?>').width(210);
$("#<?php echo $this->campoSeguro('tipo_identificacion')?>").select2(); 
$('#<?php echo $this->campoSeguro('subsistema')?>').width(210);
$("#<?php echo $this->campoSeguro('subsistema')?>").select2(); 
$('#<?php echo $this->campoSeguro('perfil')?>').width(210);
$("#<?php echo $this->campoSeguro('perfil')?>").select2(); 

<?php 
//}



?>



