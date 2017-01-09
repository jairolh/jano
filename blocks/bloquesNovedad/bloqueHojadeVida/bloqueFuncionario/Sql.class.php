<?php

namespace bloquesNovedad\bloqueHojadeVida\bloqueFuncionario;

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
                
			case 'buscarPais' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.pais ';
				$cadenaSql .= 'ORDER BY NOMBRE; ';
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
