
$("#gestionarNovedades").validationEngine({
	promptPosition : "centerRight",
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});
   
$('#<?php echo $this->campoSeguro('tipo')?>').width(280);
$('#<?php echo $this->campoSeguro('tipo')?>').select2();

$('#<?php echo $this->campoSeguro('concepto')?>').width(280);
$('#<?php echo $this->campoSeguro('concepto')?>').select2();


$('#<?php echo $this->campoSeguro('unidad')?>').width(280);
$('#<?php echo $this->campoSeguro('unidad')?>').select2();

$('#<?php echo $this->campoSeguro('periodo')?>').width(280);
$('#<?php echo $this->campoSeguro('periodo')?>').select2();

$('#<?php echo $this->campoSeguro('estado')?>').width(280);
$('#<?php echo $this->campoSeguro('estado')?>').select2();

$('#tablaNovedades').DataTable({
	"language": {
            "lengthMenu": "Mostrar _MENU_ registro por p&aacute;gina",
            "zeroRecords": "No se encontraron registros coincidentes",
            "info": "Mostrando _PAGE_ de _PAGES_ p&aacute;ginas",
            "infoEmpty": "Ninguna novedad registrada",
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
        "columnDefs": [
		    { "orderable": false, "targets": [10] }
		  ]
});
