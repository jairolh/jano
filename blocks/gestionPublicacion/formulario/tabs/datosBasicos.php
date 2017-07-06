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
                             'tipo_dato'=>'datosBasicos');    
            $cadena_sql = $this->miSql->getCadenaSql("consultaSoportesInscripcion", $parametro);
            $resultadoListaBasicos= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $datos=json_decode ($resultadoListaBasicos[0]['valor_dato']);
            foreach ($resultadoListaBasicos as $key => $value) {
                
                if($resultadoListaBasicos[$key]['consecutivo_soporte']>0 && $resultadoListaBasicos[$key]['nombre_tipo']=='foto' ){
                    $foto=array('ruta'=> $this->rutaSoporte.$resultadoListaBasicos[$key]['nombre_soporte'],
                                'alias'=> $resultadoListaBasicos[$key]['alias_soporte'],);
                    }
                if($resultadoListaBasicos[$key]['consecutivo_soporte']>0 && $resultadoListaBasicos[$key]['nombre_tipo']=='soporteIdentificacion' ){
                    $identificacion=array('ruta'=> $this->rutaSoporte.$resultadoListaBasicos[$key]['nombre_soporte'],
                                'alias'=> $resultadoListaBasicos[$key]['alias_soporte'],);
                    }
                
                
            }
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
                        $mostrarHtml= "<div style ='width: 95%; padding-left: 5%;' class='cell-border'>";
                        $mostrarHtml.= "<table id='tablaBasicos' class='table table-striped table-bordered'>";
                        $mostrarHtml.= " <tbody>";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('nombres')."</th>
                                                        <td colspan=2 class='table-tittle estilo_tr '>".$datos->nombre."</td>
                                                        <td rowspan=2 align='center'>";
                                                        if(isset($foto))
                                                            {
                                                               // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                              $esteCampo = 'archivoFoto';
                                                              $atributos ['id'] = $esteCampo;
                                                              $atributos['imagen']= $foto['ruta'];
                                                              $atributos['estilo']='campoImagen';
                                                              $atributos['etiqueta']='fotografia';
                                                              $atributos['borde']='';
                                                              $atributos ['ancho'] = '100px';
                                                              $atributos ['alto'] = '120px';
                                                              $atributos = array_merge ( $atributos, $atributosGlobales );
                                                              $mostrarHtml.= $this->miFormulario->campoImagen( $atributos );
                                                              unset ( $atributos );
                                                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------  
                                                          }
                                $mostrarHtml.=      "</td>
                                                </tr> ";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('apellidos')."</th>
                                                        <td colspan=2 class='table-tittle estilo_tr '>".$datos->apellido."</td>
                                                </tr> ";
                                
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('tipo_identificacion')."</th>
                                                        <td colspan=2 class='table-tittle estilo_tr '>".$datos->tipo_identificacion."</td>
                                                        <td rowspan=2 align='center'>";
                                                        if(isset($identificacion))
                                                            {
                                                                   // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                                                                  $esteCampo = 'archivoIdentificacion';
                                                                  $atributos ['id'] = $esteCampo;
                                                                  $atributos ['enlace'] = 'javascript:soporte("ruta_Ident");';
                                                                  $atributos ['tabIndex'] = 0;
                                                                  $atributos ['marco'] = true;
                                                                  $atributos ['columnas'] = 1;
                                                                  $atributos ['enlaceTexto'] = $identificacion['alias'];
                                                                  $atributos ['estilo'] = 'textoMediano textoGris ';
                                                                  $atributos ['enlaceImagen'] = $rutaBloque."/images/pdfImage.png";
                                                                  $atributos ['posicionImagen'] ="atras";//"adelante";
                                                                  $atributos ['ancho'] = '30px';
                                                                  $atributos ['alto'] = '30px';
                                                                  $atributos ['redirLugar'] = false;
                                                                  $atributos ['valor'] = $datos->identificacion;
                                                                  $atributos = array_merge ( $atributos, $atributosGlobales );
                                                                  $mostrarHtml.= $this->miFormulario->enlace( $atributos );
                                                                  unset ( $atributos );
                                                                 // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                                  $esteCampo = 'ruta_Ident';
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
                                                                  $atributos ['validar'] = 'minSize[1]';
                                                                  $atributos ['valor'] = $identificacion['ruta'];
                                                                  $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                                  $atributos ['deshabilitado'] = FALSE;
                                                                  $atributos ['tamanno'] = 30;
                                                                  $atributos ['anchoCaja'] = 60;
                                                                  $atributos ['maximoTamanno'] = '';
                                                                  $atributos ['anchoEtiqueta'] = 170;
                                                                  $tab ++;
                                                                  // Aplica atributos globales al control
                                                                  //$atributos = array_merge ( $atributos, $atributosGlobales );
                                                                  $mostrarHtml.= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                                  // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                                                          }
                                $mostrarHtml.=      "</td>
                                                </tr> ";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('identificacion')."</th>
                                                        <td colspan=2 class='table-tittle estilo_tr '>".$datos->identificacion."</td>
                                                </tr> ";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('sexo')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".$datos->sexo."</td>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('fecha_nacimiento')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".$datos->fecha_nacimiento."</td>  </tr> ";
                                $mostrarHtml.= "<tr align='center'>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('pais_nacimiento')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".$datos->pais_nacimiento."</td>
                                                        <th class='textoAzul' $cajaNombre>".$this->lenguaje->getCadena ('lugar_nacimiento')."</th>
                                                        <td class='table-tittle estilo_tr ' $cajaDato>".$datos->lugar_nacimiento."</td>  </tr> ";
                                
                        $mostrarHtml.= "</tbody>";
                        $mostrarHtml.= "</table></div>";
                        echo $mostrarHtml;
                        unset($mostrarHtml);
                    }else
                    {
                            $atributos["id"]="divNoEncontroPerfil";
                            $atributos["estilo"]="";
                       //$atributos["estiloEnLinea"]="display:none"; 
                            echo $this->miFormulario->division("inicio",$atributos);

                            //-------------Control Boton-----------------------
                            $esteCampo = "noEncontroPerfil";
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
