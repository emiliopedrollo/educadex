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

ALTER TABLE escolas ADD COLUMN en_tipo_ensino tipo_ensino[] DEFAULT NULL::tipo_ensino[];

select * FROM escolas ORDER by en_tipo_ensino NULLS LAST limit 500;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Fundamental'::tipo_ensino)
WHERE in_comum_fund_ai::INT::BOOLEAN OR in_comum_fund_af::INT::BOOLEAN
   OR in_esp_exclusiva_fund_ai::INT::BOOLEAN OR in_esp_exclusiva_fund_af::INT::BOOLEAN
   OR in_comum_eja_fund::INT::BOOLEAN OR in_esp_exclusiva_eja_fund::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Medio'::tipo_ensino)
WHERE in_comum_medio_medio::INT::BOOLEAN OR in_comum_medio_integrado::INT::BOOLEAN
   OR in_comum_medio_normal::INT::BOOLEAN OR in_esp_exclusiva_medio_medio::INT::BOOLEAN
   OR in_esp_exclusiva_medio_integr::INT::BOOLEAN OR in_esp_exclusiva_medio_normal::INT::BOOLEAN
   OR in_comum_eja_medio::INT::BOOLEAN OR in_esp_exclusiva_eja_medio::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Creche'::tipo_ensino)
WHERE in_comum_creche::INT::BOOLEAN OR in_esp_exclusiva_creche::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Pre'::tipo_ensino)
WHERE in_comum_pre::INT::BOOLEAN OR in_esp_exclusiva_pre::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Integrado'::tipo_ensino)
WHERE in_comum_medio_integrado::INT::BOOLEAN OR in_esp_exclusiva_medio_integr::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Anos iniciais'::tipo_ensino)
WHERE in_comum_fund_ai::INT::BOOLEAN OR in_esp_exclusiva_fund_ai::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Anos finais'::tipo_ensino)
WHERE in_comum_fund_af::INT::BOOLEAN OR in_esp_exclusiva_fund_af::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Especial'::tipo_ensino)
WHERE in_especial_exclusiva::INT::BOOLEAN OR in_esp_exclusiva_creche::INT::BOOLEAN
   OR in_esp_exclusiva_pre::INT::BOOLEAN OR in_esp_exclusiva_fund_ai::INT::BOOLEAN
   OR in_esp_exclusiva_fund_af::INT::BOOLEAN OR in_esp_exclusiva_medio_medio::INT::BOOLEAN
   OR in_esp_exclusiva_medio_integr::INT::BOOLEAN OR in_esp_exclusiva_medio_normal::INT::BOOLEAN
   OR in_esp_exclusiva_eja_fund::INT::BOOLEAN OR in_esp_exclusiva_eja_medio::INT::BOOLEAN
   OR in_esp_exclusiva_eja_prof::INT::BOOLEAN OR in_esp_exclusiva_prof::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Comum'::tipo_ensino)
WHERE in_comum_creche::INT::BOOLEAN OR in_comum_pre::INT::BOOLEAN
   OR in_comum_fund_ai::INT::BOOLEAN OR in_comum_fund_af::INT::BOOLEAN
   OR in_comum_medio_medio::INT::BOOLEAN OR in_comum_medio_integrado::INT::BOOLEAN
   OR in_comum_medio_normal::INT::BOOLEAN OR in_comum_eja_fund::INT::BOOLEAN
   OR in_comum_eja_medio::INT::BOOLEAN OR in_comum_eja_prof::INT::BOOLEAN
   OR in_comum_prof::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Presencial'::tipo_ensino)
WHERE in_mediacao_presencial::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'Semipresencial'::tipo_ensino)
WHERE in_mediacao_semipresencial::INT::BOOLEAN;

UPDATE escolas SET en_tipo_ensino = array_append(en_tipo_ensino,'EAD'::tipo_ensino)
WHERE in_mediacao_ead::INT::BOOLEAN;

-- UPDATE escolas SET en_tipo_ensino = array_cat(en_tipo_ensino,ARRAY['Creche'::tipo_ensino,'Pre'::tipo_ensino,
--     'Fundamental'::tipo_ensino,'Medio'::tipo_ensino,'Anos iniciais'::tipo_ensino,'Anos finais'::tipo_ensino])
-- WHERE in_regular::INT::BOOLEAN;

-- UPDATE escolas set en_tipo_ensino = ARRAY(SELECT DISTINCT UNNEST(escolas.en_tipo_ensino) ORDER BY 1)
-- WHERE en_tipo_ensino IS NOT NULL

CREATE INDEX en_tipo_ensino_index ON escolas (en_tipo_ensino);


SELECT am.amname AS index_method,
       opc.opcname AS opclass_name,
       opf.opfname AS opfamily_name,
       opc.opcintype::regtype AS indexed_type,
       opc.opcdefault AS is_default
FROM pg_am am, pg_opclass opc, pg_opfamily opf
WHERE opc.opcmethod = am.oid AND
        opc.opcfamily = opf.oid
ORDER BY index_method, opclass_name;

select (tipo_escola_to_bit(en_tipo_ensino))[11] FROM escolas limit 10;

explain select * from "escolas" where "co_municipio" = 4316907 and (tipo_escola_to_bit(en_tipo_ensino))[11] = 1::BIT
