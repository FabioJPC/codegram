#!/bin/bash

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

if [ ! -L public/storage ]; then
  echo "Criando link simbólico do storage..."
  php artisan storage:link
fi

echo "Setup concluído!"

exec "$@"
