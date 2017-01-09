<?php

///ESTA ES UNA PRUEBA DE MODIFICACION EN BRANCH

//corregir problema de id que retiorna al insertar y duplicar

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}


include_once ("core/builder/Mensaje.class.php");
include_once ("core/connection/Persistencia.class.php");
include_once ("core/manager/Configurador.class.php");
include_once ("core/general/Tipos.class.php");


class DAL{
	
	const limiteValor = 200;
	const limiteNombre = 150;
	const limiteDescripcion = 300;
	const estadoHistorico = true;
	const numeroCopiasMaxima = 100;
	const CONEXION = 'estructura';
	
	private $fechaBetween = '';
	private $objetos;
	private $permisos;
	private $columnasTabla;
	private $miConfigurador;
	private $persistencia;
	private $tabla;
	private $prefijoColumnas;
	private $columnas;
	private $columnasNoPrefijo;
	private $excluidos;
	private $parametros;
	private $valores;
	private $indexado;
	private $usuario;
	private $where;
	private $tablaAlias;
	private $mensaje;
	public $conexion;
	private $historico;
	private $prefijoPorDefecto;
	private $idObjetoGlobal;
	private $prefijoTabla;
	private $nombreTablaObjetos;
	private $columnasConsulta;
	private $groupBy;
	private $orderBy;
	private $tmpTipoInputColumna;
	private $tmpValorColumna;
	
	function __construct($tabla = null, $esquema = 'public',$conexion = '') {
	
		$this->miConfigurador = \Configurador::singleton ();
		$this->mensaje =   \Mensaje::singleton();
		
		if($conexion!='') $this->conexion=$conexion;
		else	$this->conexion=self::CONEXION;
		
		
		
		
		//Recupera parametros de la base de datos
		
	    $this->recuperarObjetos();
	    
	    
	    
	    if(!is_null($tabla)&&$tabla!="") $this->setAmbiente($tabla);
	    
	
	}
	
	public function setOrderBy($valor){
		$this->orderBy =  $valor;
	}
	
	public function setGroupBy($valor){
		if(!is_array($valor)) return false;
		$this->groupBy = $valor;
		if(!is_array($this->columnas)) return false;
		$this->groupBy = array();
		foreach ($valor as $columna){
			if(in_array($columna,$this->columnas)) $this->groupBy[] =  $columna;
			if(in_array($columna,$this->columnasNoPrefijo)) $this->groupBy[] =  $this->prefijoTabla.$columna;
		}
		
		
	}
	
	public function getOrderBy(){
		return $this->orderBy ;
	}
	
	public function getGroupBy(){
		return $this->groupBy ;
	}

	public function validarConexion(){
		return $this->persistencia->validarConexion();
	}
	
	public function getQuery(){
		return $this->persistencia->getQuery();
	}
	
	public function getAtributosObjeto($idObjeto = ''){
		
		if($idObjeto == '') return false;
		$nombre = $this->getObjeto($idObjeto,'id','nombre');
		$this->persistencia =  new Persistencia($this->conexion,$nombre);
		$listaColumnas = $this->persistencia->getListaColumnas();
		$prefijo = $this->getObjeto($idObjeto,'id','prefijo_columna');
		 
		
		
		$resultado =  array();
		foreach ($listaColumnas as $columna){
			$resultado[] =  str_replace ($prefijo,'',$columna);;
		}
		
		return $resultado;
	}
	
	public function setUsuario($usuario){
		if(is_object($this->persistencia))$this->persistencia->setUsuario($usuario);
		$this->usuario = $usuario;
	}
	
	
	
	public function setAmbiente($tabla= '', $estadoHistorico = self::estadoHistorico ,$prefijo = null , $excluidos = '' ){
		if(!is_null($tabla)&&$tabla!='');{
			//crea persistencia
			$this->setTabla($tabla);
			$this->setEstadoHistorico($estadoHistorico);
			$this->crearPersistencia();
			
			if(!is_null($prefijo)){
				$this->persistencia->setPrefijoColumna($prefijo);
				$this->persistencia->setPrefijoColumnaH($prefijo."h");
					
				$this->setPrefijoColumna($prefijo);
			}
			else {
				$prefijo = $this->persistencia->getPrefijoColumna().'_';
				$this->setPrefijoColumna($$prefijo);
				$this->persistencia->setPrefijoColumna($prefijo);
				$this->persistencia->setPrefijoColumnaH($prefijo."h");
					
			}
			
			$this->setExcluidos($excluidos);
			//$conexion = $this->getConexion();
			//$this->setConexion('estructura'); 
			$this->recuperarColumnas();
			
			//$this->setConexion($conexion);
			
		}
		return false;
	}
	
	private function setTabla($tabla){
		$this->tabla = $tabla;
		
	}
	
	public function getTabla(){
		return $this->tabla ;
	
	}
	
