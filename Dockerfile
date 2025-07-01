# Utiliser une image PHP officielle avec Apache
FROM php:8.1-apache

# Installer les extensions PHP nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Activer le module Apache rewrite
RUN a2enmod rewrite

# Copier le code source dans le conteneur
COPY . /var/www/html/

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances PHP sans les dev
RUN composer install --no-dev --optimize-autoloader

# Donner les droits nécessaires au dossier var et public
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/public

# Exposer le port 80
EXPOSE 80

# Commande pour démarrer Apache en mode premier plan
CMD ["apache2-foreground"]
