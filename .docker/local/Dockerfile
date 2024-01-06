FROM ubuntu:22.04
ENV TZ=America/Sao_Paulo

RUN apt-get update -y
RUN apt-get install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt-get update -y
RUN apt-get install apache2 -y
RUN apt-get install php8.2 php8.2-dev php8.2-pgsql php8.2-zip php8.2-mbstring php8.2-curl php8.2-mysql php8.2-xml php8.2-cli php8.2-common php8.2-fpm php8.2-opcache php8.2-readline php8.2-gd php8.2-soap nano vim -y
RUN apt-get install git zip unzip -y
RUN a2enmod proxy_fcgi rewrite
RUN echo "America/Sao_Paulo" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

COPY ./conf/artisan.sh /bin/artisan
COPY ./conf/init.sh /init.sh
COPY ./conf/composer.sh /bin/composer
COPY ./conf/quickstart.sh /bin/quickstart
COPY ./conf/www.conf /etc/php/8.2/fpm/pool.d/www.conf
COPY ./conf/env.conf /etc/php/8.2/fpm/env.conf
COPY ./conf/php-fpm.conf /etc/php/8.2/fpm/php-fpm.conf
COPY ./conf/env.php /env.php
COPY ./conf/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./conf/uploads.ini /etc/php/8.2/mods-available/uploads.ini
COPY ./conf/keystore_pgc.pem /keystore_pgc.pem

RUN ln -s /etc/php/8.2/mods-available/uploads.ini /etc/php/8.2/fpm/conf.d/20-uploads.ini

#Tunning apache
COPY ./conf/mpm_worker.conf /etc/apache2/mods-available/mpm_worker.conf
COPY ./conf/mpm_event.conf /etc/apache2/mods-available/mpm_event.conf
COPY ./conf/mpm_prefork.conf /etc/apache2/mods-available/mpm_prefork.conf

# Restart fpm
RUN /etc/init.d/php8.2-fpm restart

RUN find /var/www/html -type f -exec chmod 664 {} \;    
RUN find /var/www/html -type d -exec chmod 775 {} \;
RUN chmod 0777 /env.php /init.sh /bin/artisan /bin/composer /bin/quickstart
RUN export TERM=xterm

EXPOSE 80
WORKDIR /var/www/html

CMD ["/init.sh"]

