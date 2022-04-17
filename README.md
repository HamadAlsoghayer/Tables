
## TABLES (Laravel based Project)

Tables is a REST api application created for part of a screening test.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## POSTMAN COLLECTION

This workspace contains all the endpoints and the env variables
`Tables.postman_collection.json` the exported postman collection
`Local.postman_environment.json` the exported postman environment
https://www.postman.com/cryosat-observer-68000874/workspace/tables/overview

## Installation

prerequisites:
* composer installed
* docker installed

1- clone repo
2- run command composer install
3- Copy the contents of .env.example to .env
4- run command ./vendor/bin/sail run php artisan key:generate
5- run command ./vendor/bin/sail up
6- run command ./vendor/bin/sail artisan migrate --seed

application should be up and running 

after this initial set up, anytime you need to start the app 'docker-compose up' command  will be able to start everything.






## Algorithm for choosing required table

 requests takes group size
 query all tables with seats equal to size or greater -> order by ascending -> fetch the first result (it will match the smallest required seats that exists) -> $seats
 query all tables with seats = $seats and fetch available time slots.

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
