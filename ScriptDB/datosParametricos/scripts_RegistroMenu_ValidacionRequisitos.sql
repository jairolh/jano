/*Registro pagina bloque*/

INSERT INTO jano_pagina(
id_pagina,nombre, descripcion, modulo, nivel, parametro)
VALUES ((SELECT MAX(id_pagina)+1 FROM jano_pagina),'validacionRequisitos','Página que permite validar los requisitos de la inscripción de un aspirante','Gestion Concurso','1','jquery=2.1.4.min&jquery-ui=true&jquery-validation=true&datatables=true&bootstrapcss=true&bootstrap=true   ');

INSERT INTO jano_bloque(
id_bloque, nombre, descripcion, grupo)
VALUES ((SELECT MAX(id_bloque)+1 FROM jano_bloque),'evaluacionConcurso','Bloque que valida los requisitos de la inscripción','gestionConcurso');


INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='validacionRequisitos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='evaluacionConcurso'),
'C',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='validacionRequisitos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='bannerUsuario'),'A',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='validacionRequisitos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='menu'),'A',2);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='validacionRequisitos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='pie' AND id_bloque>0),'E',1);

/* crear rol */


/** menu y enlaces Parametros Generales*/

INSERT INTO jano_grupo_menu(id_grupo, id_menu, nombre, etiqueta, descripcion, estado, id_grupo_padre, posicion)
VALUES ((SELECT  MAX(id_grupo)+1 cod FROM jano_grupo_menu),
 (SELECT  distinct id_menu cod FROM jano_menu where nombre LIKE 'Gestionar Concursos'),
 'Modulo para la evaluación de los concursos', 'Evaluación', 'Grupo de menu que contiene las opciones de acceso a la evaluación de los concursos', 1,0, 1);

INSERT INTO jano_enlace(id_enlace, nombre, etiqueta, descripcion, url_host_enlace, pagina_enlace,parametros)
VALUES ((SELECT  MAX(id_enlace)+1 cod FROM jano_enlace),'Módulo para la evaluación de los aspirantes a un perfil','Validación Requisitos', 'Enlace a la página de validación de requisitos', '', 'validacionRequisitos','');

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Gestión Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Docencia'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Evaluación'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Validación Requisitos'),
'Servicio para la validación de los requisitos', 1);





