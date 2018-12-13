# UlosZone Backend Engine

## Prerequisite
- PHP 7.2.11
- Composer 1.6.3
- Maria DB 5.7.24

## Installation
- Clone this project, run `git clone https://github.com/ecojuntak/fornitori.git`
- Run `composer install` to download all PHP dependencies
- Run `npm install` to download all the Node JS dependencies
- Copy `.env.example` to `.env`, if you using Unix terminal just run `cp .env.example .env`
- Run `php artisan key:generate` to generate `APP_KEY` in `.env` file
- Run `php artisan jwt:secret` to generate `JWT_SECRET` in `.env` file

## Migration
- Run `php artisan migrate --seed` to generate the database schema and run the database seeder

## Run
- Run `php artisan serve` to start the PHP server