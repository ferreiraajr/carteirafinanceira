# Desafio para full stack Grupo Adriano Cobuccio

## Objetivo

O objetivo consiste na criação de uma interface funcional equivalente a uma carteira financeira em
que os usuários possam realizar transferência de saldo e depósito.


## 📋 Requisitos Implementados

    ✅ Cadastro e autenticação de usuários

    ✅ Transferência entre usuários com validação de saldo

    ✅ Sistema de depósitos

    ✅ Reversão de transações (estorno)

    ✅ Histórico de transações

    ✅ Validações de segurança

## 🚀 Tecnologias Utilizadas
    - Laravel 12 (PHP 8.2)
    - Vue 3
    - Tailwind CSS (Shadcn/Vue)
    - Inertia
    - PostgreSQL (Banco de dados principal)
    - Docker (Ambiente containerizado)

## Contributing

Thank you for considering contributing to our starter kit! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## ⚙️ Instalação
### Via Docker (Recomendado)

##### Clone o repositório
````
    git clone https://github.com/seu-usuario/carteira-digital.git
````
##### Acesse o diretório do projeto
````
    cd carteira-digital
````

#### Crie o arquivo .env
````
    cp .env.example .env
````

#### Altere as variáveis de ambiente
````
    DB_CONNECTION=pgsql
    DB_HOST=postgres
    DB_PORT=5432
    DB_DATABASE=walletcobuccio
    DB_USERNAME=userwallet
    DB_PASSWORD=adminteste
````
#### Inicie o ambiente Docker
````
    docker-compose up -d
````
#### Instale as dependências do PHP
````
    docker-compose run --rm composer install
````

#### Gerar a chave de aplicação
````
    docker-compose run --rm artisan key:generate
````
#### Realize as migrações e seeders
````
    docke-compose run --rm artisan migrate --seed
````
#### Gere o link simbólico para o storage
````
    docker-compose run --rm artisan storage:link
````
#### Ajuste as permissões do storage
````
    docker exec -it laravel-app chown -R www-data:www-data /var/www/storage
````
#### Instale as dependências do Node
````
    docker-compose run --rm npm install
````
#### Compile os assets
````
    docker-compose run --rm npm run build
````
### Sem Docker
````
    git clone https://github.com/seu-usuario/carteira-digital.git
````
````
    cd carteira-digital
````
````
    cp .env.example .env
````
````
    composer install
````
````
    php artisan key:generate
````
````
    php artisan migrate --seed
````
````
    php artisan serve
````

## 🧪 Testes


In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## License

The Laravel + Vue starter kit is open-sourced software licensed under the MIT license.
