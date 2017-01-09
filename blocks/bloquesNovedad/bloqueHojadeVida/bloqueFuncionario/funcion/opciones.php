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


		//Al final se ejecuta la redirección la cual pasará el control a otra página
		//echo $_REQUEST[0]."     DATOS     ";
		//echo $_REQUEST[1];
		//$valorDecodificado = $this->miConfigurador->fabricaConexiones->crypto->decodificar ( $valorCodificado );
		//var_dump($_REQUEST);exit;
		//echo $this->campoSeguro('botonConsultarFun');
		
		
		if($_REQUEST['botonConsultarFun'] == 'true'){
			Redireccionador::redireccionar('consultarFun');
			break;
		}
		
		if($_REQUEST['botonModificarFun'] == 'true'){
			Redireccionador::redireccionar('modificarFun');
			break;
		}
		
		//var_dump("Proviene de Botones Mensaje.php");exit;
		
		/*
		$i=0;
		while($i<$_REQUEST['tamaño']){
			if($_REQUEST['botonModificar'.$i] == 'true'){
				RedireccionadorFP::redireccionar('modificar',$i);
				break;
			}
			if($_REQUEST['botonVerDetalle'.$i] == 'true'){
				RedireccionadorFP::redireccionar('verdetalle',$i);
				break;
			}
			if($_REQUEST['botonInactivar'.$i] == 'true'){
				RedireccionadorFP::redireccionar('inactivar',$i);
				break;
			}

			$i+=1;
		}*/
		 
		 
			

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
$resultado= $miProcesador->procesarFormulario ();