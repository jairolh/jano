<?php

namespace bloquesPersona\personaJuridica;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

/**
 * IMPORTANTE: Se recomienda que no se borren registros.
 * Utilizar mecanismos para - independiente del motor de bases de datos,
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
		$cadenaSql = '';
		switch ($tipo) {
			
			/**
			 * Clausulas específicas
			 */
			case 'insertarRegistroBasico' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'persona.persona_juridica ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'documento,';
				// $cadenaSql .= 'consecutivo,';
				$cadenaSql .= 'tipodocumento,';
				$cadenaSql .= 'id_ubicacion,';
				$cadenaSql .= 'razon_social,';
				$cadenaSql .= 'compuesto,';
				$cadenaSql .= 'gran_contribuyente,';
				$cadenaSql .= 'autoretenedor,';
				$cadenaSql .= 'soporte_documento,';
				$cadenaSql .= 'soporte_rut,';
				$cadenaSql .= 'estado_solicitud';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['numeroDocumento'] . ', ';
				// $cadenaSql .= '\'' .$_REQUEST ['consecutivo'].'\''. ', ';
				$cadenaSql .= $variable ['tipoDocumento'] . ', ';
				$cadenaSql .= $variable ['fk_ubicacion'] . ', ';
				$cadenaSql .= '\'' . $variable ['razonSocial'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['compuesto'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['contribuyente'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['autorretenedor'] . '\'' . ', ';
				$cadenaSql .= '\'' . 'Aún no funciona' . '\'' . ', ';
				$cadenaSql .= '\'' . 'Aún no funciona' . '\'' . ', ';
				$cadenaSql .= '\'' . 'Modificable' . '\' ';
				$cadenaSql .= ') ';
				break;
			
			case 'insertarUbicacion' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'otro.ubicacion ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_pais,';
				$cadenaSql .= 'id_departamento,';
				$cadenaSql .= 'id_ciudad';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['pais'] . ', ';
				$cadenaSql .= $variable ['departamento'] . ', ';
				$cadenaSql .= $variable ['ciudad'] . ' ';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_ubicacion; ";
				break;
			
			case 'insertarRegistroComercial' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'persona.info_comercial ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'consecutivo,';
				$cadenaSql .= 'banco,';
				$cadenaSql .= 'tipo_cuenta,';
				$cadenaSql .= 'numero_cuenta,';
				$cadenaSql .= 'tipo_pago,';
				$cadenaSql .= 'estado,';
				$cadenaSql .= 'fecha_creacion,';
				$cadenaSql .= 'usuario_creo,';
				$cadenaSql .= 'soporte_rut';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $_REQUEST ['personaNaturalConsecutivo'] . ', ';
				$cadenaSql .= $_REQUEST ['personaNaturalBanco'] . ', ';
				$cadenaSql .= $_REQUEST ['personaNaturalTipoCuenta'] . ', ';
				$cadenasql .= $_REQUEST ['personaNaturalNumeroCuenta'] . ', ';
				$cadenasql .= $_REQUEST ['personaNaturalTipoPago'] . ', ';
				$cadenaSql .= '\'' . $_REQUEST ['personaNaturalPrimerNombre'] . '\'' . ', ';
				$cadenaSql .= '\'' . $_REQUEST ['personaNaturalSegundoNombre'] . '\'' . ', ';
				$cadenaSql .= '\'' . $_REQUEST ['personaNaturalPrimerApellido'] . '\'' . ', ';
				$cadenaSql .= '\'' . $_REQUEST ['personaNaturalSegundoApellido'] . '\'' . ', ';
				$cadenaSql .= $_REQUEST ['personaNaturalContribuyente'] . ', ';
				$cadenaSql .= $_REQUEST ['personaNaturalAutorretenedor'] . ', ';
				$cadenaSql .= '\'' . $_REQUEST ['personaNaturalRegimen'] . '\'' . ', ';
				// $cadenaSql .= '\'' . $_REQUEST ['emailRegistro'] . '\''. ', ';
				$cadenaSql .= '\'' . 'Activo' . '\' ';
				$cadenaSql .= ') ';
				break;
			
			case 'insertarRegistroConsorcio' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'persona.consorcio ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'identificacion,';
				$cadenaSql .= 'tipo_tercero,';
				$cadenaSql .= 'entidad,';
				$cadenaSql .= 'dependencia,';
				$cadenaSql .= 'clasificacion_juridica,';
				$cadenaSql .= 'calsificacion_comercial,';
				$cadenaSql .= 'sector_economico';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['identificacion'] . ', ';
				$cadenaSql .= '\'' . $variable ['tipoTercero'] . '\'' . ', ';
				$cadenaSql .= '\'<' . $variable ['entidad'] . '>\'' . ', ';
				$cadenaSql .= '\'' . $_REQUEST ['dependencia'] . '\'' . ', ';
				$cadenaSql .= '\'' . 'en espera' . '\'' . ', ';
				$cadenaSql .= '\'' . 'en espera' . '\'' . ', ';
				$cadenaSql .= '\'' . 'en espera' . '\'';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  identificacion; ";
				break;
			
			case 'insertarPersonaComercial' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'persona.personaxcomercial ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'documento,';
				$cadenaSql .= 'consecutivo';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['documento'] . ', ';
				$cadenaSql .= $variable ['consecutivo'] . ' ';
				$cadenaSql .= ') ';
				break;
			
			case 'insertarRegistroContacto' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'persona.contacto ';
				$cadenaSql .= '( ';
				// $cadenaSql .= 'consecutivo,';
				$cadenaSql .= 'tipo,';
				$cadenaSql .= 'descripcion,';
				$cadenaSql .= 'estado,';
				$cadenaSql .= 'observacion,';
				$cadenaSql .= 'fecha_creacion,';
				$cadenaSql .= 'usuario_creo,';
				$cadenaSql .= 'id_ubicacion';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				// $cadenaSql .= $variable ['consecutivo']. ', ';
				$cadenaSql .= '\'' . $variable ['tipo'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['descripcion'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['estado'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['observacion'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['fecha'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['creador'] . '\'' . ', ';
				$cadenaSql .= $variable ['fk_ubicacion'];
				$cadenaSql .= ')';
				$cadenaSql .= "RETURNING  consecutivo; ";
				break;
			
			case 'insertarRegistroContacto' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'persona.contacto ';
				$cadenaSql .= '( ';
				// $cadenaSql .= 'consecutivo,';
				$cadenaSql .= 'tipo,';
				$cadenaSql .= 'descripcion,';
				$cadenaSql .= 'estado,';
				$cadenaSql .= 'observacion,';
				$cadenaSql .= 'fecha_creacion,';
				$cadenaSql .= 'usuario_creo,';
				$cadenaSql .= 'id_ubicacion';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				// $cadenaSql .= $variable ['consecutivo']. ', ';
				$cadenaSql .= '\'' . $variable ['tipo'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['descripcion'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['estado'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['observacion'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['fecha'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['creador'] . '\'' . ', ';
				$cadenaSql .= $variable ['fk_ubicacion'];
				$cadenaSql .= ')';
				$cadenaSql .= "RETURNING  consecutivo; ";
				break;
			
			case 'insertarPersonaContacto' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'persona.contactoxpernatural ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'documento,';
				$cadenaSql .= 'consecutivo';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['documento'] . ', ';
				$cadenaSql .= $variable ['consecutivo'] . ' ';
				$cadenaSql .= ') ';
				break;
			case 'insertarRegistroComercial' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'persona.info_comercial ';
				$cadenaSql .= '( ';
				// $cadenaSql .= 'consecutivo,';
				$cadenaSql .= 'banco,';
				$cadenaSql .= 'tipo_cuenta,';
				$cadenaSql .= 'numero_cuenta,';
				$cadenaSql .= 'tipo_pago,';
				$cadenaSql .= 'estado,';
				$cadenaSql .= 'fecha_creacion,';
				$cadenaSql .= 'usuario_creo,';
				$cadenaSql .= 'soporte_rut';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				// $cadenaSql .= $variable ['consecutivo']. ', ';
				$cadenaSql .= '\'' . $variable ['banco'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['tipoCuenta'] . '\'' . ', ';
				$cadenaSql .= $variable ['numeroCuenta'] . ', ';
				$cadenaSql .= '\'' . $variable ['tipoPago'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['estado'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['fecha'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['creador'] . '\'' . ', ';
				$cadenaSql .= '\'' . 'soporte RUT' . '\'';
				$cadenaSql .= ')';
				$cadenaSql .= "RETURNING  consecutivo; ";
				break;
			
			case 'insertarPersonaConsorcio' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'persona.perjuridicaxconsorcio ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'documento,';
				$cadenaSql .= 'identificacion';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['documento'] . ', ';
				$cadenaSql .= $variable ['consecutivo'] . ' ';
				$cadenaSql .= ') ';
				break;
			
			case 'buscarRegistroxPersona' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'documento as DOCUMENTO, ';
				$cadenaSql .= 'razon_social as RAZON_SOCIAL, ';
				$cadenaSql .= 'autorretenedor as AUTORRETENEDOR, ';
				$cadenaSql .= 'compuesto as COMPUESTO, ';
				$cadenaSql .= 'estado_solicitud as ESTADO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'persona.persona_juridica';
				// $cadenaSql .= 'WHERE ';
				// $cadenaSql .= 'ESTADO=\'' .'modificabl'. '\' OR ';
				// $cadenaSql .= 'ESTADO=\'' . 'rechazada' . '\' ';
				
				break;
			
			case 'buscarModificarxPersona' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'documento as DOCUMENTO, ';
				$cadenaSql .= 'primer_nombre as PRIMER_NOMBRE, ';
				$cadenaSql .= 'segundo_nombre as SEGUNDO_NOMBRE,';
				$cadenaSql .= 'primer_apellido as PRIMER_APELLIDO,';
				$cadenaSql .= 'segundo_apellido as SEGUNDO_APELLIDO,';
				$cadenaSql .= 'regimen_tributario as REGIMEN_TRIBUTARIO, ';
				$cadenaSql .= 'estado_solicitud as ESTADO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'persona.persona_natural';
				
				break;
			
			case 'buscarVerdetallexCargo' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'documento as	DOCUMENTO, ';
				$cadenaSql .= 'tipo_documento as TIPO_DOCUMENTO, ';
				$cadenaSql .= 'consecutivocutivo as CONSECUTIVO, ';
				$cadenaSql .= 'razon_social as RAZON_SOCIAL ';
// 				$cadenaSql .= 'segundo_nombre as SEGUNDO_NOMBRE, ';
// 				$cadenaSql .= 'primer_apellido as PRIMER_APELLIDO, ';
// 				$cadenaSql .= 'segundo_apellido as SEGUNDO_APELLIDO, ';
// 				$cadenaSql .= 'gran_contribuyente as CONTRIBUYENTE, ';
// 				$cadenaSql .= 'autoretenedor as AUTORRETENEDOR, ';
// 				$cadenaSql .= 'regimen_tributario as REGIMEN ';
// 				$cadenaSql .= 'estado_solicitud as ESTADO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'persona.persona_juridica';
				
				break;
			
			case 'modificarRegistro' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'parametro.cargo ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'nivel = ';
				$cadenaSql .= $variable ['nivelRegistro'] . ', ';
				$cadenaSql .= 'grado = ';
				$cadenaSql .= $variable ['gradoRegistro'] . ', ';
				$cadenaSql .= 'nombre = ';
				$cadenaSql .= '\'' . $variable ['nombreRegistro'] . '\', ';
				$cadenaSql .= 'sueldo = ';
				$cadenaSql .= $variable ['sueldoRegistro'] . ', ';
				$cadenaSql .= 'tipo_sueldo = ';
				$cadenaSql .= '\'' . $variable ['tipoSueldoRegistro'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'codigo_cargo = ';
				$cadenaSql .= '\'' . $variable ['codigoRegistro'] . '\'';
				break;
			
			case 'inactivarRegistro' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'parametro.cargo ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'estado = ';
				$cadenaSql .= '\'' . $variable ['estadoRegistro'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'codigo_cargo = ';
				$cadenaSql .= '\'' . $variable ['codigoRegistro'] . '\'';
				break;
			
			case 'buscarPais' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.pais';
				break;
			
			case 'buscarDepartamento' : // Provisionalmente solo Departamentos de Colombia
				
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
				$cadenaSql .= 'id_pais = ' . $variable . ';';
				break;
			
			case 'modificarRegistroComercial' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'persona.info_comercial ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'tipo_pago = ';
				$cadenaSql .= '\'' . $variable ['tipoPago'] . '\', ';
				$cadenaSql .= 'estado = ';
				$cadenaSql .= '\'' . $variable ['estado'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'numero_cuenta = ';
				$cadenaSql .= '\'' . $variable ['numeroCuenta'] . '\'';
				break;
			
			case 'modificarRegistroContacto' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'persona.contacto ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'estado = ';
				$cadenaSql .= '\'' . $variable ['estado'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'consecutivo = ';
				$cadenaSql .= '\'' . $variable ['consecutivo'] . '\'';
				break;
			
			case 'buscarCiudad' : // Provisionalmente Solo Ciudades de Colombia sin Agrupar
				
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
				$cadenaSql .= 'id_departamento = ' . $variable . ';';
				break;
		}
		
		return $cadenaSql;
	}
}
?>
