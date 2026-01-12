FROM php:8.2-apache

WORKDIR /var/www/html

# تثبيت إضافات قاعدة البيانات
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# نسخ كل محتويات مجلد Main مباشرة إلى المجلد الرئيسي للسيرفر
COPY src/Main/ .

# إعطاء صلاحيات الدخول
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# إخبار السيرفر أن يبدأ بفتح ملف login.php
RUN sed -i 's|DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm|DirectoryIndex login.php index.php index.html|' /etc/apache2/mods-enabled/dir.conf

EXPOSE 80

CMD ["apache2-foreground"]


