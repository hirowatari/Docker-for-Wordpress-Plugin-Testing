FROM php:5-apache

RUN apt-get update \
  && apt-get install -y \
    curl `# for installing curl` \
    git `# needed for composer installing` \
    mysql-client \
    subversion `# wp-cli dependency` \
    sudo

RUN apt-get update && apt-get install -y libpng12-dev libjpeg-dev && rm -rf /var/lib/apt/lists/* \
	&& docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
	&& docker-php-ext-install gd mysqli \
	&& docker-php-ext-install mbstring # for codeception

# add user
ARG GROUP_ID=1000
ARG USER_ID=1000
RUN groupadd -g $GROUP_ID user && \
	adduser --disabled-password --gecos '' --uid=$USER_ID --gid=$GROUP_ID user && \
	adduser user sudo && \
	echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers
USER user

# install composer
RUN curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# install codeception
RUN composer global require "codeception/codeception:2.1.2"

# install wp-cli
RUN sudo curl https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp \
  && sudo chmod +x /usr/local/bin/wp

#install phantomjs
WORKDIR /tmp
RUN sudo apt-get install bzip2
# you probably don't need most of these, many are just for building phantomjs, but we're using the binary
RUN sudo apt-get -y install build-essential g++ flex bison gperf ruby perl \
  libsqlite3-dev libfontconfig1-dev libicu-dev libfreetype6 libssl-dev \
  libpng-dev libjpeg-dev python libx11-dev libxext-dev
RUN curl https://phantomjs.googlecode.com/files/phantomjs-1.9.2-linux-x86_64.tar.bz2 | tar jx \
  && sudo mv phantomjs-1.9.2-linux-x86_64/bin/phantomjs /usr/local/bin/ \
  && rm -fr phantomjs-1.9.2-linux-x86_64*

ENV PATH /home/user/.composer/vendor/bin:$PATH

WORKDIR /data

# todo move phantomjs to another container
RUN sudo apt-get install -y parallel

CMD parallel ::: 'phantomjs --webdriver=4444' 'sudo apache2-foreground'
