#!/usr/bin/env bash

openssl aes-256-cbc -d -in .env/secure.production.env -out .env/.production -k $ARBOR_ENCRYPT_KEY -md sha256
openssl aes-256-cbc -d -in .env/secure.staging.env -out .env/.staging -k $ARBOR_ENCRYPT_KEY -md sha256
openssl aes-256-cbc -d -in "config/configuration.staging" -out "config/default.conf.php" -k $ARBOR_ENCRYPT_KEY -md sha256
