
FROM jdecode/php7.3-apache-pg-grpc:0.5

# Use the PORT environment variable in Apache configuration files.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Authorise .htaccess files
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


# Configure PHP for development.
# Switch to the production php.ini for production operations.
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# https://hub.docker.com/_/php#configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY . /var/www/html/
RUN composer install -n --prefer-dist

#RUN chmod -R 0777 storage bootstrap
RUN chown -R www-data:www-data storage bootstrap

#RUN php artisan migrate
#RUN apt-get update && apt-get install -y gnupg2

#Stackdriver monitoring agent
#RUN curl -sSO https://dl.google.com/cloudagents/install-monitoring-agent.sh
#RUN bash install-monitoring-agent.sh

#Stackdriver logging agent
#RUN curl -sSO https://dl.google.com/cloudagents/install-logging-agent.sh
#RUN bash install-logging-agent.sh
