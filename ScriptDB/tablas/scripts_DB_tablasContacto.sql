

-- Table: concurso.contacto

-- DROP TABLE concurso.contacto;


CREATE TABLE concurso.contacto
(
  consecutivo_contacto serial NOT NULL,
  consecutivo_persona integer NOT NULL,
  pais_residencia numeric(10,0),
  departamento_residencia numeric(10,0),
  ciudad_residencia numeric(10,0),
  direccion_residencia character varying(255) NOT NULL DEFAULT ''::character varying,
  correo character varying(100) DEFAULT ''::character varying NOT NULL,
  correo_secundario character varying(100) ,
  telefono character varying(50) DEFAULT ''::character varying NOT NULL,
  celular character varying(50) ,
  CONSTRAINT pk_contacto PRIMARY KEY (consecutivo_contacto),
  CONSTRAINT fk_persona__relations_residencia FOREIGN KEY (ciudad_residencia)
      REFERENCES general.ciudad (id_ciudad) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_persona__relations_consecutivo FOREIGN KEY (consecutivo_persona)
      REFERENCES concurso.persona (consecutivo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT

)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.contacto
  OWNER TO jano_admin;

-- Index: concurso.persona_pk

-- DROP INDEX concurso.persona_pk;

CREATE UNIQUE INDEX contacto_persona_pk
  ON concurso.contacto
  USING btree
  (consecutivo_persona);

