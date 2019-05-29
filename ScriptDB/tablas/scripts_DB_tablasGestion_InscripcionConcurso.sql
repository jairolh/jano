-- Table: concurso.concurso_inscrito
-- DROP TABLE concurso.concurso_inscrito;

CREATE TABLE concurso.concurso_inscrito
(
  consecutivo_inscrito serial NOT NULL,
  consecutivo_perfil integer NOT NULL,
  consecutivo_persona integer NOT NULL,
  fecha_registro character varying(20),
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,
  autorizacion boolean NOT NULL,
  CONSTRAINT pk_concurso_inscrito PRIMARY KEY (consecutivo_inscrito),
  CONSTRAINT fk_inscrito__relations_perfil FOREIGN KEY (consecutivo_perfil)
      REFERENCES concurso.concurso_perfil (consecutivo_perfil) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_inscrito__relations_persona FOREIGN KEY (consecutivo_persona)
      REFERENCES concurso.persona (consecutivo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.concurso_inscrito
  OWNER TO jano_admin;
COMMENT ON COLUMN concurso.concurso_inscrito.autorizacion IS 'Campo que almacena la respuesta para dar autorización a la Universidad para publicar la información relacionada con su Hoja de Vida';

-- Index: concurso.idx_concurso_inscrito
-- DROP INDEX concurso.idx_concurso_inscrito;

CREATE UNIQUE INDEX idx_concurso_inscrito
  ON concurso.concurso_inscrito
  USING btree
  (consecutivo_perfil, consecutivo_persona);

-- Table: concurso.concurso_inscrito
-- DROP TABLE concurso.soporte_inscrito;

CREATE TABLE concurso.soporte_inscrito
( consecutivo_soporte_ins serial NOT NULL,
  consecutivo_inscrito integer NOT NULL,
  tipo_dato character varying(100) NOT NULL,
  consecutivo_dato numeric(16,0) NOT NULL,
  fuente_dato character varying(100) NOT NULL,
  valor_dato character varying(4000) DEFAULT ''::character varying NOT NULL,
  consecutivo_soporte  integer ,
  nombre_soporte character varying(100) ,
  alias_soporte character varying(100),
  fecha_registro character varying(20) ,
  estado character varying,
  CONSTRAINT pk_soporte_inscrito PRIMARY KEY (consecutivo_soporte_ins),
  CONSTRAINT fk_soporte__relations_inscrito FOREIGN KEY (consecutivo_inscrito)
      REFERENCES concurso.concurso_inscrito (consecutivo_inscrito) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.soporte_inscrito
  OWNER TO jano_admin;

-- Table: concurso.valida_requisito
-- DROP TABLE concurso.valida_requisito;

CREATE TABLE concurso.valida_requisito
( consecutivo_valida serial NOT NULL,
  consecutivo_inscrito integer NOT NULL,
  cumple_requisito character varying(4) NOT NULL,
  observacion character varying(3000) DEFAULT ''::character varying NOT NULL,
  fecha_registro character varying(20) ,
  estado character varying,
  CONSTRAINT pk_valida_requisito PRIMARY KEY (consecutivo_valida),
  CONSTRAINT fk_soporte__relations_inscrito FOREIGN KEY (consecutivo_inscrito)
      REFERENCES concurso.concurso_inscrito (consecutivo_inscrito) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.valida_requisito
  OWNER TO jano_admin;


-- Table: concurso.valida_requisito
-- DROP TABLE concurso.valida_requisito;

CREATE TABLE concurso.etapa_inscrito
( consecutivo_etapa serial NOT NULL,
  consecutivo_inscrito integer NOT NULL,
  consecutivo_calendario integer NOT NULL,
  observacion character varying(2000) DEFAULT ''::character varying NOT NULL,
  fecha_registro character varying(20) ,
  estado character varying,
  CONSTRAINT pk_etapa_inscrito PRIMARY KEY (consecutivo_etapa),
  CONSTRAINT fk_etapa__relations_inscrito FOREIGN KEY (consecutivo_inscrito)
      REFERENCES concurso.concurso_inscrito (consecutivo_inscrito) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_etapa__relations_calendario FOREIGN KEY (consecutivo_calendario)
      REFERENCES concurso.concurso_calendario (consecutivo_calendario) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.valida_requisito
  OWNER TO jano_admin;


-- Index: concurso.idx_concurso_inscrito
-- DROP INDEX concurso.idx_concurso_inscrito;

CREATE UNIQUE INDEX idx_etapa_inscrito
  ON concurso.etapa_inscrito
  USING btree
  (consecutivo_inscrito, consecutivo_calendario);