	public function getTablaAlias(){
		return $this->tablaAlias ;
	
	}
	
	public function setEstadoHistorico($estado =  ''){
		if($estado!='') $this->historico =  (bool) $estado; 
		else $this->historico =  self::estadoHistorico;
	}
	
	public function getEstadoHistorico(){
		return $this->historico;
	}
	
	public function setConexion($conexion = ''){
		$this->conexion = $conexion;
	}
	
	public function getConexion(){
		return $this->conexion ;
	}
	
	private function crearPersistencia(){
		if(is_null($this->usuario)||$this->usuario=='') $this->usuario = -1;
		$this->persistencia =  new Persistencia($this->conexion,$this->tabla, $this->historico,"'".$this->usuario."'");
	}
	
	 
	private function getPrefijoColumna(){
		return $this->prefijoColumnas;
	}
	
	public function setPrefijoColumna($prefijo = ''){
		$this->prefijoColumnas = $prefijo;
	}
	
	public function getEsquema(){
		return $this->persistencia->getEsquema();
	}
	
	private function filtrarColumnasComodin($comodin){
		$resultado = array();
		$tabla =  $this->getTabla();
		foreach ($this->columnasNoPrefijo as $columna) {
			$idCol = $this->getColumnas($columna,'nombre','id');
				if($this->setBool($this->getColumnas($idCol,'id',$comodin))){
					$this->setTabla($tabla);
		            $this->crearPersistencia();
					$resultado[] = $this->getNombreColumnaReal($columna);
				}
			       	
			}
		$this->setTabla($tabla);
		$this->crearPersistencia();
		
		return $resultado;
	}
	
	public function columnasConsulta($lista = ''){
		if($lista==''||is_null($lista)) return false;
		$tabla =  $this->getTabla();
		$columnasNoPrefijo = $this->columnasNoPrefijo;
		$columnas = $this->columnas;
		
		if($lista=='_tabla_'){
			$this->columnasConsulta = 'tabla';
			$comodin =  'requerido_tabla';
			if(!is_array($this->columnasNoPrefijo)) return false;
			$this->columnasConsulta = $this->filtrarColumnasComodin($comodin);
		} 
		else{
			if(!is_array($this->columnasNoPrefijo)){
				$this->columnasConsulta = $lista;
				return false;
			}
			
			//if(is_array($lista)) $lista =  implode(',',$lista);
			$listado = explode(",",$lista);
			$this->columnasConsulta = array();
			foreach ($listado as $columna){
				
				if(in_array($columna,$this->columnas)) $this->columnasConsulta[] =  $columna;
				if(in_array($columna,$this->columnasNoPrefijo))$this->columnasConsulta[] =  $this->prefijoTabla.$columna;
			} 
				
		}
		
	}
	
	public function getColumnasConsulta(){
		return $this->columnasConsulta;
	}
	
	private	function recuperarColumnas(){
		$this->columnas = $this->persistencia->getListaColumnas($this->excluidos);
		if(!is_array($this->columnas)) return false;
		if(count($this->columnasNoPrefijo)>0) $this->columnasNoPrefijo =  array(); 
		foreach($this->columnas as $columna)
			$this->columnasNoPrefijo[] = str_replace($this->prefijoColumnas, "", $columna);
	}
	
	public function setExcluidos($excluidos = ''){

		if(is_array($excluidos)){
			foreach ($excluidos as $fila){
				$this->excluidos[] =  "'".$this->prefijoColumnas."_".$fila."'";
			}
		}else $this->excluidos = ''; 
	}
	
	public function getExcluidos(){
		return $this->excluidos;
	}
	
	
	public function objetosVisibles($listaIds = ''){
		
		$prefijo =  "objetos_";	
		$visible =  $prefijo."visible";
		$id =  $prefijo."id";
		$where =' '.$visible.' =  true';	
		
		if($listaIds!==''&&!is_null($listaIds))$where .= ' AND '.$id.' in ('.$listaIds.') ';
		
		return $this->removerPrefijoLista($this->recuperarObjetos($where),$prefijo );
		
		
	}
	
	
	private function recuperarObjetos($where = ''){
		
		//recupera esquema
		$esquema = $this->miConfigurador->getVariableConfiguracion ( "dbesquema" );
		
		//recupera prefijo
		$prefijoTabla = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
		
		//nombre tabla de objetos
		
		$nombreTabla = 'objetos';
		
		//nombre final
		if(!is_null($esquema)&&$esquema!='')
		  $nombreFinal = $esquema.".".$prefijoTabla.$nombreTabla ;
		else $nombreFinal = $prefijoTabla.$nombreTabla ;
		
		
		$this->nombreTablaObjetos = $nombreFinal;
		
		//popula $this->objetos
		$this->persistencia =  new Persistencia(self::CONEXION,$nombreFinal);
		$listaColumnas = $this->persistencia->getListaColumnas();
		
		if(is_array($listaColumnas)){
			 $this->objetos = $this->persistencia->read($listaColumnas);
			if($this->where!==''&&!is_null($where)){
				
				 return $this->persistencia->read($listaColumnas,$where);
				
			} 
			
			return true;
		}
		
		$this->objetos = false;
		$this->mensaje->addMensaje("100","errorRecuperarObjetos",'error');
		return false;
	
	
	}
	
