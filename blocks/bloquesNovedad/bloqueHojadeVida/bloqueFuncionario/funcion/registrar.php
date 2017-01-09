<?php

namespace bloquesNovedad\bloqueHojadeVida\bloqueFuncionario\funcion;

include_once('Redireccionador.php');

class FormProcessor {
	
    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;
    var $conexion;
    
    function __construct($lenguaje, $sql) {
        
        $this->miConfigurador = \Configurador::singleton ();
        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
    
    }
    
    function procesarFormulario() {
    	
        //Aquí va la lógica de procesamiento
        
        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        
        /*Datos de PERSONA NATURAL ------------------------------------------------------------------------*/
        /*
        if(isset($_REQUEST['funcionarioIdentificacion'])){
        	switch($_REQUEST ['funcionarioIdentificacion']){
        		case 1 :
        			$_REQUEST ['funcionarioIdentificacion']='Cédula de Ciudadanía';
        			break;
        		case 2:
        			$_REQUEST ['funcionarioIdentificacion']='Tarjeta de Identidad';
        			break;
        		case 3 :
        			$_REQUEST ['funcionarioIdentificacion']='Cédula de extranjería';
        			break;
        		case 4 :
        			$_REQUEST ['codTipoCargoRegistro']='Pasaporte';
        			break;
        	}
        }*/
        
        $datosPersonaNatural = array (
        		'primerNombre' => $_REQUEST['funcionarioPrimerNombre'],
        		'segundoNombre' => $_REQUEST['funcionarioSegundoNombre'],
        		'primerApellido' => $_REQUEST['funcionarioPrimerApellido'],
        		'segundoApellido' => $_REQUEST['funcionarioSegundoApellido'],
        		'otrosNombres' => $_REQUEST['funcionarioOtrosNombres'],
        );
        
        
        //Validación Campos Dinamicos
        
        $cantidadFormacionSuperiorC = $_REQUEST['funcionarioRegistrosSuperior'];
        $cantidadFormacionInformalC = $_REQUEST['funcionarioRegistrosInformal'];
        $cantidadIdiomasC = $_REQUEST['funcionarioRegistrosIdioma'];
        $cantidadExperienciaC = $_REQUEST['funcionarioRegistrosExperiencia'];
        $cantidadReferenciasPerC = $_REQUEST['funcionarioRegistrosReferencia'];
        
        $cont = 0;
        
        while($cont < $cantidadFormacionSuperiorC){
        	 
        	if(	$_REQUEST ['funcionarioFormacionSuperiorModalidad_'.$cont] == NULL ||
        		$_REQUEST ['funcionarioFormacionSuperiorSemestres_'.$cont] == NULL  ||
        	    $_REQUEST ['funcionarioFormacionSuperiorGraduado_'.$cont] == NULL  ||
        		$_REQUEST ['funcionarioFormacionSuperiorPais_'.$cont] == NULL  ||
       			$_REQUEST ['funcionarioFormacionSuperiorDepartamento_'.$cont] == NULL  ||
       			$_REQUEST ['funcionarioFormacionSuperiorCiudad_'.$cont] == NULL  ||
       			$_REQUEST ['funcionarioFormacionSuperiorUniversidad_'.$cont] == NULL  ||
       			$_REQUEST ['funcionarioFormacionSuperiorTituloObtenido_'.$cont] == NULL  ||
       			$_REQUEST ['funcionarioFormacionSuperiorNumeroTarjeta_'.$cont] == NULL ){
        		Redireccionador::redireccionar('noInsertoVal', $datosPersonaNatural);
        		exit();
        	}
        	$cont++;
        	 
        }
        
        $cont = 0;
        
        while($cont < $cantidadIdiomasC){
        
        	if(	$_REQUEST ['funcionarioFormacionIdioma_'.$cont] == NULL ||
        		$_REQUEST ['funcionarioFormacionIdiomaUniversidad_'.$cont] == NULL  ||
        		$_REQUEST ['funcionarioFormacionIdiomaNivel_'.$cont] == NULL ){
        		Redireccionador::redireccionar('noInsertoVal', $datosPersonaNatural);
        		exit();
        	}
        	$cont++;
        
        }
        
        $cont = 0;
        
        while($cont < $cantidadExperienciaC){
        
        	if(	$_REQUEST ['funcionarioExperienciaEmpresa_'.$cont] == NULL ||
        		$_REQUEST ['funcionarioExperienciaTipo_'.$cont] == NULL ||
        		$_REQUEST ['funcionarioExperienciaPais_'.$cont] == NULL ||
        		$_REQUEST ['funcionarioExperienciaDepartamento_'.$cont] == NULL ||
        		$_REQUEST ['funcionarioExperienciaCiudad_'.$cont] == NULL ||
        		$_REQUEST ['funcionarioExperienciaEmpresaCorreo_'.$cont] == NULL ||
        		$_REQUEST ['funcionarioExperienciaEmpresaTelefono_'.$cont] == NULL ||
       			$_REQUEST ['funcionarioFechaEntradaExperiencia_'.$cont] == NULL ||
       			$_REQUEST ['funcionarioFechaSalidaExperiencia_'.$cont] == NULL ||
       			$_REQUEST ['funcionarioExperienciaEmpresaCargo_'.$cont] == NULL ){
       			Redireccionador::redireccionar('noInsertoVal', $datosPersonaNatural);
  				exit();
        	}
        	$cont++;
        
        }
        
        /*-------------------------------------------------------------------------------------------------*/
        
        $datosUbicacionExpedicion = array(
        		'paisExpedicion' => $_REQUEST['funcionarioPais'],
        		'departamentoExpedicion' => $_REQUEST['funcionarioDepartamento'],
        		'ciudadExpedicion' => $_REQUEST['funcionarioCiudad']
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarUbicacionExpedicion",$datosUbicacionExpedicion);
        $id_ubicacion_expe = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosUbicacionExpedicion, "insertarUbicacionExpedicion");
        
        
        
        $datosInformacionPersonalExpedicion = array (
        		'numeroDocumento' => $_REQUEST ['funcionarioDocumento'], //Llave Foranea fk Persona Natural
        		'soporteDocumento' => $_REQUEST ['funcionarioSoporteIden'],
        		'fechaExpedicionDocumento' => $_REQUEST ['funcionarioFechaExpDoc'],
        		'fk_ubicacion_expedicion' => $id_ubicacion_expe[0][0]
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarIdentificacionDocumento",$datosInformacionPersonalExpedicion);
		$id_datos_identificacion = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosInformacionPersonalExpedicion, "insertarIdentificacionDocumento");
        
//*************************************************************************************************//
        
        if(isset($_REQUEST['funcionarioGenero'])){
        	switch($_REQUEST ['funcionarioGenero']){
        		case 1 :
        			$_REQUEST ['funcionarioGenero']='Masculino';
        			break;
        		case 2 :
        			$_REQUEST ['funcionarioGenero']='Femenino';
        			break;
        	}
        }
        
        if(isset($_REQUEST['funcionarioEstadoCivil'])){
        	switch($_REQUEST ['funcionarioEstadoCivil']){
        		case 1 :
        			$_REQUEST ['funcionarioEstadoCivil']='Soltero';
        			break;
        		case 2 :
        			$_REQUEST ['funcionarioEstadoCivil']='Casado';
        			break;
        		case 3 :
        			$_REQUEST ['funcionarioEstadoCivil']='Union Libre';
        			break;
        		case 4 :
        			$_REQUEST ['funcionarioEstadoCivil']='Viudo';
        			break;
        		case 5 :
        			$_REQUEST ['funcionarioEstadoCivil']='Divorciado';
        			break;
        	}
        }
        
        if(isset($_REQUEST['funcionarioTipoSangre'])){
        	switch($_REQUEST ['funcionarioTipoSangre']){
        		case 1 :
        			$_REQUEST ['funcionarioTipoSangre']='A';
        			break;
        		case 2:
        			$_REQUEST ['funcionarioTipoSangre']='B';
        			break;
        		case 3 :
        			$_REQUEST ['funcionarioTipoSangre']='O';
        			break;
        		case 4 :
        			$_REQUEST ['funcionarioTipoSangre']='AB';
        			break;
        		default:
        			$_REQUEST ['funcionarioTipoSangre']='NULL';
        			break;
        	}
        }
        
        if(isset($_REQUEST['funcionarioSangreRH'])){
        	switch($_REQUEST ['funcionarioSangreRH']){
        		case 1 :
        			$_REQUEST ['funcionarioSangreRH']='Positivo';
        			break;
        		case 2 :
        			$_REQUEST ['funcionarioSangreRH']='Negativo';
        			break;
        		default:
        			$_REQUEST ['funcionarioSangreRH']='NULL';
        			break;
        	}
        }
        
        if(isset($_REQUEST['funcionarioTipoLibreta'])){
        	switch($_REQUEST ['funcionarioTipoLibreta']){
        		case 1 :
        			$_REQUEST ['funcionarioTipoLibreta']='Primera';
        			break;
        		case 2 :
        			$_REQUEST ['funcionarioTipoLibreta']='Segunda';
        			break;
        	}
        }else{
        	$_REQUEST ['funcionarioTipoLibreta']='NULL';
        }
        
        $valorSoporteLib;
        if(isset($_REQUEST['funcionarioSoporteLibreta'])){
        	$valorSoporteLib = $_REQUEST['funcionarioSoporteLibreta'];
        }else{
        	$valorSoporteLib = NULL;
        }
        
        if(isset($_REQUEST['funcionarioGrupoEtnico'])){
        	switch($_REQUEST ['funcionarioGrupoEtnico']){
        		case 1 :
        			$_REQUEST ['funcionarioGrupoEtnico']='Afrodescendiente';
        			break;
        		case 2:
        			$_REQUEST ['funcionarioGrupoEtnico']='Indigenas';
        			break;
        		case 3 :
        			$_REQUEST ['funcionarioGrupoEtnico']='Raizales';
        			break;
        		case 4 :
        			$_REQUEST ['funcionarioGrupoEtnico']='Rom';
        			break;
        		default:
        			$_REQUEST ['funcionarioGrupoEtnico']='NULL';
        			break;
        	}
        }
        
        if(isset($_REQUEST['funcionarioGrupoLGBT'])){
        	switch($_REQUEST ['funcionarioGrupoLGBT']){
        		case 1 :
        			$_REQUEST ['funcionarioGrupoLGBT']='TRUE';
        			break;
        		case 2 :
        			$_REQUEST ['funcionarioGrupoLGBT']='FALSE';
        			break;
        		default:
        			$_REQUEST ['funcionarioGrupoLGBT']='NULL';
        			break;
        	}
        }
        
        if(isset($_REQUEST['funcionarioCabezaFamilia'])){
        	switch($_REQUEST ['funcionarioCabezaFamilia']){
        		case 1 :
        			$_REQUEST ['funcionarioCabezaFamilia']='TRUE';
        			break;
        		case 2 :
        			$_REQUEST ['funcionarioCabezaFamilia']='FALSE';
        			break;
        		default:
        			$_REQUEST ['funcionarioCabezaFamilia']='NULL';
        			break;
        	}
        }
        
        if(isset($_REQUEST['funcionarioPersonasCargo'])){
        	switch($_REQUEST ['funcionarioPersonasCargo']){
        		case 1 :
        			$_REQUEST ['funcionarioPersonasCargo']='TRUE';
        			break;
        		case 2 :
        			$_REQUEST ['funcionarioPersonasCargo']='FALSE';
        			break;
        		default:
        			$_REQUEST ['funcionarioPersonasCargo']='NULL';
        			break;
        	}
        }
        
        $datosUbicacionNacimiento = array(
        		'paisNacimiento' => $_REQUEST['funcionarioPaisNacimiento'],
        		'departamentoNacimiento' => $_REQUEST['funcionarioDepartamentoNacimiento'],
        		'ciudadNacimiento' => $_REQUEST['funcionarioCiudadNacimiento']
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarUbicacionNacimiento",$datosUbicacionNacimiento);
        $id_ubicacion_nacimiento = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosUbicacionNacimiento, "insertarUbicacionNacimiento");
        
        
        $datosPersonalesBasicos = array(
        		'fechaNacimiento' => $_REQUEST['funcionarioFechaNacimiento'],
        		'edadNacimiento' => $_REQUEST['funcionarioEdad'],
        		'numeroLibreta' => $_REQUEST['funcionarioNumeroLibreta'],
        		'numeroDistritoLibreta' => $_REQUEST['funcionarioDistritoLibreta'],
        		'soporteLibreta' => $valorSoporteLib,
        		'soporteCaracterizacion' => $_REQUEST['funcionarioSoporteCaracterizacion'],
        		'fk_ubicacion' => $id_ubicacion_nacimiento[0][0]
        );
        
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarInformacionPersonalBasica",$datosPersonalesBasicos);
        $id_informacion_personal_basica = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosUbicacionNacimiento, "insertarInformacionPersonalBasica");

//******************************************************************************************************************************************************       
     
