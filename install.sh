#!/bin/bash

echo "Install composer dependencies to get Sail installed"
composer install

echo "Copy .env file over"
cp .env.example .env

echo "Generate application key"
php artisan key:generate

echo "Start Sail containers"
./vendor/bin/sail up -d

echo "Migrate database schema"
./vendor/bin/sail artisan migrate

echo "Import legacy products data (this will take a few moments)"
./vendor/bin/sail artisan db:seed

echo "Image handling requires an s3 (or compatible) bucket setting up, otherwise"
echo "Application available at http://localhost:8000 register a user and away you go!"
