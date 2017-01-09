<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

// Variables
$cadenaACodificar = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar .= "&procesarAjax=true";
$cadenaACodificar .= "&action=index.php";
$cadenaACodificar .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar .= $cadenaACodificar . "&funcion=tablaItems";
$cadenaACodificar .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar, $enlace);

// URL definitiva
$urlFinal = $url . $cadena;

// Variables
$cadenaACodificar16 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar16 .= "&procesarAjax=true";
$cadenaACodificar16 .= "&action=index.php";
$cadenaACodificar16 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar16 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar16 .= $cadenaACodificar16 . "&funcion=consultarDependencia";
$cadenaACodificar16 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena16 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar16, $enlace);

// URL definitiva
$urlFinal16 = $url . $cadena16;
?>
<script type='text/javascript'>


    $(function () {

        $("#<?php echo $this->campoSeguro('sede') ?>").select2({
            placeholder: "Search for a repository",
            minimumInputLength: 2,
        });
        $("#<?php echo $this->campoSeguro('selec_tipoConsulta') ?>").select2({
            placeholder: "Search for a repository",
            minimumInputLength: 2,
        });
        $("#<?php echo $this->campoSeguro('dependencia') ?>").select2({
            placeholder: "Search for a repository",
            minimumInputLength: 2,
        });
        $("#<?php echo $this->campoSeguro('nombreFuncionario') ?>").select2({
            placeholder: "Search for a repository",
            minimumInputLength: 2,
        });
    });

    function consultarDependencia(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal16 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('sede') ?>").val()},
            success: function (data) {

                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('dependencia') ?>").html('');
                    $("<option value=''>Seleccione ...</option>").appendTo("#<?php echo $this->campoSeguro('dependencia') ?>");
                    $.each(data, function (indice, valor) {

                        $("<option value='" + data[ indice ].ESF_ID_ESPACIO + "'>" + data[ indice ].ESF_NOMBRE_ESPACIO + "</option>").appendTo("#<?php echo $this->campoSeguro('dependencia') ?>");
                    });
                    $("#<?php echo $this->campoSeguro('dependencia') ?>").removeAttr('disabled');
                    $("#<?php echo $this->campoSeguro('dependencia') ?>").select2();
                }
            }

        });
    }
    ;


</script>

