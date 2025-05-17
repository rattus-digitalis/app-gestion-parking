FROM php:8.2-apache

# Activer les modules PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activer mod_rewrite pour Apache
RUN a2enmod rewrite

# Remplacer la config Apache pour autoriser .htaccess (optionnel mais conseillé)
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Copier le backend dans le dossier web
COPY ./ /var/www/html/
