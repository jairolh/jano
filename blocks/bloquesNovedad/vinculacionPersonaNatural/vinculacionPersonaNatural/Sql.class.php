<?php

namespace bloquesNovedad\vinculacionPersonaNatural;

if (! isset ( $GLOBALS ["autorizado"] )) {
    include ("../index.php");
    exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

/**
 * IMPORTANTE: Se recomienda que no se borren registros. Utilizar mecanismos para - independiente del motor de bases de datos,
 * poder realizar rollbacks gestionados por el aplicativo.
 */



class Sql extends \Sql {
    
    var $miConfigurador;
    
    function getCadenaSql($tipo, $variable = '') {
        
        
        
        /**
         * 1.
         * Revisar las variables para evitar SQL Injection
         */
        $prefijo = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
        $idSesion = $this->miConfigurador->getVariableConfiguracion ( "id_sesion" );
        $cadenaSql='';
        switch ($tipo) {
            
            /**
             * Clausulas específicas
             */
            
            case 'buscarArl':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'nit as NIT, ';
                $cadenaSql .= 'nombre as NOMBRE, ';
                $cadenaSql .= 'direccion as DIRECCION, ';
                $cadenaSql .= 'telefono as TELEFONO, ';
                $cadenaSql .= 'extencion_telefono as EXTENCION_TELEFONO, ';
                $cadenaSql .= 'estado as ESTADO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'nomina.arl';
                break;
             case 'buscarIdFuncionario':
                $cadenaSql = 'SELECT ';
               
                $cadenaSql .= 'a.id_funcionario as ID ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'persona.vinculacion_persona_natural b, ';
                $cadenaSql .= 'novedad.funcionario a ';
                 $cadenaSql .= ' WHERE ';
                $cadenaSql .= 'id = ';
                $cadenaSql .= $variable ['id']  .' ';
                $cadenaSql .= 'and a.documento = b.documento';
                
                break;
            
            
            case 'buscarTipoVinculacion1':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id as ID, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.tipo_vinculacion';
                break;
            
            case 'buscarTipoVinculacion':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'tipo_liquidacion as TIPOLIQUIDACION ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.tipo_vinculacion';
                $cadenaSql .= ' WHERE ';
                $cadenaSql .= 'id = ';
                $cadenaSql .= $variable   .';';
                
                break;
            
             case 'buscarRubro' ://Provisionalmente solo Departamentos de Colombia
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'rub_identificador as ID, ';
				$cadenaSql .= 'rub_nombre_rubro as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'otro.rubro ';
				
				break;
            
            case 'buscarPersonaVinculada':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'p.documento as DOCUMENTO, ';
              
                $cadenaSql .= 'd.id as ID_VINCULACION, ';
              
                $cadenaSql .= 'estado_vinculacion as ESTADO_VINCULACION ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'persona.persona_natural p, ';
                $cadenaSql .= 'parametro.tipo_vinculacion j, ';
                $cadenaSql .= 'novedad.funcionario f, ';
                $cadenaSql .= 'persona.vinculacion_persona_natural d ';
                $cadenaSql .= 'where d.documento = p.documento';
                $cadenaSql .= ' and d.id_tipo_vinculacion = j.id ';
                $cadenaSql .= '  and d.documento=f.documento';
                
             
                break;
               case 'buscarCargo' :
                
                	$cadenaSql = 'SELECT ';
                        $cadenaSql .= 'codigo_cargo as COD_CARGO, ';
                        
                        $cadenaSql .= 'tipo_cargo as NOMBRE ';
                       
                       
                        $cadenaSql .= 'FROM ';
                        $cadenaSql .= 'parametro.cargo';
//                        $cadenaSql .= 'WHERE ';
//                        $cadenaSql .= 'nombre=\'' . $_REQUEST ['usuario']  . '\' AND ';
//                        $cadenaSql .= 'clave=\'' . $claveEncriptada . '\' ';
                        
                break;
            case 'buscarPersonaVinculadaDetalle':
                $cadenaSql = 'SELECT ';
            
                $cadenaSql .= "(primer_nombre || ' ' || segundo_nombre) as NOMBRES, ";
                $cadenaSql .= "(primer_apellido || ' ' || segundo_apellido) as APELLIDOS, ";
              // nombre o naturaleza
                $cadenaSql .= 'nombre as TIPO_VINCULACION, ';
                $cadenaSql .= "fecha_inicio as FECHA_INICIO, ";
                $cadenaSql .= "fecha_final as FECHA_FINAL, ";
                $cadenaSql .= "d.id as ID_VINCULACION, ";
                $cadenaSql .= "j.id as ID_TIPO_VINCULACION ";
                
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'persona.persona_natural p, ';
                $cadenaSql .= 'parametro.tipo_vinculacion j, ';
                $cadenaSql .= 'novedad.funcionario f, ';
                $cadenaSql .= 'persona.vinculacion_persona_natural d ';
                $cadenaSql .= 'where d.documento = p.documento';
                $cadenaSql .= ' and d.id_tipo_vinculacion = j.id ';
                $cadenaSql .= '  and d.documento=f.documento';
                
                break;
            
                      
                case 'buscarPersonaFuncionario':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'p.documento as DOCUMENTO, ';
                $cadenaSql .= "(primer_nombre || ' ' || segundo_nombre) as NOMBRES, ";
                $cadenaSql .= "(primer_apellido || ' ' || segundo_apellido) as APELLIDOS ";
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'novedad.funcionario p, ';
                $cadenaSql .= 'persona.persona_natural d ';
                $cadenaSql .= 'where d.documento = p.documento';
                
                break;
             case 'buscarPersonaVinculadaghjgj':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'documento as DOCUMENTO, ';
                $cadenaSql .= "(primer_nombre || ' ' || segundo_nombre) as NOMBRES, ";
                $cadenaSql .= "(primer_apellido || ' ' || segundo_apellido) as APELLIDOS ";
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'persona.persona_natural ';
                $cadenaSql .= "where estado_solicitud='Aprobado'";
                break;
         case 'buscarArl1':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'nit as NIT, ';
                $cadenaSql .= 'nombre as NOMBRE, ';
                $cadenaSql .= 'direccion as DIRECCION, ';
                $cadenaSql .= 'telefono as TELEFONO, ';
                $cadenaSql .= 'extencion_telefono as EXTENCION_TELEFONO, ';
                $cadenaSql .= 'fax as FAX, ';
                $cadenaSql .= 'extencion_fax as EXTENCION_FAX, ';
                $cadenaSql .= 'lugar as LUGAR,';
                $cadenaSql .= 'nombre_representante_legal as NOMBRE_REPRESENTANTE_LEGAL, ';
                $cadenaSql .= 'email as EMAIL, ';
                $cadenaSql .= 'estado as ESTADO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'nomina.arl';
                break;
           case 'buscarTipoVinculacion':
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id as ID, ';
                $cadenaSql .= 'nombre as NOMBRE, ';
                $cadenaSql .= 'descripcion as DESCRIPCION, ';
                $cadenaSql .= 'naturaleza as NATURALEZA, ';
                $cadenaSql .= 'reglamentacion as REGLAMENTACION ,';
                $cadenaSql .= 'estado as ESTADO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'parametro.tipo_vinculacion';
                break; 
            
             case 'modificarRegistro' :
                $cadenaSql = 'UPDATE ';
                $cadenaSql .= 'persona.vinculacion_persona_natural ';
                $cadenaSql .= 'SET ';
                 if($variable ['tipoVinculacion']!='')
                {
                     $cadenaSql .= 'id_tipo_vinculacion = ';
                     $cadenaSql .= "'".$variable ['tipoVinculacion']  . "',";
                }
                $cadenaSql .= 'fecha_inicio = ';
                $cadenaSql .= "'".$variable ['fechaInicio'] . "',";
                $cadenaSql .= 'fecha_final = ';
                $cadenaSql .= "'".$variable ['fechaFin']  . "' ";
                
              
               
               
               
                
                $cadenaSql .= ' WHERE ';
                $cadenaSql .= 'id = ';
                $cadenaSql .= $variable ['id']  .';';
                break;
                
                
                case 'modificarRegistroCargo' :
                $cadenaSql = 'UPDATE ';
                $cadenaSql .= 'persona.vinculacion_persona_natural ';
                $cadenaSql .= 'SET ';
                
                $cadenaSql .= 'codigo_cargo = ';
               
                $cadenaSql .= "'".$variable ['codigo_cargo']  . "' ";
                
              
               
               
               
                
                $cadenaSql .= ' WHERE ';
                $cadenaSql .= 'id = ';
                $cadenaSql .= $variable ['id']  .';';
                break;
                
            case 'inactivarRegistro' :
                $cadenaSql = 'UPDATE ';
                $cadenaSql .= 'persona.vinculacion_persona_natural ';
                $cadenaSql .= 'SET ';
                $cadenaSql .= 'estado_vinculacion = ';
                $cadenaSql .= "'". $variable ['estadoRegistro']  ."' ";
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id = ';
                $cadenaSql .= $variable ['id'].";";
                break;
        
        
            case "registrarArl" :
				$cadenaSql=" INSERT INTO nomina.arl";
				$cadenaSql.=" (";
				$cadenaSql.=" nit,";
				$cadenaSql.=" nombre,";
				$cadenaSql.=" direccion,";
				$cadenaSql.=" telefono,";
				$cadenaSql.=" extencion_telefono,";
				$cadenaSql.=" fax,";
				$cadenaSql.=" extencion_fax,";
				$cadenaSql.=" lugar,";
				$cadenaSql.=" nombre_representante_legal,";
				$cadenaSql.=" email,";
				$cadenaSql.=" estado";
				$cadenaSql.=" )";
				$cadenaSql.=" VALUES";
				$cadenaSql.=" (";
				$cadenaSql.=" '" . $_REQUEST['nit']. "',";
				$cadenaSql.=" '" . $_REQUEST['nombre']. "',";
				$cadenaSql.=" '" . $_REQUEST['direccion']. "',";
				$cadenaSql.=" '" . $_REQUEST['telefono']. "',";
				$cadenaSql.=" '" . $_REQUEST['extencionTelefono']. "',";
				$cadenaSql.=" '" . $_REQUEST['fax']. "',";
				$cadenaSql.=" '" . $_REQUEST['extencionFax']. "',";
				$cadenaSql.=" '" . $_REQUEST['lugar']. "',";
				$cadenaSql.=" '" . $_REQUEST['nombreRepresentante']. "',";
                                $cadenaSql.=" '" . $_REQUEST['email']. "',";
				$cadenaSql.=" 'Activo'";
				$cadenaSql.=" );";
				break;  
            
            case 'insertarRegistro' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'parametro.cargo ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'nivel,';
                $cadenaSql .= 'codigo_alternativo,';
                $cadenaSql .= 'grado,';
                $cadenaSql .= 'nombre,';
                $cadenaSql .= 'cod_tipo_cargo,';
                $cadenaSql .= 'sueldo,';
                $cadenaSql .= 'tipo_sueldo,';
                $cadenaSql .= 'estado';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= $_REQUEST ['nivelRegistro'] . ', ';
                $cadenaSql .= $_REQUEST ['codAlternativoRegistro'] . ', ';
                $cadenaSql .= $_REQUEST ['gradoRegistro'] . ', ';
                $cadenaSql .= '\'' . $_REQUEST ['nombreRegistro']  . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['codTipoCargoRegistro'] . '\', ';
                $cadenaSql .= $_REQUEST ['sueldoRegistro'] . ', ';
                $cadenaSql .= '\'' . $_REQUEST ['tipoSueldoRegistro'] . '\', ';
                $cadenaSql .= '\'' . 'Activo' . '\' ';
                $cadenaSql .= ') ';
                echo $cadenaSql;
                break;
            
            case 'insertarVinculacion' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'persona.vinculacion_persona_natural ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'fecha_inicio,';
                $cadenaSql .= 'fecha_final,';
                $cadenaSql .= 'id_tipo_vinculacion,';
                $cadenaSql .= 'documento,';
                $cadenaSql .= 'estado_vinculacion,';
                $cadenaSql .= 'estado_vinculacion_dependencia,';
                $cadenaSql .= 'sede,';
                $cadenaSql .= 'dependencia,';
                $cadenaSql .= 'ubicacion_especifica';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= "'".$variable['fechaInicio'] . "',";
                $cadenaSql .= "'".$variable ['fechaFin'] . "',";
                $cadenaSql .= $variable ['tipoVinculacion'] . ', ';
          
                $cadenaSql .= $variable ['cedula'] . ', ';
                $cadenaSql .="'Activo' ,";
               $cadenaSql .="'Activo' ,";
                $cadenaSql .= "'".$variable ['sede'] . "',";
                $cadenaSql .= "'".$variable ['dependencia'] . "',";
                $cadenaSql .= "'".$variable ['ubicacion']. "'" ;
               
                
                $cadenaSql .= ') ';
                $cadenaSql .= "RETURNING  id; ";
                
                break;
             case 'insertarCargo' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= 'novedad.cargoxfuncionario ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'id_funcionario,';
                $cadenaSql .= 'codigo_cargo ';
                
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= "'".$variable['id_funcionario'] . "',";
             
                $cadenaSql .= "'".$variable ['codigo_cargo']. "'" ;
               
                
                $cadenaSql .= ') ';
                $cadenaSql .= "RETURNING  id_funcionario; ";
                
                break;
            case 'actualizarRegistro' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= $prefijo . 'pagina ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'nombre,';
                $cadenaSql .= 'descripcion,';
                $cadenaSql .= 'modulo,';
                $cadenaSql .= 'nivel,';
                $cadenaSql .= 'parametro';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= '\'' . $_REQUEST ['nombrePagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['descripcionPagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['moduloPagina'] . '\', ';
                $cadenaSql .= $_REQUEST ['nivelPagina'] . ', ';
                $cadenaSql .= '\'' . $_REQUEST ['parametroPagina'] . '\'';
                $cadenaSql .= ') ';
                break;
            
            case 'buscarRegistro' :
                
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_pagina as PAGINA, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                //$cadenaSql .= 'descripcion as DESCRIPCION,';
                //$cadenaSql .= 'modulo as MODULO,';
                //$cadenaSql .= 'nivel as NIVEL,';
                //$cadenaSql .= 'parametro as PARAMETRO ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= $prefijo . 'pagina ';
                //$cadenaSql .= 'WHERE ';
                //$cadenaSql .= 'nombre=\'' . $_REQUEST ['nombrePagina'] . '\' ';
                break;
                
             case 'buscarRegistroxCargo' :
                
                	$cadenaSql = 'SELECT ';
                        $cadenaSql .= 'codigo_cargo as COD_CARGO, ';
                        $cadenaSql .= 'nivel as NIVEL, ';
                        $cadenaSql .= 'codigo_alternativo as COD_ALTERNATIVO,';
                        $cadenaSql .= 'grado as GRADO,';
                        $cadenaSql .= 'nombre as NOMBRE,';
                        $cadenaSql .= 'cod_tipo_cargo as COD_TIPO, ';
                        $cadenaSql .= 'estado as ESTADO ';
                        $cadenaSql .= 'FROM ';
                        $cadenaSql .= 'parametro.cargo';
//                        $cadenaSql .= 'WHERE ';
//                        $cadenaSql .= 'nombre=\'' . $_REQUEST ['usuario']  . '\' AND ';
//                        $cadenaSql .= 'clave=\'' . $claveEncriptada . '\' ';
                        
                break;
                	
                case 'buscarRegistroUsuarioWhere' :
                		$cadenaSql = 'SELECT ';
                		$cadenaSql .= 'id_usuario as USUARIO, ';
                		$cadenaSql .= 'nombre as NOMBRE, ';
                		$cadenaSql .= 'apellido as APELLIDO, ';
                		$cadenaSql .= 'fecha_reg as FECHA_REG, ';
                		$cadenaSql .= 'edad as EDAD, ';
                		$cadenaSql .= 'telefono as TELEFONO, ';
                		$cadenaSql .= 'direccion as DIRECCION, ';
                		$cadenaSql .= 'ciudad as CIUDAD, ';
                		$cadenaSql .= 'estado as ESTADO ';
                		//$cadenaSql .= 'descripcion as DESCRIPCION,';
                		//$cadenaSql .= 'modulo as MODULO,';
                		//$cadenaSql .= 'nivel as NIVEL,';
                		//$cadenaSql .= 'parametro as PARAMETRO ';
                		$cadenaSql .= 'FROM ';
                		$cadenaSql .= "nomina." .$prefijo . 'usuarios ';
//                		$cadenaSql .= 'WHERE ';
//                		$cadenaSql .= 'fecha_reg <=\'' . $_REQUEST ['fechaRegistroConsulta'] . '\' ';
                break;

            case 'borrarRegistro' :
                $cadenaSql = 'INSERT INTO ';
                $cadenaSql .= $prefijo . 'pagina ';
                $cadenaSql .= '( ';
                $cadenaSql .= 'nombre,';
                $cadenaSql .= 'descripcion,';
                $cadenaSql .= 'modulo,';
                $cadenaSql .= 'nivel,';
                $cadenaSql .= 'parametro';
                $cadenaSql .= ') ';
                $cadenaSql .= 'VALUES ';
                $cadenaSql .= '( ';
                $cadenaSql .= '\'' . $_REQUEST ['nombrePagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['descripcionPagina'] . '\', ';
                $cadenaSql .= '\'' . $_REQUEST ['moduloPagina'] . '\', ';
                $cadenaSql .= $_REQUEST ['nivelPagina'] . ', ';
                $cadenaSql .= '\'' . $_REQUEST ['parametroPagina'] . '\'';
                $cadenaSql .= ') ';
                break;
        
        }
        
        return $cadenaSql;
    
    }
}
?>
