<?php

namespace gestionConcurso\gestionInscripcion\funcion;

use gestionConcurso\gestionInscripcion\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class cerrarSoporteConcurso {

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
        //consulta inscritos al concurso
        $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                         'nombre_concurso'=>$_REQUEST['nombre_concurso'],
                         'fecha_registro'=>date("Y-m-d H:m:s"));    
        $cadena_sql = $this->miSql->getCadenaSql("consultarInscritoConcurso", $parametro);
        $resultadoListaInscrito = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
         //var_dump($resultadoListaInscrito);exit;
        if($resultadoListaInscrito)
            {   
                //llama imagen progreso
                $this->progreso($esteBloque);
                //busca datos registrados
                $parametro['nombre_fase']='Registro Soportes';
                $cadena_sql = $this->miSql->getCadenaSql('consultarFaseObligatoria', $parametro);
                $faseAnt = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                $parametro['nombre_fase']='Evaluar Requisitos';
                $cadena_sql = $this->miSql->getCadenaSql('consultarFaseObligatoria', $parametro);
                $fase = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                //asigna fases
                $parametro['faseAct']=$faseAnt[0]['consecutivo_calendario'];
                $parametro['faseNueva']=$fase[0]['consecutivo_calendario']; 
                
                //busca los datos de la hoja de vida y registra en soportes de inscripcion
                foreach ($resultadoListaInscrito as $key => $value) {
                    $parametro['consecutivo_persona']=$resultadoListaInscrito[$key]['consecutivo_persona'];    
                    $parametro['inscripcion']=$resultadoListaInscrito[$key]['consecutivo_inscrito'];    
                    $this->cerrarDatosBasicos($parametro,$esteRecursoDB);
                    $this->cerrarDatosContacto($parametro,$esteRecursoDB);
                    $this->cerrarDatosFormacion($parametro,$esteRecursoDB);
                    $this->cerrarDatosExperiencia($parametro,$esteRecursoDB);
                    $this->cerrarDatosDocencia($parametro,$esteRecursoDB);
                    $this->cerrarDatosActividadAcad($parametro,$esteRecursoDB);
                    $this->cerrarDatosInvestigacion($parametro,$esteRecursoDB);
                    $this->cerrarDatosProduccion($parametro,$esteRecursoDB);
                    $this->cerrarDatosIdioma($parametro,$esteRecursoDB);
                    $this->pasaFase($parametro,$esteRecursoDB);
                }
                $this->cerrarFase($parametro,$esteRecursoDB);
                redireccion::redireccionar('Cerro',$parametro);
                exit();
            }
        else
            {redireccion::redireccionar('noCerro',$parametro);
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
        
    
    function cerrarDatos($datosCierre,$esteRecursoDB) {
        $this->cadena_sql = $this->miSql->getCadenaSql("registroSoporteConcurso", $datosCierre);
        $resultadoCierre = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $datosCierre, "registroCierreSoporteConcurso" );
        return $resultadoCierre;
    }  
    
    function buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB){
        $arregloSoporte[0]['consecutivo_soporte']=0;
        $arregloSoporte[0]['alias_soporte']='';
        $arregloSoporte[0]['nombre_soporte']='';
        foreach ($nombre_soporte as $key => $value) {
              $parametroSop = array('consecutivo'=>$consecutivo_persona,
                                      'tipo_dato'=>$tipo_dato,
                                      'nombre_soporte'=>$value,
                                      'consecutivo_dato'=>$consecutivo_dato);
                $cadenaSop_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                $resultadoSop = $esteRecursoDB->ejecutarAcceso($cadenaSop_sql, "busqueda");                    
                if($resultadoSop)
                    {   $arregloSoporte[$key]['consecutivo_soporte']=$resultadoSop[0]['consecutivo_soporte'];
                        $arregloSoporte[$key]['tipo_soporte']=$resultadoSop[0]['nombre'];
                        $arregloSoporte[$key]['alias_soporte']=$resultadoSop[0]['alias'];
                        $arregloSoporte[$key]['nombre_soporte']=$resultadoSop[0]['ubicacion']."/".$resultadoSop[0]['archivo'];
                    }
            }
        return ($arregloSoporte);  
    }
    
    function cerrarDatosBasicos($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.persona';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoPersona = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        $arregloDatos = array('consecutivo' => $resultadoPersona[0]['consecutivo'],
                              'tipo_identificacion' => $resultadoPersona[0]['tipo_identificacion'],
                              'identificacion' => $resultadoPersona[0]['identificacion'],
                              'nombre' => $resultadoPersona[0]['nombre'],
                              'apellido' => $resultadoPersona[0]['apellido'],
                              'lugar_nacimiento' => $resultadoPersona[0]['ciudad'],
                              'fecha_nacimiento' => $resultadoPersona[0]['fecha_nacimiento'],
                              'pais_nacimiento' => $resultadoPersona[0]['pais'],
                              'departamento_nacimiento' => $resultadoPersona[0]['departamento'],
                              'sexo'  => $resultadoPersona[0]['sexo'],
                         );
        //busca soportes cargados
        $consecutivo_persona=$resultadoPersona[0]['consecutivo'];
        $tipo_dato='datosBasicos';
        $nombre_soporte=['foto','soporteIdentificacion'];
        $consecutivo_dato=$resultadoPersona[0]['consecutivo'];
        //busca soportes por registro
        $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
        if($resParametro)
            {$arregloDatos['soportes']=array();
             $arregloDatos['soportes']=$resParametro;
            }
        $arregloDatos = json_encode ( $arregloDatos );
        $arregloCierre=array('consecutivo_inscrito'=>$parametro['inscripcion'],
                             'tipo_dato'=>$tipo_dato,
                             'consecutivo_dato'=>$consecutivo_dato,
                             'fuente_dato'=>$parametro['tabla_ppal'],
                             'valor_dato'=>$arregloDatos,
                             'consecutivo_soporte'=>0,
                             'alias_soporte'=>'',
                             'nombre_soporte'=>'',
                             'fecha_registro'=> $parametro['fecha_registro']) ;
        //se envian los datos para registro
        $this->cerrarDatos($arregloCierre, $esteRecursoDB);
    }    

    function cerrarDatosContacto($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.contacto';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoContacto = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        if($resultadoContacto)
            {                 
            $arregloDatos = array('consecutivo_contacto' => $resultadoContacto[0]['consecutivo_contacto'],
                                  'consecutivo_persona' => $resultadoContacto[0]['consecutivo_persona'],
                                  'pais_residencia' => $resultadoContacto[0]['pais_residencia'],
                                  'pais' => $resultadoContacto[0]['pais'],
                                  'departamento_residencia' => $resultadoContacto[0]['departamento_residencia'],
                                  'departamento' => $resultadoContacto[0]['departamento'],
                                  'ciudad_residencia' => $resultadoContacto[0]['ciudad_residencia'],
                                  'ciudad' => $resultadoContacto[0]['ciudad'],
                                  'direccion_residencia' => $resultadoContacto[0]['direccion_residencia'],
                                  'correo' => $resultadoContacto[0]['correo'],
                                  'correo_secundario' => $resultadoContacto[0]['correo_secundario'],
                                  'telefono'  => $resultadoContacto[0]['telefono'],
                                  'celular' => $resultadoContacto[0]['celular'],
                               );
            $consecutivo_persona=$resultadoContacto[0]['consecutivo_persona'];
            $tipo_dato='datosContacto';
            $nombre_soporte=[];
            $consecutivo_dato=$resultadoContacto[0]['consecutivo_contacto'];
            $arregloDatos = json_encode ( $arregloDatos );
            $arregloCierre=array('consecutivo_inscrito'=>$parametro['inscripcion'],
                                 'tipo_dato'=>$tipo_dato,
                                 'consecutivo_dato'=>$consecutivo_dato,
                                 'fuente_dato'=>$parametro['tabla_ppal'],
                                 'valor_dato'=>$arregloDatos,
                                 'consecutivo_soporte'=>0,
                                 'alias_soporte'=>'',
                                 'nombre_soporte'=>'',
                                 'fecha_registro'=> $parametro['fecha_registro']) ;
            //se envian los datos para registro
            $this->cerrarDatos($arregloCierre, $esteRecursoDB);
        }
    }     

    function cerrarDatosFormacion($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.formacion';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoFormacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
        if($resultadoFormacion)
            {        
            foreach ($resultadoFormacion as $key => $value) 
                {   $arregloDatos = array('consecutivo_formacion' => $resultadoFormacion[$key]['consecutivo_formacion'],
                                          'consecutivo_persona' => $resultadoFormacion[$key]['consecutivo_persona'],
                                          'codigo_modalidad' => $resultadoFormacion[$key]['codigo_modalidad'],
                                          'modalidad' => $resultadoFormacion[$key]['modalidad'],
                                          'codigo_nivel' => $resultadoFormacion[$key]['codigo_nivel'],
                                          'nivel' => $resultadoFormacion[$key]['nivel'],
                                          'pais_formacion' => $resultadoFormacion[$key]['pais_formacion'],
                                          'pais' => $resultadoFormacion[$key]['pais'],
                                          'codigo_institucion' => $resultadoFormacion[$key]['codigo_institucion'],
                                          'nombre_institucion' => $resultadoFormacion[$key]['nombre_institucion'],
                                          'codigo_programa' => $resultadoFormacion[$key]['codigo_programa'],
                                          'nombre_programa' => $resultadoFormacion[$key]['nombre_programa'],
                                          'cursos_aprobados' => $resultadoFormacion[$key]['cursos_aprobados'],
                                          'graduado' => $resultadoFormacion[$key]['graduado'],
                                          'fecha_grado' => $resultadoFormacion[$key]['fecha_grado'],
                                          'promedio' => $resultadoFormacion[$key]['promedio'],
                                     );
                    //busca soportes cargados
                    $consecutivo_persona=$resultadoFormacion[$key]['consecutivo_persona'];
                    $tipo_dato='datosFormacion';
                    $nombre_soporte=['soporteDiploma','soporteTprofesional'];
                    $consecutivo_dato=$resultadoFormacion[$key]['consecutivo_formacion'];
                    //busca soportes por registro
                    $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                    if($resParametro)
                        {$arregloDatos['soportes']=array();
                         $arregloDatos['soportes']=$resParametro;
                        }
                    $arregloDatos = json_encode ( $arregloDatos );
                    $arregloCierre=array('consecutivo_inscrito'=>$parametro['inscripcion'],
                                         'tipo_dato'=>$tipo_dato,
                                         'consecutivo_dato'=>$consecutivo_dato,
                                         'fuente_dato'=>$parametro['tabla_ppal'],
                                         'valor_dato'=>$arregloDatos,
                                         'consecutivo_soporte'=>0,
                                         'alias_soporte'=>'',
                                         'nombre_soporte'=>'',
                                         'fecha_registro'=> $parametro['fecha_registro']) ;
                   //envia datos apara registro
                   $this->cerrarDatos($arregloCierre, $esteRecursoDB);
                   unset($arregloCierre);
                   unset($arregloDatos);
                }
            }    
    }    
    
    function cerrarDatosExperiencia($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.experiencia_laboral';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoExperiencia = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
        if($resultadoExperiencia)
            {
            foreach ($resultadoExperiencia as $key => $value) 
                {   $arregloDatos = array('consecutivo_experiencia' => $resultadoExperiencia[$key]['consecutivo_experiencia'],
                                          'consecutivo_persona' => $resultadoExperiencia[$key]['consecutivo_persona'],
                                          'codigo_nivel_experiencia' => $resultadoExperiencia[$key]['codigo_nivel_experiencia'],
                                          'nivel_experiencia' => $resultadoExperiencia[$key]['nivel_experiencia'],
                                          'pais_experiencia' => $resultadoExperiencia[$key]['pais_experiencia'],
                                          'pais' => $resultadoExperiencia[$key]['pais'],
                                          'codigo_nivel_institucion' => $resultadoExperiencia[$key]['codigo_nivel_institucion'],
                                          'nivel_institucion' => $resultadoExperiencia[$key]['nivel_institucion'],
                                          'codigo_institucion' => $resultadoExperiencia[$key]['codigo_institucion'],
                                          'nombre_institucion' => $resultadoExperiencia[$key]['nombre_institucion'],
                                          'direccion_institucion' => $resultadoExperiencia[$key]['direccion_institucion'],
                                          'correo_institucion' => $resultadoExperiencia[$key]['correo_institucion'],
                                          'telefono_institucion' => $resultadoExperiencia[$key]['telefono_institucion'],
                                          'cargo' => $resultadoExperiencia[$key]['cargo'],
                                          'descripcion_cargo' => $resultadoExperiencia[$key]['descripcion_cargo'],
                                          'actual' => $resultadoExperiencia[$key]['actual'],
                                          'fecha_inicio' => $resultadoExperiencia[$key]['fecha_inicio'],
                                          'fecha_fin' => $resultadoExperiencia[$key]['fecha_fin'],

                                     );
                    //busca soportes cargados
                    $consecutivo_persona=$resultadoExperiencia[$key]['consecutivo_persona'];
                    $tipo_dato='datosExperiencia';
                    $nombre_soporte=['soporteExperiencia'];
                    $consecutivo_dato=$resultadoExperiencia[$key]['consecutivo_experiencia'];
                    //busca soportes por registro
                    $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                    if($resParametro)
                        {$arregloDatos['soportes']=array();
                         $arregloDatos['soportes']=$resParametro;
                        }
                    $arregloDatos = json_encode ( $arregloDatos );
                    $arregloCierre=array('consecutivo_inscrito'=>$parametro['inscripcion'],
                                         'tipo_dato'=>$tipo_dato,
                                         'consecutivo_dato'=>$consecutivo_dato,
                                         'fuente_dato'=>$parametro['tabla_ppal'],
                                         'valor_dato'=>$arregloDatos,
                                         'consecutivo_soporte'=>0,
                                         'alias_soporte'=>'',
                                         'nombre_soporte'=>'',
                                         'fecha_registro'=> $parametro['fecha_registro']) ;
                   //envia datos apara registro
                   $this->cerrarDatos($arregloCierre, $esteRecursoDB);
                   unset($arregloCierre);
                   unset($arregloDatos);
                }
            }        
    }    
    
    function cerrarDatosDocencia($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.experiencia_docencia';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoDocencia = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
        if($resultadoDocencia)
            {
            foreach ($resultadoDocencia as $key => $value) 
                {   $arregloDatos = array('consecutivo_docencia' => $resultadoDocencia[$key]['consecutivo_docencia'],
                                      'consecutivo_persona' => $resultadoDocencia[$key]['consecutivo_persona'],
                                      'codigo_nivel_docencia' => $resultadoDocencia[$key]['codigo_nivel_docencia'],
                                      'nivel_docencia' => $resultadoDocencia[$key]['nivel_docencia'],
                                      'pais_docencia' => $resultadoDocencia[$key]['pais_docencia'],
                                      'pais' => $resultadoDocencia[$key]['pais'],
                                      'codigo_nivel_institucion' => $resultadoDocencia[$key]['codigo_nivel_institucion'],
                                      'nivel_institucion' => $resultadoDocencia[$key]['nivel_institucion'],
                                      'codigo_institucion' => $resultadoDocencia[$key]['codigo_institucion'],
                                      'nombre_institucion' => $resultadoDocencia[$key]['nombre_institucion'],
                                      'direccion_institucion' => $resultadoDocencia[$key]['direccion_institucion'],
                                      'correo_institucion' => $resultadoDocencia[$key]['correo_institucion'],
                                      'telefono_institucion' => $resultadoDocencia[$key]['telefono_institucion'],
                                      'codigo_vinculacion' => $resultadoDocencia[$key]['codigo_vinculacion'],
                                      'nombre_vinculacion' => $resultadoDocencia[$key]['nombre_vinculacion'],
                                      'descripcion_docencia' => $resultadoDocencia[$key]['descripcion_docencia'],
                                      'actual' => $resultadoDocencia[$key]['actual'],
                                      'fecha_inicio' => $resultadoDocencia[$key]['fecha_inicio'],
                                      'fecha_fin' => $resultadoDocencia[$key]['fecha_fin'],
                                      'horas_catedra' => $resultadoDocencia[$key]['horas_catedra'],
                                 );
                //busca soportes cargados
                $consecutivo_persona=$resultadoDocencia[$key]['consecutivo_persona'];
                $tipo_dato='datosDocencia';
                $nombre_soporte=['soporteDocencia'];
                $consecutivo_dato=$resultadoDocencia[$key]['consecutivo_docencia'];
                //busca soportes por registro
                $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                if($resParametro)
                    {$arregloDatos['soportes']=array();
                     $arregloDatos['soportes']=$resParametro;
                    }
                $arregloDatos = json_encode ( $arregloDatos );
                $arregloCierre=array('consecutivo_inscrito'=>$parametro['inscripcion'],
                                     'tipo_dato'=>$tipo_dato,
                                     'consecutivo_dato'=>$consecutivo_dato,
                                     'fuente_dato'=>$parametro['tabla_ppal'],
                                     'valor_dato'=>$arregloDatos,
                                     'consecutivo_soporte'=>0,
                                     'alias_soporte'=>'',
                                     'nombre_soporte'=>'',
                                     'fecha_registro'=> $parametro['fecha_registro']) ;
                //envia datos apara registro
               $this->cerrarDatos($arregloCierre, $esteRecursoDB);
               unset($arregloCierre);
               unset($arregloDatos);
            }
        }
    }    
    
    function cerrarDatosActividadAcad($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.actividad_academica';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoActividad = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
        if($resultadoActividad)
            {
            foreach ($resultadoActividad as $key => $value) 
                {   $arregloDatos = array('consecutivo_actividad' => $resultadoActividad[$key]['consecutivo_actividad'],
                                          'consecutivo_persona' => $resultadoActividad[$key]['consecutivo_persona'],
                                          'pais_actividad' => $resultadoActividad[$key]['pais_actividad'],
                                          'pais' => $resultadoActividad[$key]['pais'],
                                          'codigo_nivel_institucion' => $resultadoActividad[$key]['codigo_nivel_institucion'],
                                          'nivel_institucion' => $resultadoActividad[$key]['nivel_institucion'],
                                          'codigo_institucion' => $resultadoActividad[$key]['codigo_institucion'],
                                          'nombre_institucion' => $resultadoActividad[$key]['nombre_institucion'],
                                          'correo_institucion' => $resultadoActividad[$key]['correo_institucion'],
                                          'telefono_institucion' => $resultadoActividad[$key]['telefono_institucion'],
                                          'codigo_tipo_actividad' => $resultadoActividad[$key]['codigo_tipo_actividad'],
                                          'nombre_tipo_actividad' => $resultadoActividad[$key]['nombre_tipo_actividad'],
                                          'nombre_actividad' => $resultadoActividad[$key]['nombre_actividad'],
                                          'descripcion' => $resultadoActividad[$key]['descripcion'],
                                          'jefe_actividad' => $resultadoActividad[$key]['jefe_actividad'],
                                          'fecha_inicio' => $resultadoActividad[$key]['fecha_inicio'],
                                          'fecha_fin' => $resultadoActividad[$key]['fecha_fin'],
                                     );
                    //busca soportes cargados
                    $consecutivo_persona=$resultadoActividad[$key]['consecutivo_persona'];
                    $tipo_dato='datosActividad';
                    $nombre_soporte=['soporteActividad'];
                    $consecutivo_dato=$resultadoActividad[$key]['consecutivo_actividad'];
                    //busca soportes por registro
                    $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                    if($resParametro)
                        {$arregloDatos['soportes']=array();
                         $arregloDatos['soportes']=$resParametro;
                        }
                    $arregloDatos = json_encode ( $arregloDatos );
                    $arregloCierre=array('consecutivo_inscrito'=>$parametro['inscripcion'],
                                         'tipo_dato'=>$tipo_dato,
                                         'consecutivo_dato'=>$consecutivo_dato,
                                         'fuente_dato'=>$parametro['tabla_ppal'],
                                         'valor_dato'=>$arregloDatos,
                                         'consecutivo_soporte'=>0,
                                         'alias_soporte'=>'',
                                         'nombre_soporte'=>'',
                                         'fecha_registro'=> $parametro['fecha_registro']) ;
                   //envia datos apara registro
                   $this->cerrarDatos($arregloCierre, $esteRecursoDB);
                   unset($arregloCierre);
                   unset($arregloDatos);
                }
            }    
    }    
    
    function cerrarDatosInvestigacion($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.experiencia_investigacion';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoInvestigacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
        if($resultadoInvestigacion)
            {
            foreach ($resultadoInvestigacion as $key => $value) 
                {   $arregloDatos = array('consecutivo_investigacion' => $resultadoInvestigacion[$key]['consecutivo_investigacion'],
                                          'consecutivo_persona' => $resultadoInvestigacion[$key]['consecutivo_persona'],
                                          'pais_investigacion' => $resultadoInvestigacion[$key]['pais_investigacion'],
                                          'pais' => $resultadoInvestigacion[$key]['pais'],
                                          'codigo_nivel_institucion' => $resultadoInvestigacion[$key]['codigo_nivel_institucion'],
                                          'nivel_institucion' => $resultadoInvestigacion[$key]['nivel_institucion'],
                                          'codigo_institucion' => $resultadoInvestigacion[$key]['codigo_institucion'],
                                          'nombre_institucion' => $resultadoInvestigacion[$key]['nombre_institucion'],
                                          'direccion_institucion' => $resultadoInvestigacion[$key]['direccion_institucion'],
                                          'correo_institucion' => $resultadoInvestigacion[$key]['correo_institucion'],
                                          'telefono_institucion' => $resultadoInvestigacion[$key]['telefono_institucion'],
                                          'titulo_investigacion' => $resultadoInvestigacion[$key]['titulo_investigacion'],
                                          'jefe_investigacion' => $resultadoInvestigacion[$key]['jefe_investigacion'],
                                          'descripcion_investigacion' => $resultadoInvestigacion[$key]['descripcion_investigacion'],
                                          'direccion_investigacion' => $resultadoInvestigacion[$key]['direccion_investigacion'],
                                          'actual' => $resultadoInvestigacion[$key]['actual'],
                                          'fecha_inicio' => $resultadoInvestigacion[$key]['fecha_inicio'],
                                          'fecha_fin' => $resultadoInvestigacion[$key]['fecha_fin'],
                                          'grupo_investigacion' => $resultadoInvestigacion[$key]['grupo_investigacion'],
                                          'categoria_grupo' => $resultadoInvestigacion[$key]['categoria_grupo'],
                                     );
                    //busca soportes cargados
                    $consecutivo_persona=$resultadoInvestigacion[$key]['consecutivo_persona'];
                    $tipo_dato='datosInvestigacion';
                    $nombre_soporte=['soporteInvestigacion'];
                    $consecutivo_dato=$resultadoInvestigacion[$key]['consecutivo_investigacion'];
                    //busca soportes por registro
                    $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                    if($resParametro)
                        {$arregloDatos['soportes']=array();
                         $arregloDatos['soportes']=$resParametro;
                        }
                    $arregloDatos = json_encode ( $arregloDatos );
                    $arregloCierre=array('consecutivo_inscrito'=>$parametro['inscripcion'],
                                         'tipo_dato'=>$tipo_dato,
                                         'consecutivo_dato'=>$consecutivo_dato,
                                         'fuente_dato'=>$parametro['tabla_ppal'],
                                         'valor_dato'=>$arregloDatos,
                                         'consecutivo_soporte'=>0,
                                         'alias_soporte'=>'',
                                         'nombre_soporte'=>'',
                                         'fecha_registro'=> $parametro['fecha_registro']) ;
                   //envia datos apara registro
                   $this->cerrarDatos($arregloCierre, $esteRecursoDB);
                   unset($arregloCierre);
                   unset($arregloDatos);
                }
            }        
    }    
    
    function cerrarDatosProduccion($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.produccion_academica';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoProduccion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
        if($resultadoProduccion)
            {
            foreach ($resultadoProduccion as $key => $value) 
                {   $arregloDatos = array('consecutivo_produccion' => $resultadoProduccion[$key]['consecutivo_produccion'],
                                      'consecutivo_persona' => $resultadoProduccion[$key]['consecutivo_persona'],
                                      'codigo_tipo_produccion' => $resultadoProduccion[$key]['codigo_tipo_produccion'],
                                      'nombre_tipo_produccion' => $resultadoProduccion[$key]['nombre_tipo_produccion'],
                                      'titulo_produccion' => $resultadoProduccion[$key]['titulo_produccion'],
                                      'nombre_autor' => $resultadoProduccion[$key]['nombre_autor'],
                                      'nombre_producto_incluye' => $resultadoProduccion[$key]['nombre_producto_incluye'],
                                      'nombre_editorial' => $resultadoProduccion[$key]['nombre_editorial'],
                                      'volumen' => $resultadoProduccion[$key]['volumen'],
                                      'pagina' => $resultadoProduccion[$key]['pagina'],
                                      'codigo_isbn' => $resultadoProduccion[$key]['codigo_isbn'],
                                      'codigo_issn' => $resultadoProduccion[$key]['codigo_issn'],
                                      'indexado' => $resultadoProduccion[$key]['indexado'],
                                      'pais_produccion' => $resultadoProduccion[$key]['pais_produccion'],
                                      'departamento_produccion' => $resultadoProduccion[$key]['departamento_produccion'],
                                      'ciudad_produccion' => $resultadoProduccion[$key]['ciudad_produccion'],
                                      'ciudad' => $resultadoProduccion[$key]['ciudad'],
                                      'descripcion' => $resultadoProduccion[$key]['descripcion'],
                                      'direccion_produccion' => $resultadoProduccion[$key]['direccion_produccion'],
                                      'fecha_produccion' => $resultadoProduccion[$key]['fecha_produccion'],
                                 );
                //busca soportes cargados
                $consecutivo_persona=$resultadoProduccion[$key]['consecutivo_persona'];
                $tipo_dato='datosProduccion';
                $nombre_soporte=['soporteProduccion'];
                $consecutivo_dato=$resultadoProduccion[$key]['consecutivo_produccion'];
                //busca soportes por registro
                $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                if($resParametro)
                    {$arregloDatos['soportes']=array();
                     $arregloDatos['soportes']=$resParametro;
                    }
                $arregloDatos = json_encode ( $arregloDatos );
                $arregloCierre=array('consecutivo_inscrito'=>$parametro['inscripcion'],
                                     'tipo_dato'=>$tipo_dato,
                                     'consecutivo_dato'=>$consecutivo_dato,
                                     'fuente_dato'=>$parametro['tabla_ppal'],
                                     'valor_dato'=>$arregloDatos,
                                     'consecutivo_soporte'=>0,
                                     'alias_soporte'=>'',
                                     'nombre_soporte'=>'',
                                     'fecha_registro'=> $parametro['fecha_registro']) ;
               //envia datos apara registro
               $this->cerrarDatos($arregloCierre, $esteRecursoDB);
               unset($arregloCierre);
               unset($arregloDatos);
            }
        }
    }    
    
    function cerrarDatosIdioma($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.conocimiento_idioma';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoIdioma = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        if($resultadoIdioma)
            {
            //recorre los registros de formación encontrados
            foreach ($resultadoIdioma as $key => $value) 
                {   $arregloDatos = array('consecutivo_conocimiento' => $resultadoIdioma[$key]['consecutivo_conocimiento'],
                                      'consecutivo_persona' => $resultadoIdioma[$key]['consecutivo_persona'],
                                      'codigo_idioma' => $resultadoIdioma[$key]['codigo_idioma'],
                                      'idioma' => $resultadoIdioma[$key]['idioma'],
                                      'certificacion' => $resultadoIdioma[$key]['certificacion'],
                                      'institucion_certificacion' => $resultadoIdioma[$key]['institucion_certificacion'],
                                 );
                //busca soportes cargados
                $consecutivo_persona=$resultadoIdioma[$key]['consecutivo_persona'];
                $tipo_dato='datosIdioma';
                $nombre_soporte=['soporteIdioma'];
                $consecutivo_dato=$resultadoIdioma[$key]['consecutivo_conocimiento'];
                //busca soportes por registro
                $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                if($resParametro)
                    {$arregloDatos['soportes']=array();
                     $arregloDatos['soportes']=$resParametro;
                    }
                $arregloDatos = json_encode ( $arregloDatos );
                $arregloCierre=array('consecutivo_inscrito'=>$parametro['inscripcion'],
                                     'tipo_dato'=>$tipo_dato,
                                     'consecutivo_dato'=>$consecutivo_dato,
                                     'fuente_dato'=>$parametro['tabla_ppal'],
                                     'valor_dato'=>$arregloDatos,
                                     'consecutivo_soporte'=>0,
                                     'alias_soporte'=>'',
                                     'nombre_soporte'=>'',
                                     'fecha_registro'=> $parametro['fecha_registro']) ;
               //envia datos apara registro
               $this->cerrarDatos($arregloCierre, $esteRecursoDB);
               unset($arregloCierre);
               unset($arregloDatos);
            }
        }    
    }    

    function pasaFase($parametro,$esteRecursoDB) {
        //busca datos registrados
        $arregloDatos = array('consecutivo_inscrito' => $parametro['inscripcion'],
                                'consecutivo_calendario' => $parametro['faseNueva'],
                                'observacion' => 'Cierre automatico fase Inscripción y registro de soportes',
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
                               'cierre' => 'final',
                              );        
        $this->cadena_sql = $this->miSql->getCadenaSql("actualizaCierreCalendario", $arregloCierre);
        $resultadoCierre = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "actualiza", $arregloCierre, "actualizaCierreCalendarioSoporte" );
    }       
}

$miRegistrador = new cerrarSoporteConcurso($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>