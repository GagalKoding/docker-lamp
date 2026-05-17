# Changelog

All notable changes to this project will be documented in this file.

The format is inspired by:

* Keep a Changelog
* Semantic Versioning

---

# [2.0.0] - 2026-05-17

## Major Rewrite

Docker-LAMP has been completely modernized from a monolithic Ubuntu 18.04 image into a modular multi-runtime PHP development platform.

This release introduces:

* isolated PHP runtime containers
* modern Docker Compose architecture
* Apache + PHP-FPM networking
* ARM64 / Apple Silicon support
* dynamic `.htaccess` runtime switching
* MariaDB separation
* phpMyAdmin separation
* ionCube support improvements

---

## Added

### Runtime Containers

* Added isolated PHP 5.6 container
* Added isolated PHP 7.4 container
* Added isolated PHP 8.5 container

### Apache Container

* Added dedicated Apache container
* Added proxy_fcgi runtime routing
* Added `.htaccess` runtime switching

### Database

* Added MariaDB 11 container
* Added persistent database volume support
* Added phpMyAdmin container

### PHP Features

* Added Composer support
* Added ionCube support
* Added multi-runtime PHP switching
* Added PHP-FPM TCP runtime architecture

### Platform Support

* Added Apple Silicon (ARM64) support
* Added modern Docker Compose support
* Added modular container architecture

---

## Changed

### Architecture

* Replaced monolithic container architecture
* Replaced internal MySQL with isolated MariaDB container
* Replaced Unix socket runtime routing with Docker networking
* Replaced supervisor-based startup with native container startup

### PHP Runtime Switching

Old:

```apache id="r7m4z2"
SetHandler "proxy:unix:/var/run/php/php7.4-fpm.sock|fcgi://localhost/"
```

New:

```apache id="f2v9w6"
SetHandler "proxy:fcgi://php74:9000"
```

### Docker Structure

Old:

```text id="p4j1c8"
1804/
supporting_files/
```

New:

```text id="v9t2m5"
docker/
  apache/
  php56/
  php74/
  php85/
```

---

## Removed

* Removed Ubuntu 18.04 monolithic image architecture
* Removed supervisord-based runtime management
* Removed internal MySQL installation
* Removed legacy startup scripts
* Removed old socket-based runtime architecture

---

## Runtime Matrix

| Runtime | Status    |
| ------- | --------- |
| PHP 5.6 | Supported |
| PHP 7.4 | Supported |
| PHP 8.5 | Supported |

---

# [0.1.0]

Initial release.
