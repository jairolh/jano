<?php
namespace gestionConcursante\concursosActivos;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

class consultarForm {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;

	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miFormulario = $formulario;
		$this->miSql = $sql;
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
            $valorCodificado .= "&opcion=detalleConcurso";
            $valorCodificado.= "&campoSeguro=" . $_REQUEST ['tiempo'];
            $valorCodificado.= "&tiempo=" . time ();

            /**
             * SARA permite que los nombres de los campos sean dinámicos.
             * Para ello utiliza la hora en que es creado el formulario para
             * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
             * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
             * (b) asociando el tiempo en que se está creando el formulario
             */
            
            //fecha
            $parametro['fecha_actual'] = date("Y-m-d");
            $cadena_sql = $this->miSql->getCadenaSql("consultaConcursosActivos", $parametro);
            $resultadoConcursosActivos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
           
            $esteCampo = "marcoDatosBasicos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "<b>Concursos Activos</b>";
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {


                if($resultadoConcursosActivos)
                {
                    //-----------------Inicio de Conjunto de Controles----------------------------------------
                        $esteCampo = "marcoConsultaConcurso";
                        $atributos["estilo"] = "jqueryui";
                        $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                        //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                        unset($atributos);

                    echo "<div class='cell-border'>";
                    echo "<table id='tablaConcursos' class='table table-striped table-bordered'>";

                        echo "<thead>
                                <tr align='center'>
                                  <th>Código</th>
                        	  <th>Concurso</th>
                        	  <th>Descripción</th>
                                  <th>Estado</th>
			  	  <th>Duración</th>
			  	  <th>Inscripciones</th>
                        	  <th>Detalle</th>
                                </tr>
                            </thead>
                            <tbody>";

                        foreach($resultadoConcursosActivos as $key=>$value )
                            {
                            	//enlace para consultar los criterios asociados al tipo de jurado
                            	$variableDetalle = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
                            	$variableDetalle.= "&opcion=detalleConcurso";
                            	//$variableDetalle.= "&usuario=" . $this->miSesion->getSesionUsuarioId();
                            	$variableDetalle.= "&id_concurso=" .$resultadoConcursosActivos[$key]['consecutivo_concurso'];
                            	$variableDetalle.= "&campoSeguro=" . $_REQUEST ['tiempo'];
                            	$variableDetalle.= "&tiempo=" . time ();
                            	$variableDetalle = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableDetalle, $directorio);

                                if($resultadoConcursosActivos[$key]['estado']=='A'){
                                        $resultadoConcursosActivos[$key]['estado']="Activo";
                                }else{
                                        $resultadoConcursosActivos[$key]['estado']="Inactivo";
                                }

                                $mostrarHtml = "<tr align='center'>
                                        <td align='left'>".$resultadoConcursosActivos[$key]['codigo']."</td>
                                        <td align='left'>".$resultadoConcursosActivos[$key]['nombre']."</td>
                                        <td align='left'>".$resultadoConcursosActivos[$key]['descripcion']."</td>
                                        <td align='left'>".$resultadoConcursosActivos[$key]['estado']."</td>
                                        <td align='left'>".$resultadoConcursosActivos[$key]['fecha_inicio']." - ".$resultadoConcursosActivos[$key]['fecha_fin']."</td>
                                        <td align='left'>Del ".$resultadoConcursosActivos[$key]['inicio_inscripcion']." al ".$resultadoConcursosActivos[$key]['fin_inscripcion']."</td>
                                ";
                                $mostrarHtml .= "<td>";
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
                                $mostrarHtml .= "</td>";
                               $mostrarHtml .= "</tr>";
                               echo $mostrarHtml;
                               unset($mostrarHtml);
                               unset($variable);
                            }
                        echo "</tbody>";
                        echo "</table></div>";
                        //echo $this->miFormulario->marcoAgrupacion("fin");
                    }
                else{
                        $atributos["id"]="divNoEncontroConcurso";
                        $atributos["estilo"]="";
                        //$atributos["estiloEnLinea"]="display:none";
                        echo $this->miFormulario->division("inicio",$atributos);

                        //-------------Control Boton-----------------------
                        $esteCampo = "noEncontroConcursosActivos";
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

$miSeleccionador = new consultarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
