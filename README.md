#Booking Layer test

## Running
Run the container
`./vendor/bin/sail up`
Migrate database
`./vendor/bin/sail php artisan migrate`
Seed database
`./vendor/bin/sail php artisan db:seed --class=RoomSeeder`
`./vendor/bin/sail php artisan db:seed --class=AssigmentDataSeeder`

##Testing
Migrate test database
` ./vendor/bin/sail php artisan migrate:fresh --env=testing`
Execute tests
` ./vendor/bin/sail php artisan test`
Generate tests coverage
`./vendor/bin/sail php artisan test --coverage-html ./coverage`
