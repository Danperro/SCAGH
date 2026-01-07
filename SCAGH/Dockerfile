FROM php:8.2-fpm

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Instalar Node.js y npm
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Permisos Laravel
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias de npm (sin el flag --production para incluir las dependencias de desarrollo)
RUN npm install

# Compilar los assets de Laravel (incluyendo Livewire)
RUN npm run build   # Usamos "npm run build" en lugar de "npm run prod"

# Publicar los assets de Livewire
RUN php artisan livewire:publish --assets

# Optimizar la aplicación Laravel
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan optimize

# Ejecutar migraciones en producción (si es necesario)
RUN php artisan migrate --force

# Puerto Railway
EXPOSE 8080

# Usar PHP built-in server (aceptable en Railway)
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