	public function getObjeto($var = null,$tipo = null,$seleccion = null){
		if(!$this->validarEntradaSeleccion($var,$tipo,$seleccion)) return false;
		$prefijo = "objetos_";
		$listado = $this->objetos;
		$nombre = $this->selectTipo($prefijo,$tipo);
		$nombreS = $this->selectTipo($prefijo,$seleccion);
	    if(!is_array($listado)) return false;
		foreach($listado as $lista){
			if(strtolower ($var)==strtolower ($lista[$nombre]))
				return $lista[$nombreS];
		}
		$this->mensaje->addMensaje("103","objetoNoEncontrado:".$var,'information');
		return false;
	}
	
	private function removerPrefijoLista($lista_obj , $prefijo){
	   
	   	$lista = array();
		$prefijo = $prefijo;
		foreach ($lista_obj as $objeto){
			$fila = array();
			foreach ($objeto as $a => $b){
				//if(strpos($a,$prefijo)!==false){
					$indice = str_replace ($prefijo,"",$a);
					$fila[$indice] =  $b;
				//}
	
			}
			if(count($fila)>0)$lista[] = $fila;
		}
		return $lista;
		
	}
	
	private function getLista($nombre,$prefijo){
		
		$lista_obj = $this->recuperarListado($nombre,$prefijo);
		return $this->removerPrefijoLista($lista_obj, $prefijo); 
	}
	
	private function recuperarListado($nombre,$prefijo){
		
		$lista=  array();
		$this->persistencia =  new Persistencia($this->conexion,$nombre);
		$listaColumnas = $this->persistencia->getListaColumnas();
		
		
		if(is_array($listaColumnas)){
			$lista = $this->persistencia->read($listaColumnas);
			return $lista;
		}
		
		$lista = false;
		$this->mensaje->addMensaje("100","errorRecuperarObjetos",'error');
		return false;
		
	}
	
	private function get($prefijo='',$listado = '',$var = null,$tipo = null,$seleccion = null){
		if(!$this->validarEntradaSeleccion($var,$tipo,$seleccion)) return false;
		//if(!is_null($listado)||$listado =='') return false;
		$prefijo = $prefijo;
		$nombre = $this->selectTipo($prefijo,$tipo);
		$nombreS = $this->selectTipo($prefijo,$seleccion);
		
		foreach($listado as $lista){
			if(strtolower ($var)==strtolower ($lista[$nombre])){
				if($nombre==$nombreS) return true;
				if(isset($lista[$nombreS])) return $lista[$nombreS];
				if(isset($lista[$seleccion])) return $lista[$seleccion];
				return false;
				
			}
				
		}
		$this->mensaje->addMensaje("103","objetoNoEncontrado",'information');
		return false;
	}
	
