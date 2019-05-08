<?php
use gestionConcurso\gestionInscripcion\funcion\redireccion;

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
		$hoy=date("Y-m-d");
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
            
            unset ( $atributos );
                {
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
                                            <th>Puntos aprueba</th>                                            
                                            <th>Estado</th>
                                            <th>Resultado Parcial</th>                                            
                                            <th>Resultado Reclamación</th>                                            
                                            <th>Resultado Final</th>      
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoListaCalendario as $key=>$value )
                                    {   //enlace actualizar estado
                                        $variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' ); 
                                        $variableEstado.= "&opcion=cerrarFase";
                                        $variableEstado.= "&fase=".$resultadoListaCalendario[$key]['fase'];
                                        $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEstado.= "&consecutivo_concurso=".$resultadoListaCalendario[$key]['consecutivo_concurso'];
                                        $variableEstado.= "&porcentaje_aprueba_concurso=".$_REQUEST['porcentaje_aprueba_conc'];
                                        $variableEstado.= "&consecutivo_calendario=".$resultadoListaCalendario[$key]['consecutivo_calendario'];       
                                        $variableEstado.= "&nombre_concurso=" . $_REQUEST ['nombre_concurso'];
                                        $variableEstado.= "&porcentaje_aprueba=" .$resultadoListaCalendario[$key]['porcentaje_aprueba'];
                                        $variableEstado.= "&nombre=" .$resultadoListaCalendario[$key]['nombre'];
                                        $variableEstado.= "&inscrito=" .$resultadoListaCalendario[$key]['clasifico'];
                                        $variableEstado.= "&reclamos=" .$resultadoListaCalendario[$key]['reclamos'];    
                                        
                                        //tipo cierre
                                        if($resultadoListaCalendario[$key]['cierre']=='')
                                              { $variableEstado.= "&tipo_cierre=parcial"; } 
                                        elseif($resultadoListaCalendario[$key]['cierre']=='parcial')
                                              { $variableEstado.= "&tipo_cierre=final"; }       
                                        //existan registros      
                                        if($resultadoListaCalendario[$key]['fase']=='soporte')
                                              { $variableEstado.= "&inscrito=".$resultadoListaCalendario[$key]['inscrito']; } 
                                        elseif($resultadoListaCalendario[$key]['fase']=='requisito')
                                              { $variableEstado.= "&evaluado=".$resultadoListaCalendario[$key]['validado']; } 
                                        elseif($resultadoListaCalendario[$key]['fase']=='evaluacion')
                                              { $variableEstado.= "&evaluado=".$resultadoListaCalendario[$key]['evaluado']; } 
                                              
                                        $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEstado.= "&tiempo=" . time ();
                                        $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);
                                        
                                        $mostrarHtml = "<tr align='center'  valign='middle'>
                                                <td align='left'>".$resultadoListaCalendario[$key]['fecha_inicio']."</td>    
                                                <td align='left'>".$resultadoListaCalendario[$key]['fecha_fin']."</td>    
                                                <td align='left'>".$resultadoListaCalendario[$key]['fecha_fin_reclamacion']."</td>        
                                                <td align='left'>".$resultadoListaCalendario[$key]['fecha_fin_resolver']."</td>        
                                                <td align='left'>".$resultadoListaCalendario[$key]['nombre']."</td>
                                                <td align='left'>".$resultadoListaCalendario[$key]['porcentaje_aprueba']." %</td>";
                                        $mostrarHtml .= "<td>";
                                                    //-------------Enlace-----------------------
                                                    $esteCampo = "estado";
                                                    $atributos["id"]=$esteCampo;
                                                    $atributos['enlace']='';//$variableEditar;
                                                    $atributos['tabIndex']=$esteCampo;
                                                    $atributos['redirLugar']=true;
                                                    $atributos['estilo']='clasico';
                                                    $atributos['posicionImagen'] = "atras";//"adelante";
                                                    $atributos['ancho']='25';
                                                    $atributos['alto']='25';
                                                    $atributos['enlaceImagen']=$rutaBloque."/images/edit.png";
                                                    if(($resultadoListaCalendario[$key]['fecha_fin_resolver']=='' &&  $hoy>$resultadoListaCalendario[$key]['fecha_fin'])
                                                        || ($resultadoListaCalendario[$key]['fecha_fin_resolver']!='' &&  $hoy>$resultadoListaCalendario[$key]['fecha_fin_resolver'])
                                                       )             
                                                        { $atributos['enlaceImagen']=$rutaBloque."/images/success.png";  
                                                          $atributos['enlaceTexto']=' Terminado';}
    
                                                    elseif($hoy>=$resultadoListaCalendario[$key]['fecha_inicio'] 
                                                            && ((($resultadoListaCalendario[$key]['fecha_fin_resolver']=='' &&  $hoy<=$resultadoListaCalendario[$key]['fecha_fin']))
                                                            || (($resultadoListaCalendario[$key]['fecha_fin_resolver']!=''  &&  $hoy<=$resultadoListaCalendario[$key]['fecha_fin_resolver'])))
                                                            )
                                                        { $atributos['enlaceImagen']=$rutaBloque."/images/goto.png";  
                                                          $atributos['enlaceTexto']=' En curso';}    
                                                          
                                                    else{ $atributos['enlaceImagen']=$rutaBloque."/images/player_pause.png";  
                                                          $atributos['enlaceTexto']=' Pendiente';}
                                                    
                                                    $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                    unset($atributos);    
                                        //resultados parciales            
                                        $mostrarHtml .= "</td> <td>";
                                                if( $resultadoListaCalendario[$key]['fase']!='registro' 
                                                    && $resultadoListaCalendario[$key]['fase']!='soporte' 
                                                    && $resultadoListaCalendario[$key]['inscrito']>0
                                                    && $resultadoListaCalendario[$key]['validado']>0 
                                                    && $resultadoListaCalendario[$key]['proceso']==0
                                                    && $hoy>$resultadoListaCalendario[$key]['fecha_fin']
                                                    && $resultadoListaCalendario[$key]['cierre']=='')
                                                      { 
                                                        //-------------Enlace-----------------------
                                                        $esteCampo = "cerrar";
                                                        $atributos["id"]=$esteCampo;
                                                        $atributos['enlace']=$variableEstado;
                                                        $atributos['tabIndex']=$esteCampo;
                                                        $atributos['redirLugar']=true;
                                                        $atributos['estilo']='clasico';
                                                        $atributos['posicionImagen'] = "atras";//"adelante";
                                                        $atributos['enlaceTexto']=' Cerrar Fase';
                                                        $atributos['ancho']='25';
                                                        $atributos['alto']='25';
                                                        $atributos['enlaceImagen']=$rutaBloque."/images/exec.png";
                                                        $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                        unset($atributos);    
                                                    }
                                                elseif($resultadoListaCalendario[$key]['fase']!='registro' 
                                                    && $resultadoListaCalendario[$key]['fase']!='soporte' 
                                                    && $hoy>$resultadoListaCalendario[$key]['fecha_fin']
                                                    && $resultadoListaCalendario[$key]['cierre']!='' )
                                                      { //Muestra Listado de resultado de cierre de soporte
                                                        $variableVer = "pagina=publicacion";                                                        
                                                        $variableVer.= "&opcion=faseParcial";
                                                        $variableVer.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                                        $variableVer.= "&id_usuario=" .$_REQUEST['usuario'];
                                                        $variableVer.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                                        $variableVer.= "&tiempo=" . time ();
                                                        $variableVer.= "&tipo_cierre=parcial";
                                                        $variableVer.= "&consecutivo_concurso=".$resultadoListaCalendario[$key]['consecutivo_concurso'];
                                                        $variableVer.= "&consecutivo_calendario=".$resultadoListaCalendario[$key]['consecutivo_calendario']; 
                                                        $variableVer.= "&fase=".$resultadoListaCalendario[$key]['fase'];  
                                                        $variableVer.= "&porcentaje_aprueba=".$resultadoListaCalendario[$key]['porcentaje_aprueba']; 
                                                        $variableVer.= "&porcentaje_aprueba_concurso=".$_REQUEST['porcentaje_aprueba_conc'];
                                                        $variableVer.= "&nombre_concurso=" . $_REQUEST ['nombre_concurso'];
                                                        $variableVer.= "&nombre=" .$resultadoListaCalendario[$key]['nombre'];      
                                                        $variableVer = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVer, $directorio);
                                                        //-------------Enlace-----------------------
                                                          $esteCampo = 'enlace_listaParcial'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:enlace("ruta_enlace_parcial'.$key.'");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['columnas'] = 1;
                                                          $atributos ['enlaceTexto'] = "&nbsp;Listado Parcial";//$resultadoListaCalendario[$key]['proceso']."&nbsp;&nbsp;";
                                                          $atributos ['estilo'] = 'clasico';
                                                          $atributos['enlaceImagen']=$rutaBloque."/images/lists.png";
                                                          $atributos ['posicionImagen'] ="atras";//"adelante";
                                                          $atributos ['ancho'] = '30px';
                                                          $atributos ['alto'] = '30px';
                                                          $atributos ['redirLugar'] = false;
                                                          $atributos ['valor'] = '';
                                                          $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                                                          unset ( $atributos );
                                                           // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                          $esteCampo = 'ruta_enlace_parcial'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['nombre'] = $esteCampo;
                                                          $atributos ['tipo'] = 'hidden';
                                                          $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                          $atributos ['obligatorio'] = false;
                                                          $atributos ['valor'] = $variableVer;
                                                          $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                          $atributos ['deshabilitado'] = FALSE;
                                                          $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                          // --------------- FIN CONTROL : Cuadro de Texto -------------------------------------------------- 
                                                      }                                                      
                                                    
                                        //resultados reclamacion              
                                        $mostrarHtml .= "</td> <td>";
                                                if( $resultadoListaCalendario[$key]['fase']!='registro' 
                                                    && $resultadoListaCalendario[$key]['fase']!='soporte' 
                                                    && $resultadoListaCalendario[$key]['fase']!='elegibles' 
                                                    && $resultadoListaCalendario[$key]['inscrito']>0
                                                    && $resultadoListaCalendario[$key]['validado']>0 
                                                    && $resultadoListaCalendario[$key]['proceso']>0
                                                    && $hoy>$resultadoListaCalendario[$key]['fecha_fin_resolver']
                                                    && $resultadoListaCalendario[$key]['cierre']=='parcial')
                                                      { 
                                                        //-------------Enlace-----------------------
                                                        $esteCampo = "cerrar";
                                                        $atributos["id"]=$esteCampo;
                                                        $atributos['enlace']=$variableEstado;
                                                        $atributos['tabIndex']=$esteCampo;
                                                        $atributos['redirLugar']=true;
                                                        $atributos['estilo']='clasico';
                                                        $atributos['posicionImagen'] = "atras";//"adelante";
                                                        $atributos['enlaceTexto']=' Cerrar Fase';
                                                        $atributos['ancho']='25';
                                                        $atributos['alto']='25';
                                                        $atributos['enlaceImagen']=$rutaBloque."/images/exec.png";
                                                        $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                        unset($atributos);    
                                                    }
                                                elseif($resultadoListaCalendario[$key]['fase']!='registro' 
                                                    && $resultadoListaCalendario[$key]['fase']!='soporte' 
                                                    && $resultadoListaCalendario[$key]['fase']!='elegibles'  
                                                    && $hoy>$resultadoListaCalendario[$key]['fecha_fin_resolver']
                                                    && $resultadoListaCalendario[$key]['cierre']=='final' )
                                                      { //Muestra Listado de resultado de cierre de soporte
                                                        $variableVerReclamo = "pagina=publicacion";                                                        
                                                        $variableVerReclamo.= "&opcion=faseReclamo";
                                                        $variableVerReclamo.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                                        $variableVerReclamo.= "&id_usuario=" .$_REQUEST['usuario'];
                                                        $variableVerReclamo.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                                        $variableVerReclamo.= "&tiempo=" . time ();
                                                        $variableVerReclamo.= "&tipo_cierre=reclamo";
                                                        $variableVerReclamo.= "&consecutivo_concurso=".$resultadoListaCalendario[$key]['consecutivo_concurso'];
                                                        $variableVerReclamo.= "&consecutivo_calendario=".$resultadoListaCalendario[$key]['consecutivo_calendario']; 
                                                        $variableVerReclamo.= "&fase=".$resultadoListaCalendario[$key]['fase'];  
                                                        $variableVerReclamo.= "&porcentaje_aprueba=".$resultadoListaCalendario[$key]['porcentaje_aprueba']; 
                                                        $variableVerReclamo.= "&nombre_concurso=" . $_REQUEST ['nombre_concurso'];
                                                        $variableVerReclamo.= "&nombre=" .$resultadoListaCalendario[$key]['nombre'];      
                                                        $variableVerReclamo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVerReclamo, $directorio);
                                                        //-------------Enlace-----------------------
                                                          $esteCampo = 'enlace_listaReclamo'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:enlace("ruta_enlace_Reclamo'.$key.'");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['columnas'] = 1;
                                                          $atributos ['enlaceTexto'] = "&nbsp;Listado Reclamaciones";//$resultadoListaCalendario[$key]['proceso']."&nbsp;&nbsp;";
                                                          $atributos ['estilo'] = 'clasico';
                                                          $atributos['enlaceImagen']=$rutaBloque."/images/lists.png";
                                                          $atributos ['posicionImagen'] ="atras";//"adelante";
                                                          $atributos ['ancho'] = '30px';
                                                          $atributos ['alto'] = '30px';
                                                          $atributos ['redirLugar'] = false;
                                                          $atributos ['valor'] = '';
                                                          $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                                                          unset ( $atributos );
                                                           // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                          $esteCampo = 'ruta_enlace_Reclamo'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['nombre'] = $esteCampo;
                                                          $atributos ['tipo'] = 'hidden';
                                                          $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                          $atributos ['obligatorio'] = false;
                                                          $atributos ['valor'] = $variableVerReclamo;
                                                          $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                          $atributos ['deshabilitado'] = FALSE;
                                                          $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                          // --------------- FIN CONTROL : Cuadro de Texto -------------------------------------------------- 
                                                      }   
                                                      
                                        //resultados finales              
                                        $mostrarHtml .= "</td> <td>";
                                                if($resultadoListaCalendario[$key]['fase']=='soporte' 
                                                   && $resultadoListaCalendario[$key]['inscrito']==0 
                                                   && $hoy>$resultadoListaCalendario[$key]['fecha_fin']
                                                   && $resultadoListaCalendario[$key]['cierre']=='')
                                                    { 
                                                        //-------------Enlace boton cerrar fase de soporte-----------------------
                                                        $esteCampo = "cerrar";
                                                        $atributos["id"]=$esteCampo;
                                                        $atributos['enlace']=$variableEstado;
                                                        $atributos['tabIndex']=$esteCampo;
                                                        $atributos['redirLugar']=true;
                                                        $atributos['estilo']='clasico';
                                                        $atributos['posicionImagen'] = "atras";//"adelante";
                                                        $atributos['enlaceTexto']=' Cerrar Soportes';
                                                        $atributos['ancho']='30';
                                                        $atributos['alto']='30';
                                                        $atributos['enlaceImagen']=$rutaBloque."/images/exec.png";
                                                        $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                        unset($atributos);    
                                                    } 
                                                elseif($resultadoListaCalendario[$key]['fase']!='registro' 
                                                       && $resultadoListaCalendario[$key]['fase']=='soporte' 
                                                       && $resultadoListaCalendario[$key]['proceso']>0
                                                       && $resultadoListaCalendario[$key]['cierre']=='final' )
                                                      { //Muestra Listado de resultado de cierre de soporte
                                                        $variableVer = "pagina=publicacion";                                                        
                                                        $variableVer.= "&opcion=faseProcesado";
                                                        $variableVer.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                                        $variableVer.= "&id_usuario=" .$_REQUEST['usuario'];
                                                        $variableVer.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                                        $variableVer.= "&tiempo=" . time ();
                                                        $variableVer.= "&consecutivo_concurso=".$resultadoListaCalendario[$key]['consecutivo_concurso'];
                                                        $variableVer.= "&consecutivo_calendario=".$resultadoListaCalendario[$key]['consecutivo_calendario']; 
                                                        $variableVer.= "&fase=".$resultadoListaCalendario[$key]['fase'];  
                                                        $variableVer.= "&nombre_concurso=" . $_REQUEST ['nombre_concurso'];
                                                        $variableVer.= "&nombre=" .$resultadoListaCalendario[$key]['nombre'];      
                                                        $variableVer = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVer, $directorio);
                                                        //-------------Enlace-----------------------
                                                          $esteCampo = 'enlace_procesado'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:enlace("ruta_enlace_ver'.$key.'");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['columnas'] = 1;
                                                          $atributos ['enlaceTexto'] = "&nbsp;Listado";;//$resultadoListaCalendario[$key]['proceso']."&nbsp;&nbsp;";
                                                          $atributos ['estilo'] = 'clasico';
                                                          $atributos['enlaceImagen']=$rutaBloque."/images/lists.png";
                                                          $atributos ['posicionImagen'] ="atras";//"adelante";
                                                          $atributos ['ancho'] = '30px';
                                                          $atributos ['alto'] = '30px';
                                                          $atributos ['redirLugar'] = false;
                                                          $atributos ['valor'] = '';
                                                          $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                                                          unset ( $atributos );
                                                           // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                          $esteCampo = 'ruta_enlace_ver'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['nombre'] = $esteCampo;
                                                          $atributos ['tipo'] = 'hidden';
                                                          $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                          $atributos ['obligatorio'] = false;
                                                          $atributos ['valor'] = $variableVer;
                                                          $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                          $atributos ['deshabilitado'] = FALSE;
                                                          $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                          // --------------- FIN CONTROL : Cuadro de Texto -------------------------------------------------- 
                                                      }                                                      

                                                elseif($resultadoListaCalendario[$key]['fase']!='registro' 
                                                    && $resultadoListaCalendario[$key]['fase']!='soporte' 
                                                    && $hoy>$resultadoListaCalendario[$key]['fecha_fin_resolver']
                                                    && $resultadoListaCalendario[$key]['cierre']=='final' )
                                                      {   //Muestra Listado de resultado de cierre de soporte
                                                        $variableVerFinal = "pagina=publicacion";                                                        
                                                        $variableVerFinal.= "&opcion=faseFinal";
                                                        $variableVerFinal.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                                        $variableVerFinal.= "&id_usuario=" .$_REQUEST['usuario'];
                                                        $variableVerFinal.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                                        $variableVerFinal.= "&tiempo=" . time ();
                                                        $variableVerFinal.= "&tipo_cierre=final";
                                                        $variableVerFinal.= "&consecutivo_concurso=".$resultadoListaCalendario[$key]['consecutivo_concurso'];
                                                        $variableVerFinal.= "&consecutivo_calendario=".$resultadoListaCalendario[$key]['consecutivo_calendario']; 
                                                        $variableVerFinal.= "&fase=".$resultadoListaCalendario[$key]['fase'];  
                                                        $variableVerFinal.= "&porcentaje_aprueba=".$resultadoListaCalendario[$key]['porcentaje_aprueba']; 
                                                        $variableVerFinal.= "&porcentaje_aprueba_concurso=".$_REQUEST['porcentaje_aprueba_conc'];
                                                        $variableVerFinal.= "&nombre_concurso=" . $_REQUEST ['nombre_concurso'];
                                                        $variableVerFinal.= "&nombre=" .$resultadoListaCalendario[$key]['nombre'];      
                                                        $variableVerFinal = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableVerFinal, $directorio);
                                                        //-------------Enlace-----------------------
                                                          $esteCampo = 'enlace_listaFinal'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['enlace'] = 'javascript:enlace("ruta_enlace_final'.$key.'");';
                                                          $atributos ['tabIndex'] = 0;
                                                          $atributos ['columnas'] = 1;
                                                          $atributos ['enlaceTexto'] = "&nbsp;Listado Final";//$resultadoListaCalendario[$key]['proceso']."&nbsp;&nbsp;";
                                                          $atributos ['estilo'] = 'clasico';
                                                          $atributos['enlaceImagen']=$rutaBloque."/images/lists.png";
                                                          $atributos ['posicionImagen'] ="atras";//"adelante";
                                                          $atributos ['ancho'] = '30px';
                                                          $atributos ['alto'] = '30px';
                                                          $atributos ['redirLugar'] = false;
                                                          $atributos ['valor'] = '';
                                                          $mostrarHtml .= $this->miFormulario->enlace( $atributos );
                                                          unset ( $atributos );
                                                           // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------  
                                                          $esteCampo = 'ruta_enlace_final'.$key;
                                                          $atributos ['id'] = $esteCampo;
                                                          $atributos ['nombre'] = $esteCampo;
                                                          $atributos ['tipo'] = 'hidden';
                                                          $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                          $atributos ['obligatorio'] = false;
                                                          $atributos ['valor'] = $variableVerFinal;
                                                          $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                                                          $atributos ['deshabilitado'] = FALSE;
                                                          $mostrarHtml .= $this->miFormulario->campoCuadroTexto ( $atributos );
                                                          // --------------- FIN CONTROL : Cuadro de Texto -------------------------------------------------- 
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
