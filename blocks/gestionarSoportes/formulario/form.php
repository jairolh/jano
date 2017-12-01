<?php 

//namespace gestionarSoportes\formulario;

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

class Formulario {

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

		$this->miSesion = \Sesion::singleton ();
    }

    function formulario() {

        
        
        /**
         * IMPORTANTE: Este formulario está utilizando jquery.
         * Por tanto en el archivo ready.php se delaran algunas funciones js
         * que lo complementan.
         */

        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
        $rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
        $rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];
        
        $directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
        $directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
        $directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
        
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
        $_REQUEST['tiempo']=time();
        
        $conexion = 'novedades';
        $esteRecurso = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
        // -------------------------------------------------------------------------------------------------

        // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
        $esteCampo = $esteBloque ['nombre'];
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;

        // Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
        $atributos ['tipoFormulario'] = '';

        // Si no se coloca, entonces toma el valor predeterminado 'POST'
        $atributos ['metodo'] = 'POST';

        // Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
        $atributos ['action'] = 'index.php';
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );

        // Si no se coloca, entonces toma el valor predeterminado.
        $atributos ['estilo'] = '';
        $atributos ['marco'] = true;
        $tab = 1;
        // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------

        ?>
        
        <br>
<div class="container">		
	<div class="panel panel-default">
      
        <div class="panel-body">					
			<div class="row">
			
        <?php
        
        // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos ['tipoEtiqueta'] = 'inicio';
        echo $this->miFormulario->formulario ( $atributos );

        // ---------------- SECCION: Controles del Formulario -----------------------------------------------
        $parametro['busca']=$_REQUEST['TipoBusqueda'];
        $parametro['documento']=$_REQUEST['documento'];
        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarNovedades",$parametro );
        $matrizNovedades = $esteRecurso->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

        //var_dump($matrizNovedades);
  if ( $matrizNovedades) {
        
        $valorCodificado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
        $valorCodificado .= "&opcion=nuevo";
        $valorCodificado .= "&codigo=" . $matrizNovedades[0]['CODIGO'];
        $valorCodificado .= "&nombre=" . $matrizNovedades[0]['NOMBRE'];
        $valorCodificado .= "&identificacion=" . $matrizNovedades[0]['IDENTIFICACION'];
        $valorCodificado .= "&cargo=" . $matrizNovedades[0]['CARGO'];
        $valorCodificado .= "&dependencia=" . $matrizNovedades[0]['DEPENDENCIA'];
        $valorCodificado .= "&sueldo=" . $matrizNovedades[0]['SUELDO_BASICO']; 
        
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
        $variableNuevo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $valorCodificado, $directorio );
        
        ?>
<div>
	<table width='100%' align='center'>
		<tr align='center'>
                    <td align='center'><strong> CÓDIGO </strong></td>
                    <td align='center'><strong> NOMBRE </strong></td>
                    <td align='center'><strong> IDENTIFICACIÓN </strong></td>
		</tr>
		<tr align='center'>
			<td align='center'>
			<?php echo $matrizNovedades[0]['CODIGO']; ?>
			</td>
                        <td align='center'>
			<?php echo $matrizNovedades[0]['NOMBRE']; ?>
			</td>
                        <td align='center'>
			<?php echo $matrizNovedades[0]['IDENTIFICACION']; ?>
			</td>
		</tr> 
                <tr align='center'>
                    <td colspan="3">&nbsp;</td>
		</tr>       
		<tr align='center'>
                    <td align='center'><strong> CARGO </strong></td>
                    <td align='center'><strong> DEPENDENCIA </strong></td>
                    <td align='center'><strong> SUELDO BÁSICO </strong></td>
		</tr>
		<tr align='center'>
			<td align='center'>
			<?php echo $matrizNovedades[0]['CARGO']; ?>
			</td>
                        <td align='center'>
			<?php echo $matrizNovedades[0]['DEPENDENCIA']; ?>
			</td>
                        <td align='center'>
			<?php echo number_format($matrizNovedades[0]['SUELDO_BASICO'], 2, '.', ','); ?>
			</td>
		</tr>        
                <tr align='center'>
                    <td colspan="3">&nbsp;</td>
		</tr>                       
                <tr align='center'>
			<td align='right' colspan="3">
			<?php 
			$esteCampo = 'nueva';
			$atributos ['id'] = $esteCampo;
			$atributos ['enlace'] = $variableNuevo;
			$atributos ['tabIndex'] = 1;
			$atributos ['enlaceTexto'] = '<br><b>' . $this->lenguaje->getCadena ( $esteCampo ) . '</b>';
			$atributos ['estilo'] = 'textoPequenno textoGris';
			$atributos ['enlaceImagen'] = $rutaBloque . "/images/new.png";
			$atributos ['posicionImagen'] = "atras"; // "adelante";
			$atributos ['ancho'] = '35px';
			$atributos ['alto'] = '35px';
			$atributos ['redirLugar'] = true;
			echo $this->miFormulario->enlace ( $atributos );
			unset ( $atributos );
			?>
			</td>
		</tr>
	</table>
