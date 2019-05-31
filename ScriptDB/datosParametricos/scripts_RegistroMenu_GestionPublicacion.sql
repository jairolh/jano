/*Registro pagina bloque*/

INSERT INTO jano_pagina(
id_pagina,nombre, descripcion, modulo, nivel, parametro)
VALUES ((SELECT MAX(id_pagina)+1 FROM jano_pagina),'publicacion','Pagina que permite el gestionar las publicaciones sin cabecera y pie','Publicacion','1','jquery=2.1.4.min&jquery-ui=true&jquery-validation=true&datatables=true&bootstrapcss=true&bootstrap=true   ');

INSERT INTO jano_bloque(
id_bloque, nombre, descripcion, grupo)
VALUES ((SELECT MAX(id_bloque)+1 FROM jano_bloque),'gestionPublicacion','Bloque para gestion de publicaciones de resultados concursos','');

INSERT INTO jano_bloque_pagina(idrelacion,id_pagina, id_bloque, seccion, posicion) 
VALUES ((SELECT MAX(idrelacion)+1 sec FROM jano_bloque_pagina),
(SELECT id_pagina FROM jano_pagina WHERE nombre='publicacion'),
(SELECT id_bloque FROM jano_bloque WHERE nombre='gestionPublicacion'),
'C',1);


/* crear rol */


/** menu y enlaces Parametros Generales*/





