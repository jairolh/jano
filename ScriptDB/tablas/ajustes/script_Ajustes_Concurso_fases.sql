

----  Se adiciona el campo de puntos de aprobacion del cconcurso

ALTER TABLE concurso.concurso ADD COLUMN maximo_puntos numeric(8,2);
ALTER TABLE concurso.concurso ALTER COLUMN maximo_puntos SET DEFAULT 0;
ALTER TABLE concurso.concurso ALTER COLUMN maximo_puntos SET NOT NULL;

ALTER TABLE concurso.concurso ADD COLUMN porcentaje_aprueba numeric(8,2);
ALTER TABLE concurso.concurso ALTER COLUMN porcentaje_aprueba SET DEFAULT 0;
ALTER TABLE concurso.concurso ALTER COLUMN porcentaje_aprueba SET NOT NULL;


----  Se adiciona el campo de puntos de aprobacion de las fases del concurso cconcurso

ALTER TABLE concurso.concurso_calendario ADD COLUMN porcentaje_aprueba numeric(8,2);
ALTER TABLE concurso.concurso_calendario ALTER COLUMN porcentaje_aprueba SET DEFAULT 0;
ALTER TABLE concurso.concurso_calendario ALTER COLUMN porcentaje_aprueba SET NOT NULL;


ALTER TABLE concurso.concurso_calendario ADD COLUMN fecha_fin_reclamacion character varying(20);
ALTER TABLE concurso.concurso_calendario ADD COLUMN fecha_fin_resolver character varying(20);

-- Se registra campo para validar el tipo de cierre efectuado
ALTER TABLE concurso.concurso_calendario ADD COLUMN cierre character varying(20);


-- ALTER TABLE concurso.etapa_inscrito DROP CONSTRAINT fk_etapa__relations_calendario;

ALTER TABLE concurso.etapa_inscrito
  ADD CONSTRAINT fk_etapa__relations_calendario FOREIGN KEY (consecutivo_calendario_ant)
      REFERENCES concurso.concurso_calendario (consecutivo_calendario) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT;


--- Tabla para textos

CREATE TABLE jano_texto (
 id serial NOT NULL,
 tipo character varying(50) NOT NULL,
 texto text NOT NULL,
 estado character varying(2) NOT NULL DEFAULT 'A'::character varying,
 CONSTRAINT jano_texto_pkey PRIMARY KEY (id)
)
