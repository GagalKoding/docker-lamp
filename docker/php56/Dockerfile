FROM php:5.6-fpm

ENV DEBIAN_FRONTEND=noninteractive

# Fix archived Debian Stretch repositories
RUN sed -i 's|deb.debian.org|archive.debian.org|g' /etc/apt/sources.list \
    && sed -i 's|security.debian.org|archive.debian.org|g' /etc/apt/sources.list \
    && sed -i '/stretch-updates/d' /etc/apt/sources.list \
    && apt-get -o Acquire::Check-Valid-Until=false update

# Install system dependencies
RUN apt-get install -y --allow-unauthenticated \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    zlib1g-dev \
    libmcrypt-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Configure GD
RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/

# Install PHP extensions
RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    mbstring \
    zip \
    gd \
    intl \
    soap \
    mcrypt

# Install Redis extension (last compatible stable version for PHP 5.6)
RUN yes '' | pecl install redis-4.3.0 \
    && docker-php-ext-enable redis

# Install Composer v1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --version=1.10.27 \
    --install-dir=/usr/local/bin \
    --filename=composer

# Install ionCube Loader (multi-architecture support)
RUN mkdir -p /tmp/ioncube \
    && ARCH=$(uname -m) \
    && if [ "$ARCH" = "aarch64" ] || [ "$ARCH" = "arm64" ]; then \
        IONCUBE_ARCH="aarch64"; \
    else \
        IONCUBE_ARCH="x86-64"; \
    fi \
    && curl -fsSL https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_${IONCUBE_ARCH}.tar.gz \
    -o /tmp/ioncube/ioncube.tar.gz \
    && tar -xzf /tmp/ioncube/ioncube.tar.gz -C /tmp/ioncube \
    && PHP_EXT_DIR=$(php -i | grep extension_dir | head -n1 | awk '{print $3}') \
    && cp /tmp/ioncube/ioncube/ioncube_loader_lin_5.6.so $PHP_EXT_DIR \
    && echo "zend_extension=$PHP_EXT_DIR/ioncube_loader_lin_5.6.so" > /usr/local/etc/php/conf.d/00-ioncube.ini \
    && rm -rf /tmp/ioncube

EXPOSE 9000

CMD ["php-fpm"]