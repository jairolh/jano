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
		$valorCodificado .= "&opcion=nuevo";
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
		
                //identifca lo roles para la busqueda de subsistemas
                $roles=  $this->miSesion->RolesSesion();
                $aux=0;
                $parametro=array();
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
                $cadena_sql = $this->miSql->getCadenaSql("consultarUsuarios", $parametro);
                $resultadoUsuarios = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
 
            $esteCampo = "marcoDatosBasicos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>GESTIÓN DE USUARIOS</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {

                echo "<div ><table width='100%' align='center'>
                        <tr align='center'>
                            <td align='center'>";
                                $esteCampo = 'nuevoUsuario';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['enlace'] = $variableNuevo;
                                $atributos ['tabIndex'] = 1;
                                $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                                $atributos ['estilo'] = 'textoPequenno textoGris';
                                $atributos ['enlaceImagen'] = $rutaBloque."/images/asociar.png";
                                $atributos ['posicionImagen'] = "atras";//"adelante";
                                $atributos ['ancho'] = '45px';
                                $atributos ['alto'] = '45px';
                                $atributos ['redirLugar'] = true;
                                echo $this->miFormulario->enlace ( $atributos );
                                unset ( $atributos );
                echo "            </td>
                        </tr>
                      </table></div> ";

                if($resultadoUsuarios)
                {	
                    //-----------------Inicio de Conjunto de Controles----------------------------------------
                        $esteCampo = "marcoConsultaUsuarios";
                        $atributos["estilo"] = "jqueryui";
                        $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                        //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                        unset($atributos);

                        echo "<div class='cell-border'><table id='tablaProcesos'>";

                        echo "<thead>
                                <tr align='center'>
                                    <th>Identificación</th>
                                    <th>Tipo</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>                    
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Usuario</th>
                                    <th>Roles</th>
                                    <th>Estado</th>
                                    <th>Tipo Usuario</th>
                                    <th>Editar</th>
                                    <th>Actualizar Roles</th>
                                    <th>Actualizar Estado</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>";

                        foreach($resultadoUsuarios as $key=>$value )
                            { 
                                $parametro['id_usuario']=$resultadoUsuarios[$key]['id_usuario'];
                                $parametro['tipo']='unico';
                                $cadena_sql = $this->miSql->getCadenaSql("consultarPerfilUsuario", $parametro);
                                $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                                //var_dump($resultadoPerfil);
                                $perfil[$key]['perfil']='';
                                if($resultadoPerfil)
                                    {$cambio='SI';
                                        foreach ($resultadoPerfil as $Pkey => $value) {
                                           $perfil[$key]['perfil'].=$resultadoPerfil[$Pkey]['rol_alias']."<br>";
                                           if($resultadoPerfil[$Pkey]['rol_id']==0 && $rol[0]!=0)
                                               {$cambio='NO';}
                                           
                                        }
                                    }
                                else{$perfil[$key]['perfil']='N/A';}
                                $variableEditar = "pagina=gestionUsuarios"; //pendiente la pagina para modificar parametro                                                        
                                $variableEditar.= "&opcion=editar";
                                $variableEditar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                $variableEditar.= "&id_usuario=" .$resultadoUsuarios[$key]['id_usuario'];
                                $variableEditar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                $variableEditar.= "&tiempo=" . time ();

                                $variableEditar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEditar, $directorio);
                                //enlace actualizar estado
                                $variableEstado = "pagina=gestionUsuarios"; //pendiente la pagina para modificar parametro      
                                if($resultadoUsuarios[$key]['estado']=='Activo')
                                    {$variableEstado.= "&opcion=inhabilitar";}
                                else{$variableEstado.= "&opcion=habilitar";}    

                                $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                $variableEstado.= "&id_usuario=" .$resultadoUsuarios[$key]['id_usuario'];
                                $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                $variableEstado.= "&tiempo=" . time ();
                                $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);

                                //enlace actualizar perfil
                                $variablePerfil = "pagina=gestionUsuarios"; //pendiente la pagina para modificar parametro      
                                $variablePerfil.= "&opcion=perfil";    
                                $variablePerfil.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                $variablePerfil.= "&id_usuario=" .$resultadoUsuarios[$key]['id_usuario'];
                                $variablePerfil = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variablePerfil, $directorio);

                                //enlace actualizar perfil
                                $variableBorrar = "pagina=gestionUsuarios"; //pendiente la pagina para modificar parametro      
                                $variableBorrar.= "&opcion=borrar";    
                                $variableBorrar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                $variableBorrar.= "&id_usuario=" .$resultadoUsuarios[$key]['id_usuario'];
                                $variableBorrar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableBorrar, $directorio);

                                $mostrarHtml = "<tr align='center'>
                                        <td align='left'>".$resultadoUsuarios[$key]['identificacion']."</td>
                                        <td align='left'>".$resultadoUsuarios[$key]['tipo_nombre']."</td>
                                        <td align='left' width='8%'>".$resultadoUsuarios[$key]['nombre']."</td>
                                        <td align='left' width='8%'>".$resultadoUsuarios[$key]['apellido']."</td>
                                        <td align='left'>".$resultadoUsuarios[$key]['correo']."</td>
                                        <td align='left'>".$resultadoUsuarios[$key]['telefono']."</td>
                                        <td align='left'>".$resultadoUsuarios[$key]['id_usuario']."</td>
                                        <td align='left' width='12%'>".$perfil[$key]['perfil']."</td>
                                        <td>".$resultadoUsuarios[$key]['estado']."</td>    
                                        <td>".$resultadoUsuarios[$key]['nivel']."</td>
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
                                $mostrarHtml .= "</td>
                                                <td>";
                                                $esteCampo = "perfil";
                                                $atributos["id"]=$esteCampo;
                                                $atributos['enlace']=$variablePerfil;
                                                $atributos['tabIndex']=$esteCampo;
                                                $atributos['redirLugar']=true;
                                                $atributos['estilo']='clasico';
                                                $atributos['enlaceTexto']='';
                                                $atributos['ancho']='25';
                                                $atributos['alto']='25';
                                                $atributos['enlaceImagen']=$rutaBloque."/images/folder_user.png";
                                                $mostrarHtml .= $this->miFormulario->enlace($atributos);
                                                unset($atributos);    
                                $mostrarHtml .= "</td>
                                                <td>";

                                        if($resultadoUsuarios[$key]['estado']=='Activo' && $cambio=='SI')
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
                                        elseif($resultadoUsuarios[$key]['estado']!='Activo' && $cambio=='SI')
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

                               $mostrarHtml .= "</td><td>";
                                        if($cambio=='SI')
                                            {    
                                                $esteCampo = "borrar";
                                                $atributos["id"]=$esteCampo;
                                                $atributos['enlace']=$variableBorrar;
                                                $atributos['tabIndex']=$esteCampo;
                                                $atributos['redirLugar']=true;
                                                $atributos['estilo']='clasico';
                                                $atributos['enlaceTexto']='';
                                                $atributos['ancho']='25';
                                                $atributos['alto']='25';
                                                $atributos['enlaceImagen']=$rutaBloque."/images/trash.png";
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

                }else
                {
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
