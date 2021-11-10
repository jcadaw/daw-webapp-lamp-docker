ARG APACHE_VERSION=""
FROM httpd:${APACHE_VERSION:+${APACHE_VERSION}-}alpine

COPY ./localhost.conf /usr/local/apache2/conf/

RUN echo "Include /usr/local/apache2/conf/localhost.conf" \
    >> /usr/local/apache2/conf/httpd.conf
