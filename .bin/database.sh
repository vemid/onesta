#!/usr/bin/env bash

set -e
set -u

die () {
    echo; echo "ERROR: $1"; echo; exit 1
}

cd ${WORK_DIR}

echo "Db host: ${DB_HOST}"
echo "Db user: ${DB_USER}"
echo "Db Name: ${DB_NAME}"
echo "env: ${ENV}"

# Setup Flyway for migrations
# sed -i -e "s/flyway.url=/flyway.url=${DB_HOST}/g" flyway-5.2.4/conf/flyway.conf
# sed -i -e "s/# flyway.user=/flyway.user=${DB_USER}/g" flyway-5.2.4/conf/flyway.conf
# sed -i -e "s/# flyway.password=/flyway.password=${DB_PASSWORD}/g" flyway-5.2.4/conf/flyway.conf

FLYWAY_URL=jdbc:mysql://${DB_HOST}:$DB_PORT/$DB_NAME

flyway -locations=filesystem:./sql -placeholders.seed="true" -placeholders.env="${ENV}" migrate