</div>

		<?php 
		$valorCodificado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		$valorCodificado .= "&opcion=consultar";
		$valorCodificado .= "&usuario=''";
		$valorCodificado .= "&jquery=true";
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
		$variableConsultar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $valorCodificado, $directorio );
		
    

        	// -----------------Inicio de Conjunto de Controles----------------------------------------
        	$esteCampo = "marcoConsultaNovedad";
        	$atributos ["estilo"] = "jqueryui";
        	$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
        	// echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
        	unset ( $atributos );
        	
        	?>
<div class='cell-border'>
	<table id='tablaNovedades' class="table table-striped table-bordered">
		<thead>
                   <tr align='center'>
	            <th>Estado</th>
	            <th>Tipo</th>
	            <th>Código</th>
	            <th>Concepto</th>
	            <th>Secuencia</th>
	            <th>Unidad</th>
	            <th>Valor</th>
	            <th>Cuotas</th>
	            <th>Periodo</th>
	            <th>Fecha</th>
	            <th>Editar</th>
	           </tr>
		</thead>
		<tbody>
		<?php 
			foreach ($matrizNovedades AS $novedad) {
                                $tipo=$novedad['TIPO']==1?'Pago':'Descuento';    
				$mostrarHtml = "<tr align='center'>
                                    <td align='center'>" . $novedad['ESTADO'] . "</td>
                                    <td align='center'>" . $tipo."</td>
                                    <td align='center'>" . $novedad['CODNOVEDAD'] . "</td>
                                    <td align='left'>" . $novedad['CONCEPTO'] . "</td>
                                    <td align='center'>" . $novedad['SECUENCIA'] . "</td>
                                    <td align='center'>" . $novedad['UNIDAD'] . "</td>
                                    <td align='right'>" .number_format((float)$novedad['VALOR'], 2, '.', ','). "</td>
                                    <td align='center'>" . $novedad['CUOTAS'] . "</td>
         			    <td align='center'>" . $novedad['PERIODO'] . "</td>
                                    <td align='center'>" . $novedad['FECHA'] . "</td>    
                                    <td>";
				
				//enlace editar notificacion
				$variableEditar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );; 
				$variableEditar .= "&opcion=editar";
				$variableEditar .= "&usuario=''";
				$variableEditar .= "&campoSeguro=" . $_REQUEST ['tiempo'];
				$variableEditar .= "&tiempo=" . time ();
                                $variableEditar .= "&tipo=" . $novedad['TIPO'];
                                $variableEditar .= "&codnovedad=" . $novedad['CODNOVEDAD'];
                                $variableEditar .= "&secuencia=" . $novedad['SECUENCIA'];
                                $variableEditar .= "&codigo=" . $novedad['CODIGO'];
                                $variableEditar .= "&nombre=" . $novedad['NOMBRE'];
                                $variableEditar .= "&identificacion=" . $novedad['IDENTIFICACION'];
                                $variableEditar .= "&cargo=" . $novedad['CARGO'];
                                $variableEditar .= "&dependencia=" . $novedad['DEPENDENCIA'];
                                $variableEditar .= "&sueldo=" . $novedad['SUELDO_BASICO']; 
                                
					
				$variableEditar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableEditar, $directorio );
				
			
				// -------------Enlace-----------------------
				$esteCampo = "editar";
				$atributos ["id"] = $esteCampo;
				$atributos ['enlace'] = $variableEditar;
				$atributos ['tabIndex'] = $esteCampo;
				$atributos ['redirLugar'] = true;
				$atributos ['estilo'] = 'clasico';
				$atributos ['enlaceTexto'] = '';
				$atributos ['ancho'] = '25';
				$atributos ['alto'] = '25';
				$atributos ['enlaceImagen'] = $rutaBloque . "/images/edit.png";
				$mostrarHtml .= $this->miFormulario->enlace ( $atributos );
				unset ( $atributos );
				
				$mostrarHtml .= "</td>";
					
				$mostrarHtml .= "</tr>";
				
				echo $mostrarHtml;
				unset ( $mostrarHtml );
			}
		?>
		</tbody>
	</table>
