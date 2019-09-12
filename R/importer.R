
for (package in c("RPostgreSQL")) {
  if (!package %in% rownames(installed.packages())){
    message (paste("Installing", package))
    install.packages(package)
  }
}

require(plyr)
require(RPostgreSQL)

pw <- {
  "*******"
}
user <- {
  "username"
}
port <- {
  5432
}
host <- {
  "127.0.0.1"
}
dbname <- {
  "dbname"
}

drv <- dbDriver("PostgreSQL")
con <- dbConnect(drv,dbname = dbname, host = host, port = port, user = user, password = pw)
rm(pw)


dfEstados <- read.csv('Lista_Estados_Brasil_Versao_CSV.csv', sep = ';')
cols <- c('IBGE', 'Estado', 'UF')
dfRecorteEstados <- dfEstados[, cols]
names(dfRecorteEstados) <- c('co_uf','no_estado','no_uf')


dbWriteTable(con,'uf', dfRecorteEstados, append = T, row.names = F)


dfRegioes <- read.csv('Lista_Municípios_com_IBGE_Brasil_Versao_CSV.csv', sep = ';')
cols <- c('IBGE7', 'Município', 'UF', 'População.2010')
dfRecorteCidades <- dfRegioes[, cols]
dfRecorteCidades$UF <- mapvalues(dfRecorteCidades$UF, from=dfRecorteEstados$no_uf, to=dfRecorteEstados$co_uf)
names(dfRecorteCidades) <- c('co_municipio','no_municipio','co_uf','qt_populacao')

dbWriteTable(con,'municipios', dfRecorteCidades, append = T, row.names = F)



















