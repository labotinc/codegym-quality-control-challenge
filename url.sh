#! /usr/bin/sh

REPOSITORY_DIR_PATH=${PWD}
ENV_FILE_PATH=${REPOSITORY_DIR_PATH}/.env

ip=`ec2-metadata -v | sed -e 's/public-ipv4: //g'`
echo "WEBサーバを起動しました
アプリケーションURL：http://"${ip}":20680/
phpMyAdminURL：http://"${ip}":20681/"

cat ${ENV_FILE_PATH}
