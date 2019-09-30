
FROM jdecode/php7.3-apache-pg-grpc:0.5

# Copy local code to the container image.
COPY . /var/www/html/

ARG NEWRELIC_LICENSE
ARG GOOGLE_CLOUD_PROJECT

# Install New Relic daemon
RUN apt-get update && \
    apt-get -yq install gnupg2 && \
    wget -O - https://download.newrelic.com/548C16BF.gpg | apt-key add - && \
    echo "deb http://apt.newrelic.com/debian/ newrelic non-free" > /etc/apt/sources.list.d/newrelic.list
 
# Setup environment variables for initializing New Relic
ENV NR_INSTALL_SILENT 1
ENV NR_INSTALL_KEY "${NEWRELIC_LICENSE}"
ENV NR_APP_NAME "${NR_APP_NAME}"
RUN apt-get update && \
    apt-get -yq install newrelic-php5
 
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

RUN ./.deploy/commands/parallel_lint.sh
RUN ./.deploy/commands/phpcs.sh
RUN ./.deploy/commands/phpunit.sh