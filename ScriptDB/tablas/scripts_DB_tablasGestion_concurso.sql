-- Table: concurso.factor_evaluacion

-- DROP TABLE concurso.factor_evaluacion;


CREATE TABLE concurso.factor_evaluacion
(
  consecutivo_factor serial NOT NULL,
  nombre character varying(100) NOT NULL DEFAULT ''::character varying,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,
  CONSTRAINT pk_factor PRIMARY KEY (consecutivo_factor)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.factor_evaluacion
  OWNER TO jano_admin;



-- DROP TABLE concurso.criterio_evaluacion;


CREATE TABLE concurso.criterio_evaluacion
(
  consecutivo_criterio serial NOT NULL,
  consecutivo_factor integer NOT NULL,
  nombre character varying(100) NOT NULL DEFAULT ''::character varying,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,
  CONSTRAINT pk_criterio PRIMARY KEY (consecutivo_criterio),
  CONSTRAINT fk_criterio__relations_factor FOREIGN KEY (consecutivo_factor)
      REFERENCES concurso.factor_evaluacion (consecutivo_factor) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.criterio_evaluacion
  OWNER TO jano_admin;

-- Table: concurso.modalidad_concurso

-- DROP TABLE concurso.modalidad_concurso;


CREATE TABLE concurso.modalidad_concurso
(
  consecutivo_modalidad serial NOT NULL,
  codigo_nivel_concurso numeric(8,0) NOT NULL, 		
  nombre character varying(100) NOT NULL DEFAULT ''::character varying,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,
  CONSTRAINT pk_modalidad PRIMARY KEY (consecutivo_modalidad)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.modalidad_concurso
  OWNER TO jano_admin;

-- Table: concurso.concurso

-- DROP TABLE concurso.concurso;


CREATE TABLE concurso.concurso
(
  consecutivo_concurso serial NOT NULL,
  consecutivo_modalidad integer NOT NULL,
  nombre  character varying(255) DEFAULT ''::character varying NOT NULL,
  acuerdo character varying(50) DEFAULT ''::character varying NOT NULL,
  descripcion character varying(3000) DEFAULT ''::character varying NOT NULL,
  fecha_inicio character varying(20) ,
  fecha_fin character varying(20) ,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,   	
  CONSTRAINT pk_concurso PRIMARY KEY (consecutivo_concurso),
  CONSTRAINT fk_concurso__relations_modalidad FOREIGN KEY (consecutivo_modalidad)
      REFERENCES concurso.modalidad_concurso (consecutivo_modalidad) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.concurso
  OWNER TO jano_admin;

-- Table: concurso.concurso_evaluar

-- DROP TABLE concurso.concurso_evaluar;


CREATE TABLE concurso.concurso_evaluar
(
  consecutivo_evaluar serial NOT NULL,
  consecutivo_concurso integer NOT NULL,
  consecutivo_criterio integer NOT NULL,
  maximo_puntos numeric(8,0) NOT NULL, 
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,   	
  CONSTRAINT pk_concurso_evaluar PRIMARY KEY (consecutivo_evaluar),
  CONSTRAINT fk_evaluar__relations_concurso FOREIGN KEY (consecutivo_concurso)
      REFERENCES concurso.concurso (consecutivo_concurso) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_evaluar__relations_criterio FOREIGN KEY (consecutivo_criterio)
      REFERENCES concurso.criterio_evaluacion (consecutivo_criterio) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.concurso_evaluar
  OWNER TO jano_admin;

-- Index: concurso.persona_pk
-- DROP INDEX concurso.persona_pk;

CREATE UNIQUE INDEX idx_concurso_evaluar
  ON concurso.concurso_evaluar
  USING btree
  (consecutivo_concurso, consecutivo_criterio);


-- Table: concurso.concurso_perfil
-- DROP TABLE concurso.concurso_perfil;

CREATE TABLE concurso.concurso_perfil
(
  consecutivo_perfil serial NOT NULL,
  consecutivo_concurso integer NOT NULL,
  nombre  character varying(255) DEFAULT ''::character varying NOT NULL,
  descripcion character varying(3000) DEFAULT ''::character varying NOT NULL,
  requisitos character varying(5000) DEFAULT ''::character varying NOT NULL,
  dependencia character varying(100) ,
  area character varying(100) ,
  vacantes numeric(4,0) NOT NULL DEFAULT 1,   	
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,   	
  CONSTRAINT pk_concurso_perfil PRIMARY KEY (consecutivo_perfil),
  CONSTRAINT fk_perfil__relations_concurso FOREIGN KEY (consecutivo_concurso)
      REFERENCES concurso.concurso (consecutivo_concurso) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.concurso_perfil
  OWNER TO jano_admin;

/* TABLAS PARA CALENDARIO */

-- Table: concurso.concurso_actividades
-- DROP TABLE concurso.concurso_actividades;

CREATE TABLE concurso.actividad_calendario
(
  consecutivo_actividad serial NOT NULL,
  nombre character varying(100) NOT NULL DEFAULT ''::character varying,
  descripcion character varying(250) DEFAULT ''::character varying NOT NULL,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,
  CONSTRAINT pk_actividad_calendario PRIMARY KEY (consecutivo_actividad)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.actividad_calendario
  OWNER TO jano_admin;



-- Table: concurso.concurso_calendario
-- DROP TABLE concurso.concurso_calendario;

CREATE TABLE concurso.concurso_calendario
(
  consecutivo_calendario serial NOT NULL,
  consecutivo_concurso integer NOT NULL,
  consecutivo_actividad integer NOT NULL,
  descripcion character varying(3000) DEFAULT ''::character varying NOT NULL,
  fecha_inicio character varying(20) ,
  fecha_fin character varying(20) ,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,   	
  CONSTRAINT pk_concurso_calendario PRIMARY KEY (consecutivo_calendario),
  CONSTRAINT fk_calendario__relations_concurso FOREIGN KEY (consecutivo_concurso)
      REFERENCES concurso.concurso (consecutivo_concurso) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_calendario__relations_actividad FOREIGN KEY (consecutivo_actividad)
      REFERENCES concurso.actividad_calendario (consecutivo_actividad) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.concurso_calendario
  OWNER TO jano_admin;

-- Index: concurso.persona_pk
-- DROP INDEX concurso.persona_pk;

CREATE UNIQUE INDEX idx_concurso_calendario
  ON concurso.concurso_calendario
  USING btree
  (consecutivo_concurso, consecutivo_actividad);







