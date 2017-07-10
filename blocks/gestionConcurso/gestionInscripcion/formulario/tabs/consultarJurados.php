<?php
use gestionConcurso\gestionInscripcion\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarJurado {
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

            $parametro=array('consecutivo_concurso'=>$_REQUEST['consecutivo_concurso']);
            $cadena_sql = $this->miSql->getCadenaSql("consultarInscritoConcurso", $parametro);
            $resultadoListaInscrito = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

            //var_dump($resultadoListaInscrito);
            $esteCampo = "marcoListaJurados";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>".$this->lenguaje->getCadena ( $esteCampo )."</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );

            unset ( $atributos );
                {
                   if($resultadoListaInscrito)
                        {
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoConsultaInscrito";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
																$tab=1;

																// ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
																$esteCampo = 'seleccionJurado';
																$atributos ['columnas'] = 1;
																$atributos ['nombre'] = $esteCampo;
																$atributos ['id'] = $esteCampo;
																$atributos ['evento'] = '';
																$atributos ['deshabilitado'] = false;
																$atributos ["etiquetaObligatorio"] = true;
																$atributos ['tab'] = $tab;
																$atributos ['tamanno'] = 1;
																$atributos ['estilo'] = 'jqueryui';
																$atributos ['validar'] = 'required';
																$atributos ['limitar'] = true;
																$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
																$atributos ['anchoEtiqueta'] = 170;
																$atributos ['anchoCaja'] = 60;
																if (isset ( $_REQUEST [$esteCampo] ))
																{$atributos ['seleccion'] = $_REQUEST [$esteCampo];}
																else {	$atributos ['seleccion'] = -1;}
																$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarJurados" );
																$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
																$atributos ['matrizItems'] = $matrizItems;
																// Utilizar lo siguiente cuando no se pase un arreglo:
																// $atributos['baseDatos']='ponerAquiElNombreDeLaConexión';
																// $atributos ['cadena_sql']='ponerLaCadenaSqlAEjecutar';
																$tab ++;
																$atributos = array_merge ( $atributos, $atributosGlobales );
																echo $this->miFormulario->campoCuadroLista ( $atributos );
																unset ( $atributos );
																// ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------

																// ---------------- CONTROL: Cuadro de Lista --------------------------------------------------------
																$esteCampo = 'tipoJurado';
																$atributos ['columnas'] = 1;
																$atributos ['nombre'] = $esteCampo;
																$atributos ['id'] = $esteCampo;
																$atributos ['evento'] = '';
																$atributos ['deshabilitado'] = false;
																$atributos ["etiquetaObligatorio"] = true;
																$atributos ['tab'] = $tab;
																$atributos ['tamanno'] = 1;
																$atributos ['estilo'] = 'jqueryui';
																$atributos ['validar'] = 'required';
																$atributos ['limitar'] = true;
																$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
																$atributos ['anchoEtiqueta'] = 170;
																$atributos ['anchoCaja'] = 60;
																if (isset ( $_REQUEST [$esteCampo] ))
																{$atributos ['seleccion'] = $_REQUEST [$esteCampo];}
																else {	$atributos ['seleccion'] = -1;}
																$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTiposJurado" );
																$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
																$atributos ['matrizItems'] = $matrizItems;
																// Utilizar lo siguiente cuando no se pase un arreglo:
																// $atributos['baseDatos']='ponerAquiElNombreDeLaConexión';
																// $atributos ['cadena_sql']='ponerLaCadenaSqlAEjecutar';
																$tab ++;
																$atributos = array_merge ( $atributos, $atributosGlobales );
																echo $this->miFormulario->campoCuadroLista ( $atributos );
																unset ( $atributos );
																// ---------------- FIN CONTROL: Cuadro de Lista --------------------------------------------------------

																$esteCampo = "marcoListaAspirantes";
																$atributos ['id'] = $esteCampo;
																$atributos ["estilo"] = "jqueryui";
																$atributos ['tipoEtiqueta'] = 'inicio';
																$atributos ["leyenda"] = "<b>".$this->lenguaje->getCadena ( $esteCampo )."</b>";
																echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );

																$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarAspirantesValidados" );
																$aspirantes = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
																//var_dump($aspirantes);

																//listado con check de los aspirantes
																echo "<div class='cell-border'><table id='tablaConsultaAspirante' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center'>
																						<th>Seleccionar</th>
                                            <th>Consecutivo</th>
                                            <th>Tipo Identificación</th>
                                            <th>Identificación</th>
                                            <th>Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
																foreach($aspirantes as $key=>$value ){

																	$mostrarHtml = "<tr align='center'>
																					<td align='left'>";

																					// ---------------- CONTROL: Checkbox -----------
																					$esteCampo = 'autorizacion';
																					$atributos ['id'] = $esteCampo;
																					$atributos ['nombre'] = $esteCampo;
																					$atributos ["etiquetaObligatorio"] = false;
																					$atributos ['columnas'] = 2;
																					$atributos ['tab'] = $tab ++;
																					$atributos ['anchoEtiqueta'] = 2;
																					$atributos ['etiqueta'] = "";
																					$atributos ['seleccionado'] = false;
																					//$atributos ['evento'] = ' ';
																					$atributos ['estilo'] = 'justificado';
																					$atributos ['eventoFuncion'] = ' ';
																					$atributos ['validar'] = 'required';

																					//$atributos ['valor'] = '';
																					$atributos = array_merge ( $atributos, $atributosGlobales );
																					$mostrarHtml .= $this->miFormulario->campoCuadroSeleccion ( $atributos );



																		$mostrarHtml.="</td>";


																	$mostrarHtml .= "<td align='left'>".$aspirantes[$key]['consecutivo']."</td>
																					<td align='left'>".$aspirantes[$key]['tipo_identificacion']."</td>
																					<td align='left'>".$aspirantes[$key]['identificacion']."</td>
																					<td align='left'>".$aspirantes[$key]['nombre']."</td>";
																	$mostrarHtml .= "</tr>";
																	echo $mostrarHtml;
																	unset($mostrarHtml);
																	unset($variable);
																}
																echo "</tbody>";
                                echo "</table></div>";

																echo $this->miFormulario->marcoAgrupacion ( 'fin' );
										            unset ( $atributos );


                        }else
                        {
                                $atributos["id"]="divNoEncontroInscrito";
                                $atributos["estilo"]="";
                           //$atributos["estiloEnLinea"]="display:none";
                                echo $this->miFormulario->division("inicio",$atributos);

                                //-------------Control Boton-----------------------
                                $esteCampo = "noEncontroInscrito";
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

$miSeleccionador = new consultarJurado ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
