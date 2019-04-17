<?php
use gestionPublicacion\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarBasicos{
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
                             'tipo_dato'=>'datosBasicos');    
            $cadena_sql = $this->miSql->getCadenaSql("consultaSoportesInscripcion", $parametro);
            $resultadoListaBasicos= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $datos=json_decode ($resultadoListaBasicos[0]['valor_dato']);
            
            //-----BUSCA LOS TIPOS DE SOPORTES PARA EL FORMUALRIO, SEGÚN LOS RELACIONADO EN LA TABLA
            $parametroTipoSop = array('dato_relaciona'=>'datosBasicos',);
            $cadenaSalud_sql = $this->miSql->getCadenaSql("buscarTipoSoporte", $parametroTipoSop);
            $resultadoTiposop = $esteRecursoDB->ejecutarAcceso($cadenaSalud_sql, "busqueda");
            // ---------------- SECCION: Enlace para soporte -----------------------------------------------
            $variableSoporte = "pagina=gestionarSoportes"; //pendiente la pagina para modificar parametro                                                        
            $variableSoporte.= "&action=gestionarSoportes";
            $variableSoporte.= "&bloque=" . $esteBloque["id_bloque"];
            $variableSoporte.= "&bloqueGrupo=";
            $variableSoporte.= "&opcion=verPdf";                

                
            $esteCampo = "marcoBasicos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "".$this->lenguaje->getCadena ( $esteCampo )."";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {
                    if($datos)
                    {	$cajaNombre="width='15%'";
                        $cajaDato="width='35%'";
                        $mostrarHtml= "<div style ='width: 98%; padding-left: 2%;' class='cell-border'>";
                        $mostrarHtml.= "<table id='tablaBasicos' class='table table-striped table-bordered'>";
                        $mostrarHtml.= " <tbody>";
                        // --------------- INICIO CONTROLES : Visualizar SOPORTES SEGUN LOS RELACIONADOS --------------------------------------------------
                          $mostrarHtml.= "<tr align='center' border=1 >
                                           <td colspan=4 >";
                                $mostrarHtml.= "<table width='100%' border='0'> 
                                                    <tr>";            
                                             if(isset($datos->soportes) && $datos->soportes!='')
                                                 {foreach ($datos->soportes as $key => $value)
                                                     { $mostrarHtml.= "<td align='center' width='".(100/count($datos->soportes))."%'>";      
                                                     if(isset($value->tipo_soporte) && $value->tipo_soporte=='foto' )
                                                        {   //Se codifica la imagen
                                                            $rutaImagen= "file://".$this->rutaSoporte.$value->nombre_soporte;
                                                            $imagen = file_get_contents ( $rutaImagen );
                                                            $imagenEncriptada = base64_encode ( $imagen );
                                                            $url_foto_perfil= "data:image;base64," . $imagenEncriptada;
                                                             // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                            $esteCampo = 'archivoFoto';
                                                            $atributos ['id'] = $esteCampo;
                                                            $atributos['imagen']= $url_foto_perfil;
                                                            $atributos['estilo']='campoImagen anchoColumna2';
                                                            $atributos['etiqueta']='fotografia';
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
                                                           $atributos ['enlaceTexto'] = $value->alias_soporte;
                                                           $atributos ['estilo'] = 'textoGrande textoGris ';
                                                           $atributos ['enlaceImagen'] = $rutaBloque."/images/pdfImage.png";
                                                           $atributos ['posicionImagen'] ="atras";//"adelante";
                                                           $atributos ['ancho'] = '35px';
                                                           $atributos ['alto'] = '35px';
                                                           $atributos ['redirLugar'] = false;
                                                           $atributos ['valor'] = '';
                                                           $atributos = array_merge ( $atributos, $atributosGlobales );
                                                           $mostrarHtml.=$this->miFormulario->enlace( $atributos );
                                                           unset ( $atributos );
                                                          // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                             //-------------Inicio preparar enlace soporte-------
                                                             $verSoporte = $variableSoporte;
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
                                                           $atributos ['estilo'] = 'jqueryui';
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
                                                       $mostrarHtml.= "</td>";      
                                                     }
                                                 }
                                $mostrarHtml.= "   </tr>
                                                </table>";    
                          $mostrarHtml.= "</td>";
                        // --------------- FIN CONTROLES : ver SOPORTES --------------------------------------------------        
                          $mostrarHtml.= "</tr>";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('nombres')."</th>
                                                        <td class='table-tittle estilo_tr '  $cajaDato>".$datos->nombre."</td>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('apellidos')."</th>
                                                        <td class='table-tittle estilo_tr '  $cajaDato>".$datos->apellido."</td>
                                                </tr> ";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('pais_nacimiento')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".$datos->pais_nacimiento."</td>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('lugar_nacimiento')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".$datos->lugar_nacimiento."</td>
                                                </tr> ";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('sexo')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".str_replace("F", "Femenino", str_replace("M", "Masculino", $datos->sexo))."</td>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('fecha_nacimiento')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".$datos->fecha_nacimiento."</td>  </tr> ";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('tipo_identificacion')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".$datos->tipo_identificacion."</td>    
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('identificacion')."</th>
                                                        <td class='table-tittle estilo_tr '  $cajaDato>".$datos->identificacion."</td>
                                                </tr> ";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('fecha_identificacion')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".$datos->fecha_identificacion."</td>    
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('lugar_identificacion')."</th>
                                                        <td class='table-tittle estilo_tr '  $cajaDato>".$datos->lugar_identificacion."</td>
                                                </tr> ";                                
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('codigo_idioma_nativo')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".$datos->idioma_nativo."</td>
                                                        <th class='textoAzul' $cajaNombre> </th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato> </td>
                                                </tr> ";



                                
                        $mostrarHtml.= "</tbody>";
                        $mostrarHtml.= "</table></div>";
                        echo $mostrarHtml;
                        unset($mostrarHtml);
                    }else
                    {
                            $atributos["id"]="divNoEncontroBasicos";
                            $atributos["estilo"]="";
                       //$atributos["estiloEnLinea"]="display:none"; 
                            echo $this->miFormulario->division("inicio",$atributos);

                            //-------------Control Boton-----------------------
                            $esteCampo = "noEncontroBasicos";
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
            echo $this->miFormulario->marcoAgrupacion ( 'fin' );
            unset ( $atributos );
    }
}

$miSeleccionador = new consultarBasicos( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
