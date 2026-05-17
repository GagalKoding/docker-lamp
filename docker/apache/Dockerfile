FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt update && apt install -y \
    apache2 \
    libapache2-mod-fcgid \
    && apt clean \
    && rm -rf /var/lib/apt/lists/*

# Enable required modules
RUN a2enmod proxy proxy_fcgi rewrite actions alias

# Avoid Apache warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy virtual host config
COPY docker/apache/apache_default /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]