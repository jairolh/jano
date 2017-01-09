<?php
/*
//if(isset($_REQUEST)) {unset ($_REQUEST);}
set_include_path('blocks/reportes/reportico/script/reportico');
require_once('blocks/reportes/reportico/script/reportico/reportico.php');

// Set the timezone according to system defaults
date_default_timezone_set(@date_default_timezone_get());

// Reserver 100Mb for running
ini_set("memory_limit", "512M");

// Allow a good time for long reports to run. Set to 0 to allow unlimited time
ini_set("max_execution_time", "180");
//Incluye archivo que direcciona al proyecto indicado
//include_once('blocks/reportes/reportico/script/reportico/login_reporte_especifico.php');

$reporte = new reportico();
//$reporte->embedded_report = true;

$reporte->execute();

ob_end_flush();*/
?>
<?php

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class consulta {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;
   
    function __construct($lenguaje, $formulario, $sql) {
        $this->miConfigurador = \Configurador::singleton();

        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');

        $this->lenguaje = $lenguaje;

        $this->miFormulario = $formulario;

        $this->miSql = $sql;
        }

    function miForm() {
        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        $conexion="estructura";
	$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
	
        $miSesion = \Sesion::singleton();
        //identifca lo roles para la busqueda de subsistemas
        $User=$miSesion->idUsuario();
        $parametro=array('id_usuario'=>$User);
        $cadena_sql = $this->miSql->getCadenaSql("consultarUsuarios", $parametro);
        $resultadoUsuarios = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        $ruta= $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" );
        $reporte='/blocks/reportes/reportico/reporteador/./run.php?';
        $reporte.="User=".$resultadoUsuarios[0]['nombre']." ".$resultadoUsuarios[0]['apellido'];
        $reporte.="&informes=".$_REQUEST['informes'];
        $reporte.="&acceso=".$_REQUEST['acceso'];
        isset($_REQUEST['reporte'])?$reporte.="&reporte=".$_REQUEST['reporte']:'';
        ?>
        <div style='width:100%; height: 650px'>
            <iframe src="<?php echo $ruta.$reporte;?>" style="width: 100%; height: 100%"></iframe>
         </div>
        <?php
 

    }
}

$miSeleccionador = new consulta($this->lenguaje, $this->miFormulario, $this->sql);

$miSeleccionador->miForm();
?>
