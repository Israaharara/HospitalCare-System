FROM php:8.2-apache

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

COPY src/ .

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/Main|' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]






