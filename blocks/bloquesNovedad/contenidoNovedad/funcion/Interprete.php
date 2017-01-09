<?php
namespace bloquesNovedad\contenidoNovedad\funcion;
include 'InterpreteInterfaz.php';
/**
*	Interprete
*	@package 	Interprete
*	@subpackage	Interprete
*	@author 	Fabio Parra
*/
class Interprete implements InterpreteInterfaz{

	/**
	*	Constructor de la clase Interprete
	*/
	function __construct(){

	}

	/**
	*	Verifica la validez de una sentencia
	*	@param  string $sentencia cadena de texto con la sentencia a evaluar
	*	@return	boolean
	*/
	function evaluarSentencia($sentencia){
		$long_variable = 5;
		$chars = str_split($sentencia);
		$flag_numero = false;
		$flag_variable = 0;
		$flag_parentesis = 0;
		$flag_signo = false;
		$pos = 0;
		foreach ($chars as $caracter) {
			if($caracter == "("){
				$flag_parentesis++;
			}
			if($caracter == ")"){
				if($flag_signo){
					return "Hay un error en la ubicacion del parentesis en la posicion $pos";
				}
				$flag_parentesis--;
			}
			if($caracter == "+" || $caracter == "-" ||$caracter == "*" ||$caracter == "/" ||$caracter == "^"){
				if($flag_signo){
					return "Hay dos signos seguidos en la posicion $pos";
				}else{
					$flag_signo=true;
				}
			}
			if($caracter != "+" && $caracter != "-" &&$caracter != "*" &&$caracter != "/" &&$caracter != "^"){
				$flag_signo=false;
			}
			if(ctype_upper($caracter)){
				$flag_variable++;
			}
			if(!ctype_upper($caracter)){
				if($flag_variable<$long_variable && $flag_variable>0){
					return "La variable no existe menos en la posicion $pos";
				}else if($flag_variable==$long_variable){
					$flag_variable=0;
				}
			}
			if($flag_parentesis<0){
				return "Parentesis fuera de sitio en la posicion $pos";
			}
			if($flag_variable>$long_variable){
				return "La variable no existe mas en la posicion $pos";
			}
			$pos++;
		}
		if($flag_parentesis>0){
			return "Todos los parentesis deben cerrarse";
		}
		return "true";
	}
    
    /**
	*	Genera el arbol de operaciones del concepto
	*	@param  string $nomina cadena de texto con la formula de calculo de la nomina 
	*	@return	NodoConcepto
	*/
    function generarArbol($nomina){
    	

    }
    
    /**
	*	Evalua el arbol de operac9iones con una referencia especifica o una lista de ellas
	*	@param NodoConcepto $nodoConcepto Arbol de operaciones
	*	@param Referencias $referencias Referencias especificas para el calculo de los valores del arbol
	*/
    function evaluarArbol($nodoConcepto, $referencias){

    }
}

?>