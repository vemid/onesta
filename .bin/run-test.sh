#!/usr/bin/env bash

set -e

die () {
    echo; echo "ERROR: $1"; echo; exit 1
}

aws configure set region eu-west-2

JQ="jq --raw-output --exit-status"
COMPOSER_TOKEN=$( aws ssm get-parameter --name /arbor/composer-token --with-decryption | ${JQ} .Parameter.Value )
GITHUB_TOKEN=$( aws ssm get-parameter --name /arbor/github-token --with-decryption | ${JQ} .Parameter.Value )
COMPOSER_USER=$( aws ssm get-parameter --name /arbor/composer-user --with-decryption | ${JQ} .Parameter.Value )

curl -sS https://getcomposer.org/installer | php
sudo php composer.phar config -g github-oauth.github.com ${GITHUB_TOKEN}
sudo php composer.phar config --global --auth http-basic.repo.packagist.com ${COMPOSER_USER} ${COMPOSER_TOKEN}
sudo php composer.phar self-update
sudo php composer.phar install

sudo php composer.phar test

echo "Tests finished!"