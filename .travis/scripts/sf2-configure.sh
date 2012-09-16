#!/bin/sh

BASEDIR=$(dirname $0)
BASEDIR=$(readlink -f "$BASEDIR/..")
ROOTDIR=$(readlink -f "$BASEDIR/..")

DBNAME="myapp_test"
if [ "$1" ]
then
    DBNAME="$1"
fi

echo "---> Starting $(tput bold ; tput setaf 2)Symfony2 project configuration$(tput sgr0)"
echo "---> MySQL database name : $(tput bold ; tput setaf 3)$DBNAME$(tput sgr0)"

sed s/%database_name%/$DBNAME/ "$ROOTDIR/app/config/parameters.ini-dist" \
    | sed s/%database_login%/root/ | sed s/%database_password%// \
    | sed s/%secret%/ThisTokenIsNotSoSecret/ \
    | sed s/%rabbitmq_host%// \
    | sed s/%rabbitmq_user%// \
    | sed s/%rabbitmq_password%// \
    | sed s/%piwik_enabled%/false/ \
    | sed s/%piwik_host%// \
    | sed s/%piwik_site_id%// \
    | sed s/%pusher_host%// \
    | sed s/%pusher_port%// \
    | sed s/%pusher_key%// \
    | sed s/%pusher_secret%// \
    | sed s/%pusher_channel%// \