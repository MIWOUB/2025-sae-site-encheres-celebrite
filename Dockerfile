# On part de l'image officielle PHP 8 avec Apache
FROM php:8.2-apache

# Mise à jour des paquets et installation des dépendances système si nécessaire
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Installation des extensions PHP requises (pdo_mysql est celle que tu as demandée)
RUN docker-php-ext-install pdo pdo_mysql

# (Optionnel) Activation du module rewrite d'Apache (très utile pour Symfony/Laravel)
RUN a2enmod rewrite

# On définit le répertoire de travail
WORKDIR /var/www/html

# On s'assure que les permissions sont correctes pour Apache
RUN chown -R www-data:www-data /var/www/html