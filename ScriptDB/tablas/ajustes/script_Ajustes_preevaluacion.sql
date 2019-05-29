
-- Adicionar campo para reclamación en validacion de requisitos


ALTER TABLE concurso.valida_requisito ADD COLUMN id_reclamacion integer;

ALTER TABLE concurso.valida_requisito
  ADD CONSTRAINT fk_valida_relations_reclamo FOREIGN KEY (id_reclamacion)
      REFERENCES concurso.evaluacion_reclamacion (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT;


----  Se adiciona el campo de puntos de aprobacion del criterio de evaluación

ALTER TABLE concurso.concurso_evaluar ADD COLUMN puntos_aprueba numeric(8,0);
ALTER TABLE concurso.concurso_evaluar ALTER COLUMN puntos_aprueba SET DEFAULT 1;
ALTER TABLE concurso.concurso_evaluar ALTER COLUMN puntos_aprueba SET NOT NULL;


-- Se relaciona el calendario al criterio de avaluacion del
ALTER TABLE concurso.concurso_calendario DROP COLUMN consecutivo_evaluar;

ALTER TABLE concurso.concurso_evaluar ADD COLUMN consecutivo_calendario integer;
ALTER TABLE concurso.concurso_evaluar
 ADD  CONSTRAINT fk_evaluar_relations_calendario FOREIGN KEY (consecutivo_calendario)
      REFERENCES concurso.concurso_calendario (consecutivo_calendario) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT;

-- modifica para el evaluacion de los reclamos


ALTER TABLE concurso.respuesta_reclamacion ADD COLUMN id_evaluar_respuesta integer;
ALTER TABLE concurso.respuesta_reclamacion ALTER COLUMN id_evaluar_respuesta SET NOT NULL;

ALTER TABLE concurso.respuesta_reclamacion ADD COLUMN id_evaluador character varying(25);
ALTER TABLE concurso.respuesta_reclamacion ALTER COLUMN id_evaluador SET NOT NULL;

-- registrar la reclamación la inscripcion

ALTER TABLE concurso.evaluacion_reclamacion ADD COLUMN id_inscrito integer;
ALTER TABLE concurso.evaluacion_reclamacion ALTER COLUMN id_inscrito SET NOT NULL;

ALTER TABLE concurso.evaluacion_reclamacion
 ADD  CONSTRAINT fk_inscrito_relations_reclamacion FOREIGN KEY (id_inscrito)
      REFERENCES concurso.concurso_inscrito (consecutivo_inscrito) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT;

-- cierre fases

ALTER TABLE concurso.evaluacion_parcial ADD COLUMN id_evaluacion_final_reclamo integer;

-- campo nuevo horas docencia
ALTER TABLE concurso.experiencia_docencia ADD COLUMN horas_catedra numeric(8,0);

