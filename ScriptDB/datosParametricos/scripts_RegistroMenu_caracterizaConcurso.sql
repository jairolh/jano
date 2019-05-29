/*Registro pagina bloque*/

INSERT INTO jano_pagina(
id_pagina,nombre, descripcion, modulo, nivel, parametro)
VALUES ((SELECT MAX(id_pagina)+1 FROM jano_pagina),'caracterizaConcurso','Pagina que permite el gestionar la caracterización de los concursos por parte del administrador','Gestión Concursos','1','jquery=true&jquery-ui=true&jquery-validation=true');


INSERT INTO jano_bloque(
id_bloque, nombre, descripcion, grupo)
VALUES ((SELECT MAX(id_bloque)+1 FROM jano_bloque),'caracterizaConcurso','Pagina que permite el gestionar los factores y criterios de evaluación','gestionConcurso');

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='caracterizaConcurso'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='caracterizaConcurso'),
'C',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='caracterizaConcurso'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='bannerUsuario'),'A',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='caracterizaConcurso'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='menu'),'A',2);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='caracterizaConcurso'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='pie' AND id_bloque>0),'E',1);


/* crear rol */


/** menu y enlaces Parametros Generales*/


INSERT INTO jano_menu(id_menu, nombre, etiqueta, descripcion, estado)
VALUES ((SELECT  MAX(id_menu)+1 cod FROM jano_menu),'Gestionar Concursos', 'Gestión Concursos', 'Menu para el acceso a la funcionalidad para gestion de los parametrso de los concursos', '1');

INSERT INTO jano_grupo_menu(id_grupo, id_menu, nombre, etiqueta, descripcion, estado, id_grupo_padre, posicion)
VALUES ((SELECT  MAX(id_grupo)+1 cod FROM jano_grupo_menu),
 (SELECT  distinct id_menu cod FROM jano_menu where nombre LIKE 'Gestionar Concursos'),
 'Modulo gestión para caracterización de Concursos', 'Parametros Generales', 'Grupo de menu que contiene las opciones de acceso a los modulos Gestión de criteriós de evaluación', 1,0, 1);

/* Criterios Evaluación */

INSERT INTO jano_enlace(id_enlace, nombre, etiqueta, descripcion, url_host_enlace, pagina_enlace,parametros)
VALUES ((SELECT  MAX(id_enlace)+1 cod FROM jano_enlace),'Modulo gestión de Criterios de evaluación','Criterios de Evaluación', 'Enlace para acceso al modulo de gestion de criterios de evaluación', '', 'caracterizaConcurso','&opcion=gestionCriterio');

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Gestión Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Administrador'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Parametros Generales'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Criterios de Evaluación'),
'Servicio para acceso al módulo de gestion de caracterización de concurso', 1);

/* Modalidades Concurso*/

INSERT INTO jano_enlace(id_enlace, nombre, etiqueta, descripcion, url_host_enlace, pagina_enlace,parametros)
VALUES ((SELECT  MAX(id_enlace)+1 cod FROM jano_enlace),'Modulo gestión de Modalidades de Concursos','Modalidad Concurso', 'Enlace para acceso al modulo de gestion de modalidades de Concursos', '', 'caracterizaConcurso','&opcion=gestionModalidad');

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Gestión Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Administrador'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Parametros Generales'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Modalidad Concurso'),
'Servicio para acceso al módulo de gestion de modalidad de concurso', 1);

/* Fases / actividades calendario concurso*/

INSERT INTO jano_enlace(id_enlace, nombre, etiqueta, descripcion, url_host_enlace, pagina_enlace,parametros)
VALUES ((SELECT  MAX(id_enlace)+1 cod FROM jano_enlace),'Modulo gestión de actividades para calendario de Concursos','Fases Concurso', 'Enlace para acceso al modulo de gestion de actividades de Concursos', '', 'caracterizaConcurso','&opcion=gestionActividades');

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Gestión Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Administrador'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Parametros Generales'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Fases Concurso'),
'Servicio para acceso al módulo de gestion de actividades de concurso', 1);





