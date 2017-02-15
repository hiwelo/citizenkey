#!/usr/bin/env bash
# WordPress Vagrant config
# Packages installed:  mysql 5.6.33 / 5.6.16, hhvm, php7 with mysql drivers, nginx, git, NodeJS, NPM, Bower, Gulp

# Unlock the root and give it a password? (YES/NO)
ROOT=YES

echo "Booting the machine..."

if [ ! -f /var/log/firsttime ];
then
    sudo touch /var/log/firsttime

    # for Ubuntu 16.04 LTS to be able to install mysql-server 5.6.*
    sudo add-apt-repository 'deb http://archive.ubuntu.com/ubuntu trusty universe'

    # Set credentials for MySQL
    sudo debconf-set-selections <<< "mysql-server-5.6 mysql-server/root_password password vagrant"
    sudo debconf-set-selections <<< "mysql-server-5.6 mysql-server/root_password_again password vagrant"

    # Install packages
    sudo apt-get update
    sudo apt-get -y upgrade
    sudo apt-get -y --force-yes install nginx mysql-server-5.6 software-properties-common
    sudo update-rc.d mysql defaults
    sudo update-rc.d mysql enable

    # Config nginx
    sudo cp /var/www/app/config/server/nginx.conf /etc/nginx/

    # Install php7.0-fpm
    sudo apt-get -y --force-yes install python-software-properties
    sudo LC_ALL=en_US.UTF-8 add-apt-repository -y ppa:ondrej/php
    sudo apt-get update
    sudo apt-get remove -y php5-common
    sudo apt-get -y autoremove
    sudo apt-get -y --force-yes install php7.0-cli php7.0-fpm php7.0-curl php7.0-cgi php7.0-mcrypt php7.0-gd php7.0-imagick php7.0-recode php7.0-sqlite php7.0-xmlrpc php7.0-xsl php7.0-mysql php php-mysql
    sudo cp /vagrant/app/config/server/php7.conf /etc/nginx/
    sudo update-rc.d php7.0-fpm defaults
    sudo update-rc.d php7.0-fpm auto

    # Add timezones to database
    mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -uroot -pvagrant mysql

    # Install curl & memcache
    sudo apt-get -y install curl memcached

    # Install Node & NPM & Gulp
    sudo apt-get install -y nodejs npm
    sudo npm install -y --global gulp-cli

    # Install Composer
    curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

    # Add vhosts
    sudo mkdir /etc/nginx/sites-available/
    sudo mkdir /etc/nginx/sites-enabled/
    sudo cp -Rv /var/www/app/config/server/vhosts/* /etc/nginx/sites-available/
    sudo ln -nfs /etc/nginx/sites-available/* /etc/nginx/sites-enabled/

    # Starting mysql
    sudo service mysql start

    # Install phpmyadmin
    # sudo apt-get -y install phpmyadmin
    # sudo ln -s /usr/share/phpmyadmin/ /usr/share/nginx/html/

    # only needed for Ubuntu 16.04
    sudo -i

    # Restart VM
    service apache2 stop
    service nginx restart

    # Set up database
    mysql -uroot -pvagrant -e "CREATE DATABASE citizenkeys"

    # Symfony initialization
    cd /var/www
    rm -r html
    cp app/config/parameters.vagrant.yml app/config/parameters.yml
    composer install
    php bin/console doctrine:schema:create
    php bin/console doctrine:fixtures:load --no-interaction
fi

if [ $ROOT = 'YES' ]
then
    sudo usermod -U root
    echo -e "password\npassword" | sudo passwd root
fi
