<?php
use gestionConcursante\gestionHoja\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarPerfil {
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
            $hoy=date("Y-m-d");   
            $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso']);    
            $cadena_sql = $this->miSql->getCadenaSql("consultarPerfilConcurso", $parametro);
            $resultadoListaPerfil = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $esteCampo = "marcoListaPerfil";
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
                                $esteCampo = 'nuevoPerfil';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['enlace'] = "#";//$variableNuevo;
                                $atributos ['onClick'] ="show(\"marcoPerfil\")";
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

                    if($resultadoListaPerfil)
                        {	
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoConsultaPerfil";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
                                echo "<div class='cell-border'><table id='tablaConsultaPerfil' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center'>
                                            <th>Dependencia</th>                                            
                                            <th>Área</th> 
                                            <th>Código</th>                                            
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Requisitos</th>
                                            <th>Vacantes</th>                                            
                                            <th>Estado</th>
                                            <th>Editar</th>
                                            <th>Actualizar Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoListaPerfil as $key=>$value )
                                    {   $parametro['tipo']='unico';
                                        /*
                                        $parametroSop = array('consecutivo'=>$resultadoListaPerfil[$key]['consecutivo_persona'],
                                             'tipo_dato'=>'datosPerfil',
                                             'nombre_soporte'=>'soportePerfil',
                                             'consecutivo_dato'=>$resultadoListaPerfil[$key]['consecutivo_actividad']
                                            );
                                        $cadenaSop_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                        $resultadoSact = $esteRecursoDB->ejecutarAcceso($cadenaSop_sql, "busqueda");*/
                                        $variableEditar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );                                                        
                                        $variableEditar.= "&opcion=detalle";
                                        $variableEditar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEditar.= "&id_usuario=" .$_REQUEST['usuario'];
                                        $variableEditar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEditar.= "&tiempo=" . time ();
                                        $variableEditar .= "&consecutivo_concurso=".$resultadoListaPerfil[$key]['consecutivo_concurso'];
                                        $variableEditar .= "&consecutivo_perfil=".$resultadoListaPerfil[$key]['consecutivo_perfil'];       
                                        $variableEditar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEditar, $directorio);
                                        $variableEditar.= "#tabPerfil";
                                        
                                        //enlace actualizar estado
                                        $variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' ); 
                                        if($resultadoListaPerfil[$key]['estado']=='A')
                                            {$variableEstado.= "&opcion=inhabilitarPerfil";}
                                        else{$variableEstado.= "&opcion=habilitarPerfil";}    
                                        $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEstado.= "&consecutivo_concurso=".$resultadoListaPerfil[$key]['consecutivo_concurso'];
                                        $variableEstado.= "&consecutivo_perfil=".$resultadoListaPerfil[$key]['consecutivo_perfil'];       
                                        $variableEstado.= "&nombre=" .$resultadoListaPerfil[$key]['nombre'];
                                        $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEstado.= "&tiempo=" . time ();
                                        $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);
                                        
                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoListaPerfil[$key]['dependencia']."</td>    
                                                <td align='left'>".$resultadoListaPerfil[$key]['area']."</td>    
                                                <td align='left'>".$resultadoListaPerfil[$key]['codigo']."</td>    
                                                <td align='left'>".$resultadoListaPerfil[$key]['nombre']."</td>
                                                <td align='left'>".$resultadoListaPerfil[$key]['descripcion']."</td>
                                                <td align='left'>".$resultadoListaPerfil[$key]['requisitos']."</td>
                                                <td align='left'>".$resultadoListaPerfil[$key]['vacantes']."</td>    
                                                <td align='left'>".$resultadoListaPerfil[$key]['nom_estado']."</td>";
                                        $mostrarHtml .= "<td>";
                                            if($resultadoListaPerfil[$key]['inscritos']==0)
                                                { 
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
                                                }
                                         $mostrarHtml .= "</td> <td>";
                                        if($resultadoListaPerfil[$key]['inscritos']==0)
                                            { 
                                                if($resultadoListaPerfil[$key]['estado']=='A')
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

$miSeleccionador = new consultarPerfil ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
