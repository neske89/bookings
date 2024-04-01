## Running
Install vendor directory:
```docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```
Copy and setup .env file with Laravel Sail Default Config

Run the container
`./vendor/bin/sail up`

Migrate database
`./vendor/bin/sail php artisan migrate`

Seed database
`./vendor/bin/sail php artisan db:seed --class=RoomSeeder`
`./vendor/bin/sail php artisan db:seed --class=AssigmentDataSeeder`

## Testing

Migrate test database
` ./vendor/bin/sail php artisan migrate:fresh --env=testing`

Execute tests
` ./vendor/bin/sail php artisan test`

Generate tests coverage
`./vendor/bin/sail php artisan test --coverage-html ./coverage`

## Manual Testing

Postman collection is under `./doc/` directory
=
