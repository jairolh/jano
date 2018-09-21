<?php

namespace gestionarSoportes\funcion;

class FormProcessor {
    
    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;
    var $conexion;
    
    function __construct($lenguaje, $sql) {
        
        $this->miConfigurador = \Configurador::singleton ();
        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
    
    }
    
    function procesarFormulario() {    
        $hostCurriculum=$this->miConfigurador->getVariableConfiguracion ( 'hostCurriculum' );
        $enlace=$this->miConfigurador->getVariableConfiguracion ( 'enlaceCurriculum' );
        $llave = $this->miConfigurador->fabricaConexiones->crypto->decodificar(trim($this->miConfigurador->getVariableConfiguracion ( 'tokenCurriculum' )));
        $token = $this->miConfigurador->fabricaConexiones->crypto->codificarVariable ($this->miConfigurador->getVariableConfiguracion ( 'enlaceCurriculum' ), $llave);
        $variableVer = "pagina=publicacion";
        $variableVer.= "&opcion=hojaVidaGeneral";
        $variableVer.= "&campoSeguro=" . $_REQUEST ['tiempo'];
        $variableVer.= "&tiempo=" . time ();
        $variableVer.= "&token=" . $token;
        $variableVer.= "&accesoCondor=Urano";
        $variableVer.= "&usuario=".$_REQUEST['usuario'];   
        $variableVer.= "&identificacion=".$_REQUEST['identificacion'];   
        $variableVer = $this->miConfigurador->fabricaConexiones->crypto->codificarVariable ($variableVer, $llave);
        ?>
        <div>
            <object type="text/html" data="<?php echo $hostCurriculum."?",$enlace."=",$variableVer;?>" width="100%" height="100%">
            </object>
        </div>            
        <?php
        /**
         * @todo lógica de procesamiento
         */
        return false;
    }

  function redireccionar($host,$enlace,$url)
	{   echo " <script type='text/javascript'>
                         window.location='".$host."".$enlace."".$url."';
                    </script>";
            exit;
	}    
}

$miProcesador = new FormProcessor ( $this->lenguaje, $this->sql );

$resultado= $miProcesador->procesarFormulario ();

