/*Registro pagina bloque*/

INSERT INTO jano_pagina(
id_pagina,nombre, descripcion, modulo, nivel, parametro)
VALUES ((SELECT MAX(id_pagina)+1 FROM jano_pagina),'gestionConcursante','Pagina que permite el gestionar la hoja de vida por parte del concursante','Gestión Concursante','1','jquery=true&jquery-ui=true&jquery-validation=true');


INSERT INTO jano_bloque(
id_bloque, nombre, descripcion, grupo)
VALUES ((SELECT MAX(id_bloque)+1 FROM jano_bloque),'gestionHoja','Pagina que permite el gestionar la hoja de vida','gestionConcursante');


INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='gestionConcursante'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='gestionHoja'),
'C',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='gestionConcursante'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='bannerUsuario'),'A',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='gestionConcursante'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='menu'),'A',2);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='gestionConcursante'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='pie' AND id_bloque>0),'E',1);


/* crear rol */


INSERT INTO jano_subsistema( id_subsistema, nombre, etiketa, id_pagina, observacion)
VALUES ((SELECT  MAX( id_subsistema)+1 cod FROM jano_subsistema),'Módulo ejecución concurso', 'Concurso', 
(SELECT  id_pagina FROM jano_pagina WHERE nombre ='indexjano'), 'Subsistema para ejecucion de Concursos');

INSERT INTO jano_rol(
            rol_id, rol_nombre, rol_alias, rol_descripcion, estado_registro_id, 
            rol_fecha_registro)
VALUES ( (SELECT  MAX(rol_id)+1 cod FROM jano_rol), 'Concursante','Concursante','Concursante',1,'2017-02-23');

INSERT INTO jano_rol_subsistema(rol_id, id_subsistema, estado)
VALUES ((SELECT  distinct rol_id from jano_rol where trim(rol_alias) LIKE 'Concursante'),
	(SELECT DISTINCT id_subsistema FROM jano_subsistema where trim(etiketa) LIKE 'Concurso'),'1');

/** menu y enlaces*/

INSERT INTO jano_menu(id_menu, nombre, etiqueta, descripcion, estado)
VALUES ((SELECT  MAX(id_menu)+1 cod FROM jano_menu),'Gestionar Hoja Vida', 'Perfil', 'Menu para el acceso a la gestion de la hoja de vida', '1');

INSERT INTO jano_grupo_menu(id_grupo, id_menu, nombre, etiqueta, descripcion, estado, id_grupo_padre, posicion)
VALUES ((SELECT  MAX(id_grupo)+1 cod FROM jano_grupo_menu),
 (SELECT  distinct id_menu cod FROM jano_menu where nombre LIKE 'Gestionar Hoja Vida'),
 'Modulo gestión de Hoja de vida', 'Hoja de Vida', 'Grupo de menu que contiene las opciones de acceso a los modulos Gestión de hoja de vida', 1,0, 1);

INSERT INTO jano_enlace(id_enlace, nombre, etiqueta, descripcion, url_host_enlace, pagina_enlace,parametros)
VALUES ((SELECT  MAX(id_enlace)+1 cod FROM jano_enlace),'Modulo gestión de Hoja de vida','Registro Hoja de Vida', 'Enlace para acceso al modulo de gestion de hojas de vida', '', 'gestionConcursante','');

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Concursante'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Hoja de Vida'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Registro Hoja de Vida'),
'Servicio para acceso al módulo de gestion de Hoja de vida', 1);


