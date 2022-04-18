
## TABLES (Laravel based Project)

Tables is a REST api application created for part of a screening test.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## POSTMAN COLLECTION

`Tables.postman_collection.json` the exported postman collection.

`Local.postman_environment.json` the exported postman environment.

https://www.postman.com/cryosat-observer-68000874/workspace/tables/overview
This workspace contains all the endpoints and the env variables.
## Installation

prerequisites:
* composer installed
* docker installed

steps:
1. clone repo
2. run command composer install
3. Copy the contents of .env.example to .env
4. run command ./vendor/bin/sail run php artisan key:generate
5. run command ./vendor/bin/sail up
6. run command ./vendor/bin/sail artisan migrate --seed

application should be up and running 

after this initial set up, anytime you need to start the app `'docker-compose up'` command  will be able to start everything.






## Algorithm for choosing required table

 1. requests takes group `size` 
 2. query all `tables` with `seats` equal to size or greater -> order by ascending -> fetch the first result (it will match the  smallest required seats that exists) -> `$seats`
 3. query all `tables` with `seats` = `$seats` and fetch available time slots.


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
