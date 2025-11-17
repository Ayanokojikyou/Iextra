FROM php:8.2-apache

# Install ekstensi PHP yg biasanya dipakai
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Aktifkan mod_rewrite (jika perlu)
RUN a2enmod rewrite

# Copy semua file ke server Apache
COPY . /var/www/html/

# Atur permission (supaya FPDF, upload, log, dll aman)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
