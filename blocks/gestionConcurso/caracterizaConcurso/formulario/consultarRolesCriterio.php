<?php
namespace gestionConcurso\caracterizaConcurso;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

class consultarFormRoles {
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

            //enlace regresar        
            $variable = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
            $variable.= "&opcion=gestionCriterio";    
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
				
            
            //enlace nuevo registro    
   	    $valorCodificado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
            $valorCodificado .= "&opcion=nuevoRol";
            $valorCodificado .= "&usuario=" . $this->miSesion->getSesionUsuarioId();
            $valorCodificado .= "&id_criterio=" .$_REQUEST['id_criterio'];
            $valorCodificado .= "&factor=" .$_REQUEST['factor'];
            $valorCodificado .= "&criterio=" .$_REQUEST['criterio'];

            $valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
            $valorCodificado .= "&tiempo=" . time ();
            // Paso 2: codificar la cadena resultante
            $variableNuevo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($valorCodificado, $directorio);

            $cadena_sql = $this->miSql->getCadenaSql("consultaRolCriterio", $_REQUEST['id_criterio']);
            $resultadoRoles = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

            $esteCampo = "marcoDatosBasicos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>Gestión de roles por criterio</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {
                // ---------------- CONTROL: Cuadro enlace --------------------------------------------------------
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
                echo $this->miFormulario->enlace ( $atributos )."<br>";
                unset ( $atributos );
                // ---------------- FIN CONTROL: enlace --------------------------------------------------------
                echo "<div ><table width='100%' align='center'>
                        <tr align='center'>
                            <td align='center'>";
                                $esteCampo = 'registrarRol';
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
                                echo $this->miFormulario->enlace ( $atributos );
                                unset ( $atributos );
                echo "            </td>
                        </tr>
                      </table></div> ";
                
            if($resultadoRoles)
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
                                    <th>Factor</th>
                 		    <th>Criterio</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Actualizar Estado</th>
                                </tr>
                            </thead>
                            <tbody>";

                        foreach($resultadoRoles as $key=>$value )
                            {
                                //enlace actualizar estado
                                $variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                if($resultadoRoles[$key]['estado']=='A')
                                    {$variableEstado.= "&opcion=inhabilitarCevaluacion";}
                                else{$variableEstado.= "&opcion=habilitarCevaluacion";}
                                $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                $variableEstado.= "&id_Cevaluacion=" .$resultadoRoles[$key]['id'];
                                $variableEstado.= "&id_criterio=" .$resultadoRoles[$key]['id_criterio'];
                                $variableEstado.= "&factor=" .$_REQUEST['factor'];
                                $variableEstado.= "&criterio=" .$_REQUEST['criterio'];
                                $variableEstado.= "&rol=" .$resultadoRoles[$key]['rol_alias'];
                                $variableEstado.= "&estado=" .$resultadoRoles[$key]['estado'];
                                $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                $variableEstado.= "&tiempo=" . time ();
                                $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);

                                if($resultadoRoles[$key]['estado']=='A'){
                                	$resultadoRoles[$key]['estado']="Activo";
                                }else{
                                	$resultadoRoles[$key]['estado']="Inactivo";
                                }
                            
                                $mostrarHtml = "<tr align='center'>
                                        <td align='left'>".$_REQUEST['factor']."</td>
                                        <td align='left'>".$_REQUEST['criterio']."</td>
                                        <td align='left'>".$resultadoRoles[$key]['rol_alias']."</td>
                                        <td align='left'>".$resultadoRoles[$key]['estado']."</td>";

                       		$mostrarHtml .= "<td >";
                                        if($resultadoRoles[$key]['estado']=='Activo')
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
                        $atributos["id"]="divNoEncontroRoles";
                        $atributos["estilo"]="";
                        //$atributos["estiloEnLinea"]="display:none";
                        echo $this->miFormulario->division("inicio",$atributos);

                        //-------------Control Boton-----------------------
                        $esteCampo = "noEncontroRoles";
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

            echo $this->miFormulario->marcoAgrupacion ( 'fin' );

            // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
            // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
            // Se debe declarar el mismo atributo de marco con que se inició el formulario.
        }
        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division ( "fin" );

    }
}

$miSeleccionador = new consultarFormRoles ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
