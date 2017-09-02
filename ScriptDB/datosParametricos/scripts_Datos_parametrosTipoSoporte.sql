/* SOPORTES HOJA VIDA*/
INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'foto','soportes/foto','Fotografia personal','A','png, jpg');
INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteIdentificacion','soportes/identificacion','Copia documento identificación','A','pdf');
INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteDiploma','soportes/formacion','Copia diploma o acta de grado','A','pdf');
INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteTprofesional','soportes/formacion','Copia tarjeta profesional','A','pdf');
INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteExperiencia','soportes/experiencia','Copia certificación experiencia profesional','A','pdf');
INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteDocencia','soportes/docencia','Copia certificación docencial','A','pdf');
INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteInvestigacion','soportes/investigacion','Copia certificaciones investigación','A','pdf');
INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteProduccion','soportes/produccion','Copia certificaciones productos publicados','A','pdf');
INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteActividad','soportes/actividad','Copia certificaciones de actividad académica','A','pdf');

INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteIdioma','soportes/idioma','Copia certificaciones de idioma extranjero','A','pdf');

/*sOPORTES GESTIÓN CONCURSO*/

INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteAcuerdo','soportes/acuerdos','Copia de acuerdo de creación y ejecución de concurso','A','pdf');


INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteAutorizacion','soportes/autorizacion','Copia de autorizacion para modificar fecha de fase del concurso','A','pdf');


/*sOPORTES EVALUACION*/

INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteEvaluacion','soportes/evaluacion','Documento detallado evaluacion de criterios','A','pdf');

INSERT INTO general.tipo_soporte(tipo_soporte, nombre, ubicacion, descripcion, estado, extencion_permitida)
VALUES ((SELECT (max(tipo_soporte)+1) cod from general.tipo_soporte),'soporteReclamacion','soportes/reclamacion','Documento referente a la reclamación del concursante','A','pdf');

