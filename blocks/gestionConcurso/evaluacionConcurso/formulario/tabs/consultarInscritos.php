<?php
use gestionConcurso\evaluacionConcurso\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarInscrito {
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
            $esteCampo = "marcoListaInscrito";
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
                                echo "<div class='cell-border'><table id='tablaConsultaInscrito' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center'>

                                            <th>Tipo Identificación</th>
                                            <th>Identificación</th>
                                            <th>Nombre</th>
																						<th>Perfil</th>
                                            <th>Inscripción</th>
                                            <th>Fecha</th>
                                            <th>Validar Requisitos</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoListaInscrito as $key=>$value )
                                    {   $parametro['tipo']='unico';
                                        /*
                                        $parametroSop = array('consecutivo'=>$resultadoListaInscrito[$key]['consecutivo_persona'],
                                             'tipo_dato'=>'datosInscrito',
                                             'nombre_soporte'=>'soporteInscrito',
                                             'consecutivo_dato'=>$resultadoListaInscrito[$key]['consecutivo_actividad']
                                            );
                                        $cadenaSop_sql = $this->miSql->getCadenaSql("buscarSoporte", $parametroSop);
                                        $resultadoSact = $esteRecursoDB->ejecutarAcceso($cadenaSop_sql, "busqueda");*/
                                        $variableEditar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                        $variableEditar.= "&opcion=validar";
                                        $variableEditar.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEditar.= "&id_usuario=" .$_REQUEST['usuario'];
                                        $variableEditar.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEditar.= "&tiempo=" . time ();
                                        $variableEditar .= "&consecutivo_concurso=".$resultadoListaInscrito[$key]['consecutivo_concurso'];
                                        $variableEditar .= "&consecutivo_perfil=".$resultadoListaInscrito[$key]['consecutivo_perfil'];
                                        $variableEditar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEditar, $directorio);
                                        $variableEditar.= "#tabInscrito";

                                        //enlace actualizar estado
                                        $variableEstado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                                        if($resultadoListaInscrito[$key]['estado']=='A')
                                            {$variableEstado.= "&opcion=inhabilitarInscrito";}
                                        else{$variableEstado.= "&opcion=habilitarInscrito";}
                                        $variableEstado.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                                        $variableEstado.= "&consecutivo_concurso=".$resultadoListaInscrito[$key]['consecutivo_concurso'];
                                        $variableEstado.= "&consecutivo_perfil=".$resultadoListaInscrito[$key]['consecutivo_perfil'];
                                        $variableEstado.= "&nombre=" .$resultadoListaInscrito[$key]['nombre'];
                                        $variableEstado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                                        $variableEstado.= "&tiempo=" . time ();
                                        $variableEstado = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableEstado, $directorio);

                                        $mostrarHtml = "<tr align='center'>

                                                <td align='left'>".$resultadoListaInscrito[$key]['tipo_identificacion']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['identificacion']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['nombre']." ".$resultadoListaInscrito[$key]['apellido']."</td>
																								<td align='left'>".$resultadoListaInscrito[$key]['perfil']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['consecutivo_inscrito']."</td>
                                                <td align='left'>".$resultadoListaInscrito[$key]['fecha_registro']."</td>";
                                        $mostrarHtml .= "<td>";
                                            if($resultadoListaInscrito[$key]['estado']==0)
                                                {
                                                //-------------Enlace-----------------------
                                                    $esteCampo = "validar";
                                                    $atributos["id"]=$esteCampo;
                                                    $atributos['enlace']=$variableEditar;
                                                    $atributos['tabIndex']=$esteCampo;
                                                    $atributos['redirLugar']=true;
                                                    $atributos['estilo']='clasico';
                                                    $atributos['enlaceTexto']='';
                                                    $atributos['ancho']='30';
                                                    $atributos['alto']='30';
                                                    $atributos['enlaceImagen']=$rutaBloque."/images/check_file.png";
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

$miSeleccionador = new consultarInscrito ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
