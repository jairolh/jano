
CREATE ROLE darwin_admin LOGIN password '4dm1n=darwin2019'
  NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION;
COMMENT ON ROLE jano_admin IS 'Usuario para sistema Gestión de procesos de selección.';

CREATE ROLE darwin_reporte LOGIN password 'r3p0rt3s=darwin2019'
  NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION;
COMMENT ON ROLE darwin_reporte IS 'Usuario para reportes de sistema Gestión de procesos de selección.';

-- Crear Base de Datos


CREATE DATABASE "darwin"
  WITH OWNER = darwin_admin
       ENCODING = 'UTF8'
       TABLESPACE = pg_default
       LC_COLLATE = 'es_CO.UTF-8'
       LC_CTYPE = 'es_CO.UTF-8'
       CONNECTION LIMIT = -1;

COMMENT ON DATABASE "darwin"
  IS 'Base de Datos para sistema de Gestión de procesos de selección';

