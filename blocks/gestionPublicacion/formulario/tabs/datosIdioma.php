<?php
use gestionPublicacion\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarIdioma {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
        var $miSesion;
        var $rutaSoporte;   
	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miFormulario = $formulario;
		$this->miSql = $sql;
                $this->miSesion = \Sesion::singleton();
                
	}
	function miForm() {
		
            // Rescatar los datos de este bloque
            $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
            $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
            $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
            $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
            $directorio = $this->miConfigurador->getVariableConfiguracion("host");
            $directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
            $directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
            $this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "host" ) .$this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";                
            $atributosGlobales ['campoSeguro'] = 'true';
            $_REQUEST ['tiempo'] = time ();
            // -------------------------------------------------------------------------------------------------
            $conexion="estructura";
            $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
                //identifca lo roles para la busqueda de subsistemas
            $parametro=array('consecutivo_inscrito'=>$_REQUEST['consecutivo_inscrito'],
                             'tipo_dato'=>'datosIdioma');    
            $cadena_sql = $this->miSql->getCadenaSql("consultaSoportesInscripcion", $parametro);
            $resultadoIdioma = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

            $esteCampo = "marcoIdioma";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "".$this->lenguaje->getCadena ( $esteCampo )."";
            
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {
                    if($resultadoIdioma)
                        {     
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoIdioma";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
                                echo "<div class='cell-border'><table id='tablaProcesos' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center' class='textoAzul'>
                                            <th>Idioma </th>
                                            <th>Certificación</th>
                                            <th>Institución certifica</th>
                                            <th>Soporte</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoIdioma as $key=>$value )
                                    {   $datos=json_decode ($resultadoIdioma[$key]['valor_dato']);	
                                        if(isset($datos->soportes) && $datos->soportes!='')
                                            {
                                            foreach ($datos->soportes as  $value) {
                                                if(isset($value->tipo_soporte) && $value->tipo_soporte=='soporteIdioma' ){
                                                  $resultadoSop=array('ruta'=> $this->rutaSoporte.$value->nombre_soporte, 'alias'=> $value->alias_soporte,'consecutivo_soporte'=> $value->consecutivo_soporte,);
                                                  }
                                              }
                                            }
                                     
                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$datos->idioma."</td>
                                                <td align='left'>".$datos->certificacion."</td>
                                                <td align='left'>".$datos->institucion_certificacion."</td>";
                                        
                                        $mostrarHtml .= "<td> ";
                                                if(isset($resultadoSop['alias']))
                                                    {
                                                      $esteCampo = 'archivoSoporte'.$resultadoSop['consecutivo_soporte'];
                                                      $atributos ['id'] = $esteCampo;
                                                      $atributos ['enlace'] = 'javascript:soporte("ruta_diploma'.$resultadoSop['consecutivo_soporte'].'");';
                                                      $atributos ['tabIndex'] = 0;
                                                      $atributos ['columnas'] = 1;
                                                      $atributos ['enlaceTexto'] = $resultadoSop['alias'];
                                                      $atributos ['estilo'] = 'clasico';
                                                      $atributos ['enlaceImagen'] = $rutaBloque."/images/pdfImage.png";
                                                      $atributos ['posicionImagen'] ="atras";//"adelante";
                                                      $atributos ['ancho'] = '25px';
                                                      $atributos ['alto'] = '25px';
                                                      $atributos ['redirLugar'] = false;
                                                      $atributos ['valor'] = '';
                                                      $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                                                      unset ( $atributos );
                                                       // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                      $esteCampo = 'ruta_diploma'.$resultadoSop['consecutivo_soporte'];
                                                      $atributos ['id'] = $esteCampo;
                                                      $atributos ['nombre'] = $esteCampo;
                                                      $atributos ['tipo'] = 'hidden';
                                                      $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                      $atributos ['obligatorio'] = false;
                                                      $atributos ['valor'] = $resultadoSop['ruta'];
                                                      $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                      $atributos ['deshabilitado'] = FALSE;
                                                      $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                      // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                    }                                        
                                       $mostrarHtml .= "</td>";
                                       $mostrarHtml .= "</tr>";
                                       echo $mostrarHtml;
                                       unset($mostrarHtml);
                                       unset($variable);
                                       unset($resultadoDip);
                                       unset($resultadoTarjeta);
                                    }
                                echo "</tbody>";
                                echo "</table></div>";
                                //Fin de Conjunto de Controles

                        }else
                        {
                                $atributos["id"]="divNoEncontroIdioma";
                                $atributos["estilo"]="";
                           //$atributos["estiloEnLinea"]="display:none"; 
                                echo $this->miFormulario->division("inicio",$atributos);

                                //-------------Control Boton-----------------------
                                $esteCampo = "noEncontroIdioma";
                                $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                                $atributos["etiqueta"] = "";
                                $atributos["estilo"] = "centrar";
                                $atributos["tipo"] = 'error';
                                $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
                                echo $this->miFormulario->cuadroMensaje($atributos);
                                unset($atributos); 
                                //-------------Fin Control Boton----------------------

                               echo $this->miFormulario->division("fin");
                                //------------------Division para los botones-------------------------
                        }
                }
            echo $this->miFormulario->marcoAgrupacion ( 'fin');
            unset ( $atributos );
    }
}

$miSeleccionador = new consultarIdioma ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
