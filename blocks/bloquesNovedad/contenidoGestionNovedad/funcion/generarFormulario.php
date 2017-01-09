<?php

namespace bloquesNovedad\contenidoGestionNovedad\funcion;

include_once('Redireccionador.php');

class FormProcessor {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;
    var $conexion;

    function __construct($lenguaje, $sql) {

        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
    }

    function procesarFormulario() {

        //Aquí va la lógica de procesamiento

        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $opcion=0;


        if($_REQUEST['tipoNovedad']=='Esporadica'){
            $opcion=1;
        }
        else{
            $opcion=2;
        }
         if($_REQUEST['estado']==1){
            $_REQUEST['estado']='Activo';
        }
        else{
            $_REQUEST['estado']='Inactivo';
        }
        
        $datosConcepto = array(
                'eleccionNovedad' => $_REQUEST['fdpNovedades'],
                'tipoNovedad' => $_REQUEST['tipoNovedad'],
                'estado' => $_REQUEST['estado']
            );




        if ($opcion==1) {
            Redireccionador::redireccionar('Esporadica', $datosConcepto);
            exit();
        } else {
            Redireccionador::redireccionar('Periodica', $datosConcepto);
            exit();
        }
    }

    function resetForm() {
        foreach ($_REQUEST as $clave => $valor) {

            if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
                unset($_REQUEST[$clave]);
            }
        }
    }

}

$miProcesador = new FormProcessor($this->lenguaje, $this->sql);

$resultado = $miProcesador->procesarFormulario();

