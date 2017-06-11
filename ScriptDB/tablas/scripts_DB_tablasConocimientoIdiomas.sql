-- Table: general.nivel   , generar niveles de los diferentes tipos




CREATE TABLE general.idioma
(
  codigo_idioma numeric(8,0) NOT NULL,
  codigo_iso character varying(8) NOT NULL,
  nombre character varying(40) NOT NULL,
  estado character varying(2),	
  CONSTRAINT pk_codigo_idioma PRIMARY KEY (codigo_idioma)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE general.idioma
  OWNER TO jano_admin;



CREATE UNIQUE INDEX codigo_idioma_pk
  ON general.idioma
  USING btree
  (codigo_idioma);


-- Table: concurso.formacion

-- DROP TABLE concurso.formacion;


CREATE TABLE concurso.conocimiento_idioma
(
  consecutivo_conocimiento serial NOT NULL,
  consecutivo_persona integer NOT NULL,
  codigo_idioma numeric(8,0) NOT NULL, 
  nivel_lee character varying(16),
  nivel_escribe character varying(16), 		
  nivel_habla character varying(16), 		 		
  certificacion character varying(50),
  institucion_certificacion character varying(150),
  CONSTRAINT pk_conocimiento PRIMARY KEY (consecutivo_conocimiento),
  CONSTRAINT fk_conocimiento__relations_persona FOREIGN KEY (consecutivo_persona)
      REFERENCES concurso.persona (consecutivo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_conocimiento__relations_idioma FOREIGN KEY (codigo_idioma)
      REFERENCES general.idioma (codigo_idioma) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.conocimiento_idioma
  OWNER TO jano_admin;

-- Index: concurso.persona_pk

-- DROP INDEX concurso.persona_pk;

CREATE UNIQUE INDEX conocimiento_persona_pk
  ON concurso.conocimiento_idioma
  USING btree
  (consecutivo_persona,codigo_idioma);

