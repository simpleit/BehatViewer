#!/bin/sh

echo "---> Starting $(tput bold ; tput setaf 2)Symfony2 project initialization$(tput sgr0)"

curl -s https://getcomposer.org/installer | php
php composer.phar install --dev --prefer-source

sudo chmod -R 777 app/cache app/logs

app/console --env=test doctrine:schema:create