Educadex
==========

Indexador educacional

Live demo: http://educadex.emilio.pedrollo.nom.br

Dependências
------------
Para instalar uma instância do **Educadex** é necessário que alguns softwares já estejam pre-instalados no sistema.
Então verifique se você tem tudo que é necessário antes de prosseguirmos. Também é possível executar o software 
utilizando um container Docker facilitando a seleção e instalação de boa parte os componentes e ferramentas 
necessárias.

### Softwares necessários
1. [PHP]: Em ambientes Unix geralmente ja vem com o sistema ou está disponível no gerenciador de pacotes do mesmo,
  verifique utilizando o comando `php -v` se você o tem instalado. No Windows geralmente é mais comum que seja 
  instalado uma versão otimizada para desenvolvimento como o [XAMPP] ou similares. **Para este projeto é necessário 
  ter o PHP 7.2 ou superior instalado**.
2. [Composer]: Este é o gerenciador de pacotes do PHP, analogico ao _pip_ no _python_ e ao _gem_ no _ruby_.
  Pode ser baixado segundo as [instruções oficiais](https://getcomposer.org/download/). 
  Deve ser instalado globalmente ou dentro do diretório do projeto. Não confundir com Docker Compose.

### Softwares opcionais
1. [node.js]: Utilizado para compilação dos componentes web desenvolvidos em Vue.js e não é necessário para 
  execução.

### Utilizando Docker
Pela própria natureza do Docker tudo que se faz necessário para executar uma instância do **Educadex** em um container
é o próprio [Docker] e um sistema compatível. 

Apesar de não obrigatório, utilizar o [Docker Compose] simplifica a coordenação dos diversos containers que são 
utilizados. Ele já vem instalado junto com o Docker Desktop para Windows, em ambientes linux ele deve ser 
instalado a parte.

Como instalar
-------------
Assim como tudo projeto hospedado no **GitHub** ou em qualquer outro serviço versionamento baseado em _git_, para 
darmos inicio ao desenvolvimento é necessário realizar a clonagem do repositório em nossa máquina local, para isso se 
faz necessário termos o software _[git]_ instalado localmente e executar o seguinte comando:
```shell script
git clone https://github.com/emiliopedrollo/educadex.git && cd educadex
```
Será criado uma pasta dentro do diretório atual chamada **educadex**. Os próximos comandos deste tutorial deverão ser
executados de dentro desta pasta.

Para instalar todas as dependências do projeto PHP devemos executar a seguinte linha se o composer estiver instalado 
globalmente:
```shell script
composer install
```
Ou se o composer estiver instalado apenas na pasta do projeto:
```shell script
php composer.phar install
```

### Framework Laravel
Para acabar de configurar o Framework Laravel é necessário apenas mais alguns comandos:
```shell script
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate --ansi
```

O primeiro comando irá copiar e renomear o arquivo `.env.example` para `.env` caso não haja um na raiz do projeto ainda.
O segundo comando irá gerar uma chave única de criptografia para a nova instância.

Agora tudo que falta é completar as configurações do arquivo `.env` com as credenciais de acesso à banco de dados.


[Composer]: https://getcomposer.org
[Docker]: https://www.docker.com
[Docker Compose]: https://docs.docker.com/compose/
[git]: https://git-scm.com
[node.js]: https://nodejs.org
[PHP]: https://php.net
[XAMPP]: https://www.apachefriends.org
