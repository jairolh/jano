-- Table: concurso.evaluacion_grupo
-- DROP TABLE concurso.evaluacion_grupo;

CREATE TABLE concurso.evaluacion_grupo
(
  id serial NOT NULL,
  id_evaluador character varying(25) NOT NULL,
  id_perfil integer NOT NULL,
  fecha_registro character varying(20) ,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,
  CONSTRAINT pk_id_evaluacion_grupo PRIMARY KEY (id),
  CONSTRAINT fk_grupo_relations_usuario FOREIGN KEY (id_evaluador)
      REFERENCES public.jano_usuario (id_usuario) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_evaluacion_relations_perfil FOREIGN KEY (id_perfil)
      REFERENCES concurso.concurso_perfil (consecutivo_perfil) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.evaluacion_grupo
  OWNER TO jano_admin;


-- Table: concurso.evaluacion_reclamacion
-- DROP TABLE concurso.evaluacion_reclamacion;

CREATE TABLE concurso.evaluacion_reclamacion
(
  id serial NOT NULL,
  consecutivo_calendario integer NOT NULL,
  id_inscrito integer NOT NULL,
  observacion character varying(3000) DEFAULT ''::character varying NOT NULL,
  fecha_registro character varying(20) ,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,
  CONSTRAINT pk_id_reclamacion PRIMARY KEY (id),
  CONSTRAINT fk_etapa__relations_reclamacion FOREIGN KEY (consecutivo_calendario)
      REFERENCES concurso.concurso_calendario (consecutivo_calendario) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_inscrito_relations_reclamacion FOREIGN KEY (id_inscrito)
      REFERENCES concurso.concurso_inscrito (consecutivo_inscrito) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT;
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.evaluacion_reclamacion
  OWNER TO jano_admin;


-- Table: respuesta.evaluacion_reclamacion
-- DROP TABLE respuesta.evaluacion_reclamacion;

CREATE TABLE concurso.respuesta_reclamacion
(
  id serial NOT NULL,
  id_reclamacion integer NOT NULL, 	
  respuesta character varying(50) DEFAULT ''::character varying NOT NULL,
  observacion character varying(3000) DEFAULT ''::character varying NOT NULL,
  fecha_registro character varying(20) ,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,
  id_evaluar_respuesta  integer NOT NULL,
  id_evaluador character varying(25) NOT NULL,
  CONSTRAINT pk_id_respuesta PRIMARY KEY (id),
  CONSTRAINT fk_respuesta__relations_reclamacion FOREIGN KEY (id_reclamacion)
      REFERENCES concurso.evaluacion_reclamacion (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.respuesta_reclamacion
  OWNER TO jano_admin;




-- Table: concurso.evaluacion_final
-- DROP TABLE concurso.evaluacion_final;

CREATE TABLE concurso.evaluacion_final
(
  id serial NOT NULL,
  id_inscrito integer NOT NULL,
  id_evaluar  integer NOT NULL,
  puntaje_final character varying(8) NOT NULL DEFAULT ''::character varying,
  observacion character varying(3000),   	
  fecha_registro character varying(20) ,
  aprobo character varying(8),
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,   	
  CONSTRAINT pk_id_evaluacion_final PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.evaluacion_final
  OWNER TO jano_admin;


-- Table: concurso.evaluacion_final
-- DROP TABLE concurso.evaluacion_final;

CREATE TABLE concurso.evaluacion_parcial
(
  id serial NOT NULL,
  id_grupo integer NOT NULL,
  id_inscrito integer NOT NULL,
  id_evaluar  integer NOT NULL,
  puntaje_parcial character varying(8) NOT NULL DEFAULT ''::character varying,
  observacion character varying(3000),   	
  fecha_registro character varying(20) ,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,   	
  id_evaluacion_final integer,
  id_reclamacion integer,
  CONSTRAINT pk_id_evaluacion_parcial PRIMARY KEY (id),
  CONSTRAINT fk_parcial_relations_grupo FOREIGN KEY (id_grupo)
      REFERENCES concurso.evaluacion_grupo (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_parcial_relations_inscrito FOREIGN KEY (id_inscrito)
      REFERENCES concurso.concurso_inscrito (consecutivo_inscrito) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_parcial_relations_evaluar FOREIGN KEY (id_evaluar)
      REFERENCES concurso.concurso_evaluar (consecutivo_evaluar) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_parcial_relations_final FOREIGN KEY (id_evaluacion_final)
      REFERENCES concurso.evaluacion_final (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_parcial_relations_reclamo FOREIGN KEY (id_reclamacion)
      REFERENCES concurso.evaluacion_reclamacion (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.evaluacion_parcial
  OWNER TO jano_admin;


