ARG PHPMYADMIN_VERSION=""
FROM phpmyadmin:${PHPMYADMIN_VERSION:+${PHPMYADMIN_VERSION}-}fpm-alpine

RUN sed -i 's/9000/9001/' /usr/local/etc/php-fpm.d/zz-docker.conf