        if(isset($_REQUEST['funcionarioContactoEstrato'])){
        	switch($_REQUEST ['funcionarioContactoEstrato']){
        		case 1 :
        			$_REQUEST ['funcionarioContactoEstrato']='Uno';
        			break;
        		case 2 :
        			$_REQUEST ['funcionarioContactoEstrato']='Dos';
        			break;
        		case 3 :
        			$_REQUEST ['funcionarioContactoEstrato']='Tres';
        			break;
        		case 4 :
        			$_REQUEST ['funcionarioContactoEstrato']='Cuatro';
        			break;
        		case 5 :
        			$_REQUEST ['funcionarioContactoEstrato']='Cinco';
        			break;
        		case 6 :
        			$_REQUEST ['funcionarioContactoEstrato']='Seis';
        			break;
        		default:
             		$_REQUEST ['funcionarioContactoEstrato']='NULL';
       				break;
        	}
        }
        
        $datosUbicacionContacto = array(
        		'paisContacto' => $_REQUEST['funcionarioContactoPais'],
        		'departamentoContacto' => $_REQUEST['funcionarioContactoDepartamento'],
        		'ciudadContacto' => $_REQUEST['funcionarioContactoCiudad']
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarUbicacionContacto",$datosUbicacionContacto);
        $id_ubicacion_contacto = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosUbicacionContacto, "insertarUbicacionContacto");
        
        
        $datosResidenciaContactos = array(
        		'nacionalidad' => $_REQUEST['funcionarioContactoNacionalidad'],
        		'localidadContacto' => $_REQUEST['funcionarioContactoLocalidad'],
        		'barrioContacto' => $_REQUEST['funcionarioContactoBarrio'],
        		'direccionContacto' => $_REQUEST['funcionarioContactoDireccion'],
        		'soporteEstrato' => $_REQUEST['funcionarioSoporteEstrato'],
        		'soporteResidencia' => $_REQUEST['funcionarioSoporteResidencia'],
        		'telefonoFijoContacto' => $_REQUEST['funcionarioContactoTelFijo'],
        		'telefonoMovilContacto' => $_REQUEST['funcionarioContactoTelMovil'],
        		'emailContacto' => $_REQUEST['funcionarioContactoEmail'],
        		'telefonoFijoOficina' => $_REQUEST['funcionarioContactoOrganiTelOficina'],
        		'emailOficina' => $_REQUEST['funcionarioContactoOrganiEmail'],
        		'direccionOficina' => $_REQUEST['funcionarioContactoOrganiDireccion'],
        		'cargoOficina' => $_REQUEST['funcionarioContactoOrganiCargo'],
        		'fk_ubicacion' => $id_ubicacion_contacto[0][0]
        		
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarDatosResidenciaCont",$datosResidenciaContactos);
        $id_datos_residencia = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosResidenciaContactos);//********************************
        
//*****************************************************************************************************************
        
        
        
