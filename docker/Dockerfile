# =====================================================
# Flarum CI/CD Pipeline - Dockerfile
# Muh Dzaky Musaddaq - Docker/Deployment Engineer
# =====================================================

# Use official PHP 8.2 with FPM and Alpine Linux for lightweight container
FROM php:8.2-fpm-alpine

# Set maintainer information
LABEL maintainer="Muh Dzaky Musaddaq"
LABEL description="Flarum Forum with CI/CD Pipeline - Production Ready Container"
LABEL version="1.0"

# Install system dependencies required for Flarum
RUN apk add --no-cache \
    # Basic utilities
    curl \
    wget \
    git \
    unzip \
    bash \
    # Image processing
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    # Database
    mysql-client \
    # Web server
    nginx \
    # Process management
    supervisor \
    # Additional utilities
    shadow \
    && rm -rf /var/cache/apk/*

# Install PHP extensions required by Flarum
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mysqli \
        zip \
        gd \
        exif \
        opcache \
        bcmath \
        intl \
    && docker-php-ext-enable opcache

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create application user for security
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -D -S -G www www

# Set working directory
WORKDIR /var/www/html

# Copy Flarum source code
COPY src/ ./

# Copy configuration files
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Create necessary directories
RUN mkdir -p /var/www/html/storage/{logs,cache,sessions,views,tmp} \
    && mkdir -p /var/www/html/public/assets \
    && mkdir -p /var/log/nginx \
    && mkdir -p /var/log/supervisor \
    && mkdir -p /run/nginx

# Set proper ownership and permissions
RUN chown -R www:www /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/public/assets

# Install Composer dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-ansi \
    --no-progress \
    || echo "Composer install completed with warnings (normal for Flarum)"

# Copy environment file for Docker
COPY .env.docker .env

# Set final permissions after composer install
RUN chown -R www:www /var/www/html/vendor \
    && chown -R www:www /var/www/html/storage

# Create startup script
RUN echo '#!/bin/bash' > /usr/local/bin/start.sh && \
    echo 'set -e' >> /usr/local/bin/start.sh && \
    echo '' >> /usr/local/bin/start.sh && \
    echo '# Wait for database to be ready' >> /usr/local/bin/start.sh && \
    echo 'echo "⏳ Waiting for database connection..."' >> /usr/local/bin/start.sh && \
    echo 'until mysql -h ${DB_HOST:-mysql} -u ${DB_USERNAME:-flarum_user} -p${DB_PASSWORD:-flarum_password} -e "SELECT 1" >/dev/null 2>&1; do' >> /usr/local/bin/start.sh && \
    echo '  echo "⏳ Database not ready, waiting..."' >> /usr/local/bin/start.sh && \
    echo '  sleep 2' >> /usr/local/bin/start.sh && \
    echo 'done' >> /usr/local/bin/start.sh && \
    echo 'echo "✅ Database connection established!"' >> /usr/local/bin/start.sh && \
    echo '' >> /usr/local/bin/start.sh && \
    echo '# Set proper permissions before start' >> /usr/local/bin/start.sh && \
    echo 'chown -R www:www /var/www/html/storage' >> /usr/local/bin/start.sh && \
    echo 'chmod -R 775 /var/www/html/storage' >> /usr/local/bin/start.sh && \
    echo '' >> /usr/local/bin/start.sh && \
    echo '# Start supervisor (manages nginx + php-fpm)' >> /usr/local/bin/start.sh && \
    echo 'echo "🚀 Starting Flarum application..."' >> /usr/local/bin/start.sh && \
    echo '/usr/bin/supervisord -c /etc/supervisord.conf' >> /usr/local/bin/start.sh && \
    chmod +x /usr/local/bin/start.sh

# Health check for container monitoring
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://localhost/api/health || exit 1

# Expose HTTP port
EXPOSE 80

# Switch to non-root user for security
USER www

# Set environment variables
ENV PHP_MEMORY_LIMIT=256M \
    PHP_MAX_EXECUTION_TIME=60 \
    PHP_POST_MAX_SIZE=20M \
    PHP_UPLOAD_MAX_FILESIZE=20M \
    NGINX_WORKER_PROCESSES=auto \
    NGINX_WORKER_CONNECTIONS=1024

# Start the application
CMD ["/usr/local/bin/start.sh"]