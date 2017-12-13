<?php
use gestionPublicacion\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarFormacion {
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
                $this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "raizSoportes" );                  
                
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
		$atributosGlobales ['campoSeguro'] = 'true';
		$_REQUEST ['tiempo'] = time ();
		// -------------------------------------------------------------------------------------------------
                //$conexion="estructura";
            $conexion="reportes";
            $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
                //identifca lo roles para la busqueda de subsistemas
            $parametro=array('consecutivo_inscrito'=>$_REQUEST['consecutivo_inscrito'],
                             'tipo_dato'=>'datosFormacion');    
            $cadena_sql = $this->miSql->getCadenaSql("consultaSoportesInscripcion", $parametro);
            $resultadoFormacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            //-----BUSCA LOS TIPOS DE SOPORTES PARA EL FORMUALRIO, SEGÚN LOS RELACIONADO EN LA TABLA
            $parametroTipoSop = array('dato_relaciona'=>'datosFormacion',);
            $cadenaSalud_sql = $this->miSql->getCadenaSql("buscarTipoSoporte", $parametroTipoSop);
            $resultadoTiposop = $esteRecursoDB->ejecutarAcceso($cadenaSalud_sql, "busqueda");
            // ---------------- SECCION: Enlace para soporte -----------------------------------------------
            $variableSoporte = "pagina=gestionarSoportes"; //pendiente la pagina para modificar parametro                                                        
            $variableSoporte.= "&action=gestionarSoportes";
            $variableSoporte.= "&bloque=" . $esteBloque["id_bloque"];
            $variableSoporte.= "&bloqueGrupo=";

            
            $esteCampo = "marcoFormacion";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "".$this->lenguaje->getCadena ( $esteCampo )."";
            
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {

                    if($resultadoFormacion)
                        {       //define las acebceras de la tablas
                            $columnas = array('Pais','Institucion','Nivel Formacion','Programa','Modalidad','Cursos aprobados','Promedio','Graduado','Fecha grado');
                            foreach ($resultadoTiposop as $tipokey => $value) 
                                {array_push($columnas, $resultadoTiposop[$tipokey]['alias']);}
                                
                                
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoFormacion";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
                                echo "<div class='cell-border'><table id='tablaProcesos' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center'>";
                                             foreach ($columnas AS $col)
                                                {echo " <th>$col</th>";}
                                echo "  </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoFormacion as $key=>$value )
                                    { 
                                        $datos=json_decode ($resultadoFormacion[$key]['valor_dato']);	
                                     
                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$datos->pais."</td>
                                                <td align='left'>".$datos->nombre_institucion."</td>
                                                <td align='left'>".$datos->nivel."</td>
                                                <td align='left'>".$datos->nombre_programa."</td>
                                                <td align='left'>".$datos->modalidad."</td>
                                                <td align='left'>".$datos->cursos_aprobados."</td>
                                                <td align='left'>".$datos->promedio."</td>
                                                <td align='left'>".str_replace("S", "SI", str_replace("N", "NO", $datos->graduado))."</td>
                                                <td align='left'>".$datos->fecha_grado."</td>";
                                        
                                                // --------------- INICIO CONTROLES : Visualizar SOPORTES SEGUN LOS RELACIONADOS --------------------------------------------------
                                        
                                                foreach ($resultadoTiposop as $tipokey => $value) 
                                                    {//valida si existen soportes para el tipo
                                                    //se arman las celdas con los soportes existentes
                                                    $mostrarHtml .= "<td> ";
                                                            if(isset($datos->soportes) && $datos->soportes!='')
                                                              {foreach ($datos->soportes as $key => $value)
                                                                 { if(isset($value->tipo_soporte) && $value->tipo_soporte==$resultadoTiposop[$tipokey]['nombre'])
                                                                    {
                                                                    $arrayFile = explode(",",strtolower( $resultadoTiposop[$tipokey]['extencion_permitida']));     
                                                                    if(isset($value->tipo_soporte) && 
                                                                        (in_array(strtolower("png"), $arrayFile) || 
                                                                         in_array(strtolower("jpg"), $arrayFile) ||
                                                                         in_array(strtolower("jpeg"), $arrayFile) ||
                                                                         in_array(strtolower("bmp"), $arrayFile)))
                                                                        {   //Se codifica la imagen
                                                                            $rutaImagen= "file://".$this->rutaSoporte.$value->nombre_soporte;
                                                                            $imagen = file_get_contents ( $rutaImagen );
                                                                            $imagenEncriptada = base64_encode ( $imagen );
                                                                            $url_Image= "data:image;base64," . $imagenEncriptada;
                                                                             // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                                            $esteCampo = 'archivoImagen';
                                                                            $atributos ['id'] = $esteCampo;
                                                                            $atributos['imagen']= $url_Image;
                                                                            $atributos['estilo']='campoImagen anchoColumna2';
                                                                            $atributos['etiqueta']='imagen';
                                                                            $atributos['borde']='';
                                                                            $atributos ['ancho'] = '100px';
                                                                            $atributos ['alto'] = '120px';
                                                                            $atributos = array_merge ( $atributos, $atributosGlobales );
                                                                            $mostrarHtml.= $this->miFormulario->campoImagen( $atributos );
                                                                            unset ( $atributos );
                                                                          // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------  
                                                                        }
                                                                    else {      
                                                                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                                           $esteCampo = 'archivo'.$value->consecutivo_soporte;
                                                                           $atributos ['id'] = $esteCampo;
                                                                           $atributos ['enlace'] = 'javascript:enlaceSop("ruta'.$value->consecutivo_soporte.'");';
                                                                           $atributos ['tabIndex'] = 0;
                                                                           $atributos ['marco'] = true;
                                                                           $atributos ['columnas'] = 2;
                                                                           $atributos ['enlaceTexto'] = '';//$value->alias_soporte;
                                                                           $atributos ['estilo'] = 'textoPequenno textoGris ';
                                                                           $atributos ['enlaceImagen'] = $rutaBloque."/images/pdfImage.png";
                                                                           $atributos ['posicionImagen'] ="atras";//"adelante";
                                                                           $atributos ['ancho'] = '25px';
                                                                           $atributos ['alto'] = '25px';
                                                                           $atributos ['redirLugar'] = false;
                                                                           $atributos ['valor'] = '';
                                                                           $atributos = array_merge ( $atributos, $atributosGlobales );
                                                                           $mostrarHtml.=$this->miFormulario->enlace( $atributos );
                                                                           unset ( $atributos );
                                                                          // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                                             //-------------Inicio preparar enlace soporte-------
                                                                             $verSoporte = $variableSoporte;
                                                                             $verSoporte .= "&opcion=verPdf";
                                                                             $verSoporte .= "&raiz=".$this->rutaSoporte;
                                                                             $verSoporte .= "&ruta=".$value->nombre_soporte;
                                                                             $verSoporte .= "&archivo=";
                                                                             $verSoporte .= "&alias=".$value->alias_soporte;
                                                                             $verSoporte = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $verSoporte, $directorio );
                                                                             //-------------Fin preparar enlace soporte-------
                                                                           $esteCampo = 'ruta'.$value->consecutivo_soporte;
                                                                           $atributos ['id'] = $esteCampo;
                                                                           $atributos ['nombre'] = $esteCampo;
                                                                           $atributos ['tipo'] = 'hidden';
                                                                           $atributos ['estilo'] = '';//jqueryui';
                                                                           $atributos ['marco'] = true;
                                                                           $atributos ['columnas'] = 1;
                                                                           $atributos ['dobleLinea'] = false;
                                                                           $atributos ['tabIndex'] = $tab=0;
                                                                           $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                                           $atributos ['obligatorio'] = false;
                                                                           $atributos ['etiquetaObligatorio'] = false;
                                                                           $atributos ['validar'] = '';
                                                                           $atributos ['valor'] = $verSoporte;
                                                                           $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                                           $atributos ['deshabilitado'] = FALSE;
                                                                           $atributos ['tamanno'] = 30;
                                                                           $atributos ['anchoCaja'] = 60;
                                                                           $atributos ['maximoTamanno'] = '';
                                                                           $atributos ['anchoEtiqueta'] = 120;
                                                                           //$atributos = array_merge ( $atributos, $atributosGlobales );
                                                                           $mostrarHtml.= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                                           // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                                        }   

                                                                     }
                                                                }
                                                              }  
                                                        $mostrarHtml .= "</td> ";               
                                                     } 
                                                // --------------- FIN CONTROLES : ver SOPORTES --------------------------------------------------                                              
                                        
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
                                $atributos["id"]="divNoEncontroFormacion";
                                $atributos["estilo"]="";
                           //$atributos["estiloEnLinea"]="display:none"; 
                                echo $this->miFormulario->division("inicio",$atributos);

                                //-------------Control Boton-----------------------
                                $esteCampo = "noEncontroFormacion";
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

$miSeleccionador = new consultarFormacion ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
