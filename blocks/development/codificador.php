<?php
include_once ("../../core/crypto/Encriptador.class.php");
$miCodificador = Encriptador::singleton ();
echo $miCodificador->codificar ( "localhost" ) . "<br>";	
echo $miCodificador->codificar ( "jano" ) . " | us<br>";	
echo $miCodificador->codificar ( "jano_admin" ) . " | us<br>";	
echo $miCodificador->codificar ( "4dm1n=jano2017" ) . " | pw<br>";
echo $miCodificador->codificar ( "jano_" ) . " | pre<br>";	
echo $miCodificador->codificar ( "public" ) . "<br>";


echo $miCodificador->decodificar ( "lYOdxSIOMjakTkGvwAJ04V8hFqt2_cs4lS0lHuaB2rw" ) . "<br>";
echo $miCodificador->decodificar ( "GjLRV1AfgseUTBDdcO62X2HJkEqjE4LGv_S3QK10wdo" ) . "<br>";
echo "<br>*<br>";
echo $miCodificador->decodificar ( "yDObloxv6yGRBU2fvG00cOsRp1sEETXzU95rpENwya0" ) . "<br>";



echo "<br>********<br>";


 $parametro=array(
"L167g09AY4lX0OuHieRw7p14jQA09ZY9RUpMEUZndg0",
"lYOdxSIOMjakTkGvwAJ04V8hFqt2_cs4lS0lHuaB2rw",
"k-Bu2eNbZHSBJQl3_zImDNhwgDqCQuxHJwvBZHkDjUU",
"REhnHp0JLgOVO0puWHxdAJOHxWMDlxwSj8lUz5y93eU",
"SPz6g5XRhlH03hLasFP0f-zkQOx-RBMnUzt7cWp88uo",
"GjLRV1AfgseUTBDdcO62X2HJkEqjE4LGv_S3QK10wdo",
"QjUu7f_-LbUa8K48D4KU-l7HQ8k7t-Vm1kNp2O5nelY",
"rrhw9WOCI45ym2NJaIyM3k07Bydm6JOYrajPvXQALoE",
);
  foreach ($parametro as $valor){ echo $miCodificador->decodificar($valor)."<br>"; }
 

?>
