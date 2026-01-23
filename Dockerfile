FROM php:8.2-apache

# 1. Installer les extensions requises (MySQL, Zip, etc.)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# 2. Activer le mod_rewrite d'Apache (pour les routes Laravel)
RUN a2enmod rewrite

# 3. Copier les fichiers du projet
COPY . /var/www/html

# 4. Configurer le Document Root vers /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 5. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# 7. Donner les permissions (Crucial pour le dossier storage)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Exposer le port (Render utilise le port 80 par défaut pour les containers)
EXPOSE 80