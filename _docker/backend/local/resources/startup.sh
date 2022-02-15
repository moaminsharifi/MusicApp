service cron start
composer install
rm -rf public/storage
php artisan storage:link
php artisan route:cache
php artisan cache:clear
php artisan config:clear
php artisan optimize
#php artisan migrate --force

chmod -R 777 /var/www/html/storage
php artisan octane:start
# php artisan serve --port=8000