</div>
        	<?php 
        }
        else{
                        $this->mensaje();
                        
        }
        
        
        
        // ------------------- SECCION: Paso de variables ------------------------------------------------

        /**
         * En algunas ocasiones es útil pasar variables entre las diferentes páginas.
         * SARA permite realizar esto a través de tres
         * mecanismos:
         * (a). Registrando las variables como variables de sesión. Estarán disponibles durante toda la sesión de usuario. Requiere acceso a
         * la base de datos.
         * (b). Incluirlas de manera codificada como campos de los formularios. Para ello se utiliza un campo especial denominado
         * formsara, cuyo valor será una cadena codificada que contiene las variables.
         * (c) a través de campos ocultos en los formularios. (deprecated)
        */

        // En este formulario se utiliza el mecanismo (b) para pasar las siguientes variables:

        // Paso 1: crear el listado de variables

        $valorCodificado = "action=" . $esteBloque ["nombre"];
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
        $valorCodificado .= "&usuario=''" ;
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=registrar";
        /**
         * SARA permite que los nombres de los campos sean dinámicos.
         * Para ello utiliza la hora en que es creado el formulario para
         * codificar el nombre de cada campo. 
         */
        $valorCodificado .= "&campoSeguro=" . $_REQUEST['tiempo'];
        // Paso 2: codificar la cadena resultante
        $valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );

        $atributos ["id"] = "formSaraData"; // No cambiar este nombre
        $atributos ["tipo"] = "hidden";
        $atributos ['estilo'] = '';
        $atributos ["obligatorio"] = false;
        $atributos ['marco'] = true;
        $atributos ["etiqueta"] = "";
        $atributos ["valor"] = $valorCodificado;
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        unset ( $atributos );

        // ----------------FIN SECCION: Paso de variables -------------------------------------------------

        // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------

        // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
        // Se debe declarar el mismo atributo de marco con que se inició el formulario.
        $atributos ['marco'] = true;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario ( $atributos );
        
        ?>
     </div>
    </div>
   </div>
</div>
        <?php
        
        return true;

    }

    function mensaje() {

        // Si existe algun tipo de error en el login aparece el siguiente mensaje
        $mensaje = $this->miConfigurador->getVariableConfiguracion ( 'mostrarMensaje' );
        $this->miConfigurador->setVariableConfiguracion ( 'mostrarMensaje', null );



            $tipoMensaje = $this->miConfigurador->getVariableConfiguracion ( 'tipoMensaje' );

            if ($tipoMensaje == 'json') {

                $atributos ['mensaje'] = $mensaje;
                $atributos ['json'] = true;
            } else {
                $atributos ['mensaje'] = $this->lenguaje->getCadena ('nodatos' );
            }
            // -------------Control texto-----------------------
            $esteCampo = 'divMensaje';
            $atributos ['id'] = $esteCampo;
            $atributos ["tamanno"] = '';
            $atributos ["estilo"] = 'information';
            $atributos ["etiqueta"] = '';
            $atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
            echo $this->miFormulario->campoMensaje ( $atributos );
            unset ( $atributos );

             
      

        return true;

    }

}

$miFormulario = new Formulario ( $this->lenguaje, $this->miFormulario, $this->sql );


$miFormulario->formulario ();
//$miFormulario->mensaje ();

?>