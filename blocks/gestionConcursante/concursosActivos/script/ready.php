
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
