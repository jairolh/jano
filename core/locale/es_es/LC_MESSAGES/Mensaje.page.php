<?php
$this->idioma ["paginaNoExiste"] = "<h2>La página que está buscando no existe</h2><p>Esto se considera un error fatal.</p><p>Por favor contacte al administrador del sistema. Posiblemente sea necesario registrarla.</p>";
$this->idioma ["usuarioNoAutorizado"] = "<h2>Insuficientes Privilegios</h2><p>Imposible realizar la acción solicitada.</p><p>Por favor contacte al administrador del sistema.</p>";
$this->idioma ["paginaSinBloques"] = "<h2>Página no válida</h2><p>La página solicitada no tiene bloques asociados.</p><p>Por favor contacte al administrador del sistema.</p>";
$this->idioma ["errorAcceso"] = "<h3>Imposible conectarse a la base de datos.</h3><p>Esto se considera un error no manejable y el aplicativo debe detenerse.</p>";
$this->idioma ["usuarioAnonimo"] = "Anónimo";
$this->idioma ["noInstanciaDatos"] = "Imposible crear una instancia de base de datos.";
$this->idioma ["noDefinido"] = "N/D";
$this->idioma ["sesionNoExiste"] = "<h3>No se pudo rescatar una sesión!!!!</h3><p>Su sesión de trabajo ha expirado o no tiene permiso de acceder a este recurso.</p><p class='textoNegrita centrar'>En unos instantes será redirigido a la página de inicio.</span></p>";
$this->idioma ["errorDatos"] = "<h3>Datos no válidos.</h3><p>Por favor revise los datos ingresados y vuelva a reintentar la operación.</p>";

//valores para la clase Persistencia.class.php

$this->idioma["errorConexion"]="Error, Falló al conectar a la base de datos";
$this->idioma["errorEliminar"]="Falló al eliminar registros";
$this->idioma["errorConsulta"]="La tabla no tiene datos ó la consulta ha fallado";
$this->idioma["errorElementosArray"] = "Error, el numero de elementos de los arrays deben ser iguales";
$this->idioma["errorActualizar"] ="Falló al realizar la Actualización";
$this->idioma["errorInsertar"] ="Falló al realizar la inserción";
$this->idioma['errorProceso'] ="El usuario no posee permisos sobre el proceso";
$this->idioma["errorTablaNoExiste"] ="Error Tabla no Existe";
$this->idioma["errorTablaHNoExiste"] ="Error Tabla Historico no Existe";
$this->idioma["errorCampos"] ="Error Campos invalidos";
$this->idioma["errorValores"] ="Error valores invalidos";
$this->idioma["errorWhere"] ="Error sentencia where invalida";
$this->idioma["errorColumnas"] ="Error no es posible consultar las columnas";
$this->idioma["errorHistorico"] ="Error no es posible crear historico";
$this->idioma["errorNoActualizar"] = "No existen elementos para actualizar";
$this->idioma["errorPks"] = "Error no fue posible recuperar las llaves primarias";
$this->idioma["errorConteo"] = "La tabla esta vacia ó no existe";

//fin valores 


//valores clase DAL para mensajes

$this->idioma["errorRecuperarObjetos"] = "Error recuperando Objetos de la base de datos";
$this->idioma["objetoNoEncontrado"] = "Objeto no encontrado";
$this->idioma["errorValorInvalido"] = "Error valor inv�lido";
$this->idioma["errorSeleccionInvalido"] = "Error indice a seleccionar inv�lido";
$this->idioma["errorTipoInvalido"] = "Error indice fuente inv�lido";
$this->idioma["erroridObjetoEntrada"] = "Error en el formato de entrada del Id Objeto";
$this->idioma["errorParametrosEntrada"] = "Error en el formato de entrada de los par�metros";
$this->idioma["errorOperacionEntrada"] = "Error en el formato de entrada de la operaci�n";
$this->idioma["errorParametrosEntradaIdObjeto"] = "Error id objeto no existe";
$this->idioma["errorColumnaNoExiste"] = "Error atributo no existe";
$this->idioma["errorEntradaParametrosId"] = "Error Id Inv�lido";
$this->idioma["errorEntradaParametrosNombre"] = "Error Nombre Inv�lido";
$this->idioma["errorEntradaParametrosDescripcion"] = "Error Descripci�n Inv�lida";
$this->idioma["errorEntradaParametrosTipo"] = "Error Tipo Inv�lido";
$this->idioma["errorEntradaParametrosValor"] = "Error Valor Inv�lido";
$this->idioma["errorEntradaParametrosEstado"] = "Error Estado Inv�lido";
$this->idioma["errorEntradaParametrosFechas"] = "Error Fechas Inv�lidas";
$this->idioma["errorEntradaParametrosGeneral"] = "Error en la Entrada de elementos";

//columnas
$this->idioma["errorEntradaColumnaInvalida"] = "Error, columna no v�lida";
$this->idioma["errorTipoDatoColumna"] = "Error, tipo dato columna no v�lido";
$this->idioma["errorValorColumna"] = 'Error, valor columna no v�lido';
$this->idioma["errorInput"] = 'Error, recuperando input columna ';
$this->idioma["errorFk"] = 'Error, valor llave foranea incorrecto ';


