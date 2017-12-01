<?php
use gestionPublicacion\funcion\redireccion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class consultarProfesional {
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
            $atributosGlobales ['campoSeguro'] = 'true';
            $_REQUEST ['tiempo'] = time ();
            // -------------------------------------------------------------------------------------------------
            //$conexion="estructura";
            $conexion="reportes";
            $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
                //identifca lo roles para la busqueda de subsistemas
            $parametro=array('id_usuario'=>$_REQUEST['id_usuario'],
                             'tipo_dato'=>'datosExperiencia');    
            $cadena_sql = $this->miSql->getCadenaSql("consultarExperiencia", $parametro);
            $resultadoProfesional = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

            $esteCampo = "marcoProfesional";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = "".$this->lenguaje->getCadena ( $esteCampo )."";
            
            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            unset ( $atributos );
                {
                    if($resultadoProfesional)
                        {     
                            //-----------------Inicio de Conjunto de Controles----------------------------------------
                                $esteCampo = "marcoProfesional";
                                $atributos["estilo"] = "jqueryui";
                                $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                                //echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                                unset($atributos);
                                echo "<div class='cell-border'><table id='tablaProcesos' class='table table-striped table-bordered'>";
                                echo "<thead>
                                        <tr align='center' class='textoAzul'>
                                            <th>Pais</th>
                                            <th>Ingreso</th>                                            
                                            <th>Terminación</th>                                            
                                            <th>Cargo</th>
                                            <th>Actividades</th>
                                            <th>Institución</th>
                                            <th>Tipo</th>
                                            <th>Telefono</th>
                                            <th>Correo</th>
                                            <th>Certificación</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                foreach($resultadoProfesional as $key=>$value )
                                    {   $datos='';//json_decode ($resultadoProfesional[$key]['valor_dato']);	
                                        if(isset($datos->soportes) && $datos->soportes!='')
                                            {
                                            foreach ($datos->soportes as  $value) {
                                                if(isset($value->tipo_soporte) && $value->tipo_soporte=='soporteExperiencia' ){
                                                  $resultadoSop=array('ruta'=> $this->rutaSoporte.$value->nombre_soporte, 'alias'=> $value->alias_soporte,'consecutivo_soporte'=> $value->consecutivo_soporte,);
                                                  }
                                              }
                                            }
                                     
                                        $mostrarHtml = "<tr align='center'>
                                                <td align='left'>".$resultadoProfesional[$key]['pais']."</td>
                                                <td align='left'>".$resultadoProfesional[$key]['fecha_inicio']."</td>
                                                <td align='left'>".$resultadoProfesional[$key]['fecha_fin']."</td>
                                                <td align='left'>".$resultadoProfesional[$key]['cargo']."</td>
                                                <td align='left'>".$resultadoProfesional[$key]['descripcion_cargo']."</td>
                                                <td align='left'>".$resultadoProfesional[$key]['nombre_institucion']."</td>
                                                <td align='left'>".$resultadoProfesional[$key]['nivel_institucion']."</td>
                                                <td align='left'>".$resultadoProfesional[$key]['telefono_institucion']."</td>
                                                <td align='left'>".$resultadoProfesional[$key]['correo_institucion']."</td>";
                                        
                                        $mostrarHtml .= "<td> ";
                                                if(isset($resultadoSop['alias']))
                                                    {
                                                      $esteCampo = 'archivoSoporte'.$resultadoSop['consecutivo_soporte'];
                                                      $atributos ['id'] = $esteCampo;
                                                      $atributos ['enlace'] = 'javascript:soporte("ruta_diploma'.$resultadoSop['consecutivo_soporte'].'");';
                                                      $atributos ['tabIndex'] = 0;
                                                      $atributos ['columnas'] = 1;
                                                      $atributos ['enlaceTexto'] = $resultadoSop['alias'];
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
                                                      $esteCampo = 'ruta_diploma'.$resultadoSop['consecutivo_soporte'];
                                                      $atributos ['id'] = $esteCampo;
                                                      $atributos ['nombre'] = $esteCampo;
                                                      $atributos ['tipo'] = 'hidden';
                                                      $atributos ['etiqueta'] = "";//$this->lenguaje->getCadena ( $esteCampo );
                                                      $atributos ['obligatorio'] = false;
                                                      $atributos ['valor'] = $resultadoSop['ruta'];
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
                                       unset($resultadoDip);
                                       unset($resultadoTarjeta);
                                    }
                                echo "</tbody>";
                                echo "</table></div>";
                                //Fin de Conjunto de Controles

                        }else
                        {
                                $atributos["id"]="divNoEncontroProfesional";
                                $atributos["estilo"]="";
                           //$atributos["estiloEnLinea"]="display:none"; 
                                echo $this->miFormulario->division("inicio",$atributos);

                                //-------------Control Boton-----------------------
                                $esteCampo = "noEncontroProfesional";
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
            echo $this->miFormulario->marcoAgrupacion ( 'fin');
            unset ( $atributos );
    }
}

$miSeleccionador = new consultarProfesional ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
