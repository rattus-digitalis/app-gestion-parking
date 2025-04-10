FROM php:8.1-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN chown -R www-data:www-data /var/www/html
