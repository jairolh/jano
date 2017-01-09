<?php

namespace bloquesPersona\personaJuridica\funcion;

include_once ('Redireccionador.php');
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
		
		// Aquí va la lógica de procesamiento
		$conexion = 'estructura';
		$primerRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$datosUbicacion = array (
				'pais' => $_REQUEST ['personaNaturalPais'],
				'departamento' => $_REQUEST ['personaNaturalDepartamento'],
				'ciudad' => $_REQUEST ['personaNaturalCiudad'] 
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( "insertarUbicacion", $datosUbicacion );
		$id_ubicacion = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda", $datosUbicacion, "insertarUbicacion" );
		
		if (isset ( $_REQUEST ['personaJuridicaIdentificacion'] )) {
			switch ($_REQUEST ['personaJuridicaIdentificacion']) {
				case 1 :
					$_REQUEST ['personaJuridicaIdentificacion'] = '4';
					break;
				
				case 2 :
					$_REQUEST ['personaJuridicaIdentificacion'] = '5';
					break;
			}
		}
		
		if (isset ( $_REQUEST ['compuesto'] )) {
			switch ($_REQUEST ['compuesto']) {
				case 1 :
					$_REQUEST ['compuesto'] = 'SI';
					break;
				
				case 2 :
					$_REQUEST ['compuesto'] = 'NO';
					break;
			}
		}
		
		if (isset ( $_REQUEST ['autorretenedor'] )) {
			switch ($_REQUEST ['autorretenedor']) {
				case 1 :
					$_REQUEST ['autorretenedor'] = 'Si';
					break;
				
				case 2 :
					$_REQUEST ['autorretenedor'] = 'No';
					break;
			}
		}
		
		if (isset ( $_REQUEST ['GranContribuyente'] )) {
			switch ($_REQUEST ['GranContribuyente']) {
				case 1 :
					$_REQUEST ['GranContribuyente'] = 'Si';
					break;
				
				case 2 :
					$_REQUEST ['GranContribuyente'] = 'No';
					break;
			}
		}
		
		$datos = array (
				'tipoDocumento' => $_REQUEST ['personaJuridicaIdentificacion'],
				'numeroDocumento' => $_REQUEST ['personaJuridicaDocumento'],
				'fk_ubicacion' => $id_ubicacion [0] [0],
				'razonSocial' => $_REQUEST ['razonSocial'],
				'compuesto' => $_REQUEST ['compuesto'],
				'contribuyente' => $_REQUEST ['GranContribuyente'],
				'autorretenedor' => $_REQUEST ['autorretenedor'] 
		)
		;
		
		$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "insertarRegistroBasico", $datos );
		$primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "acceso" );
		
		if (isset ( $_REQUEST ['tipoDeTercero'] )) {
			switch ($_REQUEST ['tipoDeTercero']) {
				case 1 :
					$_REQUEST ['tipoDeTercero'] = 'Individual';
					break;
				
				case 2 :
					$_REQUEST ['tipoDeTercero'] = 'Consorcio';
					break;
				
				case 3 :
					$_REQUEST ['tipoDeTercero'] = 'Union Temporal';
					break;
			}
		}
		
		if (isset ( $_REQUEST ['claseEntidad'] )) {
			switch ($_REQUEST ['claseEntidad']) {
				case 1 :
					$_REQUEST ['claseEntidad'] = 'Pública';
					break;
				
				case 2 :
					$_REQUEST ['claseEntidad'] = 'Privada';
					break;
			}
		}
		
		if (isset ( $_REQUEST ['dependencia'] )) {
			switch ($_REQUEST ['dependencia']) {
				case 1 :
					$_REQUEST ['dependencia'] = 'Dependiente';
					break;
				
				case 2 :
					$_REQUEST ['dependencia'] = 'No dependiente';
					break;
			}
		}
		$datosConsorcio = array (
				'identificacion' => $_REQUEST ['tipoIdentifiacionConsorcio'],
				'tipoTercero' => $_REQUEST ['tipoDeTercero'],
				'entidad' => $_REQUEST ['claseEntidad'],
				'dependencia' => $_REQUEST ['dependencia'] 
		)
		;
		
		var_dump ( $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "insertarRegistroConsorcio", $datosConsorcio ) );
		exit ();
		$id_consorcio = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda", $datosConsorcio, "insertarRegistroConsorcio" );
		
		$datosPersonaConsorcio = array (
				'documento' => $_REQUEST ['personaJuridicaDocumento'],
				'consecutivo' => $id_consorcio [0] [0] 
		);
		
		$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "insertarPersonaComercial", $datosPersonaComercial );
		$primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "acceso" );
		
		if (isset ( $_REQUEST ['personaNaturalBanco'] )) {
			switch ($_REQUEST ['personaNaturalBanco']) {
				case 1 :
					$_REQUEST ['personaNaturalBanco'] = 'Banco de Bogotá';
					break;
				case 2 :
					$_REQUEST ['personaNaturalBanco'] = 'Banco Popular';
					break;
				case 3 :
					$_REQUEST ['personaNaturalBanco'] = 'Bancolombia';
					break;
				case 4 :
					$_REQUEST ['personaNaturalBanco'] = 'CityBank Colombia';
					break;
				case 5 :
					$_REQUEST ['personaNaturalBanco'] = 'GNB Colombia S.A.';
					break;
				case 6 :
					$_REQUEST ['personaNaturalBanco'] = 'BBVA Colombia';
					break;
			}
			
			if (isset ( $_REQUEST ['personaNaturalTipoCuenta'] )) {
				switch ($_REQUEST ['personaNaturalTipoCuenta']) {
					case 1 :
						$_REQUEST ['personaNaturalTipoCuenta'] = 'Ahorros';
						break;
					case 2 :
						$_REQUEST ['personaNaturalTipoCuenta'] = 'Corriente';
						break;
				}
			}
			
			if (isset ( $_REQUEST ['personaNaturalTipoPago'] )) {
				switch ($_REQUEST ['personaNaturalTipoPago']) {
					case 1 :
						$_REQUEST ['personaNaturalTipoPago'] = 'Transferencia';
						break;
					case 2 :
						$_REQUEST ['personaNaturalTipoPago'] = 'SAP';
						break;
				}
			}
			
			if (isset ( $_REQUEST ['personaNaturalEconomicoEstado'] )) {
				switch ($_REQUEST ['personaNaturalEconomicoEstado']) {
					case 1 :
						$_REQUEST ['personaNaturalEconomicoEstado'] = 'Activo';
						break;
					case 2 :
						$_REQUEST ['personaNaturalEconomicoEstado'] = 'Inactivo';
						break;
				}
			}
			
			$datosCom = array (
					'consecutivo' => $_REQUEST ['personaNaturalConsecutivo'],
					'banco' => $_REQUEST ['personaNaturalBanco'],
					'tipoCuenta' => $_REQUEST ['personaNaturalTipoCuenta'],
					'numeroCuenta' => $_REQUEST ['personaNaturalNumeroCuenta'],
					'tipoPago' => $_REQUEST ['personaNaturalTipoPago'],
					'estado' => $_REQUEST ['personaNaturalEconomicoEstado'],
					'fecha' => $_REQUEST ['fechaCreacionConsulta1'],
					'creador' => $_REQUEST ['personaNaturalCreo'],
					'soporteRUT' => $_REQUEST ['personaNaturalSoporteRUT'] 
			);
			
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "insertarRegistroComercial", $datosCom );
			$id_comercial = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda", $datosCom, "insertarRegistroComercial" );
			
			$datosPersonaComercial = array (
					'documento' => $_REQUEST ['personaNaturalDocumento'],
					'consecutivo' => $id_comercial [0] [0] 
			);
			
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "insertarPersonaComercial", $datosPersonaComercial );
			$primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "acceso" );
			
			if (isset ( $_REQUEST ['personaNaturalContactoTipo'] )) {
				switch ($_REQUEST ['personaNaturalContactoTipo']) {
					case 1 :
						$_REQUEST ['personaNaturalContactoTipo'] = 'Dirección';
						break;
					case 2 :
						$_REQUEST ['personaNaturalContactoTipo'] = 'e-mail';
						break;
					case 3 :
						$_REQUEST ['personaNaturalContactoTipo'] = 'Teléfono fijo';
						break;
					case 4 :
						$_REQUEST ['personaNaturalContactoTipo'] = 'Teléfono Movil';
						break;
					case 5 :
						$_REQUEST ['personaNaturalBanco'] = 'Fax';
						break;
				}
				
				if (isset ( $_REQUEST ['personaNaturalContactosEstado'] )) {
					switch ($_REQUEST ['personaNaturalContactosEstado']) {
						case 1 :
							$_REQUEST ['personaNaturalContactosEstado'] = 'Activo';
							break;
						case 2 :
							$_REQUEST ['personaNaturalContactosEstado'] = 'Inactivo';
							break;
					}
				}
				
				$datosUbicacionContacto = array (
						'pais' => $_REQUEST ['personaNaturalContactoPais'],
						'departamento' => $_REQUEST ['personaNaturalContactoDepartamento'],
						'ciudad' => $_REQUEST ['personaNaturalContactoCiudad'] 
				);
				
				$cadenaSql = $this->miSql->getCadenaSql ( "insertarUbicacion", $datosUbicacionContacto );
				$id_ubicacion_contacto = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda", $datosUbicacionContacto, "insertarUbicacion" );
				
				$datosContacto = array (
						'tipo' => $_REQUEST ['personaNaturalContactoTipo'],
						'descripcion' => $_REQUEST ['personaNaturalContactosDescrip'],
						'estado' => $_REQUEST ['personaNaturalContactosEstado'],
						'observacion' => $_REQUEST ['personaNaturalContactosObserv'],
						'fecha' => $_REQUEST ['fechaCreacionConsulta'],
						'creador' => $_REQUEST ['personaNaturalContactosUsuarioCreo'],
						'ubicacion' => $id_ubicacion_contacto [0] [0] 
				);
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "insertarRegistroContacto", $datosContacto );
				$id_contacto = $primerRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda", $datosUbicacion, "insertarRegistroContacto" );
				
				$datosPersonaContacto = array (
						'documento' => $_REQUEST ['personaNaturalDocumento'],
						'consecutivo' => $id_contacto [0] [0] 
				);
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "insertarPersonaContacto", $datosPersonaContacto );
				$primerRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "acceso" );
				// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("insertarRegistroComercial",$datos);
				// $primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "acceso");
				// Al final se ejecuta la redirección la cual pasará el control a otra página
				
				if ($datos[0][1] == $datosPersonaContacto[0][0]) {
					 
					$this->miConfigurador->setVariableConfiguracion("cache", true);
					Redireccionador::redireccionar('inserto', $datos);
					exit();
				} else {
					 
					//$this->miConfigurador->setVariableConfiguracion("cache", true);
					Redireccionador::redireccionar('noInserto', $datos);
					//var_dump("TEXTO NO INS");exit;
					exit();
				}
			}
			function resetForm() {
				foreach ( $_REQUEST as $clave => $valor ) {
					
					if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
						unset ( $_REQUEST [$clave] );
					}
				}
			}
		}
	}
}

$miProcesador = new FormProcessor ( $this->lenguaje, $this->sql );

$resultado = $miProcesador->procesarFormulario ();

