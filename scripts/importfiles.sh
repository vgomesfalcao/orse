
from-stage(){
    if [[ -z $1 ]]
    then
        rsync -avhP ${STAGING_SSH_USERNAME}@${STAGING_SSH_HOSTNAME}:${STAGING_PROJECT_LOCATION}/orse/orse_d/web/sites/default/files/ ../web/sites/default/files
        sudo chmod -R 775 ../web
        sudo chown -R www-data:www-data ../web
        return 0
    fi
    return 1
}


set -o allexport
source ../.env
set +o allexport

$1 $2
