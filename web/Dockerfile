FROM php:8.2-apache

# On demande à Apache d'utiliser login.php comme page d'accueil par défaut
RUN sed -i 's/DirectoryIndex index.html/DirectoryIndex login.php/' /etc/apache2/mods-enabled/dir.conf

COPY . /var/www/html/