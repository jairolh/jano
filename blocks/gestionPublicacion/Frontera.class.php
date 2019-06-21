<?php

namespace gestionPublicacion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
class Frontera {
	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	var $miConfigurador;
	function __construct() {
		$this->miConfigurador = \Configurador::singleton ();
	}
	public function setRuta($unaRuta) {
		$this->ruta = $unaRuta;
	}
	public function setLenguaje($lenguaje) {
		$this->lenguaje = $lenguaje;
	}
	public function setFormulario($formulario) {
		$this->formulario = $formulario;
	}
	function frontera() {
		$this->html ();
	}
	function setSql($a) {
		$this->sql = $a;
	}
	function setFuncion($funcion) {
		$this->funcion = $funcion;
	}
	function html() {
		include_once ("core/builder/FormularioHtml.class.php");
		$this->ruta = $this->miConfigurador->getVariableConfiguracion ( "rutaBloque" );
		$this->miFormulario = new \FormularioHtml ();
		if (isset ( $_REQUEST ['opcion'] )) {
			
			switch ($_REQUEST ['opcion']) {
                                case "hojaVida" :
                                        $conexion="reportes";
                                        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
                                        $parametro=array('consecutivo_inscrito'=>$_REQUEST['consecutivo_inscrito'],
                                                        'tipo_dato'=>'datosBasicos');    
                                        $cadena_sql = $this->sql->getCadenaSql("consultaSoportesInscripcion", $parametro);
                                        $resultadoListaBasicos= $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                                        //var_dump($resultadoListaBasicos);
                                        include ($this->ruta . "formulario/tabs/perfil.php");
                                        //include_once ($this->ruta . "/formulario/hojaVida.php");
                                        
                                        if(is_array($resultadoListaBasicos))
                                             {  include_once ($this->ruta . "/formulario/hojaVida.php");}
                                        else {  
                                                    $atributos["id"]="divSoporte";
                                                    $atributos["estilo"]="";
                                                    //$atributos["estiloEnLinea"]="display:none"; 
                                                    echo $this->miFormulario->division("inicio",$atributos);
                                                    //-------------Control Boton-----------------------
                                                    $esteCampo = "faseSoporteOn";
                                                    $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                                                    $atributos["etiqueta"] = "";
                                                    $atributos["estilo"] = "centrar";
                                                    $atributos["tipo"] = 'warning';
                                                    $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
                                                    echo $this->miFormulario->cuadroMensaje($atributos);
                                                    unset($atributos); 
                                                    //-------------Fin Control Boton----------------------
                                                   echo $this->miFormulario->division("fin");
                                                //buscar consecutivo_persona   
                                                $tam=2;
                                                if(strtoupper(substr($_REQUEST['usuario'],0,1))!='C')
                                                    {$tam=3;}
                                                $_REQUEST['identificacion']=substr($_REQUEST['usuario'],$tam);    
                                                
                                                include_once ($this->ruta . "/formulario/hojaVidaGeneral.php");}
                                        
					break;
                                case "hojaVidaGeneral" :
					include_once ($this->ruta . "/formulario/hojaVidaGeneral.php");
					break;
                                case "evaluacion" :
					include_once ($this->ruta . "/formulario/resultadosEvaluacionDetalle.php");
					break; 
                                case "evaluacionFinal" :
					include_once ($this->ruta . "/formulario/resultadosEvaluacionFinal.php");
					break;                                     
                                case "faseProcesado" :
					include_once ($this->ruta . "/formulario/faseProcesado.php");
					break;                                    
                                case "faseParcial" :
                                        include_once ($this->ruta . "/formulario/listaParcial.php");
				     break;                                    
                                case "faseReclamo" :
                                        include_once ($this->ruta . "/formulario/listaReclamo.php");
				     break;                                    
                                case "faseFinal" :
                                        include_once ($this->ruta . "/formulario/listaFinal.php");
				     break;                                    
				case "mensaje" :
					include_once ($this->ruta . "/formulario/mensaje.php");
					break;
			                                  
        		}
		} else {
			$_REQUEST ['opcion'] = "mostrar";
			include_once ($this->ruta . "/formulario/consultarUsuarios.php");
		}
	}
}
?>
