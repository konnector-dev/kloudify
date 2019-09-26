
FROM jdecode/php7.3-apache-pg-grpc:0.5

# Copy local code to the container image.
COPY . /var/www/html/

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

COPY .env.example .env
ARG GOOGLE_CLOUD_PROJECT

RUN echo " = {GOOGLE_CLOUD_PROJECT}";
RUN sed -ri -e 's/project_id/${GOOGLE_CLOUD_PROJECT}/g' .env

RUN composer install -n --prefer-dist

#RUN chmod -R 0777 storage bootstrap
RUN chown -R www-data:www-data storage bootstrap

RUN php artisan migrate
