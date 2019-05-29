/*Registro pagina bloque*/

INSERT INTO jano_pagina(
id_pagina,nombre, descripcion, modulo, nivel, parametro)
VALUES ((SELECT MAX(id_pagina)+1 FROM jano_pagina),'evaluacionAspirantes','Página que permite gestionar las evaluaciones de los aspirantes','Gestion Concurso','1','jquery=2.1.4.min&jquery-ui=true&jquery-validation=true&datatables=true&bootstrapcss=true&bootstrap=true   ');

INSERT INTO jano_bloque(
id_bloque, nombre, descripcion, grupo)
VALUES ((SELECT MAX(id_bloque)+1 FROM jano_bloque),'evaluacion','Bloque que gestiona la evaluación de los aspirantes','gestionConcurso');

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='evaluacionAspirantes'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='evaluacion'),
'C',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='evaluacionAspirantes'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='bannerUsuario'),'A',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='evaluacionAspirantes'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='menu'),'A',2);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='evaluacionAspirantes'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='pie' AND id_bloque>0),'E',1);

/* crear rol */


/** menu y enlaces Parametros Generales*/

INSERT INTO jano_enlace(id_enlace, nombre, etiqueta, descripcion, url_host_enlace, pagina_enlace,parametros)
VALUES ((SELECT  MAX(id_enlace)+1 cod FROM jano_enlace),'Modulo para la evaluación de los aspirantes a un perfil','Evaluar aspirantes', 'Enlace a la página para la evaluación de los aspirantes', '', 'evaluacionAspirantes','');

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Gestión Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Jurado'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Evaluación'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Evaluar aspirantes'),
'Servicio para evaluar a los aspirantes asignados', 1);

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Gestión Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'ILUD'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Evaluación'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Evaluar aspirantes'),
'Servicio para evaluar a los aspirantes asignados', 1);

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Gestión Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Docencia'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Evaluación'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Evaluar aspirantes'),
'Servicio para evaluar a los aspirantes asignados', 1);

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Gestión Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Personal'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Evaluación'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Evaluar aspirantes'),
'Servicio para evaluar a los aspirantes asignados', 1);
