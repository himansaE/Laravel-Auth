FROM mcr.microsoft.com/devcontainers/php:8.2

# Install system dependencies
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y \
        unzip \
        git \
        curl \
        libzip-dev \
        zip \
        nodejs \
        npm \
        sqlite3

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Install Laravel installer globally (optional)
RUN composer global require laravel/installer

# Set PATH for global composer tools
ENV PATH="/root/.composer/vendor/bin:${PATH}"

WORKDIR /workspace
