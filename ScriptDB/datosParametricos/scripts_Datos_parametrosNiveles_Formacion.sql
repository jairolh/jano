INSERT INTO general.modalidad_educacion(codigo_modalidad, nombre, estado)
VALUES (1, 'Presencial', 'A'),
       (2, 'Virtual', 'A'),
       (3, 'Distancia (Tradicional)', 'A');

/************* NIVELES HOJA DE VIDA   *************/

INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Primaria', 'Formacion', 'Educación Basica Primaria','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Media Vocacional', 'Formacion', 'Educación Basica Secundaria','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Bachillerato', 'Formacion', 'Educación Secundaria','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Técnica Profesional', 'Formacion', 'Educación Técnica ','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Tecnológica', 'Formacion', 'Educación Tecnológica','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Profesional', 'Formacion', 'Formación Profesional Pregrado','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Especialización', 'Formacion', 'Formación Especialización Profesional','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Maestria', 'Formacion', 'Formación Magister','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Doctorado', 'Formacion', 'Formación Doctorado','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'PostDoctorado', 'Formacion', 'Formación PostDoctorado','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Educación Informal', 'Formacion', 'Formación Doctorado','A');

INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Pública', 'Institucion', 'Indica nivel institucion Pública','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Privada', 'Institucion', 'Indica nivel institucion Privada','A');

INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Profesional', 'Experiencia', 'Indica nivel experiencia profesional','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Pregrado', 'Docencia', 'Indica nivel experiencia en docencia universitaria en pregrado','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Especialización', 'Docencia', 'Indica nivel experiencia en docencia universitaria en Especialización ','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Maestria', 'Docencia', 'Indica nivel experiencia en docencia universitaria en Magister','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Doctorado', 'Docencia', 'Indica nivel experiencia en docencia universitaria en Doctorado','A');

INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Tiempo Completo Ocasional', 'VinculacionDocencia', 'Indica vinculacion en docencia universitaria por tiempo completo','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Medio Tiempo Ocasional', 'VinculacionDocencia', 'Indica vinculacion en docencia universitaria por medio tiempo','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Catedra Contrato', 'VinculacionDocencia', 'Indica vinculacion en docencia universitaria por catedra por contrato','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Catedra Honoarios', 'VinculacionDocencia', 'Indica vinculacion en docencia universitaria por catedra por honorarios','A');

INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Artículo en Revista Nacional', 'Produccion', 'Publicación de Articulo en Revista Nacional','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Artículo en Revista Internacional', 'Produccion', 'Publicación de Articulo en Revista Internacional','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Libro', 'Produccion', 'Publicación de libro','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Capítulo Libro', 'Produccion', 'Publicación de capitulo en libro','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Ponencia', 'Produccion', 'Participacion en ponencias','A');

INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Representación Estudiantil en consejos', 'ActividadAcademica', 'Participación en consejos y organismos de la universidad','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Representación Institucional en Certamenes', 'ActividadAcademica', 'Participación institucional en certamenes académicos, culturales o deportivos','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Asistente académico e investigativo', 'ActividadAcademica', 'Participación en consejos y organismos de la universidad','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Participación en grupos de trabajo', 'ActividadAcademica', 'Participación en grupos de trabajo académico','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Monitorías', 'ActividadAcademica', 'Participacion Monitorías','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Distinciones académicas', 'ActividadAcademica', 'Distinciones académicas otorgadas','A');

INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Bajo', 'Idioma', 'Nivel Bajo de dominio de idioma','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Medio', 'Idioma', 'Nivel Medio de dominio de idioma','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Alto', 'Idioma', 'Nivel Alto de dominio de idioma','A');


/************* NIVELES GESTION DE CONCURSO   *************/

INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Docente', 'TipoConcurso', 'Tipo de concurso Docente','A');
INSERT INTO general.nivel(codigo_nivel, nombre, tipo_nivel, descripcion, estado)
VALUES ((SELECT (max(codigo_nivel)+1) cod from general.nivel),'Administrativo', 'TipoConcurso', 'Tipo de concurso Administrativo','A');


