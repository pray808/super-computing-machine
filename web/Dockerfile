FROM php:8.2-apache

# Cette ligne dit à Apache d'utiliser login.php comme page d'accueil
RUN sed -i 's/DirectoryIndex index.html/DirectoryIndex login.php/' /etc/apache2/mods-enabled/dir.conf

COPY . /var/www/html/