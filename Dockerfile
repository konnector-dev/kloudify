
FROM sandymadaan/php7.3-docker-newrelic:0.4

# Copy local code to the container image.
COPY . /var/www/html/

#RUN chmod +x ./.deploy/commands/*.sh

#RUN ./.deploy/commands/check_rejected_commits.sh

#RUN newman run resources/postman-collection/yego-api.postman_collection.json -e resources/postman-collection/UAT.postman_environment.json

#ARG NR_INSTALL_SILENT
#ARG NEWRELIC_LICENSE

#ENV NR_INSTALL_SILENT 1
#ENV NR_INSTALL_KEY "${NEWRELIC_LICENSE}"
#ENV NR_APP_NAME "${NR_APP_NAME}"

#RUN newrelic-install install

RUN service apache2 restart
# Use the PORT environment variable in Apache configuration files.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

#ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Authorise .htaccess files
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

#RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
#RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

#COPY .env.example .env

#ARG GOOGLE_CLOUD_PROJECT

#RUN sed -ri -e 's/project_id/${GOOGLE_CLOUD_PROJECT}/g' .env

RUN composer install -n --prefer-dist

#RUN chown -R www-data:www-data storage bootstrap
#RUN chmod -R 777 storage bootstrap

#RUN php artisan key:generate

#RUN ./.deploy/validate.sh

