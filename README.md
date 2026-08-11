## Projeto final CodeAcademy

## Instruções de instalação

## Clone o repositório
```bash
git clone https://github.com/FabioJPC/codegram.git
cd codegram
```

## Crie o arquivo .env baseado em .env.example
## Preencha o arquivo com suas variáveis de ambiente
```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=codegram
DB_USERNAME=root
DB_PASSWORD=senharoot
DB_ROOT_PASSWORD=senharoot
```
## Rode a aplicação
```bash
docker compose up -d --build
```
