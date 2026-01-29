FROM php:8.3-fpm
# update upgrade
USER root
RUN apt-get update && apt-get upgrade -y

# php extensions
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql


RUN apt-get install -y zlib1g-dev libicu-dev g++

RUN docker-php-ext-install intl
RUN docker-php-ext-configure intl 

RUN docker-php-ext-install exif
RUN docker-php-ext-configure exif 

#zip
RUN apt install zip -y
RUN apt install git -y

# user
ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} devuser || true \
 && useradd -u ${UID} -g ${GID} -m devuser || true

RUN chown -R devuser:devuser /var/www/html

#composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN mv composer.phar /usr/local/bin/composer

# PHPUnit global
USER root
RUN curl -L https://phar.phpunit.de/phpunit-10.phar -o /usr/local/bin/phpunit \
    && chmod +x /usr/local/bin/phpunit
USER devuser