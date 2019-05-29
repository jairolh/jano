/*Registro pagina bloque*/

INSERT INTO jano_pagina(
id_pagina,nombre, descripcion, modulo, nivel, parametro)
VALUES ((SELECT MAX(id_pagina)+1 FROM jano_pagina),'detalleConcurso','Pagina que permite el gestionar la caracterización de los concursos por parte del administrador','Gestión Concursos','1','jquery=true&jquery-ui=true&jquery-validation=true');

INSERT INTO jano_bloque(
id_bloque, nombre, descripcion, grupo)
VALUES ((SELECT MAX(id_bloque)+1 FROM jano_bloque),'detalleConcurso','Pagina que permite describir las caracteristicas de los concursos a presentar','gestionConcurso');


INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='detalleConcurso'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='detalleConcurso'),
'C',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='detalleConcurso'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='bannerUsuario'),'A',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='detalleConcurso'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='menu'),'A',2);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='detalleConcurso'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='pie' AND id_bloque>0),'E',1);


/* crear rol */


/** menu y enlaces Parametros Generales*/

INSERT INTO jano_grupo_menu(id_grupo, id_menu, nombre, etiqueta, descripcion, estado, id_grupo_padre, posicion)
VALUES ((SELECT  MAX(id_grupo)+1 cod FROM jano_grupo_menu),
 (SELECT  distinct id_menu cod FROM jano_menu where nombre LIKE 'Gestionar Concursos'),
 'Modulo gestión para descripcion de Concursos', 'Concursos', 'Grupo de menu que contiene las opciones de acceso a los modulos Gestión de descripcion de concursos', 1,0, 1);

/* Descripcion  Concurso*/


INSERT INTO jano_enlace(id_enlace, nombre, etiqueta, descripcion, url_host_enlace, pagina_enlace,parametros)
VALUES ((SELECT  MAX(id_enlace)+1 cod FROM jano_enlace),'Modulo gestión de detalle de concursos','Detalle Concurso', 'Enlace para acceso al modulo para gestion de las diferentes modalidades de concursos', '', 'detalleConcurso','&opcion=listar');

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Gestión Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Administrador'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Concursos'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Detalle Concurso'),
'Servicio para acceso al módulo de gestion de detalle de concursos', 1);

