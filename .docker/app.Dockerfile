FROM php:7.4-fpm

ARG DOCKER_PROJECT_DIR
ARG XDEBUG_REMOTE_HOST
ARG XDEBUG_REMOTE_PORT
ARG XDEBUG_IDEKEY
ARG TIMEZONE

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    locales \
    git \
    zip \
    unzip \
    libzip-dev \
    wget

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo zip

# Install xdebug extension
RUN pecl install xdebug

# Prepare php config in container
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Copy php config files to container
COPY etc/php/conf.d/00-php.ini /usr/local/etc/php/conf.d/00-php.ini
COPY etc/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Create xdebug.log
RUN touch /tmp/xdebug.log

# Grant rights xdebug.log
RUN chmod 777 /tmp/xdebug.log

# Set timezone in php
RUN echo "\
[Date]\n\
date.timezone=${TIMEZONE}"\
    >> /usr/local/etc/php/php.ini;

# Set xdebug environment
RUN echo "\
xdebug.remote_host=${XDEBUG_REMOTE_HOST}\n\
xdebug.remote_port=${XDEBUG_REMOTE_PORT}\n\
xdebug.idekey=${XDEBUG_IDEKEY}"\
    >> /usr/local/etc/php/conf.d/xdebug.ini

# Copy existing application directory contents
COPY . ${DOCKER_PROJECT_DIR}

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]



