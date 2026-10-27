#!/bin/bash

# 1. Masuk ke folder aplikasi
cd /home/site/wwwroot

# 2. Re-install dependensi Composer dengan bersih
composer install --no-dev --optimize-autoloader

# 3. Jalankan optimasi & pembersihan cache Laravel
php artisan config:clear
php artisan route:clear
php artisan view:clear