<?php
namespace usuarios\gestionUsuarios;

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
		$valorCodificado .= "&opcion=nuevoPerfil";
                $valorCodificado .= "&usuario=" . $this->miSesion->getSesionUsuarioId();
		$valorCodificado .= "&id_usuario=" . $_REQUEST['id_usuario'];
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
                
		$parametro['id_usuario']=$_REQUEST['id_usuario'];
                $cadena_sql = $this->miSql->getCadenaSql("consultarUsuarios", $parametro);
                $resultadoUsuarios = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
 
                /**RESUMEN DATOS*/
                $tab = 1;
        	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$variable = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );

                $esteCampo = 'botonRegresar';
                $atributos ['id'] = $esteCampo;
                $atributos ['enlace'] = $variable;
                $atributos ['tabIndex'] = 1;
                $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                $atributos ['estilo'] = 'textoPequenno textoGris';
                $atributos ['enlaceImagen'] = $rutaBloque."/images/player_rew.png";
                $atributos ['posicionImagen'] = "atras";//"adelante";
                $atributos ['ancho'] = '30px';
                $atributos ['alto'] = '30px';
                $atributos ['redirLugar'] = true;
                echo $this->miFormulario->enlace ( $atributos );
                unset ( $atributos );
                // ---------------- SECCION: Controles del Formulario -----------------------------------------------
			
                $esteCampo = "marcoDatosBasicos";
                $atributos ['id'] = $esteCampo;
                $atributos ["estilo"] = "jqueryui";
                $atributos ['tipoEtiqueta'] = 'inicio';
                $atributos ["leyenda"] = "Roles de Usuario";
                echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
                unset ( $atributos );
                {	

                    echo "<div class='datagrid'><table width='100%' align='center'>
                             <tr align='center'>
                                 <td align='center'>";
                                         $esteCampo = 'nuevoPerfil';
                                         $atributos ['id'] = $esteCampo;
                                         $atributos ['enlace'] = $variableNuevo;
                                         $atributos ['tabIndex'] = 1;
                                         $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                                         $atributos ['estilo'] = 'textoPequenno textoGris';
                                         $atributos ['enlaceImagen'] = $rutaBloque."/images/grupoNuevo.png";
                                         $atributos ['posicionImagen'] = "atras";//"adelante";
                                         $atributos ['ancho'] = '45px';
                                         $atributos ['alto'] = '45px';
                                         $atributos ['redirLugar'] = true;
                                         echo $this->miFormulario->enlace ( $atributos );
                                         unset ( $atributos );
                         echo "   </td>
                             </tr>
                           </table></div> ";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                        $esteCampo = 'id_usuario';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = FALSE;
                        $atributos ['columnas'] = 3;
                        $atributos ['dobleLinea'] = 0;
                        $atributos ['tabIndex'] = $tab;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                        $atributos ['validar']="required, minSize[5], custom[integer]";
                        $atributos ['valor'] = $resultadoUsuarios[0]['id_usuario'];
                        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                        $atributos ['deshabilitado'] = true;
                        $atributos ['tamanno'] = 25;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 170;
                        $tab ++;
                        // Aplica atributos globales al control
                        $atributos = array_merge ( $atributos, $atributosGlobales );
                        echo $this->miFormulario->campoCuadroTexto ( $atributos );
                        unset ( $atributos );
                        // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                        $esteCampo = 'nombres';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = false;
                        $atributos ['columnas'] = 3;
                        $atributos ['dobleLinea'] = 0;
                        $atributos ['tabIndex'] = $tab;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                        $atributos ['validar']="required, minSize[5]";
                        $atributos ['valor'] = $resultadoUsuarios[0]['nombre'];
                        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                        $atributos ['deshabilitado'] = true;
                        $atributos ['tamanno'] = 35;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 170;
                        $tab ++;
                        // Aplica atributos globales al control
                        $atributos = array_merge ( $atributos, $atributosGlobales );
                        echo $this->miFormulario->campoCuadroTexto ( $atributos );
                        unset ( $atributos );
                        // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                        $esteCampo = 'apellidos';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = false;
                        $atributos ['columnas'] = 3;
                        $atributos ['dobleLinea'] = 0;
                        $atributos ['tabIndex'] = $tab;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                        $atributos ['validar']="required, minSize[5]";
                        $atributos ['valor'] = $resultadoUsuarios[0]['apellido'];
                        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                        $atributos ['deshabilitado'] = true;
                        $atributos ['tamanno'] = 35;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 170;
                        $tab ++;
                        // Aplica atributos globales al control
                        $atributos = array_merge ( $atributos, $atributosGlobales );
                        echo $this->miFormulario->campoCuadroTexto ( $atributos );
                        unset ( $atributos );
                        // ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
                 /*Fin resumen ***/

            //identifca lo roles para la busqueda de subsistemas
            $roles=  $this->miSesion->RolesSesion();
            $aux=0;
            foreach ($roles as $key => $value) {
                    if($roles[$key]['cod_rol']==1 && $roles[$key]['cod_app']>1)
                        {$app[$aux]=$roles[$key]['cod_app'];
                         $rol[$aux]=$roles[$key]['cod_rol'];
                         $aux++;
                         $parametro['tipoAdm']='subsistema';
                        }
                    elseif($roles[$key]['cod_rol']==0 && $roles[$key]['cod_app']==1)
                        {$app='';
                         $app[0]=$roles[$key]['cod_app'];
                         $rol[0]=$roles[$key]['cod_rol'];
                         $parametro['tipoAdm']='general';
                         break;
                        }      
                }                        
                        
            $cadena_sql = $this->miSql->getCadenaSql("consultarPerfilUsuario", $parametro);
            $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            //var_dump($resultadoPerfil);
            if($resultadoPerfil)
            {	
                //-----------------Inicio de Conjunto de Controles----------------------------------------
                    $esteCampo = "marcoDatosResultadoParametrizar";
                    $atributos["estilo"] = "jqueryui";
                    $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                    //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                    unset($atributos);

                    echo "<div class='cell-border'><table id='tablaProcesos'>";

                    echo "<thead>
                            <tr align='center'>
                                <th>Subsistema</th>
                                <th>Rol</th>
                                <th>Fecha Registro</th>
                                <th>Fecha Caduca</th>                    
                                <th>Estado</th>
                                <th>Editar</th>
                                <th>Actualizar Estado</th>
                            </tr>
                        </thead>
                        <tbody>";

                    foreach($resultadoPerfil as $key=>$value )
                        {   $cambio='SI';
                            //deshabilita privilegios de otros subsistemas 
                            if($resultadoPerfil[$key]['rol_id']==0 && $rol[0]!=0)
                                {$cambio='NO';} 
                            elseif ( in_array(1, $rol) && !in_array($resultadoPerfil[$key]['id_subsistema'], $app))
                                {$cambio='NO';}
                                
                            $variableEditar = "pagina=gestionUsuarios"; //pendiente la pagina para modificar parametro                                                        
                            $variableEditar.= "&opcion=editarPerfil";
                            $variableEditar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                            $variableEditar.= "&id_usuario=" .$resultadoUsuarios[0]['id_usuario'];
                            $variableEditar.= "&id_subsistema=" .$resultadoPerfil[$key]['id_subsistema'];
                            $variableEditar.= "&rol_id=" .$resultadoPerfil[$key]['rol_id'];
                            $variableEditar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                            $variableEditar.= "&tiempo=" . time ();
		
                            $variableEditar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEditar, $directorio);
                            //enlace actualizar estado
                            $variableEstado = "pagina=gestionUsuarios"; //pendiente la pagina para modificar parametro      
                            if($resultadoPerfil[$key]['estado']=='Activo')
                                {$variableEstado.= "&opcion=inhabilitarPerfil";}
                            else{$variableEstado.= "&opcion=habilitarPerfil";}    

                            $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                            $variableEstado.= "&id_usuario=" .$resultadoUsuarios[0]['id_usuario'];
                            $variableEstado.= "&id_subsistema=" .$resultadoPerfil[$key]['id_subsistema'];
                            $variableEstado.= "&rol_id=" .$resultadoPerfil[$key]['rol_id'];
                            $variableEstado.= "&rol_alias=" .$resultadoPerfil[$key]['rol_alias'];
                            $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                            $variableEstado.= "&tiempo=" . time ();

                            $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);

                            $mostrarHtml = "<tr align='center'>
                                    <td align='left'>".$resultadoPerfil[$key]['subsistema']."</td>
                                    <td align='left'>".$resultadoPerfil[$key]['rol_alias']."</td>
                                    <td align='left'>".$resultadoPerfil[$key]['fecha_registro']."</td>
                                    <td align='left'>".$resultadoPerfil[$key]['fecha_caduca']."</td>
                                    <td>".$resultadoPerfil[$key]['estado']."</td>    
                                    <td>";
                                        if($cambio=='SI')
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
                            $mostrarHtml .= "</td>
                                            <td>";

                                    if($resultadoPerfil[$key]['estado']=='Activo' && $cambio=='SI')
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
                                    elseif($resultadoPerfil[$key]['estado']!='Activo' && $cambio=='SI')
                                        {
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
