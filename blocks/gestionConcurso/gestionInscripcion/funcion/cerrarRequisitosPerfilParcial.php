<?php

namespace gestionConcurso\gestionInscripcion\funcion;

use gestionConcurso\gestionInscripcion\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class cerrarRequisitosPerfilParcial {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;
    var $miLogger;

    function __construct($lenguaje, $sql, $funcion, $miLogger) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
        $this->miLogger= $miLogger;
    }

    function procesarFormulario() {
        $conexion="estructura";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
        $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                         'validacion'=>'SI',
                         'fecha_registro'=>date("Y-m-d H:m:s"),
                         'nombre_concurso'=>$_REQUEST['nombre_concurso'],
                         'nombre'=>$_REQUEST['nombre'],   
                         'faseAct'=>$_REQUEST['consecutivo_calendario'],
                         'faseNueva'=>isset($_REQUEST['etapaPasa'])?$_REQUEST['etapaPasa']:0,  );    
        $cadena_sql = $this->miSql->getCadenaSql("consultarValidadoPerfilConcurso", $parametro);
        $resultadoListaValidado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        if($resultadoListaValidado)
            {   //llama imagen progreso
                $this->progreso($esteBloque);
                //recorre los registros de los que se validaron
                foreach ($resultadoListaValidado as $key => $value) {
                    $parametro['inscripcion']=$resultadoListaValidado[$key]['consecutivo_inscrito'];    
                    $this->pasaFase($parametro,$esteRecursoDB);
                }
                $this->cerrarFase($parametro,$esteRecursoDB);
                
                redireccion::redireccionar('CerroFase',$parametro);
                exit();
            }
        else
            {redireccion::redireccionar('noCerroFase',$parametro);
             exit();
            }
    }

    function resetForm() {
        foreach ($_REQUEST as $clave => $valor) {

            if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
                unset($_REQUEST [$clave]);
            }
        }
    }

    function progreso($esteBloque) {
        // ------------------Inicio Division para progreso-------------------------
        $url = $this->miConfigurador->getVariableConfiguracion ( "host" );
        $url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
        $directorioImg = $url."/blocks/".$esteBloque ["grupo"]."".$esteBloque ["nombre"]."/images/";
        echo '<div id="divcarga" style="color:#000;margin-top:20px; font-size:20px;font-weight:bold;text-align:center;height:300px;" >
                  <span >Procesando la información, Espere por favor ...  </span>
                  <img  src="'.$directorioImg.'load.gif">
              </div>' ;
            // ------------------Fin Division para progreso-------------------------    
        //llama funcion para visualizar al div cuando termina de cargar
        //echo "<script language='javascript'> setTimeout(function(){desbloquea('divcarga','tabs')},1000)  </script>";
    }

    function pasaFase($parametro,$esteRecursoDB) {
        //busca datos registrados
        $arregloDatos = array('consecutivo_inscrito' => $parametro['inscripcion'],
                              'consecutivo_calendario' => $parametro['faseNueva'],
                              'observacion' => 'Cierre automatico fase '.$parametro['nombre'],
                              'fecha_registro' => $parametro['fecha_registro'],
                              'consecutivo_calendario_ant' => $parametro['faseAct'],
                            );
        $this->cadena_sql = $this->miSql->getCadenaSql("registroEtapaInscrito", $arregloDatos);
        $resultadoEtapa = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $arregloDatos, "registroCierreEtapaInscrito" );
    } 
    
    function cerrarFase($parametro,$esteRecursoDB) {
        //busca datos registrados
        $arregloCierre = array('consecutivo_inscrito' => $parametro['inscripcion'],
                               'consecutivo_calendario' => $parametro['faseAct'],
                               'cierre' => 'parcial',
                              );        
        $this->cadena_sql = $this->miSql->getCadenaSql("actualizaCierreCalendario", $arregloCierre);
        $resultadoCierre = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "actualiza", $arregloCierre, "actualizaCierreCalendarioRequisito" );
       
    }        
    
}

$miRegistrador = new cerrarRequisitosPerfilParcial($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>