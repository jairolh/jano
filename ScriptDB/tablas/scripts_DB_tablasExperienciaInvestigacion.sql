CREATE TABLE concurso.experiencia_investigacion
(
  consecutivo_investigacion serial NOT NULL,
  consecutivo_persona integer NOT NULL,
  pais_investigacion numeric(10,0),
  codigo_nivel_institucion numeric(8,0) NOT NULL, 		
  codigo_institucion numeric(8,0) NOT NULL, 		
  nombre_institucion character varying(100) DEFAULT ''::character varying NOT NULL,
  direccion_institucion character varying(255) ,
  correo_institucion character varying(100) DEFAULT ''::character varying NOT NULL,
  telefono_institucion character varying(50) DEFAULT ''::character varying NOT NULL,
  titulo_investigacion character varying(255) DEFAULT ''::character varying NOT NULL,
  jefe_investigacion character varying(100) DEFAULT ''::character varying NOT NULL,
  descripcion_investigacion character varying(2000) ,
  direccion_investigacion character varying(255) DEFAULT ''::character varying NOT NULL,
  actual character varying(4),
  fecha_inicio character varying(20) NOT NULL,
  fecha_fin character varying(20) ,
  grupo_investigacion character varying(100),
  categoria_grupo character varying(8),

  CONSTRAINT pk_investigacion PRIMARY KEY (consecutivo_investigacion),
  CONSTRAINT fk_investigacion__relations_persona FOREIGN KEY (consecutivo_persona)
      REFERENCES concurso.persona (consecutivo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.experiencia_investigacion
  OWNER TO jano_admin;
