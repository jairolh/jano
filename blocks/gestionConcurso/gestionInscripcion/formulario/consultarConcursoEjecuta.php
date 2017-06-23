<?php
namespace gestionConcurso\gestionInscripcion;

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
            $parametro=array('hoy'=>date("Y-m-d"));
            $cadena_sql = $this->miSql->getCadenaSql("consultaConcurso", $parametro);
            $resultadoConcurso = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $esteCampo = "marcoEjecucion";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {
                if($resultadoConcurso)
                {	
                    //-----------------Inicio de Conjunto de Controles----------------------------------------
                        $esteCampo = "marcoListaConcurso";
                        $atributos["estilo"] = "jqueryui";
                        $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                        //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                        unset($atributos);

                        echo "<div class='cell-border'><table id='tablaConcursos' class='table table-striped table-bordered'>";

                        echo "<thead>
                                <tr align='center'>
                                    <th>Tipo Concurso</th>
                                    <th>Modalidad</th>
                                    <th>Nombre</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Acuerdo</th>
                                    <th>Soporte</th>
                                    <th>Inscritos</th>
                                    <th>Consulta</th>
                                </tr>
                            </thead>
                            <tbody>";

                        foreach($resultadoConcurso as $key=>$value )
                            {   $parametro['tipo']='unico';
                                $parametroSop = array('consecutivo'=>0,
                                     'tipo_dato'=>'datosConcurso',
                                     'nombre_soporte'=>'soporteAcuerdo',
                                     'consecutivo_dato'=>$resultadoConcurso[$key]['consecutivo_concurso']
                                    );
                                $cadenaSop_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                $resultadoSopCon = $esteRecursoDB->ejecutarAcceso($cadenaSop_sql, "busqueda");
                            
                                //enlace actualizar perfil
                                $variableDetalle = "pagina=gestionInscripcion"; //pendiente la pagina para modificar parametro      
                                $variableDetalle.= "&opcion=detalle";    
                                $variableDetalle.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                $variableDetalle.= "&consecutivo_concurso=" .$resultadoConcurso[$key]['consecutivo_concurso'];
                                $variableDetalle.= "&nombre=" .$resultadoConcurso[$key]['nombre'].' - '.$resultadoConcurso[$key]['modalidad'];
                                $variableDetalle.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                $variableDetalle.= "&tiempo=" . time ();
                                $variableDetalle = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableDetalle, $directorio);

                                
                                $mostrarHtml = "<tr align='center'>
                                        <td align='left'>".$resultadoConcurso[$key]['nivel_concurso']."</td>
                                        <td align='left'>".$resultadoConcurso[$key]['modalidad']."</td>
                                        <td align='left'>".$resultadoConcurso[$key]['nombre']."</td>
                                        <td align='left'>".$resultadoConcurso[$key]['fecha_inicio']."</td>
                                        <td align='left'>".$resultadoConcurso[$key]['fecha_fin']."</td>
                                        <td>".$resultadoConcurso[$key]['acuerdo']."</td>";
                                $mostrarHtml .= "<td>";
                                            if(isset($resultadoSopCon[0]['alias']))
                                                {
                                                  $esteCampo = 'archivoactividad'.$resultadoSopCon[0]['consecutivo_soporte'];
                                                  $atributos ['id'] = $esteCampo;
                                                  $atributos ['enlace'] = 'javascript:soporte("ruta_actividad'.$resultadoSopCon[0]['consecutivo_soporte'].'");';
                                                  $atributos ['tabIndex'] = 0;
                                                  $atributos ['columnas'] = 2;
                                                  $atributos ['enlaceTexto'] = "";// $resultadoSopCon[0]['alias'];
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
                                                  $esteCampo = 'ruta_actividad'.$resultadoSopCon[0]['consecutivo_soporte'];
                                                  $atributos ['id'] = $esteCampo;
                                                  $atributos ['nombre'] = $esteCampo;
                                                  $atributos ['tipo'] = 'hidden';
                                                  $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                  $atributos ['obligatorio'] = false;
                                                  $atributos ['valor'] = $this->rutaSoporte.$resultadoSopCon[0]['ubicacion']."/".$resultadoSopCon[0]['archivo'];
                                                  $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                  $atributos ['deshabilitado'] = FALSE;
                                                  $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                  // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                }
                        $mostrarHtml.= "</td> 
                                        <td>".$resultadoConcurso[$key]['inscritos']."</td>   
                                        <td>";
                                                $esteCampo = "detalle";
                                                $atributos["id"]=$esteCampo;
                                                $atributos['enlace']=$variableDetalle;
                                                $atributos['tabIndex']=$esteCampo;
                                                $atributos['redirLugar']=true;
                                                $atributos['estilo']='clasico';
                                                $atributos['enlaceTexto']='';
                                                $atributos['ancho']='25';
                                                $atributos['alto']='25';
                                                $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";
                                                $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                unset($atributos);
                                             //-------------Enlace-----------------------
                                         
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

                }else
                {
                    $tab=1;
                    //---------------Inicio Formulario (<form>)--------------------------------
                    $atributos["id"]="divNoEncontroConcurso";
                    $atributos["estilo"]="marcoBotones";
                    //$atributos["estiloEnLinea"]="display:none"; 
                        echo $this->miFormulario->division("inicio",$atributos);

                        //-------------Control Boton-----------------------
                        $esteCampo = "noEncontroConcurso";
                        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                        $atributos["etiqueta"] = "";
                        $atributos["estilo"] = "centrar";
                        $atributos["tipo"] = 'error';
                        $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
                        echo $this->miFormulario->cuadroMensaje($atributos);
                        unset($atributos); 
                        //------------------Fin Division para los botones-------------------------
                        echo $this->miFormulario->division("fin");
                        //-------------Control cuadroTexto con campos ocultos-----------------------
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