        $datosUbicacionFormacionBasica = array(
        		'paisFormacionBasica' => $_REQUEST['funcionarioFormacionBasicaPais'],
        		'departamentoFormacionBasica' => $_REQUEST['funcionarioFormacionBasicaDepartamento'],
        		'ciudadFormacionBasica' => $_REQUEST['funcionarioFormacionBasicaCiudad']
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarUbicacionFormacionBasica",$datosUbicacionFormacionBasica);
        $id_ubicacion_formacion_basica = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosUbicacionFormacionBasica, "insertarUbicacionFormacionBasica");
        
        $datosFormacionAcademicaBasica = array(
        		'modalidadBasica' => $_REQUEST['funcionarioFormacionBasicaModalidad'],
        		'colegioBasica' => $_REQUEST['funcionarioFormacionBasicaColegio'],
        		'tituloBasica' => $_REQUEST['funcionarioFormacionBasicaTitul'],
        		'fechaGradoBasica' => $_REQUEST['funcionarioFechaFormacionBasica'],
        		'soporteBasica' => $_REQUEST['funcionarioSoporteFormacionBasica'],
        		'fk_ubicacion' => $id_ubicacion_formacion_basica[0][0]
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarFormacionBasica",$datosFormacionAcademicaBasica);
        $id_formacion_basica = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosFormacionAcademicaBasica);//********************************
        
