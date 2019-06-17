<?php

namespace gestionConcursante\gestionHoja;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

// Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
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
		
		switch ($tipo) {
			/**
			 * Clausulas específicas
			 */
                        case "idioma":
				$cadenaSql = "SET lc_time_names = 'es_ES' ";
			break;
			case "consultaMensaje":
                                $cadenaSql = "Select id, tipo, texto, estado FROM jano_texto ";
                                $cadenaSql .= "WHERE tipo='autorizacionHV' ";
                                $cadenaSql .= "AND estado='A' ";
                            break;                    
			case 'buscarPais' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'general.pais ';
				$cadenaSql .= 'ORDER BY NOMBRE; ';
                            break;
			case 'buscarDepartamento' ://Provisionalmente solo Departamentos de Colombia
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'general.departamento ';
				$cadenaSql .= 'WHERE ';
                                if(isset($variable['pais']) && $variable['pais']!='')
                                    {$cadenaSql.=" id_pais ='".$variable['pais']."' ";}
                                else {$cadenaSql .= 'id_pais = 112';}
                            break;
               		case 'buscarDepartamentoAjax' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'general.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRE; ';
                            break;
               		case 'buscarCiudad' : //Provisionalmente Solo Ciudades de Colombia sin Agrupar
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'general.ciudad ';
				$cadenaSql .= 'WHERE ';
                                if(isset($variable['departamento']) && $variable['departamento']!='')
                                     {$cadenaSql.=" id_departamento ='".$variable['departamento']."' ";}
                                else {$cadenaSql .= 'ab_pais = \'CO\'';}
                                
                            break;
			case 'buscarCiudadAjax' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRECIUDAD ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'general.ciudad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_departamento = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRECIUDAD; ';
                            break;
                        case 'buscarIdioma' :
				$cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" codigo_idioma,";
                                $cadenaSql.=" nombre,";
                                $cadenaSql.=" codigo_iso,";
                                $cadenaSql.=" estado";
                                $cadenaSql.=" FROM general.idioma ";    
                                $cadenaSql.=" WHERE";
                                $cadenaSql.=" estado='A' ";
                                if(isset($variable['codigo_idioma']) && $variable['codigo_idioma']!='')
                                    {$cadenaSql.=" AND codigo_idioma='".$variable['codigo_idioma']."' ";}
                                $cadenaSql.=" ORDER BY nombre ASC ";
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
                        case "consultarBasicos":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" bas.consecutivo,";
                                $cadenaSql.=" bas.tipo_identificacion, ";
                                $cadenaSql.=" bas.identificacion, ";
                                $cadenaSql.=" bas.nombre, ";
                                $cadenaSql.=" bas.apellido,";
                                $cadenaSql.=" bas.lugar_nacimiento, ";
                                $cadenaSql.=" bas.fecha_nacimiento, ";
                                $cadenaSql.=" bas.pais_nacimiento, ";
                                $cadenaSql.=" bas.departamento_nacimiento, ";
                                $cadenaSql.=" bas.sexo,";
                                $cadenaSql.=" bas.lugar_identificacion, ";
                                $cadenaSql.=" bas.fecha_identificacion, ";
                                $cadenaSql.=" bas.codigo_idioma_nativo , ";
                                $cadenaSql.=" bas.autorizacion ";
                                $cadenaSql.=" FROM concurso.persona bas ";
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario us ";
                                $cadenaSql.=" ON trim(us.tipo_identificacion)=trim(bas.tipo_identificacion) ";
                                $cadenaSql.=" AND bas.identificacion=us.identificacion ";
                                $cadenaSql.=" WHERE us.id_usuario='".$variable['id_usuario']."'";
                            break;
                        case "consultarContacto":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" bas.identificacion, ";
                                $cadenaSql.=" bas.nombre, ";
                                $cadenaSql.=" bas.apellido, ";
                                $cadenaSql.=" bas.autorizacion,";
                                $cadenaSql.=" (CASE WHEN cont.consecutivo_contacto IS NULL THEN 0 ELSE cont.consecutivo_contacto END ) consecutivo_contacto, ";
                                $cadenaSql.=" bas.consecutivo consecutivo_persona, ";
                                $cadenaSql.=" cont.pais_residencia, ";
                                $cadenaSql.=" cont.departamento_residencia, ";
                                $cadenaSql.=" cont.ciudad_residencia, ";
                                $cadenaSql.=" cont.direccion_residencia, ";
                                $cadenaSql.=" (CASE WHEN cont.correo IS NULL THEN us.correo ELSE cont.correo END ) correo, ";
                                $cadenaSql.=" cont.correo_secundario, ";
                                $cadenaSql.=" (CASE WHEN cont.telefono IS NULL THEN us.telefono ELSE cont.telefono END ) telefono, ";
                                $cadenaSql.=" cont.celular";
                                $cadenaSql.=" FROM concurso.persona bas ";
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario us ";
                                $cadenaSql.=" ON trim(us.tipo_identificacion)=trim(bas.tipo_identificacion) ";
                                $cadenaSql.=" AND bas.identificacion=us.identificacion ";
                                $cadenaSql.=" LEFT OUTER JOIN concurso.contacto cont ON cont.consecutivo_persona=bas.consecutivo";
                                $cadenaSql.=" WHERE us.id_usuario='".$variable['id_usuario']."'";
                            break;
                        case "consultarFormacion":
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
                                $cadenaSql.=" form.cursos_temporalidad, ";
                                $cadenaSql.=" per.nombre periodicidad, ";
                                $cadenaSql.=" form.graduado, ";
                                $cadenaSql.=" form.fecha_grado, ";
                                $cadenaSql.=" form.promedio ";
                                $cadenaSql.=" FROM concurso.persona bas ";
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario usu ON trim(usu.tipo_identificacion)=trim(bas.tipo_identificacion) AND bas.identificacion=usu.identificacion";
                                $cadenaSql.=" INNER JOIN concurso.formacion form ON form.consecutivo_persona=bas.consecutivo";
                                $cadenaSql.=" INNER JOIN general.modalidad_educacion modo ON modo.codigo_modalidad=form.codigo_modalidad ";
                                $cadenaSql.=" INNER JOIN general.nivel nv ON nv.codigo_nivel=form.codigo_nivel AND nv.tipo_nivel='Formacion' ";
                                $cadenaSql.=" LEFT OUTER JOIN general.nivel per ON per.codigo_nivel=form.cursos_temporalidad AND per.tipo_nivel='Temporalidad' ";
                                $cadenaSql.=" INNER JOIN general.pais ps ON ps.id_pais=form.pais_formacion";
                                $cadenaSql.=" WHERE usu.id_usuario='".$variable['id_usuario']."'";
                                if(isset($variable['consecutivo_formacion']) && $variable['consecutivo_formacion']!='')
                                    {$cadenaSql.=" AND form.consecutivo_formacion='".$variable['consecutivo_formacion']."' ";}
                                $cadenaSql.=" ORDER BY form.codigo_nivel, ";
                                $cadenaSql.=" form.fecha_grado";    
                            break;      
                        case "consultarExperiencia":
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
                                $cadenaSql.=" FROM concurso.persona bas "; 
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario usu ON trim(usu.tipo_identificacion)=trim(bas.tipo_identificacion) AND bas.identificacion=usu.identificacion";
                                $cadenaSql.=" INNER JOIN concurso.experiencia_laboral prof ON prof.consecutivo_persona=bas.consecutivo";
                                $cadenaSql.=" INNER JOIN general.pais ps ON ps.id_pais=prof.pais_experiencia";
                                $cadenaSql.=" WHERE usu.id_usuario='".$variable['id_usuario']."'";
                                if(isset($variable['consecutivo_experiencia']) && $variable['consecutivo_experiencia']!='')
                                    {$cadenaSql.=" AND prof.consecutivo_experiencia='".$variable['consecutivo_experiencia']."' ";}
                                $cadenaSql.=" ORDER BY prof.fecha_inicio DESC";    
                            break;    
                        case "consultarDocencia":
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
                                $cadenaSql.=" FROM concurso.persona bas "; 
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario usu ON trim(usu.tipo_identificacion)=trim(bas.tipo_identificacion) AND bas.identificacion=usu.identificacion";
                                $cadenaSql.=" INNER JOIN concurso.experiencia_docencia doc ON doc.consecutivo_persona=bas.consecutivo";
                                $cadenaSql.=" INNER JOIN general.pais ps ON ps.id_pais=doc.pais_docencia";
                                $cadenaSql.=" WHERE usu.id_usuario='".$variable['id_usuario']."'";
                                if(isset($variable['consecutivo_docencia']) && $variable['consecutivo_docencia']!='')
                                    {$cadenaSql.=" AND doc.consecutivo_docencia='".$variable['consecutivo_docencia']."' ";}
                                $cadenaSql.=" ORDER BY doc.fecha_inicio DESC";    
                            break;                                
                        case "consultarActividad":
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
                                $cadenaSql.=" FROM concurso.persona bas "; 
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario usu ON trim(usu.tipo_identificacion)=trim(bas.tipo_identificacion) AND bas.identificacion=usu.identificacion";
                                $cadenaSql.=" INNER JOIN concurso.actividad_academica act ON act.consecutivo_persona=bas.consecutivo";
                                $cadenaSql.=" INNER JOIN general.pais ps ON ps.id_pais=act.pais_actividad";
                                $cadenaSql.=" WHERE usu.id_usuario='".$variable['id_usuario']."'";
                                if(isset($variable['consecutivo_actividad']) && $variable['consecutivo_actividad']!='')
                                    {$cadenaSql.=" AND act.consecutivo_actividad='".$variable['consecutivo_actividad']."' ";}
                                $cadenaSql.=" ORDER BY act.fecha_inicio DESC";   
                            break;                            
                        case "consultarInvestigacion":
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
                                $cadenaSql.=" FROM concurso.persona bas "; 
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario usu ON trim(usu.tipo_identificacion)=trim(bas.tipo_identificacion) AND bas.identificacion=usu.identificacion";
                                $cadenaSql.=" INNER JOIN concurso.experiencia_investigacion inv ON inv.consecutivo_persona=bas.consecutivo";
                                $cadenaSql.=" INNER JOIN general.pais ps ON ps.id_pais=inv.pais_investigacion";
                                $cadenaSql.=" WHERE usu.id_usuario='".$variable['id_usuario']."'";
                                if(isset($variable['consecutivo_investigacion']) && $variable['consecutivo_investigacion']!='')
                                    {$cadenaSql.=" AND inv.consecutivo_investigacion='".$variable['consecutivo_investigacion']."' ";}
                                $cadenaSql.=" ORDER BY inv.fecha_inicio DESC";    
                            break;    
                        case "consultarProduccion":
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
                                $cadenaSql.=" FROM concurso.persona bas "; 
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario usu ON trim(usu.tipo_identificacion)=trim(bas.tipo_identificacion) AND bas.identificacion=usu.identificacion";
                                $cadenaSql.=" INNER JOIN concurso.produccion_academica prod ON prod.consecutivo_persona=bas.consecutivo";
                                $cadenaSql.=" INNER JOIN general.ciudad city ON city.id_ciudad=prod.ciudad_produccion";
                                $cadenaSql.=" WHERE usu.id_usuario='".$variable['id_usuario']."'";
                                if(isset($variable['consecutivo_produccion']) && $variable['consecutivo_produccion']!='')
                                    {$cadenaSql.=" AND prod.consecutivo_produccion='".$variable['consecutivo_produccion']."' ";}
                                $cadenaSql.=" ORDER BY prod.fecha_produccion DESC";    
                            break;                              
                        case "consultarIdiomas":
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
                                $cadenaSql.=" FROM concurso.persona bas "; 
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario usu ON trim(usu.tipo_identificacion)=trim(bas.tipo_identificacion) AND bas.identificacion=usu.identificacion";
                                $cadenaSql.=" INNER JOIN concurso.conocimiento_idioma conidm ON conidm.consecutivo_persona=bas.consecutivo";    
                                $cadenaSql.=" INNER JOIN general.idioma idm ON idm.codigo_idioma=conidm.codigo_idioma";
                                $cadenaSql.=" WHERE usu.id_usuario='".$variable['id_usuario']."'";
                                if(isset($variable['consecutivo_conocimiento']) && $variable['consecutivo_conocimiento']!='')
                                    {$cadenaSql.=" AND conidm.consecutivo_conocimiento='".$variable['consecutivo_conocimiento']."' ";}
                                $cadenaSql.=" ORDER BY idm.nombre DESC";   
                            break;        
                            
                        case "idiomaConcursos":
                                $cadenaSql=" SELECT  ";
                                $cadenaSql.=" COUNT(DISTINCT conidm.consecutivo_conocimiento) presentar ";
                                $cadenaSql.=" FROM concurso.persona bas "; 
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario usu ON trim(usu.tipo_identificacion)=trim(bas.tipo_identificacion) AND bas.identificacion=usu.identificacion";
                                $cadenaSql.=" INNER JOIN concurso.conocimiento_idioma conidm ON conidm.consecutivo_persona=bas.consecutivo";    
                                $cadenaSql.=" WHERE usu.id_usuario='".$variable['id_usuario']."'";
                                $cadenaSql.=" AND conidm.idioma_concurso='S'";    
                                
                            break;                                   
                            
                            
                        case "consultarModalidad":
                                $cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" codigo_modalidad,";
                                $cadenaSql.=" nombre, ";
                                $cadenaSql.=" estado";
                                $cadenaSql.=" FROM general.modalidad_educacion";
                                $cadenaSql.=" WHERE estado='A'";
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
                        case "consultarInstitucion":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" codigo_ies,";
                                $cadenaSql.=" nombre, ";
                                $cadenaSql.=" pais_institucion, ";
                                $cadenaSql.=" estado";
                                $cadenaSql.=" FROM general.institucion_educacion";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" estado='A'";
                                if(isset($variable['codigo_ies']))
                                    {$cadenaSql.=" AND codigo_ies='".$variable['codigo_ies']."' ";}
                                if(isset($variable['pais_institucion']))
                                    {$cadenaSql.=" AND pais_institucion='".$variable['pais_institucion']."' ";}    
                                $cadenaSql.=" ORDER BY nombre ";    
                            break;    
                       case "consultarPrograma":
                                $cadenaSql=" SELECT DISTINCT ";
                                $cadenaSql.=" consecutivo_programa, ";
                                $cadenaSql.=" nombre, ";
                                $cadenaSql.=" codigo_programa, ";
                                $cadenaSql.=" codigo_ies, ";
                                $cadenaSql.=" estado";
                                $cadenaSql.=" FROM general.programa_ies";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" estado='A'";
                                if(isset($variable['codigo_ies']))
                                    {$cadenaSql.=" AND codigo_ies='".$variable['codigo_ies']."' ";}
                                $cadenaSql.=" UNION ";
                                $cadenaSql.=" SELECT DISTINCT ";
                                $cadenaSql.=" 0 consecutivo_programa, ";
                                $cadenaSql.=" 'OTRO' nombre, ";
                                $cadenaSql.=" 0 codigo_programa, ";
                                $cadenaSql.=" 0 codigo_ies, ";
                                $cadenaSql.=" 'A' estado";
                                $cadenaSql.=" FROM general.programa_ies";
                                $cadenaSql.=" ORDER BY nombre ";    
                            break;                              
			case 'registroSoporte' :
				$cadenaSql=" INSERT INTO";
                                $cadenaSql.=" concurso.soporte(";
                                $cadenaSql.=" consecutivo_soporte,";
                                $cadenaSql.=" tipo_soporte, ";
                                $cadenaSql.=" consecutivo_persona, ";
                                $cadenaSql.=" tipo_dato, ";
                                $cadenaSql.=" consecutivo_dato, ";
                                $cadenaSql.=" nombre, ";
                                $cadenaSql.=" alias, ";
                                $cadenaSql.=" estado)";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['tipo_soporte']."',";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['tipo_dato']."',";
                                $cadenaSql.=" '".$variable['consecutivo_dato']."',";
                                $cadenaSql.=" '".$variable['nombre']."',";
                                $cadenaSql.=" '".$variable['alias']."',";
                                $cadenaSql.=" 'A' )";
                                $cadenaSql.=  " RETURNING consecutivo_soporte ";
                            break;
			case 'registroContacto' :
                                $cadenaSql=" INSERT INTO concurso.contacto(";
                                $cadenaSql.=" consecutivo_contacto, ";
                                $cadenaSql.=" consecutivo_persona, ";
                                $cadenaSql.=" pais_residencia,";
                                $cadenaSql.=" departamento_residencia, ";
                                $cadenaSql.=" ciudad_residencia,";
                                $cadenaSql.=" direccion_residencia,";
                                $cadenaSql.=" correo,";
                                $cadenaSql.=" correo_secundario, ";
                                $cadenaSql.=" telefono,";
                                $cadenaSql.=" celular)";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['pais_residencia']."',";
                                $cadenaSql.=" '".$variable['departamento_residencia']."',";
                                $cadenaSql.=" '".$variable['ciudad_residencia']."',";
                                $cadenaSql.=" '".$variable['direccion_residencia']."',";
                                $cadenaSql.=" '".$variable['correo']."',";
                                $cadenaSql.=" '".$variable['correo_secundario']."',";
                                $cadenaSql.=" '".$variable['telefono']."',";
                                $cadenaSql.=" '".$variable['celular']."'";
                                $cadenaSql.=" )";
                            break;                    
			case 'registroFormacion' :
                                $cadenaSql=" INSERT INTO concurso.formacion(";
                                $cadenaSql.=" consecutivo_formacion, ";
                                $cadenaSql.=" consecutivo_persona, ";
                                $cadenaSql.=" codigo_modalidad, ";
                                $cadenaSql.=" codigo_nivel, ";
                                $cadenaSql.=" pais_formacion, ";
                                $cadenaSql.=" codigo_institucion, ";
                                $cadenaSql.=" nombre_institucion, ";
                                $cadenaSql.=" codigo_programa, ";
                                $cadenaSql.=" nombre_programa, ";
                                $cadenaSql.=" cursos_aprobados, ";
                                $cadenaSql.=" cursos_temporalidad,";
                                $cadenaSql.=" graduado,";
                                $cadenaSql.=" fecha_grado,";
                                $cadenaSql.=" promedio)";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['codigo_modalidad']."',";
                                $cadenaSql.=" '".$variable['codigo_nivel']."',";
                                $cadenaSql.=" '".$variable['pais_formacion']."',";
                                $cadenaSql.=" '".$variable['codigo_institucion']."',";
                                if(isset($variable['codigo_institucion']) && $variable['codigo_institucion']==0)
                                    {$cadenaSql.=" '".$variable['nombre_institucion']."',";}
                                else {$cadenaSql.="(SELECT inst.nombre inst FROM general.institucion_educacion inst WHERE inst.codigo_ies='".$variable['codigo_institucion']."'),";}    
                                $cadenaSql.=" '".$variable['codigo_programa']."',";
                                if(isset($variable['codigo_programa']) && $variable['codigo_programa']==0)
                                    { $cadenaSql.=" '".$variable['nombre_programa']."',";}
                                else {$cadenaSql.="(SELECT prog.nombre FROM general.programa_ies prog WHERE prog.consecutivo_programa='".$variable['codigo_programa']."'),";}    
                                $cadenaSql.=" '".$variable['cursos_aprobados']."',";
                                $cadenaSql.=" '".$variable['cursos_temporalidad']."',";
                                $cadenaSql.=" '".$variable['graduado']."',";
                                $cadenaSql.=" '".$variable['fecha_grado']."',";
                                $cadenaSql.=" '".$variable['promedio']."'";
                                $cadenaSql.=" )";
                                $cadenaSql.=" RETURNING consecutivo_formacion";
                            break;                               
			case 'registroExperiencia' :
                                $cadenaSql=" INSERT INTO concurso.experiencia_laboral(";
                                $cadenaSql.=" consecutivo_experiencia,";
                                $cadenaSql.=" consecutivo_persona,";
                                $cadenaSql.=" codigo_nivel_experiencia, ";
                                $cadenaSql.=" pais_experiencia,";
                                $cadenaSql.=" codigo_nivel_institucion,";
                                $cadenaSql.=" codigo_institucion,";                                
                                $cadenaSql.=" nombre_institucion, ";
                                $cadenaSql.=" direccion_institucion,";
                                $cadenaSql.=" correo_institucion,";
                                $cadenaSql.=" telefono_institucion, ";
                                $cadenaSql.=" cargo,";
                                $cadenaSql.=" descripcion_cargo,";
                                $cadenaSql.=" actual,";
                                $cadenaSql.=" fecha_inicio,";
                                $cadenaSql.=" fecha_fin)";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['codigo_nivel_experiencia']."',";
                                $cadenaSql.=" '".$variable['pais_experiencia']."',";
                                $cadenaSql.=" '".$variable['codigo_nivel_institucion']."',";
                                $cadenaSql.=" '".$variable['codigo_institucion']."',";
                                $cadenaSql.=" '".$variable['nombre_institucion']."',";
                                $cadenaSql.=" '".$variable['direccion_institucion']."',";
                                $cadenaSql.=" '".$variable['correo_institucion']."',";
                                $cadenaSql.=" '".$variable['telefono_institucion']."',";
                                $cadenaSql.=" '".$variable['cargo']."',";
                                $cadenaSql.=" '".$variable['descripcion_cargo']."',";
                                $cadenaSql.=" '".$variable['actual']."',";
                                $cadenaSql.=" '".$variable['fecha_inicio']."',";
                                $cadenaSql.=" '".$variable['fecha_fin']."'";
                                $cadenaSql.=" )";
                                $cadenaSql.=" RETURNING consecutivo_experiencia";
                            break;   
			case 'registroDocencia' :
                                $cadenaSql=" INSERT INTO ";
                                $cadenaSql.=" concurso.experiencia_docencia(";
                                $cadenaSql.=" consecutivo_docencia,";
                                $cadenaSql.=" consecutivo_persona,";
                                $cadenaSql.=" codigo_nivel_docencia, ";
                                $cadenaSql.=" pais_docencia,";
                                $cadenaSql.=" codigo_nivel_institucion, ";
                                $cadenaSql.=" codigo_institucion, ";
                                $cadenaSql.=" nombre_institucion,";
                                $cadenaSql.=" direccion_institucion, ";
                                $cadenaSql.=" correo_institucion, ";
                                $cadenaSql.=" telefono_institucion, ";
                                $cadenaSql.=" codigo_vinculacion, ";
                                $cadenaSql.=" nombre_vinculacion, ";
                                $cadenaSql.=" descripcion_docencia, ";
                                $cadenaSql.=" actual, ";
                                $cadenaSql.=" fecha_inicio, ";
                                $cadenaSql.=" fecha_fin, ";
                                $cadenaSql.=" horas_catedra)";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['codigo_nivel_docencia']."',";
                                $cadenaSql.=" '".$variable['pais_docencia']."',";
                                $cadenaSql.=" '".$variable['nivel_institucion_docencia']."',";
                                $cadenaSql.=" '".$variable['codigo_institucion_docencia']."',";
                                if(isset($variable['codigo_institucion_docencia']) && $variable['codigo_institucion_docencia']==0)
                                    {$cadenaSql.=" '".$variable['nombre_institucion_docencia']."',";}
                                else {$cadenaSql.="(SELECT inst.nombre inst FROM general.institucion_educacion inst WHERE inst.codigo_ies='".$variable['codigo_institucion_docencia']."'),";}                                    
                                $cadenaSql.=" '".$variable['direccion_institucion_docencia']."',";
                                $cadenaSql.=" '".$variable['correo_institucion_docencia']."',";
                                $cadenaSql.=" '".$variable['telefono_institucion_docencia']."',";
                                $cadenaSql.=" '".$variable['codigo_vinculacion']."',";
                                if(isset($variable['codigo_vinculacion']) && $variable['codigo_vinculacion']==0)
                                     {$cadenaSql.=" '".$variable['nombre_vinculacion']."',";}
                                else {$cadenaSql.="(SELECT niv.nombre FROM general.nivel niv WHERE niv.codigo_nivel='".$variable['codigo_vinculacion']."'),";}                                    
                                $cadenaSql.=" '".$variable['descripcion_docencia']."',";
                                $cadenaSql.=" '".$variable['docencia_actual']."',";
                                $cadenaSql.=" '".$variable['fecha_inicio_docencia']."',";
                                $cadenaSql.=" '".$variable['fecha_fin_docencia']."',";
                                $cadenaSql.=" '".$variable['horas_catedra']."'";
                                $cadenaSql.=" )";
                                $cadenaSql.=" RETURNING consecutivo_docencia";
                            break;     
                        case 'registroActividad' :
                                $cadenaSql=" INSERT INTO ";
                                $cadenaSql.=" concurso.actividad_academica(";
                                $cadenaSql.=" consecutivo_actividad,";
                                $cadenaSql.=" consecutivo_persona, ";
                                $cadenaSql.=" pais_actividad, ";
                                $cadenaSql.=" codigo_nivel_institucion, ";
                                $cadenaSql.=" codigo_institucion, ";
                                $cadenaSql.=" nombre_institucion,";
                                $cadenaSql.=" correo_institucion, ";
                                $cadenaSql.=" telefono_institucion, ";
                                $cadenaSql.=" codigo_tipo_actividad,";
                                $cadenaSql.=" nombre_tipo_actividad, ";
                                $cadenaSql.=" nombre_actividad, ";
                                $cadenaSql.=" descripcion,";
                                $cadenaSql.=" jefe_actividad, ";
                                $cadenaSql.=" fecha_inicio, ";
                                $cadenaSql.=" fecha_fin)";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['pais_actividad']."',";
                                $cadenaSql.=" '".$variable['codigo_nivel_institucion']."',";
                                $cadenaSql.=" '".$variable['codigo_institucion_actividad']."',";
                                if(isset($variable['codigo_institucion_actividad']) && $variable['codigo_institucion_actividad']==0)
                                    {$cadenaSql.=" '".$variable['nombre_institucion_actividad']."',";}
                                else {$cadenaSql.="(SELECT inst.nombre inst FROM general.institucion_educacion inst WHERE inst.codigo_ies='".$variable['codigo_institucion_actividad']."'),";}                                    
                                $cadenaSql.=" '".$variable['correo_institucion_actividad']."',";
                                $cadenaSql.=" '".$variable['telefono_institucion_actividad']."',";
                                $cadenaSql.=" '".$variable['codigo_tipo_actividad']."',";
                                if(isset($variable['codigo_tipo_actividad']) && $variable['codigo_tipo_actividad']==0)
                                     {$cadenaSql.=" '".$variable['nombre_tipo_actividad']."',";}
                                else {$cadenaSql.="(SELECT niv.nombre FROM general.nivel niv WHERE niv.codigo_nivel='".$variable['codigo_tipo_actividad']."'),";}                                    
                                $cadenaSql.=" '".$variable['nombre_actividad']."',";
                                $cadenaSql.=" '".$variable['descripcion_actividad']."',";
                                $cadenaSql.=" '".$variable['jefe_actividad']."',";
                                $cadenaSql.=" '".$variable['fecha_inicio_actividad']."',";
                                $cadenaSql.=" '".$variable['fecha_fin_actividad']."'";
                                $cadenaSql.=" )";
                                $cadenaSql.=" RETURNING consecutivo_actividad";
                            break;                              
			case 'registroInvestigacion' :
                                $cadenaSql=" INSERT INTO ";
                                $cadenaSql.=" concurso.experiencia_investigacion(";
                                $cadenaSql.=" consecutivo_investigacion,";
                                $cadenaSql.=" consecutivo_persona, ";
                                $cadenaSql.=" pais_investigacion, ";
                                $cadenaSql.=" codigo_nivel_institucion,";
                                $cadenaSql.=" codigo_institucion, ";
                                $cadenaSql.=" nombre_institucion, ";
                                $cadenaSql.=" direccion_institucion,";
                                $cadenaSql.=" correo_institucion, ";
                                $cadenaSql.=" telefono_institucion, ";
                                $cadenaSql.=" titulo_investigacion, ";
                                $cadenaSql.=" jefe_investigacion, ";
                                $cadenaSql.=" descripcion_investigacion, ";
                                $cadenaSql.=" direccion_investigacion,";
                                $cadenaSql.=" actual,";
                                $cadenaSql.=" fecha_inicio,";
                                $cadenaSql.=" fecha_fin, ";
                                $cadenaSql.=" grupo_investigacion, ";
                                $cadenaSql.=" categoria_grupo, ";
                                $cadenaSql.=" rol_investigacion)";                            
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['pais_investigacion']."',";
                                $cadenaSql.=" '".$variable['nivel_institucion_investigacion']."',";
                                $cadenaSql.=" '".$variable['codigo_institucion_investigacion']."',";
                                if(isset($variable['codigo_institucion_investigacion']) && $variable['codigo_institucion_investigacion']==0)
                                    {$cadenaSql.=" '".$variable['nombre_institucion_investigacion']."',";}
                                else {$cadenaSql.="(SELECT inst.nombre inst FROM general.institucion_educacion inst WHERE inst.codigo_ies='".$variable['codigo_institucion_investigacion']."'),";}                                    
                                $cadenaSql.=" '".$variable['direccion_institucion_investigacion']."',";
                                $cadenaSql.=" '".$variable['correo_institucion_investigacion']."',";
                                $cadenaSql.=" '".$variable['telefono_institucion_investigacion']."',";
                                $cadenaSql.=" '".$variable['titulo_investigacion']."',";
                                $cadenaSql.=" '".$variable['jefe_investigacion']."',";
                                $cadenaSql.=" '".$variable['descripcion_investigacion']."',";
                                $cadenaSql.=" '".$variable['direccion_investigacion']."',";
                                $cadenaSql.=" '".$variable['investigacion_actual']."',";
                                $cadenaSql.=" '".$variable['fecha_inicio_investigacion']."',";
                                $cadenaSql.=" '".$variable['fecha_fin_investigacion']."',";
                                $cadenaSql.=" '".$variable['grupo_investigacion']."',";
                                $cadenaSql.=" '".$variable['categoria_grupo']."', ";
                                $cadenaSql.=" '".$variable['rol_investigacion']."' ";
                                $cadenaSql.=" )";
                                $cadenaSql.=" RETURNING consecutivo_investigacion";
                            break;
                        case 'registroProduccion' :
                                $cadenaSql=" INSERT INTO concurso.produccion_academica(";
                                $cadenaSql.=" consecutivo_produccion, ";
                                $cadenaSql.=" consecutivo_persona, ";
                                $cadenaSql.=" codigo_tipo_produccion, ";
                                $cadenaSql.=" nombre_tipo_produccion,";
                                $cadenaSql.=" titulo_produccion, ";
                                $cadenaSql.=" nombre_autor, ";
                                $cadenaSql.=" nombre_producto_incluye, ";
                                $cadenaSql.=" nombre_editorial, ";
                                $cadenaSql.=" volumen, ";
                                $cadenaSql.=" pagina, ";
                                $cadenaSql.=" codigo_isbn, ";
                                $cadenaSql.=" codigo_issn, ";
                                $cadenaSql.=" indexado, ";
                                $cadenaSql.=" pais_produccion,";
                                $cadenaSql.=" departamento_produccion, ";
                                $cadenaSql.=" ciudad_produccion, ";
                                $cadenaSql.=" descripcion,";
                                $cadenaSql.=" direccion_produccion, ";
                                $cadenaSql.=" fecha_produccion)";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['codigo_tipo_produccion']."',";
                                if(isset($variable['codigo_tipo_produccion']) && $variable['codigo_tipo_produccion']==0)
                                     {$cadenaSql.=" '".$variable['nombre_tipo_produccion']."',";}
                                else {$cadenaSql.="(SELECT niv.nombre FROM general.nivel niv WHERE niv.codigo_nivel='".$variable['codigo_tipo_produccion']."'),";}                                    
                                $cadenaSql.=" '".$variable['titulo_produccion']."',";
                                $cadenaSql.=" '".$variable['nombre_autor']."',";
                                $cadenaSql.=" '".$variable['nombre_producto_incluye']."',";
                                $cadenaSql.=" '".$variable['nombre_editorial']."',";
                                $cadenaSql.=" '".$variable['volumen']."',";
                                $cadenaSql.=" '".$variable['pagina_producto']."',";
                                $cadenaSql.=" '".$variable['codigo_isbn']."',";
                                $cadenaSql.=" '".$variable['codigo_issn']."',";
                                $cadenaSql.=" '".$variable['indexado']."',";
                                $cadenaSql.=" '".$variable['pais_produccion']."',";
                                $cadenaSql.=" '".$variable['departamento_produccion']."',";
                                $cadenaSql.=" '".$variable['ciudad_produccion']."',";
                                $cadenaSql.=" '".$variable['descripcion_produccion']."',";
                                $cadenaSql.=" '".$variable['direccion_produccion']."',";
                                $cadenaSql.=" '".$variable['fecha_produccion']."'";
                                $cadenaSql.=" )";
                                $cadenaSql.=" RETURNING consecutivo_produccion";
                            break;      
			case 'registroIdioma' :
                                $cadenaSql=" INSERT INTO concurso.conocimiento_idioma(";
                                $cadenaSql.=" consecutivo_conocimiento,";
                                $cadenaSql.=" consecutivo_persona,";
                                $cadenaSql.=" codigo_idioma, ";
                                $cadenaSql.=" nivel_lee, ";
                                $cadenaSql.=" nivel_escribe, ";
                                $cadenaSql.=" nivel_habla, ";
                                $cadenaSql.=" certificacion, ";
                                $cadenaSql.=" institucion_certificacion, ";
                                $cadenaSql.=" idioma_concurso )";
                                $cadenaSql.=" VALUES (";
                                $cadenaSql.=" DEFAULT,";
                                $cadenaSql.=" '".$variable['consecutivo_persona']."',";
                                $cadenaSql.=" '".$variable['codigo_idioma']."',";
                                $cadenaSql.=" '".$variable['nivel_lee']."',";
                                $cadenaSql.=" '".$variable['nivel_escribe']."',";
                                $cadenaSql.=" '".$variable['nivel_habla']."',";
                                $cadenaSql.=" '".$variable['certificacion']."',";
                                $cadenaSql.=" '".$variable['institucion_certificacion']."',";
                                $cadenaSql.=" '".$variable['idioma_concurso']."'";
                                $cadenaSql.=" )";
                                $cadenaSql.=" RETURNING consecutivo_conocimiento";
                            break;                                   
                        case "actualizarBasicos":
                                $cadenaSql=" UPDATE concurso.persona";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" nombre='".$variable['nombre']."', ";
                                $cadenaSql.=" apellido='".$variable['apellido']."', ";
                                $cadenaSql.=" lugar_nacimiento='".$variable['lugar_nacimiento']."', ";
                                $cadenaSql.=" fecha_nacimiento='".$variable['fecha_nacimiento']."', ";
                                $cadenaSql.=" pais_nacimiento='".$variable['pais_nacimiento']."', ";
                                $cadenaSql.=" departamento_nacimiento='".$variable['departamento_nacimiento']."', ";
                                $cadenaSql.=" sexo='".$variable['sexo']."', ";
                                $cadenaSql.=" lugar_identificacion='".$variable['lugar_identificacion']."', ";
                                $cadenaSql.=" fecha_identificacion='".$variable['fecha_identificacion']."', ";
                                $cadenaSql.=" codigo_idioma_nativo='".$variable['codigo_idioma_nativo']."', ";
                                $cadenaSql.=" autorizacion='TRUE' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo='".$variable['consecutivo']."' ";
                                $cadenaSql.=  " RETURNING consecutivo ";
                            break;  
                        case "actualizarDatosUsuario":
                            
				$cadenaSql = "UPDATE ".$prefijo."usuario SET ";
                                $cadenaSql .= " nombre = '".$variable['nombre']."', ";
                                $cadenaSql .= " apellido = '".$variable['apellido']."'  ";
                                $cadenaSql .= " WHERE id_usuario = '".$variable['id_usuario']."' ";
                                $cadenaSql.=" RETURNING id_usuario";
			break;                        
                        case "actualizarContacto":
                                $cadenaSql=" UPDATE concurso.contacto";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" pais_residencia='".$variable['pais_residencia']."',";
                                $cadenaSql.=" departamento_residencia= '".$variable['departamento_residencia']."',";
                                $cadenaSql.=" ciudad_residencia='".$variable['ciudad_residencia']."',";
                                $cadenaSql.=" direccion_residencia='".$variable['direccion_residencia']."',";
                                $cadenaSql.=" correo='".$variable['correo']."',";
                                $cadenaSql.=" correo_secundario='".$variable['correo_secundario']."',";
                                $cadenaSql.=" telefono='".$variable['telefono']."',";
                                $cadenaSql.=" celular='".$variable['celular']."'";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_contacto='".$variable['consecutivo_contacto']."' ";
                                $cadenaSql.=  " RETURNING consecutivo_contacto ";
                            break;              
                        case "actualizarContactoUsuario":
                            
				$cadenaSql = "UPDATE ".$prefijo."usuario SET ";
                                $cadenaSql .= " correo = '".$variable['correo']."', ";
                                $cadenaSql .= " telefono = '".$variable['telefono']."' ";
                                $cadenaSql .= " WHERE id_usuario = '".$variable['id_usuario']."' ";
                                $cadenaSql.=" RETURNING id_usuario";
			break;                        
                        
                        case "actualizarFormacion":
                                $cadenaSql=" UPDATE concurso.formacion";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" codigo_modalidad='".$variable['codigo_modalidad']."',";
                                $cadenaSql.=" codigo_nivel='".$variable['codigo_nivel']."',";
                                $cadenaSql.=" pais_formacion='".$variable['pais_formacion']."',";
                                $cadenaSql.=" codigo_institucion='".$variable['codigo_institucion']."',";
                                
                                if(isset($variable['codigo_institucion']) && $variable['codigo_institucion']==0)
                                     {$cadenaSql.=" nombre_institucion='".$variable['nombre_institucion']."',";}
                                else {$cadenaSql.="nombre_institucion=(SELECT inst.nombre inst FROM general.institucion_educacion inst WHERE inst.codigo_ies='".$variable['codigo_institucion']."'),";}    
                                $cadenaSql.=" codigo_programa='".$variable['codigo_programa']."',";
                                if(isset($variable['codigo_programa']) && $variable['codigo_programa']==0)
                                    { $cadenaSql.="nombre_programa='".$variable['nombre_programa']."',";}
                                else {$cadenaSql.="nombre_programa=(SELECT prog.nombre FROM general.programa_ies prog WHERE prog.consecutivo_programa='".$variable['codigo_programa']."'),";}   
                                
                                $cadenaSql.=" cursos_aprobados='".$variable['cursos_aprobados']."',";
                                $cadenaSql.=" cursos_temporalidad='".$variable['cursos_temporalidad']."',";
                                $cadenaSql.=" graduado='".$variable['graduado']."',";
                                $cadenaSql.=" fecha_grado='".$variable['fecha_grado']."',";
                                $cadenaSql.=" promedio='".$variable['promedio']."'";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_formacion='".$variable['consecutivo_formacion']."' ";
                                $cadenaSql.=  " RETURNING consecutivo_formacion ";
                            break;        
                        case 'actualizarExperiencia' :
                                $cadenaSql=" UPDATE concurso.experiencia_laboral";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" codigo_nivel_experiencia='".$variable['codigo_nivel_experiencia']."', ";
                                $cadenaSql.=" pais_experiencia='".$variable['pais_experiencia']."', ";
                                $cadenaSql.=" codigo_nivel_institucion='".$variable['codigo_nivel_institucion']."', ";
                                $cadenaSql.=" codigo_institucion='".$variable['codigo_institucion']."', ";
                                $cadenaSql.=" nombre_institucion='".$variable['nombre_institucion']."', ";
                                $cadenaSql.=" direccion_institucion='".$variable['direccion_institucion']."', ";
                                $cadenaSql.=" correo_institucion='".$variable['correo_institucion']."', ";
                                $cadenaSql.=" telefono_institucion='".$variable['telefono_institucion']."', ";
                                $cadenaSql.=" cargo='".$variable['cargo']."', ";
                                $cadenaSql.=" descripcion_cargo='".$variable['descripcion_cargo']."', ";
                                $cadenaSql.=" actual='".$variable['actual']."', ";
                                $cadenaSql.=" fecha_inicio='".$variable['fecha_inicio']."', ";
                                $cadenaSql.=" fecha_fin='".$variable['fecha_fin']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_experiencia='".$variable['consecutivo_experiencia']."' ";
                                $cadenaSql.=  " RETURNING consecutivo_experiencia ";
                            break;                     
                        case 'actualizarDocencia' :
                                $cadenaSql=" UPDATE concurso.experiencia_docencia ";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" codigo_nivel_docencia='".$variable['codigo_nivel_docencia']."', ";
                                $cadenaSql.=" pais_docencia='".$variable['pais_docencia']."', ";
                                $cadenaSql.=" codigo_nivel_institucion='".$variable['nivel_institucion_docencia']."', ";
                                $cadenaSql.=" codigo_institucion='".$variable['codigo_institucion_docencia']."', ";
                                if(isset($variable['codigo_institucion_docencia']) && $variable['codigo_institucion_docencia']==0)
                                     {$cadenaSql.=" nombre_institucion='".$variable['nombre_institucion_docencia']."', ";}
                                else {$cadenaSql.=" nombre_institucion=(SELECT inst.nombre inst FROM general.institucion_educacion inst WHERE inst.codigo_ies='".$variable['codigo_institucion_docencia']."'),";}                                    
                                $cadenaSql.=" direccion_institucion='".$variable['direccion_institucion_docencia']."', ";
                                $cadenaSql.=" correo_institucion='".$variable['correo_institucion_docencia']."', ";
                                $cadenaSql.=" telefono_institucion='".$variable['telefono_institucion_docencia']."', ";
                                $cadenaSql.=" codigo_vinculacion='".$variable['codigo_vinculacion']."', ";
                                if(isset($variable['codigo_vinculacion']) && $variable['codigo_vinculacion']==0)
                                     { $cadenaSql.=" nombre_vinculacion='".['nombre_vinculacion']."',";}
                                else {$cadenaSql.="nombre_vinculacion= (SELECT niv.nombre FROM general.nivel niv WHERE niv.codigo_nivel='".$variable['codigo_vinculacion']."'),";}                                    
                                $cadenaSql.=" descripcion_docencia='".$variable['descripcion_docencia']."', ";
                                $cadenaSql.=" actual='".$variable['docencia_actual']."', ";
                                $cadenaSql.=" fecha_inicio='".$variable['fecha_inicio_docencia']."', ";
                                $cadenaSql.=" fecha_fin='".$variable['fecha_fin_docencia']."', ";
                                $cadenaSql.=" horas_catedra='".$variable['horas_catedra']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_docencia='".$variable['consecutivo_docencia']."' ";
                                $cadenaSql.=  " RETURNING consecutivo_docencia ";
                            break;        
                        case 'actualizarActividad' :
                                $cadenaSql=" UPDATE";
                                $cadenaSql.=" concurso.actividad_academica";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" pais_actividad='".$variable['pais_actividad']."', ";
                                $cadenaSql.=" codigo_nivel_institucion='".$variable['codigo_nivel_institucion']."', ";
                                $cadenaSql.=" codigo_institucion='".$variable['codigo_institucion_actividad']."', ";
                                if(isset($variable['codigo_institucion_actividad']) && $variable['codigo_institucion_actividad']==0)
                                     {$cadenaSql.=" nombre_institucion='".$variable['nombre_institucion_actividad']."', ";}
                                else {$cadenaSql.=" nombre_institucion=(SELECT inst.nombre inst FROM general.institucion_educacion inst WHERE inst.codigo_ies='".$variable['codigo_institucion_actividad']."'),";}                                    
                                $cadenaSql.=" correo_institucion='".$variable['correo_institucion_actividad']."', ";
                                $cadenaSql.=" telefono_institucion='".$variable['telefono_institucion_actividad']."', ";
                                $cadenaSql.=" codigo_tipo_actividad='".$variable['codigo_tipo_actividad']."', ";
                                if(isset($variable['codigo_tipo_actividad']) && $variable['codigo_tipo_actividad']==0)
                                     {$cadenaSql.="nombre_tipo_actividad='".$variable['nombre_tipo_actividad']."',";}
                                else {$cadenaSql.="nombre_tipo_actividad=(SELECT niv.nombre FROM general.nivel niv WHERE niv.codigo_nivel='".$variable['codigo_tipo_actividad']."'),";}                                    
                                $cadenaSql.=" nombre_actividad='".$variable['nombre_actividad']."', ";
                                $cadenaSql.=" descripcion='".$variable['descripcion_actividad']."', ";
                                $cadenaSql.=" jefe_actividad='".$variable['jefe_actividad']."', ";
                                $cadenaSql.=" fecha_inicio='".$variable['fecha_inicio_actividad']."', ";
                                $cadenaSql.=" fecha_fin='".$variable['fecha_fin_actividad']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_actividad='".$variable['consecutivo_actividad']."'";
                                $cadenaSql.=  " RETURNING consecutivo_actividad ";
                            break;                                 
                        case 'actualizarInvestigacion' :
                                $cadenaSql=" UPDATE concurso.experiencia_investigacion ";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" pais_investigacion='".$variable['pais_investigacion']."', ";
                                $cadenaSql.=" codigo_nivel_institucion='".$variable['nivel_institucion_investigacion']."', ";
                                $cadenaSql.=" codigo_institucion='".$variable['codigo_institucion_investigacion']."', ";
                                if(isset($variable['codigo_institucion_investigacion']) && $variable['codigo_institucion_investigacion']==0)
                                     {$cadenaSql.=" nombre_institucion='".$variable['nombre_institucion_investigacion']."', ";}
                                else {$cadenaSql.=" nombre_institucion=(SELECT inst.nombre inst FROM general.institucion_educacion inst WHERE inst.codigo_ies='".$variable['codigo_institucion_investigacion']."'),";}                                    
                                $cadenaSql.=" direccion_institucion='".$variable['direccion_institucion_investigacion']."', ";
                                $cadenaSql.=" correo_institucion='".$variable['correo_institucion_investigacion']."', ";
                                $cadenaSql.=" telefono_institucion='".$variable['telefono_institucion_investigacion']."', ";
                                $cadenaSql.=" titulo_investigacion='".$variable['titulo_investigacion']."', ";
                                $cadenaSql.=" jefe_investigacion='".$variable['jefe_investigacion']."', ";
                                $cadenaSql.=" descripcion_investigacion='".$variable['descripcion_investigacion']."', ";
                                $cadenaSql.=" direccion_investigacion='".$variable['direccion_investigacion']."', ";
                                $cadenaSql.=" actual='".$variable['investigacion_actual']."', ";
                                $cadenaSql.=" fecha_inicio='".$variable['fecha_inicio_investigacion']."', ";
                                $cadenaSql.=" fecha_fin='".$variable['fecha_fin_investigacion']."', ";
                                $cadenaSql.=" grupo_investigacion='".$variable['grupo_investigacion']."', ";
                                $cadenaSql.=" categoria_grupo='".$variable['categoria_grupo']."', ";
                                $cadenaSql.=" rol_investigacion='".$variable['rol_investigacion']."'";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_investigacion='".$variable['consecutivo_investigacion']."' ";
                                $cadenaSql.=  " RETURNING consecutivo_investigacion ";
                            break;    
                        case 'actualizarProduccion' :
                                $cadenaSql=" UPDATE concurso.produccion_academica";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" codigo_tipo_produccion='".$variable['codigo_tipo_produccion']."', ";
                                if(isset($variable['codigo_tipo_produccion']) && $variable['codigo_tipo_produccion']==0)
                                     { $cadenaSql.="nombre_tipo_produccion='".$variable['nombre_tipo_produccion']."',";}
                                else {$cadenaSql.="nombre_tipo_produccion= (SELECT niv.nombre FROM general.nivel niv WHERE niv.codigo_nivel='".$variable['codigo_tipo_produccion']."'),";}                                    
                                $cadenaSql.=" titulo_produccion='".$variable['titulo_produccion']."', ";
                                $cadenaSql.=" nombre_autor='".$variable['nombre_autor']."', ";
                                $cadenaSql.=" nombre_producto_incluye='".$variable['nombre_producto_incluye']."', ";
                                $cadenaSql.=" nombre_editorial='".$variable['nombre_editorial']."', ";
                                $cadenaSql.=" volumen='".$variable['volumen']."', ";
                                $cadenaSql.=" pagina='".$variable['pagina_producto']."', ";
                                $cadenaSql.=" codigo_isbn='".$variable['codigo_isbn']."', ";
                                $cadenaSql.=" codigo_issn='".$variable['codigo_issn']."', ";
                                $cadenaSql.=" indexado='".$variable['indexado']."', ";
                                $cadenaSql.=" pais_produccion='".$variable['pais_produccion']."', ";
                                $cadenaSql.=" departamento_produccion='".$variable['departamento_produccion']."', ";
                                $cadenaSql.=" ciudad_produccion='".$variable['ciudad_produccion']."', ";
                                $cadenaSql.=" descripcion='".$variable['descripcion_produccion']."', ";
                                $cadenaSql.=" direccion_produccion='".$variable['direccion_produccion']."', ";
                                $cadenaSql.=" fecha_produccion='".$variable['fecha_produccion']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_produccion='".$variable['consecutivo_produccion']."' ";
                                $cadenaSql.=  " RETURNING consecutivo_produccion ";
                            break;                                
			case 'actualizarIdioma' :
                                $cadenaSql=" UPDATE concurso.conocimiento_idioma ";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" codigo_idioma='".$variable['codigo_idioma']."',";
                                $cadenaSql.=" nivel_lee='".$variable['nivel_lee']."',";
                                $cadenaSql.=" nivel_escribe='".$variable['nivel_escribe']."',";
                                $cadenaSql.=" nivel_habla='".$variable['nivel_habla']."',";
                                $cadenaSql.=" certificacion='".$variable['certificacion']."',";
                                $cadenaSql.=" institucion_certificacion='".$variable['institucion_certificacion']."',";
                                $cadenaSql.=" idioma_concurso='".$variable['idioma_concurso']."'";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_conocimiento='".$variable['consecutivo_conocimiento']."' ";
                                $cadenaSql.=  " RETURNING consecutivo_conocimiento ";
                            break;            

                        case "borrarFormacion":
				$cadenaSql = "DELETE FROM concurso.formacion ";
                                $cadenaSql.= " WHERE consecutivo_formacion = '".$variable['consecutivo']."' ";
                                $cadenaSql.= " AND consecutivo_persona = '".$variable['persona']."' ";
                            break;                        
                        case "borrarProfesional":
				$cadenaSql = "DELETE FROM concurso.experiencia_laboral ";
                                $cadenaSql.= " WHERE consecutivo_experiencia = '".$variable['consecutivo']."' ";
                                $cadenaSql.= " AND consecutivo_persona = '".$variable['persona']."' ";
                            break;                        
                        case "borrarDocencia":
				$cadenaSql = "DELETE FROM concurso.experiencia_docencia ";
                                $cadenaSql.= " WHERE consecutivo_docencia = '".$variable['consecutivo']."' ";
                                $cadenaSql.= " AND consecutivo_persona = '".$variable['persona']."' ";
                            break;                        
                        case "borrarActividad":
				$cadenaSql = "DELETE FROM concurso.actividad_academica ";
                                $cadenaSql.= " WHERE consecutivo_actividad = '".$variable['consecutivo']."' ";
                                $cadenaSql.= " AND consecutivo_persona = '".$variable['persona']."' ";
                            break;                        
                        case "borrarInvestigacion":
				$cadenaSql = "DELETE FROM concurso.experiencia_investigacion ";
                                $cadenaSql.= " WHERE consecutivo_investigacion = '".$variable['consecutivo']."' ";
                                $cadenaSql.= " AND consecutivo_persona = '".$variable['persona']."' ";
                            break;                        
                        case "borrarProduccion":
				$cadenaSql = "DELETE FROM concurso.produccion_academica ";
                                $cadenaSql.= " WHERE consecutivo_produccion = '".$variable['consecutivo']."' ";
                                $cadenaSql.= " AND consecutivo_persona = '".$variable['persona']."' ";
                            break;                        
                        case "borrarIdiomas":
				$cadenaSql = "DELETE FROM concurso.conocimiento_idioma ";
                                $cadenaSql.= " WHERE consecutivo_conocimiento = '".$variable['consecutivo']."' ";
                                $cadenaSql.= " AND consecutivo_persona = '".$variable['persona']."' ";
                            break;                        

                        
                        
                    /*viejas consultas para revisar*/
                        case "consultarLogUsuario":
				$cadenaSql = "SELECT DISTINCT id_usuario ";
                                $cadenaSql .= "FROM ".$prefijo."log_usuario ";
                                $cadenaSql .= " WHERE  id_usuario = '".$variable['id_usuario']."'";
                            break;

                        case "tipoIdentificacion":
				$cadenaSql = "SELECT   tipo_identificacion,  tipo_nombre ";
                                $cadenaSql .= "FROM ".$prefijo."tipo_identificacion ";
                                $cadenaSql .= " WHERE  tipo_estado = 1";
                                $cadenaSql .= " ORDER BY tipo_nombre ASC";
                            break;                    
                    
                        case "borrarUsuario":
				$cadenaSql = "DELETE FROM ".$prefijo."usuario ";
                                $cadenaSql .= " WHERE id_usuario = '".$variable['id_usuario']."' ";
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
