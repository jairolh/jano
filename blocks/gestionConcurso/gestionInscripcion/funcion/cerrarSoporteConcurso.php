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
        //consulta inscritos al concurso
        $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                         'fecha_registro'=>date("Y-m-d H:m:s"));    
        $cadena_sql = $this->miSql->getCadenaSql("consultarInscritoConcurso", $parametro);
        $resultadoListaInscrito = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        // var_dump($resultadoListaInscrito);
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
            
        }    
            
            
            
            
        exit;
        
        
        $arregloDatos = array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                              'nombre'=>$_REQUEST['nombre'],
                              'estado'=>$_REQUEST['estadoConcurso']
            );
        $this->cadena_sql = $this->miSql->getCadenaSql("actualizaEstadoConcurso", $arregloDatos);
        $resultadoEstado = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "actualiza", $arregloDatos, "actualizaEstadoConcurso" );
	if($resultadoEstado)
            {redireccion::redireccionar($_REQUEST['opcion'].'Concurso',$_REQUEST['nombre']);
            }
        else
            {redireccion::redireccionar('no'.$_REQUEST['opcion'].'Concurso',$_REQUEST['nombre']);
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
    
    function cerrarDatos($datosCierre,$esteRecursoDB) {
        foreach ($datosCierre as $clave => $valor) {
            $arregloDatos=array('consecutivo_inscrito'=>$datosCierre[$clave]['consecutivo_inscrito'],
                                'tipo_dato'=>$datosCierre[$clave]['tipo_dato'],
                                'consecutivo_dato'=>$datosCierre[$clave]['consecutivo_dato'],
                                'fuente_dato'=>$datosCierre[$clave]['fuente_dato'],
                                'valor_dato'=>$datosCierre[$clave]['valor_dato'],
                                'consecutivo_soporte'=>$datosCierre[$clave]['consecutivo_soporte'],
                                'alias_soporte'=>$datosCierre[$clave]['alias_soporte'],
                                'nombre_soporte'=>$datosCierre[$clave]['nombre_soporte'],
                                'fecha_registro'=>$datosCierre[$clave]['fecha_registro'],
                            );
            echo $this->cadena_sql = $this->miSql->getCadenaSql("registroSoporteConcurso", $arregloDatos);
            //exit;
            $resultadoSoporte = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "registro", $arregloDatos, "registroCierreSoporteConcurso" );
            }
        
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
        $arregloDatos = json_encode ( $arregloDatos );
        //busca soportes cargados
        $parametroSop = array(  'consecutivo'=>$resultadoPersona[0]['consecutivo'],
                                'tipo_dato'=>'datosBasicos',
                                'nombre_soporte'=>'foto');
        $cadenaSopFoto_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
        $resultadoSopFoto = $esteRecursoDB->ejecutarAcceso($cadenaSopFoto_sql, "busqueda");                    
        $parametroSop['nombre_soporte']='soporteIdentificacion';
        $cadenaSopIden_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
        $resultadoSopIden = $esteRecursoDB->ejecutarAcceso($cadenaSopIden_sql, "busqueda");
        $aux=0;
        $arregloSoporte[$aux]['consecutivo_soporte']=0;
        $arregloSoporte[$aux]['alias_soporte']='';
        $arregloSoporte[$aux]['nombre_soporte']='';
        if($resultadoSopFoto)
            {   $arregloSoporte[$aux]['consecutivo_soporte']=$resultadoSopFoto[0]['consecutivo_soporte'];
                $arregloSoporte[$aux]['alias_soporte']=$resultadoSopFoto[0]['alias'];
                $arregloSoporte[$aux]['nombre_soporte']=$resultadoSopFoto[0]['ubicacion']."/".$resultadoSopFoto[0]['archivo'];
            }
        if($resultadoSopIden)
            {
            if($resultadoSopFoto){$aux++;}
                $arregloSoporte[$aux]['consecutivo_soporte']=$resultadoSopIden[0]['consecutivo_soporte'];
                $arregloSoporte[$aux]['alias_soporte']=$resultadoSopIden[0]['alias'];
                $arregloSoporte[$aux]['nombre_soporte']=$resultadoSopIden[0]['ubicacion']."/".$resultadoSopIden[0]['archivo'];
            }
        //preparando cadena de datos para insertar
        for($i=0;$i<=$aux;$i++)
            {
                $arregloCierre[$i]['consecutivo_inscrito']=$parametro['inscripcion'];
                $arregloCierre[$i]['tipo_dato']='datosBasicos';
                $arregloCierre[$i]['consecutivo_dato']=$resultadoPersona[0]['consecutivo'];
                $arregloCierre[$i]['fuente_dato']=$parametro['tabla_ppal'];
                $arregloCierre[$i]['valor_dato']=$arregloDatos;
                $arregloCierre[$i]['consecutivo_soporte']=$arregloSoporte[$i]['consecutivo_soporte'];
                $arregloCierre[$i]['alias_soporte']=$arregloSoporte[$i]['alias_soporte'];
                $arregloCierre[$i]['nombre_soporte']=$arregloSoporte[$i]['nombre_soporte'];
                $arregloCierre[$i]['fecha_registro']= $parametro['fecha_registro'];
            }
            //se envian los datos para registro
            $this->cerrarDatos($arregloCierre, $esteRecursoDB);
    }    

    function cerrarDatosContacto($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.contacto';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoContacto = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
               
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
        $arregloDatos = json_encode ( $arregloDatos );
        $aux=0;
        $arregloSoporte[$aux]['consecutivo_soporte']=0;
        $arregloSoporte[$aux]['alias_soporte']='';
        $arregloSoporte[$aux]['nombre_soporte']='';
        //preparando cadena de datos para insertar
        for($i=0;$i<=$aux;$i++)
            {
                $arregloCierre[$i]['consecutivo_inscrito']=$parametro['inscripcion'];
                $arregloCierre[$i]['tipo_dato']='datoscontacto';
                $arregloCierre[$i]['consecutivo_dato']=$resultadoContacto[0]['consecutivo_contacto'];
                $arregloCierre[$i]['fuente_dato']=$parametro['tabla_ppal'];
                $arregloCierre[$i]['valor_dato']=$arregloDatos;
                $arregloCierre[$i]['consecutivo_soporte']=$arregloSoporte[$i]['consecutivo_soporte'];
                $arregloCierre[$i]['alias_soporte']=$arregloSoporte[$i]['alias_soporte'];
                $arregloCierre[$i]['nombre_soporte']=$arregloSoporte[$i]['nombre_soporte'];
                $arregloCierre[$i]['fecha_registro']= $parametro['fecha_registro'];
            }
            //se envian los datos para registro
            $this->cerrarDatos($arregloCierre, $esteRecursoDB);
    }     

    function cerrarDatosFormacion($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.formacion';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoFormacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
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
                $arregloDatos = json_encode ( $arregloDatos );
                //busca soportes cargados
                $consecutivo_persona=$resultadoFormacion[$key]['consecutivo_persona'];
                $tipo_dato='datosFormacion';
                $nombre_soporte=['soporteDiploma','soporteTprofesional'];
                $consecutivo_dato=$resultadoFormacion[$key]['consecutivo_formacion'];
                //busca soportes por registro
                $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                foreach ($resParametro as $llave => $valor) {
                    $arregloCierre[$llave]['consecutivo_inscrito']=$parametro['inscripcion'];
                    $arregloCierre[$llave]['tipo_dato']=$tipo_dato;
                    $arregloCierre[$llave]['consecutivo_dato']=$consecutivo_dato;
                    $arregloCierre[$llave]['fuente_dato']=$parametro['tabla_ppal'];
                    $arregloCierre[$llave]['valor_dato']=$arregloDatos;
                    $arregloCierre[$llave]['consecutivo_soporte']=$resParametro[$llave]['consecutivo_soporte'];
                    $arregloCierre[$llave]['alias_soporte']=$resParametro[$llave]['alias_soporte'];
                    $arregloCierre[$llave]['nombre_soporte']=$resParametro[$llave]['nombre_soporte'];
                    $arregloCierre[$llave]['fecha_registro']= $parametro['fecha_registro'];
                }
               //envia datos apara registro
               $this->cerrarDatos($arregloCierre, $esteRecursoDB);
               unset($arregloCierre);
               unset($arregloDatos);
            }
    }    
    
    function cerrarDatosExperiencia($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.experiencia_laboral';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoExperiencia = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
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
                $arregloDatos = json_encode ( $arregloDatos );
                //busca soportes cargados
                $consecutivo_persona=$resultadoExperiencia[$key]['consecutivo_persona'];
                $tipo_dato='datosExperiencia';
                $nombre_soporte=['soporteExperiencia'];
                $consecutivo_dato=$resultadoExperiencia[$key]['consecutivo_experiencia'];
                //busca soportes por registro
                $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                foreach ($resParametro as $llave => $valor) {
                    $arregloCierre[$llave]['consecutivo_inscrito']=$parametro['inscripcion'];
                    $arregloCierre[$llave]['tipo_dato']=$tipo_dato;
                    $arregloCierre[$llave]['consecutivo_dato']=$consecutivo_dato;
                    $arregloCierre[$llave]['fuente_dato']=$parametro['tabla_ppal'];
                    $arregloCierre[$llave]['valor_dato']=$arregloDatos;
                    $arregloCierre[$llave]['consecutivo_soporte']=$resParametro[$llave]['consecutivo_soporte'];
                    $arregloCierre[$llave]['alias_soporte']=$resParametro[$llave]['alias_soporte'];
                    $arregloCierre[$llave]['nombre_soporte']=$resParametro[$llave]['nombre_soporte'];
                    $arregloCierre[$llave]['fecha_registro']= $parametro['fecha_registro'];
                }
               //envia datos apara registro
               $this->cerrarDatos($arregloCierre, $esteRecursoDB);
               unset($arregloCierre);
               unset($arregloDatos);
            }
    }    
    
    function cerrarDatosDocencia($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.experiencia_docencia';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoDocencia = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
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
                                 );
                $arregloDatos = json_encode ( $arregloDatos );
                //busca soportes cargados
                $consecutivo_persona=$resultadoDocencia[$key]['consecutivo_persona'];
                $tipo_dato='datosDocencia';
                $nombre_soporte=['soporteDocencia'];
                $consecutivo_dato=$resultadoDocencia[$key]['consecutivo_docencia'];
                //busca soportes por registro
                $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                foreach ($resParametro as $llave => $valor) {
                    $arregloCierre[$llave]['consecutivo_inscrito']=$parametro['inscripcion'];
                    $arregloCierre[$llave]['tipo_dato']=$tipo_dato;
                    $arregloCierre[$llave]['consecutivo_dato']=$consecutivo_dato;
                    $arregloCierre[$llave]['fuente_dato']=$parametro['tabla_ppal'];
                    $arregloCierre[$llave]['valor_dato']=$arregloDatos;
                    $arregloCierre[$llave]['consecutivo_soporte']=$resParametro[$llave]['consecutivo_soporte'];
                    $arregloCierre[$llave]['alias_soporte']=$resParametro[$llave]['alias_soporte'];
                    $arregloCierre[$llave]['nombre_soporte']=$resParametro[$llave]['nombre_soporte'];
                    $arregloCierre[$llave]['fecha_registro']= $parametro['fecha_registro'];
                }
               //envia datos apara registro
               $this->cerrarDatos($arregloCierre, $esteRecursoDB);
               unset($arregloCierre);
               unset($arregloDatos);
            }
    }    
    
    function cerrarDatosActividadAcad($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.actividad_academica';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoActividad = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
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
                $arregloDatos = json_encode ( $arregloDatos );
                //busca soportes cargados
                $consecutivo_persona=$resultadoActividad[$key]['consecutivo_persona'];
                $tipo_dato='datosActividad';
                $nombre_soporte=['soporteActividad'];
                $consecutivo_dato=$resultadoActividad[$key]['consecutivo_actividad'];
                //busca soportes por registro
                $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                foreach ($resParametro as $llave => $valor) {
                    $arregloCierre[$llave]['consecutivo_inscrito']=$parametro['inscripcion'];
                    $arregloCierre[$llave]['tipo_dato']=$tipo_dato;
                    $arregloCierre[$llave]['consecutivo_dato']=$consecutivo_dato;
                    $arregloCierre[$llave]['fuente_dato']=$parametro['tabla_ppal'];
                    $arregloCierre[$llave]['valor_dato']=$arregloDatos;
                    $arregloCierre[$llave]['consecutivo_soporte']=$resParametro[$llave]['consecutivo_soporte'];
                    $arregloCierre[$llave]['alias_soporte']=$resParametro[$llave]['alias_soporte'];
                    $arregloCierre[$llave]['nombre_soporte']=$resParametro[$llave]['nombre_soporte'];
                    $arregloCierre[$llave]['fecha_registro']= $parametro['fecha_registro'];
                }
               //envia datos apara registro
               $this->cerrarDatos($arregloCierre, $esteRecursoDB);
               unset($arregloCierre);
               unset($arregloDatos);
            }
    }    
    
    function cerrarDatosInvestigacion($parametro,$esteRecursoDB) {
        //busca datos registrados
        $parametro['tabla_ppal']='concurso.experiencia_investigacion';
        $cadena_sql = $this->miSql->getCadenaSql($parametro['tabla_ppal'], $parametro);
        $resultadoInvestigacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        //recorre los registros de formación encontrados
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
                $arregloDatos = json_encode ( $arregloDatos );
                //busca soportes cargados
                $consecutivo_persona=$resultadoInvestigacion[$key]['consecutivo_persona'];
                $tipo_dato='datosInvestigacion';
                $nombre_soporte=['soporteInvestigacion'];
                $consecutivo_dato=$resultadoInvestigacion[$key]['consecutivo_investigacion'];
                //busca soportes por registro
                $resParametro= $this->buscarSoporte($consecutivo_persona,$tipo_dato,$nombre_soporte,$consecutivo_dato,$esteRecursoDB);
                foreach ($resParametro as $llave => $valor) {
                    $arregloCierre[$llave]['consecutivo_inscrito']=$parametro['inscripcion'];
                    $arregloCierre[$llave]['tipo_dato']=$tipo_dato;
                    $arregloCierre[$llave]['consecutivo_dato']=$consecutivo_dato;
                    $arregloCierre[$llave]['fuente_dato']=$parametro['tabla_ppal'];
                    $arregloCierre[$llave]['valor_dato']=$arregloDatos;
                    $arregloCierre[$llave]['consecutivo_soporte']=$resParametro[$llave]['consecutivo_soporte'];
                    $arregloCierre[$llave]['alias_soporte']=$resParametro[$llave]['alias_soporte'];
                    $arregloCierre[$llave]['nombre_soporte']=$resParametro[$llave]['nombre_soporte'];
                    $arregloCierre[$llave]['fecha_registro']= $parametro['fecha_registro'];
                }
               //envia datos apara registro
               $this->cerrarDatos($arregloCierre, $esteRecursoDB);
               unset($arregloCierre);
               unset($arregloDatos);
            }
    }    
    
    
}

$miRegistrador = new cerrarSoporteConcurso($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>