 //**************************************************************************************************************
 
        
        $datosUbicacionFormacionMedia = array(
        		'paisFormacionMedia' => $_REQUEST['funcionarioFormacionMediaPais'],
        		'departamentoFormacionMedia' => $_REQUEST['funcionarioFormacionMediaDepartamento'],
        		'ciudadFormacionMedia' => $_REQUEST['funcionarioFormacionMediaCiudad']
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarUbicacionFormacionMedia",$datosUbicacionFormacionMedia);
        $id_ubicacion_formacion_media = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosUbicacionFormacionMedia, "insertarUbicacionFormacionMedia");
        
        
        $datosFormacionAcademicaMedia = array(
        		'modalidadMedia' => $_REQUEST['funcionarioFormacionMediaModalidad'],
        		'colegioMedia' => $_REQUEST['funcionarioFormacionMediaColegio'],
        		'tituloMedia' => $_REQUEST['funcionarioFormacionMediaTitul'],
        		'fechaGradoMedia' => $_REQUEST['funcionarioFechaFormacionMedia'],
        		'soporteMedia' => $_REQUEST['funcionarioSoporteFormacionMedia'],
        		'fk_ubicacion' => $id_ubicacion_formacion_media[0][0]
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarFormacionMedia",$datosFormacionAcademicaMedia);
        $id_formacion_media = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda",$datosFormacionAcademicaMedia);//********************************
        
 //***************************************************************************************************************
        
        $datosFormacionAcademicaFuncionario = array(
        		'fk_id_formacion_basica' => $id_formacion_basica[0][0],
        		'fk_id_formacion_media' => $id_formacion_media[0][0]
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarFormacionFuncionario",$datosFormacionAcademicaFuncionario);
        $id_datos_formacion_funcionario = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosFormacionAcademicaFuncionario, "insertarFormacionFuncionario");
        
 //***************************************************************************************************************       
        
        
        $datosInvestigacion = array(
        		'tematicaInvestigacion' => $_REQUEST['funcionarioPublicacionesTematica'],
        		'tipoInvestigacion' => $_REQUEST['funcionarioPublicacionesTipo'],
        		'logrosInvestigacion' => $_REQUEST['funcionarioPublicacionesLogros'],
        		'referenciasInvestigacion' => $_REQUEST['funcionarioPublicacionesReferencias']
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarFormacionInvestigacion",$datosInvestigacion);
        $id_publicacion = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosInvestigacion, "insertarFormacionInvestigacion");//********************************
        
//*******************************************************************************************************************        
        
        $datosFuncionario = array(
        		'fk_id_datos_identificacion' => $id_datos_identificacion[0][0],
        		'fk_id_informacion_personal_basica' => $id_informacion_personal_basica[0][0],
        		'fk_id_datos_residencia' => $id_datos_residencia[0][0],
        		'fk_id_datos_formacion_funcionario' => $id_datos_formacion_funcionario[0][0],
        		'fk_id_publicacion' => $id_publicacion[0][0],
        		'documento_fun' => $_REQUEST ['funcionarioDocumento']
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("insertarFuncionario",$datosFuncionario);
        $id_funcionario = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosFuncionario, "insertarFuncionario");//********************************
        

//****************************************************************************************************************
        
        // ---------------- INICIO: Lista Variables Control--------------------------------------------------------
        
        $cantidadFormacionSuperior = $_REQUEST['funcionarioRegistrosSuperior'];
        $cantidadFormacionInformal = $_REQUEST['funcionarioRegistrosInformal'];
        $cantidadIdiomas = $_REQUEST['funcionarioRegistrosIdioma'];
        $cantidadExperiencia = $_REQUEST['funcionarioRegistrosExperiencia'];
        $cantidadReferenciasPer = $_REQUEST['funcionarioRegistrosReferencia'];
        
        // ---------------- FIN: Lista Variables Control--------------------------------------------------------
        // -------------------------------------------------- Campos Dinamicos ----------------------------------
        $count = 0;
        
        while($count < $cantidadFormacionSuperior){
        	
        	if(isset($_REQUEST['funcionarioFormacionSuperiorModalidad_'.$count])){
        		switch($_REQUEST ['funcionarioFormacionSuperiorModalidad_'.$count]){
        			case 1 :
        				$_REQUEST ['funcionarioFormacionSuperiorModalidad_'.$count]='Tecnica';
        				break;
        			case 2 :
        				$_REQUEST ['funcionarioFormacionSuperiorModalidad_'.$count]='Tecnologica';
        				break;
        			case 3 :
        				$_REQUEST ['funcionarioFormacionSuperiorModalidad_'.$count]='Tecnologica Especializada';
        				break;
        			case 4 :
        				$_REQUEST ['funcionarioFormacionSuperiorModalidad_'.$count]='Universitaria';
        				break;
        			case 5 :
        				$_REQUEST ['funcionarioFormacionSuperiorModalidad_'.$count]='Especializacion';
        				break;
        			case 6 :
        				$_REQUEST ['funcionarioFormacionSuperiorModalidad_'.$count]='Maestria';
        				break;
        			case 7 :
        				$_REQUEST ['funcionarioFormacionSuperiorModalidad_'.$count]='Doctorado';
        		}
        	}
        	
        	if(isset($_REQUEST['funcionarioFormacionSuperiorGraduado_'.$count])){
        		switch($_REQUEST ['funcionarioFormacionSuperiorGraduado_'.$count]){
        			case 1 :
        				$_REQUEST ['funcionarioFormacionSuperiorGraduado_'.$count]='TRUE';
        				break;
        			case 2 :
        				$_REQUEST ['funcionarioFormacionSuperiorGraduado_'.$count]='FALSE';
        				break;
        		}
        	}
        	
        	
        	$datosUbicacionFormacionSuperior = array(
        			'paisFormacionSuperior' => $_REQUEST['funcionarioFormacionSuperiorPais_'.$count],
        			'departamentoFormacionSuperior' => $_REQUEST['funcionarioFormacionSuperiorDepartamento_'.$count],
        			'ciudadFormacionSuperior' => $_REQUEST['funcionarioFormacionSuperiorCiudad_'.$count]
        	);
        	
        	$cadenaSql = $this->miSql->getCadenaSql("insertarUbicacionFormacionSuperior",$datosUbicacionFormacionSuperior);
        	$id_ubicacion_formacion_superior = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosUbicacionFormacionSuperior, "insertarUbicacionFormacionSuperior");
        	

        	$datosFormacionAcademicaSuperior = array(
        			'modalidadAcademica' => $_REQUEST ['funcionarioFormacionSuperiorModalidad_'.$count],
        			'semestresCursados' => $_REQUEST['funcionarioFormacionSuperiorSemestres_'.$count],
        			'esGraduado' => $_REQUEST ['funcionarioFormacionSuperiorGraduado_'.$count],
        			'resolucionConvalidacion' => $_REQUEST['funcionarioFormacionSuperiorResolucionConvali_'.$count],
        			'fechaConvalidacion' => $_REQUEST['funcionarioFechaConvalidaSuperior_'.$count],
        			'entidadConvalidacion' => $_REQUEST['funcionarioFormacionSuperiorEntidadConvali_'.$count],
        			'universidadSuperior' => $_REQUEST['funcionarioFormacionSuperiorUniversidad_'.$count],
        			'tituloSuperior' => $_REQUEST['funcionarioFormacionSuperiorTituloObtenido_'.$count],
        			'fechaGraduacionSuperior' => $_REQUEST['funcionarioFechaTituloSuperior_'.$count],
        			'numeroTarjetaProfesional' => $_REQUEST['funcionarioFormacionSuperiorNumeroTarjeta_'.$count],
        			'fechaExpedicionTarjeta' => $_REQUEST['funcionarioFechaTarjetaSuperior_'.$count],
        			'soporteSuperior' => $_REQUEST['funcionarioSoporteFormacionSuperior_'.$count],
        			'fk_ubicacion' => $id_ubicacion_formacion_superior[0][0],
        			'fk_formacion_academica_funcionario' => $id_datos_formacion_funcionario[0][0]
        	);
        	
        	$cadenaSql = $this->miSql->getCadenaSql("insertarFormacionSuperior",$datosFormacionAcademicaSuperior);
        	$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        	
        	$count++;
        }
        
//****************************************************************************************************************        
        