	private function setBool($valor = ''){
    	if($valor=='t') return true;
    	return false;
    }
    
    
	public function __call($method_name, $arguments){
		
		if($method_name=='recuperarObjetos'||$method_name=='getObjeto'||$method_name=='getListaObjetosVisibles')
			return call_user_func_array(array($this , $method_name), $arguments);
		
		
		//Verifica si se solicit� getLista

		if (strpos($method_name,'getLista') !== false) {
			
			$objeto = str_replace('getLista','',$method_name);
			$idObjeto = $this->getObjeto($objeto,'ejecutar','id'); 
			$listar = (bool) $this->setBool($this->getObjeto($idObjeto,'id','listar'));
			if(!$listar) return false;
				
			
			if(!$idObjeto) {
				$this->mensaje->addMensaje("103","objetoNoEncontrado",'information');
				return false;
			}
			
			$alias = $this->getObjeto($idObjeto,'id','alias');
			$prefijo = $this->getObjeto($idObjeto,'id','prefijo_columna');
			$nombre = $this->getObjeto($idObjeto,'id','nombre');
			
			
			return $this->getLista($nombre,$prefijo);
			
		}
		
		//verifica si se solicit� get
		if (strpos($method_name,'get') !== false) {
				
			$objeto = str_replace('get','',$method_name);
			$idObjeto = $this->getObjeto($objeto,'ejecutar','id');
			$listar = (bool) $this->setBool($this->getObjeto($idObjeto,'id','listar'));
			if(!$listar) return false;
			
			if(!$idObjeto) {
				$this->mensaje->addMensaje("103","objetoNoEncontrado",'information');
				return false;
			}
				
			$alias = $this->getObjeto($idObjeto,'id','alias');
			$prefijo = $this->getObjeto($idObjeto,'id','prefijo_columna');
			$nombre = $this->getObjeto($idObjeto,'id','nombre');
			$listado = $this->recuperarListado($nombre,$prefijo);
			
			return $this->get($prefijo,$listado,$arguments[0],$arguments[1],$arguments[2]);
				
		}
		
		//verifica si se solicit� consultar, crear, duplicar, cambiarEstado , eliminar, actualizar
		
		//obtener lista tabla de operaciones
 		$conexionActual =  $this->conexion;
		$this->setConexion('estructura');
		
		$listaOperaciones =  $this->getListaOperacion();
		$this->setConexion($conexionActual);
		$operacionSeleccion = '';
		foreach ($listaOperaciones as $fila){
			if (strpos($method_name,$fila['nombre']) !== false) {
				$operacionSeleccion = $fila['nombre'];
				break;
			}
		}
		
		if (strpos($method_name,$operacionSeleccion) !== false&&$operacionSeleccion!='') {
		
			$conexionActual =  $this->conexion;
			$this->setConexion('estructura');
			
			$objeto = str_replace($operacionSeleccion,'',$method_name);
			$idObjeto = $this->getObjeto($objeto,'ejecutar','id');
			
			//Agregar conexion nombre a tabla de objetos
			//$conexionObjeto =  $this->getObjeto($objeto,'ejecutar','conexion_nombre');
			
			if(!$idObjeto) {
				
				$this->mensaje->addMensaje("103","objetoNoEncontrado",'information');
				return false;
			}
		
			
		    $idOperacion = $this->getOperacion($operacionSeleccion,'nombre','id');
		    
		    $this->setConexion($conexionActual);
		    
		    
			return $this->ejecutar($idObjeto,$arguments,$idOperacion);
			
		
		}
		
		
		return call_user_func_array(array($this , $method_name), $arguments);
		 
	}
	
	public function listaLlavesPrimarias($objetoId){
		
		$nombre = $this->getObjeto($objetoId,'id','nombre');
		$prefijo = $this->getObjeto($objetoId,'id','prefijo_columna');
		$this->persistencia =  new Persistencia($this->conexion,$nombre);
		$listaPks =  $this->persistencia->getPks();
		if(!is_array($listaPks)) return false;
		$listaFinal =  array();
		foreach ($listaPks as $fila){
			$listaFinal[] =  $str = str_replace($prefijo, '', $fila);
		}
		
		return $listaFinal;
		
	}
		 
		 
	
	private function selectTipo($prefijo='',$tipo = ''){
		
		if($prefijo===''||$tipo==='') return false;

		return $prefijo.$tipo;
	}
	
	private function validarEntradaSeleccion($var = null,$tipo = null,$seleccion=null){
		if(is_null($var)||$var===''){
			$this->mensaje->addMensaje("101","errorValorInvalido",'error');
			return false;
		}if(is_null($tipo)||$tipo===''){
			$this->mensaje->addMensaje("101","errorTipoInvalido",'error');
			return false;
		}if(is_null($seleccion)||$seleccion===''){
			$this->mensaje->addMensaje("101","errorSeleccionInvalido",'error');
			return false;
		}
		return true;
		
	}
	
	
	
	private function validarIdObjeto($idObjeto){
		if(!is_numeric($idObjeto)){
			$this->mensaje->addMensaje("101","erroridObjetoEntrada",'error');
			return false;
		}if(!$this->getObjeto($idObjeto,'id','nombre')){
			$this->mensaje->addMensaje("101","errorParametrosEntradaIdObjeto",'error');;
			return false;
		}
		
		return true;
	}
	
	private function validarParametros($parametros){
		
		if(!is_array($parametros)||count($parametros)==0){
			$this->mensaje->addMensaje("101","errorParametrosEntrada",'error');
			return false;
		}
		
		$llaves =  array_keys($parametros);
			foreach($llaves as $llave){
				if(!in_array($llave,$this->columnasNoPrefijo)){
					$this->mensaje->addMensaje("101","errorColumnaNoExiste",'error');
					return false;
				}
			}
		
		return true;
	}
	
	private function validarOperacion($operacion){
		
		if(!is_numeric($operacion)){
			$this->mensaje->addMensaje("101","errorOperacionEntrada",'error');
			return false;
		}
		
		return true;
	}
	
	private function validarEntrada($idObjeto = null, $parametros = null, $operacion = null){
		
		if($this->validarOperacion($operacion)&&$this->validarIdObjeto($idObjeto)){
			if($operacion!=2){
				if(!$this->validarParametros($parametros)) return false;
			}
			
				
		}else return  false;
		
		
		return true;
	}
	
	
	//http://www.sergiomejias.com/2007/09/validar-una-fecha-con-expresiones-regulares-en-php/
	private function validar_fecha($fecha){
		if (ereg("(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)[0-9]{2}", $fecha)) {
			return true;
		} else {
			return false;
		}
	}
	
