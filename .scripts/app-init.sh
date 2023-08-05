#!/bin/bash
set -e

echo "Initializing app ..."

cp .env.example .env

php artisan key:generate
