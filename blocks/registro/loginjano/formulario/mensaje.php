<?php
namespace registro\loginjano;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class registrarForm {
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
        //INVOCA EL BANNER
        include_once 'bannerClaveForm.php';
        $miBanner = new cabecera($this->lenguaje, $this->miFormulario);
        $miBanner->estructura();
        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

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

        // -------------------------------------------------------------------------------------------------
        $conexion = "estructura";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );


        // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
        $esteCampo = $esteBloque ['nombre'];
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        // Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
        $atributos ['tipoFormulario'] = 'multipart/form-data';
        // Si no se coloca, entonces toma el valor predeterminado 'POST'
        $atributos ['metodo'] = 'POST';
        // Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
        $atributos ['action'] = 'index.php';
        $atributos ['titulo'] = false;
        // $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );
        // Si no se coloca, entonces toma el valor predeterminado.
        $atributos ['estilo'] = '';
        $atributos ['marco'] = true;
        $tab = 1;
        // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------

        //var_dump("Paso de Frontera.class");
        //var_dump($_REQUEST);
        //exit;

        // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos ['tipoEtiqueta'] = 'inicio';
        echo $this->miFormulario->formulario ( $atributos );
        {
            // ---------------- SECCION: Controles del Formulario -----------------------------------------------


            $miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );

            $directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
            $directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
            $directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );

            $variable = "pagina=" . $miPaginaActual;
            //$variable .= "&usuario=" . $_REQUEST['usuario'];
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );

            

            echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );

            {

                if ($_REQUEST ['mensaje'] == 'claveCambiada') {

                    $mensaje = "La contraseña ha sido actualizada para <h4>" .
                            $_REQUEST ['nombre'] . "</h4>";
                    
                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'success';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                } 
                if ($_REQUEST ['mensaje'] == 'claveNoCambiada') {

                    //$cadenaSql = $this->miSql->getCadenaSql ( 'actualizar_entrada', $arreglo );
                    //$inserto = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "acceso" );

                    $mensaje = "La contraseña no ha sido cambiada para <h4>" . $_REQUEST ['nombre'] . "</h4>"
                            . "<br>Por favor comuniquese con el administrador del sistema";
                    
                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                } 
                if ($_REQUEST ['mensaje'] == 'correoEnviado') {

                    $mensaje = "Se ha enviado un email con el procedimiento para recuperar su contraseña "
                            . " al correo <h4> " .$_REQUEST['correo']."</h4>";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'success';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }
                
                if ($_REQUEST ['mensaje'] == 'insertoUsuario') {

                    $mensaje = "Se ha registrado el usuario exitosamente, con el perfil <b>" .$_REQUEST['perfilAlias']."</b>!<br> "
                            . " El usuario para ingresar al sistema es <h4> " .$_REQUEST['id_usuario']."</h4>";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                     $atributos ['tipo'] = 'success';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                } 
                
                if ($_REQUEST ['mensaje'] == 'noInserto') {

                    $mensaje = "No fue posible registrar el usuario, por favor intente más tarde";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                     $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }                 
                
                if ($_REQUEST ['mensaje'] == 'correoNoEnviado') {

                    $mensaje = "El correo no ha podido ser enviado a <h4> " .$_REQUEST['correo']."</h4>"
                            . "Por favor comuníquese con el administrador del sistema";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }
                if ($_REQUEST ['mensaje'] == 'usuarioInexistente') {

                    $mensaje = "El usuario que ingresó no existe, por favor verifique la información ";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }
                if ($_REQUEST ['mensaje'] == 'usuarioInactivo') {

                    $mensaje = "El usuario <h4>".$_REQUEST['usuario']."</h4> no se encuentra activo o tine todos sus perfiles inactivos o caducados<br>"
                            . "Por favor comuníquese con el administrador del sistema";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }
                if ($_REQUEST ['mensaje'] == 'existe') {

                    $mensaje = "El usuario con identificación <h4>".$_REQUEST['tipo_identificacion']." ".$_REQUEST['identificacion']."</h4> ya existe en el Sistema!<br>"
                            . "Para poder ingresar intente recuperar la contraseña, si no le es posible es porque la cuenta no se encuentra activa!<br>"
                            . "Por tanto favor comuníquese con el administrador del sistema";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }                
                if ($_REQUEST ['mensaje'] == 'campoNovalido') {

                    $mensaje = "Los campos de registro no son validos o estan vacios<br>"
                            . "Por favor verifique los datos ingresados en intente nuevamente";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;
                    $tab ++;
                    
                    $variableReg="&opcion=registro";
                    $var=['identificacion' ,'tipo_identificacion','nombres','apellidos','correo','telefono'];
                        foreach ($var as $key => $value) {
                            $variableReg .= "&".$value."=" . $_REQUEST[$value];
                        }

//                    echo $variableReg;exit;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }

                if ($_REQUEST ['mensaje'] == 'correoNovalido') {

                    $mensaje = "El correo electonico NO es valido<br>"
                            . "Por favor verifique los datos ingresados en intente nuevamente";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;
                    $tab ++;
                    
                    $variableReg="&opcion=registro";
                    $var=['identificacion' ,'tipo_identificacion','nombres','apellidos','correo','telefono'];
                        foreach ($var as $key => $value) {
                            $variableReg .= "&".$value."=" . $_REQUEST[$value];
                        }
                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }                

                if ($_REQUEST ['mensaje'] == 'claveNovalido') {

                    $mensaje = "La contraseña NO es valida o no coinciden <br>"
                            . "Por favor verifique los datos ingresados en intente nuevamente"
                            ."<br>Recuerde que la contraseña debe contener:<br><br> * Entre 8 y 16 caracteres<br>"
                            ."* Por lo menos una letra mayúscula<br>"
                            ."* Por lo menos un número<br>";
                            

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;
                    $tab ++;
                    
                    $variableReg="&opcion=registro";
                    $var=['identificacion' ,'tipo_identificacion','nombres','apellidos','correo','telefono'];
                        foreach ($var as $key => $value) {
                            $variableReg .= "&".$value."=" . $_REQUEST[$value];
                        }
                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }                
                
                
                if ($_REQUEST ['mensaje'] == 'linkCaducado') {

                    $mensaje = "El enlace ya ha caducado, su solicitud fue hecha el ".$_REQUEST['fecha'].", por favor vuelva a hacer la solicitud";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }
                if ($_REQUEST ['mensaje'] == 'claveNoCoincide') {

                    $mensaje = "La verificación de la contraseña no coincide o la ingresada no cumple las especificaciones:<br>"
                            . "<b>Mínimo 8 caracteres<br>"
                            . "Máximo 16 caracteres<br>"
                            . "Mínimo una letra mayúscula<br>"
                            . "Mínimo un número</b><br><br>Por favor vuelva a digitarlas";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'error';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge ( $atributos, $atributosGlobales );
                    echo $this->miFormulario->cuadroMensaje ( $atributos );
                    // --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
                }
                


            }


            // ------------------Division para los botones-------------------------
            $atributos ["id"] = "botones";
            $atributos ["estilo"] = "marcoBotones";
            echo $this->miFormulario->division ( "inicio", $atributos );

            // -----------------CONTROL: Botón ----------------------------------------------------------------
            $esteCampo = 'botonAceptar';
            $atributos ["id"] = $esteCampo;
            $atributos ["tabIndex"] = $tab;
            $atributos ["tipo"] = 'boton';
            // submit: no se coloca si se desea un tipo button genérico
            $atributos ['submit'] = true;
            $atributos ["estiloMarco"] = '';
            $atributos ["estiloBoton"] = 'jqueryui';
            // verificar: true para verificar el formulario antes de pasarlo al servidor.
            $atributos ["verificar"] = '';
            $atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
            $atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
            $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
            $tab ++;

            // Aplica atributos globales al control
            $atributos = array_merge ( $atributos, $atributosGlobales );
            echo $this->miFormulario->campoBoton ( $atributos );

            echo $this->miFormulario->marcoAgrupacion ( 'fin' );

            // ---------------- SECCION: División ----------------------------------------------------------
            $esteCampo = 'division1';
            $atributos ['id'] = $esteCampo;
            $atributos ['estilo'] = 'general';
            echo $this->miFormulario->division ( "inicio", $atributos );

            // ---------------- FIN SECCION: División ----------------------------------------------------------
            echo $this->miFormulario->division ( 'fin' );

            // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------

            // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
            // Se debe declarar el mismo atributo de marco con que se inició el formulario.
        }

        // -----------------FIN CONTROL: Botón -----------------------------------------------------------

        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division ( "fin" );

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

//		$valorCodificado = "actionBloque=" . $esteBloque ["nombre"];
        $valorCodificado = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        
        if(isset($variableReg))
            {$valorCodificado .= $variableReg;}
        else
            {$valorCodificado .= "&opcion=paginaPrincipal";}
        
        
        /**
         * SARA permite que los nombres de los campos sean dinámicos.
         * Para ello utiliza la hora en que es creado el formulario para
         * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
         * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
         * (b) asociando el tiempo en que se está creando el formulario
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

        $atributos ['marco'] = true;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario ( $atributos );
    }
}
$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );
$miSeleccionador->miForm ();
?>