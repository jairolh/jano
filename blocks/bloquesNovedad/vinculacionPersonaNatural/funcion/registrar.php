<?php

namespace bloquesNovedad\vinculacionPersonaNatural\funcion;

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
        
        
        
       
        
        
        
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarPersonaFuncionario");
        $resultado = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");



        $datosubicacion = array(
            'sede' => $_REQUEST ['sede'],
            'dependencia' => $_REQUEST ['dependencia']
        );
       

        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarIdUbicacion", $datosubicacion);
      
        $ubicacion = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");

        if (empty($ubicacion)) {
            $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarUbicacion", $datosubicacion);
        
            $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "insertar");

            $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarIdUbicacion", $datosubicacion);

            $ubicacion = $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
        }


        if ($_REQUEST ['registroVinculacion'] == 1) {
            $datos = array(
                'fechaInicio' => $_REQUEST ['fechaInicio'],
                'fechaFin' => $_REQUEST ['fechaFin'],
                'tipoVinculacion' => $_REQUEST ['tipoVinculacion'],
                'ubicacion_sede_dependencia' => $ubicacion[0][0],
                'ubicacion_especifica' => $_REQUEST ['ubicacion'],
                'actividad'=> $_REQUEST ['actividad'],
                'cedula' => $_REQUEST ['cedula']
            );
          
         
            $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarVinculacion", $datos);

            
           $resultado = $primerRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda", $datos, "insertarVinculacion");


            if (!empty($resultado)) {
                Redireccionador::redireccionar('opcion3',$resultado[0][0]);
                exit();
            } else {
                Redireccionador::redireccionar('noInserto');
                exit();
            }
        } else {



             $datos = array(
                'fechaInicio' => $_REQUEST ['fechaInicio'],
                'fechaFin' => $_REQUEST ['fechaFin'],
                'tipoVinculacion' => $_REQUEST ['tipoVinculacion'],
                'ubicacion_sede_dependencia' => $ubicacion[0][0],
                'ubicacion_especifica' => $_REQUEST ['ubicacion'],
                'actividad'=> $_REQUEST ['actividad'],
                'cedula' => $_REQUEST ['cedula']
            );
             

            $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarVinculacion", $datos);

            $resultado = $primerRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda", $datos, "insertarVinculacion");


            $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarTipoVinculacion", $_REQUEST ['tipoVinculacion']);

            $resultado1 = $primerRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda", $_REQUEST ['tipoVinculacion'], "buscarTipoVinculacion");

            if (!empty($resultado)) {

                if ($resultado1[0][0] == 'Rubro de salida') {
                    Redireccionador::redireccionar('opcion2', $resultado[0][0]);

                    exit();
                }

                if ($resultado1[0][0] == 'Rubro de entrada') {
                    Redireccionador::redireccionar('opcion1', $resultado[0][0]);

                    exit();
                }
            } else {
                Redireccionador::redireccionar('noInserto');
                exit();
            }
        }
        //Al final se ejecuta la redirección la cual pasará el control a otra página
        // Redireccionador::redireccionar('opcion1');
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

