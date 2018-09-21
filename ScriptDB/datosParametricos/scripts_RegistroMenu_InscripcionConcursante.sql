/*Registro pagina bloque*/

INSERT INTO jano_pagina(
id_pagina,nombre, descripcion, modulo, nivel, parametro)
VALUES ((SELECT MAX(id_pagina)+1 FROM jano_pagina),'concursosActivos','Página que permite consultar los concursos que se encuentran activos','Gestion Concursante','1','jquery=2.1.4.min&jquery-ui=true&jquery-validation=true&datatables=true&bootstrapcss=true&bootstrap=true   ');

INSERT INTO jano_bloque(
id_bloque, nombre, descripcion, grupo)
VALUES ((SELECT MAX(id_bloque)+1 FROM jano_bloque),'concursosActivos','Bloque de consulta de los concursos activos','gestionConcursante');

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='concursosActivos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='concursosActivos'),
'C',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='concursosActivos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='bannerUsuario'),'A',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='concursosActivos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='menu'),'A',2);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='concursosActivos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='pie' AND id_bloque>0),'E',1);


/* crear rol */


/** menu y enlaces Parametros Generales*/

INSERT INTO jano_grupo_menu(id_grupo, id_menu, nombre, etiqueta, descripcion, estado, id_grupo_padre, posicion)
VALUES ((SELECT  MAX(id_grupo)+1 cod FROM jano_grupo_menu),
 (SELECT  distinct id_menu cod FROM jano_menu where nombre LIKE 'Gestionar Hoja Vida'),
 'Modulo consulta concursos', 'Concursos Activos', 'Grupo de menu que contiene las opciones de acceso a los concursos', 1,0, 2);

INSERT INTO jano_enlace(id_enlace, nombre, etiqueta, descripcion, url_host_enlace, pagina_enlace,parametros)
VALUES ((SELECT  MAX(id_enlace)+1 cod FROM jano_enlace),'Modulo de consulta de los concursos activos','Concursos Activos', 'Enlace para acceso a los concursos activos', '', 'concursosActivos','');

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Ejecución Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Concursante'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Concursos Activos'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Concursos Activos'),
'Servicio para consultar los concursos activos', 1);


/************Registro pagina bloque*****/

INSERT INTO jano_pagina(
id_pagina,nombre, descripcion, modulo, nivel, parametro)
VALUES ((SELECT MAX(id_pagina)+1 FROM jano_pagina),'concursosInscritos','Página que permite consultar los concursos inscritos por el usuario','Gestion Concursante','1','jquery=2.1.4.min&jquery-ui=true&jquery-validation=true&datatables=true&bootstrapcss=true&bootstrap=true   ');

INSERT INTO jano_bloque(
id_bloque, nombre, descripcion, grupo)
VALUES ((SELECT MAX(id_bloque)+1 FROM jano_bloque),'concursosInscritos','Bloque de consulta de los concursos inscritos','gestionConcursante');

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='concursosInscritos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='concursosInscritos'),
'C',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='concursosInscritos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='bannerUsuario'),'A',1);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='concursosInscritos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='menu'),'A',2);

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='concursosInscritos'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='pie' AND id_bloque>0),'E',1);

/* crear rol */



/** menu y enlaces Parametros Generales*/

INSERT INTO jano_enlace(id_enlace, nombre, etiqueta, descripcion, url_host_enlace, pagina_enlace,parametros)
VALUES ((SELECT  MAX(id_enlace)+1 cod FROM jano_enlace),'Modulo de consulta de los consursos inscritos','Concursos Inscritos', 'Enlace para acceso a los concursos inscritos', '', 'concursosInscritos','');

INSERT INTO jano_servicio(id_subsistema, rol_id, id_grupo, id_enlace, descripcion, estado)
VALUES ((SELECT DISTINCT id_subsistema FROM jano_subsistema where etiketa LIKE 'Ejecución Concurso'), 
(SELECT rol_id FROM jano_rol where  rol_alias LIKE 'Concursante'), 
(SELECT id_grupo FROM jano_grupo_menu where etiqueta LIKE 'Concursos Activos'),
(SELECT id_enlace FROM jano_enlace where etiqueta LIKE 'Concursos Inscritos'),
'Servicio para consultar los concursos inscritos', 1);

/******mensaje consentimiento ****/
INSERT INTO public.jano_texto(
            id, tipo, texto, estado)
    VALUES ('DEFAULT', 'mensaje','<p style="text-align:justify">RESPETADO USUARIO</p><p  style="text-align:justify">La Universidad Distrital Francisco José de Caldas, informa que durante el proceso de ejecución de los concursos de Méritos, es necesario realizar tratamiento y publicación de información relacionada con el aspirante. 
<br></br>
Teniendo en cuenta lo anterior, autoriza de manera voluntaria, previa, explícita, informada e inequívoca a la Universidad Distrital Francisco José de Caldas, para realizar el tratamiento de los datos personales con fines institucionales, así como la divulgación de información pública por principio de transparencia y posterior contacto a través de medios telefónicos, electrónicos (SMS, chat, correo electrónico y demás medios considerados electrónicos) físicos y/o personales. De acuerdo a lo establecido mediante lo consagrado en la Ley 1581 de 2012 de protección de datos personales y la Ley 1712 de transparencia y del derecho de acceso a la información pública.
.</p>','A');





