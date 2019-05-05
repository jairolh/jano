<?php
namespace gestionConcurso\caracterizaConcurso;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

class consultarForm {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
    var $miSesion;
    
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
	
		$valorCodificado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		$valorCodificado .= "&opcion=nuevaActividad";
        $valorCodificado .= "&usuario=" . $this->miSesion->getSesionUsuarioId();
		
		/**
		 * SARA permite que los nombres de los campos sean dinámicos.
		 * Para ello utiliza la hora en que es creado el formulario para
		 * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
		 * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
		 * (b) asociando el tiempo en que se está creando el formulario
		 */
                
		$valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
		$valorCodificado .= "&tiempo=" . time ();
		// Paso 2: codificar la cadena resultante
        $variableNuevo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($valorCodificado, $directorio);
		
        $cadena_sql = $this->miSql->getCadenaSql("consultaActividades", "");
        $resultadoActividades = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            //var_dump($resultadoActividades);
            $esteCampo = "marcoDatosBasicos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>Gestión Fases para Concursos</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {

                echo "<div ><table width='100%' align='center'>
                        <tr align='center'>
                            <td align='center'>";
                                $esteCampo = 'nuevaActividad';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['enlace'] = $variableNuevo;
                                $atributos ['tabIndex'] = 1;
                                $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                                $atributos ['estilo'] = 'textoPequenno textoGris';
                                $atributos ['enlaceImagen'] = $rutaBloque."/images/new.png";
                                $atributos ['posicionImagen'] = "atras";//"adelante";
                                $atributos ['ancho'] = '45px';
                                $atributos ['alto'] = '45px';
                                $atributos ['redirLugar'] = true;
                                //echo $this->miFormulario->enlace ( $atributos );
                                unset ( $atributos );
                echo "            </td>
                        </tr>
                      </table></div> ";

                if($resultadoActividades)
                {	
                    //-----------------Inicio de Conjunto de Controles----------------------------------------
                        $esteCampo = "marcoConsultaPerfiles";
                        $atributos["estilo"] = "jqueryui";
                        $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                        //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                        unset($atributos);

                        echo "<div class='cell-border'><table id='tablaProcesos' class='table table-striped table-bordered'>";

                        echo "<thead>
                                <tr align='center'>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Editar</th>
                                    <th>Actualizar Estado</th>
                                </tr>
                            </thead>
                            <tbody>";

                        foreach($resultadoActividades as $key=>$value )
                            { 
                            	$variableEditar = "pagina=". $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                            	$variableEditar.= "&opcion=editarActividad";
                            	$variableEditar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                            	$variableEditar.= "&id_actividad=" .$resultadoActividades[$key]['consecutivo_actividad'];
                            	$variableEditar.= "&actividad=" .$resultadoActividades[$key]['nombre'];
                            	$variableEditar.= "&descripcion=" .$resultadoActividades[$key]['descripcion'];
                            	$variableEditar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                            	$variableEditar.= "&tiempo=" . time ();
                            	 
                            	$variableEditar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEditar, $directorio);
                            	 
                                //enlace actualizar estado
                                $variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                if($resultadoActividades[$key]['estado']=='A')
                                    {$variableEstado.= "&opcion=inhabilitarActividad";}
                                else{$variableEstado.= "&opcion=habilitarActividad";}    
                                $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                $variableEstado.= "&id_actividad=" .$resultadoActividades[$key]['consecutivo_actividad'];
                                $variableEstado.= "&nombre_actividad=" .$resultadoActividades[$key]['nombre'];
                                $variableEstado.= "&estado_actividad=" .$resultadoActividades[$key]['estado'];
                                $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                $variableEstado.= "&tiempo=" . time ();
                                $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);

                                if($resultadoActividades[$key]['estado']=='A'){
                                	$resultadoActividades[$key]['estado']="Activo";
                                }else{
                                	$resultadoActividades[$key]['estado']="Inactivo";
                                }
                                
                                $mostrarHtml = "<tr align='center'>
                                        <td align='left'>".$resultadoActividades[$key]['consecutivo_actividad']."</td>
                                        <td align='left'>".$resultadoActividades[$key]['nombre']."</td>
                                        <td align='left'>".$resultadoActividades[$key]['descripcion']."</td>		
                                        <td align='left'>".$resultadoActividades[$key]['estado']."</td>
                                
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
                               // $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                unset($atributos);
                                
