#!/bin/bash

chown -R www-data:www-data /var/www/orse/orse_d

chmod -R 775 /var/www/orse/orse_d

cd /var/www/orse/orse_d

composer install --no-interaction

drush cr

drush updb -y

drush cr

drush cim -y

drush cr
