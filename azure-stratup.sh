#!/bin/bash

# 1. Update konfigurasi Nginx
cp /etc/nginx/sites-available/default /etc/nginx/sites-available/default.bak
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' /etc/nginx/sites-available/default
sed -i 's|try_files $uri $uri/ =404;|try_files $uri $uri/ /index.php?$query_string;|g' /etc/nginx/sites-available/default
service nginx reload

# 2. Masuk ke folder aplikasi
cd /home/site/wwwroot

# 3. Buat folder storage & cache jika belum ada
mkdir -p storage/framework/views
mkdir -p storage/framework/sessions
mkdir -p storage/framework/cache

# 4. Clear cache
php artisan config:clear
php artisan route:clear