<?php
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";
//Variables
$cadenaACodificar17 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar17 .= "&procesarAjax=true";
$cadenaACodificar17 .= "&action=index.php";
$cadenaACodificar17 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar17 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar17 .= $cadenaACodificar17 . "&funcion=consultarTipoVinculacionAjax";
$cadenaACodificar17 .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena17 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar17, $enlace);
// URL definitiva
$urlFinal17 = $url . $cadena17;


$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";
//Variables
$cadenaACodificar18 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar18 .= "&procesarAjax=true";
$cadenaACodificar18 .= "&action=index.php";
$cadenaACodificar18 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar18 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar18 .= $cadenaACodificar18 . "&funcion=consultarNovedadAjax";
$cadenaACodificar18 .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena18 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar18, $enlace);
// URL definitiva
$urlFinal18 = $url . $cadena18;

$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";
//Variables
$cadenaACodificar19 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar19 .= "&procesarAjax=true";
$cadenaACodificar19 .= "&action=index.php";
$cadenaACodificar19 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar19 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar19 .= $cadenaACodificar19 . "&funcion=consultarValorConceptoAjax";
$cadenaACodificar19 .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena19 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar19, $enlace);
// URL definitiva
$urlFinal19 = $url . $cadena19;
?>

<script>
    $('#<?php echo $this->campoSeguro('fdpTipoVinculacion') ?>').width(240);
    $("#<?php echo $this->campoSeguro('fdpTipoVinculacion') ?>").select2();
    $('#<?php echo $this->campoSeguro('fdpNovedades') ?>').width(240);
    $("#<?php echo $this->campoSeguro('fdpNovedades') ?>").select2();
    $('#<?php echo $this->campoSeguro('estado') ?>').width(240);
    $("#<?php echo $this->campoSeguro('estado') ?>").select2();



    var table = $('#tablaReporte').DataTable();
    var arregloId = [];

    function consultarTipoVinculacion(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal17 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('fdpTipoVinculacion') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {

                    $.each(data, function (indice, valor) {

                        table.row.add([data[ indice ].nombres,
                            data[ indice ].apellidos,
                            data[ indice ].documento,
                            data[ indice ].cargo,
                            data[ indice ].nombre_tipo_vinculacion

                        ]).draw(false);
                    });
                }
            }
        });
    }

    function consultarTipoNovedad(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal18 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('fdpNovedades') ?>").val()},
            success: function (data) {
                if (data[0] != " -") {


                    $.each(data, function (indice, valor) {
                        $("#<?php echo $this->campoSeguro('tipoNovedad') ?>").val(data[ indice ].tipo_novedad)
                    });

                }
            }

        });

    }

    


    $(function () {
        $("#<?php echo $this->campoSeguro('fdpTipoVinculacion') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('fdpTipoVinculacion') ?>").val() != ' ') {
                table.clear().draw();
                consultarTipoVinculacion();
            }
        });
        $("#<?php echo $this->campoSeguro('fdpNovedades') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('fdpNovedades') ?>").val() != ' ') {
                consultarTipoNovedad();
            }
        });
       


    });


    $(document).ready(function () {
        var t = $('#tablaReporte').DataTable();
        $('#tablaReporte tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                t.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        $('#btGestionar').click(function () { // Elimina un panel de condiciones del DOM
            var data = t.row('.selected').data();

            $("#<?php echo $this->campoSeguro('nombres') ?>").val(data[0]);
            $("#<?php echo $this->campoSeguro('apellidos') ?>").val(data[1]);
            $("#<?php echo $this->campoSeguro('cedula') ?>").val(data[2]);
            $("#<?php echo $this->campoSeguro('cargo') ?>").val(data[3]);
        });
    });

</script>