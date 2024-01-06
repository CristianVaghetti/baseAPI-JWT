#!/bin/bash

php /env.php && service php8.2-fpm start 

/bin/chmod 777 -R /var/www/html/artisan
/bin/chmod guo+wr -R /var/www/html/storage

env | grep "^DB_" > /etc/environment

# Instalação
if [ ! -d "/var/www/html/vendor" ]; then
    /bin/echo "<pre>" > /var/www/html/public/install.html

    php /var/www/html/composer.phar self-update 2>> /var/www/html/public/install.html
    php /var/www/html/composer.phar install -d /var/www/html  2>> /var/www/html/public/install.html 

    /bin/rm -f /var/www/html/public/install.html
fi

# Create the .postgres folder if it doesn't exist
if [ ! -d "/var/www/html/.postgres" ]; then
    mkdir /var/www/html/.postgres
fi

php -f /var/www/html/artisan optimize --force
php -f /var/www/html/artisan migrate


php -f /var/www/html/artisan route:cache
php -f /var/www/html/artisan cache:clear
php -f /var/www/html/artisan view:clear
php -f /var/www/html/artisan config:cache
php -f /var/www/html/artisan optimize --force
php -f /var/www/html/artisan storage:link

chmod 777 -R /var/www/html
