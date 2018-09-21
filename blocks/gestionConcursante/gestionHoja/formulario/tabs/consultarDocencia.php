<?php
use gestionConcursante\gestionHoja\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarDocencia {
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
                                
		// ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------
		/**
		 * Atributos que deben ser aplicados a todos los controles de este formulario.
		 * Se utiliza un arreglo
		 * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
		 *
		 * Si se utiliza esta técnica es necesario realizar un mezcla entre este arreglo y el específico en cada control:
		 * $atributos= array_merge($atributos,$atributosGlobales);
		 */
		
		$atributosGlobales ['campoSeguro'] = 'true';
		
		$_REQUEST ['tiempo'] = time ();
		
		// -------------------------------------------------------------------------------------------------
                $conexion="estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
	//identifca lo roles para la busqueda de subsistemas
            $parametro=array('id_usuario'=>$_REQUEST['usuario']);    
            $cadena_sql = $this->miSql->getCadenaSql("consultarDocencia", $parametro);
            $resultadoListaDocencia = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            //-----BUSCA LOS TIPOS DE SOPORTES PARA EL FORMUALRIO, SEGÚN LOS RELACIONADO EN LA TABLA
            $parametroTipoSop = array('dato_relaciona'=>'datosDocencia',);
            $cadenaSalud_sql = $this->miSql->getCadenaSql("buscarTipoSoporte", $parametroTipoSop);
            $resultadoTiposop = $esteRecursoDB->ejecutarAcceso($cadenaSalud_sql, "busqueda");
            // ---------------- SECCION: Enlace para soporte -----------------------------------------------
            $variableSoporte = "pagina=gestionarSoportes"; //pendiente la pagina para modificar parametro                                                        
            $variableSoporte.= "&action=gestionarSoportes";
            $variableSoporte.= "&bloque=" . $esteBloque["id_bloque"];
            $variableSoporte.= "&bloqueGrupo=";            
            //----
            $esteCampo = "marcoListaDocencia";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>".$this->lenguaje->getCadena ( $esteCampo )."</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            
            unset ( $atributos );
                {

                echo "<div ><table width='100%' align='center'>
                        <tr align='center'>
                            <td align='center'>";
                                $esteCampo = 'nuevoDocencia';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['enlace'] = "#";//$variableNuevo;
                                $atributos ['onClick'] ="show(\"marcoDocencia\")";
                                $atributos ['tabIndex'] = 1;
                                $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                                $atributos ['estilo'] = 'textoPequenno textoGris';
                                $atributos ['enlaceImagen'] = $rutaBloque."/images/new.png";
                                $atributos ['posicionImagen'] = "atras";//"adelante";
                                $atributos ['ancho'] = '45px';
                                $atributos ['alto'] = '45px';
                                $atributos ['redirLugar'] = true;
                                echo $this->miFormulario->enlace ( $atributos );
                                unset ( $atributos );
                echo "            </td>
                        </tr>
                      </table></div> ";

                    if($resultadoListaDocencia)
                        {   //se definen cabeceras de la tabla
                            $columnas = array('Pais','Ingreso','Terminación','Nivel Programa','Vinculación','Actividades','Horas Dictadas','Institución','Tipo','Telefono','Correo');
                            foreach ($resultadoTiposop as $tipokey => $value) 
                                {array_push($columnas, $resultadoTiposop[$tipokey]['alias']);}
                            array_push($columnas, 'Editar', 'Borrar');	
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoConsultaDocencia";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
                                echo "<div class='cell-border'><table id='tablaDocencia' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center'>";
                                             foreach ($columnas AS $col)
                                                {echo " <th>$col</th>";}
                                echo "  </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoListaDocencia as $key=>$value )
                                    {   $parametro['tipo']='unico';
                                        $variableOpcion = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );                                                        
                                        $variableOpcion.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableOpcion.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableOpcion.= "&tiempo=" . time ();
                                        $variableOpcion.= "&consecutivo_docencia=".$resultadoListaDocencia[$key]['consecutivo_docencia'];
                                        $variableOpcion.= "&consecutivo_persona=".$resultadoListaDocencia[$key]['consecutivo_persona'];       
                                        
                                        $variableEditar = $variableOpcion;
                                        $variableEditar.= "&opcion=mostrar";
                                        $variableEditar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEditar, $directorio);
                                        $variableEditar.= "#tabDocencia";
                                        
                                        $variableBorrar = $variableOpcion;
                                        $variableBorrar.= "&opcion=borrar";
                                        $variableBorrar.= "&tipo=Docencia";
                                        $variableBorrar.= "&registro=".$resultadoListaDocencia[$key]['nombre_institucion'];
                                        $variableBorrar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableBorrar, $directorio);
                                        $variableBorrar.= "#tabDocencia";                                        
                                        
                                        
                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoListaDocencia[$key]['pais']."</td>
                                                <td align='left'>".$resultadoListaDocencia[$key]['fecha_inicio']."</td>
                                                <td align='left'>".$resultadoListaDocencia[$key]['fecha_fin']."</td>
                                                <td align='left'>".$resultadoListaDocencia[$key]['nivel_docencia']."</td>
                                                <td align='left'>".$resultadoListaDocencia[$key]['nombre_vinculacion']."</td>
                                                <td align='left'>".$resultadoListaDocencia[$key]['descripcion_docencia']."</td>
                                                <td align='left'>".$resultadoListaDocencia[$key]['horas_catedra']."</td>                                                                                                        
                                                <td align='left'>".$resultadoListaDocencia[$key]['nombre_institucion']."</td>
                                                <td align='left'>".$resultadoListaDocencia[$key]['nivel_institucion']."</td>
                                                <td align='left'>".$resultadoListaDocencia[$key]['telefono_institucion']."</td>
                                                <td align='left'>".$resultadoListaDocencia[$key]['correo_institucion']."</td>";
                                      
                                            // --------------- INICIO CONTROLES : Visualizar SOPORTES SEGUN LOS RELACIONADOS --------------------------------------------------
                                                foreach ($resultadoTiposop as $tipokey => $value) 
                                                    {//valida si existen soportes para el tipo
                                                    $parametroSop = array('consecutivo_persona'=>trim($resultadoListaDocencia[$key]['consecutivo_persona']),
                                                         'tipo_dato'=>$resultadoTiposop[$tipokey]['dato_relaciona'],
                                                         'nombre_soporte'=>$resultadoTiposop[$tipokey]['nombre'],
                                                         'consecutivo_dato'=>$resultadoListaDocencia[$key]['consecutivo_docencia']);
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
                                                                        //$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                                        $atributos ['deshabilitado'] = FALSE;
                                                                        $atributos ['tamanno'] = 30;
                                                                        $atributos ['anchoCaja'] = 60;
                                                                        $atributos ['maximoTamanno'] = '';
                                                                        $atributos ['anchoEtiqueta'] = 120;
                                                                        //$atributos = array_merge ( $atributos, $atributosGlobales );
                                                                        $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                                        // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                                     }  
                                                            }
                                                        $mostrarHtml .= "</td> ";               
                                                     } 
                                                // --------------- FIN CONTROLES : ver SOPORTES --------------------------------------------------  
                                        $mostrarHtml .= "<td>";
                                                    //-------------Enlace-----------------------
                                                    $esteCampo = "editar";
                                                    $atributos["id"]=$esteCampo;
                                                    $atributos['enlace']=$variableEditar;
                                                    $atributos['tabIndex']=$esteCampo;
                                                    $atributos['redirLugar']=true;
                                                    $atributos['estilo']='clasico';
                                                    $atributos['enlaceTexto']='';
                                                    $atributos['ancho']='25';
                                                    $atributos['alto']='25';
                                                    $atributos['enlaceImagen']=$rutaBloque."/images/edit.png";
                                                    $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                    unset($atributos);    
                                        $mostrarHtml .= "</td>";
                                        $mostrarHtml .= "<td>";
                                                    //-------------Enlace-----------------------
                                                    $esteCampo = "borrar";
                                                    $atributos["id"]=$esteCampo;
                                                    $atributos['enlace']=$variableBorrar;
                                                    $atributos['tabIndex']=$esteCampo;
                                                    $atributos['redirLugar']=true;
                                                    $atributos['estilo']='clasico';
                                                    $atributos['enlaceTexto']='';
                                                    $atributos['ancho']='25';
                                                    $atributos['alto']='25';
                                                    $atributos['enlaceImagen']=$rutaBloque."/images/trash.png";
                                                    $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                    unset($atributos);    
                                        $mostrarHtml .= "</td>";        
                                       $mostrarHtml .= "</tr>";
                                       echo $mostrarHtml;
                                       unset($mostrarHtml);
                                       unset($variable);
                                    }
                                echo "</tbody>";
                                echo "</table></div>";
                                //Fin de Conjunto de Controles

                        }else
                        {
                                $atributos["id"]="divNoEncontroDocencia";
                                $atributos["estilo"]="";
                           //$atributos["estiloEnLinea"]="display:none"; 
                                echo $this->miFormulario->division("inicio",$atributos);

                                //-------------Control Boton-----------------------
                                $esteCampo = "noEncontroDocencia";
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

$miSeleccionador = new consultarDocencia ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
