@echo off
echo Limpando caches...
composer clear-cache
composer dump-autoload -a
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan filament:clear-cached-components
echo Finalizado!
pause