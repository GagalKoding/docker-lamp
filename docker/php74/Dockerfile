FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# Install dependencies
RUN apt update && apt install -y \
    software-properties-common \
    curl \
    git \
    unzip \
    ca-certificates \
    lsb-release \
    apt-transport-https \
    && add-apt-repository ppa:ondrej/php -y \
    && apt update

# Install PHP 7.4 + extensions
RUN apt install -y \
    php7.4 \
    php7.4-fpm \
    php7.4-cli \
    php7.4-common \
    php7.4-mysql \
    php7.4-curl \
    php7.4-xml \
    php7.4-mbstring \
    php7.4-zip \
    php7.4-gd \
    php7.4-intl \
    php7.4-bcmath \
    php7.4-soap \
    php7.4-readline \
    php7.4-imagick \
    php7.4-redis \
    php7.4-dev \
    php-pear \
    && apt clean \
    && rm -rf /var/lib/apt/lists/*

# Configure PHP-FPM to listen on TCP
RUN sed -i 's|listen = /run/php/php7.4-fpm.sock|listen = 9000|g' /etc/php/7.4/fpm/pool.d/www.conf

# Optional performance tuning
RUN sed -i 's|;clear_env = no|clear_env = no|g' /etc/php/7.4/fpm/pool.d/www.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

# Install ionCube Loader
RUN mkdir -p /tmp/ioncube \
    && curl -fsSL https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_aarch64.tar.gz \
    -o /tmp/ioncube/ioncube.tar.gz \
    && tar -xzf /tmp/ioncube/ioncube.tar.gz -C /tmp/ioncube \
    && PHP_EXT_DIR=$(php -i | grep extension_dir | head -n1 | awk '{print $3}') \
    && cp /tmp/ioncube/ioncube/ioncube_loader_lin_7.4.so $PHP_EXT_DIR \
    && echo "zend_extension=$PHP_EXT_DIR/ioncube_loader_lin_7.4.so" \
    > /etc/php/7.4/fpm/conf.d/00-ioncube.ini \
    && echo "zend_extension=$PHP_EXT_DIR/ioncube_loader_lin_7.4.so" \
    > /etc/php/7.4/cli/conf.d/00-ioncube.ini \
    && rm -rf /tmp/ioncube

# Create runtime directory
RUN mkdir -p /run/php

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm7.4", "-F"]