	private function validarFechaRegistro($cadena=''){
		if(is_null($cadena)||$cadena==''){
			$this->mensaje->addMensaje("101","errorEntradaParametrosFechas",'error');
			return false;
		}
		
		$fechas = explode( ',', $cadena );
		$testWhere =  $this->where;
		if(!$testWhere){
			$testWhere =  '';
		}
		
		
		$testWhere = str_replace(' ', '', trim($testWhere));
		$testWhere  = preg_replace('/\s+/', '', trim($testWhere));
		
		$this->fechaBetween = '';
		
		if(count($fechas)==1&&$this->validar_fecha($fechas[0])){
			$this->fechaBetween .=" ".$this->prefijoColumnas."fecha_registro='".$fechas[0]."' " ;
			return $fechas[0];
		}elseif(count($fechas)>1&&$this->validar_fecha($fechas[0])&&$this->validar_fecha($fechas[1])){
			//$this->fechaBetween .=" (".$this->prefijoColumnas."fecha_registro, ".$this->prefijoColumnas."fecha_registro) OVERLAPS ('".$fechas[0]."'::DATE, '".$fechas[1]."'::DATE)";
			$this->fechaBetween .=" ".$this->prefijoColumnas."fecha_registro>='".$fechas[0]."' AND ".$this->prefijoColumnas."fecha_registro<='".$fechas[1]."' ";
			return $fechas[0];
		}else{
			return false;
		}
		
	}
	
	private function validarColumna($colIndex = '', $valor = ''){
		
		
		if(is_null($colIndex)||$colIndex==''||is_null($valor)||$valor=='') {
			$this->mensaje->addMensaje("101","errorEntradaParametros",'error');
			
			return false;
		}
		
		$conexion =  $this->getConexion();
		$this->setConexion('estructura');
		
		$columnaId = $this->getColumnas($colIndex,'nombre','id');
		$tipoDatoID = $this->getColumnas($columnaId,'id','tipo_dato_id');
		
		$tipoDatoNombre = $this->getTipoDato($tipoDatoID,'id','nombre');
		
		
		
		if($tipoDatoID===false||$columnaId===false){
			$this->mensaje->addMensaje("101","errorEntradaColumnaInvalida: ".$colIndex,'error');
			$this->setConexion($conexion);
			
			return false;
		}
		
		$tipoDatoNombre = $this->getTipoDato($tipoDatoID,'id','nombre');
		
		//si es fecha
		if($tipoDatoNombre=='date'){
			$valor =  explode(",",$valor);

			foreach ($valor as $val){
				if(Tipos::validarTipo($val,$tipoDatoID)===false){
						
					$this->mensaje->addMensaje("101","errorTipoDatoColumna: ".$colIndex,'error');
					$this->setConexion($conexion);
					return false;
				}
				
				$nuevoValor =  Tipos::evaluarTipo($val,$tipoDatoID);
				
				if($nuevoValor===false){
						
					$this->mensaje->addMensaje("101","errorValorColumna:".$colIndex,'error');
					$this->setConexion($conexion);
					return false;
				}
				
			}
		}else{
			
			if(Tipos::validarTipo($valor,$tipoDatoID)===false){
				
				$this->mensaje->addMensaje("101","errorTipoDatoColumna: ".$colIndex,'error');
				$this->setConexion($conexion);
				return false;
			}
			
			$nuevoValor =  Tipos::evaluarTipo($valor,$tipoDatoID);
			
			if($nuevoValor===false){
					
				$this->mensaje->addMensaje("101","errorValorColumna:".$colIndex,'error');
				$this->setConexion($conexion);
				return false;
			}
				
		}
		
		
		
		//validar si es una lista, si el id de la lista existe
		$tipoInput = $this->getColumnas($columnaId, 'id', 'input');
		$this->tmpTipoInputColumna = $tipoInput;
		$this->tmpValorColumna = $nuevoValor;
		if($tipoInput===false){
			$this->mensaje->addMensaje("101","errorInput: ".$colIndex,'error');
			$this->setConexion($conexion);
			return false;
		}
		
		$this->setConexion($conexion);
		if(strtolower($tipoInput)=='select'){

			
			$conexion =  $this->getConexion();
			$this->setConexion('estructura');
			
			$objetoId =  $this->getColumnas($columnaId, 'id', 'objetos_id');
			if($objetoId===false){
				$this->mensaje->addMensaje("101","registroObjetoNoExiste",'error');
				$this->setConexion($conexion);
				return false;
			}
			
			$objetoNombre = $this->getObjeto($objetoId, 'id', 'ejecutar');
			
			if($objetoNombre===false){
				$this->mensaje->addMensaje("101","registroObjetoNoExiste",'error');
				$this->setConexion($conexion);
				return false;
			}
						
			$objetoNombre =  ucfirst($objetoNombre);
			
			$ejecucion =  'get'.$objetoNombre;
			
			$conexionObjeto = $this->getColumnas($columnaId, 'id', 'conexion_nombre');
			
			$this->setConexion($conexionObjeto);
			
			$validacionIdEnLista = (bool) $this->$ejecucion($nuevoValor,'id','id');
			
			if($validacionIdEnLista===false){
				$this->mensaje->addMensaje("101","errorFk: ".$colIndex,'error');
				$this->setConexion($conexion);
				return false;
			}
			
			$this->setConexion($conexion);
				
		}
		$this->setConexion($conexion);
		return true;
		
	}

