<?php
include 'Interprete.php';
include 'NodoConcepto.php';

$interprete = new Interprete();

$sentencia = 'IVAAA+((2+3)*RESRD)/+4-5';

$aceptado = $interprete->evaluarSentencia($sentencia);

echo "<br>".$aceptado."<br>";

$arbol = $interprete->generarArbol($sentencia);

?>