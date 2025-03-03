# Customer Portal

## Requirements

### Backend
+ PHP ^7.3 with: _(newest is preferred; compatible with 8.x)_
    + bcmath
    + bz2
    + curl
    + gd
    + gmp
    + intl
    + sodium
    + openssl
    + mysqli
    + pdo_mysql
    + zlib
    + raphf
    + propro
    + http
    + iconv
    + json
    + xdebug (just for debugging)
+ MySql ^8.x _(newest is preferred)_
+ GNU bash ^5.x (or similar)
+ Crontab ^1.4
+ Composer ^1.9
+ Nginx ^1.16 (just on production) _(newest is preferred)_
+ Java ^11 (for elastic search)
+ GCC ^9.x
+ ElasticSearch ^7.x

### Frontend
+ Node ^12.18.x _(newest is preferred)_ (for build the app)
+ A modern web browser with HTML5, ES6 and CSS3 support. It's best to have one of the following:
    + Chromium ^85.x
    + Firefox ^78.x
    + Opera ^69.x
    + Safari ^13.x
    + IOS Safari ^13.x
    + Chrome for Android ^84.x
    + Android Browser ^81.x
    + Edge ^84.x _(well... if you cannot afford any good one...)_

__IE is NOT (and never will be) supported.__ <small> I really don't like Microsoft (and Windows).</small>

### Server
+ min. 8 GB RAM (because ElasticSearch needs minimum 2 GB to run and server works good with 4 GB)

### Running a project with docker
```
$ docker-compose up -d --build
$ chmod -R 777 storage
$ chmod -R 777 bootstrap/cache
$ cp .env.example .env
$ docker-compose run --rm --no-deps server composer install --optimize-autoloader --no-dev
$ docker-compose run --rm --no-deps server php artisan key:generate
$ docker-compose run --rm --no-deps server php artisan passport:install
$ SAVE - PASSPORT_API_CLIENT_ID and PASSPORT_API_CLIENT_SECRET to .env
$ docker-compose up -d
$ docker exec -it portal_mysql /bin/bash
$ cd /usr/backup
$ mysql -u root -p toflow_cp < creoworkflow_portal.sql
$ ENTER password - toor
$ exit;
$ npm i
$ npm run watch
```
# Credential
```
URL: http:\\localhost:8082
Login: Core
Password: 123qwerty456
```
