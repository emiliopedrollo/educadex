CREATE EXTENSION IF NOT EXISTS unaccent;

ALTER TABLE escolas
    ALTER COLUMN nu_ano_censo TYPE INT USING nu_ano_censo::INTEGER,
    ALTER COLUMN nu_ano_censo SET NOT NULL;

ALTER TABLE escolas
    ALTER COLUMN co_entidade TYPE INT USING co_entidade::INTEGER,
    ALTER COLUMN co_entidade SET NOT NULL,
    ALTER COLUMN no_entidade SET NOT NULL;

ALTER TABLE escolas
    ALTER COLUMN co_municipio TYPE INT USING co_municipio::INTEGER,
    ALTER COLUMN co_municipio SET NOT NULL;

ALTER TABLE escolas
    ALTER COLUMN co_uf TYPE INT USING co_uf::INTEGER,
    ALTER COLUMN co_uf SET NOT NULL;

ALTER TABLE escolas
    ADD PRIMARY KEY (co_entidade);

CREATE TABLE IF NOT EXISTS uf (
    co_uf INT PRIMARY KEY,
    no_uf CHAR(2) NOT NULL,
    no_estado VARCHAR NOT NULL
);

CREATE TABLE municipios (
    co_municipio INT PRIMARY KEY,
    no_municipio VARCHAR NOT NULL,
    co_uf INT NOT NULL REFERENCES uf(co_uf),
    qt_populacao INT
);

 -- HERE WE MUST IMPORT THE DATA FROM IBGE USING R SCRIPT

ALTER TABLE escolas
    ADD CONSTRAINT escolas_co_uf_fkey FOREIGN KEY (co_uf) REFERENCES uf (co_uf);
ALTER TABLE escolas
    ADD CONSTRAINT escolas_co_municipio_fkey FOREIGN KEY (co_municipio) REFERENCES municipios (co_municipio);

