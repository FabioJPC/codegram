#!/bin/bash

if [ -z "$APP_KEY" ]; then
  echo "APP_KEY ausente — gerando..."
  export APP_KEY=$(php artisan key:generate --show)
fi

echo "Aguardando MySQL ficar pronto..."
for i in {1..30}; do
  if php artisan tinker --execute="DB::connection()->getPdo();" 2>/dev/null; then
    echo "MySQL está pronto!"
    break
  fi
  echo "Tentativa $i/30..."
  sleep 2
done

echo "Executando migrations..."
php artisan migrate --force

USER_COUNT="$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null)"

if [ -z "$USER_COUNT" ] || [ "$USER_COUNT" = "0" ]; then
  echo "Banco vazio — populando com dados de demonstração..."
  php artisan db:seed --force
else
  echo "Banco já populado (${USER_COUNT} usuários) — mantendo dados existentes."
fi

if [ ! -L public/storage ]; then
  echo "Criando link simbólico do storage..."
  php artisan storage:link
fi

echo "Ajustando permissões de storage/ e bootstrap/cache..."
chown -R www-data:www-data storage bootstrap/cache

echo "Setup concluído!"

exec "$@"
