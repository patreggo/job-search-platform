FROM php:8.2.3-fpm as base

RUN apt update
RUN apt install -y git
RUN apt install -y wget
RUN apt install -y libnss3

RUN apt-get update && apt-get install -y \
        apt-utils \
        libpng-dev \
        libfreetype6-dev \
        libjpeg-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
        libpq-dev \
        libjpeg-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) exif

RUN DEBIAN_FRONTEND=noninteractive apt-get update \
   && apt-get install -y \
       librabbitmq-dev \
       curl \
   && rm -rf /var/lib/apt/lists/*

RUN pecl install amqp \
    && docker-php-ext-enable amqp

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install -j$(nproc) gd


#Установка композера
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Установка ноды
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y \
  --no-install-recommends nodejs \
  && rm -rf /var/lib/apt/lists/*

RUN apt-get update \
    && apt-get install -y default-mysql-client

RUN npm install --global yarn

# xdebug
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions xdebug
ENV PHP_IDE_CONFIG 'serverName=XdebugProjectPHPServer'
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#CMD ["/usr/local/bin/php", "bin/console", "messenger:consume >&1;"]

WORKDIR /var/www

#RUN ["chmod", "+x", " /var/www/.docker/script/message_script.sh"]
#ENTRYPOINT ["bash", "/var/www/.docker/script/message_script.sh"]

FROM base as image-width-chrome

# Set up the Chrome PPA
# Install chrome, required for the symfony/panther library
ENV PANTHER_NO_SANDBOX 1
# Not mandatory, but recommended
ENV PANTHER_CHROME_ARGUMENTS='--headless --no-sandbox --disable-infobars --disable-dev-shm-usage --disable-gpu --disable-extensions --remote-debugging-port=9222'
RUN wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb -O /tmp/chrome.deb
RUN dpkg -i /tmp/chrome.deb || apt-get install -yf
RUN rm /tmp/chrome.deb
