FROM php:8.0.3-fpm-alpine3.13

RUN apk add --no-cache shadow $PHPIZE_DEPS \
    curl \
    gnupg \
    nano \
    git \
    bash \
    openssl \
    unixodbc-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    libressl-dev \
    curl-dev \
    icu-dev \
    npm \
    supervisor \
    util-linux

# Set container timezone
RUN apk add -U tzdata
RUN cp /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime

# Install swoole with pecl
RUN pecl install swoole
RUN rm -rf /var/lib/apt/lists/*

# Install, configure and enable PHP dependencies
RUN docker-php-ext-install pdo pdo_mysql intl
RUN docker-php-ext-configure intl
RUN docker-php-ext-enable pdo_mysql

# Set custom php.ini
RUN ln -s /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Make supervisor log folders and copy to container
RUN mkdir -p /etc/supervisor/conf.d
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN mkdir /var/log/supervisor

# Install sockets RabbitMQ
RUN CFLAGS="$CFLAGS -D_GNU_SOURCE" docker-php-ext-install sockets
COPY docker/php.ini /etc/php/8.0/cli/conf.d/99-sail.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set default work directory
WORKDIR /var/www

# Link html folder to public
RUN rm -rf html && ln -s public html

# Configure the swoole extension
RUN touch /usr/local/etc/php/conf.d/swoole.ini && \
    echo 'extension=swoole.so' > /usr/local/etc/php/conf.d/swoole.ini

# Copy the project to container
COPY . .

# Give read permissions to entrypoint file
RUN chmod +x docker/start-container.sh

# Copy and apply cron to container
COPY docker/cron.conf /etc/cron.d/finance-control
RUN crontab /etc/cron.d/finance-control

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# Expose the port
EXPOSE 8000

# Run the entrypoint
ENTRYPOINT [ "docker/start-container.sh" ]
