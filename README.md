# ![Docker-LAMP](docs/logo.svg)

# Docker-LAMP Platform v2

Modern multi-runtime PHP infrastructure platform powered by Docker, Apache, PHP-FPM, MariaDB, PostgreSQL, Redis, Memcached, MongoDB extension support, and dynamic runtime switching.

Designed for developers, infrastructure engineers, operations teams, legacy migration, and multi-version PHP environments.

Supports:

- PHP 5.6
- PHP 7.4
- PHP 8.5
- Apache 2.4
- MariaDB 11
- PostgreSQL 17
- Redis
- Memcached
- MongoDB Extension
- phpMyAdmin
- pgAdmin
- ionCube Loader
- Apple Silicon / ARM64
- Intel / AMD64

---

# Overview

Docker-LAMP Platform v2 is a modular multi-runtime PHP infrastructure platform designed for:

- modern PHP applications
- enterprise legacy systems
- migration testing
- multi-version PHP hosting
- operations-focused development
- containerized infrastructure

The platform allows multiple PHP runtimes to coexist simultaneously within a single Docker stack while supporting per-project runtime switching using `.htaccess`.

---

# Key Features

- Multi-runtime PHP architecture
- Dynamic PHP runtime switching
- Apache + PHP-FPM design
- MariaDB support
- PostgreSQL support
- Redis support
- Memcached support
- MongoDB extension support
- phpMyAdmin included
- pgAdmin included
- ionCube Loader support
- ARM64 / Apple Silicon support
- AMD64 / Intel support
- Multi-application hosting
- VirtualHost architecture
- Dynamic php.ini configuration
- Isolated runtime containers
- Production deployment ready

---

# Supported Technologies

| Category | Technologies |
|---|---|
| Web Server | Apache 2.4 |
| PHP Runtime | PHP 5.6, 7.4, 8.5 |
| Database | MariaDB, PostgreSQL, SQLite |
| Cache | Redis, Memcached, APCu |
| Extensions | MongoDB, LDAP, IMAP, SOAP, ionCube |
| Administration | phpMyAdmin, pgAdmin |
| Architectures | ARM64, AMD64 |
| Container Runtime | Docker Compose |

---

# Use Cases

Docker-LAMP Platform v2 is designed for:

- Laravel development
- CodeIgniter development
- WHMCS hosting
- WordPress hosting
- Enterprise legacy PHP systems
- Multi-version PHP hosting
- Migration testing
- Shared runtime infrastructure
- Apple Silicon PHP development
- Production containerized PHP infrastructure

---

# Architecture

Docker-LAMP Platform v2 uses a modular service-oriented architecture.

```text
Internet
    │
    ▼
Apache Container
    ├── PHP 5.6 FPM
    ├── PHP 7.4 FPM
    ├── PHP 8.5 FPM
    ├── MariaDB
    ├── PostgreSQL
    ├── Redis
    ├── Memcached
    ├── phpMyAdmin
    └── pgAdmin
```

Each PHP runtime is fully isolated:

- PHP-FPM
- extensions
- ionCube
- Composer
- runtime configuration
- dependencies

This architecture enables:

- runtime isolation
- simplified upgrades
- compatibility testing
- stable legacy coexistence
- enterprise migration workflows

---

# Infrastructure Design

Docker-LAMP Platform v2 follows a layered infrastructure design:

- Apache reverse proxy layer
- Isolated PHP runtime containers
- Dedicated database services
- Shared Docker networking
- Dynamic `.htaccess` runtime routing
- Multi-runtime coexistence
- Multi-application hosting

---

# Supported PHP Versions

| PHP Version | Status | Primary Usage |
|---|---|---|
| PHP 5.6 | Legacy | WHMCS, legacy CMS, migration |
| PHP 7.4 | Transitional | Laravel 6/7/8, migration |
| PHP 8.5 | Modern | Modern frameworks & applications |

---

# Requirements

- Docker
- Docker Compose
- Linux / macOS / Windows
- ARM64 or AMD64 CPU

---

# Project Structure

```text
.
├── app/
├── compose/
│   ├── docker-compose.yml
│   └── docker-compose.hub.yml
├── docker/
│   ├── apache/
│   ├── php56/
│   ├── php74/
│   └── php85/
└── docs/
```

---

# Installation Methods

Docker-LAMP Platform v2 supports two installation methods.

| Method | Recommended For |
|---|---|
| Docker Hub Images | Standard users |
| Local Build | Contributors & customization |

---

# Quick Start

## Clone Repository

```bash
git clone https://github.com/GagalKoding/docker-lamp.git && cd docker-lamp
```

---

# Method 1 — Docker Hub Images (Recommended)

Uses prebuilt images from Docker Hub.

No local build required.

## Start Stack

```bash
docker compose -f compose/docker-compose.hub.yml up -d
```

---

## Open Services

Application:

```text
http://localhost
```

phpMyAdmin:

```text
http://localhost:8080
```

pgAdmin:

```text
http://localhost:8081
```

---

# Method 2 — Local Build

Recommended for:

- contributors
- runtime customization
- infrastructure development
- extension customization

## Build Containers

```bash
docker compose -f compose/docker-compose.yml build --no-cache
```

---

## Start Stack

```bash
docker compose -f compose/docker-compose.yml up -d
```

---

# PHP Runtime Switching

Docker-LAMP Platform v2 supports dynamic runtime switching using `.htaccess`.

This enables:

- per-project PHP versions
- migration testing
- legacy coexistence
- multi-runtime hosting

---

# PHP 8.5

