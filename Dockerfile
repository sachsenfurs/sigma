FROM dunglas/frankenphp:php8.4

ENV NODE_VERSION=24

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN install-php-extensions \
    pdo_mysql \
    gd \
    intl \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Get latest Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install node and NPM
ENV NVM_DIR /usr/local/nvm
RUN mkdir $NVM_DIR \
    && curl https://raw.githubusercontent.com/creationix/nvm/v0.40.6/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default \
    && ln -s $NVM_DIR/versions/node/$(nvm version $NODE_VERSION) $NVM_DIR/v$NODE_VERSION
ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH      $NVM_DIR/v$NODE_VERSION/bin:$PATH

# Set working directory
WORKDIR /app

CMD ["php", "artisan", "serve", "--host=0.0.0.0"]



