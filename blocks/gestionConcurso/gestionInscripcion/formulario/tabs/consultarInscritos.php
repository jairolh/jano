<?php
use gestionConcurso\gestionInscripcion\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarInscrito {
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
                $this->rutaSoporte = $this->miConfigurador->getVariableConfiguracion ( "raizSoportes" );
                
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
	
            $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso']);    
            $cadena_sql = $this->miSql->getCadenaSql("consultarInscritoConcurso", $parametro);
            $resultadoListaInscrito = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            //var_dump($resultadoListaInscrito);
            $esteCampo = "marcoListaInscrito";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>".$this->lenguaje->getCadena ( $esteCampo )."</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            
            unset ( $atributos );
                {
                   if($resultadoListaInscrito)
                        {	
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoConsultaInscrito";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
                                echo "<div class='cell-border'><table id='tablaConsultaInscrito' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center'>
                                            <th>Dependencia</th>                                            
                                            <th>Área</th> 
                                            <th>Código</th>
                                            <th>Perfil</th>
                                            <th>Tipo Identificación</th>
                                            <th>Identificación</th>
                                            <th>Nombre</th>
                                            <th>Inscripción</th>
                                            <th>Fecha</th>
                                            <th>Datos Inscripción</th>
                                            <th>Evaluaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoListaInscrito as $key=>$value )
                                    {   $parametro['tipo']='unico';
                                        /*
                                        $parametroSop = array('consecutivo'=>$resultadoListaInscrito[$key]['consecutivo_persona'],
                                             'tipo_dato'=>'datosInscrito',
                                             'nombre_soporte'=>'soporteInscrito',
                                             'consecutivo_dato'=>$resultadoListaInscrito[$key]['consecutivo_actividad']
                                            );
                                        $cadenaSop_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                        $resultadoSact = $esteRecursoDB->ejecutarAcceso($cadenaSop_sql, "busqueda");*/
                                        $variableVer = "pagina=publicacion";                                                        
                                        $variableVer.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableVer.= "&id_usuario=" .$_REQUEST['usuario'];
                                        $variableVer.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableVer.= "&tiempo=" . time ();
                                        $variableVer.= "&consecutivo_inscrito=".$resultadoListaInscrito[$key]['consecutivo_inscrito'];
                                        $variableVer.= "&consecutivo_concurso=".$resultadoListaInscrito[$key]['consecutivo_concurso'];
                                        $variableVer.= "&consecutivo_perfil=".$resultadoListaInscrito[$key]['consecutivo_perfil'];       
                                        //$variableVerHoja.= "#tabInscrito";
                                        
                                        
                                        $variableVerHoja = $variableVer;                                                        
                                        $variableVerHoja.= "&opcion=hojaVida";
                                        $variableVerHoja = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVerHoja, $directorio);
                                        
                                        $variableVerEval = $variableVer;                                                        
                                        $variableVerEval.= "&opcion=evaluacion";
                                        $variableVerEval = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVerEval, $directorio);
                                        
                                        //enlace actualizar estado
                                        $variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' ); 
                                        if($resultadoListaInscrito[$key]['estado']=='A')
                                            {$variableEstado.= "&opcion=inhabilitarInscrito";}
                                        else{$variableEstado.= "&opcion=habilitarInscrito";}    
                                        $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEstado.= "&consecutivo_concurso=".$resultadoListaInscrito[$key]['consecutivo_concurso'];
                                        $variableEstado.= "&consecutivo_perfil=".$resultadoListaInscrito[$key]['consecutivo_perfil'];       
                                        $variableEstado.= "&nombre=" .$resultadoListaInscrito[$key]['nombre'];
                                        $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEstado.= "&tiempo=" . time ();
                                        $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);
                                        
                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoListaInscrito[$key]['dependencia']."</td>    
                                                <td align='left'>".$resultadoListaInscrito[$key]['area']."</td>    
                                                <td align='left'>".$resultadoListaInscrito[$key]['codigo']."</td>  
                                                <td align='left'>".$resultadoListaInscrito[$key]['perfil']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['tipo_identificacion']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['identificacion']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['nombre']." ".$resultadoListaInscrito[$key]['apellido']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['consecutivo_inscrito']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['fecha_registro']."</td>";
                                        $mostrarHtml .= "<td>";
                                            if($resultadoListaInscrito[$key]['soporte']>0)
                                                { 
                                                //-------------Enlace-----------------------
                                                    $esteCampo = "verHojaVida";
                                                          $esteCampo = 'enlace_hoja'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:enlace("ruta_enlace_hoja'.$key.'");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['columnas'] = 1;
                                                          $atributos ['enlaceTexto'] = '';
                                                          $atributos ['estilo'] = 'clasico';
                                                          $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";
                                                          $atributos ['posicionImagen'] ="atras";//"adelante";
                                                          $atributos ['ancho'] = '25px';
                                                          $atributos ['alto'] = '25px';
                                                          $atributos ['redirLugar'] = false;
                                                          $atributos ['valor'] = '';
                                                          $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                                                          unset ( $atributos );
                                                           // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                          $esteCampo = 'ruta_enlace_hoja'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['nombre'] = $esteCampo;
                                                          $atributos ['tipo'] = 'hidden';
                                                          $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                          $atributos ['obligatorio'] = false;
                                                          $atributos ['valor'] = $variableVerHoja;
                                                          $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                          $atributos ['deshabilitado'] = FALSE;
                                                          $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                          // --------------- FIN CONTROL : Cuadro de Texto -------------------------------------------------- 
                                                }
                                         $mostrarHtml .= "</td> <td>";
                                             if($resultadoListaInscrito[$key]['soporte']>0)
                                                { 
                                                //-------------Enlace-----------------------
                                                    $esteCampo = "verHojaVida";
                                                          $esteCampo = 'enlace_hoja'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:enlace("ruta_enlace_resultado'.$key.'");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['columnas'] = 1;
                                                          $atributos ['enlaceTexto'] = '';
                                                          $atributos ['estilo'] = 'clasico';
                                                          $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";
                                                          $atributos ['posicionImagen'] ="atras";//"adelante";
                                                          $atributos ['ancho'] = '25px';
                                                          $atributos ['alto'] = '25px';
                                                          $atributos ['redirLugar'] = false;
                                                          $atributos ['valor'] = '';
                                                          $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                                                          unset ( $atributos );
                                                           // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                          $esteCampo = 'ruta_enlace_resultado'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['nombre'] = $esteCampo;
                                                          $atributos ['tipo'] = 'hidden';
                                                          $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                          $atributos ['obligatorio'] = false;
                                                          $atributos ['valor'] = $variableVerEval;
                                                          $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                          $atributos ['deshabilitado'] = FALSE;
                                                          $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                          // --------------- FIN CONTROL : Cuadro de Texto -------------------------------------------------- 
                                                }
                                         $mostrarHtml .= "</td>";
                                         
                                         /*
                                        if($resultadoListaInscrito[$key]['estado']==0)
                                            { 
                                                if($resultadoListaInscrito[$key]['estado']=='A')
                                                    {   $esteCampo = "habilitar";
                                                        $atributos["id"]=$esteCampo;
                                                        $atributos['enlace']=$variableEstado;
                                                        $atributos['tabIndex']=$esteCampo;
                                                        $atributos['redirLugar']=true;
                                                        $atributos['estilo']='clasico';
                                                        $atributos['enlaceTexto']='';
                                                        $atributos['ancho']='25';
                                                        $atributos['alto']='25';
                                                        $atributos['enlaceImagen']=$rutaBloque."/images/player_pause.png";
                                                        $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                        unset($atributos);    
                                                    }
                                                else{
                                                            //-------------Enlace-----------------------
                                                        $esteCampo = "habilitar";
                                                        $atributos["id"]=$esteCampo;
                                                        $atributos['enlace']=$variableEstado;
                                                        $atributos['tabIndex']=$esteCampo;
                                                        $atributos['redirLugar']=true;
                                                        $atributos['estilo']='clasico';
                                                        $atributos['enlaceTexto']='';
                                                        $atributos['ancho']='25';
                                                        $atributos['alto']='25';
                                                        $atributos['enlaceImagen']=$rutaBloque."/images/success.png";
                                                        $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                        unset($atributos);    
                                                    }
                                            }   */ 
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
                                $atributos["id"]="divNoEncontroInscrito";
                                $atributos["estilo"]="";
                           //$atributos["estiloEnLinea"]="display:none"; 
                                echo $this->miFormulario->division("inicio",$atributos);

                                //-------------Control Boton-----------------------
                                $esteCampo = "noEncontroInscrito";
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

$miSeleccionador = new consultarInscrito ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
