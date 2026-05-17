# ![Docker-LAMP](docs/logo.svg)

# Docker-LAMP v2

Modern multi-runtime PHP development platform with Apache, MariaDB, phpMyAdmin, ionCube, and dynamic `.htaccess` PHP switching.

Supports:

* PHP 5.6
* PHP 7.4
* PHP 8.5
* Apache 2.4
* MariaDB 11
* phpMyAdmin
* ionCube Loader
* Apple Silicon / ARM64
* Intel / AMD64

Designed for:

* Laravel
* CodeIgniter
* WHMCS
* WordPress
* Legacy PHP projects
* Migration testing
* Multi-version development

---

# Features

* Dynamic PHP version switching using `.htaccess`
* Separate isolated PHP runtime containers
* Apache + PHP-FPM architecture
* ARM64 / Apple Silicon support
* ionCube support
* MariaDB + phpMyAdmin included
* Modern Docker Compose architecture
* Compatible with legacy and modern PHP applications

---

# Architecture

Docker-LAMP v2 uses a modular container architecture:

```text
Apache Container
    ├── PHP 5.6 Container
    ├── PHP 7.4 Container
    ├── PHP 8.5 Container
    ├── MariaDB Container
    └── phpMyAdmin Container
```

Each PHP runtime is fully isolated:

* extensions
* ionCube
* PHP-FPM
* Composer
* dependencies

This makes the platform:

* cleaner
* more maintainable
* easier to upgrade
* more stable

---

# Supported PHP Versions

| PHP Version | Status       | Purpose                   |
| ----------- | ------------ | ------------------------- |
| 5.6         | Legacy       | WHMCS, old CMS, migration |
| 7.4         | Transitional | Laravel 6/7/8, migration  |
| 8.5         | Modern       | Latest PHP applications   |

---

# Requirements

* Docker
* Docker Compose
* Apple Silicon / Intel supported

---

# Project Structure

```text
.
├── app/
├── compose/
│   └── docker-compose.yml
├── docker/
│   ├── apache/
│   ├── php56/
│   ├── php74/
│   └── php85/
└── docs/
```

---

# Installation Methods

Docker-LAMP v2 supports two installation methods:

| Method            | Recommended For            |
| ----------------- | -------------------------- |
| Docker Hub Images | Normal users               |
| Local Build       | Contributors & development |

---

# Method 1 — Docker Hub Images (Recommended)

Uses prebuilt Docker Hub images.

No local image build required.

## Clone Repository

```bash
git clone https://github.com/GagalKoding/docker-lamp.git && cd docker-lamp
```

---

## Start Containers

```bash
docker compose -f compose/docker-compose.hub.yml up
```

Docker will automatically pull:

* Apache
* PHP runtimes
* MariaDB
* phpMyAdmin

from Docker Hub.

---

## Open in Browser

Application:

```text
http://localhost
```

phpMyAdmin:

```text
http://localhost:8080
```

---

# Method 2 — Local Build

Build all containers locally.

Recommended for:

* contributors
* runtime customization
* Docker image development

## Clone Repository

```bash
git clone https://github.com/GagalKoding/docker-lamp.git && cd docker-lamp
```

---

## Build Containers

```bash
docker compose -f compose/docker-compose.yml build --no-cache
```

---

## Start Containers

```bash
docker compose -f compose/docker-compose.yml up
```

---

## Open in Browser

Application:

```text
http://localhost
```

phpMyAdmin:

```text
http://localhost:8080
```

---

# PHP Runtime Switching

Docker-LAMP v2 supports dynamic PHP runtime switching using `.htaccess`.

Create:

```text
app/.htaccess
```

---

## PHP 8.5

```apache
<FilesMatch \.php$>
    SetHandler "proxy:fcgi://php85:9000"
</FilesMatch>
```

---

## PHP 7.4

```apache
<FilesMatch \.php$>
    SetHandler "proxy:fcgi://php74:9000"
</FilesMatch>
```

---

## PHP 5.6

```apache
<FilesMatch \.php$>
    SetHandler "proxy:fcgi://php56:9000"
</FilesMatch>
```

No rebuild required.

Simply refresh browser after changing `.htaccess`.

---

# Database Access

## MariaDB

Host:

```text
db
```

Port:

```text
3306
```

Default credentials:

| Key      | Value |
| -------- | ----- |
| Username | root  |
| Password | root  |
| Database | app   |

---

# phpMyAdmin

URL:

```text
http://localhost:8080
```

Server:

```text
db
```

Username:

```text
root
```

Password:

```text
root
```

---

# Example PDO Connection

```php
<?php

$db = new PDO(
    'mysql:host=db;dbname=app',
    'root',
    'root'
);
```

---

# ionCube Support

Docker-LAMP v2 includes ionCube support for:

* PHP 5.6
* PHP 7.4
* PHP 8.5

Perfect for:

* WHMCS
* encoded legacy applications
* commercial PHP software

---

# Apple Silicon Support

Docker-LAMP v2 fully supports:

* Apple Silicon M1
* Apple Silicon M2
* ARM64 systems

No Rosetta required.

---

# Common Commands

## Stop Containers

```bash
docker compose -f compose/docker-compose.yml down
```

---

## Rebuild Containers

```bash
docker compose -f compose/docker-compose.yml build --no-cache
```

---

## Remove Everything

```bash
docker compose -f compose/docker-compose.yml down -v
```

---

# Container Names

| Service    | Container          |
| ---------- | ------------------ |
| Apache     | docker-lamp-apache |
| PHP 5.6    | docker-lamp-php56  |
| PHP 7.4    | docker-lamp-php74  |
| PHP 8.5    | docker-lamp-php85  |
| MariaDB    | docker-lamp-db     |
| phpMyAdmin | docker-lamp-pma    |

---

# Contributing

Pull requests are welcome.

Steps:

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push branch
5. Open pull request

---

# License

Apache 2.0 License
