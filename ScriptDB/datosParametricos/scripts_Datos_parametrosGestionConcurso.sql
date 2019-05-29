
/*PERSONA SISTEMA*/

INSERT INTO concurso.persona(
            consecutivo, tipo_identificacion, identificacion, nombre, apellido, 
            lugar_nacimiento, fecha_nacimiento, pais_nacimiento, departamento_nacimiento, 
            sexo)
    VALUES (0, 'NA', '0', 'Sistema', 'Concursos','96','2017-03-01','112','1227','S');




/* SOPORTES HOJA VIDA*/
INSERT INTO concurso.factor_evaluacion(consecutivo_factor, nombre, estado)
VALUES (DEFAULT,'Hoja de Vida','A');
INSERT INTO concurso.factor_evaluacion(consecutivo_factor, nombre, estado)
VALUES (DEFAULT,'Competencias profesionales y comunicativas','A');



/*sOPORTES GESTIÓN CONCURSO*/
