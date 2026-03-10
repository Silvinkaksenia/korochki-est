FROM serversideup/php:8.2-fpm-nginx

COPY . /var/www/html

USER root
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
USER www-data

# Генерируем ключ позже через переменную окружения
