FROM composer:2.5.8 as build
COPY . /app/
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

FROM php:8.2-apache-buster as dev

ENV APP_ENV=dev
ENV APP_DEBUG=true
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y zip
RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/notebook/
COPY --from=build /usr/bin/composer /usr/bin/composer
#RUN composer install --prefer-dist --no-interaction

#COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
#COPY .env /var/www/notebook/.env
#COPY artisan /var/www/notebook/artisan

RUN chmod 777 -R /var/www/notebook/storage/

#RUN #php artisan config:cache && \
#    php artisan route:cache && \
#    chmod 777 -R /var/www/notebook/storage/ && \
#    chown -R www-data:www-data /var/www/ && \
#    a2enmod rewrite

#FROM php:8.2-apache-buster as production
#
#ENV APP_ENV=production
#ENV APP_DEBUG=false
#
#RUN docker-php-ext-configure opcache --enable-opcache && \
#    docker-php-ext-install pdo pdo_mysql
##COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
#
#COPY --from=build /app /var/www/notebook
##COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
##COPY .env.prod /var/www/notebook/.env
#
#RUN php artisan config:cache && \
#    php artisan route:cache && \
#    chmod 777 -R /var/www/notebook/storage/ && \
#    chown -R www-data:www-data /var/www/ && \
#    a2enmod rewrite
