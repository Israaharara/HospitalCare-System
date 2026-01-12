FROM php:8.2-apache

WORKDIR /var/www/html

# تثبيت إضافات mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# نسخ كافة المجلدات والملفات
COPY . .

# إعطاء الصلاحيات الكاملة لجميع الملفات المنسوخة
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# توجيه السيرفر للبحث عن login.php في كل المجلدات الفرعية
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/src/Main|' /etc/apache2/sites-available/000-default.conf

# تعيين ملف البداية
RUN sed -i 's|DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm|DirectoryIndex login.php index.php|' /etc/apache2/mods-enabled/dir.conf

EXPOSE 80

CMD ["apache2-foreground"]
