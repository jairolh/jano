-- Table: concurso.factor_evaluacion

DROP TABLE public.jano_log_usuario;


CREATE TABLE public.jano_log_usuario
( id_log serial NOT NULL,
  id_usuario character varying(25) NOT NULL, -- Identificación del usuario que realiza la acción
  accion character varying(100) NOT NULL, -- Accion que realiza el usuario, Consulta, actualización, borrado o registro.
  id_registro character varying(100) NOT NULL, -- Identificacion para el registro afectado
  tipo_registro character varying(100) NOT NULL, -- Tipo de registro afectado, identificado por proceso o subsistema
  fecha_log character varying(100) NOT NULL, -- Fecha en que se registra el evento
  nombre_registro text NOT NULL,
  descripcion text NOT NULL, -- Descripción breve de la acción
  host character varying(50) NOT NULL, -- Host desde que se realiza la acción
  CONSTRAINT pk_id_log PRIMARY KEY (id_log),	
  CONSTRAINT jano_usuario_jano_log_usuario_fk FOREIGN KEY (id_usuario)
      REFERENCES public.jano_usuario (id_usuario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.jano_log_usuario
  OWNER TO jano_admin;
COMMENT ON TABLE public.jano_log_usuario
  IS 'Log de actividades del sistema';
COMMENT ON COLUMN public.jano_log_usuario.id_usuario IS 'Identificación del usuario que realiza la acción';
COMMENT ON COLUMN public.jano_log_usuario.accion IS 'Accion que realiza el usuario, Consulta, actualización, borrado o registro.';
COMMENT ON COLUMN public.jano_log_usuario.id_registro IS 'Identificacion para el registro afectado';
COMMENT ON COLUMN public.jano_log_usuario.tipo_registro IS 'Tipo de registro afectado, identificado por proceso o subsistema';
COMMENT ON COLUMN public.jano_log_usuario.fecha_log IS 'Fecha en que se registra el evento';
COMMENT ON COLUMN public.jano_log_usuario.descripcion IS 'Descripción breve de la acción';
COMMENT ON COLUMN public.jano_log_usuario.host IS 'Host desde que se realiza la acción';
