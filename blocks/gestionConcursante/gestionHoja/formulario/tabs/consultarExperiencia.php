<?php
use gestionConcursante\gestionHoja\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarExperiencia {
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
            $cadena_sql = $this->miSql->getCadenaSql("consultarExperiencia", $parametro);
            $resultadoListaExperiencia = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $esteCampo = "marcoListaExperiencia";
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
                                $esteCampo = 'nuevoExperiencia';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['enlace'] = "#";//$variableNuevo;
                                $atributos ['onClick'] ="show(\"marcoExperiencia\")";
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

                    if($resultadoListaExperiencia)
                        {	
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoConsultaExperiencia";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
                                echo "<div class='cell-border'><table id='tablaExperiencia' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center'>
                                            <th>Pais</th>
                                            <th>Institucion</th>
                                            <th>Nivel Formacion</th>
                                            <th>Programa</th>
                                            <th>Modalidad</th>
                                            <th>Cursos aprobados</th>
                                            <th>Graduado</th>
                                            <th>Fecha de grado</th>
                                            <th>Diploma / Acta</th>
                                            <th>Tarjeta Profesional</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoListaExperiencia as $key=>$value )
                                    {   $parametro['tipo']='unico';
                                        $parametroSop = array('consecutivo'=>$$resultadoListaExperiencia[$key]['consecutivo_persona'],
                                             'tipo_dato'=>'datosFormacion',
                                             'nombre_soporte'=>'soporteDiploma',
                                             'consecutivo_dato'=>$resultadoListaExperiencia[$key]['consecutivo_formacion']
                                            );
                                        
                                        $cadenaDip_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                        $resultadoDip = $esteRecursoDB->ejecutarAcceso($cadenaDip_sql, "busqueda");
                                        
                                        $parametroSop['nombre_soporte']='soporteTprofesional';
                                        $cadenaTarjeta_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                        $resultadoTarjeta = $esteRecursoDB->ejecutarAcceso( $cadenaTarjeta_sql, "busqueda");

                                        $variableEditar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );                                                        
                                        $variableEditar.= "&opcion=mostrar";
                                        $variableEditar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEditar.= "&id_usuario=" .$resultadoExperiencia[$key]['id_usuario'];
                                        $variableEditar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEditar.= "&tiempo=" . time ();
                                        $variableEditar .= "&consecutivo_formacion=".$resultadoExperiencia[$key]['consecutivo_formacion'];
                                        $variableEditar .= "&consecutivo_persona=".$resultadoExperiencia[$key]['consecutivo_persona'];       
                                        $variableEditar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEditar, $directorio);
                                        $variableEditar.= "#tabFormacion";
                                        
                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoExperiencia[$key]['pais']."</td>
                                                <td align='left'>".$resultadoExperiencia[$key]['nombre_institucion']."</td>
                                                <td align='left'>".$resultadoExperiencia[$key]['nivel']."</td>
                                                <td align='left'>".$resultadoExperiencia[$key]['nombre_programa']."</td>
                                                <td align='left'>".$resultadoExperiencia[$key]['modalidad']."</td>
                                                <td align='left'>".$resultadoExperiencia[$key]['cursos_aprobados']."</td>
                                                <td align='left'>".$resultadoExperiencia[$key]['graduado']."</td>
                                                <td align='left'>".$resultadoExperiencia[$key]['fecha_grado']."</td>";
                                        $mostrarHtml .= "<td> ";
                                                if(isset($resultadoDip[0]['alias']))
                                                    {
                                                      $esteCampo = 'archivoDiploma'.$resultadoDip[0]['consecutivo_soporte'];
                                                      $atributos ['id'] = $esteCampo;
                                                      $atributos ['enlace'] = 'javascript:soporte("ruta_diploma'.$resultadoDip[0]['consecutivo_soporte'].'");';
                                                      $atributos ['tabIndex'] = 0;
                                                      $atributos ['columnas'] = 2;
                                                      $atributos ['enlaceTexto'] = $resultadoDip[0]['alias'];
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
                                                      $esteCampo = 'ruta_diploma'.$resultadoDip[0]['consecutivo_soporte'];
                                                      $atributos ['id'] = $esteCampo;
                                                      $atributos ['nombre'] = $esteCampo;
                                                      $atributos ['tipo'] = 'hidden';
                                                      $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                      $atributos ['obligatorio'] = false;
                                                      $atributos ['valor'] = $this->rutaSoporte.$resultadoDip[0]['ubicacion']."/".$resultadoDip[0]['archivo'];
                                                      $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                      $atributos ['deshabilitado'] = FALSE;
                                                      $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                      // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                    }                                        
                                        $mostrarHtml .= "</td>
                                                        <td>";
                                                    if(isset($resultadoTarjeta[0]['alias']))
                                                        {
                                                          $esteCampo = 'archivotarjeta'.$resultadoTarjeta[0]['consecutivo_soporte'];
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:soporte("ruta_tarjetap'.$resultadoTarjeta[0]['consecutivo_soporte'].'");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['columnas'] = 2;
                                                          $atributos ['enlaceTexto'] = $resultadoTarjeta[0]['alias'];
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
                                                          $esteCampo = 'ruta_tarjetap'.$resultadoTarjeta[0]['consecutivo_soporte'];
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['nombre'] = $esteCampo;
                                                          $atributos ['tipo'] = 'hidden';
                                                          $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                          $atributos ['obligatorio'] = false;
                                                          $atributos ['valor'] = $this->rutaSoporte.$resultadoTarjeta[0]['ubicacion']."/".$resultadoTarjeta[0]['archivo'];
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
                                $atributos["id"]="divNoEncontroExperiencia";
                                $atributos["estilo"]="";
                           //$atributos["estiloEnLinea"]="display:none"; 
                                echo $this->miFormulario->division("inicio",$atributos);

                                //-------------Control Boton-----------------------
                                $esteCampo = "noEncontroExperiencia";
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
            echo $this->miFormulario->marcoAgrupacion ( 'fin', $atributos );
            unset ( $atributos );
    }
}

$miSeleccionador = new consultarExperiencia ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
