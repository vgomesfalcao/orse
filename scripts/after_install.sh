#!/bin/bash

cd /var/www/orse_d

composer install

drush cr

drush updb -y

drush cr

drush cim -y

drush cr
