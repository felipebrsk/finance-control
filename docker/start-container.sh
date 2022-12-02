#!/usr/bin/env bash
cp .env.example .env
composer install
npm install
php artisan migrate --seed
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
