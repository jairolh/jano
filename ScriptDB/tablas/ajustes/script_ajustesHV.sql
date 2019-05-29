

ALTER TABLE concurso.persona ADD COLUMN fecha_identificacion date;
ALTER TABLE concurso.persona ADD COLUMN lugar_identificacion numeric(10,0);
ALTER TABLE concurso.persona ADD COLUMN pais_identificacion numeric(10,0);
ALTER TABLE concurso.persona ADD COLUMN autorizacion boolean;


COMMENT ON COLUMN fecha_identificacion IS 'Fecha de expedicion del documento de identidad';
COMMENT ON COLUMN lugar_identificacion IS 'Codigo del lugar de expedicion del documento de identidad';
COMMENT ON COLUMN pais_identificacion  IS 'Codigo del pais de expedicion del documento de identidad';
COMMENT ON COLUMN autorizacion IS 'Campo que almacena la autorización para el manejo de la información relacionada con la Hoja de Vida';


/******mensaje consentimiento ****/

INSERT INTO public.jano_texto(
            id, tipo, texto, estado)
    VALUES ('DEFAULT', 'autorizacionHV','<p style="text-align:justify">RESPETADO USUARIO</p><p  style="text-align:justify">Se solicita su autorización para que de manera de manera voluntaria, previa, expresa, informada e inequívoca permita a la Universidad Distrital Francisco José de Caldas el recaudo, almacenamiento y disposición de los datos personales incorporados en el sistema de información para fines institucionales, así como la divulgación de información pública por principio de transparencia y posterior contacto a través de medios telefónicos, electrónicos (SMS, chat, correo electrónico y demás medios considerados electrónicos) físicos y/o personales.</p> <p  style="text-align:justify">Cabe recordar que su cuenta en el sistema de información es personal, por tanto el uso que de a su usuario y contraseña es de su exclusiva responsabilidad, de igual manera en el marco del principio de la buena fe establecido en el artículo 83 de la Constitución Política de Colombia, se presume que los datos y soportes suministrados son verídicos y cualquier falsedad o inconsistencia que se identifique en el proceso de verificación de los mismos, será de exclusiva responsabilidad del titular de la cuenta quien asumirá de forma directa las consecuencias civiles, penales y administrativas que su actuación genere ante las autoridades públicas.</p> <p style="text-align:justify">Los datos personales que suministre mediante el sistema de información, serán administrados por la Universidad Distrital Francisco José de Caldas, su confidencialidad y seguridad serán acordes a lo establecido mediante lo consagrado en la Ley 1581 de 2012 (Régimen General de Protección de Datos Personales) y la Ley 1712 de 2014 (Transparencia y del derecho de acceso a la información pública Nacional), lo cual podrá ser consultado en https://www.udistrital.edu.co/politicas-de-privacidad.</p>','A');


/*****/

INSERT INTO general.nivel(
            codigo_nivel, nombre, tipo_nivel, descripcion, estado)
    VALUES ('0','Sin Dato','Todos','nivel sin dato','A');
