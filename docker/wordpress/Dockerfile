FROM php:5.6-apache

RUN apt-get update \
  && apt-get install -y \
    curl `# for installing curl` \
    git `# needed for composer installing` \
    sudo \
    subversion `# wp-cli dependency` \
    mysql-client

# From php docker
RUN a2enmod rewrite
RUN apt-get install -y libpng12-dev libjpeg-dev \
	&& docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
	&& docker-php-ext-install gd mysqli \
	&& docker-php-ext-install mbstring pdo pdo_mysql # for codeception

# Hack to user local user
ARG GROUP_ID=1000
ARG USER_ID=1000
RUN groupadd -g $GROUP_ID user && \
	adduser --disabled-password --gecos '' --uid=$USER_ID --gid=$GROUP_ID user && \
	adduser user sudo && \
	echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers
USER user

# install composer
RUN curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH /home/user/.composer/vendor/bin:$PATH

# install codeception
RUN composer global require "codeception/codeception:2.3.6"

# install wp-cli
RUN sudo curl https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp \
  && sudo chmod +x /usr/local/bin/wp

RUN sudo rm -rf /var/lib/apt/lists/*

WORKDIR /data

RUN sudo pear update-channels \
    && sudo pear upgrade \
    && sudo pecl install xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/
COPY php.ini /usr/local/etc/php/

CMD sudo apache2-foreground