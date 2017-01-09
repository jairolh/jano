<?php

namespace inventarios\consultaGeneral\funcion;

use inventarios\gestionCompras\consultaOrdenServicios\funcion\redireccion;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}




class RegistradorOrden {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miFuncion;
	var $miSql;
	var $conexion;
	function __construct($lenguaje, $sql, $funcion) {
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miSql = $sql;
		$this->miFuncion = $funcion;
	}
	function procesarFormulario() {

            var_dump($_REQUEST);
            exit;
				
		$conexion = "inventarios";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		if ($_REQUEST ['objeto_contrato'] == '') {
		
			redireccion::redireccionar ( 'notextos' );
		}
		
		if ($_REQUEST ['forma_pago'] == '') {
		
			redireccion::redireccionar ( 'notextos' );
		}
		
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultarOrdenServicios', $_REQUEST ['numero_orden'] );
		$orden = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		

		
		$datosSupervisor = array (
				$_REQUEST ['nombre_supervisor'],
				$_REQUEST ['cargo_supervisor'],
				$_REQUEST ['dependencia_supervisor'],
				$orden[0]['id_supervisor'] 
		);
		
		// Actualizar Supervisor
		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarSupervisor', $datosSupervisor );
		$id_supervisor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$datosContratistaC = array (
				$_REQUEST ['nombre_razon_contratista'],
				$_REQUEST ['identifcacion_contratista'],
				$_REQUEST ['direccion_contratista'],
				$_REQUEST ['telefono_contratista'],
				$_REQUEST ['cargo_contratista'],
				$orden[0]['id_contratista']
		);
		
		// Actualizar Contratista
		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarContratista', $datosContratistaC );
		$id_ContratistaC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		
		$arreglo = array (
				$_REQUEST ['vigencia_disponibilidad'],
				$_REQUEST ['diponibilidad'],
				$_REQUEST ['valor_disponibilidad'],
				$_REQUEST ['fecha_diponibilidad'],
				$_REQUEST ['valorLetras_disponibilidad'],
				$_REQUEST ['vigencia_registro'],
				$_REQUEST ['registro'],
				$_REQUEST ['valor_registro'],
				$_REQUEST ['fecha_registro'],
				$_REQUEST ['valorL_registro'],
				$orden [0]['info_presupuestal']
		);
		
		
		
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarPresupuestal', $arreglo );
		
		$inf_pre = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "acceso" );
		
		
		
// 
			// Actualizar Orden
		
		$datosOrden = array (
				$_REQUEST['dependencia_solicitante'],
				$_REQUEST['rubro'],				
				$_REQUEST ['objeto_contrato'],
				isset ( $_REQUEST ['polizaA'] ),
				isset ( $_REQUEST ['polizaB'] ),
				isset ( $_REQUEST ['polizaC'] ),
				isset ( $_REQUEST ['polizaD'] ),
				$_REQUEST ['duracion'],
				$_REQUEST ['fecha_inicio_pago'],
				$_REQUEST ['fecha_final_pago'],
				$_REQUEST ['forma_pago'],
				$_REQUEST ['total_preliminar'],
				$_REQUEST ['iva'],
				$_REQUEST ['total'],
				$_REQUEST['id_ordenador'],
				$_REQUEST['vigencia_contratista'],
				$_REQUEST['nombreContratista'],
				$_REQUEST['numero_orden']
		);
		
	


		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarOrden', $datosOrden );

  
		$id_orden = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "acceso" );


		$datos = array (
				$_REQUEST ['numero_orden'] 
		);
		
		if ($id_orden == 1) {
			
			redireccion::redireccionar ( 'inserto', $datos );
		} else {
			
			redireccion::redireccionar ( 'noInserto', $datos );
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

$miRegistrador = new RegistradorOrden ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>