                                $mostrarHtml .= "</td>
                                		<td>";

                                        if($resultadoActividades[$key]['estado']=='Activo')
                                            {   
                                            	$esteCampo = "habilitar";
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
                                
                                $mostrarHtml .= "</td>";

                               $mostrarHtml .= "</tr>";
                               echo $mostrarHtml;
                               unset($mostrarHtml);
                               unset($variable);
                            }

                        echo "</tbody>";

                        echo "</table></div>";

                        //Fin de Conjunto de Controles
                        //echo $this->miFormulario->marcoAgrupacion("fin");

                }
                else{
                        $nombreFormulario=$esteBloque["nombre"];
                        include_once("core/crypto/Encriptador.class.php");
                        $cripto=Encriptador::singleton();
                        $directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

                        $miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");

                        $tab=1;
                        //---------------Inicio Formulario (<form>)--------------------------------
                        $atributos["id"]=$nombreFormulario;
                        $atributos["tipoFormulario"]="multipart/form-data";
                        $atributos["metodo"]="POST";
                        $atributos["nombreFormulario"]=$nombreFormulario;
                        $verificarFormulario="1";
                        $atributos ['tipoEtiqueta'] = 'inicio';
                        echo $this->miFormulario->formulario ( $atributos );

                        $atributos["id"]="divNoEncontroEgresado";
                        $atributos["estilo"]="marcoBotones";
                   		//$atributos["estiloEnLinea"]="display:none"; 
                        echo $this->miFormulario->division("inicio",$atributos);

                        //-------------Control Boton-----------------------
                        $esteCampo = "noEncontroProcesos";
                        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                        $atributos["etiqueta"] = "";
                        $atributos["estilo"] = "centrar";
                        $atributos["tipo"] = 'error';
                        $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
                        echo $this->miFormulario->cuadroMensaje($atributos);
                    	unset($atributos); 

                        $valorCodificado="pagina=".$miPaginaActual;
                        $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
                        $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
                        $valorCodificado=$cripto->codificar($valorCodificado);
                        //-------------Fin Control Boton----------------------

                        //------------------Fin Division para los botones-------------------------
                        echo $this->miFormulario->division("fin");
                        //------------------Division para los botones-------------------------
                        $atributos["id"]="botones";
                        $atributos["estilo"]="marcoBotones";
                        echo $this->miFormulario->division("inicio",$atributos);

                        //-------------Control Boton-----------------------
                        $esteCampo = "regresar";
                        $atributos["id"]=$esteCampo;
                        $atributos["tabIndex"]=$tab++;
                        $atributos["tipo"]="boton";
                        $atributos["estilo"]="jquery";
                        $atributos["verificar"]="true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
                        $atributos["tipoSubmit"]="jquery"; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
                        $atributos ["estiloBoton"] = 'jqueryui';
                        $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
                        $atributos["nombreFormulario"]=$nombreFormulario;
                        echo $this->miFormulario->campoBoton($atributos);
                        unset($atributos);
                        //-------------Fin Control Boton----------------------


                        //------------------Fin Division para los botones-------------------------
                        echo $this->miFormulario->division("fin");

                        //-------------Control cuadroTexto con campos ocultos-----------------------
                        //Para pasar variables entre formularios o enviar datos para validar sesiones
                        $atributos["id"]="formSaraData"; //No cambiar este nombre
                        $atributos["tipo"]="hidden";
                        $atributos["obligatorio"]=false;
                        $atributos["etiqueta"]="";
                        $atributos["valor"]=$valorCodificado;
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        //Fin del Formulario
                        $atributos ['tipoEtiqueta'] = 'fin';
                        echo $this->miFormulario->formulario ( $atributos );
                }

            echo $this->miFormulario->marcoAgrupacion ( 'fin' );

            // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
            // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
            // Se debe declarar el mismo atributo de marco con que se inició el formulario.
        }
        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division ( "fin" );
                
    }
}

$miSeleccionador = new consultarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
