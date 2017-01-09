// Asociar el widget de validación al formulario
$("#cambiarClave").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});

$(function() {
    $("#cambiarClave").submit(function() {
        var resultado=$("#cambiarClave").validationEngine("validate");
        if (resultado) {
            // console.log(filasGrilla);
            return true;
        }
        return false;
    });
});

$(function() {
    $( "button" )
    .button()
    .click(function( event ) {
        event.preventDefault();
    });
});


$(function() {
    $( document ).tooltip();
});

//Asociar el widget tabs a la división cuyo id es tabs
$(function() {
    $( "#tabs" ).tabs();
});
        
$(function() {
    $("button").button().click(function(event) {
        event.preventDefault();
    });
});