        $count = 0;
        
        while($count < $cantidadFormacionInformal){
        	
        	$datosFormacionAcademicaInformal = array(
        			'cursoInformal' => $_REQUEST['funcionarioFormacionInformalCurso_'.$count],
        			'entidadCurso' => $_REQUEST['funcionarioFormacionInformalCursoLugar_'.$count],
        			'intensidadHoraria' => $_REQUEST['funcionarioFormacionInformalCursoIntensidad_'.$count],
        			'fechaTerminacion' => $_REQUEST['funcionarioFechaInformal_'.$count],
        			'soporteInformal' => $_REQUEST['funcionarioSoporteFormacionInformal_'.$count],
        			'fk_formacion_academica_funcionario' => $id_datos_formacion_funcionario[0][0]
        	);
        	
        	$cadenaSql = $this->miSql->getCadenaSql("insertarFormacionInformal",$datosFormacionAcademicaInformal);
        	$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        	
        	$count++;
        } 
        
//****************************************************************************************************************

        
        $count = 0;
        
        while($count < $cantidadIdiomas){
        	
        	if(isset($_REQUEST['funcionarioFormacionIdioma_'.$count])){
        		switch($_REQUEST ['funcionarioFormacionIdioma_'.$count]){
        			case 1 :
        				$_REQUEST ['funcionarioFormacionIdioma_'.$count]='Ingles';
        				break;
        			case 2 :
        				$_REQUEST ['funcionarioFormacionIdioma_'.$count]='Frances';
        				break;
        			case 3 :
        				$_REQUEST ['funcionarioFormacionIdioma_'.$count]='Aleman';
        				break;
        			case 4 :
        				$_REQUEST ['funcionarioFormacionIdioma_'.$count]='Portugues';
        				break;
        			case 5 :
        				$_REQUEST ['funcionarioFormacionIdioma_'.$count]='Italiano';
        				break;
        			case 6 :
        				$_REQUEST ['funcionarioFormacionIdioma_'.$count]='Mandarin';
        				break;
        			case 7 :
        				$_REQUEST ['funcionarioFormacionIdioma_'.$count]='Otro';
        				break;
        		}
        	}
        	
        	if(isset($_REQUEST['funcionarioFormacionIdiomaNivel_'.$count])){
        		switch($_REQUEST ['funcionarioFormacionIdiomaNivel_'.$count]){
        			case 1 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivel_'.$count]='(A1) B\E1sico';
        				break;
        			case 2 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivel_'.$count]='(A2) Elemental';
        				break;
        			case 3 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivel_'.$count]='(B1) Pre-Intermedio';
        				break;
        			case 4 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivel_'.$count]='(B2) Intermedio Alto';
        				break;
        			case 5 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivel_'.$count]='(C1) Avanzado';
        				break;
        			case 6 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivel_'.$count]='(C2) Superior';
        				break;
        		}
        	}
        	
        	if(isset($_REQUEST['funcionarioFormacionIdiomaNivelHabla_'.$count])){
        		switch($_REQUEST ['funcionarioFormacionIdiomaNivelHabla_'.$count]){
        			case 1 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelHabla_'.$count]='Aceptable';
        				break;
        			case 2 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelHabla_'.$count]='Bueno';
        				break;
        			case 3 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelHabla_'.$count]='Excelente';
        				break;
        			default :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelHabla_'.$count]=NULL;
        				break;
        		}
        	}
        	
        	if(isset($_REQUEST['funcionarioFormacionIdiomaNivelLee_'.$count])){
        		switch($_REQUEST ['funcionarioFormacionIdiomaNivelLee_'.$count]){
        			case 1 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelLee_'.$count]='Aceptable';
        				break;
        			case 2 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelLee_'.$count]='Bueno';
        				break;
        			case 3 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelLee_'.$count]='Excelente';
        				break;
        			default :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelLee_'.$count]=NULL;
        				break;
        		}
        	}
        	
        	if(isset($_REQUEST['funcionarioFormacionIdiomaNivelEscribe_'.$count])){
        		switch($_REQUEST ['funcionarioFormacionIdiomaNivelEscribe_'.$count]){
        			case 1 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelEscribe_'.$count]='Aceptable';
        				break;
        			case 2 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelEscribe_'.$count]='Bueno';
        				break;
        			case 3 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelEscribe_'.$count]='Excelente';
        				break;
        			default :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelEscribe_'.$count]=NULL;
        				break;
        		}
        	}
        	
        	if(isset($_REQUEST['funcionarioFormacionIdiomaNivelEscucha_'.$count])){
        		switch($_REQUEST ['funcionarioFormacionIdiomaNivelEscucha_'.$count]){
        			case 1 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelEscucha_'.$count]='Aceptable';
        				break;
        			case 2 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelEscucha_'.$count]='Bueno';
        				break;
        			case 3 :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelEscucha_'.$count]='Excelente';
        				break;
        			default :
        				$_REQUEST ['funcionarioFormacionIdiomaNivelEscucha_'.$count]=NULL;
        				break;
        		}
        	}
        	
        	$datosFormacionAcademicaIdiomas = array(
        			'fk_funcionario' => $id_funcionario[0][0],
        			'idioma' => $_REQUEST ['funcionarioFormacionIdioma_'.$count],
        			'universidadIdioma' => $_REQUEST['funcionarioFormacionIdiomaUniversidad_'.$count],
        			'nivel' => $_REQUEST ['funcionarioFormacionIdiomaNivel_'.$count],
        			'habla' => $_REQUEST ['funcionarioFormacionIdiomaNivelHabla_'.$count],
        			'lee' => $_REQUEST ['funcionarioFormacionIdiomaNivelLee_'.$count],
        			'escribe' => $_REQUEST ['funcionarioFormacionIdiomaNivelEscribe_'.$count],
        			'escucha' => $_REQUEST ['funcionarioFormacionIdiomaNivelEscucha_'.$count],
        			'soporteIdioma' => $_REQUEST['funcionarioSoporteIdioma_'.$count],
        			'observacionIdioma' => $_REQUEST['funcionarioIdiomaObservacion_'.$count]
        	);
        	
        	$cadenaSql = $this->miSql->getCadenaSql("insertarFormacionIdiomas",$datosFormacionAcademicaIdiomas);
        	$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        	
        	$count++;
        }
        
//**********************************************************************************************************************        
        
