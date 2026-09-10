#!/bin/sh
set -e

# roda as migrations pendentes a cada deploy — seguro porque o Laravel
# só aplica o que ainda não foi aplicado
php artisan migrate --force

# cacheia config/rotas para produção (ganho real de performance no Laravel)
php artisan config:cache
php artisan route:cache

exec php artisan serve --host=0.0.0.0 --port=8000
