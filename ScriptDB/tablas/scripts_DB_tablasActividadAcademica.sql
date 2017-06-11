CREATE TABLE concurso.actividad_academica
(
  consecutivo_actividad serial NOT NULL,
  consecutivo_persona integer NOT NULL,
  pais_actividad numeric(10,0),
  codigo_nivel_institucion numeric(8,0) NOT NULL, 		
  codigo_institucion numeric(8,0) NOT NULL, 		
  nombre_institucion character varying(100) DEFAULT ''::character varying NOT NULL,
  correo_institucion character varying(100) DEFAULT ''::character varying NOT NULL,
  telefono_institucion character varying(50),
  codigo_tipo_actividad numeric(8,0) NOT NULL, 		
  nombre_tipo_actividad character varying(100) DEFAULT ''::character varying NOT NULL,
  nombre_actividad character varying(150) DEFAULT ''::character varying NOT NULL,
  descripcion character varying(2000) ,
  jefe_actividad character varying(100) DEFAULT ''::character varying NOT NULL,
  fecha_inicio character varying(20) NOT NULL,
  fecha_fin character varying(20) ,
  CONSTRAINT pk_actividad PRIMARY KEY (consecutivo_actividad),
  CONSTRAINT fk_actividad__relations_persona FOREIGN KEY (consecutivo_persona)
      REFERENCES concurso.persona (consecutivo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_actividad__relations_nivel FOREIGN KEY (codigo_tipo_actividad)
      REFERENCES general.nivel (codigo_nivel) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.actividad_academica
  OWNER TO jano_admin;
