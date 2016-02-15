# Choobs MariaDB Docker Image #

- Follows mariadb's [official image](https://hub.docker.com/r/library/mariadb/) workflow so all ENV variables and init script *should* work.
- The image is based on the [alpine](https://hub.docker.com/_/alpine/) image and comes in about 100MB lighter than the official mariadb image.
- The `pinba` tag provides the [pinba engine plugin](http://pinba.org) for MySQL.
- Report any issues in the github [issue tracker](https://github.com/choobs/docker-mariadb/issues/new).