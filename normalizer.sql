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
    ALTER COLUMN in_esp_exclusiva_prof TYPE BOOLEAN USING in_esp_exclusiva_prof::INT::BOOLEAN,
    ALTER COLUMN in_comum_prof TYPE BOOLEAN USING in_comum_prof::INT::BOOLEAN,
    ALTER COLUMN in_esp_exclusiva_eja_prof TYPE BOOLEAN USING in_esp_exclusiva_eja_prof::INT::BOOLEAN,
    ALTER COLUMN in_profissionalizante TYPE BOOLEAN USING in_profissionalizante::INT::BOOLEAN,
    ALTER COLUMN in_comum_eja_prof TYPE BOOLEAN USING in_comum_eja_prof::INT::BOOLEAN,
    ALTER COLUMN in_eja TYPE BOOLEAN USING in_eja::INT::BOOLEAN,
    ALTER COLUMN in_comum_eja_fund TYPE BOOLEAN USING in_comum_eja_fund::INT::BOOLEAN,
    ALTER COLUMN in_comum_eja_medio TYPE BOOLEAN USING in_comum_eja_medio::INT::BOOLEAN,
    ALTER COLUMN in_esp_exclusiva_eja_fund TYPE BOOLEAN USING in_esp_exclusiva_eja_fund::INT::BOOLEAN,
    ALTER COLUMN in_esp_exclusiva_eja_medio TYPE BOOLEAN USING in_esp_exclusiva_eja_medio::INT::BOOLEAN,
    ALTER COLUMN in_comum_fund_ai TYPE BOOLEAN USING in_comum_fund_ai::INT::BOOLEAN,
    ALTER COLUMN in_comum_fund_af TYPE BOOLEAN USING in_comum_fund_af::INT::BOOLEAN,
    ALTER COLUMN in_esp_exclusiva_fund_ai TYPE BOOLEAN USING in_esp_exclusiva_fund_ai::INT::BOOLEAN,
    ALTER COLUMN in_esp_exclusiva_fund_af TYPE BOOLEAN USING in_esp_exclusiva_fund_af::INT::BOOLEAN,
    ALTER COLUMN in_comum_medio_medio TYPE BOOLEAN USING in_comum_medio_medio::INT::BOOLEAN,
    ALTER COLUMN in_comum_medio_integrado TYPE BOOLEAN USING in_comum_medio_integrado::INT::BOOLEAN,
    ALTER COLUMN in_comum_medio_normal TYPE BOOLEAN USING in_comum_medio_normal::INT::BOOLEAN,
    ALTER COLUMN in_esp_exclusiva_medio_medio TYPE BOOLEAN USING in_esp_exclusiva_medio_medio::INT::BOOLEAN,
    ALTER COLUMN in_esp_exclusiva_medio_integr TYPE BOOLEAN USING in_esp_exclusiva_medio_integr::INT::BOOLEAN,
    ALTER COLUMN in_esp_exclusiva_medio_normal TYPE BOOLEAN USING in_esp_exclusiva_medio_normal::INT::BOOLEAN,
    ALTER COLUMN in_comum_creche TYPE BOOLEAN USING in_comum_creche::INT::BOOLEAN,
    ALTER COLUMN in_esp_exclusiva_creche TYPE BOOLEAN USING in_esp_exclusiva_creche::INT::BOOLEAN,
    ALTER COLUMN in_comum_pre TYPE BOOLEAN USING in_comum_pre::INT::BOOLEAN,
    ALTER COLUMN in_esp_exclusiva_pre TYPE BOOLEAN USING in_esp_exclusiva_pre::INT::BOOLEAN,
    ALTER COLUMN in_especial_exclusiva TYPE BOOLEAN USING in_especial_exclusiva::INT::BOOLEAN,
    ALTER COLUMN in_mediacao_presencial TYPE BOOLEAN USING in_mediacao_presencial::INT::BOOLEAN,
    ALTER COLUMN in_mediacao_semipresencial TYPE BOOLEAN USING in_mediacao_semipresencial::INT::BOOLEAN,
    ALTER COLUMN in_mediacao_ead TYPE BOOLEAN USING in_mediacao_ead::INT::BOOLEAN;

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


CREATE INDEX escolas_co_municipio_index ON escolas(co_municipio);

CREATE INDEX escolas_co_ud_index ON escolas(co_uf);
