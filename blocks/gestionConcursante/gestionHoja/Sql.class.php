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
				$cadenaSql .= 'id_pais = 112;';
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
                                $cadenaSql .= 'ab_pais = \'CO\';';
                                /*
                                if(isset($variable))
                                    {  $cadenaSql .= "id_ciudad = ' . $variable . ' ";
                                    }
                                else {$cadenaSql .= 'ab_pais = \'CO\';';}    */
				
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
                            
	
			case 'buscarTipoSoporte' :
				$cadenaSql=" SELECT DISTINCT";
                                $cadenaSql.=" tipo_soporte,";
                                $cadenaSql.=" nombre,";
                                $cadenaSql.=" ubicacion,";
                                $cadenaSql.=" descripcion,";
                                $cadenaSql.=" estado,";
                                $cadenaSql.=" extencion_permitida";
                                $cadenaSql.=" FROM general.tipo_soporte";
                                $cadenaSql.=" WHERE ";
				$cadenaSql.=" nombre = '".$variable['tipo_soporte']."'";
                                $cadenaSql.=" AND estado='A' ";
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
                                $cadenaSql.=" bas.sexo ";
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
                                $cadenaSql.=" bas.apellido,";
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
                                $cadenaSql.=" form.graduado, ";
                                $cadenaSql.=" form.fecha_grado";
                                $cadenaSql.=" FROM concurso.persona bas ";
                                $cadenaSql.=" INNER JOIN ".$prefijo."usuario usu ON trim(usu.tipo_identificacion)=trim(bas.tipo_identificacion) AND bas.identificacion=usu.identificacion";
                                $cadenaSql.=" INNER JOIN concurso.formacion form ON form.consecutivo_persona=bas.consecutivo";
                                $cadenaSql.=" INNER JOIN general.modalidad_educacion modo ON modo.codigo_modalidad=form.codigo_modalidad ";
                                $cadenaSql.=" INNER JOIN general.nivel nv ON nv.codigo_nivel=form.codigo_nivel";
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
                                $cadenaSql.=" graduado,";
                                $cadenaSql.=" fecha_grado)";
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
                                $cadenaSql.=" '".$variable['graduado']."',";
                                $cadenaSql.=" '".$variable['fecha_grado']."'";
                                $cadenaSql.=" )";
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
                                $cadenaSql.=" sexo='".$variable['sexo']."' ";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo='".$variable['consecutivo']."' ";
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
                                
                    	break;                      

                        case "actualizarFormacion":
                                $cadenaSql=" UPDATE concurso.formacion";
                                $cadenaSql.=" SET ";
                                $cadenaSql.=" codigo_modalidad='".$variable['codigo_modalidad']."',";
                                $cadenaSql.=" codigo_nivel='".$variable['codigo_nivel']."',";
                                $cadenaSql.=" pais_formacion='".$variable['pais_formacion']."',";
                                $cadenaSql.=" codigo_institucion='".$variable['codigo_institucion']."',";
                                $cadenaSql.=" nombre_institucion='".$variable['nombre_institucion']."',";
                                $cadenaSql.=" codigo_programa='".$variable['codigo_programa']."',";
                                $cadenaSql.=" nombre_programa='".$variable['nombre_programa']."',";
                                $cadenaSql.=" cursos_aprobados='".$variable['cursos_aprobados']."',";
                                $cadenaSql.=" graduado='".$variable['graduado']."',";
                                $cadenaSql.=" fecha_grado='".$variable['fecha_grado']."'";
                                $cadenaSql.=" WHERE ";
                                $cadenaSql.=" consecutivo_formacion='".$variable['consecutivo_formacion']."' ";
                                
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
