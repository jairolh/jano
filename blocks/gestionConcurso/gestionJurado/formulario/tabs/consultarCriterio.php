<?php
use gestionConcurso\gestionJurado\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

class consultarCriterio {
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

			$parametro=array('id_jurado_tipo'=>$_REQUEST['id_tipoJurado']);
      $cadena_sql = $this->miSql->getCadenaSql("consultarCriteriosTipoJurado", $parametro);
      $resultadoCriterio = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

      $esteCampo = "marcoListaCriterio";
      $atributos ['id'] = $esteCampo;
      $atributos ["estilo"] = "jqueryui";
      $atributos ['tipoEtiqueta'] = 'inicio';
      $atributos ["leyenda"] = "".$this->lenguaje->getCadena ( $esteCampo ).": <b>". $_REQUEST['nombre_tipoJurado']."</b>";
      echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );

      unset ( $atributos );
    			{

                echo "<div ><table width='100%' align='center'>
                        <tr align='center'>
                            <td align='center'>";
                                $esteCampo = 'nuevoCriterio';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['enlace'] = "#";//$variableNuevo;
                                $atributos ['onClick'] ="show(\"marcoCriterios\")";
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
                echo "    </td>
                        </tr>
                      </table></div> ";

                    if($resultadoCriterio)
                        {
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoConsultaCriterio";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
                                echo "<div class='cell-border'><table id='tablaConsultaCriterio' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center'>
                                            <th>Tipo Jurado</th>
                                            <th>Criterio</th>
                                            <th>Estado</th>
                                            <th>Actualizar Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoCriterio as $key=>$value )
                                    {

                                        //enlace actualizar estado
                                        $variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                        if($resultadoCriterio[$key]['estado']=='A')
                                            {$variableEstado.= "&opcion=inhabilitarCriterio";}
                                        else{$variableEstado.= "&opcion=habilitarCriterio";}
                                        $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEstado.= "&id_tipo=".$resultadoCriterio[$key]['id_tipo'];
																				$variableEstado.= "&nombre_tipo=" .$resultadoCriterio[$key]['tipo'];
                                        $variableEstado.= "&id_criterio=".$resultadoCriterio[$key]['id_criterio'];
                                        $variableEstado.= "&nombre_criterio=" .$resultadoCriterio[$key]['criterio'];

                                        $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEstado.= "&tiempo=" . time ();
                                        $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);

																				if($resultadoCriterio[$key]['estado']=='A'){
				                                	$resultadoCriterio[$key]['estado']="Activo";
				                                }else{
				                                	$resultadoCriterio[$key]['estado']="Inactivo";
				                                }

                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoCriterio[$key]['tipo']."</td>
                                                <td align='left'>".$resultadoCriterio[$key]['criterio']."</td>
                                                <td align='left'>".$resultadoCriterio[$key]['estado']."</td>";

                                         $mostrarHtml .= "<td>";

                                            if($resultadoCriterio[$key]['estado']=='Activo')
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
                                $atributos["id"]="divNoEncontroCriterio";
                                $atributos["estilo"]="";
                           			//$atributos["estiloEnLinea"]="display:none";
                                echo $this->miFormulario->division("inicio",$atributos);

                                //-------------Control Boton-----------------------
                                $esteCampo = "noEncontroCriterios";
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

$miSeleccionador = new consultarCriterio ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
