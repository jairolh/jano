<?php

namespace bloquesNovedad\contenidoNovedad\funcion;
include 'NodoConceptoInterfaz.php';
/**
*	NodoConcepto
*	@package 	Interprete
*	@subpackage	NodoConcepto
*	@author 	Fabio Parra
*/
class NodoConcepto implements NodoConceptoInterfaz{
	//Nombre del concepto que se esta evaluando
	var $nombreConcepto	= null;

	//Valor del concepto que se esta evaluando
	var $valorConcepto	= null;

	//Referencia de consulta propia del concepto
	var $referencia		= null;

	//Operadores involucrados en el calculo del valor del concepto
	var $operadores		= null;

	//Conceptos hojas del actual objeto
	var $conceptos		= array();

	/**
	*	Constructor de la clase nodoConcepto
	*	@param string $nombreConcepto Nombre del concepto que se esta evaluando
	*	@param string $referencia Referencia de consulta propia del concepto
	*	@param array $operadores Operadores involucrados en el calculo del valor del concepto
	*	@param array $conceptos Array de objetos tipo nodoConcepto
	*	@param double $valorConcepto Valor concreto del concepto en caso de tratarse de una hoja
	*/
	function __construct($nombreConcepto, $referencia, $operadores, $conceptos, $valorConcepto = null){
		$this->nombreConcepto	= $nombreConcepto;
		$this->valorConcepto	= $valorConcepto;
		$this->referencia		= $referencia;
		$this->operadores		= $operadores;
		$this->conceptos		= $conceptos;
	}

	/**
	*	Funcion para modificar el valor del concepto con base a los conceptos que esten en el objeto $conceptos
	*/
	function evaluarConcepto(){

	}

	/**
	*	Funcion para retornar el valor del concepto
	*	@return	double
	*/
	function getValor(){
		return $this->valorConcepto;
	}

	/**
	*	Funcion para retornar el nombre del concepto
	*	@return string
	*/
	function getNombre(){
		return $this->nombreConcepto;
	}

	function setConceptos($conceptos){
		$this->conceptos = $conceptos;
	}

	function agregarConcepto($concepto){
		$this->$conceptos[count($this->conceptos)] = $concepto;
	}

	function setOperador($operador){
		$this->operadores = $operador;
	}

}

?>