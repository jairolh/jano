<?php
//Se coloca esta condición para evitar cargar algunos scripts en el formulario de confirmación de entrada de datos.
//if(!isset($_REQUEST["opcion"])||(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]!="confirmar")){

?>


$('#tablaConsultaAspirantesAsignados').DataTable({
  columns: [
      { title: "Inscripción" },
      { title: "Identificación" },
      { title: "Aspirante" },
      { title: "Código" },
      { title: "Perfil" }
  ],
"language": {
    "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
    "zeroRecords": "No se encontraron registros coincidentes",
    "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
    "infoEmpty": "No hay datos registrados",
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

$('#tablaConsultaAspirantesEvaluador').DataTable({
  columns: [
      { title: "Inscripción" },
      { title: "Identificación" },
      { title: "Aspirante" },
      { title: "Código" },
      { title: "Perfil" }
  ],
"language": {
    "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
    "zeroRecords": "No se encontraron registros coincidentes",
    "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
    "infoEmpty": "No hay datos registrados",
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

$('#tablaConsultaAspirante').DataTable({
"language": {
    "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
    "zeroRecords": "No se encontraron registros coincidentes",
    "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
    "infoEmpty": "No hay datos registrados",
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

$('#tablaConcursos').DataTable({
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

$('#tablaConsultaCalendario').DataTable({
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

$('#tablaConsultaInscrito').DataTable({
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


// Asociar el widget de validación al formulario detalleConcurso
$("#detalleConcurso").validationEngine({
    promptPosition : "centerRight",
    scroll: false
});

// Asociar el widget de validación al formulario detalleConcurso
$("#gestionInscripcion").validationEngine({
    promptPosition : "centerRight",
    scroll: false
});





$(function() {

    $("#gestionInscripcionJurados").submit(function() {
      $resultado=$("#gestionInscripcionJurados").validationEngine("validate");
        if ($resultado) {
            return true;
        }
        return false;
    });

   $("#gestionInscripcionEvaluadores").submit(function() {
      $resultado=$("#gestionInscripcionEvaluadores").validationEngine("validate");
        if ($resultado) {
            return true;
        }
        return false;
    });

      $("#asignar").submit(function() {

        items=[];

        var aux=$('#<?php echo $this->campoSeguro("numeroAspirantes")?>').val();

        var inscripciones = aux.split(",");

        var inicio=0;
        var fin=0;
        if(inscripciones.length===2){
          inicio=inscripciones[0];
          fin=inscripciones[1];
        }else{
          inicio=inscripciones[0];
          fin=inscripciones[0];
        }

            for(i=inicio; i <= fin; i ++) {
              if( document.getElementById("seleccion"+i) != null){
                if ( document.getElementById("seleccion"+i).checked ) {
                  items.push($("#seleccion"+i).val());
                }
              }
            }
            $('#<?php echo $this->campoSeguro("aspirantes")?>').val(items);

          /*
          $resultado=$("#gestionInscripcion").validationEngine("validate");
          if ($resultado) {
              return true;
          }
          return false;
          */

    });

});


$('#<?php echo $this->campoSeguro('fecha_inicio_concurso')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
        <?php /*?> maxDate: 0,<?php */?>
         minDate: 0,
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '+0:+5',
        <?php /*?>permite asirnara a otro campo como fecha minima */?>
        onSelect: function(dateText, inst) {
        var lockDate = new Date($('#<?php echo $this->campoSeguro('fecha_inicio_concurso')?>').datepicker('getDate'));
        //lockDate.setDate(lockDate.getDate() + 1);
        $('input#<?php echo $this->campoSeguro('fecha_fin_concurso')?>').datepicker('option', 'minDate', lockDate);}
   });

$('#<?php echo $this->campoSeguro('fecha_fin_concurso')?>').datepicker({
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



$('#<?php echo $this->campoSeguro('fecha_inicio_calendario')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
        <?php /*?> maxDate: 0,<?php */?>
         minDate: '<?php echo isset($_REQUEST['inicio_concurso'])?$_REQUEST['inicio_concurso']:''?>',
         maxDate: '<?php echo isset($_REQUEST['cierre_concurso'])?$_REQUEST['cierre_concurso']:''?>',
        changeYear: true,
        changeMonth: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
            dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        yearRange: '+0:+5',
        <?php /*?>permite asirnara a otro campo como fecha minima */?>
        onSelect: function(dateText, inst) {
        var lockDate = new Date($('#<?php echo $this->campoSeguro('fecha_inicio_calendario')?>').datepicker('getDate'));
        //lockDate.setDate(lockDate.getDate() + 1);
        $('input#<?php echo $this->campoSeguro('fecha_fin_calendario')?>').datepicker('option', 'minDate', lockDate);}
   });

$('#<?php echo $this->campoSeguro('fecha_fin_calendario')?>').datepicker({
        <?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
        dateFormat: 'yy-mm-dd',
        maxDate: '<?php echo isset($_REQUEST['cierre_concurso'])?$_REQUEST['cierre_concurso']:''?>',
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

// Asociar el widget de validación al formulario detalleConcurso
$("#detalleConcurso").validationEngine({
    promptPosition : "centerRight",
    scroll: false
});

$(function() {
    $("#detalleConcurso").submit(function() {
        $resultado=$("#detalleConcurso").validationEngine("validate");
        if ($resultado) {
            return true;
        }
        return false;
    });


});

// Asociar el widget de validación al formulario detalleConcurso
$("#datosCierre").validationEngine({
    promptPosition : "centerRight",
    scroll: false
});

$(function() {
    $("#datosCierre").submit(function() {
        $resultado=$("#datosCierre").validationEngine("validate");
        if ($resultado) {
            return true;
        }
        return false;
    });
 });


$('#<?php echo $this->campoSeguro('tipo')?>').width(210);
$("#<?php echo $this->campoSeguro('tipo')?>").select2();
$('#<?php echo $this->campoSeguro('modalidad')?>').width(210);
$("#<?php echo $this->campoSeguro('modalidad')?>").select2();
$('#<?php echo $this->campoSeguro('consecutivo_factor')?>').width(450);
$("#<?php echo $this->campoSeguro('consecutivo_factor')?>").select2();
$('#<?php echo $this->campoSeguro('consecutivo_criterio')?>').width(450);
$("#<?php echo $this->campoSeguro('consecutivo_criterio')?>").select2();
$('#<?php echo $this->campoSeguro('consecutivo_actividad')?>').width(450);
$("#<?php echo $this->campoSeguro('consecutivo_actividad')?>").select2();
$('#<?php echo $this->campoSeguro('consecutivo_evaluar')?>').width(450);
$("#<?php echo $this->campoSeguro('consecutivo_evaluar')?>").select2();
$('#<?php echo $this->campoSeguro('seleccionJurado')?>').width(450);
$("#<?php echo $this->campoSeguro('seleccionJurado')?>").select2();
$('#<?php echo $this->campoSeguro('tipoJurado')?>').width(210);
$("#<?php echo $this->campoSeguro('tipoJurado')?>").select2();
$('#<?php echo $this->campoSeguro('etapaPasa')?>').width(450);
$("#<?php echo $this->campoSeguro('etapaPasa')?>").select2();

$('#<?php echo $this->campoSeguro('seleccionEvaluador')?>').width(450);
$("#<?php echo $this->campoSeguro('seleccionEvaluador')?>").select2();
