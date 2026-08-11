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
php artisan migrate:fresh --seed

if [ ! -L public/storage ]; then
  echo "Criando link simbólico do storage..."
  php artisan storage:link
fi

# migrate/seed rodam como root (o entrypoint é root), então qualquer arquivo
# ou diretório criado por eles (seed-images copiados, uploads de seeder etc.)
# fica com dono root. Isso impede o Apache (www-data) de escrever/sobrescrever
# esses arquivos depois (ex.: upload de foto de perfil falha silenciosamente).
echo "Ajustando permissões de storage/ e bootstrap/cache..."
chown -R www-data:www-data storage bootstrap/cache

echo "Setup concluído!"

exec "$@"
