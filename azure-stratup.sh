#!/bin/bash

# 1. Ubah Document Root Nginx ke /public dan atur routing Laravel
cp /etc/nginx/sites-available/default /etc/nginx/sites-available/default.bak
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' /etc/nginx/sites-available/default
sed -i 's|try_files $uri $uri/ =404;|try_files $uri $uri/ /index.php?$query_string;|g' /etc/nginx/sites-available/default
service nginx reload

# 2. Pindah ke direktori utama aplikasi
cd /home/site/wwwroot

# 3. Jalankan pembersihan cache Laravel
php artisan config:clear
php artisan route:clear
php artisan view:clear