
/*sOPORTES GESTIÓN CONCURSO*/

/** OBLIGATORIOS*/
INSERT INTO concurso.actividad_calendario(consecutivo_actividad, nombre, descripcion, estado)
VALUES (DEFAULT,'Inscripción','Actividad Registro de inscripcion al a concurso','A');
INSERT INTO concurso.actividad_calendario(consecutivo_actividad, nombre, descripcion, estado)
VALUES (DEFAULT,'Registro Soportes','Actividad Registro de soportes de hoja de vida','A');
INSERT INTO concurso.actividad_calendario(consecutivo_actividad, nombre, descripcion, estado)
VALUES (DEFAULT,'Evaluar Requisitos','Actividad de verificación de requisitos del perfil','A');
INSERT INTO concurso.actividad_calendario(consecutivo_actividad, nombre, descripcion, estado)
VALUES (DEFAULT,'Lista Elegibles','Actividad de Generar el listado de aspirantes que aprueban el concurso','A');

/**opcionales**/
INSERT INTO concurso.actividad_calendario(consecutivo_actividad, nombre, descripcion, estado)
VALUES (DEFAULT,'Presentar Pruebas','Actividad de presentación Pruebas oral y escrita','A');
INSERT INTO concurso.actividad_calendario(consecutivo_actividad, nombre, descripcion, estado)
VALUES (DEFAULT,'Evaluar Hoja de Vida','Actividad de verificación de hoja de vida y soportes','A');
INSERT INTO concurso.actividad_calendario(consecutivo_actividad, nombre, descripcion, estado)
VALUES (DEFAULT,'Prueba idioma extranjero','Actividad de presentación de pruebas de segunda lengua','A');
INSERT INTO concurso.actividad_calendario(consecutivo_actividad, nombre, descripcion, estado)
VALUES (DEFAULT,'Publicación','Actividad de publicación de resultados','A');


