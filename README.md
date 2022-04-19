
## TABLES (Laravel based Project)

Tables is a REST api application created for part of a screening test. 
Tables provides features that help restaurants manage their reservations without conflicts.


## POSTMAN COLLECTION

`Tables.postman_collection.json` the exported postman collection.

`Local.postman_environment.json` the exported postman environment.

check https://www.postman.com/cryosat-observer-68000874/workspace/tables/overview for latest postman workspace.
This workspace contains all the endpoints and the env variables.
## Installation

prerequisites:
* PHP 7.2.5 or higher installed
* composer installed
* docker installed

steps:
1. clone repo
2. run command composer install
3. Copy the contents of .env.example to .env
4. run command ./vendor/bin/sail run php artisan key:generate
5. run command ./vendor/bin/sail up
6. run command ./vendor/bin/sail artisan migrate --seed


## Laravel Sail
Sail is a wrapper for docker-compose that provides the appropriate configurations and makes buiding the app easy.

application should be up and running.

after this initial set up, anytime you need to start the app `'docker-compose up'` command  will be able to start everything(try using `sail up` instead).

## Automated Testing 
### `'./vendor/bin/sail test'`
This commands runs all unit and feature tests that have been implemented. 

#### NOTE: I implmented a second pgsql container dedicated for automated testing to avoid wiping development db.
check .env.testing for testing configs, container name pgsql_test.
## Authentication
User authentication is done through issuing and verifying api tokens. Laravel Sanctum was used for handling tokens, it is closely similar to JWTs.

## Caching
Used redis container for caching.

## Algorithm for choosing required table

 1. requests takes group `size`.
 2. query all `tables` with `seats` equal to size or greater -> order by ascending -> fetch the first result (it will match the  smallest required seats that exists) -> `$seats`.
 3. query all `tables` with `seats` = `$seats` and fetch available time slots.


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
