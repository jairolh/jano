<?php

namespace gestionConcurso\gestionInscripcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

// Para evitar redefinic	iones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
// en camel case precedida por la palabra sql
class Sql extends \Sql {
	var $miConfigurador;
	function __construct() {
		$this->miConfigurador = \Configurador::singleton ();
	}
	function getCadenaSql($tipo, $variable = "") {

		/**
		 * 1.
		 * Revisar las variables para evitar SQL Injection
		 */
		$prefijo = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
		$idSesion = $this->miConfigurador->getVariableConfiguracion ( "id_sesion" );
		$cadenaSql='';
		switch ($tipo) {

			/**
			 * Clausulas específicas
			 */
      case "idioma":
				$cadenaSql = "SET lc_time_names = 'es_ES' ";
			break;

			case 'consultaTipoInterno' :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" jt.id";
				$cadenaSql.=" FROM concurso.jurado_tipo jt";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" jt.nombre ='Interno'";
				break;

			case 'consultaJurado3' :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" ji.id_inscrito, ji.id_jurado_tipo";
				$cadenaSql.=" FROM concurso.jurado_inscrito ji, concurso.jurado_tipo jt, concurso.concurso_inscrito ci, concurso.concurso_perfil cp, concurso.persona p";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" id_usuario='".$variable['usuario']."'";
				$cadenaSql.=" AND ci.consecutivo_inscrito=ji.id_inscrito";
				$cadenaSql.=" AND cp.consecutivo_perfil= ci.consecutivo_perfil";
				//concurso
				$cadenaSql.=" AND cp.consecutivo_concurso=".$variable['concurso'];
				$cadenaSql.=" AND ci.consecutivo_persona= p.consecutivo";
				$cadenaSql.=" AND jt.id=ji.id_jurado_tipo";

				break;

			case 'consultaJurado2' :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" ji.id_inscrito AS Inscripción,  concat(p.tipo_identificacion, identificacion) AS Identificación, concat( p.nombre, ' ', p.apellido) AS Aspirante, cp.codigo AS Código, cp.nombre AS Perfil, jt.nombre AS tipo_jurado";
				$cadenaSql.=" FROM concurso.jurado_inscrito ji, concurso.jurado_tipo jt, concurso.concurso_inscrito ci, concurso.concurso_perfil cp, concurso.persona p";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" ji.id_usuario='".$variable['usuario']."'";
                                $cadenaSql.=" AND ji.id_jurado_rol='".$variable['rol']."'";
				$cadenaSql.=" AND ci.consecutivo_inscrito=ji.id_inscrito";
				$cadenaSql.=" AND cp.consecutivo_perfil= ci.consecutivo_perfil";
				//concurso
                                $cadenaSql.=" AND cp.consecutivo_concurso=".$variable['concurso'];
				$cadenaSql.=" AND ci.consecutivo_persona= p.consecutivo";
				$cadenaSql.=" AND jt.id=ji.id_jurado_tipo";

				break;

			case 'consultaJurado' :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" ji.id, ji.id_usuario, ji.id_inscrito, ji.id_jurado_tipo, ji.fecha_registro, ji.estado, jt.nombre AS tipo_jurado, ci.consecutivo_perfil, cp.consecutivo_concurso";
				$cadenaSql.=" FROM concurso.jurado_inscrito ji, concurso.jurado_tipo jt, concurso.concurso_inscrito ci, concurso.concurso_perfil cp";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" id_usuario='".$variable['usuario']."'";
				$cadenaSql.=" AND ci.consecutivo_inscrito=ji.id_inscrito";
				$cadenaSql.=" AND cp.consecutivo_perfil= ci.consecutivo_perfil";
				//concurso
				$cadenaSql.=" AND cp.consecutivo_concurso=".$variable['concurso'];
				$cadenaSql.=" AND jt.id=ji.id_jurado_tipo";

				break;

			case 'consultarJurados' :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" concat(r.rol_id,'-',u.id_usuario) AS codigo, concat( u.nombre, ' ', u.apellido, ' - ', r.rol_alias) AS nombre, u.id_usuario, r.rol_id, r.rol_nombre AS nombre_rol, r.rol_nombre ";
				$cadenaSql.=" FROM jano_usuario u, jano_rol r, jano_usuario_subsistema s";
				$cadenaSql.=" WHERE ";
                                $cadenaSql.=" s.estado='1' AND";
				$cadenaSql.=" s.id_usuario=u.id_usuario AND ";
				$cadenaSql.=" s.rol_id=r.rol_id AND";
				$cadenaSql.=" (UPPER(r.rol_alias)='JURADO')";
				break;

                        case 'consultarEvaluadores' :
                                $cadenaSql=" SELECT ";
                                $cadenaSql.=" concat(r.rol_id,'-',u.id_usuario) AS codigo, concat( u.nombre, ' ', u.apellido, ' - ', r.rol_alias) AS nombre, u.id_usuario, r.rol_id, r.rol_nombre AS nombre_rol, r.rol_nombre ";
                                $cadenaSql.=" FROM jano_usuario u, jano_rol r, jano_usuario_subsistema s";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" s.estado='1' AND";
                                $cadenaSql.=" s.id_usuario=u.id_usuario AND ";
                                $cadenaSql.=" s.rol_id=r.rol_id AND";
                                $cadenaSql.=" (UPPER(r.rol_alias)='DOCENCIA' OR UPPER(r.rol_alias)='PERSONAL' OR UPPER(r.rol_alias)='ILUD' )";
                                break;

			case 'consultarTiposJurado' :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" id, nombre, descripcion, estado ";
				$cadenaSql.=" FROM concurso.jurado_tipo";
				break;

                        case 'consultarAspirantesValidados' :
                                $cadenaSql=" SELECT ";
                                $cadenaSql.=" consecutivo, tipo_identificacion, identificacion, concat( p.nombre, ' ', p.apellido) AS nombre, ci.consecutivo_perfil, cp.nombre AS perfil, ci.consecutivo_inscrito ";
                                $cadenaSql.=" FROM concurso.concurso_inscrito ci, concurso.valida_requisito vr, concurso.persona p, concurso.concurso_perfil cp";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" p.consecutivo=ci.consecutivo_persona AND ";
                                $cadenaSql.=" ci.consecutivo_inscrito=vr.consecutivo_inscrito AND ";
                                $cadenaSql.=" cumple_requisito='SI' AND";
                                $cadenaSql.=" cp.consecutivo_perfil=ci.consecutivo_perfil AND";
                                $cadenaSql.=" consecutivo_concurso=".$variable['consecutivo_concurso'];
                                break;

                        case 'consultarAspirantesNoAsignados' :
                                $cadenaSql=" SELECT ";
                                $cadenaSql.=" consecutivo, tipo_identificacion, identificacion, concat( p.nombre, ' ', p.apellido) AS nombre, ci.consecutivo_perfil, cp.codigo, cp.nombre AS perfil, ci.consecutivo_inscrito ";
                                $cadenaSql.=" FROM concurso.concurso_inscrito ci, concurso.valida_requisito vr, concurso.persona p, concurso.concurso_perfil cp";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" p.consecutivo=ci.consecutivo_persona AND ";
                                $cadenaSql.=" ci.consecutivo_inscrito=vr.consecutivo_inscrito AND ";
                                $cadenaSql.=" cumple_requisito='SI' AND ";
                                $cadenaSql.=" cp.consecutivo_perfil=ci.consecutivo_perfil AND ";
                                $cadenaSql.=" consecutivo_concurso=".$variable['consecutivo_concurso'];
                                $cadenaSql.=" AND ci.consecutivo_inscrito NOT IN ";
                                $cadenaSql.="  (SELECT id_inscrito  ";
                                $cadenaSql.="    FROM concurso.jurado_inscrito ji  ";
                                $cadenaSql.="    WHERE id_usuario='".$variable['id_usuario']."' ";
                                $cadenaSql.="    AND id_jurado_rol='".$variable['rol']."' ";
                                $cadenaSql.=" )";
                                
                                break;
			case 'buscarTipoSoporte' :
				$cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" tipo_soporte,";
                                $cadenaSql.=" nombre,";
                                $cadenaSql.=" ubicacion,";
                                $cadenaSql.=" descripcion,";
                                $cadenaSql.=" extencion_permitida,";
                                $cadenaSql.=" tamanno_permitido,";
                                $cadenaSql.=" dato_relaciona,";
                                $cadenaSql.=" alias,";
                                $cadenaSql.=" validacion,";
                                $cadenaSql.=" posicion,";
                                $cadenaSql.=" estado ";
                                $cadenaSql.=" FROM ".$this->miConfigurador->getVariableConfiguracion ("esquemaTipoSoporte").".tipo_soporte";
                                $cadenaSql.=" WHERE ";
				$cadenaSql.=" estado='A' ";
                                $cadenaSql.=" AND dato_relaciona = '".$variable['dato_relaciona']."'";
                                if(isset($variable['tipo_soporte']) && $variable['tipo_soporte']!='')
                                    {$cadenaSql.=" AND nombre = '".$variable['tipo_soporte']."'";}
                                $cadenaSql.=" ORDER BY dato_relaciona, posicion ASC, alias ASC";    
                            break;
                                                
                  
			case 'buscarSoporte' :
				$cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" sop.consecutivo_soporte,";
                                $cadenaSql.=" sop.consecutivo_persona,";
                                $cadenaSql.=" sop.tipo_dato, ";
                                $cadenaSql.=" sop.consecutivo_dato,";
                                $cadenaSql.=" sop.nombre archivo,";
                                $cadenaSql.=" sop.alias,";
                                $cadenaSql.=" tsop.tipo_soporte,";
                                $cadenaSql.=" tsop.nombre, ";
                                $cadenaSql.=" tsop.ubicacion";
                                $cadenaSql.=" FROM ".$this->miConfigurador->getVariableConfiguracion ("esquemaSoporte").".soporte sop";
                                $cadenaSql.=" INNER JOIN ".$this->miConfigurador->getVariableConfiguracion ("esquemaTipoSoporte").".tipo_soporte tsop";
                                $cadenaSql.=" ON tsop.tipo_soporte=sop.tipo_soporte";
                                $cadenaSql.=" AND tsop.estado=sop.estado";
                                $cadenaSql.=" WHERE";
                                $cadenaSql.=" tsop.estado='A' ";
                                $cadenaSql.=" AND sop.tipo_dato='".$variable['tipo_dato']."'";
                                $cadenaSql.=" AND sop.consecutivo_persona='".$variable['consecutivo_persona']."'";
                                $cadenaSql.=" AND tsop.nombre='".$variable['nombre_soporte']."'";
                                if(isset($variable['consecutivo_dato']) && $variable['consecutivo_dato']!='')
                                    {$cadenaSql.=" AND sop.consecutivo_dato='".$variable['consecutivo_dato']."' ";}
                                $cadenaSql.=" ORDER BY sop.consecutivo_soporte DESC ";
                            break; 
                        case "consultarNivel":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" codigo_nivel,";
                                $cadenaSql.=" nombre,";
                                $cadenaSql.=" tipo_nivel,";
                                $cadenaSql.=" descripcion,";
                                $cadenaSql.=" estado";
                                $cadenaSql.=" FROM general.nivel";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" estado='A'";
                                if(isset($variable['tipo_nivel']) && $variable['tipo_nivel']!='')
                                    {$cadenaSql.=" AND tipo_nivel='".$variable['tipo_nivel']."' ";}
                                if(isset($variable['codigo_nivel']) && $variable['codigo_nivel']>0)
                                    {$cadenaSql.=" AND codigo_nivel='".$variable['codigo_nivel']."' ";}
                                if(isset($variable['nombre']) && $variable['nombre']!='')
                                    {$cadenaSql.=" AND lower(nombre) LIKE lower('".$variable['nombre']."') ";}
                                if(isset($variable['add_otro']) && $variable['add_otro']=='SI')
                                    {   $cadenaSql.=" UNION ";
                                        $cadenaSql.=" SELECT DISTINCT ";
                                        $cadenaSql.=" 0 codigo_nivel, ";
                                        $cadenaSql.=" 'OTRO' nombre, ";
                                        $cadenaSql.=" 'OTRO' tipo_nivel, ";
                                        $cadenaSql.=" 'OTRO' descripcion, ";
                                        $cadenaSql.=" 'A' estado";
                                        $cadenaSql.=" FROM general.nivel";
                                    }
                                if(isset($variable['order']) && $variable['order']=='codigo')
                                     {$cadenaSql.=" ORDER BY codigo_nivel ";  }
                                else {$cadenaSql.=" ORDER BY nombre ";  }
                            break;
                        case "consultaConcurso":
                                $cadenaSql=" SELECT conc.consecutivo_concurso, ";
                                $cadenaSql.=" conc.consecutivo_modalidad,";
                                $cadenaSql.=" conc.codigo, ";
                                $cadenaSql.=" conc.nombre, ";
                                $cadenaSql.=" conc.acuerdo, ";
                                $cadenaSql.=" conc.descripcion,";
                                $cadenaSql.=" conc.fecha_inicio,";
                                $cadenaSql.=" conc.fecha_fin, ";
                                $cadenaSql.=" conc.maximo_puntos, ";
                                $cadenaSql.=" conc.porcentaje_aprueba,";
                                $cadenaSql.=" conc.max_inscribe_aspirante, ";
                                $cadenaSql.=" (CASE WHEN conc.estado='A' THEN 'Activo' ELSE 'Inactivo' END) estado, ";
                                $cadenaSql.=" mdl.nombre modalidad, ";
                                $cadenaSql.=" mdl.codigo_nivel_concurso,";
                                $cadenaSql.=" nvl.nombre nivel_concurso,";
                                $cadenaSql.=" ( SELECT count(insc.consecutivo_inscrito) inscrito";
                                $cadenaSql.=" FROM concurso.concurso_perfil prf";
                                $cadenaSql.=" INNER JOIN concurso.concurso_inscrito insc ON prf.consecutivo_perfil=insc.consecutivo_perfil";
                                $cadenaSql.=" WHERE prf.estado='A'";
                                $cadenaSql.=" AND prf.consecutivo_concurso=conc.consecutivo_concurso) inscritos";
                                $cadenaSql.=" FROM concurso.concurso conc ";
                                $cadenaSql.=" INNER JOIN concurso.modalidad_concurso mdl ON mdl.consecutivo_modalidad=conc.consecutivo_modalidad";
                                $cadenaSql.=" INNER JOIN general.nivel nvl ON nvl.tipo_nivel='TipoConcurso' AND nvl.codigo_nivel= mdl.codigo_nivel_concurso";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" conc.estado='A' ";
                                if(isset($variable['consecutivo_concurso']) &&  $variable['consecutivo_concurso']!='' )
                                   {$cadenaSql .= " AND conc.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                   }
                                if(isset($variable['tipo_concurso']) &&  $variable['tipo_concurso']!='' )
                                   {$cadenaSql.=" AND "; 
                                    $cadenaSql .= " nvl.nombre IN (".$variable['tipo_concurso'].") "; 
                                   }   
                                if(isset($variable['hoy']) &&  $variable['hoy']!='' )
                                   {$cadenaSql.=" AND conc.fecha_inicio <='".$variable['hoy']."' ";
                                    $cadenaSql.=" AND conc.fecha_fin>= '".$variable['hoy']."' ";
                                   }
                                $cadenaSql.=" ORDER BY ";
                                $cadenaSql.=" conc.fecha_inicio DESC, ";
                                $cadenaSql.=" conc.fecha_fin DESC ";
                            break;
                       case "consultarCalendarioConcurso":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" cal.consecutivo_calendario, ";
                                $cadenaSql.=" cal.consecutivo_concurso, ";
                                $cadenaSql.=" cal.consecutivo_actividad, ";
                                $cadenaSql.=" act.nombre ,";
                                $cadenaSql.=" cal.descripcion,";
                                $cadenaSql.=" cal.fecha_inicio, ";
                                $cadenaSql.=" cal.fecha_fin, ";
                                $cadenaSql.=" cal.porcentaje_aprueba, ";
                                $cadenaSql.=" cal.fecha_fin_reclamacion, ";
                                $cadenaSql.=" cal.fecha_fin_resolver, ";
                                $cadenaSql.=" cal.cierre, ";
                                $cadenaSql.=" cal.estado, ";
                                $cadenaSql.=" est.estado nom_estado, ";
                                $cadenaSql.=" (CASE WHEN act.nombre='Inscripción' THEN 'registro' ";
                                $cadenaSql.=" WHEN act.nombre='Registro soportes' THEN 'soporte' ";
                                $cadenaSql.=" WHEN act.nombre='Evaluar requisitos' THEN 'requisito' ";
                                $cadenaSql.=" WHEN act.nombre='Resultados finales' THEN 'elegibles'  ";
                                $cadenaSql.="  ELSE 'evaluacion' END ) fase, ";
                                    $cadenaSql.=" (SELECT count(DISTINCT sop.consecutivo_inscrito) soporte  ";
                                    $cadenaSql.="FROM concurso.concurso_perfil prf  ";
                                    $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc ON prf.consecutivo_perfil=insc.consecutivo_perfil  ";
                                    $cadenaSql.="INNER JOIN concurso.soporte_inscrito sop ON sop.consecutivo_inscrito=insc.consecutivo_inscrito  ";
                                    $cadenaSql.="WHERE prf.consecutivo_concurso=cal.consecutivo_concurso  ";
                                $cadenaSql.=" ) inscrito,  ";
                                    $cadenaSql.="(SELECT count(DISTINCT val.consecutivo_inscrito) valido  ";
                                    $cadenaSql.="FROM concurso.concurso_perfil prf2   ";
                                    $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc2 ON prf2.consecutivo_perfil=insc2.consecutivo_perfil   ";
                                    $cadenaSql.="INNER JOIN concurso.valida_requisito val ON val.consecutivo_inscrito=insc2.consecutivo_inscrito  ";
                                    $cadenaSql.="WHERE prf2.consecutivo_concurso=cal.consecutivo_concurso  ";
                                $cadenaSql.=" ) validado, ";
                                    $cadenaSql.="(SELECT count(etapa.consecutivo_etapa) paso FROM concurso.concurso_perfil prf3  ";
                                    $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc3 ON prf3.consecutivo_perfil=insc3.consecutivo_perfil  ";
                                    $cadenaSql.="INNER JOIN concurso.etapa_inscrito etapa ON etapa.consecutivo_inscrito=insc3.consecutivo_inscrito ";
                                    $cadenaSql.="WHERE prf3.consecutivo_concurso=cal.consecutivo_concurso  ";
                                    $cadenaSql.="AND etapa.consecutivo_calendario=cal.consecutivo_calendario ";
                                $cadenaSql.=" ) clasifico, ";
                                    $cadenaSql.="(SELECT COUNT(DISTINCT id_inscrito) parcial ";
                                    $cadenaSql.="FROM concurso.evaluacion_parcial parc ";
                                    $cadenaSql.="INNER JOIN concurso.concurso_evaluar eval ON eval.consecutivo_evaluar = parc.id_evaluar ";
                                    $cadenaSql.="WHERE parc.estado='A' ";
                                    $cadenaSql.="AND eval.consecutivo_concurso=cal.consecutivo_concurso ";
                                    $cadenaSql.="AND eval.consecutivo_calendario=cal.consecutivo_calendario ";
                                $cadenaSql.=" ) evaluado , ";
                                    $cadenaSql.="(SELECT count(etapa2.consecutivo_etapa) paso2 FROM concurso.concurso_perfil prf4  ";
                                    $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc4 ON prf4.consecutivo_perfil=insc4.consecutivo_perfil  ";
                                    $cadenaSql.="INNER JOIN concurso.etapa_inscrito etapa2 ON etapa2.consecutivo_inscrito=insc4.consecutivo_inscrito ";
                                    $cadenaSql.="WHERE prf4.consecutivo_concurso=cal.consecutivo_concurso  ";
                                    $cadenaSql.="AND etapa2.consecutivo_calendario_ant=cal.consecutivo_calendario ";
                                $cadenaSql.=" ) proceso, ";
                                    $cadenaSql.="(SELECT count(DISTINCT recl.id_inscrito) reclama   ";
                                    $cadenaSql.="FROM concurso.evaluacion_reclamacion recl ";
                                    $cadenaSql.=" WHERE   recl.estado='A'  ";
                                    $cadenaSql.="AND recl.consecutivo_calendario=cal.consecutivo_calendario ";
                                $cadenaSql.=" ) reclamos ";
                                $cadenaSql.=" FROM concurso.concurso_calendario cal";
                                $cadenaSql.=" INNER JOIN concurso.actividad_calendario act ON act.consecutivo_actividad=cal.consecutivo_actividad";
                                $cadenaSql.=" INNER JOIN general.estado est ON est.tipo=cal.estado ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" cal.estado='A' ";
                                $cadenaSql .= "AND cal.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                if(isset($variable['consecutivo_calendario']) &&  $variable['consecutivo_calendario']!='' )
                                   {
                                    $cadenaSql.=" AND cal.consecutivo_calendario='".$variable['consecutivo_calendario']."' ";
                                   }
                                $cadenaSql.=" ORDER BY  cal.fecha_inicio ASC, cal.fecha_fin ASC ";

                            break;

                            case "consultarFaseObligatoria":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" cal.consecutivo_calendario, ";
                                $cadenaSql.=" act.nombre ,";
                                $cadenaSql.=" cal.consecutivo_concurso, ";
                                $cadenaSql.=" cal.consecutivo_actividad, ";
                                $cadenaSql.=" cal.descripcion,";
                                $cadenaSql.=" cal.fecha_inicio, ";
                                $cadenaSql.=" cal.fecha_fin, ";
                                $cadenaSql.=" cal.estado ";
                                $cadenaSql.=" FROM concurso.concurso_calendario cal";
                                $cadenaSql.=" INNER JOIN concurso.actividad_calendario act ON act.consecutivo_actividad=cal.consecutivo_actividad";
                                $cadenaSql.=" INNER JOIN general.estado est ON est.tipo=cal.estado ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" cal.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                $cadenaSql.="AND cal.estado='A' ";
                                if(isset($variable['nombre_fase']) &&  $variable['nombre_fase']!='' )
                                   {
                                    $cadenaSql.=" AND act.nombre='".$variable['nombre_fase']."' ";
                                   }
                            break;

                            case "consultarFasesConcurso":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" cal.consecutivo_calendario, ";
                                $cadenaSql.=" act.nombre ,";
                                $cadenaSql.=" cal.consecutivo_concurso, ";
                                $cadenaSql.=" cal.consecutivo_actividad, ";
                                $cadenaSql.=" cal.descripcion,";
                                $cadenaSql.=" cal.fecha_inicio, ";
                                $cadenaSql.=" cal.fecha_fin, ";
                                $cadenaSql.=" cal.estado ";
                                $cadenaSql.=" FROM concurso.concurso_calendario cal";
                                $cadenaSql.=" INNER JOIN concurso.actividad_calendario act ON act.consecutivo_actividad=cal.consecutivo_actividad";
                                $cadenaSql.=" INNER JOIN general.estado est ON est.tipo=cal.estado ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" cal.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                $cadenaSql.="AND cal.estado='A' ";
                                $cadenaSql.="AND act.nombre NOT IN ('Inscripción','Registro soportes','Evaluar requisitos') ";
                                if(isset($variable['consecutivo_calendario']) &&  $variable['consecutivo_calendario']!='' )
                                   {
                                    $cadenaSql.=" AND cal.consecutivo_calendario!='".$variable['consecutivo_calendario']."' ";
                                   }
                                $cadenaSql.="AND cal.consecutivo_calendario NOT IN  ";
                                $cadenaSql.=" (SELECT cal.consecutivo_calendario ";
                                $cadenaSql.="FROM concurso.concurso_calendario cal ";
                                $cadenaSql.="INNER JOIN concurso.etapa_inscrito etp ON etp.consecutivo_calendario=cal.consecutivo_calendario ";
                                $cadenaSql.="WHERE cal.consecutivo_concurso='".$variable['consecutivo_concurso']."') ";
                                $cadenaSql.=" ORDER BY  cal.fecha_inicio ASC, cal.fecha_fin ASC ";

                            break;

                        case "consultarValidadoPerfilConcurso":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" val.consecutivo_valida,";
                                $cadenaSql.=" val.consecutivo_inscrito,";
                                $cadenaSql.=" val.cumple_requisito,";
                                $cadenaSql.=" val.observacion,";
                                $cadenaSql.=" val.fecha_registro,";
                                $cadenaSql.=" val.estado,";
                                $cadenaSql.=" val.id_reclamacion";
                                $cadenaSql.=" FROM concurso.concurso_perfil prf2";
                                $cadenaSql.=" INNER JOIN concurso.concurso_inscrito insc2 ON prf2.consecutivo_perfil=insc2.consecutivo_perfil";
                                $cadenaSql.=" INNER JOIN concurso.valida_requisito val ON val.consecutivo_inscrito=insc2.consecutivo_inscrito";
                                $cadenaSql.=" WHERE prf2.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                $cadenaSql.=" AND val.estado='A'   ";
                                if(isset($variable['validacion']) &&  $variable['validacion']!='' )
                                   {
                                    $cadenaSql.=" AND val.cumple_requisito='".$variable['validacion']."' ";
                                   }
                            break;


                        case "consultarInscritoConcurso":
                                $cadenaSql="SELECT DISTINCT ";
                                $cadenaSql.="insc.consecutivo_inscrito, ";
                                $cadenaSql.="insc.fecha_registro, ";
                                $cadenaSql.="insc.estado, ";
                                $cadenaSql.="prf.consecutivo_perfil, ";
                                $cadenaSql.="prf.consecutivo_concurso, ";
                                $cadenaSql.="prf.codigo, ";
                                $cadenaSql.="prf.nombre perfil, ";
                                $cadenaSql.="prf.dependencia, ";
                                $cadenaSql.="prf.area, ";
                                $cadenaSql.="per.consecutivo consecutivo_persona, ";
                                $cadenaSql.="per.tipo_identificacion, ";
                                $cadenaSql.="per.identificacion, ";
                                $cadenaSql.="per.nombre, ";
                                $cadenaSql.="per.apellido, ";
                                $cadenaSql.="(SELECT count(consecutivo_soporte_ins) sop ";
                                $cadenaSql.="FROM concurso.soporte_inscrito ";
                                $cadenaSql.="WHERE estado='A' ";
                                $cadenaSql.="AND consecutivo_inscrito=insc.consecutivo_inscrito) soporte ";
                                $cadenaSql.="FROM concurso.concurso_perfil prf ";
                                $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc ";
                                $cadenaSql.="ON prf.consecutivo_perfil=insc.consecutivo_perfil ";
                                $cadenaSql.="INNER JOIN concurso.persona per ";
                                $cadenaSql.="ON per.consecutivo=insc.consecutivo_persona ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql .= " prf.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                if(isset($variable['consecutivo_perfil']) &&  $variable['consecutivo_perfil']!='' )
                                   {
                                    $cadenaSql.=" AND prf.consecutivo_perfil='".$variable['consecutivo_perfil']."' ";
                                   }
                                $cadenaSql.=" ORDER BY prf.dependencia, prf.area,prf.nombre ";

                            break;

                        case "consultarEtapaAprobo":
                                $cadenaSql="SELECT DISTINCT ";
                                $cadenaSql.="consecutivo_etapa, ";
                                $cadenaSql.="consecutivo_inscrito, ";
                                $cadenaSql.="consecutivo_calendario, ";
                                $cadenaSql.="observacion, ";
                                $cadenaSql.="fecha_registro, ";
                                $cadenaSql.="estado, ";
                                $cadenaSql.="consecutivo_calendario_ant ";
                                $cadenaSql.="FROM ";
                                $cadenaSql.="concurso.etapa_inscrito ";
                                $cadenaSql.="WHERE ";
                                $cadenaSql.="consecutivo_calendario_ant='".$variable['consecutivo_calendario']."' ";
                                if(isset($variable['consecutivo_inscrito']) &&  $variable['consecutivo_inscrito']!='' )
                                   {
                                    $cadenaSql.=" AND consecutivo_inscrito='".$variable['consecutivo_inscrito']."' ";
                                   }
                                $cadenaSql.=" ORDER BY consecutivo_inscrito ";

                            break;

                        case "consultarInscritoEtapaOrg":
                                $cadenaSql="SELECT DISTINCT ";
                                $cadenaSql.="consecutivo_etapa, ";
                                $cadenaSql.="consecutivo_inscrito, ";
                                $cadenaSql.="consecutivo_calendario, ";
                                $cadenaSql.="observacion, ";
                                $cadenaSql.="fecha_registro, ";
                                $cadenaSql.="estado, ";
                                $cadenaSql.="consecutivo_calendario_ant ";
                                $cadenaSql.="FROM ";
                                $cadenaSql.="concurso.etapa_inscrito ";
                                $cadenaSql.="WHERE ";
                                $cadenaSql.="consecutivo_calendario='".$variable['consecutivo_calendario']."' ";
                                if(isset($variable['consecutivo_inscrito']) &&  $variable['consecutivo_inscrito']!='' )
                                   {
                                    $cadenaSql.=" AND consecutivo_inscrito='".$variable['consecutivo_inscrito']."' ";
                                   }
                                $cadenaSql.=" ORDER BY consecutivo_inscrito ";

                            break;

                        case "consultarInscritoEtapa":
                                $cadenaSql="SELECT DISTINCT ";
                                $cadenaSql.="etp.consecutivo_inscrito ";
                                $cadenaSql.="FROM concurso.etapa_inscrito etp ";
                                $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc ON etp.consecutivo_inscrito=insc.consecutivo_inscrito ";
                                $cadenaSql.="INNER JOIN concurso.concurso_perfil prf ON prf.consecutivo_perfil=insc.consecutivo_perfil ";
                                $cadenaSql.="WHERE ";
                                $cadenaSql.="prf.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                $cadenaSql.="AND etp.estado='A' ";
                                /*
                                if(isset($variable['consecutivo_calendario']) &&  $variable['consecutivo_calendario']!='' )
                                   {
                                    $cadenaSql.=" AND consecutivo_calendario='".$variable['consecutivo_calendario']."' ";
                                   }*/
                                $cadenaSql.="ORDER BY consecutivo_inscrito ";
                            break;

                        case "consultarRegistradoEtapa":
                                $cadenaSql="SELECT DISTINCT  ";
                                $cadenaSql.=" etp.consecutivo_inscrito, ";
                                $cadenaSql.="COUNT(DISTINCT etp.consecutivo_calendario_ant) aprobado ";
                                $cadenaSql.="FROM ";
                                $cadenaSql.="concurso.etapa_inscrito etp ";
                                $cadenaSql.="INNER JOIN concurso.concurso_calendario cal ON etp.consecutivo_calendario_ant=cal.consecutivo_calendario AND cal.estado=etp.estado ";
                                $cadenaSql.="INNER JOIN concurso.actividad_calendario act ON act.consecutivo_actividad=cal.consecutivo_actividad ";
                                $cadenaSql.="WHERE ";
                                $cadenaSql.="cal.estado='A' ";
                                $cadenaSql.="AND cal.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                $cadenaSql.="AND act.nombre<>'Inscripción' ";
                                $cadenaSql.="AND act.nombre<>'Registro soportes' ";
                                $cadenaSql.="AND act.nombre<>'Evaluar requisitos' ";
                                $cadenaSql.="AND act.nombre<>'Resultados finales' ";
                                $cadenaSql.="GROUP BY etp.consecutivo_inscrito ";
                                $cadenaSql.="ORDER BY aprobado DESC ";
                            break;

                        case "consultarCriteriosEtapa":
                                $cadenaSql="SELECT ";
                                $cadenaSql.="COUNT(eval.consecutivo_criterio) criterios, ";
                                $cadenaSql.="SUM(eval.maximo_puntos) maximo_fase ";
                                $cadenaSql.="FROM concurso.concurso_evaluar eval ";
                                $cadenaSql.="WHERE ";
                                $cadenaSql.=" eval.estado='A' ";
                                if(isset($variable['consecutivo_calendario']) &&  $variable['consecutivo_calendario']!='' )
                                   {
                                    $cadenaSql.=" AND eval.consecutivo_calendario='".$variable['consecutivo_calendario']."' ";
                                   }
                                if(isset($variable['consecutivo_concurso']) &&  $variable['consecutivo_concurso']!='' )
                                   {
                                    $cadenaSql.=" AND eval.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                   }
                                if(isset($variable['consecutivo_criterio']) &&  $variable['consecutivo_criterio']!='' )
                                   {
                                    $cadenaSql.=" AND eval.consecutivo_criterio='".$variable['consecutivo_criterio']."' ";
                                   }                                   
                                $cadenaSql.="GROUP BY eval.consecutivo_concurso ";
                            break;

                        case "consultarFasesEvaluacion":
                                $cadenaSql="SELECT ";
                                $cadenaSql.="cal.consecutivo_concurso, ";
                                $cadenaSql.="COUNT(DISTINCT cal.consecutivo_calendario) fases_evalua ";
                                $cadenaSql.="FROM ";
                                $cadenaSql.="concurso.concurso_calendario cal  ";
                                $cadenaSql.="INNER JOIN concurso.actividad_calendario act ON act.consecutivo_actividad=cal.consecutivo_actividad ";
                                $cadenaSql.="WHERE ";
                                $cadenaSql.="cal.estado='A' ";
                                $cadenaSql.="AND cal.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                $cadenaSql.="AND act.nombre<>'Inscripción' ";
                                $cadenaSql.="AND act.nombre<>'Registro soportes' ";
                                $cadenaSql.="AND act.nombre<>'Evaluar requisitos' ";
                                $cadenaSql.="AND act.nombre<>'Resultados finales' ";
                                $cadenaSql.="GROUP BY cal.consecutivo_concurso ";
                            break;

                        case "consultarDetalleEvaluacionParcial":
                                $cadenaSql="SELECT DISTINCT ";
                                $cadenaSql.="parc.id id_parcial, ";
                                $cadenaSql.="parc.id_grupo, ";
                                $cadenaSql.="parc.id_inscrito, ";
                                $cadenaSql.="parc.id_evaluar, ";
                                $cadenaSql.="parc.puntaje_parcial, ";
                                $cadenaSql.="parc.observacion,  ";
                                $cadenaSql.="parc.fecha_registro, ";
                                $cadenaSql.="parc.estado, ";
                                $cadenaSql.="parc.id_evaluacion_final, ";
                                $cadenaSql.="parc.id_reclamacion, ";
                                $cadenaSql.="eval.consecutivo_concurso, ";
                                $cadenaSql.="eval.consecutivo_criterio, ";
                                $cadenaSql.="eval.maximo_puntos, ";
                                $cadenaSql.="eval.puntos_aprueba, ";
                                $cadenaSql.="eval.consecutivo_calendario, ";
                                
                                $cadenaSql.="(SELECT count (DISTINCT jur.id_usuario) asignado ";
                                $cadenaSql.="FROM concurso.concurso_evaluar cev ";
                                $cadenaSql.="INNER JOIN concurso.jurado_criterio jcrt ON jcrt.id_criterio=cev.consecutivo_criterio AND jcrt.estado=cev.estado  ";
                                $cadenaSql.="INNER JOIN public.jano_usuario_subsistema usu ON usu.rol_id=jcrt.id_jurado_rol AND usu.estado='1' ";
                                $cadenaSql.="INNER JOIN concurso.jurado_inscrito jur ON jur.estado='A' AND usu.id_usuario=jur.id_usuario AND jur.id_jurado_rol=jcrt.id_jurado_rol ";
                                $cadenaSql.="WHERE cev.estado='A' ";
                                $cadenaSql.="AND cev.consecutivo_concurso=eval.consecutivo_concurso ";
                                $cadenaSql.="AND cev.consecutivo_calendario=eval.consecutivo_calendario ";
                                $cadenaSql.="AND '".$variable['hoy']."' BETWEEN usu.fecha_registro AND usu.fecha_caduca  ";
                                $cadenaSql.="AND cev.consecutivo_criterio=eval.consecutivo_criterio ";
                                $cadenaSql.="AND jur.id_inscrito=parc.id_inscrito ";
                                
                                
                                
                                $cadenaSql.=") jurados, ";
                                $cadenaSql.="gr.id_evaluador ";
                                $cadenaSql.="FROM concurso.evaluacion_parcial parc ";
                                $cadenaSql.="INNER JOIN concurso.concurso_evaluar eval ON eval.consecutivo_evaluar=parc.id_evaluar AND eval.estado='A' ";
                                $cadenaSql.="INNER JOIN concurso.evaluacion_grupo gr ON gr.id=parc.id_grupo ";
                                $cadenaSql.="WHERE";
                                $cadenaSql.=" eval.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                $cadenaSql.="AND parc.estado='A' ";
                                if(isset($variable['consecutivo_calendario']) &&  $variable['consecutivo_calendario']!='' )
                                   { $cadenaSql.="AND eval.consecutivo_calendario='".$variable['consecutivo_calendario']."' "; }
                                if(isset($variable['consecutivo_inscrito']) &&  $variable['consecutivo_inscrito']!='' )
                                   { $cadenaSql.="AND parc.id_inscrito='".$variable['consecutivo_inscrito']."' "; }
                                if(isset($variable['gr.id_evaluador']) &&  $variable['gr.id_evaluador']!='' )
                                   {$cadenaSql.=" AND gr.id_evaluador='".$variable['gr.id_evaluador']."' "; }
                                $cadenaSql.=" ORDER BY parc.id_evaluar,parc.id_grupo ";

                            break;

                        case "consultarCalculoEvaluacionParcial":
                                $cadenaSql="SELECT DISTINCT ";
                                $cadenaSql.="parc.id_inscrito, ";
                                $cadenaSql.="parc.id_evaluar,  ";
                                $cadenaSql.="eval.consecutivo_concurso, ";
                                $cadenaSql.="eval.consecutivo_criterio, ";
                                $cadenaSql.="eval.maximo_puntos, ";
                                $cadenaSql.="eval.puntos_aprueba, ";
                                $cadenaSql.="eval.consecutivo_calendario, ";
                                $cadenaSql.="(SELECT SUM(par1.puntaje_parcial::float) as total_parcial ";
                                $cadenaSql.="	  FROM concurso.evaluacion_parcial par1 ";
                                $cadenaSql.="	  WHERE par1.id_inscrito=parc.id_inscrito ";
                                $cadenaSql.="	  AND  par1.id_evaluar=parc.id_evaluar ";
                                $cadenaSql.=") total_puntaje, ";
                                $cadenaSql.="(SELECT count (DISTINCT jur.id_usuario) asignado  ";
                                $cadenaSql.="	  FROM concurso.jurado_inscrito jur ";
                                $cadenaSql.="	WHERE jur.estado='A' ";
                                $cadenaSql.="	AND jur.id_inscrito=parc.id_inscrito ";
                                $cadenaSql.=") jurados ";
                                $cadenaSql.="FROM concurso.evaluacion_parcial parc ";
                                $cadenaSql.="INNER JOIN concurso.concurso_evaluar eval ON eval.consecutivo_evaluar=parc.id_evaluar AND eval.estado='A' ";
                                $cadenaSql.="WHERE parc.estado='A' ";
                                $cadenaSql.="AND eval.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                $cadenaSql.="AND eval.consecutivo_calendario='".$variable['consecutivo_calendario']."' ";
                                if(isset($variable['consecutivo_inscrito']) &&  $variable['consecutivo_inscrito']!='' )
                                   { $cadenaSql.="AND parc.id_inscrito='".$variable['consecutivo_inscrito']."' "; }
                                if(isset($variable['gr.id_evaluador']) &&  $variable['gr.id_evaluador']!='' )
                                   {$cadenaSql.=" AND gr.id_evaluador='".$variable['gr.id_evaluador']."' "; }
                                $cadenaSql.=" ORDER BY parc.id_inscrito ";

                            break;

                        case "consultarReclamacionesRequisitos":
                                $cadenaSql="SELECT DISTINCT ";
                                $cadenaSql.="insc.consecutivo_inscrito, ";
                                $cadenaSql.="prf.consecutivo_perfil,  ";
                                $cadenaSql.="prf.consecutivo_concurso, ";
                                $cadenaSql.="prf.nombre perfil, ";
                                $cadenaSql.="recl.id reclamo, ";
                                $cadenaSql.="recl.consecutivo_calendario, ";
                                $cadenaSql.="recl.observacion , ";
                                $cadenaSql.="recl.fecha_registro registro_recl , ";
                                $cadenaSql.="rsta.id id_respuesta, ";
                                $cadenaSql.="rsta.respuesta, ";
                                $cadenaSql.="rsta.observacion justificacion, ";
                                $cadenaSql.="rsta.fecha_registro registro_resp, ";
                                $cadenaSql.="rsta.id_evaluar_respuesta, ";
                                $cadenaSql.="rsta.id_evaluador ";
                                $cadenaSql.="FROM concurso.concurso_perfil prf  ";
                                $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc ON prf.consecutivo_perfil=insc.consecutivo_perfil ";
                                $cadenaSql.="INNER JOIN concurso.evaluacion_reclamacion recl ON recl.id_inscrito=insc.consecutivo_inscrito AND recl.estado='A' ";
                                $cadenaSql.="INNER JOIN concurso.respuesta_reclamacion rsta ON rsta.id_reclamacion=recl.id AND recl.estado='A' ";
                                $cadenaSql.="WHERE prf.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                if(isset($variable['faseAct']) &&  $variable['faseAct']!='' )
                                   { $cadenaSql.="AND recl.consecutivo_calendario='".$variable['faseAct']."' "; }
                                $cadenaSql.="ORDER BY insc.consecutivo_inscrito, recl.id ";

                            break;

                        case "consultarReclamacionesEvaluacion":
                                $cadenaSql="SELECT DISTINCT ";
                                $cadenaSql.="insc.consecutivo_inscrito, ";
                                $cadenaSql.="prf.consecutivo_perfil,  ";
                                $cadenaSql.="prf.consecutivo_concurso, ";
                                $cadenaSql.="prf.nombre perfil, ";
                                $cadenaSql.="recl.id reclamo, ";
                                $cadenaSql.="recl.consecutivo_calendario, ";
                                $cadenaSql.="recl.observacion , ";
                                $cadenaSql.="recl.fecha_registro registro_recl  ";
                                //$cadenaSql.="rsta.id id_respuesta, ";
                                //$cadenaSql.="rsta.respuesta, ";
                                //$cadenaSql.="rsta.observacion justificacion, ";
                                //$cadenaSql.="rsta.fecha_registro registro_resp, ";
                                //$cadenaSql.="rsta.id_evaluar_respuesta, ";
                                //$cadenaSql.="rsta.id_evaluador ";
                                $cadenaSql.="FROM concurso.concurso_perfil prf  ";
                                $cadenaSql.="INNER JOIN concurso.concurso_inscrito insc ON prf.consecutivo_perfil=insc.consecutivo_perfil ";
                                $cadenaSql.="INNER JOIN concurso.evaluacion_reclamacion recl ON recl.id_inscrito=insc.consecutivo_inscrito AND recl.estado='A' ";
                                //$cadenaSql.="INNER JOIN concurso.respuesta_reclamacion rsta ON rsta.id_reclamacion=recl.id AND recl.estado='A' ";
                                $cadenaSql.="WHERE prf.consecutivo_concurso='".$variable['consecutivo_concurso']."' ";
                                if(isset($variable['faseAct']) &&  $variable['faseAct']!='' )
                                   { $cadenaSql.="AND recl.consecutivo_calendario='".$variable['faseAct']."' "; }
                                $cadenaSql.="ORDER BY insc.consecutivo_inscrito, recl.id ";

                            break;

			case 'buscarSoporte' :
				$cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" sop.consecutivo_soporte,";
                                $cadenaSql.=" sop.consecutivo_persona,";
                                $cadenaSql.=" sop.tipo_dato, ";
                                $cadenaSql.=" sop.consecutivo_dato,";
                                $cadenaSql.=" sop.nombre archivo,";
                                $cadenaSql.=" sop.alias,";
                                $cadenaSql.=" tsop.tipo_soporte,";
                                $cadenaSql.=" tsop.nombre, ";
                                $cadenaSql.=" tsop.ubicacion";
                                $cadenaSql.=" FROM concurso.soporte sop";
                                $cadenaSql.=" INNER JOIN general.tipo_soporte tsop";
                                $cadenaSql.=" ON tsop.tipo_soporte=sop.tipo_soporte";
                                $cadenaSql.=" AND tsop.estado=sop.estado";
                                $cadenaSql.=" WHERE";
                                $cadenaSql.=" tsop.estado='A' ";
                                $cadenaSql.=" AND sop.tipo_dato='".$variable['tipo_dato']."'";
                                $cadenaSql.=" AND sop.consecutivo_persona='".$variable['consecutivo']."'";
                                $cadenaSql.=" AND tsop.nombre='".$variable['nombre_soporte']."'";
                                if(isset($variable['consecutivo_dato']) && $variable['consecutivo_dato']!='')
                                    {$cadenaSql.=" AND sop.consecutivo_dato='".$variable['consecutivo_dato']."' ";}
                                $cadenaSql.=" ORDER BY sop.consecutivo_soporte DESC ";
                            break;
                        //busca datos basicos
                        case "concurso.persona":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" bas.consecutivo,";
                                $cadenaSql.=" bas.tipo_identificacion, ";
                                $cadenaSql.=" bas.identificacion, ";
                                $cadenaSql.=" bas.nombre, ";
                                $cadenaSql.=" bas.apellido,";
                                $cadenaSql.=" bas.lugar_nacimiento, ";
                                    $cadenaSql .= "(SELECT c.nombre ";
                                    $cadenaSql .= "FROM general.ciudad c ";
                                    $cadenaSql .= "WHERE ";
                                    $cadenaSql .= "c.id_ciudad = bas.lugar_nacimiento) ciudad, ";
                                $cadenaSql.=" bas.fecha_nacimiento, ";
                                $cadenaSql.=" bas.pais_nacimiento, ";
                                    $cadenaSql .= "(SELECT nombre_pais ";
                                    $cadenaSql .= "FROM general.pais ";
                                    $cadenaSql .= "WHERE ";
                                    $cadenaSql .= "id_pais =bas.pais_nacimiento) pais, ";
                                $cadenaSql.=" bas.departamento_nacimiento, ";
                                    $cadenaSql .= "(SELECT dep.nombre ";
                                    $cadenaSql .= "FROM general.departamento dep ";
                                    $cadenaSql .= "WHERE ";
                                    $cadenaSql .= "dep.id_departamento = bas.departamento_nacimiento) departamento,";
                                $cadenaSql.=" bas.sexo, ";
                                    $cadenaSql .= "(SELECT c.nombre ";
                                    $cadenaSql .= "FROM general.ciudad c ";
                                    $cadenaSql .= "WHERE ";
                                    $cadenaSql .= "c.id_ciudad = bas.lugar_identificacion) lugar_identificacion, ";
                                $cadenaSql.=" bas.fecha_identificacion, ";
                                    $cadenaSql .= "(SELECT idm.nombre ";
                                    $cadenaSql .= "FROM general.idioma idm ";
                                    $cadenaSql .= "WHERE ";
                                    $cadenaSql .= "idm.codigo_idioma=bas.codigo_idioma_nativo) idioma_nativo ";
                                $cadenaSql.=" FROM concurso.persona bas ";
                                $cadenaSql.=" WHERE bas.consecutivo='".$variable['consecutivo_persona']."'";
                            break;
                        //busca datos de contacto
                        case "concurso.contacto":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" cont.consecutivo_contacto, ";
                                $cadenaSql.=" cont.consecutivo_persona, ";
                                $cadenaSql.=" cont.pais_residencia, ";
                                    $cadenaSql .= "(SELECT nombre_pais ";
                                    $cadenaSql .= "FROM general.pais ";
                                    $cadenaSql .= "WHERE ";
                                    $cadenaSql .= "id_pais =cont.pais_residencia) pais, ";
                                $cadenaSql.=" cont.departamento_residencia, ";
                                    $cadenaSql .= "(SELECT dep.nombre ";
                                    $cadenaSql .= "FROM general.departamento dep ";
                                    $cadenaSql .= "WHERE ";
                                    $cadenaSql .= "dep.id_departamento = cont.departamento_residencia) departamento,";
                                $cadenaSql.=" cont.ciudad_residencia, ";
                                    $cadenaSql .= "(SELECT c.nombre ";
                                    $cadenaSql .= "FROM general.ciudad c ";
                                    $cadenaSql .= "WHERE ";
                                    $cadenaSql .= "c.id_ciudad = cont.ciudad_residencia) ciudad, ";
                                $cadenaSql.=" cont.direccion_residencia, ";
                                $cadenaSql.=" cont.correo, ";
                                $cadenaSql.=" cont.correo_secundario, ";
                                $cadenaSql.=" cont.telefono, ";
                                $cadenaSql.=" cont.celular";
                                $cadenaSql.=" FROM concurso.contacto cont ";
                                $cadenaSql.=" WHERE cont.consecutivo_persona='".$variable['consecutivo_persona']."'";
                            break;
                        //busca formacion academica
                        case "concurso.formacion":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" form.consecutivo_formacion, ";
                                $cadenaSql.=" form.consecutivo_persona, ";
                                $cadenaSql.=" form.codigo_modalidad, ";
                                $cadenaSql.=" modo.nombre modalidad,";
                                $cadenaSql.=" form.codigo_nivel, ";
                                $cadenaSql.=" nv.nombre nivel,";
                                $cadenaSql.=" form.pais_formacion, ";
                                $cadenaSql.=" ps.nombre_pais pais,";
                                $cadenaSql.=" form.codigo_institucion, ";
                                $cadenaSql.=" form.nombre_institucion, ";
                                $cadenaSql.=" form.codigo_programa, ";
                                $cadenaSql.=" form.nombre_programa, ";
                                $cadenaSql.=" form.cursos_aprobados, ";
                                $cadenaSql.=" per.nombre periodicidad, ";
                                $cadenaSql.=" form.graduado, ";
                                $cadenaSql.=" form.fecha_grado, ";
                                $cadenaSql.=" form.promedio ";
                                $cadenaSql.=" FROM concurso.formacion form ";
                                $cadenaSql.=" INNER JOIN general.modalidad_educacion modo ON modo.codigo_modalidad=form.codigo_modalidad ";
                                $cadenaSql.=" INNER JOIN general.nivel nv ON nv.codigo_nivel=form.codigo_nivel";
                                $cadenaSql.=" LEFT OUTER JOIN general.nivel per ON per.codigo_nivel=form.cursos_temporalidad AND per.tipo_nivel='Temporalidad' ";
                                $cadenaSql.=" INNER JOIN general.pais ps ON ps.id_pais=form.pais_formacion";
                                $cadenaSql.=" WHERE form.consecutivo_persona='".$variable['consecutivo_persona']."'";
                                $cadenaSql.=" ORDER BY form.codigo_nivel, ";
                                $cadenaSql.=" form.fecha_grado";
                            break;

                        case "concurso.experiencia_laboral":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" prof.consecutivo_experiencia,";
                                $cadenaSql.=" prof.consecutivo_persona,";
                                $cadenaSql.=" prof.codigo_nivel_experiencia, ";
                                $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=prof.codigo_nivel_experiencia) nivel_experiencia,";
                                $cadenaSql.=" prof.pais_experiencia,";
                                $cadenaSql.=" ps.nombre_pais pais,";
                                $cadenaSql.=" prof.codigo_nivel_institucion, ";
                                $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=prof.codigo_nivel_institucion) nivel_institucion,";
                                $cadenaSql.=" prof.codigo_institucion,";
                                $cadenaSql.=" prof.nombre_institucion, ";
                                $cadenaSql.=" prof.direccion_institucion,";
                                $cadenaSql.=" prof.correo_institucion,";
                                $cadenaSql.=" prof.telefono_institucion, ";
                                $cadenaSql.=" prof.cargo,";
                                $cadenaSql.=" prof.descripcion_cargo,";
                                $cadenaSql.=" prof.actual,";
                                $cadenaSql.=" prof.fecha_inicio,";
                                $cadenaSql.=" prof.fecha_fin ";
                                $cadenaSql.=" FROM concurso.experiencia_laboral prof ";
                                $cadenaSql.=" INNER JOIN general.pais ps ON ps.id_pais=prof.pais_experiencia";
                                $cadenaSql.=" WHERE prof.consecutivo_persona='".$variable['consecutivo_persona']."'";
                                $cadenaSql.=" ORDER BY prof.fecha_inicio DESC";
                            break;

                        case "concurso.experiencia_docencia":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" doc.consecutivo_docencia,";
                                $cadenaSql.=" doc.consecutivo_persona,";
                                $cadenaSql.=" doc.codigo_nivel_docencia,";
                                $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=doc.codigo_nivel_docencia) nivel_docencia,";
                                $cadenaSql.=" doc.pais_docencia,";
                                $cadenaSql.=" ps.nombre_pais pais,";
                                $cadenaSql.=" doc.codigo_nivel_institucion,";
                                $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=doc.codigo_nivel_institucion) nivel_institucion,";
                                $cadenaSql.=" doc.codigo_institucion,";
                                $cadenaSql.=" doc.nombre_institucion,";
                                $cadenaSql.=" doc.direccion_institucion,";
                                $cadenaSql.=" doc.correo_institucion,";
                                $cadenaSql.=" doc.telefono_institucion,";
                                $cadenaSql.=" doc.codigo_vinculacion,";
                                $cadenaSql.=" doc.nombre_vinculacion,";
                                $cadenaSql.=" doc.descripcion_docencia,";
                                $cadenaSql.=" doc.actual,";
                                $cadenaSql.=" doc.fecha_inicio,";
                                $cadenaSql.=" doc.fecha_fin,";
                                $cadenaSql.=" doc.horas_catedra";
                                $cadenaSql.=" FROM concurso.experiencia_docencia doc ";
                                $cadenaSql.=" INNER JOIN general.pais ps ON ps.id_pais=doc.pais_docencia";
                                $cadenaSql.=" WHERE doc.consecutivo_persona='".$variable['consecutivo_persona']."'";
                                $cadenaSql.=" ORDER BY doc.fecha_inicio DESC";
                            break;

