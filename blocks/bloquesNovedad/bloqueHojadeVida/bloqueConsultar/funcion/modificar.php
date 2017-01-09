<?php

namespace bloquesNovedad\bloqueHojadeVida\bloqueConsultar\funcion;

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
    	
    	//var_dump($_REQUEST);exit;
    	
        //Aquí va la lógica de procesamiento
        
        $conexion = 'estructura';
        $primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
       
        //*****************************************************************************************************
        $cadenaSql1 = $this->miSql->getCadenaSql("buscarInfoIdent", $_REQUEST['funcionarioDocumento']);
        $matrizInfoExpe = $primerRecursoDB->ejecutarAcceso($cadenaSql1, "busqueda");
        	
        //--var_dump($matrizInfoExpe[0][0]);//id datos de expedicion
        //--var_dump($matrizInfoExpe[0][1]);//id ubicacion
        //var_dump($matrizInfoExpe[0][2]);
        //var_dump($matrizInfoExpe[0][3]);
        	
        $cadenaSql2 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizInfoExpe[0][1]);
        $matrizUbicacion = $primerRecursoDB->ejecutarAcceso($cadenaSql2, "busqueda");
        	
        //--var_dump($matrizUbicacion[0][0]);//id pais
        //--var_dump($matrizUbicacion[0][1]);//id departamento
        //--var_dump($matrizUbicacion[0][2]);//id ciudad
        
        $cadenaSql3 = $this->miSql->getCadenaSql("consultarFuncionario", $_REQUEST['funcionarioDocumento']);
        $matrizFuncionario = $primerRecursoDB->ejecutarAcceso($cadenaSql3, "busqueda");
        	
        //var_dump($matrizFuncionario[0][0]); //id funcionario
        //var_dump($matrizFuncionario[0][1]); //id datos de expedicion
        //var_dump($matrizFuncionario[0][2]); //id informacion personal
        //var_dump($matrizFuncionario[0][3]); //id datos residencia
        //var_dump($matrizFuncionario[0][4]); //id datos formacion funcionario
        //var_dump($matrizFuncionario[0][5]); //id publicacion
        //****************************************************************************************************
        
        $cadenaSql4 = $this->miSql->getCadenaSql("consultarInformacionPersonalBasica", $matrizFuncionario[0][2]);
        $matrizInfoPersonal = $primerRecursoDB->ejecutarAcceso($cadenaSql4, "busqueda");
        
        //var_dump($matrizInfoPersonal[0][1]);
        
        $cadenaSql5 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizInfoPersonal[0][1]);
        $matrizUbicacionInfoPer = $primerRecursoDB->ejecutarAcceso($cadenaSql5, "busqueda");
        
        //****************************************************************************************************
        
        $cadenaSql6 = $this->miSql->getCadenaSql("consultarDatosResidenciaCont", $matrizFuncionario[0][3]);
        $matrizInfoResidencia = $primerRecursoDB->ejecutarAcceso($cadenaSql6, "busqueda");
        
        $cadenaSql7 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizInfoResidencia[0][1]);
        $matrizUbicacionInfoRes = $primerRecursoDB->ejecutarAcceso($cadenaSql7, "busqueda");
        
        //****************************************************************************************************
        
        $cadenaSql8 = $this->miSql->getCadenaSql("consultarFormacionAcademicaFuncionario", $matrizFuncionario[0][4]);
        $matrizFormacion = $primerRecursoDB->ejecutarAcceso($cadenaSql8, "busqueda");
        
        //var_dump($matrizFormacion[0][0]);//id formacion basica
        //var_dump($matrizFormacion[0][1]);//id formacion media
        
        $cadenaSql9 = $this->miSql->getCadenaSql("consultarFormacionBasica", $matrizFormacion[0][0]);
        $matrizFormacionBasica = $primerRecursoDB->ejecutarAcceso($cadenaSql9, "busqueda");
        
        $cadenaSql11 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizFormacionBasica[0][1]);
        $matrizUbicacionBasica = $primerRecursoDB->ejecutarAcceso($cadenaSql11, "busqueda");
        
        //var_dump($matrizFormacionBasica);
        //var_dump($matrizUbicacionBasica);
        //-----------------------------------------------------------------------------------------------
        
        $cadenaSql10 = $this->miSql->getCadenaSql("consultarFormacionMedia", $matrizFormacion[0][1]);
        $matrizFormacionMedia = $primerRecursoDB->ejecutarAcceso($cadenaSql10, "busqueda");
        
        $cadenaSql12 = $this->miSql->getCadenaSql("consultarUbicacion", $matrizFormacionMedia[0][1]);
        $matrizUbicacionMedia = $primerRecursoDB->ejecutarAcceso($cadenaSql12, "busqueda");
        
        //****************************************************************************************************
        
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
        
        if (isset($_REQUEST['funcionarioDepartamento']) && isset($_REQUEST['funcionarioCiudad'])) {
	        $datosUbicacionExpedicion = array(
	        		'paisExpedicion' => $_REQUEST['funcionarioPais'],
	        		'departamentoExpedicion' => $_REQUEST['funcionarioDepartamento'],
	        		'ciudadExpedicion' => $_REQUEST['funcionarioCiudad'],
	        		'id_ubicacion_expe' => $matrizInfoExpe[0][1]
	        );
	        
	        $cadenaSql = $this->miSql->getCadenaSql("modificarUbicacionExpedicion",$datosUbicacionExpedicion);
	        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
        }
        
        
        $datosInformacionPersonalExpedicion = array (
        		'numeroDocumento' => $_REQUEST ['funcionarioDocumento'], //Llave Foranea fk Persona Natural
        		'soporteDocumento' => $_REQUEST ['funcionarioSoporteIden'],
        		'fechaExpedicionDocumento' => $_REQUEST ['funcionarioFechaExpDocFunMod'],
        		'id_datos_expedicion' => $matrizInfoExpe[0][0]
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("modificarIdentificacionDocumento",$datosInformacionPersonalExpedicion);
		$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
		
        
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
        
        if (isset($_REQUEST['funcionarioDepartamentoNacimiento']) && isset($_REQUEST['funcionarioCiudadNacimiento'])) {
	        $datosUbicacionNacimiento = array(
	        		'paisNacimiento' => $_REQUEST['funcionarioPaisNacimiento'],
	        		'departamentoNacimiento' => $_REQUEST['funcionarioDepartamentoNacimiento'],
	        		'ciudadNacimiento' => $_REQUEST['funcionarioCiudadNacimiento'],
	        		'id_ubicacion_naci' => $matrizInfoPersonal[0][1]
	        );
	        
	        $cadenaSql = $this->miSql->getCadenaSql("modificarUbicacionNacimiento",$datosUbicacionNacimiento);
	        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
        }
        
        $datosPersonalesBasicos = array(
        		'fechaNacimiento' => $_REQUEST['funcionarioFechaNacimiento'],
        		'edadNacimiento' => $_REQUEST['funcionarioEdad'],
        		'numeroLibreta' => $_REQUEST['funcionarioNumeroLibreta'],
        		'numeroDistritoLibreta' => $_REQUEST['funcionarioDistritoLibreta'],
        		'soporteLibreta' => $valorSoporteLib,
        		'soporteCaracterizacion' => $_REQUEST['funcionarioSoporteCaracterizacion'],
        		'id_info_personal' => $matrizFuncionario[0][2]
        );
        
        
        $cadenaSql = $this->miSql->getCadenaSql("modificarInformacionPersonalBasica",$datosPersonalesBasicos);
        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
        
        

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
        
        if (isset($_REQUEST['funcionarioContactoDepartamento']) && isset($_REQUEST['funcionarioContactoCiudad'])) {
	        $datosUbicacionContacto = array(
	        		'paisContacto' => $_REQUEST['funcionarioContactoPais'],
	        		'departamentoContacto' => $_REQUEST['funcionarioContactoDepartamento'],
	        		'ciudadContacto' => $_REQUEST['funcionarioContactoCiudad'],
	        		'id_ubicacion_contac' => $matrizInfoResidencia[0][1]
	        );
	        
	        $cadenaSql = $this->miSql->getCadenaSql("modificarUbicacionContacto",$datosUbicacionContacto);
	        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
	    }
        
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
        		'id_info_contacto' => $matrizFuncionario[0][3]
        		
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("modificarDatosResidenciaCont",$datosResidenciaContactos);
        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        
        
//*****************************************************************************************************************
        //--------------------------------------------------
        //--------------------------------------------------
        //--------------------------------------------------
        
        
        $datosUbicacionFormacionBasica = array(
        		'paisFormacionBasica' => $_REQUEST['funcionarioFormacionBasicaPais'],
        		'departamentoFormacionBasica' => $_REQUEST['funcionarioFormacionBasicaDepartamento'],
        		'ciudadFormacionBasica' => $_REQUEST['funcionarioFormacionBasicaCiudad'],
        		'id_ubicacion_basica' => $matrizFormacionBasica[0][1]
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("modificarUbicacionFormacionBasica",$datosUbicacionFormacionBasica);
        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
        
        $datosFormacionAcademicaBasica = array(
        		'modalidadBasica' => $_REQUEST['funcionarioFormacionBasicaModalidad'],
        		'colegioBasica' => $_REQUEST['funcionarioFormacionBasicaColegio'],
        		'tituloBasica' => $_REQUEST['funcionarioFormacionBasicaTitul'],
        		'fechaGradoBasica' => $_REQUEST['funcionarioFechaFormacionBasica'],
        		'soporteBasica' => $_REQUEST['funcionarioSoporteFormacionBasica'],
        		'id_formacion_basica' => $matrizFormacion[0][0]
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("modificarFormacionBasica",$datosFormacionAcademicaBasica);
        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        
 //**************************************************************************************************************
 
        
        $datosUbicacionFormacionMedia = array(
        		'paisFormacionMedia' => $_REQUEST['funcionarioFormacionMediaPais'],
        		'departamentoFormacionMedia' => $_REQUEST['funcionarioFormacionMediaDepartamento'],
        		'ciudadFormacionMedia' => $_REQUEST['funcionarioFormacionMediaCiudad'],
        		'id_ubicacion_media' => $matrizFormacionMedia[0][1]
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("modificarUbicacionFormacionMedia",$datosUbicacionFormacionMedia);
        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
        
        
        $datosFormacionAcademicaMedia = array(
        		'modalidadMedia' => $_REQUEST['funcionarioFormacionMediaModalidad'],
        		'colegioMedia' => $_REQUEST['funcionarioFormacionMediaColegio'],
        		'tituloMedia' => $_REQUEST['funcionarioFormacionMediaTitul'],
        		'fechaGradoMedia' => $_REQUEST['funcionarioFechaFormacionMedia'],
        		'soporteMedia' => $_REQUEST['funcionarioSoporteFormacionMedia'],
        		'id_formacion_media' => $matrizFormacion[0][1]
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("modificarFormacionMedia",$datosFormacionAcademicaMedia);
        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        
 //***************************************************************************************************************       
        
        
        $datosInvestigacion = array(
        		'tematicaInvestigacion' => $_REQUEST['funcionarioPublicacionesTematica'],
        		'tipoInvestigacion' => $_REQUEST['funcionarioPublicacionesTipo'],
        		'logrosInvestigacion' => $_REQUEST['funcionarioPublicacionesLogros'],
        		'referenciasInvestigacion' => $_REQUEST['funcionarioPublicacionesReferencias'],
        		'id_mod_publicacion' => $matrizFuncionario[0][5]
        );
        
        $cadenaSql = $this->miSql->getCadenaSql("modificarFormacionInvestigacion",$datosInvestigacion);
        $primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        
//****************************************************************************************************************
        
        
        $cadenaSql13 = $this->miSql->getCadenaSql("consultarCantidadFormacionSuperior", $matrizFuncionario[0][4]);
        $matrizCantFormacionSuperior = $primerRecursoDB->ejecutarAcceso($cadenaSql13, "busqueda");
        
        //var_dump(count($matrizCantFormacionSuperior)); //Obtengo los id x cada formacion superior
        
        $cadenaSql14 = $this->miSql->getCadenaSql("consultarCantidadFormacionInformal", $matrizFuncionario[0][4]);
        $matrizCantFormacionInformal = $primerRecursoDB->ejecutarAcceso($cadenaSql14, "busqueda");
        
        //var_dump(count($matrizCantFormacionInformal)); //Obtengo los id x cada formacion informal
        
        $cadenaSql15 = $this->miSql->getCadenaSql("consultarCantidadFormacionIdiomas", $matrizFuncionario[0][0]);
        $matrizCantFormacionIdioma = $primerRecursoDB->ejecutarAcceso($cadenaSql15, "busqueda");
        
        //var_dump(count($matrizCantFormacionIdioma)); //Obtengo los id x cada formacion idioma
        //var_dump(array_reverse($matrizCantFormacionIdioma)); //array_reverse ordenar id
        
        $cadenaSql16 = $this->miSql->getCadenaSql("consultarCantidadExperiencia", $matrizFuncionario[0][0]);
        $matrizCantExperiencia = $primerRecursoDB->ejecutarAcceso($cadenaSql16, "busqueda");
        
        //var_dump(count($matrizCantExperiencia)); //Obtengo los id x cada experiencia laboral
        
        $cadenaSql17 = $this->miSql->getCadenaSql("consultarCantidadReferencia", $matrizFuncionario[0][0]);
        $matrizCantReferencia = $primerRecursoDB->ejecutarAcceso($cadenaSql17, "busqueda");
        
        //var_dump(count($matrizCantReferencia)); //Obtengo los id x cada experiencia laboral
        
        
        //--
        //************************************************************************************************************
        //************************************************************************************************************
        
        $cadenaSql18 = $this->miSql->getCadenaSql("consultarReferenciasPersonales", $matrizFuncionario[0][0]);
        $matrizReferencia = $primerRecursoDB->ejecutarAcceso($cadenaSql18, "busqueda");
        
        $cadenaSql19 = $this->miSql->getCadenaSql("consultarExperienciaLaboral", $matrizFuncionario[0][0]);
        $matrizExperiencia = $primerRecursoDB->ejecutarAcceso($cadenaSql19, "busqueda");
        
        $cadenaSql21 = $this->miSql->getCadenaSql("consultarFormacionIdiomas", $matrizFuncionario[0][0]);
        $matrizIdiomas = $primerRecursoDB->ejecutarAcceso($cadenaSql21, "busqueda");
        
        $cadenaSql22 = $this->miSql->getCadenaSql("consultarFormacionInformal", $matrizFuncionario[0][4]);
        $matrizInformal = $primerRecursoDB->ejecutarAcceso($cadenaSql22, "busqueda");
        
        $cadenaSql23 = $this->miSql->getCadenaSql("consultarFormacionSuperior", $matrizFuncionario[0][4]);
        $matrizSuperior = $primerRecursoDB->ejecutarAcceso($cadenaSql23, "busqueda");
        
        
        //************************************************************************************************************
        
        
        
        
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
        	
        	if(isset($_REQUEST['funcionarioFormacionSuperiorNuevo_'.$count])){
        		
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
        				'fk_formacion_academica_funcionario' => $matrizFuncionario[0][4]
        		);
        		 
        		$cadenaSql = $this->miSql->getCadenaSql("insertarFormacionSuperior",$datosFormacionAcademicaSuperior);
        		$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        		
        		//Agregar
        	}else{
        		
        		if(isset($_REQUEST['funcionarioFormacionSuperiorEliminar_'.$count]) && isset($matrizCantFormacionSuperior[$count][0]) && $_REQUEST['funcionarioFormacionSuperiorEliminar_'.$count] == 'true'){
     
        			$datosFormacionAcademicaSuperior = array(
        					'id_formacion_superior' => $matrizCantFormacionSuperior[$count][0]
        			);
        			
        			$cadenaSql = $this->miSql->getCadenaSql("eliminarFormacionSuperior",$datosFormacionAcademicaSuperior);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
        			
        			
        			//Eliminar -- 
        		}else{
        			
        			$datosUbicacionFormacionSuperior = array(
        					'paisFormacionSuperior' => $_REQUEST['funcionarioFormacionSuperiorPais_'.$count],
        					'departamentoFormacionSuperior' => $_REQUEST['funcionarioFormacionSuperiorDepartamento_'.$count],
        					'ciudadFormacionSuperior' => $_REQUEST['funcionarioFormacionSuperiorCiudad_'.$count],
        					'id_ubicacion_superior' => $matrizSuperior[$count][4]
        			);
        			
        			$cadenaSql = $this->miSql->getCadenaSql("modificarUbicacionFormacionSuperior",$datosUbicacionFormacionSuperior);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
        			
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
        					'id_formacion_superior' => $matrizCantFormacionSuperior[$count][0]
        			);
        			 
        			$cadenaSql = $this->miSql->getCadenaSql("modificarFormacionSuperior",$datosFormacionAcademicaSuperior);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
        			
        			//Modificar
        		}
        	}
        	$count++;
        }
        
//****************************************************************************************************************        
      
        
        
        $count = 0;
        
        while($count < $cantidadFormacionInformal){
        	
        	if(isset($_REQUEST['funcionarioFormacionInformalNuevo_'.$count])){
        	
        		$datosFormacionAcademicaInformal = array(
        				'cursoInformal' => $_REQUEST['funcionarioFormacionInformalCurso_'.$count],
        				'entidadCurso' => $_REQUEST['funcionarioFormacionInformalCursoLugar_'.$count],
        				'intensidadHoraria' => $_REQUEST['funcionarioFormacionInformalCursoIntensidad_'.$count],
        				'fechaTerminacion' => $_REQUEST['funcionarioFechaInformal_'.$count],
        				'soporteInformal' => $_REQUEST['funcionarioSoporteFormacionInformal_'.$count],
        				'fk_formacion_academica_funcionario' => $matrizFuncionario[0][4]
        		);
        		 
        		$cadenaSql = $this->miSql->getCadenaSql("insertarFormacionInformal",$datosFormacionAcademicaInformal);
        		$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        		     
        		
        		//Agregar
        	}else{
        		if(isset($_REQUEST['funcionarioFormacionInformalEliminar_'.$count]) && isset($matrizCantFormacionInformal[$count][0]) && $_REQUEST['funcionarioFormacionInformalEliminar_'.$count] == 'true'){
        			
        			$datosFormacionAcademicaInformal = array(
        					'id_formacion_informal' => $matrizCantFormacionInformal[$count][0]
        			);
        			 
        			$cadenaSql = $this->miSql->getCadenaSql("eliminarFormacionInformal",$datosFormacionAcademicaInformal);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
        			
        			//Eliminar --
        		}else{
        			
        			$datosFormacionAcademicaInformal = array(
        					'cursoInformal' => $_REQUEST['funcionarioFormacionInformalCurso_'.$count],
        					'entidadCurso' => $_REQUEST['funcionarioFormacionInformalCursoLugar_'.$count],
        					'intensidadHoraria' => $_REQUEST['funcionarioFormacionInformalCursoIntensidad_'.$count],
        					'fechaTerminacion' => $_REQUEST['funcionarioFechaInformal_'.$count],
        					'soporteInformal' => $_REQUEST['funcionarioSoporteFormacionInformal_'.$count],
        					'id_formacion_informal' => $matrizCantFormacionInformal[$count][0]
        			);
        			 
        			$cadenaSql = $this->miSql->getCadenaSql("modificarFormacionInformal",$datosFormacionAcademicaInformal);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        			        
        			
        			//Modificar
        		}
        	}

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
        	
        	
        	if(isset($_REQUEST['funcionarioFormacionIdiomasNuevo_'.$count])){
        		 
        		$datosFormacionAcademicaIdiomas = array(
        				'fk_funcionario' => $matrizFuncionario[0][0],
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
        		
        		//Agregar
        	}else{
        		if(isset($_REQUEST['funcionarioFormacionIdiomasEliminar_'.$count]) && isset($matrizCantFormacionIdioma[$count][0]) && $_REQUEST['funcionarioFormacionIdiomasEliminar_'.$count] == 'true'){
        			
        			$datosFormacionAcademicaIdiomas = array(
        					'id_formacion_idioma' => $matrizCantFormacionIdioma[$count][0]
        			);
        			
        			$cadenaSql = $this->miSql->getCadenaSql("eliminarFormacionIdiomas",$datosFormacionAcademicaIdiomas);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//******************************** 
        			
        			//Eliminar --
        		}else{
        			 
        			$datosFormacionAcademicaIdiomas = array(
        					'idioma' => $_REQUEST ['funcionarioFormacionIdioma_'.$count],
        					'universidadIdioma' => $_REQUEST['funcionarioFormacionIdiomaUniversidad_'.$count],
        					'nivel' => $_REQUEST ['funcionarioFormacionIdiomaNivel_'.$count],
        					'habla' => $_REQUEST ['funcionarioFormacionIdiomaNivelHabla_'.$count],
        					'lee' => $_REQUEST ['funcionarioFormacionIdiomaNivelLee_'.$count],
        					'escribe' => $_REQUEST ['funcionarioFormacionIdiomaNivelEscribe_'.$count],
        					'escucha' => $_REQUEST ['funcionarioFormacionIdiomaNivelEscucha_'.$count],
        					'soporteIdioma' => $_REQUEST['funcionarioSoporteIdioma_'.$count],
        					'observacionIdioma' => $_REQUEST['funcionarioIdiomaObservacion_'.$count],
        					'id_formacion_idioma' => $matrizCantFormacionIdioma[$count][0]
        			);
        			 
        			$cadenaSql = $this->miSql->getCadenaSql("modificarFormacionIdiomas",$datosFormacionAcademicaIdiomas);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        			
        			
        			//Modificar
        		}
        	}
        	
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
        	
        	
        	if(isset($_REQUEST['funcionarioExperienciaNuevo_'.$count])){
        		 
        		$datosUbicacionExperiencia = array(
        				'paisExperiencia' => $_REQUEST ['funcionarioExperienciaPais_'.$count],
        				'departamentoExperiencia' => $_REQUEST ['funcionarioExperienciaDepartamento_'.$count],
        				'ciudadExperiencia' => $_REQUEST ['funcionarioExperienciaCiudad_'.$count]
        		);
        		
        		$cadenaSql = $this->miSql->getCadenaSql("insertarUbicacionExperiencia",$datosUbicacionExperiencia);
        		$id_ubicacion_experiencia = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosUbicacionExperiencia, "insertarUbicacionExperiencia");
        		
        		 
        		$datosExperiencia = array(
        				'fk_funcionario' => $matrizFuncionario[0][0],
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
        		
        		
        		//Agregar
        	}else{
        		if(isset($_REQUEST['funcionarioExperienciaEliminar_'.$count]) && isset($matrizCantExperiencia[$count][0]) && $_REQUEST['funcionarioExperienciaEliminar_'.$count] == 'true'){
        			 
        			$datosExperiencia = array(
        					'id_experiencia' => $matrizCantExperiencia[$count][0]
        			);
        			 
        			$cadenaSql = $this->miSql->getCadenaSql("eliminarExperienciaLaboral",$datosExperiencia);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        			
        			//Eliminar --
        		}else{
        			 
        			$datosUbicacionExperiencia = array(
        					'paisExperiencia' => $_REQUEST ['funcionarioExperienciaPais_'.$count],
        					'departamentoExperiencia' => $_REQUEST ['funcionarioExperienciaDepartamento_'.$count],
        					'ciudadExperiencia' => $_REQUEST ['funcionarioExperienciaCiudad_'.$count],
        					'id_ubicacion_experiencia' => $matrizExperiencia[$count][4]
        			);
        			
        			$cadenaSql = $this->miSql->getCadenaSql("modificarUbicacionExperiencia",$datosUbicacionExperiencia);
        			$id_ubicacion_experiencia = $primerRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosUbicacionExperiencia, "insertarUbicacionExperiencia");
        			
        			
        			$datosExperiencia = array(
        					'nombreEmpresa' => $_REQUEST['funcionarioExperienciaEmpresa_'.$count],
        					'nitEmpresa' => $_REQUEST['funcionarioExperienciaEmpresaNIT_'.$count],
        					'tipoEntidad' => $_REQUEST ['funcionarioExperienciaTipo_'.$count],
        					'emailEmpresa' => $_REQUEST['funcionarioExperienciaEmpresaCorreo_'.$count],
        					'telefonoEmpresa' => $_REQUEST['funcionarioExperienciaEmpresaTelefono_'.$count],
        					'fechaIngreso' => $_REQUEST['funcionarioFechaEntradaExperiencia_'.$count],
        					'fechaRetiro' => $_REQUEST['funcionarioFechaSalidaExperiencia_'.$count],
        					'dependenciaEmpresa' => $_REQUEST['funcionarioExperienciaEmpresaDependencia_'.$count],
        					'cargoEmpresa' => $_REQUEST['funcionarioExperienciaEmpresaCargo_'.$count],
        					'horasTrabajo' => $_REQUEST['funcionarioExperienciaEmpresaHoras_'.$count],
        					'soporteExperiencia' => $_REQUEST['funcionarioSoporteExperiencia_'.$count],
        					'id_experiencia' => $matrizCantExperiencia[$count][0]
        			);
        			$cadenaSql = $this->miSql->getCadenaSql("modificarExperienciaLaboral",$datosExperiencia);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        			
        			//Modificar
        		}
        	}
        	
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
        	
        	
        	if(isset($_REQUEST['funcionarioReferenciasNuevo_'.$count])){
        		 
        		$datosReferencias = array(
        				'fk_funcionario' => $matrizFuncionario[0][0],
        				'nombresReferencia' => $_REQUEST['funcionarioReferenciaNombres_'.$count],
        				'tipoReferencia' => $_REQUEST ['funcionarioReferenciaTipo_'.$count],
        				'apellidosReferencia' => $_REQUEST['funcionarioReferenciaApellidos_'.$count],
        				'telefonoReferencia' => $_REQUEST['funcionarioReferenciaTelefono_'.$count],
        				'relacionReferencia' => $_REQUEST['funcionarioReferenciaRelacion_'.$count],
        				'soporteReferencia' => $_REQUEST['funcionarioSoporteReferencia_'.$count]
        		);
        		 
        		$cadenaSql = $this->miSql->getCadenaSql("insertarReferenciasPersonales",$datosReferencias);
        		$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        		
        		//Agregar
        	}else{
        		if(isset($_REQUEST['funcionarioReferenciasEliminar_'.$count]) && isset($matrizCantReferencia[$count][0]) && $_REQUEST['funcionarioReferenciasEliminar_'.$count] == 'true'){
        			
        			$datosReferencias = array(
        					'id_referencia' => $matrizCantReferencia[$count][0]
        			);
        			$cadenaSql = $this->miSql->getCadenaSql("eliminarReferenciasPersonales",$datosReferencias);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        			//Eliminar --
        		}else{
        			
        			$datosReferencias = array(
        					'nombresReferencia' => $_REQUEST['funcionarioReferenciaNombres_'.$count],
        					'tipoReferencia' => $_REQUEST ['funcionarioReferenciaTipo_'.$count],
        					'apellidosReferencia' => $_REQUEST['funcionarioReferenciaApellidos_'.$count],
        					'telefonoReferencia' => $_REQUEST['funcionarioReferenciaTelefono_'.$count],
        					'relacionReferencia' => $_REQUEST['funcionarioReferenciaRelacion_'.$count],
        					'soporteReferencia' => $_REQUEST['funcionarioSoporteReferencia_'.$count],
        					'id_referencia' => $matrizCantReferencia[$count][0]
        			);
        			 
        			$cadenaSql = $this->miSql->getCadenaSql("modificarReferenciasPersonales",$datosReferencias);
        			$primerRecursoDB->ejecutarAcceso($cadenaSql, "acceso");//********************************
        			
        			//Modificar
        		}
        	}
        	
        	$count++;
        	 
        }
        
        
//*****************************************************************************************************************

        //var_dump($cadenaSql);
        
        
        
        /*if (isset($matrizInfoExpe[0][0])) {
         //var_dump("ENTRO INSERTAR");exit;
         $this->miConfigurador->setVariableConfiguracion("cache", true);
         Redireccionador::redireccionar('inserto', $datosPersonaNatural);
         exit();
         } else {
         //var_dump("ENTRO NO INSERTAR");exit;
         $this->miConfigurador->setVariableConfiguracion("cache", true);
         Redireccionador::redireccionar('noInserto', $datosPersonaNatural);
         exit();
         }*/
        /*
        var_dump("
        		Modificar - Datos de Identificación 	<Completo>
        		Modificar - Datos Personales Basicos	<Completo>
        		Modificar - Datos Residencia		<Completo>
        		Modificar - Datos Formacion Basica	<Completo>
        		Modificar - Datos Formacion Media	<Completo>
        		Modificar - Datos Investigaciones	<Completo>
        		Modificar - n Formacion Superior	<Completo>
        		Modificar - n Formacion Informal	<Completo>
        		Modificar - n Formacion Idioma	<Completo>
        		Modificar - n Experiencia Laboral	<Completo>
        		Modificar - n Referencia Laboral	<Completo>
        
        		Desarrollando...
				");
        
        var_dump($_REQUEST);
        
        exit; //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        
        */
        
        
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
        
        //var_dump($id_funcionario[0][0] . " y " .$id_datos_formacion_funcionario[0][0]);exit;
        
        /*if(isset($id_funcionario[0][0]) && isset($id_datos_formacion_funcionario[0][0])){
        	$insertar = true;
        	//var_dump("INSERTO");exit;
        }else{
        	$insertar = false;
        	//var_dump("NO INSERTO");exit;
        }*/
        
        if (isset($matrizFuncionario[0][0]) && isset($matrizFuncionario[0][4])) {
        	//var_dump("ENTRO INSERTAR");exit;
        	$this->miConfigurador->setVariableConfiguracion("cache", true);
        	Redireccionador::redireccionar('inserto', $datosPersonaNatural);
        	exit();
        } else {
        	//var_dump("ENTRO NO INSERTAR");exit;
        	$this->miConfigurador->setVariableConfiguracion("cache", true);
        	Redireccionador::redireccionar('noInserto', $datosPersonaNatural);
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