
FROM jdecode/php7.3-apache-pg-grpc:0.5

# Copy local code to the container image.
COPY . /var/www/html/

ARG NEWRELIC_LICENSE
ARG GOOGLE_CLOUD_PROJECT

RUN cd ~ \
    && export NEWREIC_VERSION="$(curl -sS https://download.newrelic.com/php_agent/release/ | sed -n 's/.*>\(.*linux-musl\).tar.gz<.*/\1/p')" \
    && curl -sS "https://download.newrelic.com/php_agent/release/${NEWRELIC_VERSION}.tar.gz" | gzip -dc | tar xf - \
    && cd "${NEWRELIC_VERSION}" \
    && NR_INSTALL_SILENT=true ./newrelic-install install \
    && cd .. \
    && unset NEWRELIC_VERSION \
    && sed -i \
        -e "s/newrelic.license =.*/newrelic.license = \${NEWRELIC_LICENSE}/" \
        -e "s/newrelic.appname =.*/newrelic.appname = \kloudify/" \
        /usr/local/etc/php/conf.d/newrelic.ini

#ENV NR_APP_NAME "Kloudify"

RUN service apache2 restart
# Use the PORT environment variable in Apache configuration files.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

#ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Authorise .htaccess files
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configure PHP for development.
# Switch to the production php.ini for production operations.
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# https://hub.docker.com/_/php#configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY .env.example .env

RUN sed -ri -e 's/project_id/${GOOGLE_CLOUD_PROJECT}/g' .env

RUN composer install -n --prefer-dist

#RUN chmod -R 0777 storage bootstrap
RUN chown -R www-data:www-data storage bootstrap

RUN php artisan key:generate

RUN composer cghooks update