        $count = 0;
        
        while($count < $cantidadExperiencia){
        	
        	if(isset($_REQUEST['funcionarioExperienciaTipo_'.$count])){
        		switch($_REQUEST ['funcionarioExperienciaTipo_'.$count]){
        			case 1 :
        				$_REQUEST ['funcionarioExperienciaTipo_'.$count]='Publica';
        				break;
        			case 2 :
        				$_REQUEST ['funcionarioExperienciaTipo_'.$count]='Privada';
        				break;
        		}
        	}
        	     
        	$datosUbicacionExperiencia = array(
        			'paisExperiencia' => $_REQUEST ['funcionarioExperienciaPais_'.$count],
        			'departamentoExperiencia' => $_REQUEST ['funcionarioExperienciaDepartamento_'.$count],
        			'ciudadExperiencia' => $_REQUEST ['funcionarioExperienciaCiudad_'.$count]
        	);
        	 
        	$cadenaSql = $this->miSql->getCadenaSql("insertarUbicacionExperiencia",$datosUbicacionExperiencia);
        	$id_ubicacion_experiencia = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosUbicacionExperiencia, "insertarUbicacionExperiencia");
        	 
        	
        	$datosExperiencia = array(
        			'fk_funcionario' => $id_funcionario[0][0],
        			'nombreEmpresa' => $_REQUEST['funcionarioExperienciaEmpresa_'.$count],
        			'nitEmpresa' => $_REQUEST['funcionarioExperienciaEmpresaNIT_'.$count],
        			'tipoEntidad' => $_REQUEST ['funcionarioExperienciaTipo_'.$count],
        			'fk_ubicacion' => $id_ubicacion_experiencia[0][0],
        			'emailEmpresa' => $_REQUEST['funcionarioExperienciaEmpresaCorreo_'.$count],
        			'telefonoEmpresa' => $_REQUEST['funcionarioExperienciaEmpresaTelefono_'.$count],
        			'fechaIngreso' => $_REQUEST['funcionarioFechaEntradaExperiencia_'.$count],
        			'fechaRetiro' => $_REQUEST['funcionarioFechaSalidaExperiencia_'.$count],
        			'dependenciaEmpresa' => $_REQUEST['funcionarioExperienciaEmpresaDependencia_'.$count],
        			'cargoEmpresa' => $_REQUEST['funcionarioExperienciaEmpresaCargo_'.$count],
        			'horasTrabajo' => $_REQUEST['funcionarioExperienciaEmpresaHoras_'.$count],
        			'soporteExperiencia' => $_REQUEST['funcionarioSoporteExperiencia_'.$count]
        	);
        	$cadenaSql = $this->miSql->getCadenaSql("insertarExperienciaLaboral",$datosExperiencia);
        	$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        	
        	$count++;
        }
        
        