    private function getNombreColumnaReal($a){
    	if($this->persistencia->columnaEnTabla($this->prefijoColumnas.$a)){
				return $this->prefijoColumnas.$a;
				
			}elseif ($this->persistencia->columnaEnTabla($a)){
				return $a;
				
			}else {
				$colIndex = $a;
				$this->mensaje->addMensaje("101","columnaNoExiste: ".$this->tablaAlias,'information');
			    
			}
			
			return $colIndex;
    } 
    
    private function setTextoBoleano($valor=false){
    	if($valor===true||$valor==1) return 'true';
    	else return 'false';
    }
	
	private function procesarParametros($parametros,$validar=true){
			
		$this->parametros = array();
		$this->valores = array();
		$this->indexado = array();
		$valor = '';
		
		
		//_______________________________________________________________
		//esto se reemplaza por una consulta a la tabla de columnas
		//_______________________________________________________________

		
		foreach ($parametros as $param){
		
		foreach($param as $a=>$b){
			
			
			$b= trim($b);
			
		    $colIndex =  $this->getNombreColumnaReal($a);
		    
		    
		    
		    
		    if($colIndex===false&&$validar===true) continue;
			
		    	
		    
			$tabla = $this->tabla;
			$historico = $this->historico;
			$prefijo = $this->prefijoColumnas;
			
			
			$validacion =  $this->validarColumna($a, $b);
			
				
			$this->setAmbiente($tabla, $historico, $prefijo);

			
			if($validacion===false&&$validar===true) {
				
				continue;
			}
			
			
			switch($a){
				case 'fecha_registro':
					$limiteFecha = $this->validarFechaRegistro($b);
					if(!$limiteFecha) return false;
					
					$valor = "'".$limiteFecha."'";
					break;
				case is_bool($this->tmpValorColumna):
					$valor = $this->setTextoBoleano($b);
					break;
				case is_int($this->tmpValorColumna):
					$valor = $b;
					break;
				default:
					$valor = "'".$b."'";
					break;
			}
			
			
			
			
			$this->valores[] = $valor;
			$this->parametros[] = $colIndex;
			$this->indexado[$colIndex] = $valor;
			
			
		}
		}
		
		
		if(count($this->parametros)==count($this->valores))	return true;
		
		return false;
	}
	
	private function setWhere($where = ''){
		
		if($where==''||is_null($where)){
			
			if(is_array($this->indexado)){
			 foreach ($this->indexado as $a=>$b) {
			    	if($a !=$this->prefijoColumnas.'fecha_registro')$where.=" ".$a.'='.$b. " AND"; 
			 }
			 $where=substr($where, 0, strlen ($where)-3);
			}
			
		}elseif ($where=='id'){

			$where='';
			if(isset($this->indexado[$this->prefijoColumnas.'id'])){
				$where =$this->prefijoColumnas.'id='.$this->indexado[$this->prefijoColumnas.'id'];
			}else{
				
				if(is_array($this->indexado)){
					foreach ($this->indexado as $a=>$b) {
						if($a !=$this->prefijoColumnas.'fecha_registro')$where.=" ".$a.'='.$b. " AND";
					}
					$where=substr($where, 0, strlen ($where)-3);
				}
				
			}
			
		}
		$this->where = 	$where;
		return true;
	}
	
	private function procesarLeido($leido){
		
		if(isset($leido)&&is_array($leido)){
			//quitar indices numericos
			$resultado = array();
			foreach ($leido as $a => $b){
				if(!is_numeric($a)){
					
					if($this->prefijoColumnas&&strpos($a,$this->prefijoColumnas)!==false) $valorNoPrefijo = str_replace($this->prefijoColumnas,'',$a);
					else $valorNoPrefijo = $a;
					$resultado[$valorNoPrefijo] = $b ;
					
				}
				
			}
			
			return $resultado;
		}
		
			$this->mensaje->addMensaje("101","errorIdNoExiste: ".$this->tablaAlias,'error');
			return false;
		
		
		
			//
			
	}
	
