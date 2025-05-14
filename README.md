# Carteira Digital – Desafio Full Stack
**Grupo Adriano Cobuccio**

## 🎯 Objetivo

Desenvolver uma interface funcional de carteira financeira, permitindo aos usuários realizar transferências de saldo, depósitos e reversão de transações.

---

## 📋 Funcionalidades Implementadas

- Cadastro e autenticação de usuários
- Transferência entre usuários com validação de saldo
- Sistema de depósitos
- Reversão de transações (estorno)
- Histórico de transações
- Validações de segurança

---

## 🚀 Tecnologias Utilizadas

- **Laravel 12** (PHP 8.2)
- **Vue 3**
- **Tailwind CSS** (Shadcn/Vue)
- **Inertia.js**
- **PostgreSQL**
- **Docker** (ambiente containerizado)

---

## ⚙️ Instalação

### Usando Docker (Recomendado)

1. **Clone o repositório**
    ```bash
    git clone https://github.com/seu-usuario/carteira-digital.git
    cd carteira-digital
    ```

2. **Crie o arquivo `.env`**
    ```bash
    cp .env.example .env
    ```

3. **Configure as variáveis de ambiente no `.env`**
    ```
    DB_CONNECTION=pgsql
    DB_HOST=postgres
    DB_PORT=5432
    DB_DATABASE=walletcobuccio
    DB_USERNAME=userwallet
    DB_PASSWORD=adminteste
    ```

4. **Suba os containers Docker**
    ```bash
    docker-compose up -d
    ```

5. **Instale as dependências do PHP**
    ```bash
    docker-compose run --rm composer install
    ```

6. **Gere a chave da aplicação**
    ```bash
    docker-compose run --rm artisan key:generate
    ```

7. **Execute as migrações e seeders**
    ```bash
    docker-compose run --rm artisan migrate --seed
    ```

8. **Crie o link simbólico do storage**
    ```bash
    docker-compose run --rm artisan storage:link
    ```

9. **Ajuste as permissões do storage**
    ```bash
    docker exec -it laravel-app chown -R www-data:www-data /var/www/storage
    ```

10. **Instale as dependências do Node.js**
    ```bash
    docker-compose run --rm npm install
    ```

11. **Compile os assets**
    ```bash
    docker-compose run --rm npm run build
    ```

---

### Instalação Manual (Sem Docker)

1. **Clone o repositório**
    ```bash
    git clone https://github.com/seu-usuario/carteira-digital.git
    cd carteira-digital
    ```

2. **Crie o arquivo `.env`**
    ```bash
    cp .env.example .env
    ```

3. **Instale as dependências do PHP**
    ```bash
    composer install
    ```

4. **Gere a chave da aplicação**
    ```bash
    php artisan key:generate
    ```

5. **Execute as migrações e seeders**
    ```bash
    php artisan migrate --seed
    ```

6. **Inicie o servidor**
    ```bash
    php artisan serve
    ```

---

## 📝 Observações

- Certifique-se de que o PostgreSQL esteja rodando e configurado conforme o `.env`.
- Para ambiente Docker, os comandos `artisan` e `npm` são executados dentro dos containers.
- O frontend é servido via Inertia.js integrado ao Laravel.

---

## 📄 Licença

Este projeto é apenas para fins de avaliação técnica.
