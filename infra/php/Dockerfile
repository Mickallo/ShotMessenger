FROM php:8.2-fpm

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    librabbitmq-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_pgsql \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# Installer les bibliothèques nécessaires à zip + unzip
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le code de l'application
WORKDIR /var/www/html
COPY . .

# Exposer le port 9000 et démarrer PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]