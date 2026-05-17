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
    mbstring \
    zip \
    gd \
    intl \
    soap \
    mcrypt

# Install Composer v1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --version=1.10.27 \
    --install-dir=/usr/local/bin \
    --filename=composer

# Install ionCube Loader
RUN mkdir -p /tmp/ioncube \
    && curl -fsSL https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_aarch64.tar.gz \
    -o /tmp/ioncube/ioncube.tar.gz \
    && tar -xzf /tmp/ioncube/ioncube.tar.gz -C /tmp/ioncube \
    && PHP_EXT_DIR=$(php -i | grep extension_dir | head -n1 | awk '{print $3}') \
    && cp /tmp/ioncube/ioncube/ioncube_loader_lin_5.6.so $PHP_EXT_DIR \
    && echo "zend_extension=$PHP_EXT_DIR/ioncube_loader_lin_5.6.so" > /usr/local/etc/php/conf.d/00-ioncube.ini \
    && rm -rf /tmp/ioncube

# PHP recommended settings
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "upload_max_filesize=128M" > /usr/local/etc/php/conf.d/upload-limit.ini \
    && echo "post_max_size=128M" >> /usr/local/etc/php/conf.d/upload-limit.ini \
    && echo "max_execution_time=300" > /usr/local/etc/php/conf.d/max-execution.ini

EXPOSE 9000

CMD ["php-fpm"]