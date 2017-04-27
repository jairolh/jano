<?php
use gestionConcursante\gestionHoja\funcion\redireccion;

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
            $cadena_sql = $this->miSql->getCadenaSql("consultarIdiomas", $parametro);
            $resultadoListaIdioma = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $esteCampo = "marcoListaIdioma";
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
                                $esteCampo = 'nuevoIdioma';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['enlace'] = "#";//$variableNuevo;
                                $atributos ['onClick'] ="show(\"marcoIdioma\")";
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

                    if($resultadoListaIdioma)
                        {	
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoConsultaIdioma";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
                                echo "<div class='cell-border'><table id='tablaIdioma' class='table table-striped table-bordered'>";
                              /*
                                echo "<thead>
                                        <tr align='center'>
                                            <th>Idioma </th>
                                            <th>Nivel Lectura</th>                                            
                                            <th>Nivel Escritura</th>                                            
                                            <th>Nivel Dialogo</th>
                                            <th>Certificación</th>
                                            <th>Institución certifica</th>
                                            <th>Soporte</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>";*/
                                echo "<thead>
                                        <tr align='center'>
                                            <th>Idioma </th>
                                            <th>Certificación</th>
                                            <th>Institución certifica</th>
                                            <th>Soporte</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                
                                foreach($resultadoListaIdioma as $key=>$value )
                                    {   
                                        $parametroSop = array('consecutivo'=>$resultadoListaIdioma[$key]['consecutivo_persona'],
                                             'tipo_dato'=>'datosIdioma',
                                             'nombre_soporte'=>'soporteIdioma',
                                             'consecutivo_dato'=>$resultadoListaIdioma[$key]['consecutivo_conocimiento']
                                            );
                                        $cadenaSop_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                        $resultadoSidm = $esteRecursoDB->ejecutarAcceso($cadenaSop_sql, "busqueda");
                                        $variableEditar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );                                                        
                                        $variableEditar.= "&opcion=mostrar";
                                        $variableEditar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEditar.= "&id_usuario=" .$_REQUEST['usuario'];
                                        $variableEditar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEditar.= "&tiempo=" . time ();
                                        $variableEditar .= "&consecutivo_conocimiento=".$resultadoListaIdioma[$key]['consecutivo_conocimiento'];
                                        $variableEditar .= "&consecutivo_persona=".$resultadoListaIdioma[$key]['consecutivo_persona'];       
                                        $variableEditar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEditar, $directorio);
                                        $variableEditar.= "#tabIdiomas";
                                        
                                        /*$mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoListaIdioma[$key]['idioma']."</td>
                                                <td align='left'>".$resultadoListaIdioma[$key]['nombre_nivel_lee']."</td>
                                                <td align='left'>".$resultadoListaIdioma[$key]['nombre_nivel_escribe']."</td>
                                                <td align='left'>".$resultadoListaIdioma[$key]['nombre_nivel_habla']."</td>
                                                <td align='left'>".$resultadoListaIdioma[$key]['certificacion']."</td>
                                                <td align='left'>".$resultadoListaIdioma[$key]['institucion_certificacion']."</td>";*/
                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoListaIdioma[$key]['idioma']."</td>
                                                <td align='left'>".$resultadoListaIdioma[$key]['certificacion']."</td>
                                                <td align='left'>".$resultadoListaIdioma[$key]['institucion_certificacion']."</td>";
                                      
                                        $mostrarHtml .= "<td>";
                                                    if(isset($resultadoSidm[0]['alias']))
                                                        {
                                                          $esteCampo = 'archivoactividad'.$resultadoSidm[0]['consecutivo_soporte'];
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:soporte("ruta_idioma'.$resultadoSidm[0]['consecutivo_soporte'].'");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['columnas'] = 2;
                                                          $atributos ['enlaceTexto'] = $resultadoSidm[0]['alias'];
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
                                                          $esteCampo = 'ruta_idioma'.$resultadoSidm[0]['consecutivo_soporte'];
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['nombre'] = $esteCampo;
                                                          $atributos ['tipo'] = 'hidden';
                                                          $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                          $atributos ['obligatorio'] = false;
                                                          $atributos ['valor'] = $this->rutaSoporte.$resultadoSidm[0]['ubicacion']."/".$resultadoSidm[0]['archivo'];
                                                          $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                          $atributos ['deshabilitado'] = FALSE;
                                                          $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                          // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                        }
                                        $mostrarHtml .= "</td>
                                                        <td>";
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
            echo $this->miFormulario->marcoAgrupacion ( 'fin' );
            unset ( $atributos );
    }
}

$miSeleccionador = new consultarIdioma ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
