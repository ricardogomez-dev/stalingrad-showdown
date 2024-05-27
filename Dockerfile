FROM php:apache

RUN a2enmod rewrite

# Install dependencies
RUN apt-get update && \
    apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev zip unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Run Composer install to install dependencies
RUN composer require fakerphp/faker

# Install the MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb 

# Copy application code
COPY . /var/www/html
