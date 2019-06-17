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
            $parametro=array('id_usuario'=>$_REQUEST['id_usuario'],
                             'tipo_dato'=>'datosFormacion');    
            $cadena_sql = $this->miSql->getCadenaSql("consultarFormacion", $parametro);
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
            $atributos ["leyenda"] = "".$this->lenguaje->getCadena ( $esteCampo )." - ".$_REQUEST['name']." ".$_REQUEST['lastname'];
            
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {

                    if($resultadoFormacion)
                        {   //define las cabeceras de la tablas
                            $columnas = array( 
                                                $this->lenguaje->getCadena ("pais_formacion"),
                                                $this->lenguaje->getCadena ("nombre_institucion"),
                                                $this->lenguaje->getCadena ("nivel_formacion"),
                                                $this->lenguaje->getCadena ("nombre_programa"),
                                                $this->lenguaje->getCadena ("modalidad_formacion"),
                                                $this->lenguaje->getCadena ("cursos_aprobados"),
                                                $this->lenguaje->getCadena ("cursos_temporalidad"),
                                                $this->lenguaje->getCadena ("promedio"),
                                                $this->lenguaje->getCadena ("graduado"),
                                                $this->lenguaje->getCadena ("fecha_grado"));
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
                                    $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoFormacion[$key]['pais']."</td>
                                                <td align='left' width='15%' >".$resultadoFormacion[$key]['nombre_institucion']."</td>
                                                <td align='left'>".$resultadoFormacion[$key]['nivel']."</td>
                                                <td align='left' width='15%' >".$resultadoFormacion[$key]['nombre_programa']."</td>
                                                <td align='left'>".$resultadoFormacion[$key]['modalidad']."</td>
                                                <td align='left'>".$resultadoFormacion[$key]['cursos_aprobados']."</td>
                                                <td align='left'>".$resultadoFormacion[$key]['periodicidad']."</td>
                                                <td align='left'>".$resultadoFormacion[$key]['promedio']."</td>
                                                <td align='left'>".str_replace("S", "SI", str_replace("N", "NO", $resultadoFormacion[$key]['graduado']))."</td>
                                                <td align='left'>".$resultadoFormacion[$key]['fecha_grado']."</td>";

                                                // --------------- INICIO CONTROLES : Visualizar SOPORTES SEGUN LOS RELACIONADOS --------------------------------------------------
                                                foreach ($resultadoTiposop as $tipokey => $value) 
                                                    {//valida si existen soportes para el tipo
                                                    $parametroSop = array('consecutivo_persona'=>trim($resultadoFormacion[$key]['consecutivo_persona']),
                                                         'tipo_dato'=>$resultadoTiposop[$tipokey]['dato_relaciona'],
                                                         'nombre_soporte'=>$resultadoTiposop[$tipokey]['nombre'],
                                                         'consecutivo_dato'=>$resultadoFormacion[$key]['consecutivo_formacion']);
                                                    $cadenaSop_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                                    $resultadoSoporte = $esteRecursoDB->ejecutarAcceso($cadenaSop_sql , "busqueda");
                                                    //se arman las celdas con los soportes existentes
                                                    $mostrarHtml .= "<td> ";
                                                    if(isset($resultadoSoporte[0]['archivo']))
                                                          {
                                                             $arrayFile = explode(",",strtolower( $resultadoTiposop[$tipokey]['extencion_permitida']));
                                                             if(isset($resultadoSoporte[0]['archivo']) && 
                                                                 (in_array(strtolower("png"), $arrayFile) || 
                                                                  in_array(strtolower("jpg"), $arrayFile) ||
                                                                  in_array(strtolower("jpeg"), $arrayFile) ||
                                                                  in_array(strtolower("bmp"), $arrayFile)))
                                                                    { //Se codifica la imagen
                                                                       $rutaImagen= "file://".$this->rutaSoporte.$resultadoSoporte[0]['ubicacion']."/".$resultadoSoporte[0]['archivo'];
                                                                       $imagen = file_get_contents ( $rutaImagen );
                                                                       $imagenEncriptada = base64_encode ( $imagen );
                                                                       $url_foto_perfil= "data:image;base64," . $imagenEncriptada;

                                                                        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                                       $esteCampo = 'archivoImagen';
                                                                       $atributos ['id'] = $esteCampo;
                                                                       $atributos['imagen']= $url_foto_perfil;
                                                                       $atributos['estilo']='campoImagen anchoColumna2';
                                                                       $atributos['etiqueta']='Imagen';
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
                                                                        $esteCampo = 'archivo'.$resultadoSoporte[0]['consecutivo_soporte'];
                                                                        $atributos ['id'] = $esteCampo;
                                                                        $atributos ['enlace'] = 'javascript:enlaceSop("ruta'.$resultadoSoporte[0]['consecutivo_soporte'].'");';
                                                                        $atributos ['tabIndex'] = 0;
                                                                        $atributos ['marco'] = true;
                                                                        $atributos ['columnas'] = 2;
                                                                        $atributos ['enlaceTexto'] = '';//$resultadoSoporte[0]['alias'];
                                                                        $atributos ['estilo'] = 'textoPequenno textoGris ';
                                                                        $atributos ['enlaceImagen'] = $rutaBloque."/images/pdfImage.png";
                                                                        $atributos ['posicionImagen'] ="atras";//"adelante";
                                                                        $atributos ['ancho'] = '25px';
                                                                        $atributos ['alto'] = '25px';
                                                                        $atributos ['redirLugar'] = false;
                                                                        $atributos ['valor'] = '';
                                                                        $atributos = array_merge ( $atributos, $atributosGlobales );
                                                                        $mostrarHtml.= $this->miFormulario->enlace( $atributos );
                                                                        unset ( $atributos );
                                                                       // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                                          //-------------Inicio preparar enlace soporte-------
                                                                          $verSoporte = $variableSoporte;
                                                                          $verSoporte .= "&opcion=verPdf";
                                                                          $verSoporte .= "&raiz=".$this->rutaSoporte;
                                                                          $verSoporte .= "&ruta=".$resultadoSoporte[0]['ubicacion'];
                                                                          $verSoporte .= "&archivo=".$resultadoSoporte[0]['archivo'];
                                                                          $verSoporte .= "&alias=".$resultadoSoporte[0]['alias'];
                                                                          $verSoporte = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $verSoporte, $directorio );
                                                                          //-------------Fin preparar enlace soporte-------
                                                                        $esteCampo = 'ruta'.$resultadoSoporte[0]['consecutivo_soporte'];
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
