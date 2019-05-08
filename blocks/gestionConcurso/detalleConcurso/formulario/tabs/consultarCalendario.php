<?php
use gestionConcursante\gestionHoja\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarCalendario {
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
            $cadena_sql = $this->miSql->getCadenaSql("consultarCalendarioConcurso", $parametro);
            $resultadoListaCalendario = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $esteCampo = "marcoListaCalendario";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>".$this->lenguaje->getCadena ( $esteCampo )."</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            $hoy=date("Y-m-d");
            unset ( $atributos );
                {

                echo "<div ><table width='100%' align='center'>
                        <tr align='center'>
                            <td align='center'>";
                                $esteCampo = 'nuevoCalendario';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['enlace'] = "#";//$variableNuevo;
                                $atributos ['onClick'] ="show(\"marcoCalendario\")";
                                $atributos ['tabIndex'] = 1;
                                $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                                $atributos ['estilo'] = 'textoPequenno textoGris';
                                $atributos ['enlaceImagen'] = $rutaBloque."/images/new.png";
                                $atributos ['posicionImagen'] = "atras";//"adelante";
                                $atributos ['ancho'] = '45px';
                                $atributos ['alto'] = '45px';
                                $atributos ['redirLugar'] = true;
                                if($hoy<$_REQUEST['inicio_concurso'])
                                    {echo $this->miFormulario->enlace ( $atributos );}
                                unset ( $atributos );
                echo "    </td>
                        </tr>
                      </table></div> ";

                    if($resultadoListaCalendario)
                        {	
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoConsultaCalendario";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
                                echo "<div class='cell-border'><table id='tablaConsultaCalendario' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center'>
                                            <th>Fecha inicial</th>                                            
                                            <th>Fecha cierre</th> 
                                            <th>Cierre Reclamaciones</th> 
                                            <th>Resolver Reclamaciones</th> 
                                            <th>Fase</th>
                                            <th>Descripción</th>
                                            <th>Puntos aprueba</th>                                            
                                            <th>Estado</th>
                                            <th>Editar</th>
                                            <th>Actualizar Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoListaCalendario as $key=>$value )
                                    {   /*$parametro['tipo']='unico';
                                        $parametroSop = array('consecutivo'=>$resultadoListaCalendario[$key]['consecutivo_persona'],
                                             'tipo_dato'=>'datosCalendario',
                                             'nombre_soporte'=>'soporteCalendario',
                                             'consecutivo_dato'=>$resultadoListaCalendario[$key]['consecutivo_actividad']
                                            );
                                        $cadenaSop_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                        $resultadoSact = $esteRecursoDB->ejecutarAcceso($cadenaSop_sql, "busqueda");*/
                                        $variableEditar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );                                                        
                                        $variableEditar.= "&opcion=detalle";
                                        $variableEditar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEditar.= "&id_usuario=" .$_REQUEST['usuario'];
                                        $variableEditar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEditar.= "&tiempo=" . time ();
                                        $variableEditar .= "&consecutivo_concurso=".$resultadoListaCalendario[$key]['consecutivo_concurso'];
                                        $variableEditar .= "&consecutivo_calendario=".$resultadoListaCalendario[$key]['consecutivo_calendario'];       
                                        $variableEditar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEditar, $directorio);
                                        $variableEditar.= "#tabCalendario";
                                        
                                        //enlace actualizar estado
                                        $variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' ); 
                                        if($resultadoListaCalendario[$key]['estado']=='A')
                                            {$variableEstado.= "&opcion=inhabilitarCalendario";}
                                        else{$variableEstado.= "&opcion=habilitarCalendario";}    
                                        $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEstado.= "&consecutivo_concurso=".$resultadoListaCalendario[$key]['consecutivo_concurso'];
                                        $variableEstado.= "&consecutivo_calendario=".$resultadoListaCalendario[$key]['consecutivo_calendario'];       
                                        $variableEstado.= "&nombre=" .$resultadoListaCalendario[$key]['nombre'];
                                        $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEstado.= "&tiempo=" . time ();
                                        $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);
                                        
                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoListaCalendario[$key]['fecha_inicio']."</td>    
                                                <td align='left'>".$resultadoListaCalendario[$key]['fecha_fin']."</td>    
                                                <td align='left'>".$resultadoListaCalendario[$key]['fecha_fin_reclamacion']."</td>        
                                                <td align='left'>".$resultadoListaCalendario[$key]['fecha_fin_resolver']."</td>        
                                                <td align='left'>".$resultadoListaCalendario[$key]['nombre']."</td>
                                                <td align='left'>".$resultadoListaCalendario[$key]['descripcion']."</td>
                                                <td align='left'>".$resultadoListaCalendario[$key]['porcentaje_aprueba']." %</td>                                                    
                                                <td align='left'>".$resultadoListaCalendario[$key]['nom_estado']."</td>";
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
                                         $mostrarHtml .= "</td> <td>";
                                        if($resultadoListaCalendario[$key]['obligatoria']=='N' && $hoy<$_REQUEST['inicio_concurso'])
                                            { 
                                                if($resultadoListaCalendario[$key]['estado']=='A')
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
                                            }    
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
                                $atributos["id"]="divNoEncontroCalendario";
                                $atributos["estilo"]="";
                           //$atributos["estiloEnLinea"]="display:none"; 
                                echo $this->miFormulario->division("inicio",$atributos);

                                //-------------Control Boton-----------------------
                                $esteCampo = "noEncontroCalendario";
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

$miSeleccionador = new consultarCalendario ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
