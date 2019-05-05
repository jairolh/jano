
// Asociar el widget de validación al formulario
$("#concursosActivos").validationEngine({
    promptPosition : "centerRight",
    scroll: false
});

$(function() {
    $("#concursosActivos").submit(function() {
        $resultado=$("#gestionJurado").validationEngine("validate");
        if ($resultado) {
            return true;
        }
        return false;
    });
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