```apache
<FilesMatch \.php$>
    SetHandler "proxy:fcgi://php85:9000"
</FilesMatch>
```

---

# PHP 7.4

```apache
<FilesMatch \.php$>
    SetHandler "proxy:fcgi://php74:9000"
</FilesMatch>
```

---

# PHP 5.6

```apache
<FilesMatch \.php$>
    SetHandler "proxy:fcgi://php56:9000"
</FilesMatch>
```

No rebuild required.

Refresh browser after changing `.htaccess`.

---

# Multi-Application Hosting

Docker-LAMP Platform v2 supports hosting multiple applications simultaneously.

Example:

| Domain | PHP Runtime |
|---|---|
| crm.example.com | PHP 8.5 |
| billing.example.com | PHP 7.4 |
| legacy.example.com | PHP 5.6 |

Applications can coexist within the same infrastructure stack while using isolated runtimes.

---

# Apache VirtualHost Example

```apache
<VirtualHost *:80>
    ServerName crm.example.com

    DocumentRoot /var/www/html/crm/public

    <Directory /var/www/html/crm/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/crm-error.log
    CustomLog ${APACHE_LOG_DIR}/crm-access.log combined
</VirtualHost>
```

---

# Database Services

## MariaDB

Host:

```text
db
```

Port:

```text
3306
```

Default Credentials:

| Key | Value |
|---|---|
| Username | root |
| Password | root |
| Database | app |

---

## PostgreSQL

Host:

```text
postgres
```

Port:

```text
5432
```

Default Credentials:

| Key | Value |
|---|---|
| Username | root |
| Password | root |
| Database | app |

---

# Cache Services

## Redis

Host:

```text
redis
```

Port:

```text
6379
```

---

## Memcached

Host:

```text
memcached
```

Port:

```text
11211
```

---

# Administration Services

## phpMyAdmin

URL:

```text
http://localhost:8080
```

---

## pgAdmin

URL:

```text
http://localhost:8081
```

Default Credentials:

| Key | Value |
|---|---|
| Email | admin@example.com |
| Password | root |

---

# Example Database Connections

## MariaDB PDO

```php
<?php

$db = new PDO(
    'mysql:host=db;dbname=app',
    'root',
    'root'
);
```

---

## PostgreSQL PDO

```php
<?php

$db = new PDO(
    'pgsql:host=postgres;dbname=app',
    'root',
    'root'
);
```

---

# Runtime Extensions

Docker-LAMP Platform v2 includes support for:

- ionCube Loader
- Redis
- Memcached
- MongoDB
- APCu
- PostgreSQL
- SQLite
- LDAP
- IMAP
- SOAP
- Imagick
- XSL
- Tidy
- FTP
- Sockets

---

# ionCube Support

Docker-LAMP Platform v2 includes ionCube support for:

- PHP 5.6
- PHP 7.4
- PHP 8.5

Ideal for:

- WHMCS
- encoded commercial applications
- enterprise legacy software

---

# Dynamic PHP Configuration

Each PHP runtime supports dynamic php.ini configuration.

Example:

```text
docker/php85/php.ini
docker/php74/php.ini
docker/php56/php.ini
```

No rebuild required after changing runtime settings.

Example settings:

```ini
memory_limit = 512M
upload_max_filesize = 128M
post_max_size = 128M
date.timezone = Asia/Jakarta
```

---

# Apple Silicon & Multiarch Support

Docker-LAMP Platform v2 fully supports:

- Apple Silicon M1
- Apple Silicon M2
- ARM64
- AMD64 / Intel

No Rosetta required.

Supports Docker multi-architecture images.

---

# Operations Workflow

Typical operations workflow:

- deploy stack
- configure VirtualHosts
- assign PHP runtime per application
- configure databases
- manage runtime isolation
- monitor container health
- scale infrastructure services
- update runtimes independently

---

# Developer Workflow

Typical developer workflow:

- clone repository
- start stack
- create project
- assign PHP runtime
- develop locally
- test migrations
- switch runtimes dynamically
- deploy consistently

---

# Container Topology

| Service | Container |
|---|---|
| Apache | docker-lamp-apache |
| PHP 5.6 | docker-lamp-php56 |
| PHP 7.4 | docker-lamp-php74 |
| PHP 8.5 | docker-lamp-php85 |
| MariaDB | docker-lamp-db |
| PostgreSQL | docker-lamp-postgres |
| Redis | docker-lamp-redis |
| Memcached | docker-lamp-memcached |
| phpMyAdmin | docker-lamp-pma |
| pgAdmin | docker-lamp-pgadmin |

---

# Common Commands

## Start Stack

```bash
docker compose -f compose/docker-compose.yml up -d
```

---

## Stop Stack

```bash
docker compose -f compose/docker-compose.yml down
```

---

## Rebuild Stack

```bash
docker compose -f compose/docker-compose.yml build --no-cache
```

---

## Remove Stack + Volumes

```bash
docker compose -f compose/docker-compose.yml down -v
```

---

## View Running Containers

```bash
docker ps
```

---

## View Logs

```bash
docker compose logs -f
```

---

# Security Notes

Recommended production practices:

- use reverse proxy
- configure HTTPS
- restrict exposed ports
- isolate production secrets
- use environment variables
- avoid default credentials
- enable backups
- monitor container health

---

# Roadmap

Planned future improvements:

- Traefik integration
- automatic SSL
- environment templates
- health monitoring
- queue worker containers
- scheduler containers
- backup automation
- metrics & observability
- production deployment templates

---

# Contributing

Pull requests are welcome.

## Workflow

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push branch
5. Open pull request

---

# License

Apache License 2.0