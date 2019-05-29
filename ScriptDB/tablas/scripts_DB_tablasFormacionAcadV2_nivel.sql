-- Table: general.nivel   , generar niveles de los diferentes tipos


CREATE TABLE general.modalidad_educacion
(
  codigo_modalidad numeric(8,0) NOT NULL,
  nombre character varying(40) NOT NULL,
  estado character varying,	
  CONSTRAINT pk_codigo_modalidad PRIMARY KEY (codigo_modalidad)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE general.modalidad_educacion
  OWNER TO jano_admin;



-- Table: general.nivel   , generar niveles de los diferentes tipos


CREATE TABLE general.nivel
(
  codigo_nivel numeric(8,0) NOT NULL,
  nombre character varying(60) NOT NULL,
  tipo_nivel character varying(50) NOT NULL,
  descripcion character varying(255) NOT NULL,
  estado character varying,	
  CONSTRAINT pk_codigo_nivel PRIMARY KEY (codigo_nivel)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE general.nivel
  OWNER TO jano_admin;



CREATE UNIQUE INDEX codigo_nivel_pk
  ON general.nivel
  USING btree
  (codigo_nivel);



-- Table: institucion_educacion 


CREATE TABLE general.institucion_educacion
(
  codigo_ies numeric(8,0) NOT NULL,
  nombre character varying(100) NOT NULL,
  pais_institucion numeric(10,0),
  estado character varying,	
  CONSTRAINT pk_codigo_ies PRIMARY KEY (codigo_ies)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE general.institucion_educacion
  OWNER TO jano_admin;




-- Table: institucion_es 

CREATE TABLE general.programa_ies
( consecutivo_programa serial NOT NULL,
  codigo_programa numeric(8,0) NOT NULL,
  codigo_ies numeric(8,0) NOT NULL,
  nombre character varying(255) NOT NULL,
  estado character varying,	
  CONSTRAINT pk_consecutivo_programa PRIMARY KEY (consecutivo_programa),
  CONSTRAINT fk_institucion__relations_programa FOREIGN KEY (codigo_ies)
      REFERENCES  general.institucion_educacion (codigo_ies) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE general.programa_ies
  OWNER TO jano_admin;

CREATE UNIQUE INDEX programa_ies_pk
  ON general.programa_ies
  USING btree
  (codigo_programa,codigo_ies);

-- Table: concurso.formacion

-- DROP TABLE concurso.formacion;


CREATE TABLE concurso.formacion
(
  consecutivo_formacion serial NOT NULL,
  consecutivo_persona integer NOT NULL,
  codigo_modalidad numeric(8,0) NOT NULL, 
  codigo_nivel numeric(8,0) NOT NULL, 		
  pais_formacion numeric(10,0),
  codigo_institucion numeric(8,0) NOT NULL, 		
  nombre_institucion character varying(100) DEFAULT ''::character varying NOT NULL,
  codigo_programa numeric(8,0) NOT NULL, 		
  nombre_programa character varying(150) DEFAULT ''::character varying NOT NULL,
  cursos_aprobados numeric(2,0),
  graduado character varying(4) NOT NULL,
  fecha_grado character varying(20) ,
  promedio character varying(8),
  CONSTRAINT pk_formacion PRIMARY KEY (consecutivo_formacion),
  CONSTRAINT fk_formacion__relations_persona FOREIGN KEY (consecutivo_persona)
      REFERENCES concurso.persona (consecutivo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_formacion__relations_modalidad FOREIGN KEY (codigo_modalidad)
      REFERENCES general.modalidad_educacion (codigo_modalidad) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_formacion__relations_nivel FOREIGN KEY (codigo_nivel)
      REFERENCES general.nivel (codigo_nivel) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_formacion__relations_pais FOREIGN KEY (pais_formacion)
      REFERENCES general.pais  (id_pais ) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.formacion
  OWNER TO jano_admin;

-- Index: concurso.persona_pk

-- DROP INDEX concurso.persona_pk;

CREATE UNIQUE INDEX formacion_persona_pk
  ON concurso.formacion
  USING btree
  (consecutivo_formacion);

