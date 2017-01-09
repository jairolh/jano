<?php

namespace bloquesNovedad\contenidoNovedad\funcion;

/**
*	Interfaz para la construcción del interprete para la validacion de sentencias y construcción y evaluacion de arboles de operaciones
*/
interface InterpreteInterfaz{

    public function evaluarSentencia($sentencia);
    
    public function generarArbol($nomina);
    
    public function evaluarArbol($nodoConcepto, $referencias);
}
?>