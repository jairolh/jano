
CREATE ROLE jano_admin LOGIN password '4dm1n=jano2017'
  NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION;
COMMENT ON ROLE jano_admin IS 'Usuario para sistema Gestión de concursos.';

-- Crear Base de Datos


CREATE DATABASE "jano"
  WITH OWNER = jano_admin
       ENCODING = 'UTF8'
       TABLESPACE = pg_default
       LC_COLLATE = 'es_CO.UTF-8'
       LC_CTYPE = 'es_CO.UTF-8'
       CONNECTION LIMIT = -1;

COMMENT ON DATABASE "jano"
  IS 'Base de Datos para sistema de Gestión de concursos';