	private function recuperarUltimoId(){
		
		$lista = $this->listaLlavesPrimarias($this->idObjetoGlobal);
		
		$maxId = 'max('.$lista[0].')';
		$leido = $this->persistencia->read(array($maxId));
		 
		
		if(!$leido){
			$this->mensaje->addMensaje("101","errorCreacion: ".$this->tablaAlias,'error');
			return false;
		}
		return $leido[0][0];
	}
	
	private function registrarPropietario($ultimoId = '',$objetoInsertar = ''){
		
		
		if($this->usuario=='-1'){
			$this->mensaje->addMensaje("101","usuarioIndefinido",'information');
			return true;
		}
		
	    if($ultimoId&&$ultimoId>=0){
		 	
		 	//set ambiente relaciones

		 	$idObjeto = $this->getObjeto('relacion','ejecutar','id');
		 	$tabla = $this->getObjeto($idObjeto,'id','nombre');
		 	$historico = $this->setBool($this->getObjeto($idObjeto,'id','historico'));
		 	$prefijo = $this->getObjeto($idObjeto,'id','prefijo_columna');
		 	if(!$tabla) return false;
		 	$this->tablaAlias = $this->getObjeto($idObjeto,'id','alias');
		 			 	
		 	$this->setAmbiente($tabla,$historico,$prefijo);
		 	
		 	$this->persistencia->setHistorico($historico);
		 	$this->persistencia->setPrefijoColumna($prefijo);
		 	$this->persistencia->setPrefijoColumnaH($prefijo."h");
		 	
		 	$parametros =  array();
		 	$parametros['usuario_id'] = $this->usuario=='__indefinido__'?-1:$this->usuario;
		 	$parametros['objetos_id'] = $objetoInsertar;
		 	$parametros['registro'] = $ultimoId;
		 	$parametros['permiso_id'] = 0;
		 	$parametros['estado_registro_id'] = 1;
		 	
		 	if(!$this->procesarParametros(array($parametros))||!$this->persistencia->create($this->parametros,$this->valores)){

		 		$this->mensaje->addMensaje("101","errorCreacion: ".$this->tablaAlias,'error');
		 		return false;
		 		
		 	}
		 	
		 	return true;
		 	 
		 }
		 
		 $this->mensaje->addMensaje("101","errorRegistroPropietario".$this->tablaAlias,'error');
		 return false;
		
	}
	
	
		
