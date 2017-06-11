-- Schema: concurso

-- DROP SCHEMA concurso;

CREATE SCHEMA concurso
  AUTHORIZATION jano_admin;


-- Table: concurso.persona

-- DROP TABLE concurso.persona;

CREATE TABLE concurso.persona
(
  consecutivo serial NOT NULL,
  tipo_identificacion character varying(10) NOT NULL,
  identificacion numeric(15,0) NOT NULL,
  nombre character varying(50) NOT NULL DEFAULT ''::character varying,
  apellido character varying(50) NOT NULL DEFAULT ''::character varying,
  lugar_nacimiento numeric(10,0),
  fecha_nacimiento date,
  pais_nacimiento numeric(10,0),
  departamento_nacimiento numeric(10,0),
  sexo character varying(2),
  CONSTRAINT pk_persona PRIMARY KEY (consecutivo),
  CONSTRAINT fk_persona__relations_lugar FOREIGN KEY (lugar_nacimiento)
      REFERENCES general.ciudad (id_ciudad) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_persona__relations_tipo_ident FOREIGN KEY (tipo_identificacion)
      REFERENCES jano_tipo_identificacion (tipo_identificacion) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.persona
  OWNER TO jano_admin;

-- Index: concurso.persona_pk

-- DROP INDEX concurso.persona_pk;

CREATE UNIQUE INDEX persona_pk
  ON concurso.persona
  USING btree
  (identificacion);

-- Index: concurso.relationship_54_fk

-- DROP INDEX concurso.relationship_54_fk;

CREATE INDEX relationship_54_fk
  ON concurso.persona
  USING btree
  (lugar_nacimiento);

-- Index: concurso.relationship_5_fk

-- DROP INDEX concurso.relationship_5_fk;

CREATE INDEX relationship_5_fk
  ON concurso.persona
  USING btree
  (tipo_identificacion COLLATE pg_catalog."default");


