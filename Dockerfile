FROM php:8.2-apache

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

COPY . .

EXPOSE 80

CMD ["apache2-foreground"]