//por tablas
$this->idioma["errorCreacion"] = "Error Creando ";
$this->idioma["errorLectura"] = "No se encontraron registros de ";
$this->idioma["errorIdNoDefinido"] = "Identificador no definido para el ";
$this->idioma["errorActualizar"] = "Error Actualizando ";
$this->idioma["errorIdNoExiste"] = "Identificador no Existe para el ";
$this->idioma["errorDuplicar"] = "Error Duplicando ";
$this->idioma["errorEliminar"] = "Error Eliminando ";
$this->idioma["errorCambiarEstado"] = "Error Cambiando Estados ";
$this->idioma["errorRegistroPropietario"]="Error, registro propietario ";


$this->idioma["columnaNoExiste"] ="Columna no existe en tabla ";
$this->idioma["usuarioIndefinido"]="Usuario Indefinido";


//fin valores


//valores componente usuario y acceso
////////////////////////////////////////////////////////////////////////////////////////////

$this->idioma["registroObjetoNoExiste"] = "Registro objeto no existe";
$this->idioma["permisoNoEncontrado"] = "Permiso no encontrado";
$this->idioma["usuarioNoExiste"] = "Usuario no existe";
$this->idioma["errorRecuperarPermisos"] = "error recuperando permisos";
$this->idioma["errorRecuperarColumnas"] = "error recuperando columnas";
$this->idioma["errorRecuperarObjetos"] = "error recuperando objetos";
$this->idioma["objetoNoEncontrado"] = "error objeto no encontrado";
$this->idioma["relacionNoExiste"] = "relaci�n no existe";
$this->idioma["usuarioNoAutorizado"]="Error, usuario no autorizado";
$this->idioma["errorPermisosGeneral"]="Error, el usuario no tiene permisos para ejecutar esta operacion";
$this->idioma["usuarioSinPermisos"]="Error, usuario no posee permisos";
$this->idioma["errorCadenaMalFormada"]="Error, cadena mal formada";
$this->idioma["errorSoapCall"]="Error, en llamada al metodo soap";
$this->idioma["errorRecuperarOperadores"]="Error, recuperando operadores";
$this->idioma["errorRegistroPropietario"]="Error, registro propietario";
//fin valores

$this->idioma['errorOperacionNoPermitida']='Error, Operaci�n no permitida ';

//valores para el componente de documentos
$this->idioma["errorVariableArchivoVacia"]="Error, variable FILES vacia";
$this->idioma["errorNombreArray"]="Error, variable nombre debe ser un array";
$this->idioma["errorRutaFisica"]="Error, ruta fisica Invalida, revise si existe y que tenga permisos de escritura";
$this->idioma["errorArchivoExiste"]="Error, Archivo existe con ese nombre";
$this->idioma["errorExtensionTipo"]="Error, extension � o tipo documento inv�lido  ";
$this->idioma["errorTipoMime"] = "Error, tipo MIME Iv�lido";
$this->idioma["errorExtension"] = "Error, extension Iv�lida";
$this->idioma["errorMime"] = "Error, tipo MIME documento incorrecto";
$this->idioma["errorMoverArchivo"] = "Error, no fue posible mover el archivo";
$this->idioma["errorLocalizacionArchivo"] = "Error, no fue posible encontrar el archivo";
$this->idioma["errorNombreHTML"] = 'Error, nombre archivo no valido, no existe en la variable de Archivos';
//tablas
$this->idioma["errorCreacionDocumento"] = "Error Creando Documento";
$this->idioma["errorLecturaDocumento"] = "No se encontraron registros de Documentos";
$this->idioma["errorIdNoDefinidoDocumento"] = "Identificador del documento no definido";
$this->idioma["errorActualizarDocumento"] = "Error Actualizando documento";
$this->idioma["errorIdNoExisteDocumento"] = "Identificador del documento no Existe";
$this->idioma["errorDuplicarDocumento"] = "Error Duplicando documento";
$this->idioma["errorEliminarDocumento"] = "Error Eliminando documento";
$this->idioma["errorCambiarEstadoDocumento"] = "Error Cambiando Estados documento";
//
$this->idioma["errorCreacionDocumentoTipoMIME"] = "Error Creando Documentos Tipos MIME";
$this->idioma["errorLecturaDocumentoTipoMIME"] = "No se encontraron registros de Documentos  Tipos MIME";
$this->idioma["errorIdNoDefinidoDocumentoTipoMIME"] = "Identificador del documento Tipos MIME no definido";
$this->idioma["errorActualizarDocumentoTipoMIME"] = "Error Actualizando documento Tipos MIME";
$this->idioma["errorIdNoExisteDocumentoTipoMIME"] = "Identificador del documento Tipos MIME no Existe";
$this->idioma["errorDuplicarDocumentoTipoMIME"] = "Error Duplicando documento Tipos MIME";
$this->idioma["errorEliminarDocumentoTipoMIME"] = "Error Eliminando documento Tipos MIME";
$this->idioma["errorCambiarEstadoDocumentoTipoMIME"] = "Error Cambiando Estados documento Tipos MIME";
//fin valores


?>