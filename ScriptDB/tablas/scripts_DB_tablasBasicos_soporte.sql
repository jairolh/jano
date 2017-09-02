-- Table: general.tipo_documento

-- DROP TABLE general.tipo_documento;

CREATE TABLE general.tipo_soporte
(
  tipo_soporte numeric(8,0) NOT NULL,
  nombre character varying(40) NOT NULL,
  ubicacion character varying(100) NOT NULL,
  descripcion character varying(500) NOT NULL,
  estado character varying,	
  CONSTRAINT pk_tipo_soporte PRIMARY KEY (tipo_soporte)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE general.tipo_soporte
  OWNER TO jano_admin;

-- Index: general.tipo_documento_pk

-- DROP INDEX general.tipo_documento_pk;

CREATE UNIQUE INDEX tipo_soporte_pk
  ON general.tipo_soporte
  USING btree
  (tipo_soporte);




-- Table: concurso.persona

-- DROP TABLE concurso.persona;


CREATE TABLE concurso.persona
(
  consecutivo serial NOT NULL,
  tipo_identificacion character varying(10) NOT NULL,
  identificacion character varying(25) NOT NULL DEFAULT '0'::character varying,
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
      REFERENCES public.jano_tipo_identificacion (tipo_identificacion) MATCH SIMPLE
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

/****************************************/


CREATE TABLE concurso.soporte
( consecutivo_soporte serial NOT NULL,
  tipo_soporte integer NOT NULL,
  consecutivo_persona integer NOT NULL,
  tipo_dato character varying(100) NOT NULL,
  consecutivo_dato numeric(16,0) NOT NULL,
  nombre character varying(100) NOT NULL,
  alias character varying(100) NOT NULL,
  estado character varying,
  sexo character varying(2),
  CONSTRAINT pk_soporte PRIMARY KEY (consecutivo_soporte),
  CONSTRAINT fk_soporte__relations_tipo FOREIGN KEY (tipo_soporte)
      REFERENCES general.tipo_soporte (tipo_soporte) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_persona__relations_consecutivo FOREIGN KEY (consecutivo_persona)
      REFERENCES concurso.persona (consecutivo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.soporte
  OWNER TO jano_admin;








