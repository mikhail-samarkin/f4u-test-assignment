FROM symfony-app

ARG DOCKER_PROJECT_DIR
ARG COMPOSER_VERSION
ARG COMPOSER_ASSET_PLUGIN_VERSION

# Allow Composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_VERSION ${COMPOSER_VERSION}

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=${COMPOSER_VERSION}

WORKDIR ${DOCKER_PROJECT_DIR}



