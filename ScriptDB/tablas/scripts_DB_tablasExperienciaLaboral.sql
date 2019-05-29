CREATE TABLE concurso.experiencia_laboral
(
  consecutivo_experiencia serial NOT NULL,
  consecutivo_persona integer NOT NULL,
  codigo_nivel_experiencia numeric(8,0) NOT NULL, 		
  pais_experiencia numeric(10,0),
  codigo_nivel_institucion numeric(8,0) NOT NULL, 		
  codigo_institucion numeric(8,0) NOT NULL, 		
  nombre_institucion character varying(100) DEFAULT ''::character varying NOT NULL,
  direccion_institucion character varying(255) ,
  correo_institucion character varying(100) DEFAULT ''::character varying NOT NULL,
  telefono_institucion character varying(50) DEFAULT ''::character varying NOT NULL,
  cargo character varying(100) DEFAULT ''::character varying NOT NULL,
  descripcion_cargo character varying(2000) ,
  actual character varying(4),
  fecha_inicio character varying(20) NOT NULL,
  fecha_fin character varying(20) ,
  CONSTRAINT pk_experiencia PRIMARY KEY (consecutivo_experiencia),
  CONSTRAINT fk_experiencia__relations_persona FOREIGN KEY (consecutivo_persona)
      REFERENCES concurso.persona (consecutivo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_experiencia__relations_nivel FOREIGN KEY (codigo_nivel_experiencia)
      REFERENCES general.nivel (codigo_nivel) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.experiencia_laboral
  OWNER TO jano_admin;
