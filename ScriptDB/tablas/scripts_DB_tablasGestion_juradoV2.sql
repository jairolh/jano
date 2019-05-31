-- Table: concurso.tipo_jurado
-- DROP TABLE concurso.tipo_jurado;

CREATE TABLE concurso.jurado_tipo
(
  id serial NOT NULL,
  nombre character varying(100) NOT NULL DEFAULT ''::character varying,
  descripcion character varying(511),
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,
  CONSTRAINT pk_tipo_jurado PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.jurado_tipo
  OWNER TO jano_admin;

-- Table: concurso.jurado_criterio
-- DROP TABLE concurso.jurado_criterio;

CREATE TABLE concurso.jurado_criterio
(
  id serial NOT NULL,
  id_jurado_rol integer NOT NULL,
  id_criterio integer NOT NULL,
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,   	
  CONSTRAINT pk_jurado_criterio PRIMARY KEY (id),
  CONSTRAINT fk_jurado__relations_rol FOREIGN KEY (id_jurado_rol)
      REFERENCES public.jano_rol (rol_id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_juradoc__relations_criterio FOREIGN KEY (id_criterio)
      REFERENCES concurso.criterio_evaluacion (consecutivo_criterio) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.jurado_criterio
  OWNER TO jano_admin;



-- Table: concurso.jurado_inscrito
-- DROP TABLE concurso.jurado_inscrito;

CREATE TABLE concurso.jurado_inscrito
(
  id serial NOT NULL,
  id_usuario character varying(25) NOT NULL,
  id_inscrito integer NOT NULL,
  id_jurado_tipo integer NOT NULL,
  fecha_registro character varying(20) NOT NULL DEFAULT ''::character varying,  
  estado character varying(2) NOT NULL DEFAULT 'A'::character varying,   
  CONSTRAINT pk_jurado_inscrito PRIMARY KEY (id),
  CONSTRAINT fk_jurado__relations_usuario FOREIGN KEY (id_usuario)
      REFERENCES public.jano_usuario (id_usuario) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_inscrito__relations_inscrito FOREIGN KEY (id_inscrito)
      REFERENCES concurso.concurso_inscrito (consecutivo_inscrito) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_jurado__relations_tipo FOREIGN KEY (id_jurado_tipo)
      REFERENCES concurso.jurado_tipo (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT

)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.jurado_inscrito
  OWNER TO jano_admin;





