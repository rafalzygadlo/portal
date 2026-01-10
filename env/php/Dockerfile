FROM php:8.1-fpm
# update upgrade
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
#composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN mv composer.phar /usr/local/bin/composer
#CMD bash -c "composer install"

#RUN apt-get install phpunit