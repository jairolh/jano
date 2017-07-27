<?php

namespace gestionConcurso\gestionInscripcion\funcion;

use gestionConcurso\gestionInscripcion\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class cerrarEvaluacion {

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
        //busca inscritos a la etapa
        $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                         'consecutivo_calendario'=>$_REQUEST['consecutivo_calendario'],    
                         'fecha_registro'=>date("Y-m-d H:m:s"),
                         'nombre_concurso'=>$_REQUEST['nombre_concurso'],
                         'nombre'=>$_REQUEST['nombre'],   
                         'faseNueva'=>$_REQUEST['etapaPasa'],
                         'faseDesc'=>'',);    
        //$cadena_sql = $this->miSql->getCadenaSql("consultarCalculoEvaluacionParcial", $parametro);
        $cadena_sql = $this->miSql->getCadenaSql("consultarInscritoEtapa", $parametro);
        $resultadoListaInscrito = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

        if($resultadoListaInscrito)
            {   //llama imagen progreso
                $this->progreso($esteBloque);
                //recorre los registros de los que se validaron
                foreach ($resultadoListaInscrito as $key => $value)
                    {
                    $parametro['consecutivo_inscrito']=$resultadoListaInscrito[$key]['consecutivo_inscrito'];   
                    $cadena_sql = $this->miSql->getCadenaSql("consultarDetalleEvaluacionParcial", $parametro);
                    $resultadoParcial = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    if($resultadoParcial)
                        {
                         $evaluar=array();$puntaje=array();
                         foreach ($resultadoParcial as $parc => $value) 
                            {   //recorre las evaluaciones de criterios, registradas por inscrito y calcula el puntaje final
                                if (in_array($resultadoParcial[$parc]['id_evaluar'], $evaluar)) {
                                     $pos=array_search($resultadoParcial[$parc]['id_evaluar'], $evaluar);
                                     $puntaje[$pos]['puntos']+=$resultadoParcial[$parc]['puntaje_parcial'];
                                    }
                                else{
                                     array_push($evaluar,$resultadoParcial[$parc]['id_evaluar']);
                                     $pos=array_search($resultadoParcial[$parc]['id_evaluar'], $evaluar);
                                     $puntaje[$pos]['evaluar']=$resultadoParcial[$parc]['id_evaluar'];
                                     $puntaje[$pos]['puntos']=$resultadoParcial[$parc]['puntaje_parcial'];
                                     $puntaje[$pos]['aprueba']=$resultadoParcial[$parc]['puntos_aprueba'];
                                     $puntaje[$pos]['jurados']=$resultadoParcial[$parc]['jurados'];
                                     $puntaje[$pos]['id_inscrito']=$resultadoParcial[$parc]['id_inscrito'];
                                    }
                            }
                        $fase=array('puntos'=>0,'aprueba'=>0);
                        foreach ($puntaje as $eval => $value) 
                            {   $final=($puntaje[$eval]['puntos']/$puntaje[$eval]['jurados']);
                                //se calcula los puntajes final de la fase y de aprobación
                                $fase['puntos']+=$final;
                                $fase['aprueba']+=$puntaje[$eval]['aprueba'];
                                $puntosFinal=array( 'id_inscrito'=>$puntaje[$eval]['id_inscrito'],
                                              'id_evaluar'=>$puntaje[$eval]['evaluar'],
                                              'puntaje_final'=>$final,
                                              'observacion'=>"Cálculo de puntaje final de evaluación, con puntaje minimo de aprobación de ".$puntaje[$eval]['aprueba'],
                                              'fecha_registro'=>$parametro['fecha_registro'],
                                              'aprobo'=>($final>=$puntaje[$eval]['aprueba'])?'SI':'NO',
                                        );
                               //registra puntaje final
                               $this->cadena_sql = $this->miSql->getCadenaSql("registroEvaluacionFinal", $puntosFinal);
                               $resultadofinal = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $puntosFinal, "registroCalculoEvaluacionFinal" );
                               $puntosParcial=array('id_inscrito'=>$puntosFinal['id_inscrito'],
                                                    'id_evaluar'=>$puntosFinal['id_evaluar'],
                                                    'id_evaluacion_final'=>$resultadofinal,
                                        );
                               //registra relacion de evaluacion final con evaluación parcial
                               $this->cadena_sql = $this->miSql->getCadenaSql("actualizarEvaluacionParcial", $puntosParcial);
                               $resultadoParcial = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $puntosParcial, "actualizarEvaluacionParcial" );
                              unset($final); 
                              unset($puntosFinal);
                            } 
                        unset($evaluar);
                        unset($puntaje);
                           
                        }
                        //se validan los puntajes finales para identificar si pasa la fase
                        if(isset($fase))
                            {   if($fase['puntos']>=$fase['aprueba'])
                                    { $parametro['faseDesc']= ',con '.$fase['puntos'].' puntos y un minimo para aprobar de '.$fase['aprueba'].' puntos';  
                                      $parametro['inscripcion']=$resultadoListaInscrito[$key]['consecutivo_inscrito'];   
                                      $this->cerrarFase($parametro,$esteRecursoDB);     
                                    }
                              unset($fase);   
                            }
                    }
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
    
    function cerrarFase($parametro,$esteRecursoDB) {
        //busca datos registrados
        $arregloDatos = array('consecutivo_inscrito' => $parametro['inscripcion'],
                              'consecutivo_calendario' => $parametro['faseNueva'],
                              'observacion' => 'Cierre automatico fase '.$parametro['nombre'].$parametro['faseDesc'],
                              'fecha_registro' => $parametro['fecha_registro'],
                              'consecutivo_calendario_ant' => $parametro['consecutivo_calendario'],
                            );
       $this->cadena_sql = $this->miSql->getCadenaSql("registroEtapaInscrito", $arregloDatos);
       $resultadoCierre = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $arregloDatos, "registroCierreEtapaInscrito" );
    }        
    
}

$miRegistrador = new cerrarEvaluacion($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>