	public function ejecutar($idObjeto = null, $parametros = array(), $operacion = null){
		
		
		if(isset($parametros['justificacion'])){
			$justificacion = $parametros['justificacion'];
			unset($parametros['justificacion']);
		}else $justificacion = 'sin justificacion';
		$tabla = $this->getObjeto($idObjeto,'id','nombre');
		
		if(!$tabla) return false;
		
		
		$historico = $this->setBool($this->getObjeto($idObjeto,'id','historico'));
		$prefijo = $this->getObjeto($idObjeto,'id','prefijo_columna');
 
		
		$this->idObjetoGlobal = $idObjeto;
		$this->tablaAlias = $this->getObjeto($idObjeto,'id','alias');
		
		$this->tabla =  $tabla;
		$this->historico = $historico;
		$this->prefijoTabla = $prefijo;
		
		
		$this->setAmbiente($tabla,$historico,$prefijo,$this->excluidos);
		$this->setTabla($tabla);
		
		$this->persistencia->setPrefijoColumna($prefijo);
		$this->persistencia->setPrefijoColumnaH($prefijo."h");
		
		
		//Estado historico
		$this->persistencia->setHistorico($historico);
		
		switch($operacion){
			
			case 1:
				//crear
				
				unset($parametros['id']);
				unset($parametros['fecha_creacion']);
				
				
				if(!$this->procesarParametros($parametros)){
					
					return false;
					
					
				}
				$justificacion =  'create';
				$this->persistencia->setJustificacion($justificacion);
				$this->persistencia->setHistorico($historico);
				
				
				$creacion =  $this->persistencia->create($this->parametros,$this->valores);
				
				if($creacion==false){
					var_dump($this->getQuery());
					$this->mensaje->addMensaje("101","errorCreacion: ".$this->tablaAlias,'error');
					return false;
				}
				//$ultimoId =  $this->recuperarUltimoId();
				
				 return $creacion;
				//registrar propietario
						
				//if($this->usuario!=-1&&$this->usuario!='__indefinido__'&&!$this->registrarPropietario($ultimoId,$idObjeto)) return false;
				
				return $ultimoId;
				
				break;
			case 2:
				//consultar

				
				if(!$this->procesarParametros($parametros)){
					
					return false;
				}
				else{
					
					
					$this->setWhere();
					
					if(strlen($this->fechaBetween)>0&&strlen($this->where)>0) $this->where .= " AND ".$this->fechaBetween;
					elseif(strlen($this->fechaBetween)>0&&!$this->where) $this->where .= $this->fechaBetween;
                    
					if($this->columnasConsulta == 'tabla'){
						$this->columnasConsulta('_tabla_');
						
					}
					else{
						$this->columnasConsulta($this->columnasConsulta);
					}
					
					$this->setGroupBy($this->groupBy);
					
					if(is_array($this->columnasConsulta)) {
						
						$leido = $this->persistencia->read($this->columnasConsulta,$this->where,$this->groupBy,$this->orderBy);
					}else{
					
						$leido = $this->persistencia->read($this->columnas,$this->where,$this->groupBy,$this->orderBy);
					}

					
					
					if(!$leido){
						
						$this->mensaje->addMensaje("101","errorLectura: ".$this->tablaAlias,'information');
						return false;
					}
					
					    //return $leido;
						$lista =  array();
						foreach($leido as $lei) $lista[] =  $this->procesarLeido($lei);
						return $lista; 
					
					
				}
				break;
			case 3:
				//actualizar
				
				
				if(!$this->procesarParametros($parametros)||
				   !$this->setWhere('id')) return false;

				
				    $this->persistencia->setJustificacion($justificacion);
				    $this->persistencia->setHistorico($historico);
				
				   
				   if(!$this->persistencia->update($this->parametros,$this->valores,$this->where)){
					
				   	
					$this->mensaje->addMensaje("101","errorActualizar: ".$this->tablaAlias,'error');
					
				
					return false;
				}
				
				
				
				break;
			case 4:
				//duplicar
				
				 
				if(!$this->procesarParametros($parametros)||!$this->setWhere('id')){
					
				  return false;
				}else{
					
					
					//1. Leer
					$columnas = $this->columnas;
					$parametros = array();
					unset($columnas[0]);
					$leido = $this->persistencia->read($columnas,$this->where);
					 
					if(!$leido) return false;
					
					$justificacion =  'duplicate';
					$this->persistencia->setJustificacion($justificacion);
					$this->persistencia->setHistorico($historico);
					//2. Crear
						$parametros = $this->procesarLeido($leido[0]);
						
						$nombre = $parametros['nombre'];
						$creacion =  false;
						$i = 0;
						
						do{
							
							if($i==0) $parametros['nombre'] = $nombre." copia";
							else $parametros['nombre'] = $nombre." copia".$i;
							$this->procesarParametros(array($parametros),false);
							$this->persistencia->setHistorico($historico);
				
							$creacion =  $this->persistencia->create($this->parametros,$this->valores);
				            
							$i++;
						}while (!$creacion&&$i<self::numeroCopiasMaxima);
							
					  if(!$creacion){
					  	
					  	$this->mensaje->addMensaje("101","errorDuplicar: ".$this->tablaAlias,'error');
					  	return false;
					  
					  }

					  $ultimoId =  $this->recuperarUltimoId();
					  
					  	
					  //registrar propietario
					  if($this->usuario!=-1&&$this->usuario!='__indefinido__'&&!$this->registrarPropietario($ultimoId,$idObjeto)) return false;
					  return $ultimoId;
					
				}
				
				break;
			case 5:
				
				//cambio activo/inactivo
				if(!$this->procesarParametros($parametros)||!$this->setWhere('id')){
					return false;
				}else{
					
					
					$leido = $this->persistencia->read($this->columnas,$this->where);
					 
					if(!$leido) return false;
					$parametros = $this->procesarLeido($leido[0]);
					
					foreach($parametros as $a => $b){
						if($a!='estado_registro_id') unset($parametros[$a]);
					}
					
					//toggle
					if(isset($parametros['estado_registro_id'])&&$parametros['estado_registro_id']==2) $parametros['estado_registro_id'] = 1;
					else $parametros['estado_registro_id'] = 2;
					
					if(!$this->procesarParametros(array($parametros)))
						return false;
					

					$justificacion =  'activo/inactivo';
					$this->persistencia->setJustificacion($justificacion);
					$this->persistencia->setHistorico($historico);
					
					if(!$this->persistencia->update($this->parametros,$this->valores,$this->where)){
                        
						$this->mensaje->addMensaje("101","errorCambiarEstado: ".$this->tablaAlias,'error');
						return false;
					}
						
				}
				break;
			case 6:
				//eliminar
				if(!$this->procesarParametros($parametros)||!$this->setWhere('id')) return false;
				
				
				$justificacion =  'delete';
				$this->persistencia->setJustificacion($justificacion);
				$this->persistencia->setHistorico($historico);
				
				if(!$this->persistencia->delete($this->where)){
					
					
					$this->mensaje->addMensaje("101","errorEliminar: ".$this->tablaAlias,'error');
					return false;
				}
				break;
			default:
				return false;
				break;
		}
		
		return true;
		
	}
	
	
	public function registrarDatos(){
		return call_user_func_array(array($this,'ejecutar'), func_get_args());
	}
}


