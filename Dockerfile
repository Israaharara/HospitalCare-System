FROM php:8.2-apache

WORKDIR /var/www/html

# تثبيت إضافات mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# نسخ كل شيء من داخل مجلد Main إلى المجلد الرئيسي للسيرفر مباشرة
COPY src/Main/ .

# إعطاء الصلاحيات
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# إخبار السيرفر بفتح login.php كصفحة رئيسية
RUN sed -i 's|DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm|DirectoryIndex login.php index.php index.html|' /etc/apache2/mods-enabled/dir.conf

EXPOSE 80

CMD ["apache2-foreground"]
