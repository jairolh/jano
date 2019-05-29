	
/**EVALUADOR**/	

INSERT INTO jano_rol(
            rol_id, rol_nombre, rol_alias, rol_descripcion, estado_registro_id, 
            rol_fecha_registro)
VALUES ( (SELECT  MAX(rol_id)+1 cod FROM jano_rol), 'Jurado','Jurado',' Jurado Evaluador para los criterios de evaluación ',1,'2017-02-23');

INSERT INTO jano_rol_subsistema(rol_id, id_subsistema, estado)
VALUES ((SELECT  distinct rol_id from jano_rol where trim(rol_alias) LIKE 'Jurado'),
(SELECT DISTINCT id_subsistema FROM jano_subsistema where trim(etiketa) LIKE 'Gestión Concurso'),'1');

/**Verificador**/

INSERT INTO jano_rol(
            rol_id, rol_nombre, rol_alias, rol_descripcion, estado_registro_id, 
            rol_fecha_registro)
VALUES ( (SELECT  MAX(rol_id)+1 cod FROM jano_rol), 'Docencia','Docencia','Perfil Verificador de requisitos de perfiles y evaluación hoja de vida de docentes ',1,'2017-02-23');


INSERT INTO jano_rol_subsistema(rol_id, id_subsistema, estado)
VALUES ((SELECT  distinct rol_id from jano_rol where trim(rol_alias) LIKE 'Docencia'),
(SELECT DISTINCT id_subsistema FROM jano_subsistema where trim(etiketa) LIKE 'Gestión Concurso'),'1');


INSERT INTO jano_rol(
            rol_id, rol_nombre, rol_alias, rol_descripcion, estado_registro_id, 
            rol_fecha_registro)
VALUES ( (SELECT  MAX(rol_id)+1 cod FROM jano_rol), 'Recursos Humanos','Personal','Perfil Verificador de requisitos de perfiles y evaluación hoja de vida de administrativos',1,'2017-02-23');


INSERT INTO jano_rol_subsistema(rol_id, id_subsistema, estado)
VALUES ((SELECT  distinct rol_id from jano_rol where trim(rol_alias) LIKE 'Personal'),
(SELECT DISTINCT id_subsistema FROM jano_subsistema where trim(etiketa) LIKE 'Gestión Concurso'),'1');



/**Verificador**/

INSERT INTO jano_rol(
            rol_id, rol_nombre, rol_alias, rol_descripcion, estado_registro_id, 
            rol_fecha_registro)
VALUES ( (SELECT  MAX(rol_id)+1 cod FROM jano_rol), 'Instituto de Lenguas - ILUD','ILUD','Perfil Evaluador Segúnda Lengua',1,'2017-02-23');


INSERT INTO jano_rol_subsistema(rol_id, id_subsistema, estado)
VALUES ((SELECT  distinct rol_id from jano_rol where trim(rol_alias) LIKE 'ILUD'),
(SELECT DISTINCT id_subsistema FROM jano_subsistema where trim(etiketa) LIKE 'Gestión Concurso'),'1');


/* insertar tipo jurado*/

INSERT INTO concurso.jurado_tipo(id, nombre, descripcion, estado)
VALUES (DEFAULT,'Interno','Evaluador con vinculación activa con la institución','A');

INSERT INTO concurso.jurado_tipo(id, nombre, descripcion, estado)
VALUES (DEFAULT,'Externo','Evaluador Sin vinculación activa con la institución','A');
