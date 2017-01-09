<?php

namespace bloquesPersona\personaNatural;

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
			case 'insertarCategoria' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'concepto.categoria_novedad ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'nombre,';
				$cadenaSql .= 'descripcion,';
				// $cadenaSql .= 'ley,';
				$cadenaSql .= 'estado';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable ['nombre'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['descripcion'] . '\'' . ', ';
				// $cadenaSql .= $variable ['ley'] . ', ';
				$cadenaSql .= '\'' . 'Activo' . '\'';
				$cadenaSql .= ') ';
				$cadenaSql .= "RETURNING  id_categoria; ";
				break;
			
			case 'insertarCategoriaxLey' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'concepto.catnovxldn ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_ldn,';
				$cadenaSql .= 'id_categoria';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= $variable ['ley'] . ', ';
				$cadenaSql .= $variable ['fk_categoria'];
				$cadenaSql .= ')';
				break;
			
			case 'versiones' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'conceptos.verxcatnovedad ';
				$cadenaSql .= '( ';
				$cadenaSql .= 'nombre,';
				$cadenaSql .= 'descripcion,';
				$cadenaSql .= 'ley,';
				$cadenaSql .= 'estado,';
				$cadenaSql .= 'id,';
				$cadenaSql .= 'fecha,';
				$cadenaSql .= 'usuario';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable ['nombre'] . '\'' . ', ';
				$cadenaSql .= '\'' . $variable ['descripcion'] . '\'' . ', ';
				$cadenaSql .= $variable ['ley'] . ', ';
				$cadenaSql .= '\'' . $variable ['estado'] . '\'' . ', ';
				$cadenaSql .= $variable ['id'] . ', ';
				$cadenaSql .= 'current_date' .', ';
				$cadenaSql .= '\'' .'Administrador'. '\'';
				$cadenaSql .= ') ';
				break;
			
			case 'buscarRegistroxActivo' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_categoria as ID, ';
				$cadenaSql .= 'nombre as NOMBRE, ';
				$cadenaSql .= 'descripcion as DESCRIPCION, ';
				$cadenaSql .= 'estado as ESTADO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'concepto.categoria_novedad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'ESTADO=\'' . 'Activo' . '\'';
				// $cadenaSql .= 'ESTADO=\'' . 'rechazada' . '\' ';
				
				break;
			
			case 'buscarVerdetallexCategoria' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_categoria as ID, ';
				$cadenaSql .= 'nombre as NOMBRE, ';
				$cadenaSql .= 'descripcion as DESCRIPCION, ';
				$cadenaSql .= 'estado as ESTADO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'concepto.categoria_novedad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_categoria = ';
				$cadenaSql .= $variable ['id'];
				break;
			
			case 'buscarIDLey' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ldn as ID_LDN ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'concepto.catnovxldn ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_categoria = ';
				$cadenaSql .= $variable ['id'];
				break;
			
			case 'buscarley' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.ley_decreto_norma ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ldn = ';
				$cadenaSql .= '\'' . $variable ['id'] . '\'';
				break;
			
			case 'buscar_ley' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ldn as ID, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.ley_decreto_norma';
				break;
			
			
			
			case 'buscarVerdetallexCargo' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_categoria as ID, ';
				$cadenaSql .= 'nombre as NOMBRE, ';
				$cadenaSql .= 'descripcion as DESCRIPCION, ';
				$cadenaSql .= 'estado as ESTADO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'concepto.categoria_novedad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_categoria = ';
				$cadenaSql .= $variable ['id'];
				
				break;
			
			case 'buscarConceptos' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'codigo as CODIGO, ';
				$cadenaSql .= 'nombre as NOMBRE, ';
				$cadenaSql .= 'descripcion as DESCRIPCION, ';
				$cadenaSql .= 'estado as ESTADO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'concepto.novedad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id = ';
				$cadenaSql .= $variable ['codigoRegistro'];
				break;
			
			case 'inactivarRegistro' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'concepto.categoria_novedad ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'estado = ';
				$cadenaSql .= '\'' . $variable ['estadoRegistro'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_categoria = ';
				$cadenaSql .= '\'' . $variable ['codigoRegistro'] . '\'';
				break;
			
			case 'modificarCategoria' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'concepto.categoria_novedad ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'nombre = ';
				$cadenaSql .= '\'' . $variable ['nombre'] . '\', ';
				$cadenaSql .= 'descripcion = ';
				$cadenaSql .= '\'' . $variable ['descripcion'] . '\' ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_categoria = ';
				$cadenaSql .= '\'' . $variable ['id'] . '\'';
				break;
			
			case 'modificarCategoriaLey' :
				$cadenaSql = 'UPDATE ';
				$cadenaSql .= 'concepto.catnovxldn ';
				$cadenaSql .= 'SET ';
				$cadenaSql .= 'id_ldn = ';
				$cadenaSql .= $variable ['ley'];
				$cadenaSql .= ' WHERE ';
				$cadenaSql .= 'id_categoria = ';
				$cadenaSql .= '\'' . $variable ['id'] . '\'';
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
