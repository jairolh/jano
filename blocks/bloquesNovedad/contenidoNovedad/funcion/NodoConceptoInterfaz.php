<?php
namespace bloquesNovedad\contenidoNovedad\funcion;

/**
*	Interfaz para la construcción de la clase nodoConcepto o de cualquier clase que cumple la misma función de nodoConcepto
*/
interface NodoConceptoInterfaz{

    public function evaluarConcepto();
    
    public function getValor();
    
    public function getNombre();
}
?>