//****************************************************************************************************************

        
        $count = 0;
        
        while($count < $cantidadReferenciasPer){
        	
        	if(isset($_REQUEST['funcionarioReferenciaTipo_'.$count])){
        		switch($_REQUEST ['funcionarioReferenciaTipo_'.$count]){
        			case 1 :
        				$_REQUEST ['funcionarioReferenciaTipo_'.$count]='Personal';
        				break;
        			case 2 :
        				$_REQUEST ['funcionarioReferenciaTipo_'.$count]='Profesional';
        				break;
        			default:
        				$_REQUEST ['funcionarioReferenciaTipo_'.$count]=NULL;
        		}
        	}
        	
        	$datosReferencias = array(
        			'fk_funcionario' => $id_funcionario[0][0],
        			'nombresReferencia' => $_REQUEST['funcionarioReferenciaNombres_'.$count],
        			'tipoReferencia' => $_REQUEST ['funcionarioReferenciaTipo_'.$count],
        			'apellidosReferencia' => $_REQUEST['funcionarioReferenciaApellidos_'.$count],
        			'telefonoReferencia' => $_REQUEST['funcionarioReferenciaTelefono_'.$count],
        			'relacionReferencia' => $_REQUEST['funcionarioReferenciaRelacion_'.$count],
        			'soporteReferencia' => $_REQUEST['funcionarioSoporteReferencia_'.$count]
        	);
        	
        	$cadenaSql = $this->miSql->getCadenaSql("insertarReferenciasPersonales",$datosReferencias);
        	$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        	
        	$count++;
        	 
        }
        
        
