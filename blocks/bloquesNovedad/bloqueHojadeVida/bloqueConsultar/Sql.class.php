<?php

namespace bloquesNovedad\bloqueHojadeVida\bloqueConsultar;

if (! isset ( $GLOBALS ["autorizado"] )) {
    include ("../index.php");
    exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

/**
 * IMPORTANTE: Se recomienda que no se borren registros. Utilizar mecanismos para - independiente del motor de bases de datos,
 * poder realizar rollbacks gestionados por el aplicativo.
 */



class Sql extends \Sql {
    
    var $miConfigurador;
    
    function getCadenaSql($tipo, $variable = '') {
        
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
        	
        	case 'buscarInfoIdent' :
        		 
        		$cadenaSql = 'SELECT ';
        		$cadenaSql .= 'id_datos_identificacion as ID_IDENTIFICACION, ';
        		$cadenaSql .= 'id_ubicacion as ID_UBICACION,';
        		$cadenaSql .= 'fecha_expe_documento as FECHA_EXPE,';
        		$cadenaSql .= 'soporte_identificacion as SOPORTE ';
        		$cadenaSql .= 'FROM ';
        		$cadenaSql .= 'novedad.identificacion_expedicion ';
        		$cadenaSql .= 'WHERE ';
        		$cadenaSql .= 'documento = '.$variable.';';
        		break;
        	
        	case 'buscarTipoDoc' :
        		 
        		$cadenaSql = 'SELECT ';
        		$cadenaSql .= 'tipo_documento as TIPO ';
        		//$cadenaSql .= 'nombre as NOMBRE ';
        		$cadenaSql .= 'FROM ';
        		$cadenaSql .= 'persona.persona_natural ';
        		$cadenaSql .= 'WHERE ';
        		$cadenaSql .= 'documento = '.$variable.';';
        		break;
        		
			case 'buscarSegundoApellido' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'segundo_apellido as APELLIDO2 ';
				// $cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'persona.persona_natural ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'documento = ' . $variable . ';';
				break;
        			
			case 'buscarPrimerNombre' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'primer_nombre as NOMBRE1 ';
				// $cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'persona.persona_natural ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'documento = ' . $variable . ';';
				break;
			
			case 'buscarSegundoNombre' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'segundo_nombre as NOMBRE2 ';
				// $cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'persona.persona_natural ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'documento = ' . $variable . ';';
				break;
			
			case 'buscarPrimerApellido' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'primer_apellido as APELLIDO1 ';
				// $cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'persona.persona_natural ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'documento = ' . $variable . ';';
				break;
        	
            case 'insertarUbicacionExpedicion' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'otro.ubicacion ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'id_pais,';
                $cadenaSql .= 'id_departamento,';
                $cadenaSql .= 'id_ciudad';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .=  $variable ['paisExpedicion'] . ', ';
                $cadenaSql .=  $variable ['departamentoExpedicion'] . ', ';
                $cadenaSql .=  $variable ['ciudadExpedicion'] . ' ';
                $cadenaSql .= ') ';
                $cadenaSql .= "RETURNING  id_ubicacion; ";
                break;
                
            case 'insertarIdentificacionDocumento' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.identificacion_expedicion ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'documento,';
				$cadenaSql .= 'fecha_expe_documento,';
				$cadenaSql .= 'soporte_identificacion';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .=  $variable ['fk_ubicacion_expedicion'] . ', ';
				$cadenaSql .=  $variable ['numeroDocumento'] . ', ';
				$cadenaSql .= '\'' . $variable ['fechaExpedicionDocumento'] . '\', ';
				$cadenaSql .= '\'' . $variable ['soporteDocumento'] . '\' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_datos_identificacion; ";
				break;
                
                
            case 'insertarUbicacionNacimiento' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_pais,';
				$cadenaSql .= 'id_departamento,';
				$cadenaSql .= 'id_ciudad';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .=  $variable ['paisNacimiento'] . ', ';
				$cadenaSql .=  $variable ['departamentoNacimiento'] . ', ';
				$cadenaSql .=  $variable ['ciudadNacimiento'] . ' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_ubicacion; ";
				break;
                
			case 'insertarInformacionPersonalBasica' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.informacion_personal_basica ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'fecha_nacimiento,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'genero,';
				$cadenaSql .= 'estado_civil,';
				$cadenaSql .= 'edad,';
				$cadenaSql .= 'tipo_sangre,';
				$cadenaSql .= 'rh_sangre,';
				$cadenaSql .= 'tipo_libreta_militar,';
				$cadenaSql .= 'numero_libreta,';
				$cadenaSql .= 'numero_distrito_militar,';
				$cadenaSql .= 'soporte_libreta,';
				$cadenaSql .= 'grupo_etnico,';
				$cadenaSql .= 'comunidad_lgbt,';
				$cadenaSql .= 'cabeza_familia,';
				$cadenaSql .= 'personas_a_cargo,';
				$cadenaSql .= 'soporte_caracterizacion';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable ['fechaNacimiento'] . '\', ';
				$cadenaSql .=  $variable ['fk_ubicacion'] . ', ';
				$cadenaSql .= '\'' . $_REQUEST ['funcionarioGenero'] . '\', ';
				$cadenaSql .= '\'' . $_REQUEST ['funcionarioEstadoCivil'] . '\', ';
				$cadenaSql .=  $variable ['edadNacimiento'] . ', ';
				if($_REQUEST ['funcionarioTipoSangre'] != 'NULL'){
					$cadenaSql .= '\'' . $_REQUEST ['funcionarioTipoSangre'] . '\', ';
				}else{
					$cadenaSql .= $_REQUEST ['funcionarioTipoSangre'] . ', ';
				}
				if($_REQUEST ['funcionarioSangreRH'] != 'NULL'){
					$cadenaSql .= '\'' . $_REQUEST ['funcionarioSangreRH'] . '\', ';
				}else{
					$cadenaSql .= $_REQUEST ['funcionarioSangreRH'] . ', ';
				}
				if($_REQUEST ['funcionarioTipoLibreta'] != 'NULL'){
					$cadenaSql .= '\'' . $_REQUEST ['funcionarioTipoLibreta'] . '\', ';
				}else{
					$cadenaSql .= $_REQUEST ['funcionarioTipoLibreta'] . ', ';
				}
				if($variable ['numeroLibreta'] > 0){
					$cadenaSql .=  $variable ['numeroLibreta'] . ', ';
				}else{
					$cadenaSql .= 'NULL, ';
				}
				if($variable ['numeroDistritoLibreta'] > 0){
					$cadenaSql .=  $variable ['numeroDistritoLibreta'] . ', ';
				}else{
					$cadenaSql .= 'NULL, ';
				}
				if($variable ['soporteLibreta'] != NULL){
					$cadenaSql .= '\'' . $variable ['soporteLibreta'] . '\', ';
				}else{
					$cadenaSql .= '\'\', ';
				}
				
				if($_REQUEST ['funcionarioGrupoEtnico'] != 'NULL'){
					$cadenaSql .= '\'' . $_REQUEST ['funcionarioGrupoEtnico'] . '\', ';
				}else{
					$cadenaSql .= $_REQUEST ['funcionarioGrupoEtnico'] . ', ';
				}
				$cadenaSql .=  $_REQUEST ['funcionarioGrupoLGBT'] . ', ';
				$cadenaSql .=  $_REQUEST ['funcionarioCabezaFamilia'] . ', ';
				$cadenaSql .=  $_REQUEST ['funcionarioPersonasCargo'] . ', ';
				$cadenaSql .= '\'' . $variable ['soporteCaracterizacion'] . '\' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_informacion_personal_basica; ";
				break;
				
			case 'insertarUbicacionContacto' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_pais,';
				$cadenaSql .= 'id_departamento,';
				$cadenaSql .= 'id_ciudad';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['paisContacto'] . ', ';
				$cadenaSql .= $variable ['departamentoContacto'] . ', ';
				$cadenaSql .= $variable ['ciudadContacto'] . ' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_ubicacion; ";
				break;
				
			case 'insertarDatosResidenciaCont' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.informacion_residencia_contacto ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'nacionalidad,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'localidad,';
				$cadenaSql .= 'barrio,';
				$cadenaSql .= 'direccion_residencia,';
				$cadenaSql .= 'estrato,';
				$cadenaSql .= 'soporte_estrato,';
				$cadenaSql .= 'soporte_residencia,';
				$cadenaSql .= 'telefono_fijo,';
				$cadenaSql .= 'telefono_movil,';
				$cadenaSql .= 'correo_personal,';
				$cadenaSql .= 'telefono_oficina,';
				$cadenaSql .= 'correo_oficina,';
				$cadenaSql .= 'direccion_oficina,';
				$cadenaSql .= 'cargo';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable ['nacionalidad'] . '\', ';
				$cadenaSql .= $variable ['fk_ubicacion'] . ', ';
				$cadenaSql .= '\'' . $variable ['localidadContacto'] . '\', ';
				$cadenaSql .= '\'' . $variable ['barrioContacto'] . '\', ';
				$cadenaSql .= '\'' . $variable ['direccionContacto'] . '\', ';		
				if($_REQUEST ['funcionarioContactoEstrato'] != 'NULL'){
					$cadenaSql .= '\'' . $_REQUEST ['funcionarioContactoEstrato'] . '\', ';
				}else{
					$cadenaSql .= $_REQUEST ['funcionarioContactoEstrato'] . ', ';
				}
				$cadenaSql .= '\'' . $variable ['soporteEstrato'] . '\', ';
				$cadenaSql .= '\'' . $variable ['soporteResidencia'] . '\', ';
				$cadenaSql .= $variable ['telefonoFijoContacto'] . ', ';
				$cadenaSql .= $variable ['telefonoMovilContacto'] . ', ';
				$cadenaSql .= '\'' . $variable ['emailContacto'] . '\', ';	
				if($variable ['telefonoFijoOficina'] > 0){
					$cadenaSql .=  $variable ['telefonoFijoOficina'] . ', ';
				}else{
					$cadenaSql .= 'NULL, ';
				}
				$cadenaSql .= '\'' . $variable ['emailOficina'] . '\', ';
				$cadenaSql .= '\'' . $variable ['direccionOficina'] . '\', ';
				$cadenaSql .= '\'' . $variable ['cargoOficina'] . '\' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_datos_residencia; ";
				break;
				
			case 'insertarUbicacionFormacionBasica' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_pais,';
				$cadenaSql .= 'id_departamento,';
				$cadenaSql .= 'id_ciudad';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['paisFormacionBasica'] . ', ';
				$cadenaSql .= $variable ['departamentoFormacionBasica'] . ', ';
				$cadenaSql .= $variable ['ciudadFormacionBasica'] . ' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_ubicacion; ";
				break;
				
			case 'insertarFormacionBasica' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.formacion_basica ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'modalidad,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'nombre_colegio,';
				$cadenaSql .= 'titulo_obtenido,';
				$cadenaSql .= 'fecha_graduacion,';
				$cadenaSql .= 'soporte_graduacion';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable ['modalidadBasica'] . '\', ';
				$cadenaSql .= $variable ['fk_ubicacion'] . ', ';
				$cadenaSql .= '\'' . $variable ['colegioBasica'] . '\', ';
				$cadenaSql .= '\'' . $variable ['tituloBasica'] . '\', ';
				$cadenaSql .= '\'' . $variable ['fechaGradoBasica'] . '\', ';
				$cadenaSql .= '\'' . $variable ['soporteBasica'] . '\' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_formacion_basica; ";
				break;
				
			case 'insertarUbicacionFormacionMedia' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_pais,';
				$cadenaSql .= 'id_departamento,';
				$cadenaSql .= 'id_ciudad';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['paisFormacionMedia'] . ', ';
				$cadenaSql .= $variable ['departamentoFormacionMedia'] . ', ';
				$cadenaSql .= $variable ['ciudadFormacionMedia'] . ' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_ubicacion; ";
				break;
			
			case 'insertarFormacionMedia' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.formacion_media ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'modalidad,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'nombre_colegio,';
				$cadenaSql .= 'titulo_obtenido,';
				$cadenaSql .= 'fecha_graduacion,';
				$cadenaSql .= 'soporte_graduacion';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable ['modalidadMedia'] . '\', ';
				$cadenaSql .= $variable ['fk_ubicacion'] . ', ';
				$cadenaSql .= '\'' . $variable ['colegioMedia'] . '\', ';
				$cadenaSql .= '\'' . $variable ['tituloMedia'] . '\', ';
				$cadenaSql .= '\'' . $variable ['fechaGradoMedia'] . '\', ';
				$cadenaSql .= '\'' . $variable ['soporteMedia'] . '\' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_formacion_media; ";
				break;
				
			case 'insertarFormacionFuncionario' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.formacion_academica_funcionario ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_formacion_basica,';
				$cadenaSql .= 'id_formacion_media';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['fk_id_formacion_basica'] . ', ';
				$cadenaSql .= $variable ['fk_id_formacion_media'] . ' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_datos_formacion_funcionario; ";
				break;
				
			case 'insertarFormacionInvestigacion' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.publicacion_investigacion ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'tematica,';
				$cadenaSql .= 'tipo_investigacion,';
				$cadenaSql .= 'logros_obtenidos,';
				$cadenaSql .= 'referencias_bibliograficas';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable ['tematicaInvestigacion'] . '\', ';
				$cadenaSql .= '\'' . $variable ['tipoInvestigacion'] . '\', ';
				$cadenaSql .= '\'' . $variable ['logrosInvestigacion'] . '\', ';
				$cadenaSql .= '\'' . $variable ['referenciasInvestigacion'] . '\' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_publicacion; ";
				break;
				
			case 'insertarFuncionario' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.funcionario ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_datos_identificacion,';
				$cadenaSql .= 'id_informacion_personal_basica,';
				$cadenaSql .= 'id_datos_residencia,';
				$cadenaSql .= 'id_datos_formacion_funcionario,';
				$cadenaSql .= 'id_publicacion,';
				$cadenaSql .= 'estado_funcionario,';
				$cadenaSql .= 'documento';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['fk_id_datos_identificacion'] . ', ';
				$cadenaSql .= $variable ['fk_id_informacion_personal_basica'] . ', ';
				$cadenaSql .= $variable ['fk_id_datos_residencia'] . ', ';
				$cadenaSql .= $variable ['fk_id_datos_formacion_funcionario'] . ', ';
				$cadenaSql .= $variable ['fk_id_publicacion'] . ', ';
				$cadenaSql .= '\'Activo\', ';
				$cadenaSql .= $variable ['documento_fun'] . ' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_funcionario; ";
				break;
				
			case 'insertarUbicacionFormacionSuperior' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_pais,';
				$cadenaSql .= 'id_departamento,';
				$cadenaSql .= 'id_ciudad';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['paisFormacionSuperior'] . ', ';
				$cadenaSql .= $variable ['departamentoFormacionSuperior'] . ', ';
				$cadenaSql .= $variable ['ciudadFormacionSuperior'] . ' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_ubicacion; ";
				break;
				
			case 'insertarFormacionSuperior' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.formacion_superior ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_datos_formacion_funcionario,';
				$cadenaSql .= 'modalidad_academica,';
				$cadenaSql .= 'cantidad_semestres_aprobados,';
				$cadenaSql .= 'graduado,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'convalidacion_resolucion,';
				if($variable ['fechaConvalidacion'] != NULL){
					$cadenaSql .= 'convalidacion_fecha,';
				}
				$cadenaSql .= 'convalidacion_entidad,';
				$cadenaSql .= 'nombre_universidad,';
				$cadenaSql .= 'titulo_obtenido,';
				if($variable ['fechaGraduacionSuperior'] != NULL){
					$cadenaSql .= 'fecha_graduacion,';
				}
				$cadenaSql .= 'numero_tarjeta_profesional,';
				if($variable ['fechaExpedicionTarjeta'] != NULL){
					$cadenaSql .= 'fecha_expe_tarjeta,';
				}
				$cadenaSql .= 'soporte_educacion_superior';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['fk_formacion_academica_funcionario'] . ', ';
				$cadenaSql .= '\'' . $variable ['modalidadAcademica'] . '\', ';
				$cadenaSql .= $variable ['semestresCursados'] . ', ';
				$cadenaSql .= $variable ['esGraduado'] . ', ';
				$cadenaSql .= $variable ['fk_ubicacion'] . ', ';
				$cadenaSql .= '\'' . $variable ['resolucionConvalidacion'] . '\', ';
				if($variable ['fechaConvalidacion'] != NULL){
					$cadenaSql .= '\'' . $variable ['fechaConvalidacion'] . '\', ';
				}
				$cadenaSql .= '\'' . $variable ['entidadConvalidacion'] . '\', ';
				$cadenaSql .= '\'' . $variable ['universidadSuperior'] . '\', ';
				$cadenaSql .= '\'' . $variable ['tituloSuperior'] . '\', ';
        		if($variable ['fechaGraduacionSuperior'] != NULL){
					$cadenaSql .= '\'' . $variable ['fechaGraduacionSuperior'] . '\', ';
				}
				$cadenaSql .= '\'' . $variable ['numeroTarjetaProfesional'] . '\', ';
				if($variable ['fechaExpedicionTarjeta'] != NULL){
					$cadenaSql .= '\'' . $variable ['fechaExpedicionTarjeta'] . '\', ';
				}
				$cadenaSql .= '\'' . $variable ['soporteSuperior'] . '\' ';
				$cadenaSql .= '); ';
				break;
				
			case 'insertarFormacionInformal' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.formacion_informal ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_datos_formacion_funcionario,';
				$cadenaSql .= 'nombre_curso,';
				$cadenaSql .= 'nombre_institucion,';
				$cadenaSql .= 'intesidad_horaria,';
				if($variable ['fechaTerminacion'] != NULL){
					$cadenaSql .= 'fecha_terminacion,';
				}
				$cadenaSql .= 'soporte_certificado';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['fk_formacion_academica_funcionario'] . ', ';
				$cadenaSql .= '\'' . $variable ['cursoInformal'] . '\', ';
				$cadenaSql .= '\'' . $variable ['entidadCurso'] . '\', ';
				$cadenaSql .= '\'' . $variable ['intensidadHoraria'] . '\', ';
				if($variable ['fechaTerminacion'] != NULL){
					$cadenaSql .= '\'' . $variable ['fechaTerminacion'] . '\', ';
				}
				$cadenaSql .= '\'' . $variable ['soporteInformal'] . '\' ';
				$cadenaSql .= '); ';
				break;
				
			case 'insertarFormacionIdiomas' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.formacion_idioma ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_funcionario,';
				$cadenaSql .= 'idioma,';
				$cadenaSql .= 'nombre_institucion,';
				$cadenaSql .= 'nivel,';
				if($variable ['habla'] != NULL){
					$cadenaSql .= 'habla,';
				}
				if($variable ['lee'] != NULL){
					$cadenaSql .= 'lee,';
				}
				if($variable ['escribe'] != NULL){
					$cadenaSql .= 'escribe,';
				}
				if($variable ['escucha'] != NULL){
					$cadenaSql .= 'escucha,';
				}
				$cadenaSql .= 'soporte_idioma,';
				$cadenaSql .= 'observaciones';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['fk_funcionario'] . ', ';
				$cadenaSql .= '\'' . $variable ['idioma'] . '\', ';
				$cadenaSql .= '\'' . $variable ['universidadIdioma'] . '\', ';
				$cadenaSql .= '\'' . $variable ['nivel'] . '\', ';
				if($variable ['habla'] != NULL){
					$cadenaSql .= '\'' . $variable ['habla'] . '\', ';
				}
				if($variable ['lee'] != NULL){
					$cadenaSql .= '\'' . $variable ['lee'] . '\', ';
				}
				if($variable ['escribe'] != NULL){
					$cadenaSql .= '\'' . $variable ['escribe'] . '\', ';
				}
				if($variable ['escucha'] != NULL){
					$cadenaSql .= '\'' . $variable ['escucha'] . '\', ';
				}
				$cadenaSql .= '\'' . $variable ['soporteIdioma'] . '\', ';
				$cadenaSql .= '\'' . $variable ['observacionIdioma'] . '\' ';
				$cadenaSql .= '); ';
				break;
				
			case 'insertarUbicacionExperiencia' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_pais,';
				$cadenaSql .= 'id_departamento,';
				$cadenaSql .= 'id_ciudad';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['paisExperiencia'] . ', ';
				$cadenaSql .= $variable ['departamentoExperiencia'] . ', ';
				$cadenaSql .= $variable ['ciudadExperiencia'] . ' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_ubicacion; ";
				break;
				
			case 'insertarExperienciaLaboral' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.experiencia_laboral ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_funcionario,';
				$cadenaSql .= 'nombre_empresa,';
				if($variable ['nitEmpresa'] > 0){
					$cadenaSql .= 'nit_empresa,';
				}
				$cadenaSql .= 'tipo_entidad,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'correo_empresa,';
				$cadenaSql .= 'telefono_empresa,';
				$cadenaSql .= 'fecha_ingreso,';
				$cadenaSql .= 'fecha_retiro,';
				$cadenaSql .= 'dependencia,';
				$cadenaSql .= 'cargo,';
				$cadenaSql .= 'horas_semanales_trabajo,';
				$cadenaSql .= 'soporte_experiencia';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['fk_funcionario'] . ', ';
				$cadenaSql .= '\'' . $variable ['nombreEmpresa'] . '\', ';
				if($variable ['nitEmpresa'] > 0){
					$cadenaSql .= $variable ['nitEmpresa'] . ', ';
				}
				$cadenaSql .= '\'' . $variable ['tipoEntidad'] . '\', ';
				$cadenaSql .= $variable ['fk_ubicacion'] . ', ';
				$cadenaSql .= '\'' . $variable ['emailEmpresa'] . '\', ';
				$cadenaSql .= $variable ['telefonoEmpresa'] . ', ';
				$cadenaSql .= '\'' . $variable ['fechaIngreso'] . '\', ';
				$cadenaSql .= '\'' . $variable ['fechaRetiro'] . '\', ';
				$cadenaSql .= '\'' . $variable ['dependenciaEmpresa'] . '\', ';
				$cadenaSql .= '\'' . $variable ['cargoEmpresa'] . '\', ';
				$cadenaSql .= '\'' . $variable ['horasTrabajo'] . '\', ';
				$cadenaSql .= '\'' . $variable ['soporteExperiencia'] . '\' ';
				$cadenaSql .= '); ';
				break;
				
			case 'insertarReferenciasPersonales' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'novedad.referencia_laboral ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_funcionario,';
				if($variable ['tipoReferencia'] != NULL){
					$cadenaSql .= 'tipo_referencia,';
				}
				$cadenaSql .= 'nombres_referencia,';
				$cadenaSql .= 'apellidos_referencia,';
				if($variable ['telefonoReferencia'] > 0){
					$cadenaSql .= 'telefono_contacto,';
				}
				$cadenaSql .= 'parentesco_relacion,';
				$cadenaSql .= 'soporte_referencia';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['fk_funcionario'] . ', ';
				if($variable ['tipoReferencia'] != NULL){
					$cadenaSql .= '\'' . $variable ['tipoReferencia'] . '\', ';
				}
				$cadenaSql .= '\'' . $variable ['nombresReferencia'] . '\', ';
				$cadenaSql .= '\'' . $variable ['apellidosReferencia'] . '\', ';
				if($variable ['telefonoReferencia'] > 0){
					$cadenaSql .= $variable ['telefonoReferencia'] . ', ';
				}
				$cadenaSql .= '\'' . $variable ['relacionReferencia'] . '\', ';
				$cadenaSql .= '\'' . $variable ['soporteReferencia'] . '\' ';
				$cadenaSql .= '); ';
				break;
				
            case 'actualizarRegistro' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= $prefijo . 'pagina ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'nombre,';
                $cadenaSql .= 'descripcion,';
                $cadenaSql .= 'modulo,';
                $cadenaSql .= 'nivel,';
                $cadenaSql .= 'parametro';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= '\'' . $_REQUEST ['nombrePagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['descripcionPagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['moduloPagina'] . '\', ';
                $cadenaSql .= $_REQUEST ['nivelPagina'] . ', ';
                $cadenaSql .= '\'' . $_REQUEST ['parametroPagina'] . '\'';
                $cadenaSql .= ') ';
                break;
            
            case 'buscarRegistro' :
                
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_pagina as PAGINA, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                //$cadenaSql .= 'descripcion as DESCRIPCION,';
                //$cadenaSql .= 'modulo as MODULO,';
                //$cadenaSql .= 'nivel as NIVEL,';
                //$cadenaSql .= 'parametro as PARAMETRO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= $prefijo . 'pagina ';
                //$cadenaSql .= 'WHERE ';
                //$cadenaSql .= 'nombre=\'' . $_REQUEST ['nombrePagina'] . '\' ';
                break;
                
			case 'consultarFuncionario' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_funcionario as ID_FUNCIONARIO, ';
				$cadenaSql .= 'id_datos_identificacion as ID_DATOS_IDENTI, ';
				$cadenaSql .= 'id_informacion_personal_basica as ID_INFO_PERSONAL_BASICA, ';
				$cadenaSql .= 'id_datos_residencia as ID_DATOS_RESIDENCIA, ';
				$cadenaSql .= 'id_datos_formacion_funcionario as ID_DATOS_FORMACION_FUNCIONARIO, ';
				$cadenaSql .= 'id_publicacion as ID_PUBLICACION ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.funcionario ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'documento =  ' . $variable . ' ';
				$cadenaSql .= 'AND ';
				$cadenaSql .= 'estado_funcionario = \'Activo\';';
				break;
				
			case 'consultarInformacionPersonalBasica' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'fecha_nacimiento,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'genero,';
				$cadenaSql .= 'estado_civil,';
				$cadenaSql .= 'edad,';
				$cadenaSql .= 'tipo_sangre,';
				$cadenaSql .= 'rh_sangre,';
				$cadenaSql .= 'tipo_libreta_militar,';
				$cadenaSql .= 'numero_libreta,';
				$cadenaSql .= 'numero_distrito_militar,';
				$cadenaSql .= 'soporte_libreta,';
				$cadenaSql .= 'grupo_etnico,';
				$cadenaSql .= 'comunidad_lgbt,';
				$cadenaSql .= 'cabeza_familia,';
				$cadenaSql .= 'personas_a_cargo,';
				$cadenaSql .= 'soporte_caracterizacion ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.informacion_personal_basica ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_informacion_personal_basica =  ' . $variable . ';';
				break;
				
			case 'consultarDatosResidenciaCont' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'nacionalidad,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'localidad,';
				$cadenaSql .= 'barrio,';
				$cadenaSql .= 'direccion_residencia,';
				$cadenaSql .= 'estrato,';
				$cadenaSql .= 'soporte_estrato,';
				$cadenaSql .= 'soporte_residencia,';
				$cadenaSql .= 'telefono_fijo,';
				$cadenaSql .= 'telefono_movil,';
				$cadenaSql .= 'correo_personal,';
				$cadenaSql .= 'telefono_oficina,';
				$cadenaSql .= 'correo_oficina,';
				$cadenaSql .= 'direccion_oficina,';
				$cadenaSql .= 'cargo ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.informacion_residencia_contacto ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_datos_residencia =  ' . $variable . ';';
				break;
				
			case 'consultarFormacionAcademicaFuncionario' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_formacion_basica,';
				$cadenaSql .= 'id_formacion_media ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_academica_funcionario ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_datos_formacion_funcionario =  ' . $variable . ';';
				break;
				
			case 'consultarFormacionBasica' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'modalidad,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'nombre_colegio,';
				$cadenaSql .= 'titulo_obtenido,';
				$cadenaSql .= 'fecha_graduacion,';
				$cadenaSql .= 'soporte_graduacion ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_basica ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_formacion_basica =  ' . $variable . ';';
				break;
				
			case 'consultarFormacionMedia' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'modalidad,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'nombre_colegio,';
				$cadenaSql .= 'titulo_obtenido,';
				$cadenaSql .= 'fecha_graduacion,';
				$cadenaSql .= 'soporte_graduacion ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_media ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_formacion_media =  ' . $variable . ';';
				break;
				
			case 'consultarCantidadFormacionSuperior' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_formacion_superior ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_superior ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_datos_formacion_funcionario =  ' . $variable . ';';
				break;
				
			case 'consultarCantidadFormacionInformal' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_formacion_informal ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_informal ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_datos_formacion_funcionario =  ' . $variable . ';';
				break;
				
			case 'consultarCantidadFormacionIdiomas' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_formacion_idioma ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_idioma ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_funcionario =  ' . $variable . ';';
				break;
				
			case 'consultarCantidadExperiencia' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_experiencia_laboral ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.experiencia_laboral ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_funcionario =  ' . $variable . ';';
				break;
				
			case 'consultarCantidadReferencia' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_referencia_laboral ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.referencia_laboral ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_funcionario =  ' . $variable . ';';
				break;
				
			case 'consultarReferenciasPersonales' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_funcionario,';
				$cadenaSql .= 'tipo_referencia,';
				$cadenaSql .= 'nombres_referencia,';
				$cadenaSql .= 'apellidos_referencia,';
				$cadenaSql .= 'telefono_contacto,';
				$cadenaSql .= 'parentesco_relacion,';
				$cadenaSql .= 'soporte_referencia ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.referencia_laboral ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_funcionario =  ' . $variable . ';';
				break;
				
			case 'consultarExperienciaLaboral' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_funcionario,';
				$cadenaSql .= 'nombre_empresa,';
				$cadenaSql .= 'nit_empresa,';
				$cadenaSql .= 'tipo_entidad,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'correo_empresa,';
				$cadenaSql .= 'telefono_empresa,';
				$cadenaSql .= 'fecha_ingreso,';
				$cadenaSql .= 'fecha_retiro,';
				$cadenaSql .= 'dependencia,';
				$cadenaSql .= 'cargo,';
				$cadenaSql .= 'horas_semanales_trabajo,';
				$cadenaSql .= 'soporte_experiencia ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.experiencia_laboral ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_funcionario =  ' . $variable . ';';
				break;
				
			case 'consultarFormacionIdiomas' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_funcionario,';
				$cadenaSql .= 'idioma,';
				$cadenaSql .= 'nombre_institucion,';
				$cadenaSql .= 'nivel,';
				$cadenaSql .= 'habla,';
				$cadenaSql .= 'lee,';
				$cadenaSql .= 'escribe,';
				$cadenaSql .= 'escucha,';
				$cadenaSql .= 'soporte_idioma,';
				$cadenaSql .= 'observaciones ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_idioma ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_funcionario =  ' . $variable . ';';
				break;
				
			case 'consultarFormacionSuperior' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_datos_formacion_funcionario,';
				$cadenaSql .= 'modalidad_academica,';
				$cadenaSql .= 'cantidad_semestres_aprobados,';
				$cadenaSql .= 'graduado,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'convalidacion_resolucion,';
				$cadenaSql .= 'convalidacion_fecha,';
				$cadenaSql .= 'convalidacion_entidad,';
				$cadenaSql .= 'nombre_universidad,';
				$cadenaSql .= 'titulo_obtenido,';
				$cadenaSql .= 'fecha_graduacion,';
				$cadenaSql .= 'numero_tarjeta_profesional,';
				$cadenaSql .= 'fecha_expe_tarjeta,';
				$cadenaSql .= 'soporte_educacion_superior ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_superior ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_datos_formacion_funcionario =  ' . $variable . ';';
				break;
			
			case 'consultarFormacionInformal' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_datos_formacion_funcionario,';
				$cadenaSql .= 'nombre_curso,';
				$cadenaSql .= 'nombre_institucion,';
				$cadenaSql .= 'intesidad_horaria,';
				$cadenaSql .= 'fecha_terminacion,';
				$cadenaSql .= 'soporte_certificado ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_informal ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_datos_formacion_funcionario =  ' . $variable . ';';
				break;
				
			case 'consultarFormacionInvestigacion' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'tematica,';
				$cadenaSql .= 'tipo_investigacion,';
				$cadenaSql .= 'logros_obtenidos,';
				$cadenaSql .= 'referencias_bibliograficas ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.publicacion_investigacion ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_publicacion =  ' . $variable . ';';
				break;
				
				
				
			case 'modificarUbicacionExpedicion' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'id_pais = ';
				$cadenaSql .= $variable ['paisExpedicion'] . ', ';
				$cadenaSql .= 'id_departamento = ';
				$cadenaSql .= $variable ['departamentoExpedicion'] . ', ';
				$cadenaSql .= 'id_ciudad = ';
				$cadenaSql .= $variable ['ciudadExpedicion'] . ' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ubicacion =  ' . $variable ['id_ubicacion_expe'] . ';';
				break;
			
			case 'modificarIdentificacionDocumento' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.identificacion_expedicion ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'documento = ';
				$cadenaSql .= $variable ['numeroDocumento'] . ', ';
				$cadenaSql .= 'fecha_expe_documento = ';
				$cadenaSql .= '\'' . $variable ['fechaExpedicionDocumento'] . '\', ';
				$cadenaSql .= 'soporte_identificacion = ';
				$cadenaSql .= '\'' . $variable ['soporteDocumento'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_datos_identificacion =  ' . $variable ['id_datos_expedicion'] . ';';
				break;
				
			case 'modificarUbicacionNacimiento' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'id_pais = ';
				$cadenaSql .= $variable ['paisNacimiento'] . ', ';
				$cadenaSql .= 'id_departamento = ';
				$cadenaSql .= $variable ['departamentoNacimiento'] . ', ';
				$cadenaSql .= 'id_ciudad = ';
				$cadenaSql .= $variable ['ciudadNacimiento'] . ' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ubicacion =  ' . $variable ['id_ubicacion_naci'] . ';';
				break;
				
			case 'modificarInformacionPersonalBasica' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.informacion_personal_basica ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'fecha_nacimiento = ';
				$cadenaSql .= '\'' . $variable ['fechaNacimiento'] . '\', ';
				$cadenaSql .= 'genero = ';
				$cadenaSql .= '\'' . $_REQUEST ['funcionarioGenero'] . '\', ';
				$cadenaSql .= 'estado_civil = ';
				$cadenaSql .= '\'' . $_REQUEST ['funcionarioEstadoCivil'] . '\', ';
				$cadenaSql .= 'edad = ';
				$cadenaSql .= $variable ['edadNacimiento'] . ', ';
				$cadenaSql .= 'tipo_sangre = ';
				if ($_REQUEST ['funcionarioTipoSangre'] != 'NULL') {
					$cadenaSql .= '\'' . $_REQUEST ['funcionarioTipoSangre'] . '\', ';
				} else {
					$cadenaSql .= $_REQUEST ['funcionarioTipoSangre'] . ', ';
				}
				$cadenaSql .= 'rh_sangre = ';
				if ($_REQUEST ['funcionarioSangreRH'] != 'NULL') {
					$cadenaSql .= '\'' . $_REQUEST ['funcionarioSangreRH'] . '\', ';
				} else {
					$cadenaSql .= $_REQUEST ['funcionarioSangreRH'] . ', ';
				}
				$cadenaSql .= 'tipo_libreta_militar = ';
				if ($_REQUEST ['funcionarioTipoLibreta'] != 'NULL') {
					$cadenaSql .= '\'' . $_REQUEST ['funcionarioTipoLibreta'] . '\', ';
				} else {
					$cadenaSql .= $_REQUEST ['funcionarioTipoLibreta'] . ', ';
				}
				$cadenaSql .= 'numero_libreta = ';
				if ($variable ['numeroLibreta'] > 0) {
					$cadenaSql .= $variable ['numeroLibreta'] . ', ';
				} else {
					$cadenaSql .= 'NULL, ';
				}
				$cadenaSql .= 'numero_distrito_militar = ';
				if ($variable ['numeroDistritoLibreta'] > 0) {
					$cadenaSql .= $variable ['numeroDistritoLibreta'] . ', ';
				} else {
					$cadenaSql .= 'NULL, ';
				}
				$cadenaSql .= 'soporte_libreta = ';
				if ($variable ['soporteLibreta'] != NULL) {
					$cadenaSql .= '\'' . $variable ['soporteLibreta'] . '\', ';
				} else {
					$cadenaSql .= '\'\', ';
				}
				$cadenaSql .= 'grupo_etnico = ';
				if ($_REQUEST ['funcionarioGrupoEtnico'] != 'NULL') {
					$cadenaSql .= '\'' . $_REQUEST ['funcionarioGrupoEtnico'] . '\', ';
				} else {
					$cadenaSql .= $_REQUEST ['funcionarioGrupoEtnico'] . ', ';
				}
				$cadenaSql .= 'comunidad_lgbt = ';
				$cadenaSql .= $_REQUEST ['funcionarioGrupoLGBT'] . ', ';
				$cadenaSql .= 'cabeza_familia = ';
				$cadenaSql .= $_REQUEST ['funcionarioCabezaFamilia'] . ', ';
				$cadenaSql .= 'personas_a_cargo = ';
				$cadenaSql .= $_REQUEST ['funcionarioPersonasCargo'] . ', ';
				$cadenaSql .= 'soporte_caracterizacion = ';
				$cadenaSql .= '\'' . $variable ['soporteCaracterizacion'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_informacion_personal_basica = ' . $variable ['id_info_personal'] . ';';
				break;
				
			case 'modificarUbicacionContacto' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'id_pais = ';
				$cadenaSql .= $variable ['paisContacto'] . ', ';
				$cadenaSql .= 'id_departamento = ';
				$cadenaSql .= $variable ['departamentoContacto'] . ', ';
				$cadenaSql .= 'id_ciudad = ';
				$cadenaSql .= $variable ['ciudadContacto'] . ' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ubicacion =  ' . $variable ['id_ubicacion_contac'] . ';';
				break;
				
			case 'modificarDatosResidenciaCont' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.informacion_residencia_contacto ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'nacionalidad = ';
				$cadenaSql .= '\'' . $variable ['nacionalidad'] . '\', ';
				$cadenaSql .= 'localidad = ';
				$cadenaSql .= '\'' . $variable ['localidadContacto'] . '\', ';
				$cadenaSql .= 'barrio = ';
				$cadenaSql .= '\'' . $variable ['barrioContacto'] . '\', ';
				$cadenaSql .= 'direccion_residencia = ';
				$cadenaSql .= '\'' . $variable ['direccionContacto'] . '\', ';
				$cadenaSql .= 'estrato = ';
				if ($_REQUEST ['funcionarioContactoEstrato'] != 'NULL') {
					$cadenaSql .= '\'' . $_REQUEST ['funcionarioContactoEstrato'] . '\', ';
				} else {
					$cadenaSql .= $_REQUEST ['funcionarioContactoEstrato'] . ', ';
				}
				$cadenaSql .= 'soporte_estrato = ';
				$cadenaSql .= '\'' . $variable ['soporteEstrato'] . '\', ';
				$cadenaSql .= 'soporte_residencia = ';
				$cadenaSql .= '\'' . $variable ['soporteResidencia'] . '\', ';
				$cadenaSql .= 'telefono_fijo = ';
				$cadenaSql .= $variable ['telefonoFijoContacto'] . ', ';
				$cadenaSql .= 'telefono_movil = ';
				$cadenaSql .= $variable ['telefonoMovilContacto'] . ', ';
				$cadenaSql .= 'correo_personal = ';
				$cadenaSql .= '\'' . $variable ['emailContacto'] . '\', ';
				$cadenaSql .= 'telefono_oficina = ';
				if ($variable ['telefonoFijoOficina'] > 0) {
					$cadenaSql .= $variable ['telefonoFijoOficina'] . ', ';
				} else {
					$cadenaSql .= 'NULL, ';
				}
				$cadenaSql .= 'correo_oficina = ';
				$cadenaSql .= '\'' . $variable ['emailOficina'] . '\', ';
				$cadenaSql .= 'direccion_oficina = ';
				$cadenaSql .= '\'' . $variable ['direccionOficina'] . '\', ';
				$cadenaSql .= 'cargo = ';
				$cadenaSql .= '\'' . $variable ['cargoOficina'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_datos_residencia = ' . $variable ['id_info_contacto'] . ';';
				break;
				
			case 'modificarUbicacionFormacionBasica' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'id_pais = ';
				$cadenaSql .= $variable ['paisFormacionBasica'] . ', ';
				$cadenaSql .= 'id_departamento = ';
				$cadenaSql .= $variable ['departamentoFormacionBasica'] . ', ';
				$cadenaSql .= 'id_ciudad = ';
				$cadenaSql .= $variable ['ciudadFormacionBasica'] . ' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ubicacion =  ' . $variable ['id_ubicacion_basica'] . ';';
				break;
			
			case 'modificarFormacionBasica' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.formacion_basica ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'modalidad = ';
				$cadenaSql .= '\'' . $variable ['modalidadBasica'] . '\', ';
				$cadenaSql .= 'nombre_colegio = ';
				$cadenaSql .= '\'' . $variable ['colegioBasica'] . '\', ';
				$cadenaSql .= 'titulo_obtenido = ';
				$cadenaSql .= '\'' . $variable ['tituloBasica'] . '\', ';
				$cadenaSql .= 'fecha_graduacion = ';
				$cadenaSql .= '\'' . $variable ['fechaGradoBasica'] . '\', ';
				$cadenaSql .= 'soporte_graduacion = ';
				$cadenaSql .= '\'' . $variable ['soporteBasica'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_formacion_basica = ' . $variable ['id_formacion_basica'] . ';';
				break;
			
			case 'modificarUbicacionFormacionMedia' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'id_pais = ';
				$cadenaSql .= $variable ['paisFormacionMedia'] . ', ';
				$cadenaSql .= 'id_departamento = ';
				$cadenaSql .= $variable ['departamentoFormacionMedia'] . ', ';
				$cadenaSql .= 'id_ciudad = ';
				$cadenaSql .= $variable ['ciudadFormacionMedia'] . ' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ubicacion =  ' . $variable ['id_ubicacion_media'] . ';';
				break;
			
			case 'modificarFormacionMedia' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.formacion_media ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'modalidad = ';
				$cadenaSql .= '\'' . $variable ['modalidadMedia'] . '\', ';
				$cadenaSql .= 'nombre_colegio = ';
				$cadenaSql .= '\'' . $variable ['colegioMedia'] . '\', ';
				$cadenaSql .= 'titulo_obtenido = ';
				$cadenaSql .= '\'' . $variable ['tituloMedia'] . '\', ';
				$cadenaSql .= 'fecha_graduacion = ';
				$cadenaSql .= '\'' . $variable ['fechaGradoMedia'] . '\', ';
				$cadenaSql .= 'soporte_graduacion = ';
				$cadenaSql .= '\'' . $variable ['soporteMedia'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_formacion_media = ' . $variable ['id_formacion_media'] . ';';
				break;
			
			case 'modificarFormacionInvestigacion' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.publicacion_investigacion ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'tematica = ';
				$cadenaSql .= '\'' . $variable ['tematicaInvestigacion'] . '\', ';
				$cadenaSql .= 'tipo_investigacion = ';
				$cadenaSql .= '\'' . $variable ['tipoInvestigacion'] . '\', ';
				$cadenaSql .= 'logros_obtenidos = ';
				$cadenaSql .= '\'' . $variable ['logrosInvestigacion'] . '\', ';
				$cadenaSql .= 'referencias_bibliograficas = ';
				$cadenaSql .= '\'' . $variable ['referenciasInvestigacion'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_publicacion = ' . $variable ['id_mod_publicacion'] . ';';
				break;
				
			case 'modificarUbicacionFormacionSuperior' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'id_pais = ';
				$cadenaSql .= $variable ['paisFormacionSuperior'] . ', ';
				$cadenaSql .= 'id_departamento = ';
				$cadenaSql .= $variable ['departamentoFormacionSuperior'] . ', ';
				$cadenaSql .= 'id_ciudad = ';
				$cadenaSql .= $variable ['ciudadFormacionSuperior'] . ' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ubicacion = ' . $variable ['id_ubicacion_superior'] . ';';
				break;
			
			case 'modificarFormacionSuperior' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.formacion_superior ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'modalidad_academica = ';
				$cadenaSql .= '\'' . $variable ['modalidadAcademica'] . '\', ';
				$cadenaSql .= 'cantidad_semestres_aprobados = ';
				$cadenaSql .= $variable ['semestresCursados'] . ', ';
				$cadenaSql .= 'graduado = ';
				$cadenaSql .= $variable ['esGraduado'] . ', ';
				$cadenaSql .= 'convalidacion_resolucion = ';
				$cadenaSql .= '\'' . $variable ['resolucionConvalidacion'] . '\', ';
				if ($variable ['fechaConvalidacion'] != NULL) {
					$cadenaSql .= 'convalidacion_fecha = ';
					$cadenaSql .= '\'' . $variable ['fechaConvalidacion'] . '\', ';
				}
				$cadenaSql .= 'convalidacion_entidad = ';
				$cadenaSql .= '\'' . $variable ['entidadConvalidacion'] . '\', ';
				$cadenaSql .= 'nombre_universidad = ';
				$cadenaSql .= '\'' . $variable ['universidadSuperior'] . '\', ';
				$cadenaSql .= 'titulo_obtenido = ';
				$cadenaSql .= '\'' . $variable ['tituloSuperior'] . '\', ';
				if ($variable ['fechaGraduacionSuperior'] != NULL) {
					$cadenaSql .= 'fecha_graduacion = ';
					$cadenaSql .= '\'' . $variable ['fechaGraduacionSuperior'] . '\', ';
				}
				$cadenaSql .= 'numero_tarjeta_profesional = ';
				$cadenaSql .= '\'' . $variable ['numeroTarjetaProfesional'] . '\', ';
				if ($variable ['fechaExpedicionTarjeta'] != NULL) {
					$cadenaSql .= 'fecha_expe_tarjeta = ';
					$cadenaSql .= '\'' . $variable ['fechaExpedicionTarjeta'] . '\', ';
				}
				$cadenaSql .= 'soporte_educacion_superior = ';
				$cadenaSql .= '\'' . $variable ['soporteSuperior'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_formacion_superior = ' . $variable ['id_formacion_superior'] . ';';
				break;
				
			case 'eliminarFormacionSuperior' :
				$cadenaSql = 'DELETE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_superior ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_formacion_superior = ' . $variable ['id_formacion_superior'] . ';';
				break;
				
			case 'modificarFormacionInformal' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.formacion_informal ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'nombre_curso = ';
				$cadenaSql .= '\'' . $variable ['cursoInformal'] . '\', ';
				$cadenaSql .= 'nombre_institucion = ';
				$cadenaSql .= '\'' . $variable ['entidadCurso'] . '\', ';
				$cadenaSql .= 'intesidad_horaria = ';
				$cadenaSql .= '\'' . $variable ['intensidadHoraria'] . '\', ';
				if ($variable ['fechaTerminacion'] != NULL) {
					$cadenaSql .= 'fecha_terminacion = ';
					$cadenaSql .= '\'' . $variable ['fechaTerminacion'] . '\', ';
				}
				$cadenaSql .= 'soporte_certificado = ';
				$cadenaSql .= '\'' . $variable ['soporteInformal'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_formacion_informal = ' . $variable ['id_formacion_informal'] . ';';
				break;
				
			case 'eliminarFormacionInformal' :
				$cadenaSql = 'DELETE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_informal ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_formacion_informal = ' . $variable ['id_formacion_informal'] . ';';
				break;
				
			case 'modificarFormacionIdiomas' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.formacion_idioma ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'idioma = ';
				$cadenaSql .= '\'' . $variable ['idioma'] . '\', ';
				$cadenaSql .= 'nombre_institucion = ';
				$cadenaSql .= '\'' . $variable ['universidadIdioma'] . '\', ';
				$cadenaSql .= 'nivel = ';
				$cadenaSql .= '\'' . $variable ['nivel'] . '\', ';
				if ($variable ['habla'] != NULL) {
					$cadenaSql .= 'habla = ';
					$cadenaSql .= '\'' . $variable ['habla'] . '\', ';
				}
				if ($variable ['lee'] != NULL) {
					$cadenaSql .= 'lee = ';
					$cadenaSql .= '\'' . $variable ['lee'] . '\', ';
				}
				if ($variable ['escribe'] != NULL) {
					$cadenaSql .= 'escribe = ';
					$cadenaSql .= '\'' . $variable ['escribe'] . '\', ';
				}
				if ($variable ['escucha'] != NULL) {
					$cadenaSql .= 'escucha = ';
					$cadenaSql .= '\'' . $variable ['escucha'] . '\', ';
				}
				$cadenaSql .= 'soporte_idioma = ';
				$cadenaSql .= '\'' . $variable ['soporteIdioma'] . '\', ';
				$cadenaSql .= 'observaciones = ';
				$cadenaSql .= '\'' . $variable ['observacionIdioma'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_formacion_idioma = ' . $variable ['id_formacion_idioma'] . ';';
				break;
				
			case 'eliminarFormacionIdiomas' :
				$cadenaSql = 'DELETE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.formacion_idioma ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_formacion_idioma = ' . $variable ['id_formacion_idioma'] . ';';
				break;
				
			case 'modificarUbicacionExperiencia' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'id_pais = ';
				$cadenaSql .= $variable ['paisExperiencia'] . ', ';
				$cadenaSql .= 'id_departamento = ';
				$cadenaSql .= $variable ['departamentoExperiencia'] . ', ';
				$cadenaSql .= 'id_ciudad = ';
				$cadenaSql .= $variable ['ciudadExperiencia'] . ' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ubicacion = ' . $variable ['id_ubicacion_experiencia'] . ';';
				break;
			
			case 'modificarExperienciaLaboral' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.experiencia_laboral ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'nombre_empresa = ';
				$cadenaSql .= '\'' . $variable ['nombreEmpresa'] . '\', ';
				if ($variable ['nitEmpresa'] > 0) {
					$cadenaSql .= 'nit_empresa = ';
					$cadenaSql .= $variable ['nitEmpresa'] . ', ';
				}
				$cadenaSql .= 'tipo_entidad = ';
				$cadenaSql .= '\'' . $variable ['tipoEntidad'] . '\', ';
				$cadenaSql .= 'correo_empresa = ';
				$cadenaSql .= '\'' . $variable ['emailEmpresa'] . '\', ';
				$cadenaSql .= 'telefono_empresa = ';
				$cadenaSql .= $variable ['telefonoEmpresa'] . ', ';
				$cadenaSql .= 'fecha_ingreso = ';
				$cadenaSql .= '\'' . $variable ['fechaIngreso'] . '\', ';
				$cadenaSql .= 'fecha_retiro = ';
				$cadenaSql .= '\'' . $variable ['fechaRetiro'] . '\', ';
				$cadenaSql .= 'dependencia = ';
				$cadenaSql .= '\'' . $variable ['dependenciaEmpresa'] . '\', ';
				$cadenaSql .= 'cargo = ';
				$cadenaSql .= '\'' . $variable ['cargoEmpresa'] . '\', ';
				$cadenaSql .= 'horas_semanales_trabajo = ';
				$cadenaSql .= '\'' . $variable ['horasTrabajo'] . '\', ';
				$cadenaSql .= 'soporte_experiencia = ';
				$cadenaSql .= '\'' . $variable ['soporteExperiencia'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_experiencia_laboral = ' . $variable ['id_experiencia'] . ';';
				break;
				
			case 'eliminarExperienciaLaboral' :
				$cadenaSql = 'DELETE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.experiencia_laboral ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_experiencia_laboral = ' . $variable ['id_experiencia'] . ';';
				break;
				
			case 'modificarReferenciasPersonales' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'novedad.referencia_laboral ';
				$cadenaSql .= 'SET ';
				if ($variable ['tipoReferencia'] != NULL) {
					$cadenaSql .= 'tipo_referencia = ';
					$cadenaSql .= '\'' . $variable ['tipoReferencia'] . '\', ';
				}
				$cadenaSql .= 'nombres_referencia = ';
				$cadenaSql .= '\'' . $variable ['nombresReferencia'] . '\', ';
				$cadenaSql .= 'apellidos_referencia = ';
				$cadenaSql .= '\'' . $variable ['apellidosReferencia'] . '\', ';
				if ($variable ['telefonoReferencia'] > 0) {
					$cadenaSql .= 'telefono_contacto = ';
					$cadenaSql .= $variable ['telefonoReferencia'] . ', ';
				}
				$cadenaSql .= 'parentesco_relacion = ';
				$cadenaSql .= '\'' . $variable ['relacionReferencia'] . '\', ';
				$cadenaSql .= 'soporte_referencia = ';
				$cadenaSql .= '\'' . $variable ['soporteReferencia'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_referencia_laboral = ' . $variable ['id_referencia'] . ';';
				break;
				
			case 'eliminarReferenciasPersonales' :
				$cadenaSql = 'DELETE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'novedad.referencia_laboral ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_referencia_laboral = ' . $variable ['id_referencia'] . ';';
				break;
				
			case 'consultarUbicacion' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ubicacion =  ' . $variable . ';';
				break;
                
			case 'buscarPais' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.pais ';
				$cadenaSql .= 'ORDER BY NOMBRE; ';
				break;
				
			case 'consultarPais' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.pais ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais =  ' . $variable . ';';
				break;
				
			case 'consultarDepartamento' ://Provisionalmente solo Departamentos de Colombia
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_departamento =  ' . $variable . ';';
				break;
				
			case 'consultarCiudad' : // Provisionalmente Solo Ciudades de Colombia sin Agrupar
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.ciudad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ciudad =  ' . $variable . ';';
				break;
			
			case 'buscarDepartamento' ://Provisionalmente solo Departamentos de Colombia
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = 112;';
				break;
               		
			case 'buscarDepartamentoAjax' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRE; ';
				break;
               		
			case 'buscarCiudad' : //Provisionalmente Solo Ciudades de Colombia sin Agrupar
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.ciudad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'ab_pais = \'CO\';';
				break;
				
			case 'buscarCiudadAjax' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRECIUDAD ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.ciudad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_departamento = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRECIUDAD; ';
				break;
             
             case 'buscarRegistroPersonaNatural' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'pNatural.documento as USUARIO, ';
				$cadenaSql .= 'primer_nombre as NOMBRE_1, ';
				$cadenaSql .= 'segundo_nombre as NOMBRE_2, ';
				$cadenaSql .= 'primer_apellido as APELLIDO_1, ';
				$cadenaSql .= 'segundo_apellido as APELLIDO_2, ';
				$cadenaSql .= 'estado_solicitud as ESTADO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'persona.persona_natural pNatural ';
				$cadenaSql .= 'LEFT JOIN ';
				$cadenaSql .= 'novedad.funcionario pFuncionario ';
				$cadenaSql .= 'ON ';
				$cadenaSql .= 'pNatural.documento = pFuncionario.documento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'pFuncionario.documento IS NULL; ';
				break;
				
			case 'buscarRegistroFuncionario' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'pNatural.documento as USUARIO, ';
				$cadenaSql .= 'primer_nombre as NOMBRE_1, ';
				$cadenaSql .= 'segundo_nombre as NOMBRE_2, ';
				$cadenaSql .= 'primer_apellido as APELLIDO_1, ';
				$cadenaSql .= 'segundo_apellido as APELLIDO_2, ';
				$cadenaSql .= 'estado_solicitud as ESTADO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'persona.persona_natural pNatural ';
				$cadenaSql .= 'INNER JOIN ';
				$cadenaSql .= 'novedad.funcionario pFuncionario ';
				$cadenaSql .= 'ON ';
				$cadenaSql .= 'pNatural.documento = pFuncionario.documento; ';
				break;
                	
			case 'buscarRegistroUsuarioWhere' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_usuario as USUARIO, ';
				$cadenaSql .= 'nombre as NOMBRE, ';
				$cadenaSql .= 'apellido as APELLIDO, ';
				$cadenaSql .= 'fecha_reg as FECHA_REG, ';
				$cadenaSql .= 'edad as EDAD, ';
				$cadenaSql .= 'telefono as TELEFONO, ';
				$cadenaSql .= 'direccion as DIRECCION, ';
				$cadenaSql .= 'ciudad as CIUDAD, ';
				$cadenaSql .= 'estado as ESTADO ';
				// $cadenaSql .= 'descripcion as DESCRIPCION,';
				// $cadenaSql .= 'modulo as MODULO,';
				// $cadenaSql .= 'nivel as NIVEL,';
				// $cadenaSql .= 'parametro as PARAMETRO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= "" . $prefijo . 'usuarios ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'fecha_reg <=\'' . $_REQUEST ['funcionarioFechaExpDoc'] . '\' ';
				break;

            case 'borrarRegistro' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= $prefijo . 'pagina ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'nombre,';
                $cadenaSql .= 'descripcion,';
                $cadenaSql .= 'modulo,';
                $cadenaSql .= 'nivel,';
                $cadenaSql .= 'parametro';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= '\'' . $_REQUEST ['nombrePagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['descripcionPagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['moduloPagina'] . '\', ';
                $cadenaSql .= $_REQUEST ['nivelPagina'] . ', ';
                $cadenaSql .= '\'' . $_REQUEST ['parametroPagina'] . '\'';
                $cadenaSql .= ') ';
                break;
        
        }
        return $cadenaSql;
    
    }
}
?>
