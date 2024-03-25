## Guia de Instalação da Aplicação Symfony com Docker
Este guia fornece instruções passo a passo para configurar e executar a aplicação Symfony com um banco de dados PostgreSQL, utilizando Docker. A aplicação é servida por PHP-FPM e utiliza o servidor web Nginx.

## Pré-requisitos
Antes de começar, certifique-se de ter instalado em sua máquina:

php ^8.2
Docker
Docker Compose
Você também precisará de conhecimentos básicos de Docker, Docker Compose e do framework Symfony.

## Configuração do Projeto
Clonar o Repositório

Primeiro, clone o repositório do projeto para a sua máquina local usando Git.

```bash
git clone <https://github.com/indexsaulomathe/symfony_docker_challenge.git>
cd <symfony_docker_challenge>
```

## Configuração do Ambiente

Copie o arquivo .env.example para .env e ajuste as variáveis de ambiente conforme necessário. As variáveis essenciais incluem as configurações do banco de dados:

```bash
POSTGRES_DB=nome_do_banco
POSTGRES_USER=usuario
POSTGRES_PASSWORD=senha
```
Assegure-se de ajustar essas variáveis para refletir as configurações desejadas para o banco de dados PostgreSQL.

## Realize a instalacao das dependencias com composer

```bash
composer install
```

## Construção dos Containers Docker

Com a Docker Instalada e On, dentro do diretório do projeto, execute o seguinte comando para construir e iniciar os containers:

```bash
docker-compose up -d --build
```

Isso irá construir a imagem do projeto e iniciar os containers necessários (PHP-FPM, PostgreSQL e Nginx).

## Instalação das Dependências do Projeto dentro da docker

Após os containers estarem em execução, instale as dependências do projeto na docker executando:

``` bash
docker-compose exec php composer install
```

Isso irá instalar todas as dependências do PHP necessárias para o projeto Symfony.

## Configuração do Banco de Dados

Você pode precisar executar migrações para configurar seu banco de dados. Execute o seguinte comando para aplicar migrações:

```bash
docker-compose exec php php bin/console doctrine:migrations:migrate 
```

## Acessando a Aplicação

A aplicação agora estará acessível no navegador através do endereço http://localhost:9000/ (ou a porta configurada, se você a alterou).

## Comandos Úteis

Parar os Containers: Para parar os containers, 
execute 
```bash
docker-compose down
```

Visualizar Logs: Para ver os logs dos containers, 
utilize
```bash
 docker-compose logs -f
```

Executar Comandos Symfony: Para executar comandos Symfony, 
utilize 

```bash
 docker-compose exec php bin/console <comando>
```

## Para roda os testes deve ser criado o banco de test em sqlite

php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test

## Rodar os testes

./vendor/bin/phpunit

## Caso necessario execute migration
php bin/console doctrine:migrations:migrate --env=test



