# Codegram

Projeto final do curso CodeAcademy.

## Sobre o projeto

Codegram é uma rede social de compartilhamento de fotos, inspirada no Instagram, desenvolvida como projeto final do curso CodeAcademy. O objetivo é aplicar, em um projeto full stack completo, os principais conceitos trabalhados ao longo da formação: modelagem de dados, autenticação, construção de uma API REST e integração com uma interface web reativa.

A aplicação permite que usuários criem conta, publiquem fotos e stories, sigam outros perfis, curtam e comentem publicações, e acompanhem um feed com o conteúdo de quem seguem.

### Principais funcionalidades

- Cadastro e autenticação de usuários
- Publicação de posts com imagens
- Publicação de stories
- Sistema de seguidores (seguir/deixar de seguir)
- Curtidas e comentários em posts
- Feed de publicações e de stories
- Busca e sugestão de usuários

### Tecnologias utilizadas

**Backend**
- PHP 8.3 e Laravel 13
- Laravel Sanctum (autenticação da API)
- MySQL 8.4
- Documentação da API via OpenAPI (Swagger UI)

**Frontend**
- Vue 3
- Vue Router e Pinia
- Bootstrap 5
- Vite

**Infraestrutura**
- Docker e Docker Compose

## Instruções de instalação

### Pré-requisitos

- Docker e Docker Compose instalados

### Clone o repositório

```bash
git clone https://github.com/FabioJPC/codegram.git
cd codegram
```

### Configure as variáveis de ambiente

Crie o arquivo `.env` dentro da pasta `backend`, tendo como base o `backend/.env.example`, e preencha as variáveis necessárias, entre elas:

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=codegram
DB_USERNAME=root
DB_PASSWORD=senharoot
DB_ROOT_PASSWORD=senharoot
```

### Suba a aplicação

Na raiz do projeto, execute:

```bash
docker compose up -d --build
```

### Acessando a aplicação

- Frontend: http://localhost:5173
- API: http://localhost:8000/api
- Documentação da API (Swagger UI): http://localhost:8081
