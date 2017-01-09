


$(function() {
	$("button").button().click(function(event) {
		event.preventDefault();
	});
});

$( "#divLogoNotificador" ).click(function() {
	  $( "#divContenidoNotificador" ).toggle( "slow");
	});
