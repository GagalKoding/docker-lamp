FROM arm64v8/ubuntu:18.04
ENV REFRESHED_AT 2021-07-04

ENV DOCKER_USER_ID 501 
ENV DOCKER_USER_GID 20

ENV BOOT2DOCKER_ID 1000
ENV BOOT2DOCKER_GID 50

ENV PHPMYADMIN_VERSION=5.1.1
ENV SUPERVISOR_VERSION=4.2.2

# Tweaks to give Apache/PHP write permissions to the app
RUN usermod -u ${BOOT2DOCKER_ID} www-data && \
    usermod -G staff www-data && \
    useradd -r mysql && \
    usermod -G staff mysql && \
    groupmod -g $(($BOOT2DOCKER_GID + 10000)) $(getent group $BOOT2DOCKER_GID | cut -d: -f1) && \
    groupmod -g ${BOOT2DOCKER_GID} staff

# Install packages
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get -y update
RUN apt-get -y dist-upgrade
RUN apt-get -y install software-properties-common
RUN add-apt-repository -y ppa:ondrej/php && \
  apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 4F4EA0AAE5267A6C && \
  apt-get update && \
  apt-get -y upgrade && \
  apt-get -y install postfix python3-setuptools wget git apache2 openssl libapache2-mod-fcgid mysql-server pwgen zip unzip curl php5.6 php5.6-fpm php5.6-xdebug php5.6-mysql php5.6-apcu php5.6-gd php5.6-xml php5.6-common php5.6-json php5.6-mbstring php5.6-gettext php5.6-zip php5.6-curl php5.6-cli php5.6-bcmath php5.6-bz2 php5.6-fpm php5.6-gd php5.6-mcrypt php7.4 php7.4-fpm php7.4-xdebug php7.4-mysql php7.4-apcu php7.4-gd php7.4-xml php7.4-common php7.4-json php7.4-mbstring php7.4-gettext php7.4-zip php7.4-curl php7.4-cli php7.4-bcmath php7.4-bz2 php7.4-fpm php7.4-gd php7.4-mcrypt php8.0 php8.0-fpm php8.0-xdebug php8.0-mysql php8.0-apcu php8.0-gd php8.0-xml php8.0-common php8.0-mbstring php8.0-gettext php8.0-zip php8.0-curl php8.0-cli php8.0-bcmath php8.0-bz2 php8.0-fpm php8.0-gd php8.0-mcrypt && \
  apt-get -y autoremove && \
  echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install supervisor 4
RUN curl -L https://pypi.io/packages/source/s/supervisor/supervisor-${SUPERVISOR_VERSION}.tar.gz | tar xvz && \
  cd supervisor-${SUPERVISOR_VERSION}/ && \
  python3 setup.py install

# Add image configuration and scripts
ADD supporting_files/start-apache2.sh /start-apache2.sh
ADD supporting_files/start-mysqld.sh /start-mysqld.sh
ADD supporting_files/run.sh /run.sh
RUN chmod 755 /*.sh
ADD supporting_files/supervisord-apache2.conf /etc/supervisor/conf.d/supervisord-apache2.conf
ADD supporting_files/supervisord-mysqld.conf /etc/supervisor/conf.d/supervisord-mysqld.conf
ADD supporting_files/supervisord.conf /etc/supervisor/supervisord.conf
ADD supporting_files/mysqld_innodb.cnf /etc/mysql/conf.d/mysqld_innodb.cnf

# Remove pre-installed database
RUN rm -rf /var/lib/mysql

# Add MySQL utils
ADD supporting_files/create_mysql_users.sh /create_mysql_users.sh

# Add phpmyadmin
RUN wget -O /tmp/phpmyadmin.tar.gz https://files.phpmyadmin.net/phpMyAdmin/${PHPMYADMIN_VERSION}/phpMyAdmin-${PHPMYADMIN_VERSION}-all-languages.tar.gz
RUN tar xfvz /tmp/phpmyadmin.tar.gz -C /var/www
RUN ln -s /var/www/phpMyAdmin-${PHPMYADMIN_VERSION}-all-languages /var/www/phpmyadmin
RUN mv /var/www/phpmyadmin/config.sample.inc.php /var/www/phpmyadmin/config.inc.php

# Add composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

ENV MYSQL_PASS:-$(pwgen -s 12 1)
# config to enable .htaccess
ADD supporting_files/apache_default /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
#RUN a2enmod actions alias proxy_fcgi fcgid
#RUN a2enconf php5.6-fpm
#RUN a2enconf php7.4-fpm
#RUN a2enconf php8.0-fpm
#RUN service php5.6-fpm start
#RUN service php7.4-fpm start
#RUN service php8.0-fpm start
#RUN service apache2 restart

# Configure /app folder with sample app
RUN mkdir -p /app && rm -fr /var/www/html && ln -s /app /var/www/html
ADD app/ /app

#Environment variables to configure php
ENV PHP_UPLOAD_MAX_FILESIZE 64M
ENV PHP_POST_MAX_SIZE 64M
ENV PHP_VERSION 7.4

RUN wget 'https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_aarch64.tar.gz'
RUN tar xzf ioncube_loaders_lin_aarch64.tar.gz -C /usr/local
#RUN mkdir -p /tmp/ioncube
#RUN tar -xvzf ioncube_loaders_lin_aarch64.tar.gz
#RUN mv ioncube/ioncube_loader_lin_7.4.so `php-config --extension-dir`
RUN rm -Rf ioncube_loaders_lin_aarch64.tar.gz
#RUN docker-php-ext-enable ioncube_loader_lin_7.4

# Add volumes for the app and MySql
VOLUME  ["/var/lib/mysql", "/app" ]

EXPOSE 80 3306
CMD ["/run.sh"]
