# Utiliser une image PHP officielle avec Apache
FROM php:8.0-apache

# Installer les dépendances nécessaires
RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        zip \
    && docker-php-ext-install zip \
    && a2enmod rewrite

# Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html

# Exposer le port 80 pour le serveur web
EXPOSE 80

# Démarrer Apache au lancement du conteneur
CMD ["apache2-foreground"]