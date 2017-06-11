CREATE TABLE concurso.experiencia_docencia
(
  consecutivo_docencia serial NOT NULL,
  consecutivo_persona integer NOT NULL,
  codigo_nivel_docencia numeric(8,0) NOT NULL, 		
  pais_docencia numeric(10,0),
  codigo_nivel_institucion numeric(8,0) NOT NULL, 		
  codigo_institucion numeric(8,0) NOT NULL, 		
  nombre_institucion character varying(100) DEFAULT ''::character varying NOT NULL,
  direccion_institucion character varying(255) ,
  correo_institucion character varying(100) DEFAULT ''::character varying NOT NULL,
  telefono_institucion character varying(50) DEFAULT ''::character varying NOT NULL,
  codigo_vinculacion numeric(8,0) NOT NULL, 		
  nombre_vinculacion character varying(100) DEFAULT ''::character varying NOT NULL,
  descripcion_docencia character varying(2000) ,
  actual character varying(4),
  fecha_inicio character varying(20) NOT NULL,
  fecha_fin character varying(20) ,
  CONSTRAINT pk_docencia PRIMARY KEY (consecutivo_docencia),
  CONSTRAINT fk_docencia__relations_persona FOREIGN KEY (consecutivo_persona)
      REFERENCES concurso.persona (consecutivo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_docencia__relations_nivel FOREIGN KEY (codigo_nivel_docencia)
      REFERENCES general.nivel (codigo_nivel) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.experiencia_docencia
  OWNER TO jano_admin;
