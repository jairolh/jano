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
            //$this->cerrarDatosBasicos($parametro,$esteRecursoDB);
            $this->cerrarDatosContacto($parametro,$esteRecursoDB);
            
            
            
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
        $arregloSoporte[$aux]['consecutivo_soporte']='';
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
                           var_dump($arregloDatos);
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

}

$miRegistrador = new cerrarSoporteConcurso($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>