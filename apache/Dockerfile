# Parent
FROM php:7.2-apache

# Update packages
RUN apt-get update

# Switch to standard user
RUN useradd -m matthewslouismarie
RUN apt-get install -y sudo
COPY config/sudoers /etc/sudoers.d/sudoers
RUN chmod 0444 /etc/sudoers.d/sudoers
USER matthewslouismarie

# Packages
RUN sudo apt-get install -y wget git zlib1g-dev gnupg2

# PHP configuration
RUN sudo docker-php-ext-install pdo_mysql zip

# Apache URL configuration
RUN sudo a2enmod rewrite
RUN sudo a2enmod headers
COPY config/000-default.conf /etc/apache2/sites-available

# Install Composer
WORKDIR /usr/local/bin
COPY scripts/install-composer .
COPY scripts/composer .
RUN sudo chmod 755 install-composer composer
RUN sudo ./install-composer
RUN sudo rm install-composer

# Install NPM and Grunt
RUN sudo curl -sL https://deb.nodesource.com/setup_8.x | sudo bash -
RUN sudo apt-get install -y nodejs
RUN sudo npm install -g grunt-cli

# Set working directory
WORKDIR /var/www/html

# Set permissions
RUN sudo mkdir symfony
RUN sudo chown -cR matthewslouismarie: symfony

CMD sudo apache2-foreground