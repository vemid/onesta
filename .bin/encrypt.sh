#!/usr/bin/env bash

openssl aes-256-cbc -e -in .env/.production -out .env/secure.production.env -k $ARBOR_ENCRYPT_KEY -md sha256
openssl aes-256-cbc -e -in .env/.staging -out .env/secure.staging.env -k $ARBOR_ENCRYPT_KEY -md sha256