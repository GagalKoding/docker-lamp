# ![Docker-LAMP][logo]
Docker-LAMP for Apple Silicon MAC M1 is a set of docker images that include the arm64v8/ubuntu:18.04 baseimage, along with a LAMP stack ([Apache][apache], [MySQL][mysql] and multiple [PHP][php]) all in one handy package especially for Apple Silicon Mac M1 (ARM architecture).

With Ubuntu **18.04** images on the `latest` tags, Docker-LAMP is flexible enough to use with all of your LAMP projects.

[![Docker Hub][shield-docker-hub]][info-docker-hub]
[![License][shield-license]][info-license]

### Contents
<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [Introduction](#introduction)
- [Image Versions](#image-versions)
- [Using the image](#using-the-image)
  - [On the command line](#on-the-command-line)
  - [With a Dockerfile](#with-a-dockerfile)
  - [MySQL Databases](#mysql-databases)
    - [Creating a database](#creating-a-database)
      - [PHPMyAdmin](#phpmyadmin)
      - [Command Line](#command-line)
- [Adding your own content](#adding-your-own-content)
  - [Adding your app](#adding-your-app)
  - [Persisting your MySQL](#persisting-your-mysql)
  - [Doing both](#doing-both)
    - [`.bash_profile` alias examples](#bash_profile-alias-examples)
      - [Example usage](#example-usage)
- [Developing the image](#developing-the-image)
  - [Building and running](#building-and-running)
- [Inspiration](#inspiration)
- [Contributing](#contributing)
- [License](#license)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Introduction
As a developer, part of my day to day role is to build LAMP applications. I searched in vein for an image that had everything I wanted, up-to-date packages, a simple interface, good documentation and active support. 

Designed to be a single interface that just 'gets out of your way', and works on 18.04 with php 5, 7, and 8.

## Image Versions
The table below shows the PHP, MySQL and Apache versions that come with it.

Component | `latest`
---|---
[Apache][apache] | `2.4.29`
[MySQL][mysql] | `5.7.34`
[PHP][php] | `5.6`, `7.4`, `8.0`
[phpMyAdmin][phpmyadmin] | `5.1.1`
[ionCube][ioncube] | `10.2`


## Using the image
### On the command line
This is the quickest way
```bash
# Launch a 18.04 based image
docker run -p "80:80" -v ${PWD}/app:/app gagalkoding/lamp:latest
```

### With a Dockerfile
```docker
FROM gagalkoding/lamp:latest

# Your custom commands

CMD ["/run.sh"]
```

### MySQL Databases
By default, the image comes with a `root` MySQL account that has no password. This account is only available locally, i.e. within your application. It is not available from outside your docker image or through phpMyAdmin.

When you first run the image you'll see a message showing your `admin` user's password. This user can be used locally and externally, either by connecting to your MySQL port (default 3306) and using a tool like MySQL Workbench or Sequel Pro, or through phpMyAdmin.

If you need this login later, you can run `docker logs CONTAINER_ID` and you should see it at the top of the log.

#### Creating a database
So your application needs a database - you have two options...

1. PHPMyAdmin
2. Command line

##### PHPMyAdmin
Docker-LAMP comes pre-installed with phpMyAdmin available from `http://DOCKER_ADDRESS/phpmyadmin`.

**NOTE:** you cannot use the `root` user with PHPMyAdmin. We recommend logging in with the admin user mentioned in the introduction to this section.

##### Command Line
First, get the ID of your running container with `docker ps`, then run the below command replacing `CONTAINER_ID` and `DATABASE_NAME` with your required values:
```bash
docker exec CONTAINER_ID  mysql -uroot -e "create database DATABASE_NAME"
```


## Adding your own content
The 'easiest' way to add your own content to the lamp image is using Docker volumes. This will effectively 'sync' a particular folder on your machine with that on the docker container.

The below examples assume the following project layout and that you are running the commands from the 'project root'.
```
/ (project root)
/app/ (your PHP files live here)
/mysql/ (docker will create this and store your MySQL data here)
```

In english, your project should contain a folder called `app` containing all of your app's code. That's pretty much it.

### Adding your app
The below command will run the docker image `gagalkoding/lamp:latest` interactively, exposing port `80` on the host machine with port `80` on the docker container. It will then create a volume linking the `app/` directory within your project to the `/app` directory on the container. This is where Apache is expecting your PHP to live.
```bash
docker run -i -t -p "80:80" -v ${PWD}/app:/app gagalkoding/lamp:latest
```

### Persisting your MySQL
The below command will run the docker image `gagalkoding/lamp:latest`, creating a `mysql/` folder within your project. This folder will be linked to `/var/lib/mysql` where all of the MySQL files from container lives. You will now be able to stop/start the container and keep your database changes.

You may also add `-p 3306:3306` after `-p 80:80` to expose the mysql sockets on your host machine. This will allow you to connect an external application such as SequelPro or MySQL Workbench.
```bash
docker run -i -t -p "80:80" -v ${PWD}/mysql:/var/lib/mysql gagalkoding/lamp:latest
```

### Doing both
The below command is our 'recommended' solution. It both adds your own PHP and persists database files. We have created a more advanced alias in our `.bash_profile` files to enable the short commands `ldi` and `launchdocker`. See the next section for an example.
```bash
docker run -i -t -p "80:80" -v ${PWD}/app:/app -v ${PWD}/mysql:/var/lib/mysql gagalkoding/lamp:latest
```

#### `.bash_profile` alias examples
The below example can be added to your `~/.bash_profile` file to add the alias commands `ldi` and `launchdocker`. By default it will launch the 18.04 image.
```bash
# A helper function to launch docker container using gagalkoding/lamp with overrideable parameters
#
# $1 - Apache Port (optional)
# $2 - MySQL Port (optional - no value will cause MySQL not to be mapped)
function launchdockerwithparams {
    APACHE_PORT=80
    MYSQL_PORT_COMMAND=""
    
    if ! [[ -z "$1" ]]; then
        APACHE_PORT=$1
    fi
    
    if ! [[ -z "$2" ]]; then
        MYSQL_PORT_COMMAND="-p \"$2:3306\""
    fi

    docker run -i -t -p "$APACHE_PORT:80" $MYSQL_PORT_COMMAND -v ${PWD}/app:/app -v ${PWD}/mysql:/var/lib/mysql gagalkoding/lamp:latest
}
alias launchdocker='launchdockerwithparams $1 $2'
alias ldi='launchdockerwithparams $1 $2'
```

##### Example usage
```bash
# Launch docker and map port 80 for apache
ldi

# Launch docker and map port 8080 for apache
ldi 8080

# Launch docker and map port 3000 for apache along with 3306 for MySQL
ldi 3000 3306
```


## Developing the image
### Building and running
```bash
# Clone the project from Github
git clone https://github.com/gagalkoding/docker-lamp.git
cd docker-lamp

# Build the images
docker build -t=gagalkoding/lamp:latest -f ./1804/Dockerfile .

# Run the image as a container
docker run -d -p "3000:80" gagalkoding/lamp:latest

# Sleep to allow the container to boot
sleep 5

# Curl out the contents of our new container
curl "http://$(docker-machine ip):3000/"
```


## Inspiration
This image was originally based on [mattrayner/lamp][mattrayner-lamp], with a few changes to make it compatible with the Laravel, CodeIgniter, WHMCS (with ionCube 10.2), and other CMS/Framework.

I also changed the setup to create ubuntu using arm64v8 so that this project can run very well on Apple Silicon (especially on MAC M1).


## Contributing
If you wish to submit a bug fix or feature, you can create a pull request and it will be merged pending a code review.

1. Clone/fork it
2. Create your feature branch (git checkout -b my-new-feature)
3. Commit your changes (git commit -am 'Add some feature')\
4. Push to the branch (git push origin my-new-feature)
5. Create a new Pull Request


## License
Docker-LAMP is licensed under the [Apache 2.0 License][info-license].


[logo]: https://raw.githubusercontent.com/GagalKoding/docker-lamp/master/docs/logo.svg

[apache]: http://www.apache.org/
[mysql]: https://www.mysql.com/
[php]: http://php.net/
[phpmyadmin]: https://www.phpmyadmin.net/
[ioncube]: https://www.ioncube.com/

[end-of-life]: http://php.net/supported-versions.php

[info-docker-hub]: https://hub.docker.com/r/gagalkoding/lamp
[info-license]: LICENSE

[shield-docker-hub]: https://img.shields.io/badge/docker%20hub-gagalkoding%2Flamp-brightgreen.svg
[shield-license]: https://img.shields.io/badge/license-Apache%202.0-blue.svg

[mattrayner-lamp]: https://github.com/mattrayner/docker-lamp
