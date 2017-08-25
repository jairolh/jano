<?php

namespace gestionConcurso\gestionInscripcion\funcion;

use gestionConcurso\gestionInscripcion\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class cerrarEvaluacionFinal {

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
                         'faseAct'=>$_REQUEST['consecutivo_calendario'],
                         'fecha_registro'=>date("Y-m-d H:m:s"),
                         'nombre_concurso'=>$_REQUEST['nombre_concurso'],
                         'nombre'=>$_REQUEST['nombre'],   
                         'faseNueva'=>isset($_REQUEST['etapaPasa'])?$_REQUEST['etapaPasa']:0,
                         'faseDesc'=>'',);    
        //$cadena_sql = $this->miSql->getCadenaSql("consultarCalculoEvaluacionParcial", $parametro);
        $cadena_sql = $this->miSql->getCadenaSql("consultarReclamacionesEvaluacion", $parametro);
        $resultadoListaReclamo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //var_dump($resultadoListaReclamo);
        //consulta datos de puntaje de criterios
        $cadena_sql = $this->miSql->getCadenaSql("consultarCriteriosEtapa", $parametro);
        $resultadoCriterio = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        if($resultadoListaReclamo)
            {   //llama imagen progreso
                $this->progreso($esteBloque);
                //recorre los registros de los que se validaron
                foreach ($resultadoListaReclamo as $key => $value)
                    {
                    $parametro['consecutivo_inscrito']=$resultadoListaReclamo[$key]['consecutivo_inscrito'];   
                    $parametro['id_reclamacion']=$resultadoListaReclamo[$key]['reclamo'];   
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
                                     if($resultadoParcial[$parc]['id_reclamacion']!='')
                                       {$puntaje[$pos]['id_reclamacion']=$resultadoParcial[$parc]['id_reclamacion'];}
                                    }
                                else{
                                     array_push($evaluar,$resultadoParcial[$parc]['id_evaluar']);
                                     $pos=array_search($resultadoParcial[$parc]['id_evaluar'], $evaluar);
                                     $puntaje[$pos]['evaluar']=$resultadoParcial[$parc]['id_evaluar'];
                                     $puntaje[$pos]['puntos']=$resultadoParcial[$parc]['puntaje_parcial'];
                                     $puntaje[$pos]['aprueba']=$resultadoParcial[$parc]['puntos_aprueba'];
                                     $puntaje[$pos]['jurados']=$resultadoParcial[$parc]['jurados'];
                                     $puntaje[$pos]['id_inscrito']=$resultadoParcial[$parc]['id_inscrito'];
                                     $puntaje[$pos]['id_reclamacion']=$resultadoParcial[$parc]['id_reclamacion'];
                                    }
                            }
                            
                        $fase=array('puntos'=>0,'Paprueba'=>0,'aprobo'=>array());
                        $evaluacion=array();
                        $promedio=0;
                        foreach ($puntaje as $eval => $value) 
                            {    
                                    $final=($puntaje[$eval]['puntos']/$puntaje[$eval]['jurados']);
                                    //se calcula los puntajes final de la fase y de aprobación
                                    $fase['puntos']+=$final;
                                    $fase['Paprueba']+=$puntaje[$eval]['aprueba'];
                                    $puntosFinal=array( 'id_inscrito'=>$puntaje[$eval]['id_inscrito'],
                                                  'id_evaluar'=>$puntaje[$eval]['evaluar'],
                                                  'puntaje_final'=>$final,
                                                  'observacion'=>" Según reclamación ".$puntaje[$eval]['id_reclamacion'].", cálculo de puntaje final de evaluación, con puntaje minimo de aprobación de ".$puntaje[$eval]['aprueba'],
                                                  'fecha_registro'=>$parametro['fecha_registro'],
                                                  'aprobo'=>($final>=$puntaje[$eval]['aprueba'])?'SI':'NO',
                                            );
                                   array_push($fase['aprobo'],$puntosFinal['aprobo']);
                                   if($puntaje[$eval]['id_reclamacion']!='' && $puntaje[$eval]['id_reclamacion']>0 ) 
                                        { 
                                           //actualizar estado de evaluaciónfinal anterior
                                           $updateFinal=array( 'id_inscrito'=>$puntaje[$eval]['id_inscrito'],
                                                               'id_evaluar'=>$puntaje[$eval]['evaluar'],
                                                               'estado'=>'I',
                                                          );
                                           $this->cadena_sql = $this->miSql->getCadenaSql("actualizarEvaluacionFinal", $updateFinal);
                                           $resultadoUpd = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "actualiza", $updateFinal, "actualizarEvaluacionFinal" );
                                           //registra puntaje final
                                           $this->cadena_sql = $this->miSql->getCadenaSql("registroEvaluacionFinal", $puntosFinal);
                                           $resultadofinal=$esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $puntosFinal, "registroCalculoEvaluacionFinal" );
                                        }
                                   $puntosParcial=array('id_inscrito'=>$puntosFinal['id_inscrito'],
                                                        'id_evaluar'=>$puntosFinal['id_evaluar'],
                                                        'id_evaluacion_final_reclamo'=>isset($resultadofinal)?$resultadofinal:0,
                                                        'puntaje_final'=>$final,
                                            );
                                    array_push($evaluacion,$puntosParcial);    
                                    $promedio+=$final;
                                    
                                    if($puntaje[$eval]['id_reclamacion']!='' && $puntaje[$eval]['id_reclamacion']>0 ) 
                                        { 
                                           //registra relacion de evaluacion final con evaluación parcial
                                           $this->cadena_sql = $this->miSql->getCadenaSql("actualizarEvaluacionParcial", $puntosParcial);
                                           $resultadoParcial = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "actualiza", $puntosParcial, "actualizarEvaluacionParcial" );
                                        }
                                  unset($final); 
                                  unset($puntosFinal);
                            } 
                        unset($evaluar);
                        unset($puntaje);
                           
                        }
                       
                        //se valida si pasa todos las evaluaciones y alcanza el porcentaje de aprobacion
                        if(isset($fase))
                            {  
                            $parametroUpd=array('id_inscrito'=>$resultadoListaReclamo[$key]['consecutivo_inscrito'] ,
                                                 'id_calendario'=>$parametro['consecutivo_calendario'] ,
                                                 'estado'=>'I',
                                                );
                            $this->cadena_sql = $this->miSql->getCadenaSql("actualizarEvaluacionPromedio", $parametroUpd);
                            $resultadoUpd = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "actualiza", $parametroUpd, "actualizarEvaluacionPromedio" );
                            
                            $parametroProm=array('id_inscrito'=>$resultadoListaReclamo[$key]['consecutivo_inscrito'] ,
                                                 'id_calendario'=>$parametro['consecutivo_calendario'] ,
                                                 'puntaje_promedio'=>$promedio ,
                                                 'evaluaciones'=>json_encode($evaluacion),
                                                 'fecha_registro'=>$parametro['fecha_registro'],
                                                 'id_reclamacion'=>$parametro['id_reclamacion'],
                                                );
                            $this->cadena_sql = $this->miSql->getCadenaSql("registroEvaluacionPromedio", $parametroProm);
                            $resultadoPromedio = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $parametroProm, "registroEvaluacionPromedio" );
                            unset($evaluacion);
                            unset($promedio);
                            }                        
                        
                        
                        //se valida si pasa todos las evaluaciones y alcanza el porcentaje de aprobacion
                        if(isset($fase))
                            {   $parametroEtp=array('consecutivo_inscrito'=>$parametro['consecutivo_inscrito'],
                                                    'faseAct'=>$parametro['faseAct'],
                                                    'estado'=>'I');
                                $this->cadena_sql = $this->miSql->getCadenaSql("actualizarEtapaInscrito", $parametroEtp);
                                $resultadoUpd = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "actualiza", $parametroEtp, "actualizarEtapaInscrito" );
                                $porcetaje_fase=($fase['puntos']*100)/$resultadoCriterio[0]['maximo_fase'];
                                $puntos_aprueba=($resultadoCriterio[0]['maximo_fase']*$_REQUEST['porcentaje_aprueba'])/100;
                                if(!in_array("NO", $fase['aprobo']) && $porcetaje_fase>=$_REQUEST['porcentaje_aprueba'])
                                    { $parametro['faseDesc']= ',con '.$fase['puntos'].' puntos y un minimo para aprobar de '.$puntos_aprueba.' puntos;';  
                                      $parametro['faseDesc'].= 'Porcentaje total de  '. number_format($porcetaje_fase,2).'%, correspondiente a la reclamación '.$parametro['id_reclamacion'];  
                                      $parametro['inscripcion']=$resultadoListaReclamo[$key]['consecutivo_inscrito'];   
                                      $this->pasaFase($parametro,$esteRecursoDB);
                                    }
                              unset($fase);   
                            }
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
        //registra los aspirantes que pasan la fase
        $arregloDatos = array('consecutivo_inscrito' => $parametro['inscripcion'],
                              'consecutivo_calendario' => $parametro['faseNueva'],
                              'observacion' => 'Cierre automatico fase '.$parametro['nombre'].$parametro['faseDesc'],
                              'fecha_registro' => $parametro['fecha_registro'],
                              'consecutivo_calendario_ant' => $parametro['consecutivo_calendario'],
                            );
       $this->cadena_sql = $this->miSql->getCadenaSql("registroEtapaInscrito", $arregloDatos);
       $resultadoCierre = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $arregloDatos, "registroCierreEtapaInscrito" );
    }
    
    function cerrarFase($parametro,$esteRecursoDB) {
        //actualiza tipo de cierre
        $arregloCierre = array('consecutivo_inscrito' => $parametro['inscripcion'],
                               'consecutivo_calendario' => $parametro['faseAct'],
                               'cierre' => 'final',
                              );        
        $this->cadena_sql = $this->miSql->getCadenaSql("actualizaCierreCalendario", $arregloCierre);
        $resultadoCierre = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "actualiza", $arregloCierre, "actualizaCierreCalendarioRequisito" );
    }        
        
    
}

$miRegistrador = new cerrarEvaluacionFinal($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>