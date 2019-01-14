#! /bin/bash

echo 'Setting everything up...'
sleep 2

if [ -f /var/www/vendor/autoload.php ]; then
    cd /var/www
    chown -R www-data:www-data /var/www

    if [ ! -f /var/www/.env ]; then
        cp .env.example .env
        php artisan key:generate
        php artisan storage:link
        php artisan wipe
        npm rebuild node-sass --force && npm install && npm run prod
        echo 'DONE!! Go to localhost'
    else
        echo 'Seems like .env file is already created'
    fi

    # if [ -f /etc/supervisor/conf.d/laravel-worker.conf ]; then
    #     supervisord && supervisorctl update && supervisorctl start laravel-worker:*
    # fi
else
    echo 'WAIT COUPLE SECONDS AND TRY AGAIN. vendor/autoload.php file is not created yet, composer is currently installing it.'
fi