                        case "concurso.actividad_academica":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" act.consecutivo_actividad,";
                                $cadenaSql.=" act.consecutivo_persona, ";
                                $cadenaSql.=" act.pais_actividad,";
                                $cadenaSql.=" ps.nombre_pais pais,";
                                $cadenaSql.=" act.codigo_nivel_institucion, ";
                                $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=act.codigo_nivel_institucion) nivel_institucion,";
                                $cadenaSql.=" act.codigo_institucion,";
                                $cadenaSql.=" act.nombre_institucion,";
                                $cadenaSql.=" act.correo_institucion, ";
                                $cadenaSql.=" act.telefono_institucion, ";
                                $cadenaSql.=" act.codigo_tipo_actividad,";
                                $cadenaSql.=" act.nombre_tipo_actividad, ";
                                $cadenaSql.=" act.nombre_actividad, ";
                                $cadenaSql.=" act.descripcion, ";
                                $cadenaSql.=" act.jefe_actividad,";
                                $cadenaSql.=" act.fecha_inicio,";
                                $cadenaSql.=" act.fecha_fin";
                                $cadenaSql.=" FROM concurso.actividad_academica act";
                                $cadenaSql.=" INNER JOIN general.pais ps ON ps.id_pais=act.pais_actividad";
                                $cadenaSql.=" WHERE act.consecutivo_persona='".$variable['consecutivo_persona']."'";
                                $cadenaSql.=" ORDER BY act.fecha_inicio DESC";
                            break;
                        case "concurso.experiencia_investigacion":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" inv.consecutivo_investigacion,";
                                $cadenaSql.=" inv.consecutivo_persona,";
                                $cadenaSql.=" inv.pais_investigacion,";
                                $cadenaSql.=" ps.nombre_pais pais,";
                                $cadenaSql.=" inv.codigo_nivel_institucion,";
                                $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=inv.codigo_nivel_institucion) nivel_institucion,";
                                $cadenaSql.=" inv.codigo_institucion,";
                                $cadenaSql.=" inv.nombre_institucion,";
                                $cadenaSql.=" inv.direccion_institucion,";
                                $cadenaSql.=" inv.correo_institucion,";
                                $cadenaSql.=" inv.telefono_institucion,";
                                $cadenaSql.=" inv.titulo_investigacion,";
                                $cadenaSql.=" inv.jefe_investigacion,";
                                $cadenaSql.=" inv.descripcion_investigacion,";
                                $cadenaSql.=" inv.direccion_investigacion,";
                                $cadenaSql.=" inv.actual,";
                                $cadenaSql.=" inv.fecha_inicio,";
                                $cadenaSql.=" inv.fecha_fin,";
                                $cadenaSql.=" inv.grupo_investigacion,";
                                $cadenaSql.=" inv.categoria_grupo,";
                                $cadenaSql.=" inv.rol_investigacion ";
                                $cadenaSql.=" FROM concurso.experiencia_investigacion inv";
                                $cadenaSql.=" INNER JOIN general.pais ps ON ps.id_pais=inv.pais_investigacion";
                                $cadenaSql.=" WHERE inv.consecutivo_persona='".$variable['consecutivo_persona']."'";
                                $cadenaSql.=" ORDER BY inv.fecha_inicio DESC";
                            break;
                        case "concurso.produccion_academica":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" prod.consecutivo_produccion,";
                                $cadenaSql.=" prod.consecutivo_persona,";
                                $cadenaSql.=" prod.codigo_tipo_produccion,";
                                $cadenaSql.=" (CASE WHEN prod.codigo_tipo_produccion!=0
                                                    THEN  (SELECT nombre FROM general.nivel WHERE codigo_nivel=prod.codigo_tipo_produccion)
                                                    ELSE prod.nombre_tipo_produccion END) nombre_tipo_produccion, ";
                                $cadenaSql.=" prod.titulo_produccion,";
                                $cadenaSql.=" prod.nombre_autor,";
                                $cadenaSql.=" prod.nombre_producto_incluye,";
                                $cadenaSql.=" prod.nombre_editorial,";
                                $cadenaSql.=" prod.volumen,";
                                $cadenaSql.=" prod.pagina,";
                                $cadenaSql.=" prod.codigo_isbn,";
                                $cadenaSql.=" prod.codigo_issn,";
                                $cadenaSql.=" prod.indexado,";
                                $cadenaSql.=" prod.pais_produccion,";
                                $cadenaSql.=" prod.departamento_produccion,";
                                $cadenaSql.=" prod.ciudad_produccion,";
                                $cadenaSql.=" city.nombre ciudad,";
                                $cadenaSql.=" prod.descripcion,";
                                $cadenaSql.=" prod.direccion_produccion,";
                                $cadenaSql.=" prod.fecha_produccion";
                                $cadenaSql.=" FROM concurso.produccion_academica prod ";
                                $cadenaSql.=" INNER JOIN general.ciudad city ON city.id_ciudad=prod.ciudad_produccion";
                                $cadenaSql.=" WHERE prod.consecutivo_persona='".$variable['consecutivo_persona']."'";
                                $cadenaSql.=" ORDER BY prod.fecha_produccion DESC";
                            break;
                        case "concurso.conocimiento_idioma":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" conidm.consecutivo_conocimiento,";
                                $cadenaSql.=" conidm.consecutivo_persona, ";
                                $cadenaSql.=" conidm.codigo_idioma, ";
                                $cadenaSql.=" idm.nombre idioma, ";
                                $cadenaSql.=" conidm.nivel_lee, ";
                               // $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=to_number(conidm.nivel_lee,'99')) nombre_nivel_lee,";
                                $cadenaSql.=" conidm.nivel_escribe,";
                               // $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=to_number(conidm.nivel_escribe,'99')) nombre_nivel_escribe,";
                                $cadenaSql.=" conidm.nivel_habla, ";
                               // $cadenaSql.=" (SELECT nombre FROM general.nivel WHERE codigo_nivel=to_number(conidm.nivel_habla,'99')) nombre_nivel_habla,";
                                $cadenaSql.=" conidm.certificacion,";
                                $cadenaSql.=" conidm.institucion_certificacion, ";
                                $cadenaSql.=" conidm.idioma_concurso";
                                $cadenaSql.=" FROM concurso.conocimiento_idioma conidm ";
                                $cadenaSql.=" INNER JOIN general.idioma idm ON idm.codigo_idioma=conidm.codigo_idioma";
                                $cadenaSql.=" WHERE conidm.consecutivo_persona='".$variable['consecutivo_persona']."'";
                                $cadenaSql.=" ORDER BY idm.nombre DESC";
                            break;

                        case "registroSoporteConcurso":
                                $cadenaSql=" INSERT INTO concurso.soporte_inscrito(";
                                $cadenaSql.=" consecutivo_soporte_ins,";
                                $cadenaSql.=" consecutivo_inscrito, ";
                                $cadenaSql.=" tipo_dato, ";
                                $cadenaSql.=" consecutivo_dato,";
                                $cadenaSql.=" fuente_dato, ";
                                $cadenaSql.=" valor_dato, ";
                                $cadenaSql.=" consecutivo_soporte, ";
                                $cadenaSql.=" alias_soporte, ";
                                $cadenaSql.=" nombre_soporte, ";
                                $cadenaSql.=" fecha_registro, ";
                                $cadenaSql.=" estado)";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " DEFAULT, ";
                                $cadenaSql .= " '".$variable['consecutivo_inscrito']."', ";
                                $cadenaSql .= " '".$variable['tipo_dato']."', ";
                                $cadenaSql .= " '".$variable['consecutivo_dato']."', ";
                                $cadenaSql .= " '".$variable['fuente_dato']."', ";
                                $cadenaSql .= " '".$variable['valor_dato']."', ";
                                $cadenaSql .= " '".$variable['consecutivo_soporte']."', ";
                                $cadenaSql .= " '".$variable['alias_soporte']."', ";
                                $cadenaSql .= " '".$variable['nombre_soporte']."', ";
                                $cadenaSql .= " '".$variable['fecha_registro']."', ";
                                $cadenaSql .= " 'A' ";
                                $cadenaSql .= " )";
                                $cadenaSql.=" RETURNING consecutivo_soporte_ins";
                        break;

                        case "registroEtapaInscrito":
                                $cadenaSql=" INSERT INTO concurso.etapa_inscrito(";
                                $cadenaSql.=" consecutivo_etapa,";
                                $cadenaSql.=" consecutivo_inscrito,";
                                $cadenaSql.=" consecutivo_calendario,";
                                $cadenaSql.=" observacion,";
                                $cadenaSql.=" fecha_registro,";
                                $cadenaSql.=" estado,";
                                $cadenaSql.=" consecutivo_calendario_ant)";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " DEFAULT, ";
                                $cadenaSql .= " '".$variable['consecutivo_inscrito']."', ";
                                $cadenaSql .= " '".$variable['consecutivo_calendario']."', ";
                                $cadenaSql .= " '".$variable['observacion']."', ";
                                $cadenaSql .= " '".$variable['fecha_registro']."', ";
                                $cadenaSql .= " 'A', ";
                                $cadenaSql .= " '".$variable['consecutivo_calendario_ant']."' ";
                                $cadenaSql .= " )";
                                $cadenaSql.=" RETURNING consecutivo_etapa";
                        break;

                        case "registroEvaluacionFinal":
                                $cadenaSql=" INSERT INTO concurso.evaluacion_final(";
                                $cadenaSql.=" id,";
                                $cadenaSql.=" id_inscrito,";
                                $cadenaSql.=" id_evaluar,";
                                $cadenaSql.=" puntaje_final,";
                                $cadenaSql.=" observacion,";
                                $cadenaSql.=" fecha_registro,";
                                $cadenaSql.=" aprobo,";
                                $cadenaSql.=" estado)";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " DEFAULT, ";
                                $cadenaSql .= " '".$variable['id_inscrito']."', ";
                                $cadenaSql .= " '".$variable['id_evaluar']."', ";
                                $cadenaSql .= " '".$variable['puntaje_final']."', ";
                                $cadenaSql .= " '".$variable['observacion']."', ";
                                $cadenaSql .= " '".$variable['fecha_registro']."', ";
                                $cadenaSql .= " '".$variable['aprobo']."', ";
                                $cadenaSql .= " 'A' ";
                                $cadenaSql .= " )";
                                $cadenaSql.=" RETURNING id";
                        break;

                        case "registroEvaluacionPromedio":
                                $cadenaSql=" INSERT INTO";
                                $cadenaSql.=" concurso.evaluacion_promedio(";
                                $cadenaSql.=" id,";
                                $cadenaSql.=" id_inscrito,";
                                $cadenaSql.=" id_calendario,";
                                $cadenaSql.=" puntaje_promedio,";
                                $cadenaSql.=" evaluaciones,";
                                $cadenaSql.=" fecha_registro,";
                                $cadenaSql.=" estado,";
                                $cadenaSql.=" id_reclamacion)";
                                $cadenaSql .= " VALUES ( ";
                                $cadenaSql .= " DEFAULT, ";
                                $cadenaSql .= " '".$variable['id_inscrito']."', ";
                                $cadenaSql .= " '".$variable['id_calendario']."', ";
                                $cadenaSql .= " '".$variable['puntaje_promedio']."', ";
                                $cadenaSql .= " '".$variable['evaluaciones']."', ";
                                $cadenaSql .= " '".$variable['fecha_registro']."', ";
                                $cadenaSql .= " 'A', ";
                                $cadenaSql .= " '".$variable['id_reclamacion']."' ";
                                $cadenaSql .= " )";
                                $cadenaSql.=" RETURNING id";
                        break;



                        case "registroAspirantesJurado":
				$cadenaSql=" INSERT INTO concurso.jurado_inscrito(";
				$cadenaSql.=" id_usuario,";
				$cadenaSql.=" id_inscrito, ";
				$cadenaSql.=" id_jurado_tipo,";
				$cadenaSql.=" fecha_registro,";
				$cadenaSql.=" id_jurado_rol ";
				$cadenaSql.=" )";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " '".$variable['usuario']."', ";
				$cadenaSql .= " '".$variable['inscrito']."', ";
				$cadenaSql .= " '".$variable['jurado_tipo']."', ";
				$cadenaSql .= " '".$variable['fecha']."', ";
				$cadenaSql .= " '".$variable['rol']."' ";
				$cadenaSql .= " )";
				$cadenaSql.=" RETURNING id";
                        break;

                        case "actualizarEvaluacionParcial":
                                $cadenaSql.=" UPDATE concurso.evaluacion_parcial";
                                $cadenaSql.=" SET ";
                                if(isset($variable['estado']) && $variable['estado']!='')
                                    {$cadenaSql.=" estado='".$variable['estado']."' ";}
                                if(isset($variable['id_evaluacion_final']) && $variable['id_evaluacion_final']!='')
                                    {$cadenaSql.="id_evaluacion_final='".$variable['id_evaluacion_final']."' ";}
                                if(isset($variable['id_reclamacion']) && $variable['id_reclamacion']!='')
                                    {$cadenaSql.=" id_reclamacion='".$variable['id_reclamacion']."' ";}
                                if(isset($variable['id_evaluacion_final_reclamo']) && $variable['id_evaluacion_final_reclamo']!='')
                                    {$cadenaSql.="id_evaluacion_final_reclamo='".$variable['id_evaluacion_final_reclamo']."' ";}
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" id_inscrito='".$variable['id_inscrito']."' ";
                                $cadenaSql.=" AND id_evaluar='".$variable['id_evaluar']."' ";
                                if(isset($variable['id_parcial']) && $variable['id_parcial']!='')
                                    {$cadenaSql.=" id='".$variable['id_parcial']."' ";}
                                if(isset($variable['id_evaluacion_final_reclamo']) && $variable['id_evaluacion_final_reclamo']!='')
                                    {$cadenaSql.=" AND estado='A' ";}
                                $cadenaSql.=" RETURNING id";
                        break;

                        case "actualizarEvaluacionFinal":
                                $cadenaSql.=" UPDATE concurso.evaluacion_final";
                                $cadenaSql.=" SET ";
                                if(isset($variable['estado']) && $variable['estado']!='')
                                    {$cadenaSql.=" estado='".$variable['estado']."' ";}
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" id_inscrito='".$variable['id_inscrito']."' ";
                                $cadenaSql.=" AND id_evaluar='".$variable['id_evaluar']."' ";
                                if(isset($variable['id_final']) && $variable['id_final']!='')
                                    {$cadenaSql.=" id='".$variable['id_final']."' ";}
                                $cadenaSql.=" RETURNING id";
                        break;

                        case "actualizarEvaluacionPromedio":
                                $cadenaSql.=" UPDATE concurso.evaluacion_promedio";
                                $cadenaSql.=" SET ";
                                if(isset($variable['estado']) && $variable['estado']!='')
                                    {$cadenaSql.=" estado='".$variable['estado']."' ";}
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" id_inscrito='".$variable['id_inscrito']."' ";
                                $cadenaSql.=" AND id_calendario='".$variable['id_calendario']."' ";
                                if(isset($variable['id_promedio']) && $variable['id_promedio']!='')
                                    {$cadenaSql.=" id='".$variable['id_promedio']."' ";}
                                $cadenaSql.=" RETURNING id";
                        break;

                        case "actualizarEtapaInscrito":
                                $cadenaSql.=" UPDATE concurso.etapa_inscrito";
                                $cadenaSql.=" SET ";
                                if(isset($variable['estado']) && $variable['estado']!='')
                                    {$cadenaSql.=" estado='".$variable['estado']."' ";}
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_inscrito='".$variable['consecutivo_inscrito']."' ";
                                $cadenaSql.=" AND consecutivo_calendario_ant='".$variable['faseAct']."' ";
                                if(isset($variable['consecutivo_etapa']) && $variable['consecutivo_etapa']!='')
                                    {$cadenaSql.=" consecutivo_etapa='".$variable['consecutivo_etapa']."' ";}
                                $cadenaSql.=" RETURNING consecutivo_etapa";
                        break;


                        case "actualizaCierreCalendario":
                                $cadenaSql=" UPDATE concurso.concurso_calendario";
                                $cadenaSql.=" SET ";
                                $cadenaSql.= " cierre= '".$variable['cierre']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_calendario= '".$variable['consecutivo_calendario']."' ";
                                $cadenaSql.=" RETURNING consecutivo_calendario";
                        break;


                        case "actualizaEstadoEstapaInscrito":
                                $cadenaSql=" UPDATE concurso.etapa_inscrito";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" estado= '".$variable['estado']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_etapa= '".$variable['consecutivo_etapa']."' ";
                                $cadenaSql.=" RETURNING consecutivo_etapa";
                        break;

				/**
				 * Clausulas genéricas. se espera que estén en todos los formularios
				 * que utilicen esta plantilla
				 */

			case "iniciarTransaccion":
				$cadenaSql="START TRANSACTION";
				break;

			case "finalizarTransaccion":
				$cadenaSql="COMMIT";
				break;

			case "cancelarTransaccion":
				$cadenaSql="ROLLBACK";
				break;

		}
		return $cadenaSql;
	}
}

?>