//*****************************************************************************************************************

        /*var_dump($cadenaSql);
        //var_dump("El ID es..... ".$id_salida[0][0]);
        //exit;
        
        var_dump("
        		*******************************************************
        		Registro Completo Satisfactorio
        			Las Tablas
        					Identificacion Basica
        					Datos de Nacimiento fueron almacenados
        					Información Residencia Contacto
        					Información Formación Basica
        					Información Formación Media
        					Relacion Formacion Funcionario
        					Informacion Investigacion
        					Creación del Funcionario
        					n Registros Formacion Superior
        					n Registros Formacion Informal
        					n Registros Formacion Idiomas
        					n Registros Experiencia Laboral
        					n Registros Referencias Personales
        			Con sus
        					id_ubicacion (5)
        					id_ubicacion (n Formacion Superior)
        					id_ubicacion (n Experiencia Laboral)
        			Retorno de
	        					(id_datos_identificacion)
	        					(id_informacion_personal_basica)
	        					(id_datos_residencia)
        
	        						(id_formacion_basica)
	        						(id_formacion_media)
	        					(id_datos_formacion_funcionario)
	        					(id_publicacion)
        					id_funcionario
        
        			Insercion de id_datos_formacion_funcionario (n -> Formacion - Superior, Informal)
        			Insercion de id_funcionario 				(n -> Idioma, Experiencia Laboral, Referencias)
        		*******************************************************");
        exit;*/
        
        //var_dump("TEXTO");exit;
        
        /*if(isset($id_funcionario[0][0]) && isset($id_datos_formacion_funcionario[0][0])){
        	$insertar = true;
        	//var_dump("INSERTO");exit;
        }else{
        	$insertar = false;
        	//var_dump("NO INSERTO");exit;
        }*/
        
        if (isset($id_funcionario[0][0]) && isset($id_datos_formacion_funcionario[0][0])) {
        	//var_dump("ENTRO INSERTAR");exit;
        	$this->miConfigurador->setVariableConfiguracion("cache", true);
        	Redireccionador::redireccionar('inserto', $datosPersonaNatural);
        	exit();
        } else {
        	//var_dump("ENTRO NO INSERTAR");exit;
        	//$this->miConfigurador->setVariableConfiguracion("cache", true);
        	Redireccionador::redireccionar('noInserto', $datosPersonaNatural);
        	//var_dump("TEXTO NO INS");exit;
        	exit();
        }
        
        
        //Al final se ejecuta la redirección la cual pasará el control a otra página
        
        //Redireccionador::redireccionar('form');
    	        
    }
    
    function resetForm(){
        foreach($_REQUEST as $clave=>$valor){
             
            if($clave !='pagina' && $clave!='development' && $clave !='jquery' &&$clave !='tiempo'){
                unset($_REQUEST[$clave]);
            }
        }
    }
    
}

$miProcesador = new FormProcessor ( $this->lenguaje, $this->sql );
$resultado = $miProcesador->procesarFormulario ();

?>