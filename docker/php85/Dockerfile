FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

# Install dependencies
RUN apt-get update \
    && apt-get install -y \
    software-properties-common \
    curl \
    git \
    unzip \
    ca-certificates \
    gnupg2 \
    lsb-release \
    apt-transport-https \
    && update-ca-certificates \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update 

# Install PHP 8.5 + extensions
RUN apt install -y \
    php8.5 \
    php8.5-fpm \
    php8.5-cli \
    php8.5-common \
    php8.5-mysql \
    php8.5-pgsql \
    php8.5-curl \
    php8.5-xml \
    php8.5-mbstring \
    php8.5-zip \
    php8.5-gd \
    php8.5-intl \
    php8.5-bcmath \
    php8.5-soap \
    php8.5-readline \
    php8.5-imagick \
    php8.5-redis \
    php8.5-dev \
    php-pear \
    && apt clean \
    && rm -rf /var/lib/apt/lists/*

# Configure PHP-FPM to listen on TCP
RUN sed -i 's|listen = /run/php/php8.5-fpm.sock|listen = 9000|g' /etc/php/8.5/fpm/pool.d/www.conf

# Optional performance tuning
RUN sed -i 's|;clear_env = no|clear_env = no|g' /etc/php/8.5/fpm/pool.d/www.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
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
    && cp /tmp/ioncube/ioncube/ioncube_loader_lin_8.5.so $PHP_EXT_DIR \
    && echo "zend_extension=$PHP_EXT_DIR/ioncube_loader_lin_8.5.so" \
    > /etc/php/8.5/mods-available/ioncube.ini \
    && phpenmod ioncube \
    && rm -rf /tmp/ioncube

# Create runtime directory
RUN mkdir -p /run/php

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm8.5", "-F"]