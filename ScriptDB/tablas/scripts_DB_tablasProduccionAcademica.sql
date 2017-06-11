CREATE TABLE concurso.produccion_academica
(
  consecutivo_produccion serial NOT NULL,
  consecutivo_persona integer NOT NULL,
  codigo_tipo_produccion numeric(8,0) NOT NULL, 		
  nombre_tipo_produccion character varying(100) DEFAULT ''::character varying NOT NULL,
  titulo_produccion character varying(150) DEFAULT ''::character varying NOT NULL,
  nombre_autor character varying(100) DEFAULT ''::character varying NOT NULL,
  nombre_producto_incluye character varying(150) ,
  nombre_editorial character varying(100) ,
  volumen character varying(50) ,
  pagina character varying(50) ,
  codigo_isbn character varying(50) ,
  codigo_issn character varying(50) ,
  indexado character varying(255) ,
  pais_produccion numeric(10,0),
  departamento_produccion numeric(10,0),
  ciudad_produccion numeric(10,0),
  descripcion character varying(2000) ,
  direccion_produccion character varying(255),
  fecha_produccion character varying(20) ,
  CONSTRAINT pk_produccion PRIMARY KEY (consecutivo_produccion),
  CONSTRAINT fk_produccion__relations_persona FOREIGN KEY (consecutivo_persona)
      REFERENCES concurso.persona (consecutivo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_produccion__relations_nivel FOREIGN KEY (codigo_tipo_produccion)
      REFERENCES general.nivel (codigo_nivel) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE concurso.produccion_academica
  OWNER TO jano_admin;
