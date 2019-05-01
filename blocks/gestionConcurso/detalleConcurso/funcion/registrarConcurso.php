<?php
namespace gestionConcurso\detalleConcurso\funcion;
use gestionConcurso\detalleConcurso\funcion\redireccion;
include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorConcurso {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;
    var $miLogger;
    var $miArchivo;

    function __construct($lenguaje, $sql, $funcion, $miLogger,$miArchivo) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
        $this->miLogger= $miLogger;
        $this->miArchivo = $miArchivo;
    }

    function procesarFormulario() {
        $conexion="estructura";
	$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $arregloDatos = array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso'],
                              'consecutivo_persona'=>!isset($_REQUEST['consecutivo_persona'])?0:$_REQUEST['consecutivo_persona'],
                              'codigo_tipo'=>$_REQUEST['tipo'],
                              'codigo_modalidad'=>$_REQUEST['modalidad'],
                              'nombre'=>$_REQUEST['nombre'],
                              'acuerdo'=>$_REQUEST['acuerdo'],
                              'descripcion'=>$_REQUEST['descripcion'],
                              'fecha_inicio_concurso'=>$_REQUEST['fecha_inicio_concurso'],
                              'fecha_fin_concurso'=>$_REQUEST['fecha_fin_concurso'],
                              'estado'=>isset($_REQUEST['estado'])?$_REQUEST['estado']:'',
                              'maximo_puntos'=>$_REQUEST['maximo_puntos_conc'],
                              'porcentaje_aprueba'=>$_REQUEST['porc_aprueba_conc'],
                              'max_inscribe_aspirante'=>$_REQUEST['max_inscribe_aspirante'],
            );
        
        
        if($arregloDatos['consecutivo_concurso']==0)
             {  
                $parametro=array('tipo_nivel'=> 'TipoConcurso','codigo_nivel'=>$_REQUEST['tipo']);
                $cadena_sql = $this->miSql->getCadenaSql("consultarNivel", $parametro);
                $resultadoNivel = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                //Genera el codigo del concurso
                $codigo=date('Y').strtoupper(substr($resultadoNivel[0]['nombre'],0,3));
                $parametroCod=array('codigo'=>$codigo);
                $cadena_sql = $this->miSql->getCadenaSql("consultaCodigoConcurso", $parametroCod);
                $resultadoCod = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                $codigo.=str_pad(($resultadoCod[0]['secuencia']>0?$resultadoCod[0]['secuencia']:1),  3, "0",STR_PAD_LEFT);
                $arregloDatos['codigo']=$codigo;
                
                $cadenaSql = $this->miSql->getCadenaSql ( 'registroConcurso',$arregloDatos );
                $resultadoConcurso = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $arregloDatos, "registroConcurso" );
                $_REQUEST['consecutivo_concurso']=$resultadoConcurso;
            
                $cadenaSql = $this->miSql->getCadenaSql ( 'consultaActividadObligatoria',$arregloDatos );
                $resultadoActividad = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
                foreach ($resultadoActividad as $key => $value) {
                    $datosCalendario=array('consecutivo_concurso'=> $resultadoConcurso,
                                           'consecutivo_actividad'=>$resultadoActividad[$key]['consecutivo_actividad'] ,
                                           'descripcion'=> 'Espacio tiempo para '.$resultadoActividad[$key]['descripcion'] ,
                                           'fecha_inicio'=> $_REQUEST['fecha_inicio_concurso'],
                                           'fecha_fin'=> $_REQUEST['fecha_fin_concurso'],
                                           'fecha_fin_reclamacion'=>'',
                                           'fecha_fin_resolver'=>'',
                                           'consecutivo_evaluar'=> 0,
                                           'porcentaje_aprueba'=>0
                                        );
                    $cadenaSql = $this->miSql->getCadenaSql ('registroCalendarioConcurso',$datosCalendario);
                    $resultadoCalendario = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "registra", $datosCalendario, "registroCalendarioConcurso" );
                }
                
             }
        else {  $cadenaSql = $this->miSql->getCadenaSql ( 'actualizaConcurso',$arregloDatos );
                $resultadoConcurso = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "actualiza", $arregloDatos, "actualizarConcurso" );
        }
        
        if($resultadoConcurso)
            {   $datosSoporte=array('consecutivo_persona'=>0,
                                    'consecutivo_dato'=>$_REQUEST['consecutivo_concurso'],
                                    'id_usuario'=>$_REQUEST['id_usuario']);
                $this->miArchivo->procesarArchivo($datosSoporte);                
                
                redireccion::redireccionar('actualizoConcurso',$arregloDatos);  exit();
            }else
            {
                redireccion::redireccionar('noActualizo',$arregloDatos);  exit();
            }
  
    }

    function resetForm() {
        foreach ($_REQUEST as $clave => $valor) {

            if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
                unset($_REQUEST [$clave]);
            }
        }
    }

}

$miRegistrador = new RegistradorConcurso($this->lenguaje, $this->sql, $this->funcion,$this->miLogger,$this->miArchivo);
$resultado = $miRegistrador->procesarFormulario();
?>