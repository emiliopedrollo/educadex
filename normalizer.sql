ALTER TABLE escolas
    ALTER COLUMN nu_ano_censo TYPE INT USING nu_ano_censo::INTEGER,
    ALTER COLUMN nu_ano_censo SET NOT NULL,
    ALTER COLUMN co_entidade TYPE INT USING co_entidade::INTEGER,
    ALTER COLUMN co_entidade SET NOT NULL,
    ALTER COLUMN no_entidade SET NOT NULL;

CREATE UNIQUE INDEX escolas_pk ON escolas(co_entidade);

ALTER TABLE escolas ADD PRIMARY KEY (co_entidade);
