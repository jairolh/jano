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

            $path_doc= $_REQUEST['raiz'].$_REQUEST['ruta'];
            $filename =$_REQUEST['archivo'];
            $aliasname =$_REQUEST['alias'];
            if(isset($_REQUEST['archivo']) && $_REQUEST['archivo']!='')
                { $file=$path_doc."/".$filename;}
            else{ $file=$path_doc;}    

            header('Content-type: application/pdf');// esta linea fue mi dolor de cabeza
            header('Content-Disposition: inline; filename="' . $aliasname . '"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($file));
            header('Accept-Ranges: bytes');
            readfile($file);
        
        /**
         * @todo lógica de procesamiento
         */
        return false;
    }
    
}

$miProcesador = new FormProcessor ( $this->lenguaje, $this->sql );

$resultado= $miProcesador->procesarFormulario ();

