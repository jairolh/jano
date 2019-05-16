<?php
namespace gestionConcurso\reclamaciones;

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

                 //identifca lo roles para la busqueda de subsistemas
                $roles=  $this->miSesion->RolesSesion();
                $aux=0;
                $tipo = array();
                $perfil = array();
                $actividad = array();
                $find='';
                $perfiles='';
                $actividades = '';
                
                foreach ($roles as $key => $value) 
                    {  if (!in_array($roles[$key]['nom_app'], $tipo)) 
                          { array_push ( $tipo , $roles[$key]['nom_app'] );}
                       if (!in_array($roles[$key]['cod_rol'], $perfil)) 
                          { array_push ( $perfil , $roles[$key]['cod_rol'] );}    
                    }
                foreach ($tipo as $key => $value) 
                    {   $find.="'".$value."'";
                        if(($key+1)<count($tipo))
                            {$find.=",";}
                    }

                foreach ($perfil as $key => $value) 
                    {   $perfiles.="'".$value."'";
                        if(($key+1)<count($perfil))
                            {$perfiles.=",";}
                    }    
                    
            $parametro=array('hoy'=>date("Y-m-d"),'tipo'=>$find,'perfiles'=>$perfiles);            
            //consultar roles del usuario
            $cadena_sql = $this->miSql->getCadenaSql("consultaRolActividad", $parametro);
            $resultadoRoles= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

            foreach ($resultadoRoles as $key => $value) 
                    {  if (!in_array($resultadoRoles[$key]['consecutivo_actividad'], $actividad)) 
                          { array_push ( $actividad , $resultadoRoles[$key]['consecutivo_actividad'] );}
                    }
                foreach ($actividad as $key => $value) 
                    {   $actividades.="'".$value."'";
                        if(($key+1)<count($actividad))
                            {$actividades.=",";}
                    }
           
            $parametro['actividad']=$actividades;
            $cadena_sql = $this->miSql->getCadenaSql("consultaConcurso", $parametro);
            $resultadoConcurso = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

            //-----BUSCA LOS TIPOS DE SOPORTES PARA EL FORMUALRIO, SEGÚN LOS RELACIONADO EN LA TABLA
            $parametroTipoSop = array('dato_relaciona'=>'datosConcurso','tipo_soporte'=>'soporteAcuerdo',);
            $cadenaSalud_sql = $this->miSql->getCadenaSql("buscarTipoSoporte", $parametroTipoSop);
            $resultadoTiposop = $esteRecursoDB->ejecutarAcceso($cadenaSalud_sql, "busqueda");
            // ---------------- SECCION: Enlace para soporte -----------------------------------------------
            $variableSoporte = "pagina=gestionarSoportes"; //pendiente la pagina para modificar parametro                                                        
            $variableSoporte.= "&action=gestionarSoportes";
            $variableSoporte.= "&bloque=" . $esteBloque["id_bloque"];
            $variableSoporte.= "&bloqueGrupo=";              
            
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
                                    <th>Reclamaciones</th>
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

                                $variableDetalle = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                $variableDetalle.= "&opcion=consutarReclamaciones";
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
                                       // --------------- INICIO CONTROLES : Visualizar SOPORTES SEGUN LOS RELACIONADOS --------------------------------------------------
                                        foreach ($resultadoTiposop as $tipokey => $value) 
                                            {//valida si existen soportes para el tipo
                                            $parametroSop = array('consecutivo_persona'=>0,
                                                 'tipo_dato'=>$resultadoTiposop[$tipokey]['dato_relaciona'],
                                                 'nombre_soporte'=>$resultadoTiposop[$tipokey]['nombre'],
                                                 'consecutivo_dato'=>$resultadoConcurso[$key]['consecutivo_concurso']);


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
                        $mostrarHtml.= "</td>

                                        <td>";

                                            if($resultadoConcurso[$key]['reclamaciones']>=1){
                                                    $esteCampo = "detalle";
                                                    $atributos["id"]=$esteCampo;
                                                    $atributos['enlace']=$variableDetalle;
                                                    $atributos['tabIndex']=$esteCampo;
                                                    $atributos['redirLugar']=true;
                                                    $atributos['estilo']='clasico';
                                                    $atributos['enlaceTexto']=$resultadoConcurso[$key]['reclamaciones']." ";
                                                    $atributos['ancho']='25';
                                                    $atributos['alto']='25';
                                                    $atributos ['posicionImagen'] ="adelante";//"atras";
                                                    $atributos['enlaceImagen']=$rutaBloque."/images/xmag.png";
                                                    $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                    unset($atributos);
                                     //-------------Enlace-----------------------
                                         }else{
                                                 $mostrarHtml .= "0";
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
